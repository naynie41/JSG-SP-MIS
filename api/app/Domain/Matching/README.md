# Matching domain — configurable duplicate verification

Implements the configurable matching engine (PRD **FR-DUP-02**, **FR-DUP-03**).
This is the Phase 3 *skeleton*: the config model, the rule engine, the scorer
seam, and the seeded default. Wiring it into the import pre-save check and the
standalone lookup comes in later Phase 3 steps.

## Pieces

| Piece | Role |
| --- | --- |
| `Models/MatchingConfig` | A **versioned** config row (deterministic key sets, weighted fuzzy fields, thresholds, exact-match behaviour). Immutable versions; one `is_active`. Auditable. |
| `Services/MatchingConfigService` | Reads the active config; `publish()` appends a new active version (history preserved). |
| `Contracts/MatchScorer` | The pluggable scoring seam (see below). |
| `Scoring/RuleBasedMatchScorer` | Built-in deterministic + fuzzy scorer. |
| `Scoring/Comparators/*` | `exact`, `jaro_winkler`, `levenshtein` similarity (pure PHP; pgsql + sqlite). |
| `Scoring/FieldNormalizer` | Identifiers → digits, dates → `Y-m-d`, text → lower/whitespace-collapsed. |
| `Engine/MatchingEngine` | Scores a candidate vs a set of existing records and applies the **bands**. |
| `Enums/MatchBand` | `exact` · `probable` · `none`. |

## Outcome bands

For each candidate-vs-existing pair the engine returns a band:

- **exact** — a deterministic key set matched (e.g. `[nin]`), **or** the composite
  cleared the optional `auto_accept_threshold`.
- **probable** — composite ≥ `review_threshold` (needs human review).
- **none** — below `review_threshold`.

The composite is the weighted-average field similarity, **normalised by the total
configured weight**, so it stays in `[0,1]` however weights are set. Each result
carries a **PII-free, per-rule explanation** (field names, comparators,
similarities, contributions — never the raw values) for transparency and audit.

## The `MatchScorer` seam (FR-DUP-07-ready)

`MatchScorer::score(array $candidate, array $existing, MatchingConfig $config): MatchScore`
is the **only** thing an alternative scorer implements. Records are associative
arrays of canonical fields; a missing value on either side is never a match; the
returned `MatchScore` has a normalised composite, a `deterministic` flag, and the
explanation. **Banding is the engine's job, not the scorer's.**

To add an external/AI scorer later (FR-DUP-07): implement `MatchScorer` and rebind
it in `MatchingServiceProvider::register()` — no caller changes. **No AI scorer is
implemented in this step.**

## DEFAULT configuration (seeded — adjust via the admin API)

| Part | Default |
| --- | --- |
| Deterministic (decisive → `exact`) | `[nin]`, `[bvn]` |
| Fuzzy weights (sum 1.00) | last_name `.25` (**phonetic**) · first_name `.15` (**phonetic**) · date_of_birth `.20` (**date_proximity**) · phone `.20` (exact) · lga `.05` (exact) · ward `.05` (exact) · address `.10` (jaro_winkler) |
| `review_threshold` | **0.75** |
| `auto_accept_threshold` | **0.92** |
| `exact_match_behaviour` | **confirm** (human confirms exact matches; alt: `auto_link`) |

## Comparators

`exact` · `jaro_winkler` · `levenshtein` · **`phonetic`** (Hausa/Nigerian name
variance: a phonetic-code match → 1.0, else Jaro-Winkler) · **`date_proximity`**
(exact → 1.0, decaying to 0 over ~3 years so DOB typos still contribute).

## Candidate blocking (performance — NFR-PERF-01)

Fuzzy matching never scans the whole registry. `CandidateGatherer` gathers only
records sharing an **index-backed** blocking key with the candidate — NIN, BVN,
phone, or `block_name_dob` = `phonetic(last_name)|dob_year` (maintained on the
beneficiary on save, indexed). The gathered set is hard-capped
(`CandidateGatherer::MAX_CANDIDATES`), so even a common block stays bounded and a
single lookup returns within the 5-second target. `FuzzyDuplicateFinder` = gather
→ score (engine) → keep exact + probable. It is read-only (surfaces, never blocks);
batches run it from the queue worker, single lookups inline.

## Admin API (System Administrator)

- `GET /api/v1/matching/config` — active config (`matching.view`).
- `PUT /api/v1/matching/config` — publish a new version (`matching.edit`), audited.
- `GET /api/v1/matching/config/versions` — full version history (`matching.view`).
