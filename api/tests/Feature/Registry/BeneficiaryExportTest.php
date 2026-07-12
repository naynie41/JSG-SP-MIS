<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
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
 * Beneficiary registry list export (FR-REG-04 + FR-RPT-03). Reuses the shared Phase 6
 * exporters: exports exactly the filtered, MDA-scoped view; masks NIN/BVN unless the
 * caller may reveal; queues + notifies for large exports; audits every export.
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

        // MDA-scoped exporter (own MDA only), a variant that may also reveal
        // identifiers, an oversight exporter (state-wide), and a viewer without export.
        // Export-capable roles carry reporting.view/export too (as every real bundle
        // does) so they can retrieve a queued export via the report-run download.
        $reporting = ['reporting.view', 'reporting.export'];
        $this->users['exporterA'] = $this->userWith($this->mdaA, ['beneficiary.view', 'beneficiary.export', ...$reporting]);
        $this->users['revealerA'] = $this->userWith($this->mdaA, ['beneficiary.view', 'beneficiary.export', 'beneficiary-reveal.view', ...$reporting]);
        $this->users['oversight'] = $this->userWith($this->mdaA, ['beneficiary.view', 'beneficiary.export', 'cross-mda.view', ...$reporting]);
        $this->users['viewerA'] = $this->userWith($this->mdaA, ['beneficiary.view']);
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

    /* --------------------------------------------------------------- format output */

    public function test_in_scope_user_can_export_to_csv(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Ada', 'last_name' => 'Okoye']);

        $csv = $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csv->headers->get('content-type'));
        $content = $csv->streamedContent();
        $this->assertStringContainsString('Full name', $content); // header row
        $this->assertStringContainsString('Ada Okoye', $content);
    }

    public function test_in_scope_user_can_export_to_excel(): void
    {
        if (! class_exists(Spreadsheet::class)) {
            $this->markTestSkipped('phpoffice/phpspreadsheet is not installed in this environment.');
        }

        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Ada', 'last_name' => 'Okoye']);

        $excel = $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=excel')->assertOk();
        $this->assertStringContainsString('spreadsheetml', (string) $excel->headers->get('content-type'));
        $this->assertStringStartsWith('PK', $excel->streamedContent()); // xlsx (zip) signature
    }

    public function test_missing_or_invalid_format_is_rejected(): void
    {
        $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export')->assertStatus(422);
        $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=pdf')->assertStatus(422);
    }

    /* ------------------------------------------------------------- filter fidelity */

    public function test_export_reflects_the_same_filters_as_the_list(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Dutse', 'last_name' => 'Resident', 'lga' => 'dutse']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Hadejia', 'last_name' => 'Resident', 'lga' => 'hadejia']);

        $content = $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv&filter[lga]=dutse')
            ->assertOk()->streamedContent();

        $this->assertStringContainsString('Dutse Resident', $content);
        $this->assertStringNotContainsString('Hadejia Resident', $content);
    }

    public function test_export_search_matches_the_list(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Amina', 'last_name' => 'Bello']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Chidi', 'last_name' => 'Nwosu']);

        $content = $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv&search=Amina')
            ->assertOk()->streamedContent();

        $this->assertStringContainsString('Amina Bello', $content);
        $this->assertStringNotContainsString('Chidi Nwosu', $content);
    }

    /* ------------------------------------------------------------------- scoping */

    public function test_out_of_scope_rows_never_appear(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Mine', 'last_name' => 'Record']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Other', 'last_name' => 'Record']);

        // MDA-scoped exporter sees only its own MDA.
        $scoped = $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk()->streamedContent();
        $this->assertStringContainsString('Mine Record', $scoped);
        $this->assertStringNotContainsString('Other Record', $scoped);

        // Oversight (cross-mda.view) sees both MDAs.
        $all = $this->send('oversight', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk()->streamedContent();
        $this->assertStringContainsString('Mine Record', $all);
        $this->assertStringContainsString('Other Record', $all);
    }

    /* ------------------------------------------------------------------- masking */

    public function test_nin_bvn_masked_unless_the_caller_may_reveal(): void
    {
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'first_name' => ' Id', 'last_name' => 'Holder',
            'nin' => '12345678901', 'bvn' => '22345678901',
        ]);

        // Without reveal → masked (last 4), matching the on-screen list.
        $masked = $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk()->streamedContent();
        $this->assertStringContainsString('•••••••8901', $masked);
        $this->assertStringNotContainsString('12345678901', $masked);
        $this->assertStringNotContainsString('22345678901', $masked);

        // With reveal → unmasked identifiers.
        $revealed = $this->send('revealerA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk()->streamedContent();
        $this->assertStringContainsString('12345678901', $revealed);
        $this->assertStringContainsString('22345678901', $revealed);
    }

    /* ---------------------------------------------------------------- permission */

    public function test_export_requires_the_export_permission(): void
    {
        $this->send('viewerA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertStatus(403);
    }

    /* -------------------------------------------------------------------- audit */

    public function test_streamed_export_is_audited(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv')->assertOk();

        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.exported',
            'actor_id' => $this->users['exporterA']->id,
        ]);
    }

    /* ----------------------------------------------------- large export → queue */

    public function test_large_export_is_queued(): void
    {
        Queue::fake();
        config(['registry.export_sync_max' => 1]);
        Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id]);

        $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv')
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

        // Two in-scope rows (so the MDA-scoped exporter is over the threshold of 1)
        // plus an out-of-scope row that must never reach the file.
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id, 'first_name' => 'Queued', 'last_name' => 'Mine',
            'nin' => '12345678901',
        ]);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Queued', 'last_name' => 'Extra']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Queued', 'last_name' => 'Other']);

        // Over the threshold → queued (the sync queue runs the job inline in tests).
        $id = $this->send('exporterA', 'GET', '/api/v1/beneficiaries/export?format=csv')
            ->assertStatus(202)->json('data.id');

        // The run completed and the requester was notified (FR-NOT-01).
        $this->send('exporterA', 'GET', "/api/v1/reports/{$id}")->assertOk()->assertJsonPath('data.status', 'ready');
        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['exporterA']->id,
            'type' => 'report.ready',
            'related_type' => 'report_run',
            'related_id' => $id,
        ]);
        $this->assertDatabaseHas('audit_log', ['action' => 'report.generated', 'entity_id' => $id]);

        // The generated file honours scope + masking, and downloading is audited.
        $content = $this->send('exporterA', 'GET', "/api/v1/reports/{$id}/download")->assertOk()->streamedContent();
        $this->assertStringContainsString('Queued Mine', $content);
        $this->assertStringNotContainsString('Queued Other', $content); // out-of-scope excluded
        $this->assertStringContainsString('•••••••8901', $content);      // masked (no reveal)
        $this->assertStringNotContainsString('12345678901', $content);
        $this->assertDatabaseHas('audit_log', ['action' => 'report.downloaded', 'entity_id' => $id]);
    }
}
