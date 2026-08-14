<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Support\IdentifierHasher;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Inbound REST registration intake (PRD FR-REG-02, source = "api"): shared
 * validation, provenance/ownership stamping, authentication, permission, and
 * rate limiting.
 */
class ApiIntakeTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->users['officer'] = $this->user(RoleKey::MdaAdmin);
        $this->users['partner'] = $this->user(RoleKey::DevelopmentPartner);
    }

    private function user(RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function tokenFor(string $key): string
    {
        return $this->users[$key]->createToken('test')->plainTextToken;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Amina',
            'last_name' => 'Sadiq',
            'date_of_birth' => '1990-01-01',
            'gender' => 'female',
            'lga' => 'dutse',
            'ward' => 'Ward 1',
            'nin' => '22200000011',
            'phone' => '08030000001',
            'original_record_id' => 'PARTNER-SYS-4821',
        ], $overrides);
    }

    public function test_api_submission_creates_beneficiary_with_api_provenance(): void
    {
        $this->withToken($this->tokenFor('officer'))
            ->postJson('/api/v1/beneficiaries/intake', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.owner_mda_id', $this->mda->id)
            ->assertJsonPath('data.registration_source', RegistrationSource::Api->value)
            ->assertJsonPath('data.first_name', 'Amina');

        // NIN is encrypted at rest — the row is located by its deterministic hash
        // and the stored ciphertext must decrypt back to the submitted value.
        $this->assertDatabaseHas('beneficiaries', [
            'owner_mda_id' => $this->mda->id,
            'registration_source' => RegistrationSource::Api->value,
            'original_record_id' => 'PARTNER-SYS-4821',
            'nin_hash' => IdentifierHasher::hash('22200000011'),
        ]);
        $stored = Beneficiary::query()->where('original_record_id', 'PARTNER-SYS-4821')->firstOrFail();
        $this->assertSame('22200000011', $stored->nin);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.created']);
    }

    public function test_invalid_submission_is_rejected_with_standard_envelope(): void
    {
        $this->withToken($this->tokenFor('officer'))
            ->postJson('/api/v1/beneficiaries/intake', [
                'first_name' => 'NoId',
                'gender' => 'martian',
            ])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonStructure(['error' => ['code', 'message', 'details' => [['field', 'message']]]])
            ->assertJsonFragment(['field' => 'last_name'])
            ->assertJsonFragment(['field' => 'original_record_id'])
            ->assertJsonFragment(['field' => 'gender'])
            ->assertJsonFragment(['field' => 'date_of_birth']);

        $this->assertDatabaseCount('beneficiaries', 0);
    }

    /* ------------------------------------------------------- household grouping */

    public function test_intake_forms_a_household_from_the_canonical_household_ref(): void
    {
        // `household_ref` is the name the file pipeline uses; the intake endpoint
        // shipped with `household_id`. Both must reach the same household.
        $this->withToken($this->tokenFor('officer'))
            ->postJson('/api/v1/beneficiaries/intake', [
                ...$this->payload(),
                'original_record_id' => 'REF-1',
                'household_ref' => 'HH-77',
                'household_head' => true,
            ])
            ->assertStatus(201);

        $household = Household::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame('HH-77', $household->original_record_id);
        $this->assertSame($this->mda->id, $household->owner_mda_id);
        $this->assertSame(RegistrationSource::Api, $household->registration_source);
        $this->assertNotNull($household->head_beneficiary_id);
    }

    public function test_the_two_household_field_names_are_the_same_field(): void
    {
        $token = $this->tokenFor('officer');

        $this->withToken($token)->postJson('/api/v1/beneficiaries/intake', [
            ...$this->payload(),
            'original_record_id' => 'REF-1', 'nin' => '22200000041', 'household_id' => 'HH-88',
        ])->assertStatus(201);

        $this->withToken($token)->postJson('/api/v1/beneficiaries/intake', [
            ...$this->payload(),
            'original_record_id' => 'REF-2', 'nin' => '22200000042', 'household_ref' => 'HH-88',
        ])->assertStatus(201);

        // One household, two members — an integrator using either name lands in the
        // same place rather than splitting one family across two records.
        $this->assertSame(1, Household::query()->withoutGlobalScopes()->count());
        $this->assertSame(2, HouseholdMembership::query()->withoutGlobalScopes()->count());
    }

    public function test_intake_requires_authentication(): void
    {
        $this->postJson('/api/v1/beneficiaries/intake', $this->payload())
            ->assertStatus(401)
            ->assertJsonPath('error.code', 'UNAUTHENTICATED');
    }

    public function test_intake_requires_create_permission(): void
    {
        // Development partner has beneficiary.view but not beneficiary.create.
        $this->withToken($this->tokenFor('partner'))
            ->postJson('/api/v1/beneficiaries/intake', $this->payload())
            ->assertStatus(403);
    }

    public function test_intake_is_rate_limited(): void
    {
        $token = $this->tokenFor('officer');

        // The limiter allows 60/min; the 61st call is throttled. Invalid bodies
        // still count against the limit (throttle runs before validation).
        for ($i = 0; $i < 60; $i++) {
            $this->withToken($token)->postJson('/api/v1/beneficiaries/intake', [])->assertStatus(422);
        }

        $this->withToken($token)
            ->postJson('/api/v1/beneficiaries/intake', [])
            ->assertStatus(429);
    }
}
