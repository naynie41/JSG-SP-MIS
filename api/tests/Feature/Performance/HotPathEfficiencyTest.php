<?php

declare(strict_types=1);

namespace Tests\Feature\Performance;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\MdaAccessGrant;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Services\SyncEngine;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * Query efficiency on the paths added AFTER the original performance pass
 * (NFR-PERF-01, NFR-SCAL-01).
 *
 * {@see QueryEfficiencyTest} pins the two list endpoints that existed when it was
 * written. Everything since — the duplicate queue, the data-sharing report, the
 * graduation record, and connector sync — has its own per-row work, and a per-row query
 * in a loop that runs over a whole import is the difference between a sync that finishes
 * and one that times out.
 *
 * These assert GROWTH, not an absolute count: the same work at 5 rows and at 25 rows must
 * cost about the same number of queries. An absolute ceiling drifts as legitimate joins
 * are added; a growth check only fails when the cost became per-row, which is the actual
 * defect.
 */
class HotPathEfficiencyTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    private Mda $mda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->users['admin'] = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->users['sysAdmin'] = $this->user(null, RoleKey::SystemAdministrator);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** @return int queries issued while running $work */
    private function queriesDuring(callable $work): int
    {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $work();
        $count = count(DB::getQueryLog());
        DB::disableQueryLog();

        return $count;
    }

    private function queriesFor(string $key, string $url): int
    {
        $token = $this->users[$key]->createToken('t')->plainTextToken;

        $count = $this->queriesDuring(function () use ($token, $url): void {
            $this->withToken($token)->getJson($url)->assertOk();
        });
        $this->app['auth']->forgetGuards();

        return $count;
    }

    /* ------------------------------------------------------- the duplicate queue */

    public function test_the_duplicate_queue_does_not_query_per_flagged_row(): void
    {
        // The queue exists to clear a backlog, so it is read when the backlog is LARGE.
        // Resolving each flagged row's matched record individually is exactly the shape
        // that makes a queue slower the more work it has.
        $batch = ImportBatch::create([
            'owner_mda_id' => $this->mda->id,
            'original_filename' => 'flagged.csv',
            'stored_path' => 'imports/flagged.csv',
            'source' => 'csv',
            'status' => 'preview_ready',
        ]);

        $rows = function (int $from, int $to) use ($batch): void {
            for ($i = $from; $i <= $to; $i++) {
                $existing = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
                ImportRow::query()->create([
                    'import_batch_id' => $batch->id,
                    'row_number' => $i,
                    'payload' => ['first_name' => 'Aisha', 'last_name' => 'Bello'.$i],
                    'is_valid' => true,
                    'match_band' => 'exact',
                    'match_candidates' => [[
                        'type' => 'registry', 'reference' => $existing->id, 'band' => 'exact',
                        'score' => 1.0, 'matched_fields' => ['nin'], 'comparison' => [], 'stage' => 'deterministic',
                    ]],
                ]);
            }
        };

        $rows(1, 5);
        $small = $this->queriesFor('admin', '/api/v1/beneficiaries/duplicates?per_page=25');

        $rows(6, 25);
        $large = $this->queriesFor('admin', '/api/v1/beneficiaries/duplicates?per_page=25');

        $this->assertLessThanOrEqual(
            $small + 3,
            $large,
            "Five flagged rows cost {$small} queries and twenty-five cost {$large}: the queue is resolving per row.",
        );
    }

    /* --------------------------------------------------- the data-sharing report */

    public function test_the_data_sharing_report_does_not_query_per_grant(): void
    {
        $sysAdmin = $this->users['sysAdmin'];

        $grants = function (int $count) use ($sysAdmin): void {
            for ($i = 0; $i < $count; $i++) {
                $target = Mda::factory()->create();
                MdaAccessGrant::query()->create([
                    'user_id' => $this->user($target, RoleKey::MdaAdmin)->id,
                    'mda_id' => $target->id,
                    'reason' => 'M&E review',
                    'granted_by' => $sysAdmin->id,
                ]);
            }
        };

        $grants(3);
        $small = $this->queriesFor('sysAdmin', '/api/v1/data-sharing/grants');

        $grants(17);
        $large = $this->queriesFor('sysAdmin', '/api/v1/data-sharing/grants');

        $this->assertLessThanOrEqual(
            $small + 3,
            $large,
            "Three grants cost {$small} queries and twenty cost {$large}: the report is resolving per grant.",
        );
    }

    /* ------------------------------------------------------- connector sync */

    public function test_a_sync_run_does_not_query_per_record_beyond_the_pipeline(): void
    {
        // Sync processes a whole feed in one job. Per-record overhead multiplies by the
        // size of the source, so a small constant added here is a large cost at scale.
        $programme = Programme::factory()->create();
        $activity = Activity::factory()->forProgramme($programme, $this->mda)->create([
            'status' => 'active',
            'created_by' => $this->users['admin']->id,
        ]);
        $connector = $this->confirmConnectorMapping(
            SyncConnector::factory()->create([
                'owner_mda_id' => $this->mda->id,
                'source' => RegistrationSource::Socu,
                'conflict_policy' => ConflictPolicy::FlagForReview,
                'activity_id' => $activity->id,
            ]),
            $this->users['sysAdmin'],
        );

        $records = function (int $count): array {
            $out = [];
            for ($i = 1; $i <= $count; $i++) {
                $out[] = [
                    'first_name' => 'Ada', 'last_name' => 'Okoye'.$i,
                    'nin' => str_pad((string) (30000000000 + $i), 11, '0', STR_PAD_LEFT),
                    'id' => 'SOCU-'.$i,
                ];
            }

            return $out;
        };

        config(['sync.mock_records.socu' => $records(4)]);
        $small = $this->queriesDuring(fn () => app(SyncEngine::class)->runConnector($connector, SyncTrigger::Scheduled));

        config(['sync.mock_records.socu' => $records(20)]);
        $large = $this->queriesDuring(fn () => app(SyncEngine::class)->runConnector($connector, SyncTrigger::Scheduled));

        // Per-record pipeline work is inherent (validate, screen, register, enrol), so
        // this is not a flat ceiling. What it pins is the RATE: 5x the records must not
        // cost meaningfully more than 5x the per-record cost.
        $perRecordSmall = $small / 4;
        $perRecordLarge = $large / 20;

        $this->assertLessThanOrEqual(
            $perRecordSmall * 1.3,
            $perRecordLarge,
            "Per-record query cost rose from {$perRecordSmall} to {$perRecordLarge}: sync work is super-linear.",
        );
    }
}
