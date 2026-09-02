<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Services\CatalogSeedLoader;
use Illuminate\Database\Seeder;

/**
 * The authoritative starting list of Jigawa MDAs (PRD FR-UAM-02).
 *
 * Real reference data, not sample data — it runs in production. Idempotent: MDAs are
 * matched CASE-INSENSITIVELY on name, because `mdas.name` is unique and production
 * already holds "Ministry of health" while the inventory says "Ministry of Health".
 * A case-sensitive match would create a second, near-identical MDA — the exact
 * fragmentation this seeding exists to avoid, one table over.
 *
 * An existing MDA is never renamed or retyped. It may have been corrected by an
 * administrator since, and a seeder must not overwrite that.
 */
class ReferenceMdaSeeder extends Seeder
{
    public function run(CatalogSeedLoader $loader): void
    {
        $data = $loader->load();

        $created = 0;
        $matched = 0;
        $caseMismatches = [];
        $lowConfidence = [];

        foreach ($data['mdas'] as $entry) {
            $name = (string) $entry['name'];

            $existing = Mda::query()
                ->withoutGlobalScopes()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->first();

            if ($existing !== null) {
                $matched++;

                if ($existing->name !== $name) {
                    $caseMismatches[] = ['stored' => $existing->name, 'inventory' => $name];
                }

                continue;
            }

            Mda::create(['name' => $name, 'type' => $entry['type']]);
            $created++;

            if ($entry['type_confidence'] === 'low') {
                $lowConfidence[] = ['name' => $name, 'assumed_type' => $entry['type']->value];
            }
        }

        $this->report($created, $matched, $caseMismatches, $lowConfidence);
    }

    /**
     * @param  list<array{stored: string, inventory: string}>  $caseMismatches
     * @param  list<array{name: string, assumed_type: string}>  $lowConfidence
     */
    private function report(int $created, int $matched, array $caseMismatches, array $lowConfidence): void
    {
        $command = $this->command;

        if ($command === null) {
            return;
        }

        $command->info("MDAs: {$created} created, {$matched} already present.");

        if ($caseMismatches !== []) {
            $command->warn('Matched by case only — the stored name differs from the inventory:');
            foreach ($caseMismatches as $m) {
                $command->warn("  stored \"{$m['stored']}\"  vs inventory \"{$m['inventory']}\"");
            }
            $command->warn('  Left as stored. Correct it in the admin console if the inventory spelling is right.');
        }

        if ($lowConfidence !== []) {
            $command->warn('MDA type was INFERRED and needs review (the inventory does not carry one):');
            foreach ($lowConfidence as $m) {
                $command->warn("  {$m['name']} → assumed {$m['assumed_type']}");
            }
        }
    }
}
