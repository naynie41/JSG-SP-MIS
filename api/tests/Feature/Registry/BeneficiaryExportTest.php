<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Jobs\GenerateReport;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Tests\TestCase;

/**
 * Beneficiary registry list export (FR-REG-04 + FR-RPT-03) and its permission matrix
 * (SECURITY.md — Export of beneficiary data). Reuses the shared Phase 6 exporters:
 * exports exactly the filtered, MDA-scoped view; enforces the role matrix; masks
 * NIN/BVN unless the caller holds export.reveal_pii; queues + notifies for large
 * exports; audits every export distinctly (actor, scope, filters, format, rows, PII).
 */
class BeneficiaryExportTest extends TestCase
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

        // One user per predefined role (the real seeded bundles drive the matrix).
        $this->users['sysAdmin'] = $this->seededUser(RoleKey::SystemAdministrator, $this->mdaA);
        $this->users['spCoord'] = $this->seededUser(RoleKey::SpCoordination, $this->mdaA);
        $this->users['mne'] = $this->seededUser(RoleKey::MneOfficer, $this->mdaA);
        $this->users['mdaAdminA'] = $this->seededUser(RoleKey::MdaAdmin, $this->mdaA);
        $this->users['mdaOfficerA'] = $this->seededUser(RoleKey::MdaOfficer, $this->mdaA);
        $this->users['executive'] = $this->seededUser(RoleKey::Executive, $this->mdaA);
        $this->users['partner'] = $this->seededUser(RoleKey::DevelopmentPartner, null);

        // "Admin grants an MDA Officer export per user" — modelled by a bespoke,
        // own-MDA role carrying beneficiary.export (this system is role-based; there
        // is no per-user permission override table).
        $this->users['grantedOfficerA'] = $this->userWith($this->mdaA, [
            'beneficiary.view', 'beneficiary.export', 'reporting.view', 'reporting.export',
        ]);
    }

    private function seededUser(RoleKey $role, ?Mda $mda): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** A user on $mda whose bespoke (non-system) role holds exactly $permissionKeys. */
    private function userWith(Mda $mda, array $permissionKeys): User
    {
        $role = Role::create(['key' => 'test-'.bin2hex(random_bytes(4)), 'name' => 'Test role', 'is_system' => false]);
        $role->permissions()->sync(Permission::whereIn('key', $permissionKeys)->pluck('id')->all());

        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => $role->id]);
    }

    private function send(string $key, string $method, string $url): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------------------------------------ permission matrix */

    public function test_permission_matrix_is_enforced(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // Allowed: System Administrator, SP Coordination, M&E, MDA Admin, granted officer.
        foreach (['sysAdmin', 'spCoord', 'mne', 'mdaAdminA', 'grantedOfficerA'] as $key) {
            $this->send($key, 'GET', '/api/v1/beneficiaries/export?format=csv')
                ->assertOk();
        }

        // Denied (403): MDA Officer (not granted), Development Partner, Executive.
        foreach (['mdaOfficerA', 'partner', 'executive'] as $key) {
            $this->send($key, 'GET', '/api/v1/beneficiaries/export?format=csv')
                ->assertStatus(403);
        }
    }

    public function test_mda_officer_is_denied_by_default_but_allowed_once_granted(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Own', 'last_name' => 'Record']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Foreign', 'last_name' => 'Record']);

        // Default MDA Officer role has no beneficiary.export.
        $this->send('mdaOfficerA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertStatus(403);

        // Granted (own-MDA) → allowed, and limited to their own MDA.
        $content = $this->send('grantedOfficerA', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertOk()->streamedContent();
        $this->assertStringContainsString('Own Record', $content);
        $this->assertStringNotContainsString('Foreign Record', $content);
    }

    /* --------------------------------------------------------------- scope fidelity */

    public function test_own_mda_role_exports_only_its_own_mda(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Mine', 'last_name' => 'Record']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Other', 'last_name' => 'Record']);

        $content = $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertOk()->streamedContent();

        $this->assertStringContainsString('Mine Record', $content);
        $this->assertStringNotContainsString('Other Record', $content);
    }

    public function test_cross_mda_and_state_wide_roles_export_all_mdas(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Mine', 'last_name' => 'Record']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Other', 'last_name' => 'Record']);

        foreach (['spCoord', 'sysAdmin'] as $key) {
            $content = $this->send($key, 'GET', '/api/v1/beneficiaries/export?format=csv')
                ->assertOk()->streamedContent();
            $this->assertStringContainsString('Mine Record', $content);
            $this->assertStringContainsString('Other Record', $content);
        }
    }

    /* --------------------------------------------------------------- format output */

    public function test_exports_to_csv(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Ada', 'last_name' => 'Okoye']);

        $csv = $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csv->headers->get('content-type'));
        $content = $csv->streamedContent();
        $this->assertStringContainsString('Full name', $content);
        $this->assertStringContainsString('Ada Okoye', $content);
    }

    public function test_exports_to_excel(): void
    {
        if (! class_exists(Spreadsheet::class)) {
            $this->markTestSkipped('phpoffice/phpspreadsheet is not installed in this environment.');
        }

        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Ada', 'last_name' => 'Okoye']);

        $excel = $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=excel')->assertOk();
        $this->assertStringContainsString('spreadsheetml', (string) $excel->headers->get('content-type'));
        $this->assertStringStartsWith('PK', $excel->streamedContent());
    }

    public function test_missing_or_invalid_format_is_rejected(): void
    {
        $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export')->assertStatus(422);
        $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=pdf')->assertStatus(422);
    }

    /* ------------------------------------------------------------- filter fidelity */

    public function test_export_reflects_the_same_filters_as_the_list(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Dutse', 'last_name' => 'Resident', 'lga' => 'dutse']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Hadejia', 'last_name' => 'Resident', 'lga' => 'hadejia']);

        $content = $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv&filter[lga]=dutse')
            ->assertOk()->streamedContent();

        $this->assertStringContainsString('Dutse Resident', $content);
        $this->assertStringNotContainsString('Hadejia Resident', $content);
    }

    public function test_export_search_matches_the_list(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Amina', 'last_name' => 'Bello']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Chidi', 'last_name' => 'Nwosu']);

        $content = $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv&search=Amina')
            ->assertOk()->streamedContent();

        $this->assertStringContainsString('Amina Bello', $content);
        $this->assertStringNotContainsString('Chidi Nwosu', $content);
    }

    /* -------------------------------------------------------------- masking + PII */

    public function test_nin_bvn_masked_unless_caller_holds_reveal_pii(): void
    {
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'first_name' => 'Id', 'last_name' => 'Holder',
            'nin' => '12345678901', 'bvn' => '22345678902',
        ]);

        // MDA Admin may export but NOT reveal → identifiers masked (matching the list).
        $masked = $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk()->streamedContent();
        $this->assertStringContainsString('•••••••8901', $masked);
        $this->assertStringContainsString('•••••••8902', $masked);
        $this->assertStringNotContainsString('12345678901', $masked);
        $this->assertStringNotContainsString('22345678902', $masked);

        // System Administrator holds export.reveal_pii (implicit all) → unmasked.
        $revealed = $this->send('sysAdmin', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk()->streamedContent();
        $this->assertStringContainsString('12345678901', $revealed);
        $this->assertStringContainsString('22345678902', $revealed);
    }

    public function test_reveal_pii_is_bundled_into_no_role_except_system_administrator(): void
    {
        // Only the System Administrator holds it (via the implicit all-permissions grant).
        foreach ([RoleKey::SpCoordination, RoleKey::MneOfficer, RoleKey::MdaAdmin, RoleKey::MdaOfficer, RoleKey::Executive, RoleKey::DevelopmentPartner] as $role) {
            $keys = Role::where('key', $role->value)->firstOrFail()->permissions->pluck('key');
            $this->assertFalse($keys->contains('export.reveal_pii'), "{$role->value} must not hold export.reveal_pii");
        }

        $admin = Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail();
        $this->assertTrue($admin->permissions->pluck('key')->contains('export.reveal_pii'));
    }

    /* -------------------------------------------------------------------- audit */

    public function test_export_is_audited_with_scope_filters_format_rows_and_reveal_flag(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);

        $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv&filter[lga]=dutse')->assertOk();

        $log = AuditLog::query()->where('action', 'beneficiary.exported')->latest('id')->firstOrFail();
        $this->assertSame($this->users['mdaAdminA']->id, $log->actor_id);
        $this->assertSame('csv', $log->after['format']);
        $this->assertFalse($log->after['revealed']);
        $this->assertSame(1, $log->after['row_count']);
        $this->assertSame('mda', $log->after['scope']['kind']);
        $this->assertContains($this->mdaA->id, $log->after['scope']['mda_ids']);
        $this->assertSame('dutse', $log->after['filters']['lga']);
    }

    public function test_reveal_is_recorded_in_the_audit_when_pii_is_exposed(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'nin' => '12345678901']);

        $this->send('sysAdmin', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk();

        $log = AuditLog::query()->where('action', 'beneficiary.exported')->latest('id')->firstOrFail();
        $this->assertTrue($log->after['revealed']);
    }

    /* ----------------------------------------------------- large export → queue */

    public function test_large_export_is_queued(): void
    {
        Queue::fake();
        config(['registry.export_sync_max' => 1]);
        Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id]);

        $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertStatus(202)
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.report_key', 'beneficiary_list');

        Queue::assertPushed(GenerateReport::class);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.export_queued']);
    }

    public function test_queued_export_generates_notifies_and_downloads_scoped_and_masked(): void
    {
        Storage::fake('local');
        config(['registry.export_sync_max' => 1]);

        // Two in-scope rows (over the threshold of 1) + an out-of-scope row.
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id, 'first_name' => 'Queued', 'last_name' => 'Mine', 'nin' => '12345678901',
        ]);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Queued', 'last_name' => 'Extra']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Queued', 'last_name' => 'Other']);

        $id = $this->send('mdaAdminA', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertStatus(202)->json('data.id');

        // Generated + the requester was notified (FR-NOT-01).
        $this->send('mdaAdminA', 'GET', "/api/v1/reports/{$id}")->assertOk()->assertJsonPath('data.status', 'ready');
        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['mdaAdminA']->id,
            'type' => 'report.ready',
            'related_type' => 'report_run',
            'related_id' => $id,
        ]);
        $this->assertDatabaseHas('audit_log', ['action' => 'report.generated', 'entity_id' => $id]);

        // The file honours scope + masking, and downloading is audited.
        $content = $this->send('mdaAdminA', 'GET', "/api/v1/reports/{$id}/download")->assertOk()->streamedContent();
        $this->assertStringContainsString('Queued Mine', $content);
        $this->assertStringNotContainsString('Queued Other', $content);
        $this->assertStringContainsString('•••••••8901', $content);
        $this->assertStringNotContainsString('12345678901', $content);
        $this->assertDatabaseHas('audit_log', ['action' => 'report.downloaded', 'entity_id' => $id]);
    }
}
