<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\CatalogSeedLoader;
use Database\Seeders\ProgrammeCatalogSeeder;
use Database\Seeders\ReferenceMdaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Seeding the reference MDA list and the initial global catalog (PRD §10, FR-PRG-01).
 *
 * The inventory is real Jigawa data with real messiness in it: names repeated across
 * MDAs, capitalisation that disagrees with itself, and no MDA type at all. What the
 * seeder must NOT do is quietly tidy any of that up.
 */
class CatalogSeedTest extends TestCase
{
    use RefreshDatabase;

    private function seedAll(): void
    {
        $this->seed(ReferenceMdaSeeder::class);
        $this->seed(ProgrammeCatalogSeeder::class);
    }

    private function inventory(): array
    {
        return app(CatalogSeedLoader::class)->load();
    }

    // ------------------------------------------------------------------- MDAs

    public function test_it_seeds_every_mda_from_the_inventory(): void
    {
        $this->seed(ReferenceMdaSeeder::class);

        $expected = count($this->inventory()['mdas']);

        $this->assertSame(22, $expected, 'The inventory should hold 22 MDAs.');
        $this->assertSame($expected, Mda::query()->withoutGlobalScopes()->count());
    }

    public function test_it_matches_an_existing_mda_case_insensitively_instead_of_duplicating_it(): void
    {
        // Production holds "Ministry of health"; the inventory says "Ministry of
        // Health". `mdas.name` is unique, so a case-sensitive seeder would create a
        // second, near-identical MDA — the same fragmentation this seeding exists to
        // prevent, one table over.
        $existing = Mda::create(['name' => 'Ministry of health', 'type' => MdaType::Ministry]);

        $this->seed(ReferenceMdaSeeder::class);

        $this->assertSame(22, Mda::query()->withoutGlobalScopes()->count());
        // And the stored spelling is LEFT ALONE — an admin may have corrected it.
        $this->assertSame('Ministry of health', $existing->fresh()->name);
    }

    public function test_mda_types_are_inferred_and_the_guesses_are_identifiable(): void
    {
        $loader = app(CatalogSeedLoader::class);

        // Named forms are read from the name, not guessed.
        $this->assertSame(MdaType::Ministry, $loader->inferType('Ministry of Health')['type']);
        $this->assertSame('high', $loader->inferType('Ministry of Health')['confidence']);
        $this->assertSame(MdaType::Agency, $loader->inferType('Youth Empowerment and Employment Agency')['type']);
        $this->assertSame('high', $loader->inferType('State Rehabilitation Board')['confidence']);

        // An acronym carries no type. It must default AND admit that it guessed —
        // silently typing SEMA as an agency would look identical to knowing.
        $sema = $loader->inferType('SEMA');
        $this->assertSame(MdaType::Agency, $sema['type']);
        $this->assertSame('low', $sema['confidence']);
    }

    // ------------------------------------------------------------- programmes

    public function test_it_seeds_every_inventory_row_as_an_active_catalog_entry(): void
    {
        $this->seedAll();

        $expected = count($this->inventory()['programmes']);

        $this->assertSame(112, $expected, 'The inventory should hold 112 programmes.');
        $this->assertSame($expected, Programme::query()->count());
        $this->assertSame(
            $expected,
            Programme::query()->where('status', ProgrammeStatus::Active)->count(),
            'Every seeded catalog entry should be runnable.',
        );
    }

    public function test_it_captures_the_target_hint_and_automated_flag(): void
    {
        $this->seedAll();

        $quranic = Programme::query()
            ->where('name', 'Integrated Quranic Tsangaya Education Programme')
            ->firstOrFail();

        $this->assertSame('Almajiris', $quranic->target_group);
        $this->assertFalse($quranic->is_automated);

        $insurance = Programme::query()->where('name', 'State Health Insurance Scheme')->firstOrFail();
        $this->assertTrue($insurance->is_automated);
    }

    public function test_target_values_are_stored_verbatim_not_normalised(): void
    {
        // PWDs / PWDS differ only in case in the source. Collapsing them is a
        // vocabulary decision for SP Coordination, not something a seeder does.
        $this->seedAll();

        $stored = Programme::query()->whereNotNull('target_group')->pluck('target_group')->unique();

        $this->assertContains('PWDs', $stored);
        $this->assertContains('PWDS', $stored);
    }

    public function test_each_programme_is_associated_with_the_mda_that_runs_it(): void
    {
        $this->seedAll();

        // Association, never ownership — programmes stay global.
        $this->assertSame(112, DB::table('mda_programme')->count());

        $subeb = Mda::query()->withoutGlobalScopes()
            ->where('name', 'State Universal Basic Education Board')->firstOrFail();
        $programme = Programme::query()
            ->where('name', 'Integrated Quranic Tsangaya Education Programme')->firstOrFail();

        $this->assertDatabaseHas('mda_programme', [
            'mda_id' => $subeb->id,
            'programme_id' => $programme->id,
        ]);
    }

    // -------------------------------------------------------- duplicate flagging

    public function test_repeated_names_are_kept_separate_and_never_auto_merged(): void
    {
        $this->seedAll();

        // "Goat revolving scheme" appears under Ministry of Agric AND JARDA. Merging
        // them is a judgement about whether they are one scheme — a person's call.
        $entries = Programme::query()->where('name', 'Goat revolving scheme')->get();

        $this->assertCount(2, $entries, 'Identical names under two MDAs must not be merged.');

        $mdaIds = DB::table('mda_programme')
            ->whereIn('programme_id', $entries->pluck('id'))
            ->pluck('mda_id');

        $this->assertCount(2, $mdaIds->unique(), 'Each copy belongs to its own MDA.');
    }

    public function test_it_flags_likely_duplicates_including_near_misses(): void
    {
        $loader = app(CatalogSeedLoader::class);
        $pairs = $loader->findLikelyDuplicates($this->inventory()['programmes']);

        $this->assertNotEmpty($pairs);

        $describe = fn (array $p) => $p['a']['name'].' | '.$p['b']['name'];
        $flagged = array_map($describe, $pairs);

        // An exact collision across two MDAs.
        $this->assertTrue(
            (bool) array_filter($flagged, fn (string $f) => str_contains($f, 'Goat revolving scheme')),
            'Exact cross-MDA duplicates must be flagged.',
        );

        // A near miss that differs in wording — the case a plain name match misses.
        $this->assertTrue(
            (bool) array_filter($flagged, fn (string $f) => str_contains($f, 'Cash assistance to poor and vulnerable')),
            'Near-duplicate wording must be flagged, not just identical names.',
        );

        // Every flagged pair must clear the threshold.
        foreach ($pairs as $pair) {
            $this->assertGreaterThanOrEqual(CatalogSeedLoader::SIMILARITY_THRESHOLD, $pair['similarity']);
        }
    }

    public function test_unrelated_programmes_are_not_flagged(): void
    {
        // Guards against a threshold so low that everything looks like a duplicate,
        // which would make the review list useless.
        $pairs = app(CatalogSeedLoader::class)->findLikelyDuplicates($this->inventory()['programmes']);

        foreach ($pairs as $pair) {
            $this->assertNotEquals(
                'Low Cost Housing Scheme',
                $pair['a']['name'],
                'A distinctive one-off programme should not be flagged.',
            );
        }
    }

    public function test_it_writes_a_review_file_rather_than_only_console_output(): void
    {
        $path = storage_path('app/'.ProgrammeCatalogSeeder::REVIEW_FILE);
        @unlink($path);

        $this->seedAll();

        $this->assertFileExists($path);

        $review = json_decode((string) file_get_contents($path), true);

        $this->assertNotEmpty($review['likely_duplicate_programmes']);
        $this->assertNotEmpty($review['target_group_values']);
        // The inferred MDA types must be listed for correction.
        $this->assertNotEmpty($review['inferred_mda_types']);
    }

    // ---------------------------------------------------------------- idempotency

    public function test_running_both_seeders_twice_changes_nothing(): void
    {
        $this->seedAll();

        $mdas = Mda::query()->withoutGlobalScopes()->count();
        $programmes = Programme::query()->count();
        $links = DB::table('mda_programme')->count();

        $this->seedAll();

        $this->assertSame($mdas, Mda::query()->withoutGlobalScopes()->count());
        $this->assertSame($programmes, Programme::query()->count());
        $this->assertSame($links, DB::table('mda_programme')->count());
    }
}
