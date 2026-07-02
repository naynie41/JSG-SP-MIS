# Registry domain — hybrid registration sources

The beneficiary registry accepts registrations from several inbound channels
(PRD FR-REG-01/02, §6.1). Every channel converges on **one validation ruleset**
and **one provenance-stamping choke-point**, so a record's origin is always
traceable via `registration_source` + `import_batch_id` + `original_record_id`.

## Channels

| Channel | Provenance (`registration_source`) | Path |
| --- | --- | --- |
| Manual entry | `manual` | `POST /api/v1/beneficiaries` |
| Excel / CSV upload | `excel` / `csv` | Bulk import pipeline |
| Kobo Collect export | `kobo` | Bulk import pipeline (`source=kobo`) |
| ODK export | `odk` | Bulk import pipeline (`source=odk`) |
| Inbound REST API | `api` | `POST /api/v1/beneficiaries/intake` (see `docs/registry-intake.md`) |
| Existing government systems / future sources | `government_system` / … | Add an adapter (below) |

## Shared building blocks

| Piece | Role |
| --- | --- |
| `Support/BeneficiaryRules` | The single registration ruleset — used by manual, import, and API paths |
| `Services/BeneficiaryRegistrar` | The only place a beneficiary is persisted; stamps owner + provenance + origin, audited |
| `Imports/SpreadsheetReader` | Reads Excel/CSV (incl. Kobo/ODK exports) into header-keyed rows |
| `Imports/ImportRowValidator` | Normalises + validates one canonical row with `BeneficiaryRules` |
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
