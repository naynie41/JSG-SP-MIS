<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

/**
 * The one-off migration of each activity's single LGA/Ward into the location set.
 *
 * The migration itself has already run by the time a test boots, so these exercise its
 * `backfill()` logic directly against a reconstructed pre-migration state — the only
 * way to assert on data that no longer exists in the schema.
 */
class ActivityLocationBackfillTest extends TestCase
{
    use RefreshDatabase;

    private function migration(): object
    {
        return require database_path('migrations/2026_08_15_100000_create_activity_locations_table.php');
    }

    /** Re-adds the dropped columns so the pre-migration shape can be rebuilt. */
    private function restoreOldColumns(): void
    {
        Schema::table('activities', function ($table): void {
            $table->string('lga')->nullable();
            $table->string('ward')->nullable();
        });
    }

    private function activityWith(?string $lga, ?string $ward): string
    {
        $id = (string) Str::uuid7();

        DB::table('activities')->insert([
            'id' => $id,
            'programme_id' => Programme::factory()->create()->id,
            'owner_mda_id' => Mda::factory()->create()->id,
            'involves_beneficiaries' => false,
            'name' => 'Legacy activity '.substr($id, 0, 8),
            'status' => 'active',
            'lga' => $lga,
            'ward' => $ward,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id;
    }

    /** @return array{0: Lga, 1: Ward} */
    private function reference(): array
    {
        $lga = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        $ward = Ward::factory()->create(['lga_id' => $lga->id, 'code' => 'limawa', 'name' => 'Limawa']);

        return [$lga, $ward];
    }

    private function runBackfill(): void
    {
        $migration = $this->migration();
        $method = new \ReflectionMethod($migration, 'backfill');
        $method->invoke($migration);
    }

    public function test_a_single_lga_and_ward_become_one_location_row(): void
    {
        [$lga, $ward] = $this->reference();
        $this->restoreOldColumns();
        $activityId = $this->activityWith('dutse', 'Limawa');

        $this->runBackfill();

        $this->assertDatabaseHas('activity_locations', [
            'activity_id' => $activityId,
            'lga_id' => $lga->id,
            'ward_id' => $ward->id,
        ]);
        $this->assertSame(1, DB::table('activity_locations')->count());
    }

    public function test_an_activity_with_an_lga_but_no_ward_becomes_a_whole_lga_row(): void
    {
        [$lga] = $this->reference();
        $this->restoreOldColumns();
        $activityId = $this->activityWith('dutse', null);

        $this->runBackfill();

        $this->assertDatabaseHas('activity_locations', [
            'activity_id' => $activityId,
            'lga_id' => $lga->id,
            'ward_id' => null,
        ]);
    }

    public function test_an_unresolvable_ward_keeps_the_lga_as_a_whole_lga_row(): void
    {
        // The LGA resolved but the free-text ward did not. The activity demonstrably
        // operates in that LGA, and that much is true even when the ward string is not —
        // dropping the row entirely would lose a fact the old data actually established.
        [$lga] = $this->reference();
        $this->restoreOldColumns();
        $activityId = $this->activityWith('dutse', 'Ward 7');

        $this->runBackfill();

        $this->assertDatabaseHas('activity_locations', [
            'activity_id' => $activityId,
            'lga_id' => $lga->id,
            'ward_id' => null,
        ]);
    }

    public function test_ward_resolution_is_scoped_to_its_own_lga(): void
    {
        // A ward name that exists in a DIFFERENT LGA must not be matched — ward names
        // repeat across Jigawa, and a state-wide match attaches activities to the wrong place.
        [$dutse] = $this->reference();
        $kiyawa = Lga::factory()->forEnum(LgaEnum::Kiyawa)->create();
        Ward::factory()->create(['lga_id' => $kiyawa->id, 'code' => 'kwanda', 'name' => 'Kwanda']);

        $this->restoreOldColumns();
        $activityId = $this->activityWith('dutse', 'Kwanda'); // Kwanda is in Kiyawa, not Dutse

        $this->runBackfill();

        $row = DB::table('activity_locations')->where('activity_id', $activityId)->first();
        $this->assertNotNull($row);
        $this->assertSame($dutse->id, $row->lga_id);
        $this->assertNull($row->ward_id, 'A ward from another LGA must not be matched.');
    }

    public function test_an_unknown_lga_is_reported_and_produces_no_row(): void
    {
        $this->reference();
        $this->restoreOldColumns();
        $this->activityWith('atlantis', 'Somewhere');

        $this->runBackfill();

        $this->assertSame(0, DB::table('activity_locations')->count());

        // The raw values survive in the audit log, so the column drop is not a silent loss.
        $entry = DB::table('audit_log')->where('action', 'activity.locations.migrated')->latest('created_at')->first();
        $this->assertNotNull($entry);
        $payload = json_decode((string) $entry->after, true);
        $this->assertSame(1, $payload['unresolved_count']);
        $this->assertSame('unknown_lga', $payload['unresolved'][0]['reason']);
        $this->assertSame('atlantis', $payload['unresolved'][0]['lga']);
    }

    public function test_activities_without_an_lga_are_skipped(): void
    {
        $this->reference();
        $this->restoreOldColumns();
        $this->activityWith(null, null);

        $this->runBackfill();

        $this->assertSame(0, DB::table('activity_locations')->count());
    }

    public function test_the_backfill_refuses_to_run_when_reference_data_is_missing(): void
    {
        // The realistic load-order mistake: migrating before the GEO.1 dataset is loaded.
        // Every activity would be unresolved and the NEXT migration drops the columns —
        // so this must fail rather than quietly destroy the values.
        $this->restoreOldColumns();
        $this->activityWith('dutse', 'Limawa');

        $this->assertSame(0, Lga::query()->count());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/reference:load-divisions/');

        $this->runBackfill();
    }

    public function test_nothing_is_written_when_the_backfill_refuses(): void
    {
        $this->restoreOldColumns();
        $this->activityWith('dutse', 'Limawa');

        try {
            $this->runBackfill();
        } catch (RuntimeException) {
            // expected
        }

        $this->assertSame(0, DB::table('activity_locations')->count());
    }

    public function test_the_single_location_columns_are_gone(): void
    {
        // The replacement is complete, not additive: leaving the old columns would let a
        // caller keep writing a second, contradictory location.
        $this->assertFalse(Schema::hasColumn('activities', 'lga'));
        $this->assertFalse(Schema::hasColumn('activities', 'ward'));

        // ...while free-prose location detail is unaffected.
        $this->assertTrue(Schema::hasColumn('activities', 'location_description'));
    }

    public function test_a_whole_lga_row_cannot_be_duplicated(): void
    {
        // Partial unique index: NULLs are distinct in a plain SQL unique, so without it
        // an activity could declare the same whole LGA any number of times.
        [$lga] = $this->reference();
        $activityId = Activity::factory()->create()->id;

        DB::table('activity_locations')->insert([
            'id' => (string) Str::uuid7(), 'activity_id' => $activityId,
            'lga_id' => $lga->id, 'ward_id' => null, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->expectException(QueryException::class);

        DB::table('activity_locations')->insert([
            'id' => (string) Str::uuid7(), 'activity_id' => $activityId,
            'lga_id' => $lga->id, 'ward_id' => null, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_the_same_ward_cannot_be_declared_twice_for_one_activity(): void
    {
        [$lga, $ward] = $this->reference();
        $activityId = Activity::factory()->create()->id;

        $row = fn (): array => [
            'id' => (string) Str::uuid7(), 'activity_id' => $activityId,
            'lga_id' => $lga->id, 'ward_id' => $ward->id, 'created_at' => now(), 'updated_at' => now(),
        ];

        DB::table('activity_locations')->insert($row());

        $this->expectException(QueryException::class);
        DB::table('activity_locations')->insert($row());
    }
}
