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

Validation is identical to manual registration (`BeneficiaryRules`). NIN/BVN/phone
are normalised (non-digits stripped) before validation.

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

## Traceability guarantee

Every ingested record carries `registration_source` **and**
`original_record_id` (and, for file imports, `import_batch_id`). Nothing
untraceable is saved; invalid rows/payloads are rejected or flagged and never
silently dropped.
