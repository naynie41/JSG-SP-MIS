# Access domain

Authentication, authorization (RBAC), MFA, account security, and MDA data-scoping.

**Implements:** FR-UAM-01 (roles), FR-UAM-02 (user/MDA management), FR-UAM-03 (MDA scoping),
FR-UAM-04 (MFA), FR-UAM-05 (module+action permissions), FR-UAM-06 (lockout), FR-DSH-01
(cross-MDA sharing). Security rules per `docs/SECURITY.md`.

## Building blocks

| Area | Where |
| --- | --- |
| Models | `Models/` — `User`, `Mda`, `Role`, `Permission`, `MdaAccessGrant` (UUID PKs) |
| Enums | `Enums/` — `RoleKey`, `PermissionAction`, `UserStatus`, `MdaType`, `MdaStatus` |
| Auth services | `Services/AuthTokenIssuer` (issues full/MFA-challenge/MFA-setup tokens, audits `auth.login`), `Services/MfaService` (TOTP + recovery codes) |
| RBAC | `Support/PermissionRegistry` (modules register permissions), `Services/PermissionSynchronizer` (registry → DB), `AccessServiceProvider` (registers permissions + the `Gate::before` bridge) |
| Scoping | `Concerns/ScopedToMda` (+ `MdaScoped` interface) applies `Scopes/MdaScope` |
| Password policy | `Support/PasswordRules` — min 12 + breached-password (HIBP) check |
| Events (audit hooks) | `Events/` — `AccountLocked`, `MfaEnrolled`, `MfaDisabled`, `MfaChallengeFailed`, `CrossMdaAccessGranted`, `CrossMdaAccessRevoked` |

## Authentication (Sanctum bearer tokens)

- `POST /api/v1/auth/login` → full token, **or** `mfa_required` (challenge), **or**
  `mfa_setup_required` (role forces enrolment). Generic `INVALID_CREDENTIALS` for all failures
  (no account enumeration).
- `POST /api/v1/auth/mfa/challenge` (TOTP or one-time recovery code) → full token.
- `POST /api/v1/auth/mfa/{enroll,verify}` (setup token) and `/mfa/disable` (full token).
- `GET /api/v1/auth/me`, `POST /api/v1/auth/logout`, `POST /api/v1/auth/password`.
- **MFA is mandatory** for `System Administrator` and `Executive` (data-driven via
  `roles.requires_mfa`). Secrets/recovery codes are encrypted at rest.
- **Lockout (FR-UAM-06):** `User::registerFailedAttempt()` locks after
  `config('security.lockout.max_attempts')` (default 5) with exponential backoff; locked accounts
  get `ACCOUNT_LOCKED` (423) + `Retry-After`. Login is also rate-limited (`throttle:login`).
- Token lifetimes: idle timeout (`EnforceIdleTimeout` middleware) + absolute lifetime (Sanctum
  `expiration`), both in `config/security.php`.

## Authorization (RBAC, deny-by-default)

- Permissions are `module.action` strings. Code authorizes on **permissions, never role names**.
- Enforce on routes with the `permission` middleware: `->middleware('permission:user.create')`.
  `Gate::before` also resolves any `module.action` ability so `$user->can('user.view')` works.
- Adding a permission: register it in a module's service provider
  (`$registry->register('module', PermissionAction::View, '…')`), then `php artisan permissions:sync`
  (the seeder does this). Assign it to roles in `RolesAndPermissionsSeeder`.

## MDA data-scoping (FR-UAM-03, FR-DSH-01)

- A model becomes MDA-scoped by `use ScopedToMda` (and `implements MdaScoped`). Default ownership
  column is `owner_mda_id`; override `mdaOwnershipColumn()` (`User` → `mda_id`, `Mda` → `id`).
- The global `MdaScope` restricts every query to the caller's own MDA **plus active cross-MDA
  grants**, unless the user holds `cross-mda.view` (oversight bypass). Enforced centrally — never
  re-implement scoping in controllers.
- Cross-MDA access is admin-managed via `POST /api/v1/mda-access-grants` (permission
  `mda-access.create`) and audited.

## Admin management (FR-UAM-02)

`/api/v1/mdas` and `/api/v1/users` (controllers under `Http/Controllers/Api/V1/Access/`): scoped
list/show, create, edit, activate/deactivate, plus user suspend, force-password-reset and reset-mfa.
User create/edit prevent privilege escalation (an actor can only assign roles whose permissions are
a subset of their own) and can only place users in MDAs they can access.

## Tests

`api/tests/Feature/{Auth,Access}/…` — auth, MFA, lockout, RBAC, scoping, and MDA/user management.
