<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Gis;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Loads administrative boundaries from a GeoJSON FeatureCollection (PRD FR-GIS-01).
 * Idempotent — upserts by (level, code). LGA first; wards are additive.
 *
 * Expected GeoJSON: a `FeatureCollection` whose features have a polygon/multipolygon
 * `geometry` and `properties` carrying the admin code + name (and, for wards, the
 * parent LGA). Which property names are read is configured in `config/gis.php`
 * (defaults derive the join `code` from the NAME, slugged to match registry values):
 *
 *   LGA  properties → name (→ code slug), name
 *   Ward properties → name (→ code slug), name, lga_name (→ parent_code slug)
 *
 * On PostgreSQL the PostGIS `geom` column is populated too (heat-map extension point).
 */
class BoundaryLoader
{
    /**
     * @param  array<string, mixed>  $featureCollection
     * @return int number of boundaries loaded
     */
    public function load(string $level, array $featureCollection): int
    {
        if (! in_array($level, [GeoBoundary::LEVEL_LGA, GeoBoundary::LEVEL_WARD], true)) {
            throw new InvalidArgumentException("Unknown boundary level: {$level}.");
        }

        /** @var array<string, string> $props */
        $props = config("gis.properties.{$level}", []);
        $codeProp = $props['code_property'] ?? 'name';
        $nameProp = $props['name_property'] ?? 'name';
        $parentProp = $props['parent_property'] ?? null;

        $features = $featureCollection['features'] ?? [];
        $loaded = 0;

        foreach ($features as $feature) {
            $geometry = $feature['geometry'] ?? null;
            $properties = $feature['properties'] ?? [];
            if (! is_array($geometry) || $geometry === []) {
                continue;
            }

            $code = $this->slug((string) ($properties[$codeProp] ?? ''));
            if ($code === '') {
                $code = $this->slug((string) ($properties[$nameProp] ?? ''));
            }
            if ($code === '') {
                continue; // no usable key
            }

            $name = (string) ($properties[$nameProp] ?? $code);
            $parentCode = $level === GeoBoundary::LEVEL_WARD && $parentProp !== null
                ? ($this->slug((string) ($properties[$parentProp] ?? '')) ?: null)
                : null;

            GeoBoundary::query()->updateOrCreate(
                ['level' => $level, 'code' => $code],
                ['name' => $name, 'parent_code' => $parentCode, 'geometry' => $geometry],
            );

            if (DB::getDriverName() === 'pgsql') {
                DB::statement(
                    'UPDATE geo_boundaries SET geom = ST_Multi(ST_GeomFromGeoJSON(?)) WHERE level = ? AND code = ?',
                    [json_encode($geometry), $level, $code],
                );
            }

            $loaded++;
        }

        return $loaded;
    }

    private function slug(string $value): string
    {
        return (string) Str::of($value)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_');
    }
}
