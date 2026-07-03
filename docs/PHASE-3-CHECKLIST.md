# Phase 3 — Completion checklist (Duplicate Verification)

Maps each delivered item to its PRD requirement ID. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases).
Module docs: [api/app/Domain/Matching/README.md](../api/app/Domain/Matching/README.md).

## Matching engine & configuration

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-DUP-01** — duplicate check runs **before save** on every import (per-row + within-batch) and as a standalone lookup | Queued screening annotates every preview row against the registry and earlier rows in the batch; standalone search runs the same engine | `Registry/Jobs/ParseImportBatch`, `Registry/Services/BatchDuplicateScreener`, `BeneficiaryController::search` | `ImportDuplicateScreeningTest`, `ServeSearchTest`, `DuplicateAccuracyTest` |
| **FR-DUP-02** — rules/thresholds configurable, versioned, admin-editable | Versioned `MatchingConfig` (append-only publish, one active); admin API + UI | `Matching/Models/MatchingConfig`, `Matching/Services/MatchingConfigService`, `MatchingConfigController`, `web/features/registry/MatchingConfigPage` | `MatchingConfigTest`, `MatchingConfigPage.test` |
| **FR-DUP-03** — deterministic **and** fuzzy matching with banded outcomes | Deterministic key sets + weighted fuzzy comparators (phonetic, date-proximity, jaro-winkler, levenshtein, exact); exact/probable/none bands | `Matching/Engine/MatchingEngine`, `Scoring/RuleBasedMatchScorer`, `Scoring/Comparators/*` | `MatchingEngineTest`, `RuleBasedMatchScorerTest`, `FuzzyComparatorsTest`, `DeterministicMatcherTest`, `PhoneticEncoderTest`, `FuzzyMatchingAccuracyTest`, `DuplicateAccuracyTest` |
| **FR-DUP-04** — match **REVEAL** (recognise without exposing the full profile) | Reveal-only projection (name+id, owner MDA, source, reg date, LGA/Ward, status; empty Phase-4 programmes/benefits) on import previews and search results | `Http/Resources/BeneficiaryRevealResource`, `ImportBatchController::attachMatchReveals`, `web` `MatchRevealPanel` | `ImportDuplicateScreeningTest`, `ServeSearchTest` |
| **FR-DUP-05** — resolve flagged rows (new-with-justification / link–request-to-serve / skip); request-to-serve never transfers ownership | Per-row resolve endpoint + commit branching; serve requests via the Phase-2 serve seam, owner accepts/declines | `ImportBatchController::resolveRow`, `Registry/Jobs/CommitImportBatch`, `Registry/Services/ServeRequestService`, `ServeRequestController`, `web` `ResolveRowControls`/`RequestToServeButton`/`ServeRequestsPage` | `ImportResolutionTest`, `ServeSearchTest`, `ImportBatchPage.test`, `ServeRequestsPage.test` |
| **FR-DUP-06** — every decision audited (actor, choice, justification, matched record) | `import.row_resolved` + `serve_request.created/accepted/declined` audit entries | `AuditLogger` in `resolveRow`, `ServeRequest` (Auditable), `ServeRequestService` | `ImportResolutionTest`, `ServeSearchTest` |
| **FR-DUP-07** — pluggable scorer seam for a future external/AI scorer | `MatchScorer` contract; rebindable in the service provider (no AI scorer ships in Phase 3) | `Matching/Contracts/MatchScorer`, `MatchingServiceProvider` | `MatchingEngineTest` |

## Ownership & serve seam

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-OWN-03** — request-to-serve without ownership transfer | `ServeRequestService` routes to the owner MDA; `owner_mda_id` never changes on request/accept/decline | `Registry/Services/ServeRequestService`, `Registry/Models/ServeRequest` | `ImportResolutionTest`, `ServeSearchTest` |
| **No silent auto-merge** | Engine is read-only; default `exact_match_behaviour = confirm`; fuzzy-only never reaches auto-accept | `Matching/Engine/MatchingEngine` | `DuplicateAccuracyTest` |

## Accuracy & performance

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| Labelled synthetic fixtures across ≥2 MDAs | Exact + fuzzy duplicates, near-misses, clear non-duplicates; two fixture MDAs; synthetic only | `Database/Seeders/MatchFixtureSeeder` | `DuplicateAccuracyTest` |
| Accuracy — precision/recall + band behaviour | 1.00 / 1.00 on the default config; exact/probable/none asserted; cross-MDA; read-only | — | `DuplicateAccuracyTest`, `FuzzyMatchingAccuracyTest` |
| **NFR-PERF-01** — single check within target; batch bounded via blocking | Index-backed candidate blocking caps the scored set; single lookup + 50-row batch on a ~1,200-row registry stay well within 5s | `Registry/Services/CandidateGatherer`, `FuzzyDuplicateFinder`, `BatchDuplicateScreener` | `MatchingPerformanceTest`, `FuzzyBlockingTest` |

## UI (design system)

| Item | Delivered | Where |
| --- | --- | --- |
| Import review — match badges, expandable REVEAL panel, per-row resolve + bulk | Composes shared Data Table (expandable rows), Badge (status map), Modal, Field set | `web/features/registry/ImportBatchPage`, `ResolveRowControls`, `MatchRevealPanel` |
| Standalone duplicate search + request-to-serve | Query form → ranked reveal candidates → request-to-serve | `web/features/registry/DuplicateSearchPage`, `RequestToServeButton` |
| Serve-request inbox — owner accept/decline | Direction + status badges; owner-only decision with reason | `web/features/registry/ServeRequestsPage` |
| Matching-config admin — rules/weights/thresholds/behaviour, confirmed publish | Versioned editor with validation + confirm dialog | `web/features/registry/MatchingConfigPage` |

## Verification

- Backend: `php artisan test` — **all green** (incl. pre-save gate, resolution paths, request-to-serve ownership invariance, decision auditing, config versioning, accuracy, performance).
- Backend: Pint (style) + Larastan level 5 (static analysis) — **clean**.
- Frontend: `npm run typecheck` + `npm run lint` (oxlint) + `npm run test` — **all green**.
- Docs: [api/app/Domain/Matching/README.md](../api/app/Domain/Matching/README.md) (FR-DUP-01→07 map + tuning guide + defaults); this checklist; root README Phase 3 section.

## Local demo

```bash
# Non-MFA admin + two MDA accounts for clicking through the flow:
docker compose exec api php artisan db:seed --class="Database\Seeders\LocalDevSeeder"
# Labelled duplicate fixtures across two MDAs (for the duplicate search demo):
docker compose exec api php artisan db:seed --class="Database\Seeders\MatchFixtureSeeder"
```
