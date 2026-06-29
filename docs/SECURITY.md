# SECURITY.md — Security & Data-Protection Requirements

> SP-MIS holds **citizens' personal data (PII)** on behalf of a government. Every contributor
> — human or AI — must follow this document. It implements PRD requirements **NFR-SEC-01,
> NFR-SEC-02, NFR-PRV-01, NFR-AUD-01, FR-AUD-01, FR-UAM-04, FR-UAM-06, FR-DSH-01** and the
> OWASP Top 10. When in doubt, choose the more secure option and ask.

---

## 1. Data classification

| Class            | Examples                                                      | Handling |
|------------------|---------------------------------------------------------------|----------|
| **Sensitive PII**| NIN, BVN, full name, DOB, phone, address, household links      | Encrypt at rest; never log; access strictly by role + MDA; consent-tracked. |
| **Operational**  | Programmes, activities, benefits, referrals, grievances        | RBAC-scoped; audit-logged on change. |
| **Secrets**      | DB/Redis/RabbitMQ creds, app keys, TOTP secrets, API keys      | `.env` / secret manager only. Never in code, logs, or git. |
| **Public**       | Aggregate, de-identified dashboard figures                     | May be shown per role scope. |

**Rule:** treat anything that can identify a beneficiary as Sensitive PII by default.

---

## 2. Authentication (FR-UAM-04, FR-UAM-06)

- Token-based auth via **Laravel Sanctum**. No custom crypto.
- **Password policy:** minimum 12 characters, check against a breached-password list, no
  forced periodic rotation (follow modern NIST guidance), securely hashed with **bcrypt/argon2**
  (Laravel default). Never store or log plaintext passwords.
- **Multi-factor authentication (TOTP):** **required** for `System Administrator` and
  `Executive` roles; available (and encouraged) for all others. TOTP secrets are encrypted at rest.
- **Account lockout:** lock after a configurable number of failed attempts (default 5) with
  exponential backoff; record lockouts in the audit log.
- **Session management:** configurable idle timeout (default 30 min) and absolute lifetime;
  invalidate tokens on logout, password change, and role change.
- Generic auth error messages only ("Invalid credentials") — never reveal whether a username exists.

---

## 3. Authorization (FR-UAM-01, FR-UAM-03, FR-UAM-05, FR-DSH-01)

- **RBAC** with predefined roles: Executive, SP Coordination, M&E Officer, MDA Officer,
  MDA Admin, Development Partner, System Administrator.
- Permissions are configurable at **module + action** level (view, create, edit, approve, export).
- **Least privilege** everywhere — deny by default; grant explicitly.
- **MDA data scoping:** every query that touches MDA-owned data must be scoped to the user's MDA
  unless cross-MDA access is explicitly granted and logged. Enforce this centrally (policies +
  global query scopes / middleware), never by ad-hoc `if` checks in controllers.
- **Ownership rules:** only the Owner MDA may edit a beneficiary's core profile; ownership
  transfer requires Owner-MDA approval and is logged.
- Authorization is enforced **server-side**. The frontend hides/show UI for UX only; it is never
  the security boundary.

---

## 4. Data protection (NFR-SEC-01, NFR-PRV-01)

- **In transit:** TLS 1.2+ everywhere. No plaintext HTTP in any environment except localhost dev.
- **At rest:** encrypt the database volume/disk; encrypt sensitive columns (NIN, BVN, TOTP secret)
  at the application layer where feasible.
- **PII minimisation:** collect only what the PRD requires; do not add fields "just in case".
- **Consent:** capture and store consent where required; expose consent status on the record.
- **Retention:** enforce a defined data-retention policy; support deletion/anonymisation flows
  in line with **NDPA/NDPR**.
- **Right of access:** design so a beneficiary's data and benefit history can be exported on request.

---

## 5. Input validation & output handling (OWASP A03)

- Validate **every** inbound payload with Laravel Form Requests / validation rules — types,
  formats, ranges, allowed values. Reject or flag invalid data with the standard error format.
- Use **parameterised queries / Eloquent** only. Never build SQL by string concatenation.
- Validate and sanitise file uploads (type, size, content) for supporting documents; store
  outside the web root; scan where possible.
- Encode/escape all output in the React app; rely on React's default escaping and never use
  `dangerouslySetInnerHTML` with user data.
- Enforce strict **CORS** (allow-list the frontend origin) and sensible security headers
  (HSTS, X-Content-Type-Options, X-Frame-Options/CSP).

---

## 6. Audit logging (FR-AUD-01, NFR-AUD-01)

- Maintain an **immutable, append-only audit log** of every create, edit, delete, access of
  sensitive data, and approval action.
- Each entry records: actor (user + MDA), action, entity + id, **before/after values**,
  timestamp, IP/user-agent, and correlation/request id.
- Audit writes must be **tamper-evident** (e.g. no UPDATE/DELETE permission on the table for the
  app role; consider hash-chaining entries in a later hardening pass).
- **Never** put raw PII or secrets in audit `before/after` for the most sensitive fields — store
  masked/hashed representations where the value itself is not needed for the audit purpose.

---

## 7. Secrets & configuration

- All secrets via environment (`.env` locally, secret manager in production). `.env` is gitignored.
- Commit a documented **`.env.example`** with safe placeholder values only.
- Rotate the Laravel `APP_KEY` and all service credentials per environment; never share prod
  secrets into dev/staging.
- No secrets in logs, error responses, commit history, or the frontend bundle.

---

## 8. Logging & error handling hygiene

- **Never log** PII (NIN, BVN, phone, full name, address) or secrets. Mask before logging.
- Return generic error messages to clients; log details server-side with a correlation id.
- Do not leak stack traces, framework versions, or SQL to clients. `APP_DEBUG=false` outside dev.

---

## 9. Dependencies & supply chain (OWASP A06)

- Pin dependency versions; keep `composer.lock` and `package-lock.json` committed.
- Run `composer audit` and `npm audit` regularly; address high/critical advisories promptly.
- Prefer well-maintained, widely-used packages; justify any new dependency.

---

## 10. OWASP Top 10 — working checklist

For every feature, confirm:

- [ ] **A01 Broken Access Control** — server-side RBAC + MDA scoping enforced and tested.
- [ ] **A02 Cryptographic Failures** — TLS in transit, encryption at rest, strong hashing.
- [ ] **A03 Injection** — parameterised queries, validated input, escaped output.
- [ ] **A04 Insecure Design** — least privilege, ownership/consent rules respected.
- [ ] **A05 Security Misconfiguration** — debug off, headers set, CORS locked, defaults changed.
- [ ] **A06 Vulnerable Components** — deps pinned and audited.
- [ ] **A07 Auth Failures** — MFA, lockout, session timeout, generic errors.
- [ ] **A08 Integrity Failures** — signed/locked deps, audit-log integrity.
- [ ] **A09 Logging/Monitoring Failures** — audit log complete, no PII leakage.
- [ ] **A10 SSRF** — validate/allow-list any outbound URL (sync, integrations).

---

## 11. Environments

- **dev** — local Docker; synthetic/seeded data only. Never copy production PII into dev.
- **staging** — production-like; de-identified or synthetic data.
- **production** — real PII; restricted access, offsite encrypted backups, monitoring, and a
  security review/penetration test **before** go-live.

---

## 12. AI-assistant guardrails (Claude Code)

- Do not weaken any control in this file to make a task easier.
- Do not generate code that logs PII or embeds secrets.
- If a task seems to require a real credential or external access, **stop and ask** — never invent one.
- Flag, don't silently implement, anything that touches consent, retention, or cross-MDA sharing.

---

## 13. Reporting a vulnerability

If you discover a security issue, do **not** open a public issue. Contact the project's
Data Protection Officer / Technical Lead directly (see Approvals in the PRD) with details and
reproduction steps. Treat all reports as confidential until resolved.