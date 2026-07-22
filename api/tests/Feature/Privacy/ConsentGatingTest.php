<?php

declare(strict_types=1);

namespace Tests\Feature\Privacy;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Processing-consent gate (NFR-PRV-01): when the DPO switches processing consent ON,
 * recording a new intervention requires the subject's data-processing consent, and the
 * capture/withdraw lifecycle flips the gate. The gate is OFF by default so ordinary
 * operations are never silently blocked.
 */
class ConsentGatingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private User $admin;

    private Programme $programme;

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->admin = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->programme = Programme::factory()->individual()->create();
        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
        Enrollment::factory()->create([
            'programme_id' => $this->programme->id,
            'mda_id' => $this->mda->id,
            'beneficiary_id' => $this->beneficiary->id,
        ]);
    }

    private function send(string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->admin->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function recordBenefit(): TestResponse
    {
        return $this->send('POST', '/api/v1/benefits', [
            'beneficiary_id' => $this->beneficiary->id,
            'programme_id' => $this->programme->id,
            'benefit_type' => 'cash',
            'monetary_value' => 500_000,
            'delivery_date' => now()->toDateString(),
        ]);
    }

    public function test_processing_is_allowed_when_the_gate_is_off_by_default(): void
    {
        // No processing consent captured, gate off (default) → recording succeeds.
        $this->recordBenefit()->assertCreated();
    }

    public function test_processing_is_blocked_when_the_gate_is_on_without_consent(): void
    {
        config(['privacy.consent.processing_requires_consent' => true]);

        $this->recordBenefit()
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'CONSENT_REQUIRED');
    }

    public function test_capturing_processing_consent_opens_the_gate_and_withdrawing_closes_it(): void
    {
        config(['privacy.consent.processing_requires_consent' => true]);

        // Capture processing consent via the API (owner MDA), then processing is allowed.
        $this->send('PUT', "/api/v1/beneficiaries/{$this->beneficiary->id}/consent", [
            'status' => 'granted',
            'purpose' => 'data_processing',
            'basis' => 'Signed processing consent form',
        ])->assertOk();
        $this->assertDatabaseHas('beneficiary_consents', [
            'beneficiary_id' => $this->beneficiary->id,
            'purpose' => 'data_processing',
            'status' => 'granted',
        ]);

        $this->recordBenefit()->assertCreated();

        // Withdraw → the gate closes again (lifecycle: withdraw re-blocks processing).
        $this->send('PUT', "/api/v1/beneficiaries/{$this->beneficiary->id}/consent", [
            'status' => 'withdrawn',
            'purpose' => 'data_processing',
        ])->assertOk();
        $this->assertDatabaseHas('audit_log', ['action' => 'consent.withdrawn', 'entity_id' => $this->beneficiary->id]);

        $this->recordBenefit()
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'CONSENT_REQUIRED');
    }

    public function test_processing_consent_is_independent_of_sharing_consent(): void
    {
        config(['privacy.consent.processing_requires_consent' => true]);

        // Granting only the SHARING purpose must not satisfy the PROCESSING gate.
        $this->send('PUT', "/api/v1/beneficiaries/{$this->beneficiary->id}/consent", [
            'status' => 'granted',
            'purpose' => 'cross_mda_sharing',
        ])->assertOk();

        $this->recordBenefit()
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'CONSENT_REQUIRED');
    }
}
