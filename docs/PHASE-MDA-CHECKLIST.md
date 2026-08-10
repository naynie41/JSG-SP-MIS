# MDA console — completion checklist

The restructure of the MDA experience into six task-based modules behind one
permission-gated navigation. Every line below is backed by a test named beside it.

Module detail: [web/src/features/mda/README.md](../web/src/features/mda/README.md).

---

## 1. One navigation, two roles

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | Six modules — Overview, Programmes, Beneficiaries, Service Delivery, Duplicate Resolution, Reports | `MdaConsole.test.tsx` · `MdaGating.test.tsx` |
| ✅ | Officer and Admin get the **identical** rail; items gate on permission, never on role | `MdaGating.test.tsx` |
| ✅ | Officer permissions are a strict **subset** of Admin's — the premise of one rail | `MdaRoleMatrixTest` |
| ✅ | The Admin-only difference is exactly six permissions, pinned against seeder drift | `MdaRoleMatrixTest` |
| ✅ | Settings is a header affordance, never a rail item | `MdaGating.test.tsx` |
| ✅ | A module the user cannot reach disappears rather than showing a dead link | `MdaConsole.test.tsx` |
| ✅ | The console is closed to non-MDA roles; the generic operator rail is closed to MDA roles | `MdaConsole.test.tsx` |
| ✅ | Every module MDA-scoped server-side (`MdaScope` + resolved `DashboardScope`) | per-module tests below |

## 2. Compose, never rebuild

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | Each module imports from the feature it composes | `MdaComposition.test.ts` |
| ✅ | No module touches the HTTP layer directly (no `apiRequest`, axios or `fetch`) | `MdaComposition.test.ts` |
| ✅ | The console defines exactly one api module of its own — the action-required counters | `MdaComposition.test.ts` |
| ✅ | No module is still a scaffold; `mdaScaffolds.tsx` is deleted | `MdaComposition.test.ts` |
| ✅ | Behavioural reuse: each module's tests mock the **source** api and watch it get called | per-module tests |
| ✅ | The report builder/schedules/runs are shared with the admin console, not copied | `ReportPanels.tsx`, `MdaReports.test.tsx` |

## 3. One flow, multiple entry points

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | Create Activity opens the same `ActivityFormModal` from Quick Actions and from a programme | `MdaProgrammes.test.tsx` |
| ✅ | Both upload doors stage identical rows from the same file — one parser | `OnePipelineTest` |
| ✅ | Both reach the same `preview_ready` state | `OnePipelineTest` |
| ✅ | Both screen against the same registry into the same bands — one matcher | `OnePipelineTest` |
| ✅ | Both commit through the same `ImportCommitter` to the same terminal state | `OnePipelineTest` |
| ✅ | A record carries the same provenance whichever door it came through | `OnePipelineTest` |
| ✅ | The one legitimate difference — the wizard creates its activity on confirm — is asserted | `OnePipelineTest` |

## 4. Module → source phase

| # | Module | Composes | Phase |
| --- | --- | --- | --- |
| 1 | Overview | `/dashboard` aggregation · `/mda/action-required` · notification feed | 6 + 5 |
| 2 | Programmes | `useProgrammes(participating)` · `ActivityFormModal` | 4 |
| 3 | Beneficiaries | `BeneficiaryListPage` · `HouseholdListPage` · `ImportListPage` | 2 + 3 |
| 4 | Service Delivery | `RecordBenefitPage` · ledger tabs · `BenefitsPanel` · `ReferralTable` · `ServiceRequestsPage` | 4 + 5 + 2/3 |
| 5 | Duplicate Resolution | `ResolveRowControls` · match evidence components · `DuplicateSearchPage` | 3 |
| 6 | Reports | `ReportPanels` over the ad-hoc engine · beneficiary export | 6 |
| — | Settings (gear) | `/auth/me` · `/auth/password` · `/auth/mfa/disable` · `/notifications/preferences` | 1 + 5 |
| — | Notifications (bell) | Phase 5 `Notifier`, role-aware deep-links | 5 |

## 5. Domain rules held

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | **No manual beneficiary creation** — no route, no `store`, direct POST is 405 | `MdaBeneficiaryModuleTest` · `NoManualCreateRouteTest` |
| ✅ | No create affordance anywhere in the module, and it says why | `MdaBeneficiaries.test.tsx` |
| ✅ | **Adjudication only on probable matches**; the control is absent on exact, not disabled | `MdaDuplicateResolution.test.tsx` |
| ✅ | An exact match is refused server-side with `ADJUDICATION_NOT_ALLOWED` | `ImportResolutionTest` |
| ✅ | Discard-or-serve remains available at **every** band | `ImportResolutionTest` |
| ✅ | "Not the same person" requires a justification, recorded | `ImportResolutionTest` · `MdaDuplicateResolution.test.tsx` |
| ✅ | Ownership never transfers — accepted request-to-serve and completed referral both leave `owner_mda_id` | `MdaServiceDeliveryModuleTest` |
| ✅ | Programmes stay global and unowned; no create/edit affordance | `MdaProgrammes.test.tsx` |
| ✅ | Delivery value is programme data, never expenditure | `MdaServiceDelivery.test.tsx` |

## 6. Export matrix (SECURITY.md §3)

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | Aggregate reports ride `reporting.export`; both roles hold it | `MdaReportsModuleTest` · `MdaRoleMatrixTest` |
| ✅ | Bulk beneficiary export rides `beneficiary.export` — Admin yes, Officer **denied** | `MdaReportsModuleTest` · `MdaRoleMatrixTest` |
| ✅ | Granting the Officer role export works, and does **not** widen scope | `MdaRoleMatrixTest` |
| ✅ | NIN/BVN masked without `export.reveal_pii`; no MDA role can hold it | `MdaReportsModuleTest` · `MdaGating.test.tsx` |
| ✅ | No identifier column is selectable in an aggregate report at all | `MdaReportsModuleTest` |
| ✅ | Every export audited with actor, scope, filters, format, row count, reveal flag | `MdaReportsModuleTest` |
| ✅ | The audit payload is not itself a PII sink | `MdaReportsModuleTest` |
| ✅ | An MDA cannot widen scope with a filter | `MdaReportsModuleTest` |
| ✅ | All six report types reachable and MDA-scoped | `MdaReportsModuleTest` |

## 7. Action-required reconciles with Overview

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | Counters are live and **directional** (inbound only), not the 15-minute snapshot | `MdaActionRequiredTest` |
| ✅ | Counts only — no beneficiary data on the Overview | `MdaActionRequiredTest` |
| ✅ | Deciding through the real API clears the counter | `MdaServiceDeliveryModuleTest` |
| ✅ | The counter equals the rows the module's Pending view lists | `MdaServiceDeliveryModuleTest` · `MdaConsoleDemoSeederTest` |
| ✅ | Decision mutations invalidate the counter's query key, so both update together | `registry/hooks.ts`, `referrals/hooks.ts` |
| ✅ | One count per figure — the embedded queue defers to the host's headline | `ServiceRequestsPage.tsx` |
| ✅ | Received referrals and incoming approvals are visually distinct from records | `MdaServiceDelivery.test.tsx` |

## 8. Notifications

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | Bell surfaces referrals, approvals, duplicate alerts, import results, announcements | `notificationRouting.test.tsx` · `ImportNotificationTest` |
| ✅ | Duplicate alerts and import results **added** — the pipeline emitted no events before | `ImportNotificationTest` |
| ✅ | One notification per batch, not per row | `ImportNotificationTest` |
| ✅ | Deep-links are role-aware — MDA users land in their modules | `notificationRouting.test.tsx` |
| ✅ | Same batch, different event → different module | `notificationRouting.test.tsx` |
| ✅ | MDA-scoped: never reaches another MDA; unattributed batches stay inside the owner | `ImportNotificationTest` |
| ✅ | Counts only — no identity data, since these also go by email | `ImportNotificationTest` |

## 9. Settings

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | Profile, Preferences, Security — behind the header gear | `MdaSettings.test.tsx` |
| ✅ | Each panel composes an existing capability; no settings store | `MdaComposition.test.ts` |
| ✅ | Password change uses `/auth/password` and ends the session, as the server does | `MdaSettings.test.tsx` |
| ✅ | MFA disable offered only when the role permits it | `MdaSettings.test.tsx` |
| ✅ | Nothing here writes MDA or platform configuration | `MdaSettings.test.tsx` |

## 10. Demo data

| ✔ | Criterion | Evidence |
| --- | --- | --- |
| ✅ | All six modules render for **both** roles from a fresh seed | `MdaConsoleDemoSeederTest` |
| ✅ | Activities that do **and** do not register beneficiaries | `MdaConsoleDemoSeederTest` |
| ✅ | Request-to-serve and referrals in both directions | `MdaConsoleDemoSeederTest` |
| ✅ | A duplicate case with exact + probable rows, decided and undecided | `MdaConsoleDemoSeederTest` |
| ✅ | Every seeded band/resolution is a real domain value | `MdaConsoleDemoSeederTest` |
| ✅ | A flagged row's candidate resolves to a real record, so evidence renders | `MdaConsoleDemoSeederTest` |
| ✅ | Synthetic only — no identifier-shaped data anywhere | `MdaConsoleDemoSeederTest` |
| ✅ | Idempotent; skipped in production | `MdaConsoleDemoSeederTest` |

---

## Known gaps

Recorded rather than papered over.

- **Per-user permission grants do not exist.** `docs/SECURITY.md` §3 says
  `beneficiary.export` "may be granted per user"; permissions resolve from
  `role_permission` only, so in practice an administrator grants it to the *MDA Officer
  role* through the permission-matrix editor. Documented in `MdaRoleMatrixTest`.
- **`export.reveal_pii` masking is asserted, not exercised end-to-end.** It is in
  `NEVER_ROLE_GRANTABLE`, so no MDA role can hold it and there is no legitimate way to
  construct a revealing MDA export. Tests assert masking holds and that neither role
  carries the permission.
- **Self-service profile editing has no endpoint.** `/auth/me` is a GET and
  `PATCH /users/{user}` is an administrator capability, so Profile is read-only and says
  who can change it.
- **First-time MFA enrolment is unreachable from Settings.** It runs behind a short-lived
  setup token on the login flow; a signed-in session cannot start it.
- **`duplicates` was reclassified** from governance-only to additionally MDA-scopable so
  an MDA can report on its own rows. Executive and partner access is unchanged. Reversible
  by dropping `mda_scopable` from the dataset.
- **Duplicate reporting is an N+1 across the current page of batches.** There is no
  cross-batch rows endpoint; the fix, if volumes grow, is a
  `GET /beneficiaries/duplicates` projection rather than a client cache.
