<?php

declare(strict_types=1);

namespace Tests\Feature\Audit;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Audit & Security section of the administration console (FR-AUD-01, FR-RPT-03).
 * READ + EXPORT over the existing immutable log — no second logging path. Covers the
 * filters, request-to-serve visibility, export gating, and the no-PII/no-secret rule:
 * the projection returns changed FIELD NAMES, never recorded values.
 */
class AdminAuditLogTest extends TestCase
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
        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['officer'] = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);

        // Clear the entries the fixtures themselves produced so counts are exact.
        AuditLog::query()->delete();
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function as(string $key, string $method, string $url): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function audit(string $action, ?array $after = null, ?Carbon $at = null): AuditLog
    {
        return AuditLog::create([
            'actor_id' => $this->users['officer']->id,
            'actor_mda_id' => $this->mda->id,
            'action' => $action,
            'entity_type' => User::class,
            'entity_id' => $this->users['officer']->id,
            'after' => $after,
            'ip_address' => '10.0.0.9',
            'created_at' => $at ?? Carbon::now(),
        ]);
    }

    /** One entry per category, so filters can be asserted precisely. */
    private function seedCategories(): void
    {
        $this->audit('auth.login');                    // security
        $this->audit('cross_mda.granted');             // permission
        $this->audit('service_request.accepted');      // service_request
        $this->audit('beneficiary.exported');          // data_access
        $this->audit('household.updated');             // activity (uncategorised)
    }

    /* ------------------------------------------------------------------ gating */

    public function test_the_audit_log_is_gated_to_the_system_administrator(): void
    {
        $this->audit('auth.login');

        $this->as('admin', 'GET', '/api/v1/admin/audit-logs')->assertOk();

        foreach (['officer', 'coordination'] as $key) {
            $this->as($key, 'GET', '/api/v1/admin/audit-logs')
                ->assertStatus(403)->assertJsonPath('error.code', 'FORBIDDEN');
        }
    }

    /* ----------------------------------------------------------------- filters */

    public function test_entries_can_be_filtered_by_category(): void
    {
        $this->seedCategories();

        $cases = [
            'security' => 'auth.login',
            'permission' => 'cross_mda.granted',
            'service_request' => 'service_request.accepted',
            'data_access' => 'beneficiary.exported',
            'activity' => 'household.updated',
        ];

        foreach ($cases as $category => $expectedAction) {
            $rows = $this->as('admin', 'GET', "/api/v1/admin/audit-logs?category={$category}")
                ->assertOk()->json('data');

            $this->assertCount(1, $rows, "Category {$category} should return exactly its own entry.");
            $this->assertSame($expectedAction, $rows[0]['action']);
            $this->assertSame($category, $rows[0]['category']);
        }
    }

    public function test_entries_can_be_filtered_by_action_actor_date_and_search(): void
    {
        $this->audit('auth.login', at: Carbon::now()->subDays(10));
        $this->audit('auth.logout');
        AuditLog::create([
            'actor_id' => $this->users['admin']->id,
            'actor_mda_id' => null,
            'action' => 'report.downloaded',
            'entity_type' => Mda::class,
            'entity_id' => $this->mda->id,
        ]);

        // Exact action.
        $this->assertCount(1, $this->as('admin', 'GET', '/api/v1/admin/audit-logs?action=auth.logout')->json('data'));

        // Actor.
        $adminId = $this->users['admin']->id;
        $byActor = $this->as('admin', 'GET', "/api/v1/admin/audit-logs?actor_id={$adminId}")->json('data');
        $this->assertCount(1, $byActor);
        $this->assertSame('report.downloaded', $byActor[0]['action']);

        // Entity type.
        $this->assertCount(1, $this->as('admin', 'GET', '/api/v1/admin/audit-logs?entity_type=Mda')->json('data'));

        // Date range — the 10-day-old login is excluded from the last 3 days.
        $from = Carbon::now()->subDays(3)->toDateString();
        $recent = $this->as('admin', 'GET', "/api/v1/admin/audit-logs?from={$from}")->json('data');
        $this->assertCount(2, $recent);
        $this->assertNotContains('auth.login', array_column($recent, 'action'));

        // Free-text search over action/entity only.
        $found = $this->as('admin', 'GET', '/api/v1/admin/audit-logs?q=auth')->json('data');
        $this->assertCount(2, $found);
    }

    public function test_request_to_serve_decisions_are_visible_to_the_administrator(): void
    {
        $this->audit('service_request.created');
        $this->audit('service_request.accepted');
        $this->audit('service_request.declined');
        $this->audit('auth.login');

        $rows = $this->as('admin', 'GET', '/api/v1/admin/audit-logs?category=service_request')
            ->assertOk()->json('data');

        $actions = array_column($rows, 'action');
        $this->assertCount(3, $rows);
        foreach (['service_request.created', 'service_request.accepted', 'service_request.declined'] as $action) {
            $this->assertContains($action, $actions);
        }

        // The decision envelope identifies who acted and for which MDA.
        $this->assertSame($this->users['officer']->name, $rows[0]['actor']);
        $this->assertSame('MDA A', $rows[0]['actor_mda']);
    }

    /* ------------------------------------------------------------- no leakage */

    public function test_recorded_values_never_leave_the_server(): void
    {
        // A snapshot carrying obviously sensitive values.
        $this->audit('user.updated', after: [
            'email' => 'secret.person@example.test',
            'phone' => '08012345678',
            'password' => 'S3cret-Passw0rd',
            'status' => 'suspended',
        ]);

        $response = $this->as('admin', 'GET', '/api/v1/admin/audit-logs')->assertOk();
        $row = $response->json('data.0');

        // The reviewer sees WHICH fields changed...
        $this->assertEqualsCanonicalizing(['email', 'phone', 'password', 'status'], $row['changed_fields']);

        // ...but no value, and no before/after payload at all.
        $this->assertArrayNotHasKey('before', $row);
        $this->assertArrayNotHasKey('after', $row);

        $json = (string) json_encode($response->json());
        foreach (['secret.person@example.test', '08012345678', 'S3cret-Passw0rd', 'suspended'] as $value) {
            $this->assertStringNotContainsString($value, $json);
        }
    }

    public function test_search_cannot_reach_into_recorded_values(): void
    {
        $this->audit('user.updated', after: ['email' => 'needle@example.test']);

        // Searching for a value inside a payload returns nothing — search covers the
        // action and entity type only.
        $this->assertCount(0, $this->as('admin', 'GET', '/api/v1/admin/audit-logs?q=needle')->json('data'));
    }

    /* -------------------------------------------------------------- export */

    public function test_export_streams_the_filtered_view_and_is_itself_audited(): void
    {
        $this->seedCategories();

        $response = $this->as('admin', 'GET', '/api/v1/admin/audit-logs/export?format=csv&category=security');
        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));

        $csv = $response->streamedContent();
        $this->assertStringContainsString('auth.login', $csv);
        // Filtered: another category's entry is absent.
        $this->assertStringNotContainsString('beneficiary.exported', $csv);

        // The export itself went through the EXISTING audit path.
        $this->assertTrue(AuditLog::query()->where('action', 'audit_log.exported')->exists());
    }

    public function test_export_requires_the_aggregate_export_permission(): void
    {
        $this->audit('auth.login');

        // Strip reporting.export from the administrator's role: the role gate alone
        // must not open the export.
        $role = Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail();
        $permissionId = DB::table('permissions')->where('key', 'reporting.export')->value('id');
        $role->permissions()->detach($permissionId);

        $this->as('admin', 'GET', '/api/v1/admin/audit-logs/export?format=csv')->assertStatus(403);

        // Non-admin roles are refused regardless.
        foreach (['officer', 'coordination'] as $key) {
            $this->as($key, 'GET', '/api/v1/admin/audit-logs/export?format=csv')->assertStatus(403);
        }
    }

    public function test_the_export_carries_no_recorded_values(): void
    {
        $this->audit('user.updated', after: ['email' => 'leak@example.test', 'password' => 'hunter2']);

        $csv = $this->as('admin', 'GET', '/api/v1/admin/audit-logs/export?format=csv')
            ->assertOk()->streamedContent();

        // Field names appear; values never do.
        $this->assertStringContainsString('email', $csv);
        $this->assertStringNotContainsString('leak@example.test', $csv);
        $this->assertStringNotContainsString('hunter2', $csv);
    }

    /* ------------------------------------------------------------- read-only */

    public function test_the_section_exposes_no_write_path(): void
    {
        $entry = $this->audit('auth.login');

        foreach (['POST', 'PATCH', 'PUT', 'DELETE'] as $method) {
            $this->as('admin', $method, '/api/v1/admin/audit-logs')->assertStatus(405);
        }

        // The log itself remains append-only.
        $this->assertDatabaseHas('audit_log', ['id' => $entry->id, 'action' => 'auth.login']);
    }
}
