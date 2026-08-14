# Registry REST intake & hybrid sources (FR-REG-02)

Documents the inbound integration surface for beneficiary registration. All
endpoints live under `/api/v1`, require a Sanctum bearer token with the
`beneficiary.create` permission, and use the standard response envelopes
(`{ "data": … }` on success, `{ "error": { code, message, details } }` on error).
Records are owned by the calling user's MDA and stamped with provenance so they
stay traceable to origin.

## 1. Inbound REST intake (single record) — `source = "api"`

```
POST /api/v1/beneficiaries/intake
Authorization: Bearer <token>
Content-Type: application/json
```

Authenticated and **rate-limited** (`throttle:registration-intake`, 60/min per
account; exceeding it returns `429` with `Retry-After`).

### Request body

| Field | Rules |
| --- | --- |
| `first_name` | required, string |
| `middle_name` | optional, string |
| `last_name` | required, string |
| `nin` | optional, 11 digits, unique |
| `bvn` | optional, 11 digits, unique |
| `phone` | optional, string |
| `date_of_birth` | required, date, in the past |
| `gender` | required, one of `male` `female` `other` |
| `address` | optional, string |
| `lga` | required, a Jigawa LGA (e.g. `dutse`) |
| `ward` | required, string |
| `original_record_id` | **required** — the caller's own id for this record |
| `household_ref` | optional — the source's own household key (see §2a) |
| `household_role` | optional — `head` `spouse` `child` `parent` `sibling` `other` |
| `household_head` | optional boolean — marks this person as the head |

Validation is the shared `BeneficiaryRules` ruleset — identical to every other
ingestion source. NIN/BVN/phone are normalised (non-digits stripped) before
validation.

> There is **no manual single-record create endpoint**. This intake is a *source*
> channel (`source = "api"`): it requires the caller's own `original_record_id` so
> the record stays traceable to origin. `POST /beneficiaries` and `POST /households`
> do not exist.

### Responses

- `201 Created` — `data` is the created beneficiary (NIN/BVN masked). Provenance:
  `registration_source = "api"`, `original_record_id` as supplied.
- `422 Unprocessable Entity` — `error.code = "VALIDATION_ERROR"`, with
  `details: [{ field, message }]`.
- `401` unauthenticated · `403` missing permission · `429` rate limited.

```json
{
  "first_name": "Amina", "last_name": "Sadiq",
  "date_of_birth": "1990-01-01", "gender": "female",
  "lga": "dutse", "ward": "Ward 1",
  "nin": "22200000011", "phone": "08030000001",
  "original_record_id": "PARTNER-SYS-4821"
}
```

## 2a. Households are formed from the source, never created by hand

Since there is no manual create path, the **only** way to express household
grouping is a household reference on the record itself. Supply the source's own
household key and the system finds-or-creates that household (owned by your MDA,
keyed by `(owner_mda_id, household key)`, idempotent across re-sends) and opens a
membership for the person.

The canonical field name is **`household_ref`**. For file imports the adapters also
accept `household_id`, `household_code`, `household`, `hh_id`; the REST intake
accepts `household_ref` or `household_id`. The head flag is truthy on `1`, `true`,
`yes`, `y`, `head`.

Two rules worth knowing before you integrate:

- **One open membership per person.** If someone already belongs to a household,
  a later record naming a different household does **not** move them — the existing
  membership stands and the move must be made deliberately through the membership
  API. Ingestion never reshuffles families silently.
- **Absent means absent.** A record with no household reference creates no
  household; a beneficiary-only source is perfectly valid.

This applies to every source — file upload, this REST intake, SOCU/government
connectors, and flushed offline batches all run the same formation step.

## 2. Kobo / ODK ingestion — `source = "kobo" | "odk"`

Kobo Collect and ODK exports go through the **same queued import pipeline** as
Excel/CSV. Upload the export file and declare its source:

```
POST /api/v1/beneficiaries/imports      (multipart: file=<export.csv|.xlsx>, source=kobo|odk)
GET  /api/v1/beneficiaries/imports/{id} (poll for the preview: summary + per-row errors)
POST /api/v1/beneficiaries/imports/{id}/confirm   (commit valid rows only)
```

The source adapter maps the export's columns (question names, group prefixes,
meta columns) onto the canonical schema and takes `original_record_id` from the
submission's own id (`_id`/`_uuid` for Kobo, `instanceID`/`__id` for ODK).
Everything else — row-level validation, batch tracking, commit-valid-only,
provenance, audit, idempotency — is shared with the Excel/CSV import.

If `source` is omitted, it is inferred from the file extension (`excel`/`csv`).

## 3. Existing government systems & future sources

New sources plug in behind a small adapter interface without touching core
registry logic. See `api/app/Domain/Registry/README.md` for the adapter contract
and a worked example.

## 4. Idempotent intake (offline-capture groundwork — FR-REG-08)

Create/intake paths accept an optional **client-supplied idempotency key** so a
future offline client can flush its queue safely without creating duplicates.

- `POST /beneficiaries/intake` accepts `idempotency_key` (defaulting to
  `original_record_id` when omitted).
- The key is unique **per owning MDA**. A repeat submission with the same key
  resolves to the existing record and returns **`200 OK`** (a first submission
  returns `201 Created`).
- Bulk import uses each row's `original_record_id` as the key, so re-importing the
  same Kobo/ODK/Excel export does not double-insert.

The offline client and sync engine themselves are **out of scope here** — this is
only the idempotent, documented intake they will build on.

## 5. Supporting documents (FR-REG-07)

Attach scanned documents (national ID, birth certificate, proof of residence,
passport photo, attestation, other) to a beneficiary.

```
GET    /api/v1/beneficiaries/{id}/documents                     (list metadata)
POST   /api/v1/beneficiaries/{id}/documents                     (multipart: file, document_type)
GET    /api/v1/beneficiaries/{id}/documents/{doc}/download      (stream the file)
DELETE /api/v1/beneficiaries/{id}/documents/{doc}               (soft delete)
```

- **Validation:** PDF/JPEG/PNG only, ≤ 5 MB, checked by both extension and actual
  content type (`mimetypes` via finfo), per SECURITY.md §5.
- **Storage:** files are written to a private disk **outside the web root** with a
  random generated name; they are only ever returned through the authorized
  download action (never served statically). A SHA-256 checksum is stored.
- **Access control:** list/download require `beneficiary.view` and ownership (or
  `cross-mda.view`); upload/delete are **owner-MDA only** (`beneficiary.edit`).
- **Audit:** uploads and deletes are audited via the model; downloads record a
  `beneficiary_document.downloaded` entry (sensitive-PII access).

## Traceability guarantee

Every ingested record carries `registration_source` **and**
`original_record_id` (and, for file imports, `import_batch_id`). Nothing
untraceable is saved; invalid rows/payloads are rejected or flagged and never
silently dropped.
