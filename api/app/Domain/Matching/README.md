# Matching domain — configurable duplicate verification

The configurable duplicate-verification engine (PRD **FR-DUP-01 → FR-DUP-07**) and
the workflow built on it: a pre-save duplicate check on every ingestion path, a
standalone lookup, the match REVEAL, and the resolve / request-to-serve flow.
**Status: Phase 3 complete.**

## Requirement map (FR-DUP-01 → 07)

| Req | What | Where |
| --- | --- | --- |
| **FR-DUP-01** | Duplicate check runs **before save** on every import — per-row against the registry **and** within the batch | `ParseImportBatch` → `BatchDuplicateScreener`; `tests/Feature/Registry/ImportDuplicateScreeningTest` |
| **FR-DUP-02** | Rules/thresholds are **configurable**, **versioned**, admin-editable | `Models/MatchingConfig`, `Services/MatchingConfigService`, `MatchingConfigController`; `web/features/registry/MatchingConfigPage`; `MatchingConfigTest` |
| **FR-DUP-03** | Deterministic **and** fuzzy matching with banded outcomes | `Engine/MatchingEngine`, `Scoring/RuleBasedMatchScorer`, `Scoring/Comparators/*`; `tests/Unit/Matching/*`, `DuplicateAccuracyTest` |
| **FR-DUP-04** | Match **REVEAL** — recognise an existing record without exposing the full profile | `Http/Resources/BeneficiaryRevealResource`, `ImportBatchController::attachMatchReveals`, `BeneficiaryController::search`; `web` `MatchRevealPanel` |
| **FR-DUP-05** | Resolve a flagged row: **new (justified) / link–request-to-serve / skip**; owner accepts/declines | `ImportRowResolution`, `ImportBatchController::resolveRow`, `CommitImportBatch`, `ServeRequestService`, `ServeRequestController`; `ImportResolutionTest`, `ServeSearchTest` |
| **FR-DUP-06** | Every decision is **audited** (actor, choice, justification, matched record) | `AuditLogger` calls in `resolveRow` (`import.row_resolved`) + `ServeRequest` (`serve_request.*`); `ImportResolutionTest` |
| **FR-DUP-07** | Pluggable scorer seam for a future external/AI scorer | `Contracts/MatchScorer`, rebind in `MatchingServiceProvider`. **No AI scorer ships in Phase 3.** |

## Pieces

| Piece | Role |
| --- | --- |
| `Models/MatchingConfig` | A **versioned** config row (deterministic key sets, weighted fuzzy fields, thresholds, exact-match behaviour). Immutable versions; one `is_active`. Auditable. |
| `Services/MatchingConfigService` | Reads the active config; `publish()` appends a new active version (history preserved). |
| `Contracts/MatchScorer` | The pluggable scoring seam (FR-DUP-07). |
| `Scoring/RuleBasedMatchScorer` | Built-in deterministic + fuzzy scorer. |
| `Scoring/Comparators/*` | `exact`, `jaro_winkler`, `levenshtein`, `phonetic`, `date_proximity` (pure PHP; pgsql + sqlite). |
| `Scoring/FieldNormalizer` | Identifiers → digits, dates → `Y-m-d`, text → lower/whitespace-collapsed. |
| `Engine/MatchingEngine` | Scores a candidate vs a set of existing records and applies the **bands**. |
| `Registry/Services/CandidateGatherer` | Index-backed **blocking** — gathers only records sharing a key. |
| `Registry/Services/FuzzyDuplicateFinder` | gather → score → keep exact + probable. The registry duplicate check used by screening **and** the standalone lookup. |
| `Registry/Services/BatchDuplicateScreener` | Per-row screening for an import: registry **and** earlier rows in the same batch (within-batch blocking). |

## Outcome bands

For each candidate-vs-existing pair the engine returns a band:

- **exact** — a deterministic key set matched (e.g. `[nin]`), **or** the composite
  cleared the optional `auto_accept_threshold`.
- **probable** — composite ≥ `review_threshold` (needs human review).
- **none** — below `review_threshold`.

The composite is the weighted-average field similarity, **normalised by the total
configured weight**, so it stays in `[0,1]` however weights are set. Each result
carries a **PII-free, per-rule explanation** (field names, comparators,
similarities — never the raw values) for transparency and audit.

> **Nothing auto-merges silently.** The engine only *surfaces* matches; it never
> creates, merges, or deletes a record. With the default `exact_match_behaviour =
> confirm`, even an exact match waits for a human decision. Note that fuzzy-only
> scoring caps below `auto_accept_threshold` in practice (the `address` field is
> not gathered for blocked candidates), so **auto-accept requires a deterministic
> key** — a deliberate safety margin. See `DuplicateAccuracyTest`.

## DEFAULT configuration (seeded — `MatchingConfigSeeder`)

| Part | Default | Meaning |
| --- | --- | --- |
| Deterministic (decisive → `exact`) | `[nin]`, `[bvn]` | Either identifier alone is a decisive match. A key set is a list of fields that must **all** be equal. |
| Fuzzy weights (sum 1.00) | last_name `.25` (**phonetic**) · first_name `.15` (**phonetic**) · date_of_birth `.20` (**date_proximity**) · phone `.20` (exact) · lga `.05` (exact) · ward `.05` (exact) · address `.10` (jaro_winkler) | Weighted similarity; higher weight = more influence on the composite. |
| `review_threshold` | **0.75** | At/above → **probable** (surfaced for review). |
| `auto_accept_threshold` | **0.92** | At/above → **exact** band without a deterministic key. Optional; must be ≥ `review_threshold`. |
| `exact_match_behaviour` | **confirm** | `confirm` = a human confirms exact matches; `auto_link` = auto-route to serve without a confirmation step. |

## How to tune the config

Edit it in the UI — **03 · Registry → Matching rules** (System Administrator; needs
`matching.edit`) — or via `PUT /api/v1/matching/config`. **Publishing never mutates
the current version**; it deactivates it and creates the next numbered version, so
history is intact and every change is audited. The new version takes effect on the
next preview re-run / lookup.

Practical guidance:

- **Fewer false positives (higher precision):** raise `review_threshold` (e.g. 0.80)
  or reduce the weight of weak signals (lga/ward).
- **Catch more variants (higher recall):** lower `review_threshold` (e.g. 0.70) or
  raise the phonetic name / DOB weights. Watch precision on the fixtures.
- **Add a decisive key:** add a deterministic rule, e.g. `["phone","date_of_birth"]`
  (both must match) → `exact`. Single-field rules like `["nin"]` are strongest.
- **Comparators:** `phonetic` for names (Hausa/Nigerian variance), `date_proximity`
  for DOB (tolerates typos), `exact` for identifiers/codes, `jaro_winkler`/
  `levenshtein` for free text (addresses). Weights need not sum to 1 — they are
  normalised — but keeping the sum at 1.00 keeps the composite intuitive.
- **`auto_link` behaviour:** only turn on for deterministic-exact confidence; it
  routes duplicates straight to serve without a human confirming.

Validate any change against the labelled fixtures:

```bash
docker compose exec api php artisan test tests/Feature/Matching/DuplicateAccuracyTest.php
```

## Comparators

`exact` · `jaro_winkler` · `levenshtein` · **`phonetic`** (Hausa/Nigerian name
variance: a phonetic-code match → 1.0, else Jaro-Winkler) · **`date_proximity`**
(exact → 1.0, decaying to 0 over ~3 years so DOB typos still contribute).

## Candidate blocking (performance — NFR-PERF-01)

Fuzzy matching never scans the whole registry. `CandidateGatherer` gathers only
records sharing an **index-backed** blocking key with the candidate — NIN, BVN,
phone, or `block_name_dob` = `phonetic(last_name)|dob_year` (maintained on the
beneficiary on save, indexed). The gathered set is hard-capped
(`CandidateGatherer::MAX_CANDIDATES`), so cost scales with the (tiny) blocked set,
not the registry size — a single lookup and a whole batch stay within the 5-second
target on a large registry (`MatchingPerformanceTest`). Batches run from the queue
worker; single lookups run inline.

## Labelled fixtures, accuracy & performance

- `Database/Seeders/MatchFixtureSeeder` — a small, fully **synthetic**, labelled set
  (exact + fuzzy duplicates, near-misses, clear non-duplicates) across **two MDAs**.
  Also usable to demo the flow: `db:seed --class="Database\Seeders\MatchFixtureSeeder"`.
- `tests/Feature/Matching/DuplicateAccuracyTest` — runs the labelled probes through
  the real engine and reports **precision/recall** (1.00/1.00 on the default config),
  asserts the exact/probable/none bands behave, that detection spans both MDAs, and
  that the check is read-only (no silent auto-merge).
- `tests/Feature/Matching/MatchingPerformanceTest` — a ~1,200-row registry: a single
  check and a 50-row batch both stay bounded and well within target via blocking.

## Standalone lookup + request-to-serve

- `GET /api/v1/beneficiaries/search` (`beneficiary-lookup.view`) — the same engine
  against partial identity details, returning ranked reveal-only candidates.
- `POST /api/v1/serve-requests` (`beneficiary.create`) — raise a request-to-serve on
  a non-owned record; it routes to the owner MDA and **never transfers ownership**.
- `POST /api/v1/serve-requests/{id}/accept|decline` (`beneficiary.approve`) — the
  owner decides; grants serve access on accept. Both are audited.

## Admin API (matching config)

- `GET /api/v1/matching/config` — active config (`matching.view`).
- `PUT /api/v1/matching/config` — publish a new version (`matching.edit`), audited.
- `GET /api/v1/matching/config/versions` — full version history (`matching.view`).
