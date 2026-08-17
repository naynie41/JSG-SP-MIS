# Reference — administrative divisions (LGA / Ward)

Jigawa's **27 LGAs** and their **wards** as proper lookup tables, replacing free text with
a navigable hierarchy. Shared, non-PII, MDA-independent reference data.

## No ward data ships in this repository

Ward names are **not** in this repo, not in a seeder, and not in a fallback list. They come
only from an authoritative dataset file a maintainer supplies. If that file is absent the
seeder **fails loudly and loads nothing**.

That is the whole point. Free text is visibly unverified, so nobody trusts it. A populated
lookup table looks authoritative — so a fabricated or partial ward list is *worse* than the
free text it replaces, because it launders a guess into something that reads as a State
register.

## Tables

| Table   | Columns |
| ------- | ------- |
| `lgas`  | `id`, `code` (unique), `name`, `state`, `latitude?`, `longitude?`, `geometry?` |
| `wards` | `id`, `lga_id` → `lgas`, `code`, `name`, `latitude?`, `longitude?`, `geometry?`, unique `(lga_id, code)` |

`wards.lga_id` is the hierarchy the cascading selector walks.

**`code` is the join key**, and is the same slug the registry already uses —
`"Birnin Kudu"` → `birnin_kudu` — matching both `App\Domain\Registry\Enums\Lga` and
`geo_boundaries.code`. That is what makes the deferred free-text → lookup migration of
`beneficiaries.lga` / `.ward` a join rather than a fuzzy re-match.

**Ward codes are unique only within their LGA.** Ward names repeat across Jigawa, so a ward
is identified by `(lga_id, code)`. Any later backfill must resolve a ward *through* its LGA;
resolving a bare ward name state-wide is ambiguous and will attach people to the wrong place.

### Geometry (FR-GIS-01)

`geometry` (portable GeoJSON JSON, works on sqlite) and, on PostgreSQL only, a PostGIS
`geom geometry(MultiPolygon, 4326)` column — both **nullable and left NULL**. These tables
carry the *hierarchy*; `geo_boundaries` carries the *shapes*; `code` is the seam. Boundaries
can be attached here later without a schema change.

## Loading the dataset

```bash
php artisan reference:load-divisions                      # reads config('reference.divisions.path')
php artisan reference:load-divisions path/to/wards.csv    # or an explicit path
php artisan db:seed --class=AdministrativeDivisionsSeeder # equivalent
```

Default path: `storage/app/reference/jigawa-administrative-divisions.csv`, overridable with
`REFERENCE_DIVISIONS_PATH`. `storage/app` is gitignored, so a real dataset cannot be
committed by accident.

Idempotent — upserts by `code` / `(lga_id, code)`, so re-running with a corrected file
updates in place.

> `AdministrativeDivisionsSeeder` is deliberately **not** in `DatabaseSeeder`. `db:seed` has
> to keep working on a fresh clone, and a fresh clone has no dataset — wiring it in would
> break the baseline seed for everyone, which is the fastest route to someone "fixing" it
> with a hardcoded ward list.

### File format

CSV (or JSON) with these columns — see
[`database/data/administrative-divisions.example.csv`](../../../database/data/administrative-divisions.example.csv):

| Column      | Required | Notes |
| ----------- | -------- | ----- |
| `lga_name`  | yes      | must match a Jigawa LGA |
| `ward_name` | yes      | blank keeps the LGA with zero wards |
| `lga_code`  | no       | slugged from `lga_name` when absent |
| `ward_code` | no       | slugged from `ward_name` when absent |

**Supply `ward_code` if ward names may ever be corrected.** When the code is derived from
the name, the name *is* the identity: a dataset without `ward_code` cannot express "this
ward was renamed", so the loader sees a new ward plus an absent one and reports both rather
than guessing they are the same place. With a stable `ward_code`, a corrected name updates
in place.

JSON may be a flat list of the same keys, or nested:
`[{ "name": "...", "wards": [{ "name": "..." }] }]`. A `{"lgas": [...]}` wrapper is accepted.

The example file is intentionally **un-seedable**: its placeholder LGA names are not Jigawa
LGAs, so the loader rejects it. Copying it without replacing the contents cannot produce
fake reference data.

### LGAs without a dataset

```bash
php artisan reference:seed-lgas    # the 27 LGAs from the Lga enum — NO wards
```

For the legitimate intermediate state: **LGAs known, wards not yet supplied.** The 27 LGAs
are already committed authoritative data (the enum FR-REG-04/05 validates against, and the
same list the loader checks supplied files against), so this copies a fact the repository
asserts. Ward names are still never generated.

Needed on any deploy where activities already carry an LGA but no dataset has arrived yet —
without it the activity-location backfill refuses to run. See
[DEPLOY.md §3.1](../../../../docs/DEPLOY.md). Idempotent, and
`reference:load-divisions` later updates these rows in place (same `code`) and adds wards.

### Where to source a real dataset

- **OCHA HDX** — *Nigeria - Administrative Boundaries* (admin 1–3), `data.humdata.org`
- **GRID3 Nigeria** — operational LGA + Ward boundaries, `grid3.org`
- **Jigawa State administrative records** — the State's own ward register

Filter to Jigawa State before exporting.

### What the loader refuses

Validation runs **before any write**, so these messages can honestly say nothing was loaded:

| Condition | Why it is fatal |
| --------- | --------------- |
| file absent / unreadable | nothing to load, and nothing will be invented |
| file empty | seeding nothing looks identical to seeding successfully |
| an LGA not in Jigawa | wrong file — usually national data left unfiltered |
| fewer than all 27 LGAs | a partial list looks complete to every user of the selector |
| one ward code, two names | a contradiction the loader will not resolve by guessing |

**Ward totals are not validated.** Jigawa's ward count is commonly cited as ~287, but that
figure is not a fact this code is entitled to enforce — the supplied file is the authority.
The loader instead prints the **per-LGA ward spread**, which catches what a total cannot: a
plausible-looking total hiding an LGA with zero wards.

Wards already stored but absent from a newly loaded file are **reported, never deleted** — a
file that omits a ward is not making the same claim as a file that retires one.

## Endpoints

| Endpoint | Returns |
| -------- | ------- |
| `GET /api/v1/reference/lgas` | all LGAs + `ward_count` each |
| `GET /api/v1/reference/wards?lga_id={uuid}` | that LGA's wards |

Authenticated, **not** permission-gated: every role needs these to render a selector, so a
permission granted to all six would deny nothing while implying the system draws a
distinction here. Read-only — there is no write side, because the list must reproduce its
source rather than drift from it (same reasoning as bulk/source-only ingestion, CLAUDE.md §8).

`ward_count` is how a client tells that ward data is absent: a fresh install has 27 LGAs and
zero wards until a dataset is supplied.

Both responses are cached by `ReferenceDataCache`. Invalidation is a **version counter baked
into the cache key**, not cache tags — the default store is `database`, where tags are
unsupported and a tag flush would silently do nothing in production while passing against a
redis-backed test. A load calls `flush()`, so a re-seed is visible immediately.

## Not done here

`beneficiaries.lga` / `.ward` and `households.lga` / `.ward` are **untouched** — still free
text, still validated against the `Lga` enum. Migrating them onto these tables is a separate,
deferred step; the schema above is shaped so it needs no rework when it happens.

Tests: `tests/Feature/Reference/`.
