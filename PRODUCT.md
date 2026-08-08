# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

**Primary — MDA programme officers and MDA administrators.** Staff inside Jigawa
State Ministries, Departments and Agencies who run the day-to-day work: creating
activities against the central programme catalog, bulk-importing beneficiary data
for those activities, adjudicating probable duplicate matches, recording
interventions, raising and answering referrals and request-to-serve, and pulling
reports. They are **non-technical government staff expected to be productive with
minimal training** (NFR-USE-01). They work at **office desktops and laptops on
reliable connectivity** — the app is not a field tool; field collection happens
upstream in Kobo Collect / ODK and arrives by import.

Other confirmed audiences, all lower-volume:

- **Executive users** — Governor, Deputy Governor, Commissioners, Permanent
  Secretaries, Executive Council. **Read-only.** State-wide dashboards, coverage,
  performance, high-level reports. Highest-stakes, lowest-frequency audience:
  they arrive infrequently and must comprehend without training or navigation.
- **SP Coordination Unit** — State SP Coordination Office and M&E officers.
  Cross-MDA coordination, programme catalog and matching-rule maintenance,
  performance monitoring, M&E and reporting.
- **Development partners** — funding and implementing partners, scoped to their
  own programmes: budget utilization, performance, reports.
- **System administrators** — platform/IT. Users, MDAs, programme catalog and
  matching rules, data synchronization, system health.

Every user's view is bounded by role-based access control and MDA scope; two
people on the same screen may legitimately see different data.

## Product Purpose

SP-MIS is the single environment in which every social protection programme in
Jigawa State is coordinated, delivered, and reported on. Today each MDA runs its
own register and its own tools, so the same person is enrolled many times, no
agency can see who is already being served, and there is no reliable way to
refer, coordinate, or report across programmes. The result is duplicated spend
and weak oversight.

SP-MIS replaces that with shared data under clear ownership: MDAs deliver
programmes through their own activities, register beneficiaries, track every
benefit, coordinate referrals, and generate evidence — while each MDA keeps
ownership of the records it originated.

Success means: duplicate registration is eliminated at the point of entry, every
benefit a beneficiary has received is traceable to who delivered it, MDAs
collaborate without ownership disputes, and leadership has a real-time state-wide
view instead of manually assembled reports.

## Positioning

Three mechanisms define the product, and a neighboring beneficiary database could
not truthfully claim them together:

1. **Hybrid registry with provenance.** Data enters from many sources — SOCU,
   Kobo Collect, ODK, Excel/CSV, APIs, existing government systems — never from a
   manual single-record form. Every record carries Registration Source, Owner
   MDA, Registration Date, Import Batch, and Original Record ID, so lineage is
   always traceable. Supports both individual- and household-based programmes.
2. **Ownership without turf conflict.** The first MDA to register a beneficiary
   owns the core profile and is the only party that may edit it. Another MDA that
   wants to serve that person submits a **request-to-serve** to the Owner MDA and
   may only record an intervention once it is accepted; acceptance also grants
   read access to the full profile and benefit history. Every request, acceptance
   and decline is audited. Nothing is ever silently auto-merged.
3. **Verification before save.** Duplicate checking runs *before* a record is
   written, not as later cleanup. This is what operationally enforces "register
   once, serve many times."

## Operating Context

- **Activity-first upload.** There is no manual single-beneficiary creation path.
  An MDA creates an activity (selecting a programme from the central catalog),
  then uploads beneficiary data in the context of that activity. Each resolved
  row becomes an intervention recorded under that activity in the benefit ledger.
- **The duplicate cascade is the core decision moment.** Default configuration
  evaluates exact NIN → exact BVN → fuzzy name/phone, stopping at the first
  deterministic match. Exact identifier matches are definitive and not
  adjudicated; only fuzzy-band probable matches are put to the officer. In every
  match case the officer still chooses what happens next — discard the incoming
  row, or serve the existing beneficiary via request-to-serve. Field
  participation, order, weights and thresholds are all admin-configurable, not
  hard-coded.
- **Work arrives in batches, not single records.** The characteristic session is
  an import of many rows followed by resolution of the flagged subset — long
  tables, bulk state, and per-row decisions, not one-at-a-time form filling.
- **Two-sided workflows.** Referrals and request-to-serve each have an inbound
  and an outbound side with distinct queues and outcomes; a referral and a
  request-to-serve are different objects and must not be conflated in language or
  UI.
- **Everything data-changing is audited.** Security-relevant and data-changing
  actions must be auditable and tamper-evident (NFR-AUD-01).
- **Deployment.** Containerized (Docker Compose); Laravel 12 REST API with a
  React + TypeScript SPA, PostgreSQL 16 + PostGIS, Redis, RabbitMQ.

## Capabilities and Constraints

Confirmed in-scope modules: User & Access Management · Beneficiary Registry
(hybrid) · Duplicate Verification · Beneficiary Ownership · Referral & Linkage ·
Benefit Tracking (ledger) · Programme Catalog (central) · Activity Management
(MDA-owned) · Graduation Management · Grievance Redress (GRM) · Notifications ·
Reports & Analytics · Executive / MDA / Partner Dashboards · GIS Dashboard ·
Audit Logs · Document Management · Data Sharing · Data Synchronization. Household
Registry is optional, for programmes that operate at household level.

Explicit non-goals — future work must not imply these exist:

- **Not a payment or disbursement engine.** It records benefits delivered; money
  movement stays in existing financial channels.
- **Not a national identity system.** It consumes NIN and BVN for matching; it is
  not the system of record for identity.
- **Does not replace MDAs' specialized operational systems.** It coordinates and
  consolidates.
- Public/citizen self-service portals, mobile money integration, and predictive
  analytics are deferred to later phases.
- MDAs do **not** create or configure programmes — the catalog is centrally owned
  by System Administrators and the SP Coordination Unit.

Constraints that bind design:

- **Language: English only.** Confirmed. No text-expansion or multi-script
  allowance is required.
- **Responsive across desktop and mobile browsers** is a stated requirement
  (NFR-USE-01) even though the primary scene is desktop.
- Standard pages within 3s; duplicate verification results within 5s (proposed
  baselines, NFR-PERF-01). Scale target: millions of beneficiary records, 500+
  concurrent users (proposed).
- PII-heavy under NDPA/NDPR: minimize PII on screen, capture consent where
  required, honor the data-retention policy. Privileged and executive accounts
  require MFA.

**Terminology — use these exactly.** Programme = a shared, centrally-created
service type, owned by no MDA. Activity = an MDA-owned unit of work delivering a
catalog programme, with its own budget, funding, schedule and location.
Intervention = a service recorded in the benefit ledger. Owner MDA = first
registrant, sole editor. Request-to-Serve (Service Request) = a non-owner MDA
asking the Owner MDA for approval to deliver — **distinct from a Referral**.
MDA · LGA / Ward · GRM · NIN / BVN · SOCU.

## Brand Commitments

- **Name:** Jigawa State SP-MIS (State Social Protection Management Information
  System).
- **A binding brand guide already exists** — *Jigawa Social-Protection MIS Design
  System V1.0* — and its foundations (color, type, spacing, radius, elevation)
  and primitives are recorded as non-negotiable in
  [DESIGN.md](DESIGN.md), which is the project's single
  source of visual truth. Tokens marked `[inferred]` in that file were derived
  rather than specified and are still pending confirmation by the design owner.
- The brand's **marketing** components (hero banners, testimonials, demo CTAs)
  are explicitly not used; only its tokens carry over. SP-MIS is a dense, form-
  and table-heavy government application, not a marketing site.
- Implemented as a shared component library under
  [web/src/components/](web/src/components/) with a single token stylesheet at
  [web/src/styles/theme.css](web/src/styles/theme.css). Existing primitives are
  extended, never re-rolled.

## Evidence on Hand

- **Pre-launch. Seed data only.** Every MDA, programme, activity, beneficiary and
  coverage figure currently in the system is a fixture. There are no real users,
  no real PII, and no genuine reporting numbers yet.
- **Future work must not fabricate** beneficiary counts, coverage percentages,
  budget-utilization figures, MDA names, testimonials, or go-live claims, and
  must not present seeded values as real state data.
- Real, usable assets that do exist: the full product requirements document
  ([docs/jigawa-SP-MIS.md](docs/jigawa-SP-MIS.md), v1.3), the design system
  ([DESIGN.md](DESIGN.md)), a completed WCAG 2.1 AA audit
  ([docs/ACCESSIBILITY.md](docs/ACCESSIBILITY.md)), architecture and conventions
  docs, and a built component library and route set.

## Product Principles

1. **Ownership is visible, always.** Who owns a record, who may edit it, and what
   the current user is permitted to do must be legible on screen — never
   discovered by hitting a permission error.
2. **Prevent the duplicate, don't clean it up later.** The verification moment is
   the product's core value. It gets the clearest, most deliberate treatment in
   the interface, and its outcomes are never silent or automatic.
3. **Provenance travels with the record.** Source, owner, batch, and original ID
   are part of what a beneficiary *is*. Surfacing where data came from is
   routine, not an audit-only detail.
4. **Built for batches and repetition.** The default unit of work is many rows,
   not one. Scanability, bulk state, and per-row decisions outrank expressive
   flourish everywhere in the authenticated app.
5. **Evidence over impression.** This system exists to make numbers trustworthy.
   Never show a figure that implies more certainty, coverage, or real-world
   activity than the underlying data supports.

## Accessibility & Inclusion

**WCAG 2.1 Level AA is the required standard**, tied to NFR-USE-01. A full audit
has been completed and its fixes applied — see
[docs/ACCESSIBILITY.md](docs/ACCESSIBILITY.md), which also tracks the items still
needing manual assistive-technology sign-off before go-live. The app is built
almost entirely from shared primitives, so component-level compliance propagates
to every flow; this makes the shared component library the right place to fix
accessibility, and regressions there are systemic.

Additional confirmed needs: non-technical government staff must succeed with
minimal training, and the interface must remain operable on desktop and mobile
browsers.
