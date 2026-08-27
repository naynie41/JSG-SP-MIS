<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Reporting\Reports\AdHoc\AdHocDatasetRegistry;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Support\DashboardFilter;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * The reporting layer must not name columns the schema no longer has.
 *
 * `activities.lga` / `.ward` were dropped when an activity's area became a SET
 * (`activity_locations`), but the reporting layer kept selecting and filtering on them.
 * On Postgres that is a hard 500 on the dashboard; the suite never saw it, because
 * SQLite reads a double-quoted identifier that matches no column as a STRING LITERAL —
 * so `select "lga"` returns the word "lga" and `where "lga" = 'dutse'` quietly matches
 * nothing. Every assertion here is therefore written against OBSERVABLE behaviour or the
 * schema itself, never against "the query did not throw", which SQLite cannot tell us.
 */
class DroppedLocationColumnsTest extends TestCase
{
    use RefreshDatabase;

    /* ------------------------------------------------------------ the schema itself */

    public function test_the_single_activity_location_columns_are_gone(): void
    {
        // The premise of everything below. If this ever fails the migration was rolled
        // back and the rest of this file is testing the wrong world.
        $this->assertFalse(Schema::hasColumn('activities', 'lga'));
        $this->assertFalse(Schema::hasColumn('activities', 'ward'));
    }

    public function test_every_ad_hoc_dimension_and_filter_names_a_real_column(): void
    {
        // The registry is the single source of truth for the report builder AND for the
        // catalogue the UI renders, so a stale column here is an offered dimension that
        // 500s the moment anyone picks it.
        foreach (AdHocDatasetRegistry::DATASETS as $dataset => $config) {
            /** @var class-string<Model> $modelClass */
            $modelClass = $config['model'];
            $table = (new $modelClass)->getTable();

            foreach (['dimensions', 'filters'] as $section) {
                foreach ($config[$section] as $key => $spec) {
                    $column = $spec['column'] ?? null;
                    if (! is_string($column) || $column === '') {
                        continue;
                    }

                    $this->assertTrue(
                        Schema::hasColumn($table, $column),
                        "Dataset [{$dataset}] {$section}.{$key} names {$table}.{$column}, which does not exist.",
                    );
                }
            }
        }
    }

    /* -------------------------------------------------------- observable behaviour */

    public function test_an_area_filter_counts_an_activity_by_its_declared_location(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $mda = Mda::factory()->create();
        $exec = User::factory()->create([
            'mda_id' => null,
            'role_id' => Role::where('key', RoleKey::Executive->value)->firstOrFail()->id,
        ]);

        $programme = Programme::factory()->create();
        Activity::factory()->forProgramme($programme, $mda)->inLgaCode('dutse')
            ->create(['status' => 'active', 'budget_amount' => 1_000_000, 'target_beneficiaries' => 4]);
        Activity::factory()->forProgramme($programme, $mda)->inLgaCode('hadejia')
            ->create(['status' => 'active', 'budget_amount' => 500_000, 'target_beneficiaries' => 2]);

        $dashboard = app(DashboardService::class)->forUser($exec, new DashboardFilter(lga: 'dutse'));
        $row = collect($dashboard['metrics']['programme_performance'])
            ->firstWhere('programme_id', $programme->id);

        // Narrowed to the LGA the activity DECLARED — not zeroed because the filter went
        // looking for a column the table lost.
        $this->assertNotNull($row, 'The filtered programme disappeared from performance.');
        $this->assertSame(1_000_000, $row['budget']['allocated']);
        $this->assertSame(4, $row['target']);
    }

    public function test_no_reporting_query_selects_a_column_activities_no_longer_has(): void
    {
        // SQLite will not raise on `select "lga" from activities`, so this inspects the
        // SQL the reporting layer actually emits. It is the only way this suite can see
        // the failure Postgres reports as a 500.
        $this->seed(RolesAndPermissionsSeeder::class);

        $partner = User::factory()->create([
            'mda_id' => null,
            'role_id' => Role::where('key', RoleKey::DevelopmentPartner->value)->firstOrFail()->id,
        ]);
        $mda = Mda::factory()->create();
        $programme = Programme::factory()->create();
        Activity::factory()->forProgramme($programme, $mda)->inLgaCode('dutse')->create([
            'status' => 'active',
            'budget_amount' => 1_000_000,
            'funding_partner_id' => $partner->id,
        ]);

        $offenders = [];
        DB::listen(function ($query) use (&$offenders): void {
            foreach (['lga', 'ward'] as $gone) {
                if (preg_match('/"activities"\."'.$gone.'"|select[^;]*"'.$gone.'"[^;]*from "activities"/i', $query->sql) === 1) {
                    $offenders[] = $query->sql;
                }
            }
        });

        app(DashboardService::class)->forUser($partner);

        $this->assertSame([], $offenders, 'Reporting still queries a dropped activities column.');
    }
}
