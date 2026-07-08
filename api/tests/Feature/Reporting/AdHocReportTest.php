<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Ad-hoc report builder (PRD FR-RPT-03): whitelist constraints, scope + PII masking
 * that cannot be bypassed via column/filter choices, saved definitions, preview and
 * export.
 */
class AdHocReportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $progA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);
        $this->users['noRole'] = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);

        $aBen = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        $bBen = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia']);

        $this->progA = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaA->id, 'name' => 'Cash A', 'status' => 'active']);
        $progB = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaB->id, 'name' => 'Grant B', 'status' => 'active']);
        ProgrammeFunder::create(['programme_id' => $this->progA->id, 'user_id' => $this->users['partner']->id]);

        Benefit::factory()->create(['beneficiary_id' => $aBen->id, 'programme_id' => $this->progA->id, 'mda_id' => $this->mdaA->id, 'monetary_value' => 100_000, 'lga' => 'dutse', 'status' => 'verified']);
        Benefit::factory()->create(['beneficiary_id' => $aBen->id, 'programme_id' => $this->progA->id, 'mda_id' => $this->mdaA->id, 'monetary_value' => 200_000, 'lga' => 'dutse', 'status' => 'verified']);
        Benefit::factory()->create(['beneficiary_id' => $bBen->id, 'programme_id' => $progB->id, 'mda_id' => $this->mdaB->id, 'monetary_value' => 150_000, 'lga' => 'hadejia', 'status' => 'verified']);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* --------------------------------------------------------- builder constraints */

    public function test_rejects_definitions_outside_the_whitelist(): void
    {
        // Unknown dataset.
        $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', ['dataset' => 'secret', 'measures' => ['count']])
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');

        // A PII / non-whitelisted column can never be selected.
        $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', ['dataset' => 'beneficiaries', 'group_by' => ['nin'], 'measures' => ['count']])
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');

        // Unknown measure + unknown filter.
        $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', ['dataset' => 'benefits', 'measures' => ['bogus']])
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');
        $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', ['dataset' => 'benefits', 'measures' => ['count'], 'filters' => ['secret_col' => 'x']])
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');
    }

    public function test_dataset_catalogue_exposes_no_pii_columns(): void
    {
        $body = $this->send('officerA', 'GET', '/api/v1/reports/adhoc/datasets')->assertOk()->json();
        $json = json_encode($body);

        foreach (['nin', 'bvn', 'phone', 'first_name', 'last_name', 'address', 'date_of_birth'] as $pii) {
            $this->assertStringNotContainsString($pii, (string) $json);
        }
    }

    /* --------------------------------------------------------------- scope */

    public function test_preview_is_scoped_and_filters_cannot_widen_scope(): void
    {
        // MDA A officer: grouping by MDA only ever yields their own MDA.
        $body = $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'benefits', 'group_by' => ['mda'], 'measures' => ['count', 'total_value'],
        ])->assertOk()->json('data');

        $this->assertSame(1, $body['row_count']);
        $this->assertSame('MDA A', $body['rows'][0][0]);
        $this->assertSame('2', $body['rows'][0][1]);           // 2 deliveries
        $this->assertSame('₦3,000.00', $body['rows'][0][2]);   // 300,000 kobo

        // Filtering by ANOTHER MDA cannot widen scope — it intersects to nothing.
        $this->send('officerA', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'benefits', 'group_by' => ['mda'], 'measures' => ['count'], 'filters' => ['mda_id' => $this->mdaB->id],
        ])->assertOk()->assertJsonPath('data.row_count', 0);

        // Executive (state-wide) sees both MDAs.
        $this->send('exec', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'benefits', 'group_by' => ['mda'], 'measures' => ['count'],
        ])->assertOk()->assertJsonPath('data.row_count', 2);
    }

    public function test_partner_scope_limits_datasets_and_rows(): void
    {
        // Coordination datasets are not available to a partner scope.
        $this->send('partner', 'POST', '/api/v1/reports/adhoc/preview', ['dataset' => 'referrals', 'measures' => ['count']])
            ->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');

        // Benefits are limited to the partner's funded programme.
        $body = $this->send('partner', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'benefits', 'group_by' => ['programme'], 'measures' => ['count'],
        ])->assertOk()->json('data');
        $this->assertSame(1, $body['row_count']);
        $this->assertSame('Cash A', $body['rows'][0][0]);
    }

    /* ------------------------------------------------------- saved definitions */

    public function test_save_list_run_and_isolation_of_definitions(): void
    {
        Storage::fake('local');

        $id = $this->send('officerA', 'POST', '/api/v1/report-definitions', [
            'name' => 'My cash summary', 'dataset' => 'benefits', 'group_by' => ['lga'], 'measures' => ['count', 'total_value'],
        ])->assertCreated()->json('data.id');

        // It appears in my list…
        $this->send('officerA', 'GET', '/api/v1/report-definitions')
            ->assertOk()->assertJsonPath('data.definitions.0.name', 'My cash summary');

        // …and generates a report run when run.
        $runId = $this->send('officerA', 'POST', "/api/v1/report-definitions/{$id}/run", ['format' => 'csv'])
            ->assertCreated()->json('data.id');
        $this->send('officerA', 'GET', "/api/v1/reports/{$runId}")->assertOk()->assertJsonPath('data.status', 'ready');

        // Another user cannot see or run it.
        $this->send('partner', 'GET', "/api/v1/report-definitions/{$id}")->assertStatus(404);
        $this->send('partner', 'POST', "/api/v1/report-definitions/{$id}/run", ['format' => 'csv'])->assertStatus(404);
    }

    /* -------------------------------------------------------------- export */

    public function test_export_generates_a_downloadable_scoped_file(): void
    {
        Storage::fake('local');

        $id = $this->send('officerA', 'POST', '/api/v1/reports/adhoc', [
            'dataset' => 'benefits', 'group_by' => ['lga'], 'measures' => ['count', 'total_value'], 'format' => 'csv',
        ])->assertCreated()->json('data.id');

        $this->send('officerA', 'GET', "/api/v1/reports/{$id}")->assertOk()->assertJsonPath('data.status', 'ready');

        $content = $this->send('officerA', 'GET', "/api/v1/reports/{$id}/download")->assertOk()->streamedContent();
        $this->assertStringContainsString('Dutse', $content);      // A's LGA (scoped)
        $this->assertStringNotContainsString('Hadejia', $content); // B's LGA — out of scope
    }

    public function test_export_requires_export_permission(): void
    {
        Storage::fake('local');

        $this->send('noRole', 'POST', '/api/v1/reports/adhoc', ['dataset' => 'benefits', 'measures' => ['count'], 'format' => 'csv'])
            ->assertStatus(403);
    }
}
