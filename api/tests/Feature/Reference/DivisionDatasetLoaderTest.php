<?php

declare(strict_types=1);

namespace Tests\Feature\Reference;

use App\Domain\Reference\Imports\AdministrativeDivisionLoader;
use App\Domain\Reference\Imports\ReferenceDatasetException;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Reference\Services\ReferenceDataCache;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use Database\Seeders\AdministrativeDivisionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The loader's central obligation: load exactly what the maintainer supplied, and when
 * that is not possible, fail loudly and load NOTHING.
 */
class DivisionDatasetLoaderTest extends TestCase
{
    use RefreshDatabase;

    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = storage_path('framework/testing/reference-'.bin2hex(random_bytes(4)));
        mkdir($this->dir, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir.'/*') ?: [] as $file) {
            unlink($file);
        }
        @rmdir($this->dir);

        parent::tearDown();
    }

    private function loader(): AdministrativeDivisionLoader
    {
        return app(AdministrativeDivisionLoader::class);
    }

    private function write(string $name, string $contents): string
    {
        $path = $this->dir.'/'.$name;
        file_put_contents($path, $contents);

        return $path;
    }

    /**
     * A complete, well-formed dataset covering all 27 LGAs.
     *
     * Ward names here are SYNTHETIC test fixtures, deliberately unmistakable — the real
     * ones exist only in the maintainer's file.
     */
    private function completeCsv(int $wardsPerLga = 2): string
    {
        $lines = ['lga_name,ward_name'];

        foreach (LgaEnum::cases() as $lga) {
            for ($i = 1; $i <= $wardsPerLga; $i++) {
                $lines[] = $lga->label().',Test Ward '.$i;
            }
        }

        return implode("\n", $lines)."\n";
    }

    // ------------------------------------------------- fail loudly, load nothing

    public function test_a_missing_file_throws_with_a_clear_actionable_message(): void
    {
        $path = $this->dir.'/does-not-exist.csv';

        try {
            $this->loader()->loadFromFile($path);
            $this->fail('Expected a missing dataset to throw.');
        } catch (ReferenceDatasetException $e) {
            $this->assertStringContainsString($path, $e->getMessage());
            $this->assertStringContainsString('will NOT invent', $e->getMessage());
            // It must say where to get a real one, not just that it failed.
            $this->assertStringContainsString('HDX', $e->getMessage());
            $this->assertStringContainsString('GRID3', $e->getMessage());
        }

        $this->assertSame(0, Lga::query()->count());
        $this->assertSame(0, Ward::query()->count());
    }

    public function test_the_seeder_fails_loudly_when_the_dataset_is_absent(): void
    {
        config(['reference.divisions.path' => $this->dir.'/absent.csv']);

        $this->expectException(ReferenceDatasetException::class);
        $this->expectExceptionMessageMatches('/not found/');

        $this->seed(AdministrativeDivisionsSeeder::class);
    }

    public function test_the_seeder_seeds_no_placeholder_data_when_it_fails(): void
    {
        config(['reference.divisions.path' => $this->dir.'/absent.csv']);

        try {
            $this->seed(AdministrativeDivisionsSeeder::class);
        } catch (ReferenceDatasetException) {
            // expected
        }

        // The failure mode this whole design exists to prevent.
        $this->assertSame(0, Lga::query()->count());
        $this->assertSame(0, Ward::query()->count());
    }

    public function test_the_artisan_command_exits_nonzero_when_the_dataset_is_absent(): void
    {
        $this->artisan('reference:load-divisions', ['file' => $this->dir.'/absent.csv'])
            ->assertExitCode(1);

        $this->assertSame(0, Lga::query()->count());
    }

    public function test_an_empty_file_is_an_error_not_a_silent_success(): void
    {
        $path = $this->write('empty.csv', "lga_name,ward_name\n");

        $this->expectException(ReferenceDatasetException::class);
        $this->loader()->loadFromFile($path);
    }

    public function test_a_file_missing_required_columns_is_rejected(): void
    {
        $path = $this->write('bad.csv', "district,village\nDutse,Somewhere\n");

        try {
            $this->loader()->loadFromFile($path);
            $this->fail('Expected missing columns to throw.');
        } catch (ReferenceDatasetException $e) {
            $this->assertStringContainsString('lga_name', $e->getMessage());
            $this->assertStringContainsString('district, village', $e->getMessage());
        }
    }

    public function test_an_unsupported_format_is_rejected(): void
    {
        $path = $this->write('data.xlsx', 'binary');

        $this->expectException(ReferenceDatasetException::class);
        $this->expectExceptionMessageMatches('/Unsupported dataset format/');

        $this->loader()->loadFromFile($path);
    }

    // ------------------------------------------------- "looks authoritative" guards

    public function test_a_dataset_naming_non_jigawa_lgas_is_rejected(): void
    {
        $csv = $this->completeCsv()."Ikeja,Test Ward 1\nKano Municipal,Test Ward 1\n";
        $path = $this->write('national.csv', $csv);

        try {
            $this->loader()->loadFromFile($path);
            $this->fail('Expected non-Jigawa LGAs to throw.');
        } catch (ReferenceDatasetException $e) {
            $this->assertStringContainsString('ikeja', $e->getMessage());
            $this->assertStringContainsString('kano_municipal', $e->getMessage());
        }

        $this->assertSame(0, Lga::query()->count(), 'Nothing may be written when validation fails.');
    }

    public function test_a_partial_dataset_is_rejected_rather_than_partially_loaded(): void
    {
        // 26 of 27 — the dangerous case: a lookup table silently missing a real LGA.
        $lines = ['lga_name,ward_name'];
        foreach (LgaEnum::cases() as $lga) {
            if ($lga === LgaEnum::Yankwashi) {
                continue;
            }
            $lines[] = $lga->label().',Test Ward 1';
        }
        $path = $this->write('partial.csv', implode("\n", $lines)."\n");

        try {
            $this->loader()->loadFromFile($path);
            $this->fail('Expected a partial dataset to throw.');
        } catch (ReferenceDatasetException $e) {
            $this->assertStringContainsString('26 of', $e->getMessage());
            $this->assertStringContainsString('yankwashi', $e->getMessage());
        }

        $this->assertSame(0, Lga::query()->count());
    }

    public function test_the_shipped_example_file_cannot_be_seeded(): void
    {
        // The example documents the FORMAT. If copying it without editing could seed,
        // the repository would be one careless step from fabricated reference data.
        $example = database_path('data/administrative-divisions.example.csv');
        $this->assertFileExists($example);

        $this->expectException(ReferenceDatasetException::class);
        $this->loader()->loadFromFile($example);
    }

    public function test_contradictory_ward_names_for_one_code_are_rejected(): void
    {
        // "test-ward-1" slugs to the same code as "Test Ward 1" but is a different name —
        // a contradiction the loader must not resolve by guessing.
        $path = $this->write('conflict.csv', $this->completeCsv()."Dutse,test-ward-1\n");

        try {
            $this->loader()->loadFromFile($path);
            $this->fail('Expected conflicting ward names to throw.');
        } catch (ReferenceDatasetException $e) {
            $this->assertStringContainsString('two different names', $e->getMessage());
        }
    }

    // ------------------------------------------------- the happy path

    public function test_a_complete_dataset_loads_all_lgas_and_wards(): void
    {
        $path = $this->write('good.csv', $this->completeCsv(wardsPerLga: 3));

        $result = $this->loader()->loadFromFile($path);

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(27 * 3, Ward::query()->count());
        $this->assertSame(27, $result->lgasCreated);
        $this->assertSame(81, $result->wardsCreated);
        $this->assertSame([], $result->lgasWithoutWards);
    }

    public function test_codes_are_slugged_to_match_the_registry_enum(): void
    {
        $path = $this->write('good.csv', $this->completeCsv());

        $this->loader()->loadFromFile($path);

        // "Birnin Kudu" → birnin_kudu, the value beneficiaries.lga already holds.
        $this->assertDatabaseHas('lgas', ['code' => 'birnin_kudu', 'name' => 'Birnin Kudu']);
        $this->assertDatabaseHas('lgas', ['code' => 'kafin_hausa']);

        $stored = Lga::query()->pluck('code')->sort()->values()->all();
        $expected = collect(LgaEnum::cases())->map(fn (LgaEnum $l): string => $l->value)->sort()->values()->all();

        $this->assertSame($expected, $stored);
    }

    public function test_explicit_codes_in_the_file_are_honoured(): void
    {
        $lines = ['lga_code,lga_name,ward_code,ward_name'];
        foreach (LgaEnum::cases() as $lga) {
            $lines[] = $lga->value.','.$lga->label().',JG-W-'.$lga->value.',Test Ward 1';
        }
        $path = $this->write('coded.csv', implode("\n", $lines)."\n");

        $this->loader()->loadFromFile($path);

        $this->assertDatabaseHas('wards', ['code' => 'jg_w_dutse', 'name' => 'Test Ward 1']);
    }

    public function test_the_loader_is_idempotent(): void
    {
        $path = $this->write('good.csv', $this->completeCsv());

        $first = $this->loader()->loadFromFile($path);
        $second = $this->loader()->loadFromFile($path);

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(54, Ward::query()->count());

        $this->assertSame(27, $first->lgasCreated);
        $this->assertSame(0, $second->lgasCreated);
        $this->assertSame(27, $second->lgasUpdated);
        $this->assertSame(0, $second->wardsCreated);
        $this->assertSame(54, $second->wardsUpdated);
    }

    public function test_a_corrected_name_updates_in_place_when_the_file_carries_stable_ward_codes(): void
    {
        $csv = function (string $wardName): string {
            $lines = ['lga_code,lga_name,ward_code,ward_name'];
            foreach (LgaEnum::cases() as $lga) {
                $lines[] = $lga->value.','.$lga->label().',w1,'.$wardName;
            }

            return implode("\n", $lines)."\n";
        };

        $this->loader()->loadFromFile($this->write('v1.csv', $csv('Test Ward 1')));
        $result = $this->loader()->loadFromFile($this->write('v2.csv', $csv('Corrected Ward')));

        // Same code, new name — updated, not duplicated.
        $this->assertSame(27, Ward::query()->count());
        $this->assertSame(27, Ward::query()->where('name', 'Corrected Ward')->count());
        $this->assertSame(27, $result->wardsUpdated);
        $this->assertSame(0, $result->wardsCreated);
    }

    public function test_renaming_a_ward_without_a_stable_code_creates_a_new_row_and_reports_the_old_one(): void
    {
        // The consequence of deriving codes from names: identity IS the name. A dataset
        // that has no ward_code column cannot express "this ward was renamed" — the
        // loader sees a new ward and an absent one, which is exactly what it reports.
        // Datasets that need stable identity must carry ward_code. Documented in the README.
        $this->loader()->loadFromFile($this->write('v1.csv', $this->completeCsv(1)));

        $renamed = str_replace('Test Ward 1', 'Renamed Ward', $this->completeCsv(1));
        $result = $this->loader()->loadFromFile($this->write('v2.csv', $renamed));

        $this->assertSame(54, Ward::query()->count());
        $this->assertSame(27, $result->wardsCreated);
        $this->assertCount(27, $result->staleWards);
        $this->assertContains('dutse/test_ward_1', $result->staleWards);
    }

    public function test_wards_absent_from_a_new_file_are_reported_but_not_deleted(): void
    {
        $this->loader()->loadFromFile($this->write('v1.csv', $this->completeCsv(3)));

        $result = $this->loader()->loadFromFile($this->write('v2.csv', $this->completeCsv(1)));

        // Retained: a file that omits a ward is not the same claim as one that retires it.
        $this->assertSame(81, Ward::query()->count());
        $this->assertCount(54, $result->staleWards);
        $this->assertContains('dutse/test_ward_2', $result->staleWards);
    }

    public function test_an_lga_with_no_wards_is_kept_and_reported(): void
    {
        // A plausible ward TOTAL can hide an empty LGA; the per-LGA spread cannot.
        $csv = $this->completeCsv(1);
        $csv = str_replace("Yankwashi,Test Ward 1\n", "Yankwashi,\n", $csv);
        $result = $this->loader()->loadFromFile($this->write('gap.csv', $csv));

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(['yankwashi'], $result->lgasWithoutWards);
        $this->assertSame(0, $result->wardsPerLga['yankwashi']);
    }

    // ------------------------------------------------- formats

    public function test_a_flat_json_dataset_loads(): void
    {
        $records = [];
        foreach (LgaEnum::cases() as $lga) {
            $records[] = ['lga_name' => $lga->label(), 'ward_name' => 'Test Ward 1'];
        }
        $path = $this->write('flat.json', (string) json_encode($records));

        $this->loader()->loadFromFile($path);

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(27, Ward::query()->count());
    }

    public function test_a_nested_json_dataset_loads(): void
    {
        $records = [];
        foreach (LgaEnum::cases() as $lga) {
            $records[] = [
                'name' => $lga->label(),
                'wards' => [['name' => 'Test Ward 1'], ['name' => 'Test Ward 2']],
            ];
        }
        $path = $this->write('nested.json', (string) json_encode(['lgas' => $records]));

        $this->loader()->loadFromFile($path);

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(54, Ward::query()->count());
    }

    public function test_a_utf8_bom_and_blank_lines_are_tolerated(): void
    {
        $path = $this->write('bom.csv', "\xEF\xBB\xBF".$this->completeCsv()."\n\n");

        $this->loader()->loadFromFile($path);

        $this->assertSame(27, Lga::query()->count());
    }

    public function test_loading_invalidates_the_cached_lookups(): void
    {
        $cache = app(ReferenceDataCache::class);

        $this->assertSame([], $cache->lgas()); // caches the empty list

        $this->loader()->loadFromFile($this->write('good.csv', $this->completeCsv()));

        // Stale cache here would leave the API serving "no LGAs" after a successful seed.
        $this->assertCount(27, $cache->lgas());
    }

    // ------------------------------------------------- LGAs without a dataset

    public function test_seed_lgas_writes_the_27_lgas_and_no_wards(): void
    {
        // The legitimate intermediate state: LGAs known (they are committed reference
        // data), wards not yet supplied. Needed so the activity-location backfill can
        // resolve LGA values on a deploy that has no dataset file yet.
        $this->artisan('reference:seed-lgas')->assertExitCode(0);

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(0, Ward::query()->count(), 'Ward names are never generated.');

        $stored = Lga::query()->pluck('code')->sort()->values()->all();
        $expected = collect(LgaEnum::cases())->map(fn (LgaEnum $l): string => $l->value)->sort()->values()->all();
        $this->assertSame($expected, $stored);
        $this->assertDatabaseHas('lgas', ['code' => 'birnin_kudu', 'name' => 'Birnin Kudu', 'state' => 'Jigawa']);
    }

    public function test_seed_lgas_is_idempotent(): void
    {
        $this->artisan('reference:seed-lgas')->assertExitCode(0);
        $this->artisan('reference:seed-lgas')->assertExitCode(0);

        $this->assertSame(27, Lga::query()->count());
    }

    public function test_a_dataset_load_after_seed_lgas_updates_in_place_and_adds_wards(): void
    {
        // The upgrade path the runbook promises: seed LGAs now, load the real dataset
        // later. It must match on `code` and enrich, not duplicate.
        $this->artisan('reference:seed-lgas');
        $seededIds = Lga::query()->pluck('id', 'code');

        $this->loader()->loadFromFile($this->write('good.csv', $this->completeCsv(2)));

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(54, Ward::query()->count());
        $this->assertSame($seededIds['dutse'], Lga::query()->where('code', 'dutse')->firstOrFail()->id);
    }

    public function test_seed_lgas_invalidates_the_cached_lookups(): void
    {
        $cache = app(ReferenceDataCache::class);
        $this->assertSame([], $cache->lgas());

        $this->artisan('reference:seed-lgas');

        $this->assertCount(27, $cache->lgas());
    }
}
