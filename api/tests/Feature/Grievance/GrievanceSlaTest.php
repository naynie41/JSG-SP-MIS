<?php

declare(strict_types=1);

namespace Tests\Feature\Grievance;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Grievance\Jobs\EscalateOverdueGrievances;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Grievance\Models\GrievanceSlaPolicy;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Grievance SLAs + escalation (PRD FR-GRM-03): admin-editable per-category windows;
 * a scheduled sweep that flags/escalates overdue grievances (never auto-closes); and
 * notifications to the handling MDA's team + the escalation tier on breach.
 */
class GrievanceSlaTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['staffA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);   // logs + handles
        $this->users['adminA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);     // escalation tier (level 1)
        $this->users['spCoord'] = $this->user($this->mdaB, RoleKey::SpCoordination); // grievance-sla.edit

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
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

    private function log(string $category = 'payment'): string
    {
        return $this->send('staffA', 'POST', '/api/v1/grievances', [
            'category' => $category,
            'channel' => 'walk_in',
            'beneficiary_id' => $this->beneficiary->id,
            'description' => 'Did not receive the last cash transfer.',
        ])->assertCreated()->json('data.id');
    }

    public function test_sla_windows_are_admin_editable_per_category(): void
    {
        // A grievance officer cannot configure SLAs.
        $this->send('staffA', 'PUT', '/api/v1/grievance-sla-policies/payment', ['hours' => 24])->assertStatus(403);

        // SP Coordination can.
        $this->send('spCoord', 'PUT', '/api/v1/grievance-sla-policies/payment', ['hours' => 24])
            ->assertOk()->assertJsonPath('data.hours', 24)->assertJsonPath('data.category', 'payment');
        $this->assertDatabaseHas('grievance_sla_policies', ['category' => 'payment', 'hours' => 24]);

        // Unknown category is rejected.
        $this->send('spCoord', 'PUT', '/api/v1/grievance-sla-policies/bogus', ['hours' => 24])
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_CATEGORY');

        // Hours must be valid.
        $this->send('spCoord', 'PUT', '/api/v1/grievance-sla-policies/payment', ['hours' => 0])
            ->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_ERROR');

        $this->send('spCoord', 'GET', '/api/v1/grievance-sla-policies')
            ->assertOk()->assertJsonPath('data.sla_policies.0.category', 'payment');
    }

    public function test_overdue_grievance_is_flagged_and_escalated_without_auto_closing(): void
    {
        GrievanceSlaPolicy::create(['category' => 'payment', 'hours' => 24]);
        $id = $this->log();

        // Backdate so it has been open (unresolved) for 30h — past the 24h window.
        Grievance::query()->withoutGlobalScopes()->where('id', $id)->update(['created_at' => Carbon::now()->subHours(30)]);

        EscalateOverdueGrievances::dispatchSync();

        $grievance = Grievance::query()->withoutGlobalScopes()->findOrFail($id);
        $this->assertSame(1, $grievance->escalation_level);
        $this->assertNotNull($grievance->sla_breached_at);
        $this->assertSame('open', $grievance->status->value); // NEVER auto-closed
        $this->assertDatabaseHas('audit_log', ['action' => 'grievance.sla_breached', 'entity_id' => $id]);

        // The handling MDA's grievance team AND the level-1 escalation tier (its admin) are notified.
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['staffA']->id, 'type' => 'grievance.sla_breached']);
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['adminA']->id, 'type' => 'grievance.sla_breached']);

        // Re-running without further elapsed time does not escalate again.
        EscalateOverdueGrievances::dispatchSync();
        $this->assertSame(1, Grievance::query()->withoutGlobalScopes()->findOrFail($id)->escalation_level);
    }

    public function test_escalation_advances_a_tier_per_elapsed_window(): void
    {
        GrievanceSlaPolicy::create(['category' => 'payment', 'hours' => 24]);
        $id = $this->log();

        // 60h open with a 24h window → 2 windows elapsed → tier 2 (chain length 2).
        Grievance::query()->withoutGlobalScopes()->where('id', $id)->update(['created_at' => Carbon::now()->subHours(60)]);

        EscalateOverdueGrievances::dispatchSync();

        $this->assertSame(2, Grievance::query()->withoutGlobalScopes()->findOrFail($id)->escalation_level);
        // Level 2 tier is state coordination (org-wide fallback: spCoord in MDA B).
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['spCoord']->id, 'type' => 'grievance.sla_breached']);
    }

    public function test_a_grievance_within_its_window_is_not_flagged(): void
    {
        GrievanceSlaPolicy::create(['category' => 'payment', 'hours' => 72]);
        $id = $this->log();

        EscalateOverdueGrievances::dispatchSync();

        $grievance = Grievance::query()->withoutGlobalScopes()->findOrFail($id);
        $this->assertSame(0, $grievance->escalation_level);
        $this->assertNull($grievance->sla_breached_at);
    }

    public function test_a_resolved_grievance_is_not_escalated(): void
    {
        GrievanceSlaPolicy::create(['category' => 'payment', 'hours' => 24]);
        $id = $this->log();

        Grievance::query()->withoutGlobalScopes()->where('id', $id)->update([
            'created_at' => Carbon::now()->subHours(90),
            'status' => 'resolved',
            'resolved_at' => Carbon::now(),
        ]);

        EscalateOverdueGrievances::dispatchSync();

        $this->assertSame(0, Grievance::query()->withoutGlobalScopes()->findOrFail($id)->escalation_level);
    }
}
