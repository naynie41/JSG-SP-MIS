# Security Hardening Pass — Findings & Remediation (Phase 7)

> Executed against `SECURITY.md`, **NFR-SEC-01/02**, **NFR-AUD-01** on 2026-07-21;
> **re-verified 2026-08-10** (pass 2) and **2026-08-29** (pass 3 — §0 below).
> This pass **verifies and strengthens** — no control was weakened. Each finding lists
> its status: **FIXED** (this pass), **VERIFIED** (already in place, now covered by
> tests), **TRACKED EXCEPTION** (accepted with rationale + owner), or **PEN-TEST /
> OPS** (needs human/external sign-off before go-live).

---


## 0. Re-verification pass — 2026-08-29 (pass 3)

Re-run against a codebase that has moved substantially since pass 2: the Data Import &
Mapping layer, the duplicate queue, the segment/report builder, connector activity
binding, the data-sharing active-vs-revoked report, the graduation record, and a **new
unauthenticated public landing page** at `/`. Pass-2 controls were re-checked and **all
still hold**. Three findings are new.

### F-08 — 21 dependency advisories, 8 of them HIGH — **FIXED** (HIGH)

`composer audit` reported 21 advisories across four packages. The high ones:

| Package | Was | Now | High advisories |
|---|---|---|---|
| `phpoffice/phpspreadsheet` | 5.8.0 | 5.9.0 | 3 |
| `league/commonmark` | 2.8.x | 2.10.0 | 4 |
| `guzzlehttp/guzzle` | 7.15.x | 7.15.5 | 1 |
| `dompdf/dompdf` | 3.1.x | v3.1.6 | 0 (4 medium, 2 low) |

The PHPSpreadsheet ones matter most here: SP-MIS parses MDA-supplied spreadsheets in the
import pipeline, so CVE-2026-59933 (XLS/OLE sector-chain self-loop) and CVE-2026-59932
(unbounded gzip expansion in the Gnumeric reader) are reachable by uploading a crafted
file — memory exhaustion on the import worker. CVE-2026-59931 is an SSRF bypass in
`WEBSERVICE()`.

Resolved inside the existing `composer.json` constraints — **only `composer.lock`
changed**, no constraint was widened. `composer audit` is now clean; `npm audit` reports
0 vulnerabilities.

**This is the third consecutive pass where a clean audit went stale.** Pass 2 recorded 17
new advisories three weeks after pass 1 reported clean; this pass found 21 more nineteen
days later. OPS-07 (scheduled audit) is not optional housekeeping — it is the control.

### F-09 — Beneficiary document download had no rate limit — **FIXED** (HIGH)

`GET /beneficiaries/{b}/documents/{d}/download` streams the most sensitive artefact the
system holds: an uploaded identity document is the ID itself, not a field derived from
it. Every other bulk-PII path carried the `exports` per-user ceiling; this one did not,
so a compromised account could pull documents one request at a time as fast as the
network allowed — precisely the scripted exfiltration the export limiter exists to turn
into noise and an audit trail.

Authorization was never the gap (the policy resolves through `DataSharingGuard`, and a
cross-MDA document 404s on the scope before authorization is reached). The gap was rate.
Now `throttle:exports`.

### F-10 — Report preview endpoints had no rate limit — **FIXED** (MEDIUM)

`POST /reports/segments/preview` and `POST /reports/adhoc/preview` each run a full
aggregate query on demand, and both were added after pass 2. Unbounded, they are a cheap
way to load the database from an ordinary `reporting.view` account.

Given a new `reports` limiter (`RATE_LIMIT_REPORT_PREVIEWS_PER_MINUTE`, default 30)
rather than the export ceiling: narrowing a filter is what an officer does repeatedly
while composing a report, and throttling that at bulk-egress rates would break the screen
long before it protected anything. Nothing leaves the system on a preview — the export
itself is still bounded by `exports` (10/min). A test asserts the preview ceiling stays
strictly above the export ceiling, so the two cannot quietly converge.

### F-11 — Anonymization left a complete copy in the import staging table — **FIXED** (HIGH)

`AnonymizationService` cleared the `beneficiaries` row and purged attached documents, but
never touched `import_rows.payload` — the staged copy of the row the person was created
from, holding first name, last name, NIN, BVN, phone and address exactly as the MDA
supplied them, still joined to the same person by `beneficiary_id`.

Every beneficiary in this system arrives through an import, so this was not an edge case:
**no anonymized record was actually de-identified.** The identifiers stayed queryable by
anyone who could read an import batch, and the record simultaneously reported itself as
erased — `anonymized_at` set, an audit entry saying it happened, and every downstream
check believing it. An erasure that leaves a full copy one table away is worse than no
erasure, because it stops anyone looking.

Anonymization now redacts the direct AND quasi lists out of the staging payload, in the
same transaction. Three deliberate boundaries:

- **The row survives.** Only its identifying keys are removed. It records that this batch
  produced this record; deleting it would rewrite provenance and silently change import
  tallies already reported.
- **Only this person's rows.** A batch holds many people; anonymizing one must not erase
  the rest of the file.
- **`original_record_id` is kept.** It is the source system's own reference and the
  per-MDA idempotency key — clearing it would let a re-import recreate the very person
  the erasure removed.

Both lists go regardless of mode: `aggregate` keeps quasi fields on the REGISTRY row so
de-identified statistics stay possible, and statistics never read the staging table.

`tests/Feature/Privacy/AnonymizationStagingResidueTest.php` — 5 tests, including a sweep
that fails if any table still holds the erased NIN, so a new payload-bearing table cannot
be added without someone noticing that anonymization has to reach it.

**Not covered, and needs a DPO decision (see OPS-08):** the ORIGINAL UPLOADED FILE
(`import_batches.stored_path`) still contains every person in it. CLAUDE.md §11 forbids
mutating the raw file, and a file cannot be surgically edited for one subject without
destroying its value as evidence for everyone else in it. The answer is a retention period
on the FILE — delete batch files after N days — which is a legal period this system must
not invent.

### Re-verified with no change (pass 3)

- NIN/BVN encrypted at rest + keyed-hash lookup; TOTP secrets encrypted.
- Audit log hash-chained, append-only, tamper detected by the verifier.
- Deny-by-default RBAC; a role-less user is denied everywhere.
- Central MDA scoping: the `withoutGlobalScope` allow-list still passes, now including
  the connector→activity relation added for activity-first sync (registered with its
  reason, not merged silently).
- Export matrix + import upload limits.
- **The new public landing page reads nothing from the API** and renders no authenticated
  layout — asserted by a `fetch` spy and a numeral scan in the frontend suite, so an
  anonymous visitor cannot reach system data through the one route that needs no session.

### Test coverage added by pass 3

`tests/Feature/Security/EgressRateLimitTest.php` — 3 tests: document download ceiling,
report preview ceiling, and the invariant that preview stays looser than export.

---

## 0b. Re-verification pass — 2026-08-10 (pass 2)

Pass 1's controls were re-checked against the current codebase (materially changed
since: the data-sharing framework gained an administrative-grant basis, cross-MDA reads
are now audited, a consent UI was added). **Every pass-1 control still holds**; the
findings below are NEW since 2026-07-21.

### F-06 — 17 dependency advisories, 8 of them HIGH — **FIXED** (HIGH)

`composer audit` reported 17 advisories across 4 packages, most published *after*
pass 1 (2026-07-23), so pass 1's "clean of high/critical" was accurate when written and
had since decayed. This is the argument for running the audit rather than trusting the
report.

| Package | Was | Now | Advisories |
|---|---|---|---|
| `phpoffice/phpspreadsheet` | 5.8.0 | 5.9.0 | 3 high — incl. **SSRF bypass via HTTP redirect** in the `WEBSERVICE()` domain allow-list, and unbounded gzip expansion (DoS) |
| `league/commonmark` | 2.8.2 | 2.9.1 | 4 high + 2 medium — quadratic-time and nested-output DoS |
| `guzzlehttp/guzzle` | 7.15.1 | 7.15.2 | 1 high + 1 medium — noncanonical host bypasses host-based checks; cookie domain keeps subdomain scope |
| `dompdf/dompdf` | 3.1.5 | 3.1.6 | 4 medium + 2 low — chroot bypass, local file read via data-URI SVG, file-existence oracles |

`npm audit` reported **5 high** (`react-router-dom` direct; `nanoid`, `postcss`,
`react-router`, `undici` transitive), all resolved by a semver-compatible
`npm audit fix` — no `--force`, no breaking upgrades.

**Both ecosystems now report zero advisories at any severity.** Full backend (674) and
frontend (480) suites pass on the updated dependencies.

The guzzle and phpspreadsheet items matter beyond the version bump: the SSRF-bypass and
host-canonicalisation advisories are the exact class **TE-04/OPS-03** (worker egress
allow-list) exists to contain. They strengthen, not replace, that requirement.

### F-07 — Production CSP blocks the GIS map tiles — **FIXED (documented)** (MEDIUM)

`docker/nginx/prod.conf.template` sets `img-src 'self' data:` — correct and strict. But
both map components fall back to `https://{s}.tile.openstreetmap.org/...` when
`VITE_MAP_TILE_URL` is unset, and that variable appeared in **no** template:
`web/.env.example` did not mention it, nor did `.env.prod.example`, and
`docker-compose.prod.yml` ships no tile service.

So in production the coverage maps (FR-GIS-01) render blank, with the reason invisible
to whoever deploys it.

**Deliberately NOT fixed by loosening the CSP.** Allowing a third-party tile host by
default would weaken a control and add an outbound dependency that tells that host which
LGAs/wards a government officer is examining, and when. Instead `web/.env.example` now
documents the variable, states that it is required in production, and records that using
a third-party tile host is a DPO decision requiring a deliberate `img-src` extension.
**Ops action: OPS-06 below.**

### Re-verified with no change

| Control | Evidence today |
|---|---|
| Dependency locks committed | `composer.lock`, `package-lock.json` updated in place |
| Secrets absent from the browser bundle | only `VITE_API_BASE_URL`, `VITE_APP_NAME`, `VITE_MAP_TILE_URL` — none secret |
| `.env` never committed; templates documented | `.gitignore` `.env` + `*/.env`, `!*/.env.example`; per-service templates + `.env.prod.example` |
| Rate limiters | `login`, `mfa`, `registration-intake`, `exports`, `imports` all still registered and env-tunable |
| Security headers | nosniff, DENY, no-referrer, `default-src 'none'` API CSP, Permissions-Policy, HSTS-on-HTTPS |
| Audit tamper-evidence | 8 `SecurityHardeningTest` assertions green: chain linkage, forged-row detection, app-level append-only refusal |
| Central MDA scoping | direct-id bypass still 404 + audited; the new administrative-grant basis is bounded by expiry, single-MDA scope and the consent gate (`AdminGrantGovernanceTest`) |

**Not exercised this pass:** `php artisan audit:verify-chain` against a live chain —
Postgres was not running locally. Its logic is covered by the two chain tests, but the
command itself should be run once against staging data before go-live (folded into
**OPS-04**).

---

## 1. Findings & remediation list

### F-01 — NIN/BVN stored in plaintext — **FIXED** (HIGH)
The Phase 2 schema stored NIN/BVN plaintext ("must be uniquely indexed and matched").
SECURITY.md §4 requires application-layer encryption of NIN, BVN, and TOTP secrets.

**Fix:** `nin`/`bvn` are now Laravel `encrypted` casts (AES-256-CBC + MAC under
`APP_KEY`). Exact matching and uniqueness move to new deterministic **keyed-hash
columns** `nin_hash`/`bvn_hash` (HMAC-SHA256; key derived from `APP_KEY` via HKDF —
`IdentifierHasher`). The partial-unique indexes moved to the hash columns. Migration
`2026_07_21_150000_encrypt_beneficiary_identifiers` backfills existing rows in place.
Updated to hash-based equality: deterministic duplicate finder, candidate gatherer
(blocking), lookup/serve seam, benefit-import resolution, beneficiary list/export
search, and registration uniqueness (`UniqueIdentifier` rule). The keyed HMAC (not a
bare SHA-256) means a leaked table cannot be reversed by enumerating the 11-digit
space without also holding the app key.

**Consequence to operate:** rotating `APP_KEY` now requires re-encrypting `nin`/`bvn`
and re-computing `nin_hash`/`bvn_hash` (a rotation runbook is a go-live prerequisite —
see PT-04). Fuzzy-matched fields (name, phone) remain plaintext — **tracked exception
TE-01** below.

### F-02 — Audit log not hash-chained — **FIXED** (MEDIUM)
Append-only was already enforced (Eloquent guard + PostgreSQL `UPDATE/DELETE/TRUNCATE`
triggers), but SECURITY.md §6 called for hash-chaining "in a later hardening pass" —
this pass.

**Fix:** every new `audit_log` row records `chain_position` (1..n, partial-unique),
`prev_hash`, and `entry_hash` = SHA-256 over the row's canonical payload + the
previous entry's hash (genesis = 64 zeros). Any edit, deletion, or reorder of a
chained row breaks every later hash. `php artisan audit:verify-chain` walks and
re-computes the chain (exit 0 intact / 1 tamper-evident, first break named);
concurrent writers are serialized by the unique index + a bounded retry. Rows that
predate the migration have `chain_position = NULL` (pre-chain era) — they were never
mutable (the DB triggers predate them) but are not chain-verifiable; the verifier
reports their count. **Backfilling them was deliberately rejected** — it would require
UPDATEs on audit rows, i.e. weakening the append-only control to add tamper-evidence.

### F-03 — No rate limit on export / write-heavy endpoints — **FIXED** (MEDIUM)
Auth (`login` 5/min by email+IP, `mfa` 5/min) and intake (60/min) were limited;
exports and bulk-write endpoints were not, contrary to the task scope + OWASP A04.

**Fix:** two new per-user limiters (config `security.rate_limits`, env-tunable):
`exports` (default 10/min) on `/beneficiaries/export`, report generation, report
download, ad-hoc export, and definition runs; `imports` (default 12/min) on
beneficiary/activity/benefit import uploads, offline sync batches, and document
uploads. 429s render the standard error envelope.

### F-04 — `guzzlehttp/guzzle` medium advisories — **FIXED** (MEDIUM)
`composer audit` reported three medium advisories (< 7.15.1: host-only cookie scope,
unbounded response cookies; < 7.14.2: Proxy-Authorization forwarded to origin).
Updated to the patched release; `npm audit` was already clean (0 vulnerabilities).
No high/critical advisories in either ecosystem.

### F-05 — Ciphertext length vs column width — **FIXED** (follow-on of F-01)
`nin`/`bvn` columns widened to `text` to hold ciphertext; hidden from serialization
(`$hidden`), masked in audit snapshots, and the derived hash columns are excluded
from audit diffs entirely.

---

## 2. Verified controls (already correct — now regression-tested where new)

| Control | Where | Status |
|---|---|---|
| TLS in transit | HSTS emitted when HTTPS; TLS termination is deployment-level | VERIFIED / **OPS-01** for prod TLS config |
| TOTP secret + recovery codes encrypted | `User` casts `encrypted` / `encrypted:array` | VERIFIED |
| Password policy | `Password::min(12)->uncompromised()` (breached-password check), bcrypt cost 12 | VERIFIED |
| MFA for privileged roles | `roles.requires_mfa` (SysAdmin, Executive) → mandatory enrol/challenge at login | VERIFIED |
| Account lockout | 5 attempts, exponential backoff (config `security.lockout`), audited, generic errors, timing-equalized credential check | VERIFIED |
| Token/session lifetimes | idle 30 min + absolute 480 min, revocation on logout/password change | VERIFIED |
| Deny-by-default RBAC | `permission` middleware on every route; SysAdmin-implicit; role-less user gets nothing | VERIFIED + new test |
| Central MDA scoping | `MdaScope` global scope via `ScopedToMda`; every `withoutGlobalScope` site audited in Phase 7 step 1 (legitimate: system jobs, global catalog, governed cross-MDA seams) | VERIFIED + new bypass-attempt tests |
| Cross-MDA read/serve governance | single `DataSharingGuard` (owner / oversight / consent-gated grant); denials audited + 404 (no existence leak) | VERIFIED (Phase 7 step 1 tests) |
| Strict CORS | explicit allow-list from env, no wildcard | VERIFIED |
| Security headers | nosniff, DENY framing, no-referrer, restrictive CSP, Permissions-Policy, HSTS on HTTPS, `X-Powered-By` stripped | VERIFIED |
| Injection | Eloquent/parameterised everywhere; Form Request validation; React escaping (no `dangerouslySetInnerHTML` with user data) | VERIFIED |
| Error hygiene | JSON envelope, generic messages, no stack traces (`APP_DEBUG=false` template), correlation ids | VERIFIED |
| Secrets | env-only; `.env.example` documented placeholders; `.env` gitignored; frontend bundle exposes only `VITE_API_BASE_URL`/name | VERIFIED |
| Audit completeness | `Auditable` trait + `AuditLogger`; PII masked/omitted by `AuditScrubber`; lookups audited without identifier values | VERIFIED |
| Export permission matrix | distinct `export` + `export.reveal_pii` (SysAdmin-only), NIN/BVN masked, exports audited & scope-inheriting | VERIFIED (Phase 6/7 tests) |

---

## 3. Tracked exceptions (accepted, documented — DPO/architect owned)

- **TE-01 — Name/phone/address remain plaintext.** Fuzzy duplicate matching
  (FR-DUP-03) requires similarity scoring and phonetic blocking, which ciphertext
  cannot support. Mitigations: DB-volume encryption (OPS-02), strict scoping, audit,
  masked exports. Revisit if the DB adds transparent column encryption. *Owner: DPO.*
- **TE-02 — Pre-chain audit rows (`chain_position` NULL).** Chaining them would
  require UPDATEs on the append-only table. They remain protected by the DB triggers;
  the verifier counts them. *Owner: Technical Lead.*
- **TE-03 — `SESSION_ENCRYPT=false`.** Sessions are Redis-side and the API is
  token-based (Sanctum); session payloads hold no PII. *Owner: Technical Lead.*
- **TE-04 — A10 SSRF surface.** Sync connector endpoints/credentials come only from
  `config/sync.php` + env (`credentials_ref`), never from user input; there is no
  connector-create API. Enforce an egress allow-list at the network layer for the
  worker in production (OPS-03). *Owner: Ops.*

---

## 4. OWASP Top 10 checklist result

- [x] **A01 Broken Access Control** — RBAC + MDA scoping central; bypass attempts tested (404 + audited); deny-by-default tested.
- [x] **A02 Cryptographic Failures** — TLS (HSTS), NIN/BVN/TOTP encrypted at rest (F-01), bcrypt-12 + breached-password check; DB-volume encryption = OPS-02.
- [x] **A03 Injection** — parameterised queries only; Form Requests everywhere; React default escaping.
- [x] **A04 Insecure Design** — least privilege, ownership/consent gates, export matrix, new export/import ceilings (F-03).
- [x] **A05 Security Misconfiguration** — debug off, headers, CORS allow-list, no default creds in templates.
- [x] **A06 Vulnerable Components** — locks committed; `composer audit` + `npm audit` clean of high/critical (F-04).
- [x] **A07 Auth Failures** — MFA (privileged mandatory), lockout + backoff, idle/absolute timeouts, generic errors.
- [x] **A08 Integrity Failures** — locked deps; audit log append-only **and hash-chained with verifier** (F-02).
- [x] **A09 Logging/Monitoring Failures** — complete audit trail, PII masked, denials + lockouts audited; ops alerting = OPS-04.
- [x] **A10 SSRF** — no user-supplied outbound URLs; connector endpoints config-only (TE-04 + OPS-03).

---

## 5. Items requiring human / external pen-test sign-off (go-live gate)

- **PT-01 — External penetration test** (SECURITY.md §11) against a staging build:
  priority targets — auth/MFA flow, export matrix, cross-MDA seams (lookup, service
  requests, sync offline batches), file upload/download, audit-chain verification.
- **PT-02 — DPO sign-off (NDPA/NDPR)** on: consent model, retention/anonymisation
  schedule (right-of-access/export exists; retention policy is a stakeholder
  decision), the export matrix, and TE-01.
- **OPS-01 — TLS 1.2+ everywhere in production** (reverse-proxy config + cert
  management); HSTS is emitted by the app when HTTPS.
- **OPS-02 — Encrypt the DB volume/disk + offsite encrypted backups** (SECURITY.md
  §4/§11) — infrastructure task, complements F-01 for the plaintext fuzzy fields.
- **OPS-03 — Egress allow-list for workers** (sync connectors) in production.
- **OPS-04 — Monitoring/alerting**: schedule `audit:verify-chain` (e.g. daily) and
  alert on failure; alert on lockout spikes and 429 storms.
- **OPS-05 — Key management**: per-environment `APP_KEY`; rotation runbook must
  include NIN/BVN re-encrypt + re-hash (F-01) — never rotate without it.
- **OPS-06 — Map tiles (F-07)**: set `VITE_MAP_TILE_URL` to a tile source on your own
  origin before go-live, or obtain DPO sign-off for a named third-party host and extend
  `img-src` in `docker/nginx/prod.conf.template` to exactly that host. Leaving it unset
  ships working maps in dev and blank maps in production.
- **OPS-08 — Retention period for RAW IMPORT FILES** (F-11): anonymising a subject
  cannot edit the spreadsheet they arrived in without destroying it as evidence for
  everyone else in that file. A batch-file retention period is a legal value the DPO must
  supply; until then, uploaded files keep every identity they contained.
- **OPS-07 — Recurring dependency audit**: F-06 was entirely new advisories published
  three weeks after a pass that reported clean. A point-in-time audit has a shelf life;
  run `composer audit` + `npm audit` on a schedule (CI weekly and pre-release) and treat
  high/critical as release-blocking.

---

## 6. Test coverage added by this pass

`tests/Feature/Security/SecurityHardeningTest.php` — 8 tests: encryption at rest +
hash lookup; chain linkage + verifier; tamper detection (forged row caught); app-level
append-only guard; direct-id scoping bypass (404 + audited) incl. export scope;
deny-by-default for a role-less user; export + import rate limits (429 envelope).
Pre-existing suites (RBAC matrix, data-sharing governance, dedup cascade, export
masking/matrix) all remain green against the encrypted schema — 400+ tests total.
