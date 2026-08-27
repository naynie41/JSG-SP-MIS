<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Registry\Enums\Lga;
use App\Domain\Reporting\Gis\BoundaryLoader;
use App\Domain\Reporting\Gis\GeoBoundary;
use Database\Seeders\ReportingSampleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The shipped Jigawa LGA boundaries (FR-GIS-01).
 *
 * The map used to draw 27 identical squares on a 6-column grid — a placeholder that was
 * never replaced, so the choropleth showed a lattice over northern Nigeria instead of
 * Jigawa. These tests pin the real thing: the dataset is present, it is the 27 LGAs the
 * registry knows, its geometry is where Jigawa actually is, and — the part that rots
 * silently — no LGA is a rectangle.
 *
 * Geometry source: geoBoundaries gbOpen NGA ADM2 (CC BY 4.0), whose upstream is GRID3
 * Nigeria LGA boundaries (2022). Selected spatially by centroid-in-Jigawa, never edited.
 */
class JigawaBoundariesTest extends TestCase
{
    use RefreshDatabase;

    private const PATH = __DIR__.'/../../../database/data/jigawa-lga-boundaries.geojson';

    /** @return array<string, mixed> */
    private function dataset(): array
    {
        $this->assertFileExists(self::PATH, 'The committed Jigawa boundary dataset is missing.');
        $decoded = json_decode((string) file_get_contents(self::PATH), true);
        $this->assertIsArray($decoded);
        $this->assertSame('FeatureCollection', $decoded['type']);

        return $decoded;
    }

    /** Every coordinate in a feature, flattened. @return list<array{float, float}> */
    private function points(array $geometry): array
    {
        $polygons = $geometry['type'] === 'Polygon' ? [$geometry['coordinates']] : $geometry['coordinates'];
        $out = [];
        foreach ($polygons as $polygon) {
            foreach ($polygon as $ring) {
                foreach ($ring as $point) {
                    $out[] = [(float) $point[0], (float) $point[1]];
                }
            }
        }

        return $out;
    }

    /* ------------------------------------------------------------------ the dataset */

    public function test_it_covers_every_lga_the_registry_knows_and_no_others(): void
    {
        // The registry enum and a spatial cut of the national layer are independent
        // sources; agreeing on the same 27 is what makes this trustworthy.
        $codes = collect($this->dataset()['features'])->pluck('properties.code')->sort()->values()->all();
        $expected = collect(Lga::cases())->map(fn (Lga $l) => $l->value)->sort()->values()->all();

        $this->assertSame($expected, $codes);
    }

    public function test_each_features_code_is_the_slug_the_loader_will_derive(): void
    {
        // The loader keys on a slug of `name`; the file also carries `code` so the join
        // key is readable in the data. If they ever disagree the file is lying.
        foreach ($this->dataset()['features'] as $feature) {
            $slug = trim((string) preg_replace('/[^a-z0-9]+/', '_', mb_strtolower($feature['properties']['name'])), '_');
            $this->assertSame($feature['properties']['code'], $slug);
        }
    }

    public function test_the_geometry_lies_within_jigawa(): void
    {
        // Jigawa spans roughly 8.0–10.7°E, 10.9–13.2°N. A dataset that drifted to another
        // state (or to null island) fails here rather than on someone's screen.
        foreach ($this->dataset()['features'] as $feature) {
            foreach ($this->points($feature['geometry']) as [$lng, $lat]) {
                $this->assertGreaterThan(7.9, $lng, $feature['properties']['name']);
                $this->assertLessThan(10.8, $lng, $feature['properties']['name']);
                $this->assertGreaterThan(10.8, $lat, $feature['properties']['name']);
                $this->assertLessThan(13.3, $lat, $feature['properties']['name']);
            }
        }
    }

    public function test_no_boundary_is_a_placeholder_rectangle(): void
    {
        // The regression that produced the grid. A traced LGA has hundreds of vertices
        // and an irregular outline; the placeholder had five points forming a box. Both
        // conditions are checked, because a box could be subdivided and still be a box.
        foreach ($this->dataset()['features'] as $feature) {
            $name = $feature['properties']['name'];
            $points = $this->points($feature['geometry']);

            $this->assertGreaterThan(20, count($points), "{$name} has too few vertices to be a traced boundary.");

            $lngs = array_column($points, 0);
            $lats = array_column($points, 1);
            $onEdge = 0;
            foreach ($points as [$lng, $lat]) {
                if ($lng === min($lngs) || $lng === max($lngs) || $lat === min($lats) || $lat === max($lats)) {
                    $onEdge++;
                }
            }
            $this->assertLessThan(
                count($points) * 0.5,
                $onEdge,
                "{$name} sits mostly on its own bounding box — that is a rectangle, not a boundary.",
            );
        }
    }

    /* -------------------------------------------------------------------- the load */

    public function test_loading_the_dataset_stores_all_twenty_seven(): void
    {
        $count = app(BoundaryLoader::class)->load(GeoBoundary::LEVEL_LGA, $this->dataset());

        $this->assertSame(27, $count);
        $this->assertSame(27, GeoBoundary::query()->where('level', GeoBoundary::LEVEL_LGA)->count());
        $this->assertNotNull(GeoBoundary::query()->where('code', 'dutse')->first()?->geometry);
    }

    public function test_the_sample_seeder_plants_real_boundaries_not_squares(): void
    {
        // A fresh dev environment gets the map right without anyone remembering to run
        // the loader — which is how the placeholder survived to production-looking demos.
        $this->seed(ReportingSampleSeeder::class);

        $dutse = GeoBoundary::query()->where('level', GeoBoundary::LEVEL_LGA)->where('code', 'dutse')->first();
        $this->assertNotNull($dutse);
        $this->assertGreaterThan(20, count($this->points($dutse->geometry)));
    }
}
