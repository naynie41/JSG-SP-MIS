<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Gis;

use Illuminate\Console\Command;

/**
 * Loads Jigawa LGA/Ward boundaries from a GeoJSON file (PRD FR-GIS-01):
 *
 *   php artisan gis:load-boundaries lga  storage/app/boundaries/jigawa-lga.geojson
 *   php artisan gis:load-boundaries ward storage/app/boundaries/jigawa-ward.geojson
 *
 * Idempotent (upserts by level + code). LGA first; wards are additive. See
 * {@see BoundaryLoader} for the expected GeoJSON properties.
 */
class LoadGeoBoundaries extends Command
{
    protected $signature = 'gis:load-boundaries {level : lga or ward} {file : path to a GeoJSON FeatureCollection}';

    protected $description = 'Load LGA/Ward administrative boundaries from a GeoJSON file into PostGIS';

    public function handle(BoundaryLoader $loader): int
    {
        $level = (string) $this->argument('level');
        $file = (string) $this->argument('file');

        if (! in_array($level, [GeoBoundary::LEVEL_LGA, GeoBoundary::LEVEL_WARD], true)) {
            $this->error("Level must be 'lga' or 'ward'.");

            return self::INVALID;
        }
        if (! is_file($file)) {
            $this->error("File not found: {$file}");

            return self::INVALID;
        }

        $decoded = json_decode((string) file_get_contents($file), true);
        if (! is_array($decoded) || ($decoded['type'] ?? null) !== 'FeatureCollection') {
            $this->error('The file is not a GeoJSON FeatureCollection.');

            return self::INVALID;
        }

        $count = $loader->load($level, $decoded);
        $this->info("Loaded {$count} {$level} boundaries.");

        return self::SUCCESS;
    }
}
