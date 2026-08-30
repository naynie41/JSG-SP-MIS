<?php

declare(strict_types=1);

namespace Tests\Feature\Reference;

use App\Domain\Reference\Imports\AdministrativeDivisionLoader;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The maintainer's supplied Jigawa dataset, as committed.
 *
 * GEO.1's rule was that ward names are never invented — the file is the authority. This
 * exercises the file that is actually shipped, so a later edit that quietly adds a ward
 * nobody supplied, or drops one that was, fails here.
 *
 * The dataset is knowingly PARTIAL: it names wards for 21 of the 27 LGAs and gives only
 * prose for the remaining six. That is recorded as a fact about the file, not smoothed
 * over — a partial list that presents itself as complete is the failure the locked
 * decision was written to prevent.
 */
class SuppliedDivisionsDatasetTest extends TestCase
{
    use RefreshDatabase;

    /** LGAs the source described in prose, without naming any ward. */
    private const WARDS_NOT_SUPPLIED = [
        'maigatari', 'malam_madori', 'roni', 'sule_tankarkar', 'taura', 'yankwashi',
    ];

    /**
     * Load the dataset from the SAME path the application uses.
     *
     * This previously hard-coded `storage/app/reference/…`, where the file was first
     * dropped by hand. That directory is gitignored, so the test passed on any machine
     * that still had the working copy and failed on a clean checkout — it was green
     * locally and red in CI, which is the least useful way for a test to be wrong.
     *
     * Reading `config('reference.divisions.path')` means the test can only ever check the
     * file the seeder and `reference:load-divisions` actually read. If that path moves
     * again, this follows it instead of silently testing a stale copy.
     */
    private function load(): void
    {
        app(AdministrativeDivisionLoader::class)->loadFromFile($this->datasetPath());
    }

    private function datasetPath(): string
    {
        return (string) config('reference.divisions.path');
    }

    public function test_the_shipped_file_loads_and_covers_every_jigawa_lga(): void
    {
        $this->load();

        $this->assertSame(27, Lga::query()->count());

        // Every code lines up with the committed enum, so `beneficiaries.lga` and this
        // lookup table describe the same 27 places.
        $loaded = Lga::query()->pluck('code')->sort()->values()->all();
        $expected = collect(LgaEnum::cases())->map(fn (LgaEnum $l): string => $l->value)->sort()->values()->all();
        $this->assertSame($expected, $loaded);
    }

    public function test_it_holds_exactly_the_wards_that_were_supplied(): void
    {
        $this->load();

        $this->assertSame(162, Ward::query()->count());
    }

    public function test_the_six_lgas_with_no_supplied_wards_have_none_invented(): void
    {
        $this->load();

        foreach (self::WARDS_NOT_SUPPLIED as $code) {
            $lga = Lga::query()->where('code', $code)->first();
            $this->assertNotNull($lga, "{$code} must still exist as an LGA");
            $this->assertSame(
                0,
                $lga->wards()->count(),
                "{$code} had no wards in the source — inventing them would make a guess look authoritative",
            );
        }
    }

    public function test_a_spot_check_of_ward_names_matches_the_source(): void
    {
        $this->load();

        $dutse = Lga::query()->where('code', 'dutse')->firstOrFail();
        $names = $dutse->wards()->orderBy('name')->pluck('name')->all();

        $this->assertSame([
            'Abaya', 'Chamo', 'Dundubus', 'Duru', 'Jigawar Tsada', 'Kachi',
            'Karnaya', 'Kudai', 'Limawa', 'Madobi', 'Sakwaya',
        ], $names);
    }

    public function test_the_same_ward_name_may_exist_in_two_different_lgas(): void
    {
        // "Kanya" is listed under both Babura and Garki. Uniqueness is per LGA, so both
        // survive — a global unique constraint would have silently dropped one.
        $this->load();

        $this->assertSame(1, Ward::query()->whereHas('lga', fn ($q) => $q->where('code', 'babura'))->where('name', 'Kanya')->count());
        $this->assertSame(1, Ward::query()->whereHas('lga', fn ($q) => $q->where('code', 'garki'))->where('name', 'Kanya')->count());
    }

    public function test_loading_twice_changes_nothing(): void
    {
        $this->load();
        $this->load();

        $this->assertSame(27, Lga::query()->count());
        $this->assertSame(162, Ward::query()->count());
    }
}
