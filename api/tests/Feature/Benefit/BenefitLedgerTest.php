<?php

declare(strict_types=1);

namespace Tests\Feature\Benefit;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Benefit ledger + manual recording + verification (PRD FR-BEN-01/02/04, §8.3):
 * records delivery (never money), against an enrolled beneficiary, with the
 * delivering MDA and provenance; verification records method + reference and
 * clearly marks stubbed methods unavailable; the per-beneficiary ledger reads
 * across MDAs subject to visibility; scoping + audit hold.
 */
class BenefitLedgerTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $programmeA;

    private Beneficiary $enrolledA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['viewer'] = $this->user($this->mdaA, RoleKey::MneOfficer); // benefit.view only
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive);

        $this->programmeA = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->enrolledA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        Enrollment::factory()->create([
            'programme_id' => $this->programmeA->id,
            'mda_id' => $this->mdaA->id,
            'beneficiary_id' => $this->enrolledA->id,
        ]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** Open the read/serve grant an accepted Service Request would create (R2.3). */
    private function grantServeAccess(Beneficiary $beneficiary, Mda $servingMda): void
    {
        $request = ServiceRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $servingMda->id,
            'to_mda_id' => $beneficiary->owner_mda_id,
            'status' => ServiceRequestStatus::Accepted,
        ]);
        BeneficiaryServiceGrant::create([
            'beneficiary_id' => $beneficiary->id,
            'mda_id' => $servingMda->id,
            'service_request_id' => $request->id,
            'granted_at' => now(),
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'beneficiary_id' => $this->enrolledA->id,
            'programme_id' => $this->programmeA->id,
            'benefit_type' => 'cash',
            'quantity' => 2,
            'unit' => 'transfers',
            'monetary_value' => 500_000, // kobo — data only
            'funding_source' => 'State budget',
            'delivery_date' => now()->toDateString(),
            'notes' => 'Q1 payout',
        ], $overrides);
    }

    public function test_officer_records_a_verified_benefit_against_an_enrolled_beneficiary(): void
    {
        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['verification_method' => 'field_confirmation']))
            ->assertCreated()
            ->assertJsonPath('data.beneficiary_id', $this->enrolledA->id)
            ->assertJsonPath('data.mda_id', $this->mdaA->id) // delivering MDA
            ->assertJsonPath('data.status', 'verified')
            ->assertJsonPath('data.verification_method', 'field_confirmation')
            ->assertJsonPath('data.monetary_value', 500_000);

        $benefit = Benefit::query()->firstOrFail();
        $this->assertSame($this->users['officerA']->id, $benefit->recorded_by);
        $this->assertSame($this->users['officerA']->id, $benefit->verified_by);
        $this->assertNotNull($benefit->verification_reference);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'benefit.created',
            'entity_id' => $benefit->id,
            'actor_id' => $this->users['officerA']->id,
        ]);
    }

    public function test_recording_without_verification_is_recorded_not_verified(): void
    {
        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.status', 'recorded')
            ->assertJsonPath('data.verification_method', 'none');
    }

    public function test_monetary_value_is_recorded_as_data_only(): void
    {
        // The ledger stores the value; no money is moved (§2.3) — the benefit row
        // is the sole artifact, with the value exactly as supplied.
        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['monetary_value' => 1_234_500]))
            ->assertCreated()
            ->assertJsonPath('data.monetary_value', 1_234_500);

        $this->assertSame(1, Benefit::query()->count());
        $this->assertSame(1_234_500, Benefit::query()->firstOrFail()->monetary_value);
    }

    public function test_cannot_record_for_a_beneficiary_not_enrolled(): void
    {
        $notEnrolled = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['beneficiary_id' => $notEnrolled->id]))
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'NOT_ENROLLED');
    }

    public function test_signature_verification_requires_a_reference(): void
    {
        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['verification_method' => 'signature']))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'verification_reference']);

        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['verification_method' => 'signature', 'verification_reference' => 'sig-doc-123']))
            ->assertCreated()
            ->assertJsonPath('data.status', 'verified')
            ->assertJsonPath('data.verification_reference', 'sig-doc-123');
    }

    public function test_stubbed_methods_are_unavailable_and_nothing_is_recorded(): void
    {
        foreach (['otp', 'biometric'] as $method) {
            $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['verification_method' => $method]))
                ->assertStatus(422)
                ->assertJsonPath('error.code', 'VERIFICATION_UNAVAILABLE');
        }

        // Nothing was persisted (the record is rolled back with the failed verification).
        $this->assertSame(0, Benefit::query()->count());

        // Verifying an already-recorded delivery with a stub is also rejected.
        $benefit = Benefit::factory()->create([
            'programme_id' => $this->programmeA->id,
            'mda_id' => $this->mdaA->id,
            'beneficiary_id' => $this->enrolledA->id,
        ]);
        $this->send('officerA', 'POST', "/api/v1/benefits/{$benefit->id}/verify", ['verification_method' => 'otp'])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VERIFICATION_UNAVAILABLE');
        $this->assertSame('recorded', $benefit->fresh()->status->value);
    }

    public function test_verify_an_existing_benefit_and_only_the_delivering_mda_may(): void
    {
        $benefit = Benefit::factory()->create([
            'programme_id' => $this->programmeA->id,
            'mda_id' => $this->mdaA->id,
            'beneficiary_id' => $this->enrolledA->id,
        ]);

        // A different MDA cannot verify.
        $this->send('officerB', 'POST', "/api/v1/benefits/{$benefit->id}/verify", ['verification_method' => 'field_confirmation'])
            ->assertStatus(403);

        // The delivering MDA verifies.
        $this->send('officerA', 'POST', "/api/v1/benefits/{$benefit->id}/verify", ['verification_method' => 'field_confirmation'])
            ->assertOk()
            ->assertJsonPath('data.status', 'verified')
            ->assertJsonPath('data.verified_by', $this->users['officerA']->id);
    }

    public function test_serving_mda_delivers_to_a_non_owned_beneficiary_with_authorization_without_taking_ownership(): void
    {
        // Beneficiary owned by MDA B, enrolled by MDA A in MDA A's programme, with
        // an accepted Service Request authorizing MDA A to serve it (FR-BEN-06).
        $served = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);
        Enrollment::factory()->create(['programme_id' => $this->programmeA->id, 'mda_id' => $this->mdaA->id, 'beneficiary_id' => $served->id]);
        $this->grantServeAccess($served, $this->mdaA);

        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['beneficiary_id' => $served->id]))
            ->assertCreated()
            ->assertJsonPath('data.mda_id', $this->mdaA->id);

        // Ownership is unchanged; the cross-MDA authorization basis is audited.
        $this->assertSame($this->mdaB->id, $served->fresh()->owner_mda_id);
        $this->assertDatabaseHas('audit_log', [
            'action' => 'benefit.delivery_authorized',
            'actor_id' => $this->users['officerA']->id,
        ]);
    }

    public function test_non_owner_cannot_record_without_an_accepted_authorization(): void
    {
        // Beneficiary owned by MDA B, enrolled by MDA A — but NO accepted Service
        // Request / Referral → recording is refused (FR-BEN-06), nothing persisted.
        $served = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);
        Enrollment::factory()->create(['programme_id' => $this->programmeA->id, 'mda_id' => $this->mdaA->id, 'beneficiary_id' => $served->id]);

        $this->send('officerA', 'POST', '/api/v1/benefits', $this->payload(['beneficiary_id' => $served->id]))
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'DELIVERY_NOT_AUTHORIZED');

        $this->assertSame(0, Benefit::query()->withoutGlobalScopes()->count());
        $this->assertSame($this->mdaB->id, $served->fresh()->owner_mda_id);
    }

    public function test_per_beneficiary_ledger_is_a_complete_cross_mda_history_subject_to_visibility(): void
    {
        // A beneficiary owned by MDA B, served a benefit by MDA A.
        $served = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);
        Benefit::factory()->create(['beneficiary_id' => $served->id, 'programme_id' => $this->programmeA->id, 'mda_id' => $this->mdaA->id]);
        // A third MDA with no relationship to the beneficiary.
        $this->users['officerC'] = $this->user(Mda::factory()->create(['name' => 'MDA C']), RoleKey::MdaOfficer);

        // Owner MDA sees the benefit delivered by another MDA.
        $this->send('officerB', 'GET', "/api/v1/beneficiaries/{$served->id}/benefits")->assertOk()->assertJsonCount(1, 'data');
        // The delivering MDA sees it too.
        $this->send('officerA', 'GET', "/api/v1/beneficiaries/{$served->id}/benefits")->assertOk()->assertJsonCount(1, 'data');
        // Oversight sees it.
        $this->send('oversight', 'GET', "/api/v1/beneficiaries/{$served->id}/benefits")->assertOk()->assertJsonCount(1, 'data');
        // An unrelated MDA may not view the ledger.
        $this->send('officerC', 'GET', "/api/v1/beneficiaries/{$served->id}/benefits")->assertStatus(403);
    }

    public function test_ledger_list_is_scoped_to_the_delivering_mda(): void
    {
        Benefit::factory()->create(['beneficiary_id' => $this->enrolledA->id, 'programme_id' => $this->programmeA->id, 'mda_id' => $this->mdaA->id]);
        Benefit::factory()->create(['mda_id' => $this->mdaB->id]);

        $this->send('officerA', 'GET', '/api/v1/benefits')->assertOk()->assertJsonCount(1, 'data');
        $this->send('officerB', 'GET', '/api/v1/benefits')->assertOk()->assertJsonCount(1, 'data');
        $this->send('oversight', 'GET', '/api/v1/benefits')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_recording_requires_the_create_permission(): void
    {
        $this->send('viewer', 'POST', '/api/v1/benefits', $this->payload())->assertStatus(403);
    }
}
