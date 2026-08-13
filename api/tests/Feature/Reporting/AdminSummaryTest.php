<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * System Administrator console — governance summary (FR-UAM-01, FR-AUD-01). Covers the
 * ROLE gate (the console is not opened by holding a permission), KPI accuracy against
 * known fixtures, the registry snapshot, administrative alerts, and the no-PII rule for
 * recent audit activity.
 */
class AdminSummaryTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A', 'status' => 'active']);
        $mdaB = Mda::factory()->create(['name' => 'MDA B', 'status' => 'inactive']);

        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);
        $this->users['officer'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);
        $this->users['partner2'] = $this->user(null, RoleKey::DevelopmentPartner);

        // One suspended account + one active account without MFA (alert fixtures).
        $this->users['suspended'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['suspended']->update(['status' => 'suspended']);
        $this->users['officer']->update(['mfa_enabled' => false]);

        // Catalog + delivery fixtures.
        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        Programme::factory()->individual()->create(['status' => 'draft']);
        Activity::factory()->forProgramme($programme, $this->mdaA)->create(['status' => 'active']);
        Activity::factory()->forProgramme($programme, $mdaB)->create(['status' => 'archived']);

        Beneficiary::factory()->count(3)->create(['owner_mda_id' => $this->mdaA->id]);
        Household::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id]);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
            'mfa_enabled' => true,
        ]);
    }

    private function send(string $key): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)
            ->getJson('/api/v1/admin/summary');
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------------------------------------------ gating */

    public function test_only_a_system_administrator_can_open_the_console(): void
    {
        $this->send('admin')->assertOk();

        // Every other role is refused — including SP Coordination, which holds many of
        // the same permissions. The console is gated by ROLE, not by capability.
        foreach (['coordination', 'officer', 'partner'] as $key) {
            $this->send($key)->assertStatus(403)->assertJsonPath('error.code', 'FORBIDDEN');
        }
    }

    public function test_the_console_requires_authentication(): void
    {
        $this->getJson('/api/v1/admin/summary')->assertStatus(401);
    }

    /* -------------------------------------------------------------------- KPIs */

    public function test_governance_kpis_are_accurate(): void
    {
        $k = $this->send('admin')->assertOk()->json('data.kpis');

        $this->assertSame(6, $k['users_total']);          // 6 users created in setUp
        $this->assertSame(5, $k['users_active']);         // one is suspended
        $this->assertSame(1, $k['users_suspended']);
        $this->assertSame(1, $k['users_without_mfa']);    // the officer
        $this->assertSame(2, $k['mdas_registered']);
        $this->assertSame(1, $k['mdas_active']);          // MDA B is inactive
        $this->assertSame(2, $k['development_partners']);
        $this->assertSame(2, $k['programmes_catalog']);   // active + draft
        $this->assertSame(1, $k['activities_active']);    // the archived one is excluded
        $this->assertSame(3, $k['beneficiaries_registered']);
        $this->assertSame(2, $k['households_registered']);
    }

    public function test_kpis_are_platform_wide_not_mda_scoped(): void
    {
        // A beneficiary owned by another MDA still counts: the administrator's remit is
        // the whole platform, so the MDA scope is bypassed explicitly.
        $other = Mda::factory()->create();
        Beneficiary::factory()->create(['owner_mda_id' => $other->id]);

        $this->assertSame(4, $this->send('admin')->assertOk()->json('data.kpis.beneficiaries_registered'));
    }

    public function test_user_adoption_trend_spans_twelve_months_and_ends_at_the_total(): void
    {
        $trend = $this->send('admin')->assertOk()->json('data.adoption_trend');

        $this->assertCount(12, $trend);
        $this->assertSame(now()->format('Y-m'), $trend[11]['month']);
        // The running total closes on the live user count.
        $this->assertSame(6, $trend[11]['total_users']);
        $this->assertSame(6, $trend[11]['new_users']); // all six were created this month
    }

    /* -------------------------------------------------- registry + alerts */

    public function test_registry_snapshot_reports_imports_validation_and_duplicates(): void
    {
        $this->seedImport();

        $r = $this->send('admin')->assertOk()->json('data.registry');

        $this->assertSame(1, $r['imports_total']);
        $this->assertSame(1, $r['imports_failed']);
        $this->assertSame(3, $r['rows_total']);
        $this->assertSame(2, $r['rows_valid']);
        $this->assertSame(1, $r['rows_invalid']);
        $this->assertEqualsWithDelta(0.6667, $r['validation_rate'], 0.0001);
        $this->assertSame(2, $r['duplicates_surfaced']);
        $this->assertSame(1, $r['duplicates_resolved']);
        $this->assertSame(1, $r['duplicates_pending']);
        // 2 surfaced matches over 3 processed rows.
        $this->assertEqualsWithDelta(0.6667, $r['duplicate_rate'], 0.0001);
    }

    public function test_alerts_are_governance_conditions_not_infrastructure(): void
    {
        $this->seedImport();

        $alerts = collect($this->send('admin')->assertOk()->json('data.alerts'))->keyBy('id');

        $this->assertTrue($alerts->has('mfa'));         // 1 active account without MFA
        $this->assertTrue($alerts->has('suspended'));   // 1 suspended account
        $this->assertTrue($alerts->has('mdas'));        // 1 inactive MDA
        $this->assertTrue($alerts->has('imports'));     // 1 failed import
        $this->assertTrue($alerts->has('duplicates'));  // 1 unresolved match

        // No infrastructure/system-health alerts belong in this console.
        foreach ($alerts as $alert) {
            $this->assertDoesNotMatchRegularExpression(
                '/backup|queue|cpu|memory|disk|uptime|snapshot/i',
                $alert['title'].' '.$alert['detail'],
            );
        }
    }

    /* -------------------------------------------------- recent activity / PII */

    public function test_recent_activity_exposes_the_envelope_only_never_audit_payloads(): void
    {
        $beneficiary = Beneficiary::query()->withoutGlobalScopes()->firstOrFail();
        AuditLog::create([
            'actor_id' => $this->users['admin']->id,
            'actor_mda_id' => $this->mdaA->id,
            'action' => 'user.created',
            'entity_type' => Beneficiary::class,
            'entity_id' => $beneficiary->id,
            'before' => null,
            'after' => ['first_name' => 'Sensitive', 'phone' => '08012345678'],
        ]);

        $body = $this->send('admin')->assertOk();
        $recent = $body->json('data.recent_activity');

        $this->assertNotEmpty($recent);
        $this->assertSame('user.created', $recent[0]['action']);
        $this->assertSame('Beneficiary', $recent[0]['entity_type']); // class basename, not FQCN
        $this->assertSame($this->users['admin']->name, $recent[0]['actor']);
        $this->assertSame('MDA A', $recent[0]['actor_mda']);

        // The audit before/after payload never leaves the server.
        $this->assertArrayNotHasKey('before', $recent[0]);
        $this->assertArrayNotHasKey('after', $recent[0]);
        $json = (string) json_encode($body->json());
        $this->assertStringNotContainsString('Sensitive', $json);
        $this->assertStringNotContainsString('08012345678', $json);
    }

    public function test_the_console_carries_no_infrastructure_telemetry(): void
    {
        $data = $this->send('admin')->assertOk()->json('data');

        foreach (['backups', 'dashboard_snapshots', 'volumes', 'queue', 'uptime'] as $key) {
            $this->assertArrayNotHasKey($key, $data);
        }
    }

    /** An import batch with 3 rows (2 valid) and 2 surfaced matches (1 resolved). */
    private function seedImport(): void
    {
        $batchId = (string) Str::uuid();
        DB::table('import_batches')->insert([
            'id' => $batchId,
            'owner_mda_id' => $this->mdaA->id,
            'original_filename' => 'admin-test.csv',
            'stored_path' => 'imports/admin-test.csv',
            'source' => 'csv',
            'status' => 'failed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rows = [
            ['is_valid' => true, 'match_band' => 'exact', 'resolution' => 'link'],
            ['is_valid' => true, 'match_band' => 'probable', 'resolution' => null],
            ['is_valid' => false, 'match_band' => null, 'resolution' => null],
        ];
        foreach ($rows as $i => $row) {
            DB::table('import_rows')->insert([
                'id' => (string) Str::uuid(),
                'import_batch_id' => $batchId,
                'row_number' => $i + 1,
                'payload' => json_encode([]),
                'is_valid' => $row['is_valid'],
                'match_band' => $row['match_band'],
                'resolution' => $row['resolution'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
