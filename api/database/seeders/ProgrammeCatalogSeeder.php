<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\CatalogSeedLoader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * The initial global programme catalog, from the MDA inventory (PRD §10, FR-PRG-01).
 *
 * Real reference data — it runs in production. Programmes are seeded as ACTIVE
 * (runnable) catalog entries. They stay GLOBAL: the inventory's grouping records
 * which MDA RUNS each programme, written to `mda_programme`, never as ownership.
 *
 * The inventory contains near-duplicates across MDAs. They are seeded as distinct
 * entries and FLAGGED for review — a person decides which are one catalog programme
 * run by several MDAs. Nothing is merged automatically; see CatalogSeedLoader.
 */
class ProgrammeCatalogSeeder extends Seeder
{
    public const REVIEW_FILE = 'catalog-seed-review.json';

    public function run(CatalogSeedLoader $loader): void
    {
        $data = $loader->load();

        $created = 0;
        $matched = 0;
        $linked = 0;
        $missingMdas = [];

        foreach ($data['programmes'] as $entry) {
            $name = (string) $entry['name'];

            $mda = Mda::query()
                ->withoutGlobalScopes()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower((string) $entry['mda'])])
                ->first();

            if ($mda === null) {
                $missingMdas[(string) $entry['mda']] = true;

                continue;
            }

            // Idempotency is keyed on (this MDA, this name) — NOT on name alone.
            //
            // Five names appear under two MDAs each ("Goat revolving scheme" under
            // both Ministry of Agric and JARDA). Collapsing those into one shared
            // entry would be deciding they are the same programme, and that decision
            // belongs to a person: two MDAs running a similarly-named scheme may be
            // one catalog entry or two genuinely different schemes. They are seeded
            // separately and flagged, so the merge stays a reviewed act.
            //
            // Re-running still finds the row it created last time, so nothing doubles.
            $programme = Programme::query()
                ->withArchived()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->whereIn('id', function ($q) use ($mda): void {
                    $q->select('programme_id')->from('mda_programme')->where('mda_id', $mda->id);
                })
                ->first();

            if ($programme === null) {
                $programme = Programme::create([
                    'name' => $name,
                    // The inventory says nothing about household vs individual
                    // targeting, and guessing it would drive real eligibility
                    // behaviour. Individual is the schema's own default shape;
                    // catalog admins correct it per entry.
                    'type' => ProgrammeType::Individual,
                    'target_group' => $entry['target_group'],
                    'is_automated' => $entry['is_automated'],
                    'status' => ProgrammeStatus::Active,
                ]);
                $created++;
            } else {
                $matched++;
            }

            // The unique index on (mda_id, programme_id) is the real idempotency
            // guarantee; this check just avoids relying on an exception for control
            // flow on a re-run.
            $exists = DB::table('mda_programme')
                ->where('mda_id', $mda->id)
                ->where('programme_id', $programme->id)
                ->exists();

            if (! $exists) {
                DB::table('mda_programme')->insert([
                    'id' => (string) Str::uuid(),
                    'mda_id' => $mda->id,
                    'programme_id' => $programme->id,
                    'source' => 'inventory',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $linked++;
            }
        }

        $duplicates = $loader->findLikelyDuplicates($data['programmes']);
        $this->writeReview($data, $duplicates);
        $this->report($created, $matched, $linked, array_keys($missingMdas), $duplicates);
    }

    /**
     * Write the review artefact. The duplicate list is the POINT of this seeder, so
     * it goes to a file someone can work through rather than scrolling console output
     * that disappears.
     *
     * @param  array{mdas: list<array<string, mixed>>, programmes: list<array<string, mixed>>}  $data
     * @param  list<array<string, mixed>>  $duplicates
     */
    private function writeReview(array $data, array $duplicates): void
    {
        $targets = [];
        foreach ($data['programmes'] as $p) {
            if ($p['target_group'] !== null) {
                $targets[(string) $p['target_group']] = true;
            }
        }
        ksort($targets);

        $review = [
            'generated_at' => now()->toIso8601String(),
            'threshold' => CatalogSeedLoader::SIMILARITY_THRESHOLD,
            'note' => 'Nothing here was merged or changed. Each item is a decision for a person.',
            'likely_duplicate_programmes' => $duplicates,
            'inferred_mda_types' => array_values(array_map(
                fn (array $m) => [
                    'name' => $m['name'],
                    'type' => $m['type']->value,
                    'confidence' => $m['type_confidence'],
                ],
                array_filter($data['mdas'], fn (array $m) => $m['type_confidence'] === 'low'),
            )),
            // 39 distinct values that do not yet form a controlled vocabulary
            // (PWDs/PWDS, "Youth/Women", typos). Stored verbatim; listed here so SP
            // Coordination can decide what the vocabulary should be.
            'target_group_values' => array_keys($targets),
        ];

        $path = storage_path('app/'.self::REVIEW_FILE);
        @mkdir(dirname($path), 0755, true);
        file_put_contents($path, json_encode($review, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param  list<string>  $missingMdas
     * @param  list<array<string, mixed>>  $duplicates
     */
    private function report(int $created, int $matched, int $linked, array $missingMdas, array $duplicates): void
    {
        $command = $this->command;

        if ($command === null) {
            return;
        }

        $command->info("Programmes: {$created} created, {$matched} already present. {$linked} MDA associations added.");

        if ($missingMdas !== []) {
            $command->warn('Skipped associations — these MDAs are not seeded (run ReferenceMdaSeeder first):');
            foreach ($missingMdas as $name) {
                $command->warn("  {$name}");
            }
        }

        $exact = count(array_filter($duplicates, fn (array $d) => $d['exact'] === true));
        $command->warn(sprintf(
            '%d likely-duplicate programme name pair(s) flagged for review (%d exact). NOT merged.',
            count($duplicates),
            $exact,
        ));

        foreach (array_slice($duplicates, 0, 8) as $d) {
            $command->warn(sprintf(
                '  %.3f  "%s" [%s]  ~  "%s" [%s]',
                $d['similarity'], $d['a']['name'], $d['a']['mda'], $d['b']['name'], $d['b']['mda'],
            ));
        }

        $command->info('Full review written to storage/app/'.self::REVIEW_FILE);
    }
}
