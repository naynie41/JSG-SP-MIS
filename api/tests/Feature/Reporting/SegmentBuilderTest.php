<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Support\CanonicalSchema;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Segments\CellSizeGuard;
use App\Domain\Reporting\Segments\SegmentAccess;
use App\Domain\Reporting\Segments\SegmentDefinition;
use App\Domain\Reporting\Segments\SegmentDimension;
use App\Domain\Reporting\Segments\SegmentDimensionRegistry;
use App\Domain\Reporting\Segments\SegmentReportService;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The filtered report builder (PRD FR-RPT-03, SECURITY.md §3).
 *
 * Two things are being tested at once and they pull in opposite directions: the builder
 * has to be genuinely useful for segmenting a population, and it must not become the
 * hole in the export matrix. A tool that lets anyone narrow a filter until one row
 * remains has turned "aggregate reporting" into a lookup of a named individual.
 */
class SegmentBuilderTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mine;

    private Mda $theirs;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mine = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->theirs = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['mdaAdmin'] = $this->user($this->mine, RoleKey::MdaAdmin);
        $this->users['otherMdaAdmin'] = $this->user($this->theirs, RoleKey::MdaAdmin);
        $this->users['coordination'] = $this->user($this->mine, RoleKey::SpCoordination);
        $this->users['executive'] = $this->user($this->mine, RoleKey::Executive);
        $this->users['partner'] = $this->user($this->mine, RoleKey::DevelopmentPartner);
        $this->users['sysAdmin'] = $this->user($this->mine, RoleKey::SystemAdministrator);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** A beneficiary of a given age today, so age-band filters have something to bite on. */
    private function person(Mda $owner, int $age, Gender $gender, Lga $lga, array $overrides = []): Beneficiary
    {
        return Beneficiary::factory()->create([
            'owner_mda_id' => $owner->id,
            'date_of_birth' => Carbon::today()->subYears($age)->subMonths(2)->toDateString(),
            'gender' => $gender,
            'lga' => $lga,
            ...$overrides,
        ]);
    }

    /* ------------------------------------------------- the catalogue is data-driven */

    public function test_identity_fields_are_never_offered_as_filters(): void
    {
        $keys = (new SegmentDimensionRegistry)->keys();

        foreach (['nin', 'bvn', 'phone', 'first_name', 'last_name', 'middle_name', 'full_name'] as $identity) {
            $this->assertNotContains(
                $identity,
                $keys,
                "“{$identity}” identifies a person, not a segment — filtering on it is a lookup wearing a report's clothes",
            );
        }
    }

    public function test_the_canonical_segmentable_fields_are_offered(): void
    {
        $keys = (new SegmentDimensionRegistry)->keys();

        foreach (array_keys(CanonicalSchema::segmentableFields()) as $field) {
            $this->assertContains($field, $keys);
        }

        // ...alongside the attributes SP-MIS stamps rather than receives.
        foreach (['programme', 'activity', 'registration_source', 'registration_date', 'status', 'household'] as $system) {
            $this->assertContains($system, $keys);
        }
    }

    public function test_the_registry_offers_exactly_what_the_schema_declares_segmentable(): void
    {
        // THE auto-expose contract. The registry does not keep its own list of canonical
        // dimensions — it derives them — so a field declared segmentable in DM.1 is
        // filterable the moment it exists, and a field that stops being segmentable
        // disappears just as automatically. Asserting equality (not containment) is what
        // makes that true in both directions.
        $declared = array_keys(CanonicalSchema::segmentableFields());
        $offered = array_keys(array_filter(
            (new SegmentDimensionRegistry)->all(),
            static fn (SegmentDimension $d): bool => $d->canonical,
        ));

        sort($declared);
        sort($offered);
        $this->assertSame($declared, $offered);
    }

    public function test_the_schema_refuses_to_declare_an_identity_field_segmentable(): void
    {
        // Belt and braces against a future edit: identity wins over the declaration, so
        // a mistaken `segment` on an identifier cannot open a filter on it.
        foreach (CanonicalSchema::segmentableFields() as $field => $_) {
            $this->assertFalse(
                CanonicalSchema::isIdentityField($field),
                "“{$field}” is an identity field and must never be segmentable",
            );
        }

        $this->assertNotSame([], CanonicalSchema::identityFields(), 'the guard needs identity fields to guard against');
    }

    public function test_a_non_identity_field_that_declares_nothing_is_not_offered(): void
    {
        // `address` is non-identity, but it is free text that narrows to a household or
        // a person. Silence is the safe default: a field becomes a filter because
        // someone decided it segments, not because nobody stopped it.
        $this->assertArrayHasKey('address', CanonicalSchema::FIELDS);
        $this->assertFalse(CanonicalSchema::isIdentityField('address'));
        $this->assertNotContains('address', (new SegmentDimensionRegistry)->keys());
    }

    public function test_the_household_grouping_fields_are_offered_too(): void
    {
        // DM.1's household fields are not columns on `beneficiaries` — they form the
        // household on import — but they describe a group as truly as gender does. The
        // query layer resolves them through the membership; the schema declares them.
        $this->assertContains('household_role', (new SegmentDimensionRegistry)->keys());
    }

    public function test_a_relationship_dimension_cannot_be_charted(): void
    {
        // It filters correctly but has no column on `beneficiaries` to group by, and
        // joining to get one would make the chart count memberships while the table
        // counts people.
        $registry = new SegmentDimensionRegistry;

        foreach (['programme', 'activity', 'household', 'household_role'] as $key) {
            $this->assertFalse($registry->get($key)?->groupable, "{$key} must not be groupable");
        }

        $this->assertTrue($registry->get('gender')?->groupable);
        $this->assertTrue($registry->get('lga')?->groupable);
    }

    public function test_filtering_by_household_role_uses_the_open_membership(): void
    {
        $head = $this->person($this->mine, 40, Gender::Male, Lga::Dutse);
        $child = $this->person($this->mine, 9, Gender::Female, Lga::Dutse);
        $former = $this->person($this->mine, 30, Gender::Female, Lga::Dutse);

        $household = Household::factory()->create(['owner_mda_id' => $this->mine->id]);
        $this->member($household->id, $head->id, 'head');
        $this->member($household->id, $child->id, 'child');
        // Left the household: history, not a current head.
        $this->member($household->id, $former->id, 'head', left: true);

        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [
            'filters' => ['household_role' => ['values' => ['head']]],
        ])->assertOk()->assertJsonPath('data.total', 1);
    }

    private function member(string $householdId, string $beneficiaryId, string $role, bool $left = false): void
    {
        HouseholdMembership::query()->create([
            'household_id' => $householdId,
            'beneficiary_id' => $beneficiaryId,
            'role_in_household' => $role,
            'joined_at' => now()->subMonth(),
            'left_at' => $left ? now()->subDay() : null,
        ]);
    }
    /* -------------------------------------------------------- filter composition */

    public function test_the_composed_filter_returns_exactly_the_matching_people(): void
    {
        // The worked example from the brief: female + 20-25 + Dutse.
        $wanted = $this->person($this->mine, 22, Gender::Female, Lga::Dutse);
        $this->person($this->mine, 22, Gender::Male, Lga::Dutse);      // wrong gender
        $this->person($this->mine, 31, Gender::Female, Lga::Dutse);    // outside the band
        $this->person($this->mine, 22, Gender::Female, Lga::Gumel);    // wrong LGA

        $response = $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [
            'filters' => [
                'gender' => ['values' => ['female']],
                'date_of_birth' => ['op' => 'between', 'values' => ['20', '25']],
                'lga' => ['values' => ['dutse']],
            ],
        ])->assertOk();

        $response->assertJsonPath('data.total', 1);
        $this->assertSame($wanted->first_name, $response->json('data.rows.0.first_name'));
    }

    public function test_a_multi_select_within_one_dimension_is_an_or(): void
    {
        $this->person($this->mine, 30, Gender::Female, Lga::Dutse);
        $this->person($this->mine, 30, Gender::Female, Lga::Gumel);
        $this->person($this->mine, 30, Gender::Female, Lga::Ringim);

        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [
            'filters' => ['lga' => ['values' => ['dutse', 'gumel']]],
        ])->assertOk()->assertJsonPath('data.total', 2);
    }

    public function test_the_age_band_is_inclusive_at_both_ends(): void
    {
        // Someone who turned 25 yesterday is 25, and belongs in a 20-25 band. An
        // exclusive upper bound would quietly drop a whole birth-year from every report.
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mine->id,
            'date_of_birth' => Carbon::today()->subYears(25)->subDay()->toDateString(),
        ]);
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mine->id,
            'date_of_birth' => Carbon::today()->subYears(26)->subDay()->toDateString(),
        ]);

        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [
            'filters' => ['date_of_birth' => ['op' => 'between', 'values' => ['20', '25']]],
        ])->assertOk()->assertJsonPath('data.total', 1);
    }

    public function test_an_unknown_filter_is_refused_rather_than_ignored(): void
    {
        // Silently dropping it would hand back a WIDER population than the caller asked
        // for, while they believed they had narrowed it.
        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [
            'filters' => ['nin' => ['values' => ['22200000011']]],
        ])->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');
    }

    /* ------------------------------------------------------ scope + export matrix */

    public function test_an_mda_admin_sees_only_its_own_beneficiaries(): void
    {
        $this->person($this->mine, 30, Gender::Female, Lga::Dutse);
        $this->person($this->theirs, 30, Gender::Female, Lga::Dutse);

        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [])
            ->assertOk()->assertJsonPath('data.total', 1);
    }

    public function test_sp_coordination_segments_across_mdas(): void
    {
        $this->person($this->mine, 30, Gender::Female, Lga::Dutse);
        $this->person($this->theirs, 30, Gender::Female, Lga::Dutse);

        $this->send('coordination', 'POST', '/api/v1/reports/segments/preview', [])
            ->assertOk()->assertJsonPath('data.total', 2);
    }

    public function test_an_executive_gets_counts_and_never_rows(): void
    {
        for ($i = 0; $i < 9; $i++) {
            $this->person($this->mine, 30, Gender::Female, Lga::Dutse);
        }

        $response = $this->send('executive', 'POST', '/api/v1/reports/segments/preview', [])->assertOk();

        $response->assertJsonPath('data.tier', 'aggregate');
        $this->assertSame([], $response->json('data.rows'), 'an Executive never pulls the raw registry');
        $this->assertSame([], $response->json('data.columns'));
        $this->assertSame(9, $response->json('data.total'));
    }

    public function test_a_partner_gets_counts_and_never_rows(): void
    {
        for ($i = 0; $i < 9; $i++) {
            $this->person($this->mine, 30, Gender::Female, Lga::Dutse);
        }

        $response = $this->send('partner', 'POST', '/api/v1/reports/segments/preview', [])->assertOk();

        $response->assertJsonPath('data.tier', 'aggregate');
        $this->assertSame([], $response->json('data.rows'));
    }

    /* ------------------------------------------------------------------- masking */

    public function test_identifiers_are_masked_unless_the_caller_may_reveal_them(): void
    {
        $this->person($this->mine, 30, Gender::Female, Lga::Dutse, ['nin' => '22200000011']);

        $masked = $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [])->assertOk();
        $this->assertFalse($masked->json('data.reveal_pii'), 'an MDA Admin does not hold export.reveal_pii');

        // The reveal flag is what the exporter reads to decide masking; the run and the
        // audit entry both record which way it went.
        $revealing = $this->send('sysAdmin', 'POST', '/api/v1/reports/segments/preview', [])->assertOk();
        $this->assertTrue($revealing->json('data.reveal_pii'));
    }

    public function test_an_export_masks_identifiers_for_a_caller_without_reveal(): void
    {
        $this->person($this->mine, 30, Gender::Female, Lga::Dutse, ['nin' => '22200000011']);

        $access = SegmentAccess::forUser(
            $this->users['mdaAdmin'],
            app(DashboardScopeResolver::class)->forUser($this->users['mdaAdmin']),
        );
        $definition = SegmentDefinition::fromArray([], new SegmentDimensionRegistry);
        $data = app(SegmentReportService::class)->toReportData($definition, $access);

        $ninColumn = collect($data->columns)->firstWhere('key', 'nin');
        $this->assertTrue($ninColumn->sensitive);
        $this->assertStringNotContainsString('22200000011', $data->cell($data->rows[0], $ninColumn));
    }

    /* ---------------------------------------------------------- cell-size guard */

    public function test_small_groups_are_suppressed_for_an_aggregate_tier(): void
    {
        config(['reporting.min_cell_size' => 5]);

        // 6 women, 2 men: the men are a group of 2 and must not be published.
        for ($i = 0; $i < 6; $i++) {
            $this->person($this->mine, 30, Gender::Female, Lga::Dutse);
        }
        for ($i = 0; $i < 2; $i++) {
            $this->person($this->mine, 30, Gender::Male, Lga::Dutse);
        }

        $response = $this->send('executive', 'POST', '/api/v1/reports/segments/preview', [
            'breakdown' => 'gender',
        ])->assertOk();

        $groups = collect($response->json('data.breakdown.groups'))->keyBy('key');
        $this->assertSame(6, $groups['female']['count']);
        $this->assertNull($groups['male']['count'], 'a group of 2 re-identifies people in a small ward');
        $this->assertTrue($groups['male']['suppressed']);
        $this->assertSame(1, $response->json('data.breakdown.suppressed_groups'));
    }

    public function test_the_guard_does_not_apply_to_an_mda_segmenting_its_own_people(): void
    {
        config(['reporting.min_cell_size' => 5]);

        for ($i = 0; $i < 6; $i++) {
            $this->person($this->mine, 30, Gender::Female, Lga::Dutse);
        }
        for ($i = 0; $i < 2; $i++) {
            $this->person($this->mine, 30, Gender::Male, Lga::Dutse);
        }

        $response = $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/preview', [
            'breakdown' => 'gender',
        ])->assertOk();

        $this->assertFalse($response->json('data.cell_size_guard'));

        $groups = collect($response->json('data.breakdown.groups'))->keyBy('key');
        // It owns these records — there is nothing to re-identify, and suppressing here
        // would break ordinary operational work.
        $this->assertSame(2, $groups['male']['count']);
    }

    public function test_the_guard_applies_to_cross_mda_aggregates(): void
    {
        config(['reporting.min_cell_size' => 5]);

        for ($i = 0; $i < 2; $i++) {
            $this->person($this->theirs, 30, Gender::Male, Lga::Dutse);
        }

        $response = $this->send('coordination', 'POST', '/api/v1/reports/segments/preview', [
            'breakdown' => 'gender',
        ])->assertOk();

        // SP Coordination sees across MDAs, and those people are not theirs.
        $this->assertTrue($response->json('data.cell_size_guard'));
        $this->assertTrue(collect($response->json('data.breakdown.groups'))->firstWhere('key', 'male')['suppressed']);
    }

    public function test_a_whole_segment_smaller_than_the_minimum_is_itself_suppressed(): void
    {
        config(['reporting.min_cell_size' => 5]);

        $this->person($this->mine, 22, Gender::Female, Lga::Dutse);

        // Without this, narrowing the filters until one person matches and reading the
        // TOTAL would defeat every suppressed group underneath it.
        $response = $this->send('executive', 'POST', '/api/v1/reports/segments/preview', [
            'filters' => ['gender' => ['values' => ['female']]],
        ])->assertOk();

        $this->assertNull($response->json('data.total'));
        $this->assertTrue($response->json('data.total_suppressed'));
    }

    public function test_the_minimum_is_configurable(): void
    {
        config(['reporting.min_cell_size' => 3]);
        $this->assertSame(3, (new CellSizeGuard)->minimum());

        config(['reporting.min_cell_size' => 10]);
        $this->assertSame(10, (new CellSizeGuard)->minimum());
    }

    /* ------------------------------------------------------------ export + audit */

    public function test_an_export_is_queued_and_audited_with_the_query_definition(): void
    {
        $this->person($this->mine, 22, Gender::Female, Lga::Dutse);

        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/export', [
            'filters' => [
                'gender' => ['values' => ['female']],
                'lga' => ['values' => ['dutse']],
            ],
            'format' => 'csv',
        ])->assertStatus(202);

        $log = AuditLog::query()->withoutGlobalScopes()
            ->where('action', 'report.segment_exported')->firstOrFail();

        // Who ran what filter, when, under which scope, how many rows, PII or not.
        $this->assertSame(['female'], $log->after['definition']['filters']['gender']['values']);
        $this->assertSame('rows', $log->after['tier']);
        $this->assertSame(1, $log->after['row_count']);
        $this->assertFalse($log->after['reveal_pii']);
        $this->assertSame($this->users['mdaAdmin']->id, $log->actor_id);
    }

    public function test_a_queued_run_stores_the_definition_and_the_entitlement(): void
    {
        $this->person($this->mine, 22, Gender::Female, Lga::Dutse);

        $runId = $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/export', [
            'filters' => ['gender' => ['values' => ['female']]],
            'format' => 'csv',
        ])->assertStatus(202)->json('data.id');

        $run = ReportRun::query()->withoutGlobalScopes()->findOrFail($runId);

        $this->assertSame('segment', $run->report_key);
        $this->assertSame(['female'], $run->definition['filters']['gender']['values']);
        $this->assertSame('rows', $run->params['tier']);
        // The scope is captured too, so the queued job cannot re-resolve a wider one.
        $this->assertSame([$this->mine->id], $run->scope_mda_ids);
    }

    public function test_a_user_without_reporting_export_cannot_export(): void
    {
        $officer = User::factory()->create([
            'mda_id' => $this->mine->id,
            'role_id' => Role::where('key', RoleKey::MneOfficer->value)->firstOrFail()->id,
        ]);
        $this->users['stripped'] = $officer;

        $officer->role->permissions()->detach(
            Permission::where('key', 'reporting.export')->firstOrFail()->id
        );

        $this->send('stripped', 'POST', '/api/v1/reports/segments/export', ['format' => 'csv'])
            ->assertStatus(403);
    }
}
