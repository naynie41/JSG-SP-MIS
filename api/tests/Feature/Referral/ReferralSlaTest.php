<?php

declare(strict_types=1);

namespace Tests\Feature\Referral;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Referral\Jobs\EscalateOverdueReferrals;
use App\Domain\Referral\Models\Referral;
use App\Domain\Referral\Models\ReferralSlaPolicy;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Referral SLAs + notifications (PRD FR-REF-04/05): admin-editable SLA windows; a
 * scheduled sweep that flags/escalates overdue referrals (never auto-closes); and
 * notifications to BOTH MDAs on every transition and on SLA breach.
 */
class ReferralSlaTest extends TestCase
{
    use RefreshDatabase;

    private Mda $fromMda;

    private Mda $toMda;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->fromMda = Mda::factory()->create(['name' => 'From MDA']);
        $this->toMda = Mda::factory()->create(['name' => 'To MDA']);

        $this->users['fromOfficer'] = $this->user($this->fromMda, RoleKey::MdaOfficer);
        $this->users['toOfficer'] = $this->user($this->toMda, RoleKey::MdaOfficer);
        $this->users['toAdmin'] = $this->user($this->toMda, RoleKey::MdaAdmin); // escalation tier (mda_admin)
        $this->users['spCoord'] = $this->user($this->fromMda, RoleKey::SpCoordination); // referral-sla.edit

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->fromMda->id]);
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

    private function createReferral(): string
    {
        return $this->send('fromOfficer', 'POST', '/api/v1/referrals', [
            'beneficiary_id' => $this->beneficiary->id,
            'to_mda_id' => $this->toMda->id,
            'need' => 'Cash transfer',
        ])->assertCreated()->json('data.id');
    }

    public function test_sla_windows_are_admin_editable(): void
    {
        // A referral officer cannot configure SLAs.
        $this->send('toOfficer', 'PUT', '/api/v1/referral-sla-policies/created', ['hours' => 24])->assertStatus(403);

        // SP Coordination can.
        $this->send('spCoord', 'PUT', '/api/v1/referral-sla-policies/created', ['hours' => 24])
            ->assertOk()->assertJsonPath('data.hours', 24);
        $this->assertDatabaseHas('referral_sla_policies', ['status' => 'created', 'hours' => 24]);

        // Unknown status is rejected.
        $this->send('spCoord', 'PUT', '/api/v1/referral-sla-policies/bogus', ['hours' => 24])
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_STATUS');

        $this->send('spCoord', 'GET', '/api/v1/referral-sla-policies')
            ->assertOk()->assertJsonPath('data.sla_policies.0.status', 'created');
    }

    public function test_overdue_referral_is_flagged_and_escalated_without_auto_closing(): void
    {
        ReferralSlaPolicy::create(['status' => 'created', 'hours' => 24]);
        $id = $this->createReferral();

        // Backdate so it has sat in `created` for 30h (past the 24h window).
        Referral::query()->withoutGlobalScopes()->where('id', $id)->update(['created_at' => Carbon::now()->subHours(30)]);

        EscalateOverdueReferrals::dispatchSync();

        $referral = Referral::query()->withoutGlobalScopes()->findOrFail($id);
        $this->assertSame(1, $referral->escalation_level);
        $this->assertNotNull($referral->sla_breached_at);
        $this->assertSame('created', $referral->status->value); // NEVER auto-closed
        $this->assertDatabaseHas('audit_log', ['action' => 'referral.sla_breached', 'entity_id' => $id]);

        // Both MDAs (+ the mda_admin escalation tier) are notified of the breach.
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['fromOfficer']->id, 'type' => 'referral.sla_breached']);
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['toOfficer']->id, 'type' => 'referral.sla_breached']);
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['toAdmin']->id, 'type' => 'referral.sla_breached']);

        // Re-running without further elapsed time does not escalate again.
        EscalateOverdueReferrals::dispatchSync();
        $this->assertSame(1, Referral::query()->withoutGlobalScopes()->findOrFail($id)->escalation_level);
    }

    public function test_a_referral_within_its_window_is_not_flagged(): void
    {
        ReferralSlaPolicy::create(['status' => 'created', 'hours' => 48]);
        $id = $this->createReferral();

        EscalateOverdueReferrals::dispatchSync();

        $referral = Referral::query()->withoutGlobalScopes()->findOrFail($id);
        $this->assertSame(0, $referral->escalation_level);
        $this->assertNull($referral->sla_breached_at);
    }

    public function test_both_mdas_are_notified_on_each_status_change(): void
    {
        $id = $this->createReferral();

        // Creation notifies both MDAs.
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['fromOfficer']->id, 'type' => 'referral.created']);
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['toOfficer']->id, 'type' => 'referral.created']);

        // Acceptance notifies both MDAs.
        $this->send('toOfficer', 'POST', "/api/v1/referrals/{$id}/accept")->assertOk();
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['fromOfficer']->id, 'type' => 'referral.accepted']);
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['toOfficer']->id, 'type' => 'referral.accepted']);
    }
}
