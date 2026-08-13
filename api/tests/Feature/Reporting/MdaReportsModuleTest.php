<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The MDA console's Reports module: the six report types over the Phase 6 engine, the
 * export permission matrix from docs/SECURITY.md §3, and the audit trail.
 *
 * Two things are asserted that nothing else covered:
 *
 *  1. **The six types are reachable and MDA-scoped.** Programme/Activity/Beneficiary/
 *     Benefit/Referral/Duplicate all resolve to whitelisted datasets, and every one is
 *     constrained to the caller's MDA — including `duplicates`, which is an
 *     administrative dataset admitted to an MDA scope by the narrow `mda_scopable`
 *     exception and therefore needs its scoping proven, not assumed.
 *  2. **The matrix.** Aggregate reporting rides `reporting.export`; bulk beneficiary PII
 *     rides `beneficiary.export` (MDA Admin yes, MDA Officer no unless granted) with
 *     NIN/BVN masked unless `export.reveal_pii`. Every export is audited.
 */
class MdaReportsModuleTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $programme;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->mdaB = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['adminA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['adminB'] = $this->user($this->mdaB, RoleKey::MdaAdmin);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['sysadmin'] = $this->user(null, RoleKey::SystemAdministrator);

        $this->programme = Programme::factory()->individual()->create(['name' => 'Cash Transfer', 'status' => 'active']);

        // Activities: two for A, one for B.
        Activity::factory()->forProgramme($this->programme, $this->mdaA)->create(['name' => 'A one', 'target_beneficiaries' => 100, 'status' => 'active']);
        Activity::factory()->forProgramme($this->programme, $this->mdaA)->create(['name' => 'A two', 'target_beneficiaries' => 50, 'status' => 'active']);
        Activity::factory()->forProgramme($this->programme, $this->mdaB)->create(['name' => 'B one', 'target_beneficiaries' => 999, 'status' => 'active']);

        $benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'nin' => '12345678901', 'bvn' => '22233344455']);
        $benB = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia', 'nin' => '99988877766']);

        Benefit::factory()->create(['beneficiary_id' => $benA->id, 'programme_id' => $this->programme->id, 'mda_id' => $this->mdaA->id, 'monetary_value' => 100_000, 'lga' => 'dutse', 'status' => 'verified']);
        Benefit::factory()->create(['beneficiary_id' => $benB->id, 'programme_id' => $this->programme->id, 'mda_id' => $this->mdaB->id, 'monetary_value' => 900_000, 'lga' => 'hadejia', 'status' => 'verified']);

        $this->duplicateRow($this->mdaA, MatchBand::Exact->value, 'skip');
        $this->duplicateRow($this->mdaB, MatchBand::Probable->value, 'new');
        $this->duplicateRow($this->mdaB, MatchBand::Probable->value, 'link');
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function duplicateRow(Mda $owner, string $band, string $resolution): void
    {
        $batch = ImportBatch::create([
            'owner_mda_id' => $owner->id,
            'original_filename' => 'rows.csv',
            'stored_path' => 'imports/rows.csv',
            'source' => 'csv',
            'status' => 'preview_ready',
        ]);

        ImportRow::create([
            'import_batch_id' => $batch->id,
            'row_number' => 1,
            'payload' => ['first_name' => 'A', 'last_name' => 'B'],
            'is_valid' => true,
            'match_band' => $band,
            'resolution' => $resolution,
        ]);
    }

    /** @param array<string, mixed> $body */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** Grant a permission to a role — the only grant mechanism that exists (role-level). */
    private function grant(RoleKey $role, string $permission): void
    {
        $roleModel = Role::where('key', $role->value)->firstOrFail();
        $roleModel->permissions()->syncWithoutDetaching([Permission::where('key', $permission)->firstOrFail()->id]);
    }

    /** @return list<string> the dataset keys the caller may report on */
    private function datasetKeys(string $key): array
    {
        $body = $this->send($key, 'GET', '/api/v1/reports/adhoc/datasets')->assertOk()->json('data.datasets');

        return array_column($body, 'key');
    }

    /* ------------------------------------------------------- the six report types */

    public function test_an_mda_can_reach_every_required_report_type(): void
    {
        $keys = $this->datasetKeys('officerA');

        // Programme reporting for an MDA is its delivery UNDER the shared catalogue
        // (§10 — an MDA owns no programme), so it rides the activities/benefits
        // datasets grouped by programme rather than a catalogue dataset.
        foreach (['activities', 'beneficiaries', 'benefits', 'referrals', 'duplicates'] as $dataset) {
            $this->assertContains($dataset, $keys, "an MDA must be able to report on {$dataset}");
        }

        $programmeDimension = collect(
            $this->send('officerA', 'GET', '/api/v1/reports/adhoc/datasets')->json('data.datasets')
        )->firstWhere('key', 'activities');

        $this->assertContains('programme', array_column($programmeDimension['dimensions'], 'key'));
    }

    public function test_an_activity_report_counts_only_the_callers_own_activities(): void
    {
        $rows = $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'activities',
            'group_by' => ['status'],
            'measures' => ['count', 'target_beneficiaries'],
        ])->assertOk()->json('data.rows');

        // A has two activities targeting 150; B's 999 must not appear anywhere.
        $flat = (string) json_encode($rows);
        $this->assertStringContainsString('150', $flat);
        $this->assertStringNotContainsString('999', $flat, "another MDA's activity must never be counted");
    }

    public function test_a_programme_report_groups_the_mdas_own_delivery_by_programme(): void
    {
        $data = $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'benefits',
            'group_by' => ['programme'],
            'measures' => ['count', 'total_value'],
        ])->assertOk()->json('data');

        $flat = (string) json_encode($data['rows']);
        $this->assertStringContainsString('Cash Transfer', $flat);
        // A delivered ₦100,000; B's ₦900,000 is out of scope.
        $this->assertStringNotContainsString('900,000', $flat);
    }

    public function test_a_duplicate_report_is_scoped_through_the_owning_import_batch(): void
    {
        // `duplicates` is an administrative dataset admitted to an MDA scope by the
        // mda_scopable exception. ImportRow has no MDA column, so if the scope clause
        // were missing this would silently return every MDA's rows.
        $rows = $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'duplicates',
            'group_by' => ['match_band'],
            'measures' => ['count'],
        ])->assertOk()->json('data.rows');

        $bands = array_map(static fn (array $r): string => (string) $r[0], $rows);
        $this->assertSame(['Exact'], $bands, 'only the caller MDA’s own rows may be counted');

        // B sees only its own two probable rows.
        $bRows = $this->send('adminB', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'duplicates',
            'group_by' => ['match_band'],
            'measures' => ['count'],
        ])->assertOk()->json('data.rows');

        $this->assertSame(['Probable'], array_map(static fn (array $r): string => (string) $r[0], $bRows));
        $this->assertSame('2', (string) $bRows[0][1]);
    }

    public function test_the_mda_scopable_exception_does_not_leak_other_admin_datasets(): void
    {
        $keys = $this->datasetKeys('officerA');

        // Governance data stays governance-only: an MDA reports on duplicates but never
        // on users, the audit log, organizations, the catalogue or imports.
        foreach (['users', 'audit', 'organizations', 'programme_catalogue', 'imports'] as $dataset) {
            $this->assertNotContains($dataset, $keys, "{$dataset} must remain governance-only");
        }
    }

    public function test_state_wide_oversight_without_governance_still_cannot_report_on_duplicates(): void
    {
        // An Executive out-ranks the MDA axis but is not a governance scope, and the
        // exception is deliberately MDA-only — otherwise it would widen Executive access.
        $this->assertNotContains('duplicates', $this->datasetKeys('exec'));
        $this->assertContains('duplicates', $this->datasetKeys('sysadmin'));
    }

    public function test_an_mda_cannot_widen_its_scope_with_a_filter(): void
    {
        // A filter may only ever NARROW within scope (FR-RPT-03).
        $rows = $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'benefits',
            'group_by' => ['mda'],
            'measures' => ['total_value'],
            'filters' => ['mda_id' => $this->mdaB->id],
        ])->assertOk()->json('data.rows');

        $this->assertSame([], $rows, 'naming another MDA in a filter must yield nothing, not their data');
    }

    /* ------------------------------------------------- the export permission matrix */

    public function test_aggregate_export_rides_the_reporting_export_permission(): void
    {
        // Both MDA roles hold reporting.export — an aggregate report carries no PII, so
        // it is not the matrix-governed path.
        foreach (['officerA', 'adminA'] as $caller) {
            $this->send($caller, 'POST', '/api/v1/reports/adhoc', [
                'dataset' => 'benefits',
                'group_by' => ['lga'],
                'measures' => ['count'],
                'format' => 'csv',
            ])->assertSuccessful();
        }
    }

    public function test_aggregate_export_is_refused_without_reporting_export(): void
    {
        $role = Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail();
        $role->permissions()->detach(Permission::where('key', 'reporting.export')->firstOrFail()->id);

        $this->send('officerA', 'POST', '/api/v1/reports/adhoc', [
            'dataset' => 'benefits', 'group_by' => ['lga'], 'measures' => ['count'], 'format' => 'csv',
        ])->assertStatus(403);
    }

    public function test_mda_admin_may_export_beneficiary_data_for_its_own_mda(): void
    {
        $response = $this->send('adminA', 'GET', '/api/v1/beneficiaries/export?format=csv');
        $response->assertSuccessful();

        $body = $response->streamedContent();
        // Own-MDA only: the other MDA's record is absent even unfiltered.
        $this->assertStringContainsString('dutse', $body);
        $this->assertStringNotContainsString('hadejia', $body, "an MDA Admin must not export another MDA's records");
    }

    public function test_mda_officer_is_denied_beneficiary_export_by_default(): void
    {
        // SECURITY.md §3: the largest, most junior group — no bulk PII export by default.
        $this->send('officerA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertStatus(403);
    }

    public function test_mda_officer_may_export_once_granted(): void
    {
        $this->grant(RoleKey::MdaOfficer, 'beneficiary.export');

        $response = $this->send('officerA', 'GET', '/api/v1/beneficiaries/export?format=csv');
        $response->assertSuccessful();

        // Granting export does not widen scope — still own-MDA only.
        $this->assertStringNotContainsString('hadejia', $response->streamedContent());
    }

    public function test_an_executive_can_never_export_the_beneficiary_registry(): void
    {
        $this->send('exec', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertStatus(403);
    }

    /* ---------------------------------------------------------------- PII masking */

    public function test_nin_and_bvn_are_masked_in_an_export_without_the_reveal_permission(): void
    {
        $body = $this->send('adminA', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertSuccessful()->streamedContent();

        $this->assertStringNotContainsString('12345678901', $body, 'a raw NIN must never reach an export');
        $this->assertStringNotContainsString('22233344455', $body, 'a raw BVN must never reach an export');
    }

    public function test_reveal_pii_is_not_bundled_into_any_mda_role(): void
    {
        // export.reveal_pii is a System Administrator permission by default and is never
        // part of an MDA role (SECURITY.md §3; RolePermissionService::NEVER_ROLE_GRANTABLE).
        foreach (['officerA', 'adminA'] as $caller) {
            $this->assertFalse(
                $this->users[$caller]->fresh()->hasPermission('export.reveal_pii'),
                'export.reveal_pii must never be bundled into an MDA role',
            );
        }
    }

    public function test_an_aggregate_report_cannot_select_an_identifier_at_all(): void
    {
        // The stronger guarantee for reports: masking is moot because no identifier
        // column is selectable in the first place.
        foreach (['nin', 'bvn', 'phone', 'first_name'] as $column) {
            $this->send('adminA', 'POST', '/api/v1/reports/adhoc/preview', [
                'dataset' => 'beneficiaries', 'group_by' => [$column], 'measures' => ['count'],
            ])->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');
        }
    }

    /* --------------------------------------------------------------------- audit */

    public function test_a_beneficiary_export_is_audited_with_scope_format_and_reveal(): void
    {
        $this->send('adminA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertSuccessful();

        $entry = AuditLog::query()->where('action', 'beneficiary.exported')->latest('id')->first();

        $this->assertNotNull($entry, 'every beneficiary export must be audited');
        $this->assertSame($this->users['adminA']->id, $entry->actor_id);

        $after = $entry->after ?? [];
        $this->assertSame('csv', $after['format'] ?? null);
        $this->assertArrayHasKey('revealed', $after, 'the audit must record whether PII was revealed');
        $this->assertFalse($after['revealed'], 'an MDA Admin holds no reveal permission, so this export was masked');
        $this->assertArrayHasKey('row_count', $after);
        // The scope is captured so an auditor can see WHAT the export covered.
        $this->assertSame([$this->mdaA->id], $after['scope']['mda_ids'] ?? null);
    }

    public function test_the_export_audit_records_no_identifier_values(): void
    {
        $this->send('adminA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertSuccessful();

        $entry = AuditLog::query()->where('action', 'beneficiary.exported')->latest('id')->firstOrFail();
        $json = (string) json_encode($entry->after);

        foreach (['12345678901', '22233344455'] as $identifier) {
            $this->assertStringNotContainsString($identifier, $json, 'the audit trail must not become a PII sink');
        }
    }

    public function test_an_aggregate_export_is_recorded_as_a_run_for_the_caller(): void
    {
        $run = $this->send('adminA', 'POST', '/api/v1/reports/adhoc', [
            'dataset' => 'benefits', 'group_by' => ['lga'], 'measures' => ['count'], 'format' => 'csv',
        ])->assertSuccessful()->json('data');

        $this->assertNotNull($run['id'] ?? null);

        // It shows up in the caller's own run list — the shared engine's pipeline.
        $mine = $this->send('adminA', 'GET', '/api/v1/reports')->assertOk()->json('data');
        $this->assertContains($run['id'], array_column($mine, 'id'));

        // …and never in another MDA's.
        $theirs = $this->send('adminB', 'GET', '/api/v1/reports')->assertOk()->json('data');
        $this->assertNotContains($run['id'], array_column($theirs, 'id'));
    }
}
