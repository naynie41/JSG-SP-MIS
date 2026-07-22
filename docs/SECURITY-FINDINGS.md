# Security Hardening Pass — Findings & Remediation (Phase 7)

> Executed against `SECURITY.md`, **NFR-SEC-01/02**, **NFR-AUD-01** on 2026-07-21.
> This pass **verifies and strengthens** — no control was weakened. Each finding lists
> its status: **FIXED** (this pass), **VERIFIED** (already in place, now covered by
> tests), **TRACKED EXCEPTION** (accepted with rationale + owner), or **PEN-TEST /
> OPS** (needs human/external sign-off before go-live).

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

---

## 6. Test coverage added by this pass

`tests/Feature/Security/SecurityHardeningTest.php` — 8 tests: encryption at rest +
hash lookup; chain linkage + verifier; tamper detection (forged row caught); app-level
append-only guard; direct-id scoping bypass (404 + audited) incl. export scope;
deny-by-default for a role-less user; export + import rate limits (429 envelope).
Pre-existing suites (RBAC matrix, data-sharing governance, dedup cascade, export
masking/matrix) all remain green against the encrypted schema — 400+ tests total.
