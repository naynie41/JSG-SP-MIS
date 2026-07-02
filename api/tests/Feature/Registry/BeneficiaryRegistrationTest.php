<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Manual individual registration + owner-scoped CRUD (PRD FR-REG-01/04/05,
 * FR-OWN-01/02): ownership stamping, provenance, validation, scoping, RBAC, audit.
 */
class BeneficiaryRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        // Created up front so an Auditable model is never created between
        // sub-requests with a stale Auth::user (see RbacTest note).
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['partnerA'] = $this->user($this->mdaA, RoleKey::DevelopmentPartner);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function tokenFor(string $key): string
    {
        return $this->users[$key]->createToken('test')->plainTextToken;
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Sadiq',
            'last_name' => 'Bello',
            'date_of_birth' => '1990-05-10',
            'gender' => 'male',
            'lga' => 'dutse',
            'ward' => 'Ward 3',
            'nin' => '22233344455',
            'phone' => '08031234567',
        ], $overrides);
    }

    public function test_officer_can_register_an_individual_owned_by_their_mda_with_provenance_and_audit(): void
    {
        $this->withToken($this->tokenFor('officerA'))
            ->postJson('/api/v1/beneficiaries', $this->validPayload())
            ->assertCreated()
            ->assertJsonPath('data.owner_mda_id', $this->mdaA->id)
            ->assertJsonPath('data.registration_source', RegistrationSource::Manual->value)
            ->assertJsonPath('data.first_name', 'Sadiq');

        $this->assertDatabaseHas('beneficiaries', [
            'owner_mda_id' => $this->mdaA->id,
            'registration_source' => RegistrationSource::Manual->value,
            'first_name' => 'Sadiq',
            'nin' => '22233344455',
        ]);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.created',
            'entity_type' => 'beneficiary',
        ]);
    }

    public function test_identifiers_are_normalised_before_save(): void
    {
        $this->withToken($this->tokenFor('officerA'))
            ->postJson('/api/v1/beneficiaries', $this->validPayload(['nin' => '222-333-444-55']))
            ->assertCreated();

        $this->assertDatabaseHas('beneficiaries', ['nin' => '22233344455']);
    }

    public function test_registration_rejects_missing_and_invalid_fields_with_standard_envelope(): void
    {
        $this->withToken($this->tokenFor('officerA'))
            ->postJson('/api/v1/beneficiaries', [
                'gender' => 'martian',
                'nin' => '123',
                'lga' => 'not_a_real_lga',
                'date_of_birth' => '2999-01-01',
            ])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonStructure(['error' => ['code', 'message', 'details' => [['field', 'message']]]])
            ->assertJsonFragment(['field' => 'first_name'])
            ->assertJsonFragment(['field' => 'last_name'])
            ->assertJsonFragment(['field' => 'ward'])
            ->assertJsonFragment(['field' => 'gender'])
            ->assertJsonFragment(['field' => 'lga'])
            ->assertJsonFragment(['field' => 'nin'])
            ->assertJsonFragment(['field' => 'date_of_birth']);
    }

    public function test_registration_rejects_duplicate_nin(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'nin' => '99988877766']);

        $this->withToken($this->tokenFor('officerA'))
            ->postJson('/api/v1/beneficiaries', $this->validPayload(['nin' => '99988877766']))
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonFragment(['field' => 'nin']);
    }

    public function test_registration_requires_beneficiary_create_permission(): void
    {
        // Development partner holds beneficiary.view but not beneficiary.create.
        $this->withToken($this->tokenFor('partnerA'))
            ->postJson('/api/v1/beneficiaries', $this->validPayload())
            ->assertStatus(403);
    }

    public function test_list_is_owner_scoped(): void
    {
        Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id]);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);

        $response = $this->withToken($this->tokenFor('officerA'))
            ->getJson('/api/v1/beneficiaries')
            ->assertOk()
            ->assertJsonPath('meta.pagination.total', 2);

        foreach ($response->json('data') as $row) {
            $this->assertSame($this->mdaA->id, $row['owner_mda_id']);
        }
    }

    public function test_show_returns_owned_record_and_404s_out_of_scope(): void
    {
        $benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $benB = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);

        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/{$benA->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $benA->id);

        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/{$benB->id}")
            ->assertStatus(404);
    }

    public function test_owner_can_update_but_non_owner_cannot(): void
    {
        $benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'last_name' => 'Original']);

        $this->withToken($this->tokenFor('officerA'))
            ->patchJson("/api/v1/beneficiaries/{$benA->id}", ['last_name' => 'Updated'])
            ->assertOk()
            ->assertJsonPath('data.last_name', 'Updated');

        $this->app['auth']->forgetGuards();

        // MDA B officer holds beneficiary.edit but does not own the record → 403.
        $this->withToken($this->tokenFor('officerB'))
            ->patchJson("/api/v1/beneficiaries/{$benA->id}", ['last_name' => 'Hacked'])
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'FORBIDDEN');

        $this->assertDatabaseHas('beneficiaries', ['id' => $benA->id, 'last_name' => 'Updated']);
    }

    public function test_owner_can_soft_delete_but_non_owner_cannot(): void
    {
        $benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // Non-owner denied.
        $this->withToken($this->tokenFor('officerB'))
            ->deleteJson("/api/v1/beneficiaries/{$benA->id}")
            ->assertStatus(403);

        $this->app['auth']->forgetGuards();

        // Owner soft-deletes, and it is audited.
        $this->withToken($this->tokenFor('officerA'))
            ->deleteJson("/api/v1/beneficiaries/{$benA->id}")
            ->assertOk();

        $this->assertSoftDeleted('beneficiaries', ['id' => $benA->id]);
        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.deleted',
            'entity_type' => 'beneficiary',
            'entity_id' => $benA->id,
        ]);
    }
}
