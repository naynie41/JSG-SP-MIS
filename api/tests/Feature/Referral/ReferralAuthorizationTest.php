<?php

declare(strict_types=1);

namespace Tests\Feature\Referral;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Referral → delivery authorization + ledger reconciliation (PRD FR-REF-02/03,
 * FR-BEN-06). An accepted referral authorizes the receiving MDA to serve via the
 * Phase 4 enroll/deliver gate WITHOUT any Service Request and WITHOUT moving
 * ownership; the outcome reconciles with the ledger.
 */
class ReferralAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $fromMda; // owner / originating

    private Mda $toMda;   // receiving

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    private Programme $toProgramme; // owned by the receiving MDA

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->fromMda = Mda::factory()->create(['name' => 'From MDA']);
        $this->toMda = Mda::factory()->create(['name' => 'To MDA']);

        $this->users['from'] = $this->user($this->fromMda, RoleKey::MdaOfficer);
        $this->users['to'] = $this->user($this->toMda, RoleKey::MdaOfficer);

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->fromMda->id]);
        $this->toProgramme = Programme::factory()->individual()->create(['owner_mda_id' => $this->toMda->id, 'eligibility' => null]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function refer(): string
    {
        return $this->send('from', 'POST', '/api/v1/referrals', [
            'beneficiary_id' => $this->beneficiary->id,
            'to_mda_id' => $this->toMda->id,
            'need' => 'Cash transfer',
        ])->assertCreated()->json('data.id');
    }

    private function enroll(): TestResponse
    {
        return $this->send('to', 'POST', "/api/v1/programmes/{$this->toProgramme->id}/enrollments", ['beneficiary_id' => $this->beneficiary->id]);
    }

    private function deliver(): TestResponse
    {
        return $this->send('to', 'POST', '/api/v1/benefits', [
            'beneficiary_id' => $this->beneficiary->id,
            'programme_id' => $this->toProgramme->id,
            'benefit_type' => 'cash',
            'monetary_value' => 750_000,
            'delivery_date' => now()->toDateString(),
        ]);
    }

    public function test_delivery_is_refused_until_the_referral_is_accepted(): void
    {
        $this->refer(); // pending — not yet accepted

        // The Phase 4 enroll gate refuses a non-owner without an accepted authorization.
        $this->enroll()->assertStatus(409)->assertJsonPath('error.code', 'SERVICE_REQUEST_REQUIRED');
    }

    public function test_accepted_referral_authorizes_phase4_delivery_without_ownership_change_or_service_request(): void
    {
        $id = $this->refer();
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/accept")->assertOk()->assertJsonPath('data.status', 'accepted');

        // Now the receiving MDA can enroll + deliver via the existing Phase 4 flow.
        $this->enroll()->assertCreated()->assertJsonPath('data.mda_id', $this->toMda->id);
        $this->deliver()->assertCreated()->assertJsonPath('data.mda_id', $this->toMda->id);

        // Authorization basis is the REFERRAL (not a service request).
        $this->assertDatabaseHas('audit_log', ['action' => 'benefit.delivery_authorized']);
        $entry = AuditLog::query()->where('action', 'benefit.delivery_authorized')->latest('created_at')->firstOrFail();
        $this->assertSame('referral', $entry->after['basis']);

        // Ownership is unchanged throughout.
        $this->assertSame($this->fromMda->id, $this->beneficiary->fresh()->owner_mda_id);

        // The referral path created NO Service Request and NO serve grant — distinct.
        $this->assertSame(0, ServiceRequest::query()->withoutGlobalScopes()->count());
        $this->assertSame(0, BeneficiaryServiceGrant::query()->withoutGlobalScopes()->count());
        $this->assertSame(1, Referral::query()->withoutGlobalScopes()->count());
    }

    public function test_outcome_is_recorded_and_reconciles_with_the_ledger(): void
    {
        $id = $this->refer();
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/accept")->assertOk();
        $this->send('to', 'POST', "/api/v1/referrals/{$id}/start")->assertOk()->assertJsonPath('data.status', 'in_progress');

        $this->enroll()->assertCreated();
        $benefitId = $this->deliver()->assertCreated()->json('data.id');

        // Complete with an outcome → it reconciles with the delivered benefit.
        $response = $this->send('to', 'POST', "/api/v1/referrals/{$id}/complete", ['outcome' => 'Beneficiary enrolled and paid'])
            ->assertOk()
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.outcome', 'Beneficiary enrolled and paid')
            ->assertJsonPath('data.ledger.benefit_count', 1)
            ->assertJsonPath('data.ledger.benefit_value_total', 750_000);

        $this->assertContains($benefitId, $response->json('data.ledger.benefit_ids'));
        $this->assertSame(1, Benefit::query()->withoutGlobalScopes()->count());
        // Ownership still unchanged after completion.
        $this->assertSame($this->fromMda->id, $this->beneficiary->fresh()->owner_mda_id);
    }
}
