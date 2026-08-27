# GIS — coverage map & boundary loader (FR-GIS-01)

Scope-aware coverage of social protection across Jigawa, by **LGA (admin level 2)** and
**Ward (admin level 3)**. Boundaries live in PostGIS; the map is a choropleth, and the
page **degrades gracefully** to a ranked table when no boundaries are loaded.

## Boundary loader

Load boundaries from a GeoJSON `FeatureCollection` (LGA first; wards additive):

```bash
php artisan gis:load-boundaries lga  database/data/jigawa-lga-boundaries.geojson
php artisan gis:load-boundaries ward storage/app/boundaries/jigawa-ward.geojson
```

Idempotent — upserts by `(level, code)`, so re-running updates in place.

### Expected GeoJSON

A `FeatureCollection` whose features have a polygon/multipolygon `geometry` and a
`properties` object carrying the admin **name** (and, for wards, the parent LGA). Which
properties are read is configurable in [`config/gis.php`](../../../../config/gis.php):

| Level | Reads (default property) | Becomes |
| --- | --- | --- |
| LGA  | `name` | `name` + join `code` (slug of name) |
| Ward | `name`, `lga_name` | `name` + `code` (slug) + `parent_code` (slug of `lga_name`) |

The join **`code` is a slug** (`"Birnin Kudu"` → `birnin_kudu`) so it matches the
registry's LGA/Ward values, which is how coverage aggregates attach to shapes. If your
GeoJSON carries a distinct code that already matches the registry values, point
`GIS_LGA_CODE_PROPERTY` / `GIS_WARD_CODE_PROPERTY` at it.

On PostgreSQL the loader also fills a PostGIS `geom` (`geometry(MultiPolygon,4326)`)
column — the **extension point for heat maps (FR-GIS-02)** and point-in-polygon work.
On sqlite (tests) only the portable JSON `geometry` is stored.

### The shipped LGA boundaries

Jigawa.'.s 27 LGA outlines ship with the repo at
[`database/data/jigawa-lga-boundaries.geojson`](../../../../database/data/jigawa-lga-boundaries.geojson)
and `ReportingSampleSeeder` loads them, so a fresh environment draws the state without
anyone remembering to run the command. They are also the default argument above.

| | |
| --- | --- |
| Source | [geoBoundaries](https://www.geoboundaries.org) `gbOpen` **NGA ADM2** |
| Upstream | **GRID3 Nigeria** Local Government Area boundaries, 2022 |
| Licence | **CC BY 4.0** — attribution required wherever the map is published |
| CRS | EPSG:4326 (WGS84) |
| Selection | spatial: an ADM2 whose centroid falls inside the ADM1 Jigawa polygon |

Selection is spatial rather than by name because Nigerian LGA names are not unique —
`Garki` exists in both Jigawa and the FCT. The 27 it returns are then cross-checked
against the {@see App\Domain\Registry\Enums\Lga} enum: two independent sources agreeing
is what makes the set trustworthy. Geometry is passed through **unedited**.

`tests/Feature/Reporting/JigawaBoundariesTest` pins all of that, including that no
boundary is a rectangle — this used to plant 27 placeholder squares on a grid, and
nothing failed while the map was wrong.

### Wards, and other sources

Ward boundaries (admin level 3) are **not** shipped. When they are sourced, load them
with the `ward` command above; each feature needs its parent LGA name (see the table
below). Do not hand-draw or approximate them: a fabricated boundary is worse than an
absent one, because it looks authoritative.

- **GRID3 Nigeria** — operational LGA + Ward boundaries (`grid3.org` / data portal).
- **OCHA HDX** — *Nigeria - Administrative Boundaries* (admin 1–3), CKAN/`data.humdata.org`.
- **GADM** (`gadm.org`) — admin level 2 (LGA); wards are not in GADM.
- **geoBoundaries** (`geoboundaries.org`) — ADM2 for Nigeria.

Reproject to **EPSG:4326** (WGS84) and ensure each feature has a name property before
loading.

## Coverage endpoint

`GET /api/v1/gis/coverage?level=lga|ward` (permission `dashboard.view`) — scope-aware
(Executive state-wide, MDA own, Partner funded), reusing the 6.1 aggregation layer:

- **boundaries loaded** → `mode: choropleth` with a GeoJSON `feature_collection`
  (each feature = shape + `beneficiary_count` / `benefit_count` / `benefit_value`).
- **none loaded** → `mode: table` with the ranked `rows` only (the graceful fallback).

The web page (`web/src/features/gis`) renders the choropleth with Leaflet
(`VITE_MAP_TILE_URL` configurable) using the design-system palette, and falls back to
the ranked bar list otherwise. Heat maps (FR-GIS-02) are a documented, unbuilt
extension point.

Tests: `tests/Feature/Reporting/GisCoverageTest`, `web/src/features/gis/GisDashboardPage.test`.
