# Registry domain — hybrid registration sources

The beneficiary registry accepts registrations from several inbound channels
(PRD FR-REG-02, §6.1). **Ingestion is source-only — there is NO manual
single-record entry of beneficiaries or households** (enforced by
`NoManualCreateRouteTest`). Records enter through bulk import, the REST intake, or
a future source adapter. Every channel converges on **one validation ruleset** and
**one provenance-stamping choke-point**, so a record's origin is always traceable
via `registration_source` + `import_batch_id` + `original_record_id`.

## Channels

| Channel | Provenance (`registration_source`) | Path |
| --- | --- | --- |
| Excel / CSV upload | `excel` / `csv` | Bulk import pipeline |
| Kobo Collect export | `kobo` | Bulk import pipeline (`source=kobo`) |
| ODK export | `odk` | Bulk import pipeline (`source=odk`) |
| Inbound REST API | `api` | `POST /api/v1/beneficiaries/intake` (see `docs/registry-intake.md`) |
| Existing government systems / future sources | `government_system` / … | Add an adapter (below) |

> Read/browse/search, **owner-only correction of existing records** (edit/soft
> delete), the ownership + non-owner **serve/lookup** seam, and household
> **membership management + history** remain available — only the create paths
> were removed.

## Identity vs non-identity validation (§9, FR-REG-05/09)

A row's failures are split into two groups so a bad non-identity value never
discards an otherwise-good person:

- **Identity fields** — name, phone, NIN, BVN (`BeneficiaryRules::IDENTITY_FIELDS`).
  A **present-but-malformed** identity field **rejects the whole row** to the error
  report; it is never partial-saved and never reaches the live tables. An **absent
  optional** NIN/BVN/phone is still valid.
- **Non-identity fields** — DOB, gender, address, LGA, ward
  (`BeneficiaryRules::NON_IDENTITY_FIELDS`, all nullable). A failure **drops/flags
  just that field** (nulled in the staged payload) and the **row still saves**.
- A NIN/BVN **uniqueness** hit is a *duplicate signal*, not a malformed-field
  reject — it is routed to the match/serve flow (`match_band`), not the reject group.

`ImportRowValidator` classifies each failure; `ParseImportBatch` stores the
group-tagged errors (`{field, message, group: identity|dropped|duplicate}`) per row
and two distinct batch counters — `rejected_rows` (identity malformed) and
`dropped_field_rows` (rows kept with a dropped field) — surfaced in the preview
summary. A rejected row is retained in staging (`is_valid=false`) for review but is
skipped at commit, so **no rejected PII reaches `beneficiaries`/`households`**.

## Activity-first upload (§9, FR-REG-10/FR-PRG-05)

Every bulk upload is bound to a **registered activity that the importing MDA
owns** — an activity must exist first. `UploadImportRequest` requires a valid
`activity_id` and refuses an upload with **no activity, a non-existent activity, or
one owned by another MDA** (422). The activity is recorded on the batch
(`import_batches.activity_id`).

On commit, each resulting beneficiary's participation is recorded as the
**intervention under that activity**: `CommitImportBatch` enrolls the new
beneficiary (individual programme) or the formed household (household programme)
into the activity's programme via `EnrollmentService`, scoped to the importing MDA.
It is best-effort and idempotent — an already-enrolled/ineligible/type-mismatched
target simply records no enrollment and never blocks the registry commit.
Beneficiaries still enter the shared registry under first-importer ownership
(FR-OWN-01); enrollment ownership follows the enrolling MDA, unchanged.

> The single-record REST intake (`/beneficiaries/intake`) is a separate
> synchronous channel with no batch; activity binding there is a later follow-up.

## Household formation (source-driven, §9)

Households are **not** created manually either — they are formed during ingestion
from a source **household-reference** field. When a source row/record carries a
household key, `HouseholdIngestionService` finds-or-creates the household owned by
the importing MDA (keyed by `(owner_mda_id, original_record_id)`, idempotent),
opens a membership (single-open rule enforced; DB partial-unique backstop), sets
the head from a flagged row, and stamps provenance (`source` + `import_batch_id`).

Recognised source columns (via the adapters' alias mapping):
`household_id` / `household_ref` / `household_code` (the key), `household_role` /
`relationship` (role), and `household_head` / `is_head` (truthy → head). The REST
intake accepts the same as `household_id` / `household_role` / `household_head`.

## Shared building blocks

| Piece | Role |
| --- | --- |
| `Support/BeneficiaryRules` | The single registration ruleset — used by manual, import, and API paths |
| `Services/BeneficiaryRegistrar` | The only place a beneficiary is persisted; stamps owner + provenance + origin, audited |
| `Imports/SpreadsheetReader` | Reads Excel/CSV (incl. Kobo/ODK exports) into header-keyed rows |
| `Imports/ImportRowValidator` | Normalises + validates one canonical row with `BeneficiaryRules`, classifying failures into identity-reject / non-identity-drop / duplicate |
| `Jobs/ParseImportBatch` / `Jobs/CommitImportBatch` | Queued preview then commit-valid-only, idempotent |

## The source-adapter seam

`Imports/Adapters/RegistrationSourceAdapter` is the **only** thing a new source
needs. An adapter does two things:

1. `source(): RegistrationSource` — the provenance to stamp.
2. `map(array $raw): array` — map one raw source record onto the canonical
   beneficiary fields plus `original_record_id`.

`FieldMappingAdapter` provides alias-based mapping (it canonicalises keys —
lower-casing and stripping group prefixes like `personal/first_name` or
`respondent-nin`) so most sources are a few lines. `KoboAdapter` and `OdkAdapter`
just declare their record-id meta keys; `DefaultImportAdapter` serves plain
Excel/CSV.

The parse → validate → commit pipeline calls the adapter and never changes, so
**core registry logic stays untouched** when a source is added.

### Adding a new source (e.g. an existing government system)

```php
// 1. Implement the adapter.
final class GovSystemAdapter extends FieldMappingAdapter
{
    public function source(): RegistrationSource
    {
        return RegistrationSource::GovernmentSystem;
    }

    protected function idKeys(): array
    {
        return ['citizen_ref']; // that system's own record id
    }

    // Optional: extra field aliases specific to the source.
    protected function extraAliases(): array
    {
        return ['nin' => ['national_id_no']];
    }
}

// 2. Register it in RegistryServiceProvider::register().
$registry->register(new GovSystemAdapter);
```

Uploading a file with `source=government_system` (or wiring the adapter into a
dedicated intake) now flows through the same validation, batch tracking,
row-level error reporting, and provenance stamping — no other changes required.

## PRD requirement map (Phase 2)

| Requirement | Delivered |
| --- | --- |
| **FR-REG-01** Individual + household registration (source-only) | Beneficiaries via import/intake; households formed from the source household-reference by `HouseholdIngestionService`. No manual create path. |
| **FR-REG-02** Hybrid sources (Excel/CSV, Kobo, ODK, REST API, gov systems) | Import pipeline + source adapters; `BeneficiaryIntakeController` (`source=api`); UI `ImportListPage` |
| **FR-REG-03** Provenance on every record | `BeneficiaryRegistrar` stamps `registration_source` + `import_batch_id` + `original_record_id`; shown on the detail "Provenance" panel |
| **FR-REG-04** Mandatory fields + formats | `BeneficiaryRules` (shared by manual/import/API); zod `beneficiarySchema` mirrors them |
| **FR-REG-05 / FR-REG-09** Reject malformed identity / drop-flag non-identity | Standard `VALIDATION_ERROR` envelope; import row-level errors split into identity-reject vs non-identity-drop groups (`ImportRowValidator` + `rejected_rows`/`dropped_field_rows` counters); inline UI errors |
| **FR-REG-06** Bulk import preview before commit | `ParseImportBatch` (preview) → confirm → `CommitImportBatch`; UI `ImportBatchPage` |
| **FR-REG-07** Document attachment | `BeneficiaryDocumentController` (private storage, validated, audited); UI `DocumentsPanel` |
| **FR-REG-08** Offline-capture groundwork | Idempotent intake via `idempotency_key` / `original_record_id` (see `docs/registry-intake.md`) |
| **FR-REG-10 / FR-PRG-05** Activity-first upload | `UploadImportRequest` requires an MDA-owned `activity_id`; batch stores it; `CommitImportBatch` records the intervention (enrollment) under it |
| **FR-OWN-01** First MDA owns the record | `owner_mda_id` stamped from the caller's MDA on every create |
| **FR-OWN-02** Owner-only edit of core profile | `BeneficiaryPolicy@update`; UI shows edit only to the owner (server still enforces → 403) |
| **FR-OWN-03** Non-owner lookup / serve path | `BeneficiaryLookupService` + `BeneficiaryRevealResource`; UI `LookupModal` (reveal fields only) |
| **FR-OWN-04** Auto-route / assign hook | `BeneficiaryRouter` → `ProgrammeMatchingRouter` (Phase 4): `GET /beneficiaries/{id}/routing-suggestions?need=`, `POST /beneficiaries/{id}/routing-assignments` — suggest-then-confirm, explicit + audited, never silent |
| **FR-OWN-05** Ownership transfer with owner approval | `OwnershipTransferController` + `OwnershipTransferService` (request → approve/reject), audited |

Households (PRD §9): `HouseholdController` + `HouseholdMembershipService` keep full
membership history — a single open membership per beneficiary is enforced; move
closes the old membership and opens a new one. UI: `HouseholdDetailPage`.

All create/edit/delete + document access are written to the append-only audit log
(`Auditable` + explicit `beneficiary_document.downloaded`).
