# CONVENTIONS.md — Coding Standards & Conventions

> Mandatory for all contributors (human and AI). These exist so the codebase stays consistent,
> reviewable, and traceable to the PRD. If a convention is missing here, follow the framework's
> idiomatic default and propose adding it.

---

## 1. Requirement traceability

- Every commit and PR references the PRD requirement IDs it implements (e.g. `FR-UAM-01`).
- When a behaviour is ambiguous in the PRD, do not guess silently — note the assumption in the
  PR and flag it for me to confirm.

---

## 2. Git & commits

- **Conventional Commits:** `type(scope): summary` — e.g.
  `feat(access): add MFA enrolment endpoint [FR-UAM-04]`.
  Types: `feat`, `fix`, `refactor`, `test`, `docs`, `chore`, `perf`, `build`, `ci`.
- One logical change per commit; keep commits small and runnable.
- Branch naming: `phase-1/access-control`, `phase-2/registry-import`, etc.
- Never commit `.env`, secrets, or generated artefacts. Keep `.gitignore` strict.
- Keep `composer.lock` and `package-lock.json` committed.

---

## 3. Backend — Laravel / PHP

- **PHP 8.3+**, **PSR-12**, `declare(strict_types=1);` in PHP files.
- Run a formatter/linter (Laravel Pint) and static analysis (PHPStan/Larastan) — code must pass.
- **Layering:** `Controller → Form Request → Service → Model`. Controllers are thin; business
  logic lives in domain services; authorization lives in Policies.
- **Validation:** always via Form Requests. Never trust client input.
- **Authorization:** via Policies + Gates; enforce MDA scoping with global query scopes /
  dedicated query objects, not ad-hoc controller checks.
- **Models:** UUID keys for externally-referenced entities; `$fillable` explicit (never
  `$guarded = []`); casts for dates/enums/JSON; soft deletes where the PRD implies recoverability
  (not for the audit log).
- **Enums:** use PHP native enums for fixed sets (roles, statuses, benefit types).
- **No raw SQL string building.** Use Eloquent / the query builder with bindings.
- **Migrations:** one concern per migration; add indexes and foreign keys explicitly; reversible.
- **Jobs:** queued, idempotent, retry-safe; no PII in job payloads beyond IDs.

---

## 4. API conventions

- Base path **`/api/v1`**. Resource-oriented, plural nouns: `/api/v1/beneficiaries`,
  `/api/v1/mdas`, `/api/v1/users`.
- JSON keys are **snake_case** (matches DB and Laravel models).
- **Success envelope:**
  ```json
  { "data": { }, "meta": { } }
  ```
  `meta` carries pagination/context when relevant. Collections return an array in `data`.
- **Error envelope** (use everywhere, including validation):
  ```json
  {
    "error": {
      "code": "VALIDATION_ERROR",
      "message": "The request is invalid.",
      "details": [ { "field": "nin", "message": "NIN must be 11 digits." } ]
    }
  }
  ```
- **HTTP status codes:** 200/201 success, 204 no content, 400 bad request, 401 unauthenticated,
  403 forbidden, 404 not found, 409 conflict (e.g. duplicate), 422 validation, 429 rate limited,
  500 server error.
- **Pagination:** cursor or page-based, consistent across endpoints; expose `meta.pagination`.
- **Filtering/sorting:** documented query params (`?filter[lga]=...&sort=-registration_date`).
- **Every endpoint** declares the permission it requires and is covered by an authorization test.
- **Rate limiting** on auth and write-heavy endpoints. **CORS** locked to the frontend origin.
- Document endpoints (OpenAPI/Swagger or a clear `docs/api/` markdown) as you build.

---

## 5. Frontend — React / TypeScript

- **TypeScript strict mode on.** No `any` without a written justification.
- Function components + hooks only. Keep components small and focused.
- **Feature-folder structure** (see ARCHITECTURE.md). Shared primitives in `components/`.
- **Server state** via TanStack Query (or equivalent) through a single typed API client in
  `lib/api`. No scattered `fetch` calls in components.
- **Types mirror API payloads** (snake_case) in `types/`; convert at the edge if you prefer
  camelCase internally, but do it in one place, consistently.
- **Forms** with a typed form library + schema validation (e.g. React Hook Form + Zod) that
  mirrors backend validation.
- **Auth:** token handling and the MFA flow live in `lib/auth`; protected routes enforce role
  access for UX only (server is the real boundary).
- **Accessibility:** semantic HTML, labelled inputs, keyboard navigation, sufficient contrast
  (NFR-USE-01). Responsive for desktop and mobile browsers.
- Lint + format with ESLint + Prettier; code must pass.

---

## 6. Validation rules (shared expectations)

- **NIN:** 11 digits. **BVN:** 11 digits. **Phone:** valid Nigerian format. Validate on both
  entry and import; reject/flag invalid rows with row-level errors (FR-REG-05/06).
- Required fields per PRD §6/§7; never silently drop or default required data.
- Dates ISO-8601; enums validated against allowed values.

---

## 7. Testing

- **Backend:** Pest/PHPUnit. Cover happy path + failure + permission/scoping cases. Feature tests
  hit real endpoints through the framework. Use factories and seeders, never real PII.
- **Frontend:** component tests (Testing Library) for key screens and flows (login + MFA,
  user/MDA management).
- A feature is not done until its tests pass. CI (when added) runs lint, static analysis, and tests.
- Aim for meaningful coverage of security-critical paths (auth, RBAC, scoping, audit) — these
  must always be tested.

---

## 8. Configuration & secrets

- All config via env; never hard-code environment-specific values.
- Maintain a documented `.env.example` (placeholders only).
- Feature flags / configurable rules (matching thresholds, lockout counts, SLA windows) live in
  config or DB settings — never hard-coded magic numbers. Ask me for the real values.

---

## 9. Logging & errors

- Structured logs with a correlation/request id. **Never log PII or secrets** (see SECURITY.md).
- Client-facing errors use the standard error envelope and reveal nothing sensitive.

---

## 10. Documentation

- Update `README.md` whenever setup or run commands change.
- Keep `ARCHITECTURE.md` current when structure changes.
- Add short module READMEs (`api/app/Domain/<module>/README.md`) describing the module's purpose,
  the requirement IDs it covers, and its main endpoints.

---

## 11. Pull-request / change checklist

Before declaring a task done, confirm:

- [ ] Implements the referenced PRD requirement(s).
- [ ] Follows this file and `SECURITY.md`.
- [ ] Validation, error format, and (where relevant) audit logging in place.
- [ ] MDA scoping and role permissions enforced **and tested**.
- [ ] Tests written and passing; lint + static analysis clean.
- [ ] No secrets/PII committed or logged.
- [ ] Docs/README updated; commits reference requirement IDs.