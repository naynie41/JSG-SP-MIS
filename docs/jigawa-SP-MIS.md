# PRODUCT REQUIREMENTS DOCUMENT
## State Social Protection Management Information System (SP-MIS)

*A unified platform for coordinating, monitoring, and managing social protection programmes across MDAs and partners*

**Version 1.8 · Draft for Review**
**Prepared by:** Project Team
**Date:** July 2026

---

## Document Control

### Version History

| Version | Date | Author | Summary of Changes |
|---------|------|--------|--------------------|
| 1.0 | June 2026 | Project Team | Initial draft. |
| 1.1 | June 2026 | Project Team | Restructured into a full PRD: added goals/non-goals, requirement IDs and priorities, non-functional requirements, data model, user flows, success targets, risks, and a phased roadmap. |
| 1.2 | July 2026 | Project Team | Duplicate-matching default cascade and adjudication rules made explicit; identity-field validation tightened to row-level rejection; request-to-serve gated by Owner-MDA approval with cross-MDA read access on acceptance; activity-first upload flow introduced. See Change Log (v1.2). |
| 1.3 | July 2026 | Project Team | Programme model revised: programmes become a global, centrally-managed catalog (System Administrator / SP Coordination) rather than MDA-owned. MDAs no longer create programmes; they create MDA-owned activities that select a catalog programme. Budget and funding source moved from Programme to Activity. One programme may be delivered by multiple MDAs through separate activities. Beneficiary ownership model unchanged. See Change Log (v1.3). Revises FR-PRG-01, FR-PRG-02; adds FR-PRG-06. |
| 1.4 | July 2026 | Project Team | Activity creation may include an optional inline beneficiary-upload step: when a file is provided, validation and duplicate verification run in preview before the activity is saved; on confirm the activity saves with new beneficiaries under it and a pending Service Request for each served duplicate (intervention deferred until approval). Beneficiary upload is optional per activity. See Change Log (v1.4). Clarifies FR-REG-10, FR-PRG-05, §8.1; adds FR-REG-11. |
| 1.5 | July 2026 | Project Team | Activity beneficiary-involvement made conditional (an activity declares whether it involves beneficiaries; if yes, a target count and beneficiary upload are required; if no, none) with a View Activity detail view. Export of beneficiary data governed by a permission matrix (distinct `export` + `export.reveal_pii`). Activities may be attributed to a funding partner for reporting (reporting-visibility only). Executive & Partner reporting suites and the System Administrator Console described. See Change Log (v1.5). Revises FR-REG-11, §6.4, §8.1; adds FR-PRG-07, FR-RPT-05/06/07/08, FR-UAM-07. |
| 1.6 | July 2026 | Project Team | **MDA Officer role removed.** Consolidated into a single MDA role (MDA Admin) that performs all MDA operational work. All user management is centralized with the System Administrator (MDAs do not manage users). See Change Log (v1.6). Revises FR-UAM-01, §4, FR-RPT-05, §8 flows. |
| 1.7 | July 2026 | Project Team | **Data Import & Mapping** module added (canonical schema, column mapping, learnable per-source templates, value normalization, mapping/validation preview) with a hard rule that identity-field mappings (NIN/BVN/name/phone) are confirmed every import. **Grant revocation** added: cross-MDA read access granted on request-to-serve acceptance is revocable by the Owner MDA. See Change Log (v1.7). Adds §6.5, FR-REG-12..17, FR-OWN-08; revises §8.1. |
| 1.8 | July 2026 | Project Team | **Provenance** (SOCU vs self-sourced; per-record SOCU/source ID; source distinct from owner). **Concrete field-format validation** (NIN/BVN = 11 digits, etc.). **Self-owned re-upload** blocks the duplicate but allows a new intervention. **Multi-LGA/multi-ward activity locations** (wards optional per LGA; descriptive) on **LGA/Ward reference-data lookups**. **Request-to-serve email** to the owner MDA (no PII in body). **Filtered report builder** (schema-driven filters, export-matrix-scoped, cell-size guard) + **Overview dashboard summary**. **Sync-connector** identity mapping confirmed at config time. See Change Log (v1.8). Adds FR-REG-18..20, FR-DUP-10, FR-PRG-08, FR-NOT-03, FR-RPT-09/10; revises FR-REG-05, FR-PRG-02, §9. |

### Approvals

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Sponsor | | | |
| SP Coordination Lead | | | |
| Technical Lead | | | |
| Data Protection Officer | | | |

**Status legend (priorities use MoSCoW):** Must = required for launch, Should = important but not launch-blocking, Could = desirable, Won't (this release) = explicitly deferred.
Markers: ▸ = v1.2 · ◆ = v1.3 · ✦ = v1.4 · ✚ = v1.5 · ✱ = v1.6 · ✜ = v1.7 · ❖ = v1.8.

---

## Change Log (v1.2)

| # | Change | Affected requirements |
|---|--------|-----------------------|
| 1 | Default duplicate-matching cascade made explicit: exact NIN → exact BVN → fuzzy name/phone. Evaluation stops at the first exact match; where an identifier is absent, the record falls through to the next stage. Remains fully configurable (not hard-coded). | §6.3, FR-DUP-03, FR-DUP-08 (new) |
| 2 | Adjudication only at the probable band. The officer decides whether a candidate is the same person only for fuzzy (probable) matches. An exact NIN/BVN match is a definitive duplicate and is not adjudicated — but the discard-or-serve choice still applies at every band. | §6.3, FR-DUP-09 (new) |
| 3 | Identity-field validation tightened. A row whose name, phone, NIN, or BVN is present but malformed is rejected in whole to the error report and not saved. Non-identity fields that fail validation are dropped/flagged for that row while the row still saves. (Absent optional NIN/BVN remains valid.) | FR-REG-05 (revised), FR-REG-09 (new) |
| 4 | Request-to-serve now requires Owner-MDA approval. A non-owner MDA that wants to deliver an intervention to a matched beneficiary must send a service request; the Owner MDA accepts or declines. On acceptance, the requesting MDA may record the intervention and gains read access to the full beneficiary record; ownership is unchanged. | FR-DUP-05 (revised), FR-OWN-03, FR-OWN-06 (new), FR-OWN-07 (new) |
| 5 | Activity-first upload. An MDA must register an activity before uploading beneficiary data; each upload is performed in the context of that activity, and the resulting intervention is recorded under it. | FR-REG-10 (new), FR-PRG-05 (new), §8.1 |

---

## Change Log (v1.3)

| # | Change | Affected requirements |
|---|--------|-----------------------|
| 1 | **Programmes are a global catalog, centrally created.** The System Administrator (optionally SP Coordination) creates and maintains programmes as shared service types (e.g. Cash Transfer, Skills Training). Programmes are not owned by any MDA and are readable by all MDAs for selection. MDAs can no longer create or edit programmes. | §4, §7.7, §9; FR-PRG-01 (revised), FR-PRG-06 (new) |
| 2 | **Activities are MDA-owned and select a catalog programme.** An MDA creates and owns activities under a chosen catalog programme, capturing target, location, schedule, budget, funding source, period, and eligibility. A single programme may be delivered by multiple MDAs through their own separate activities. | §7.7, §9; FR-PRG-02 (revised) |
| 3 | **Budget and funding source move to the Activity.** Programme-level budget is now an aggregate of its activities' budgets across MDAs; each MDA funds its own activity. | §7.7, §9; FR-PRG-02, FR-PRG-04 (clarified) |
| 4 | **Beneficiary data ownership is unchanged.** First-importer ownership (FR-OWN-01) and MDA data isolation remain exactly as in v1.2. Only *programme* ownership changed. | FR-OWN-01 (unchanged, noted) |

---

## Change Log (v1.4)

| # | Change | Affected requirements |
|---|--------|-----------------------|
| 1 | **Optional inline beneficiary upload in activity creation.** Activity creation may end with an optional beneficiary-upload step. When a file is provided, validation and duplicate verification run in preview *before* the activity is saved; on confirm, the activity is saved with new beneficiaries recorded under it and a **pending Service Request** created (under the activity) for each duplicate the officer chooses to serve — the intervention is deferred until Owner-MDA approval. | §6.4, §8.1; FR-REG-10 (clarified), FR-REG-11 (new), FR-PRG-05 (clarified) |
| 2 | **Beneficiary upload is optional per activity.** Not every activity has beneficiary data; an activity may be created with no upload, and data may be uploaded later via the Import Center (bound to that activity). | §6.4; FR-REG-10, FR-REG-11 |

---

## Change Log (v1.5)

| # | Change | Affected requirements |
|---|--------|-----------------------|
| 1 | **Conditional beneficiary involvement per activity.** Activity creation asks whether the activity involves beneficiaries. If yes, a target-beneficiaries count and a beneficiary upload are **required** (not optional), dedup runs in preview before save, and pending Service Requests attach for served duplicates. If no, no target and no upload. Every activity has a **View Activity** detail view. | §6.4, §8.1; FR-REG-11 (revised), FR-PRG-05 (clarified) |
| 2 | **Export of beneficiary data governed by a permission matrix.** Distinct `export` permission (System Admin all; SP Coordination cross-MDA; MDA Admin own MDA; MDA Officer only if granted; Partners/Executives aggregates only) + a separate `export.reveal_pii` for unmasked NIN/BVN (System Admin only). Exports inherit scope, mask PII by default, audited. Per-grid "export this list" reuses the export service. | FR-RPT-05 (new), FR-RPT-06 (new); NFR-SEC/PRV |
| 3 | **Funding-partner attribution on activities (reporting).** An activity may be attributed to a Development Partner (on the activity, never the programme) so partner reporting scopes to funded programmes. Attribution is reporting-visibility only — never beneficiary-data access. | §9; FR-PRG-07 (new), FR-RPT-02 |
| 4 | **Executive & Partner reporting suites; System Administrator Console.** Executive/partner dashboards elaborated into multi-tab suites (headline = net unique beneficiaries, never gross; coverage absolute where no denominator; partner funding = delivery value vs budget, not expenditure). A System Administrator Console composes existing modules for governance/config/oversight (no infrastructure or delivery operations). | FR-RPT-01/02 (elaborated), FR-RPT-07 (new), FR-RPT-08 (new), FR-UAM-07 (new) |

---

## Change Log (v1.6)

| # | Change | Affected requirements |
|---|--------|-----------------------|
| 1 | **MDA Officer role removed.** The MDA Officer and MDA Admin roles are consolidated into a single **MDA Admin** role that performs all MDA operational work (activity creation, import, benefit delivery, referrals, request-to-serve including approving incoming ones, ownership decisions, and MDA-scoped export). | §4; FR-UAM-01 (revised), FR-RPT-05 (revised), §8 flows |
| 2 | **User management centralized.** All user management (creating/assigning any role, including MDA users) is performed by the System Administrator; MDAs do not manage users. | §4; FR-UAM-02, FR-UAM-07 |

---

## Change Log (v1.7)

| # | Change | Affected requirements |
|---|--------|-----------------------|
| 1 | **Data Import & Mapping module.** A layer between raw MDA upload and validation/dedup: MDAs map their own columns to a canonical SP-MIS schema, values are normalized (for comparison, preserving originals), and a preview runs before commit. Mappings are learnable per source (templates). **Identity-field mappings (NIN, BVN, name, phone) must be explicitly confirmed on every import** — never silently guessed; the raw file is never mutated. Feeds the existing validation (FR-REG-05/09) and duplicate cascade (FR-DUP-08) unchanged. | §6.5 (new); FR-REG-12..17 (new); §8.1 (revised) |
| 2 | **Grant revocation.** Cross-MDA read access granted on request-to-serve acceptance (FR-OWN-07) is revocable by the Owner MDA (System Administrator override). Revocation is immediate, audited, and notified; it withdraws ongoing read access only — it does not delete recorded interventions or change ownership. | §8.4 (revised); FR-OWN-08 (new) |

---

## Change Log (v1.8)

| # | Change | Affected requirements |
|---|--------|-----------------------|
| 1 | **Data provenance (source ≠ owner).** An upload is tagged SOCU (mined) or self-sourced; SOCU records carry a per-record SOCU/source ID. Source is stored separately from owner — ownership remains first-importer (FR-OWN-01); SOCU provenance never changes ownership. | FR-REG-18 (new); §9 |
| 2 | **Concrete field-format validation.** Identity formats are explicit and configurable (NIN/BVN = exactly 11 numeric digits; phone = valid Nigerian number; real non-future dates). Malformed identity fields reject the row (FR-REG-05) with a specific reason. | FR-REG-19 (new); FR-REG-05 (sharpened) |
| 3 | **Self-owned re-upload.** When an MDA re-uploads a beneficiary it already owns, the system blocks the duplicate record and raises no request-to-serve, but allows a new intervention on the existing beneficiary. | FR-DUP-10 (new) |
| 4 | **Multi-LGA / multi-ward activity locations.** An activity targets one or more LGAs, with zero or more wards per LGA (no wards = whole LGA); descriptive for planning/coverage, not enforced against beneficiary locations. Backed by LGA/Ward reference-data lookups. | FR-PRG-08 (new); FR-PRG-02 (revised); §9 |
| 5 | **Request-to-serve email.** The owner MDA is emailed (action-required) when a request-to-serve is raised; the email carries no beneficiary PII and links to the in-app request. | FR-NOT-03 (new); FR-NOT-02 |
| 6 | **Filtered report builder + Overview summary.** A self-service report builder filters on segmentable canonical fields (identity fields excluded from filtering, masked in output), returns a table + export + optional chart, enforces the export matrix + scoping, and applies a minimum cell-size guard on aggregate/cross-MDA tiers. An Overview summary card expands into the full Reports dashboard. | FR-RPT-09/10 (new) |
| 7 | **Sync-connector mapping.** Because sync runs unattended, identity-field mapping is confirmed once at connector configuration time and re-confirmed on source schema change (never per import). | FR-REG-20 (new) |

---

## 1. Executive Summary

The State Social Protection Management Information System (SP-MIS) is a centralized digital platform for coordinating, monitoring, and managing social protection programmes delivered by Ministries, Departments, and Agencies (MDAs), development partners, and other stakeholders across the state.

Today, each MDA runs its programmes on separate systems and registers beneficiaries independently. The same individual or household is often recorded many times, no agency can see the full picture of who is being served and with what, and there is no reliable way to coordinate, refer, or report across programmes. The result is duplication, wasted budget, and weak oversight.

SP-MIS resolves this by providing a single environment where MDAs deliver programmes through their activities, register beneficiaries, track benefits, coordinate referrals, monitor performance, and generate evidence-based reports — while each MDA retains ownership of the records it originates. Its design rests on three core ideas:

- **A hybrid beneficiary registry** that accepts data from many sources (SOCU, Kobo Collect, ODK, Excel uploads, APIs, and existing government systems) and supports both household-based and individual-based programmes.
- **A beneficiary ownership model** in which the first MDA to register a beneficiary owns the core profile, while other MDAs can request to deliver services without taking ownership — enabling collaboration without turf conflict.
- **Duplicate verification** that runs before a record is saved and uses configurable matching rules to surface existing beneficiaries, so agencies serve people instead of re-registering them.

On top of this foundation sit a shared programme catalog, MDA-owned activities, referral management, a complete benefit ledger, grievance redress, GIS mapping, and role-based dashboards and reporting that give everyone from frontline officers to the Governor a shared, real-time view of social protection in the state.

---

## 2. Product Vision, Goals & Non-Goals

### 2.1 Vision
To provide a secure, scalable, and integrated Social Protection Management Information System that improves coordination, transparency, accountability, and efficiency across all social protection programmes within the state.

### 2.2 Goals (Objectives)
The system shall enable the state to:
- Operate a single, centralized social protection platform shared by all participating MDAs.
- Eliminate duplicate beneficiary registration through pre-save verification.
- Preserve beneficiary ownership by the originating MDA while enabling cross-MDA service delivery.
- Coordinate referrals and linkages between MDAs and record their outcomes.
- Maintain a complete, auditable history of every benefit each beneficiary receives.
- Support programme monitoring, evaluation, and graduation.
- Improve data quality, transparency, and budget utilization.
- Deliver executive dashboards and automated, evidence-based reports.
- Provide an open foundation for future integration with national and external systems.

### 2.3 Non-Goals (Out of Scope for this Release)
To keep the first release focused, the following are explicitly out of scope and noted here to manage expectations:
- SP-MIS is **not a payment or disbursement engine**; it records benefits delivered, but money movement remains with existing financial channels (integration may follow later).
- It is **not a national identity system**; it consumes identity numbers (e.g. NIN, BVN) for matching but is not the system of record for identity.
- It does **not replace MDAs' specialized operational systems**; it coordinates and consolidates rather than absorbing every internal workflow.
- Public/citizen self-service portals, mobile money integration, and predictive analytics are deferred to later phases (see Section 16).

---

## 3. Problem Statement

Social protection programmes are currently managed independently by different MDAs, each with its own tools and registers. This fragmentation produces a recurring set of problems:

| Current Problem | Impact |
|-----------------|--------|
| Duplicate beneficiary registrations | The same person/household is enrolled multiple times, inflating numbers and cost. |
| Fragmented beneficiary databases | No single source of truth; data is inconsistent and hard to reconcile. |
| Limited coordination between MDAs | Programmes overlap or leave gaps; effort is duplicated. |
| Poor referral mechanisms | Identified needs are not reliably routed to the right MDA. |
| Difficulty tracking benefits received | No complete view of what a beneficiary has been given, by whom. |
| Inconsistent reporting | Reports are manual, slow, and not comparable across MDAs. |
| Inefficient budget utilization | Funds are spent on duplicates and poorly targeted delivery. |
| Lack of centralized monitoring | Leadership has no real-time, state-wide view of performance. |

SP-MIS addresses these problems through one unified platform built around shared data with clear ownership.

---

## 4. Target Users & Roles

The system serves several distinct user groups. Access is governed by role-based access control (RBAC); each user sees and acts only on data permitted for their role and MDA.

| User Group | Who | What they do in SP-MIS |
|------------|-----|------------------------|
| Executive Users | Governor, Deputy Governor, Commissioners, Permanent Secretaries, Executive Council | View state-wide dashboards, coverage, and performance; consume high-level reports. Read-only. |
| SP Coordination Unit | State SP Coordination Office; Monitoring & Evaluation Officers | Coordinate across MDAs, **maintain the programme catalog** and matching rules, monitor performance, run M&E and reporting. |
| MDA Users (MDA Admin) ✱ | A single MDA role (no separate Officer) | Register beneficiaries (via bulk import), **manage activities (selecting from the programme catalog)**, validate duplicates, deliver services, raise/accept referrals, raise/**approve** request-to-serve, and generate MDA-scoped reports. MDAs do not create or configure programmes, and do not manage users (that is centralized with the System Administrator). |
| Development Partners | Funding and implementing partners | Monitor their **funded programmes** (scoped via activity funding attribution, FR-PRG-07) — reach, budget-vs-delivered value, coverage, programme overlap — via a partner reporting suite; aggregates only, never beneficiary PII. |
| System Administrators | Platform/IT team | Manage users and MDAs, **own and configure the programme catalog** and matching rules, run data synchronization, and administer the platform via the **System Administrator Console** (governance, configuration, oversight — not infrastructure monitoring or delivery operations). |

---

## 5. Product Scope

### 5.1 In Scope (Functional Modules)

| Module | Module | Module |
|--------|--------|--------|
| User & Access Management | Beneficiary Registry (Hybrid) | Duplicate Verification |
| Beneficiary Ownership | Referral & Linkage | Benefit Tracking (Ledger) |
| Programme Catalog (central) | Activity Management (MDA) | Graduation Management |
| Grievance Redress (GRM) | Notifications | Reports & Analytics |
| Executive / MDA / Partner Dashboards | GIS Dashboard | Audit Logs |
| Document Management | Data Sharing | Data Synchronization |

Household Registry is supported as an optional capability for programmes that operate at household level.

Reporting is delivered through role-based suites — an Executive Reporting Suite, a Funding Partner Reporting Suite, and a System Administrator Console — over the shared data (see §7.11, FR-RPT-07/08, and FR-UAM-07).

### 5.2 Out of Scope / Deferred
See Section 2.3 (Non-Goals) and Section 16 (Future Enhancements) for capabilities deliberately deferred to later releases.

---

## 6. Key Concepts

Three concepts underpin the whole system and are referenced throughout the requirements.

### 6.1 Hybrid Beneficiary Registry
Beneficiary data enters SP-MIS from many sources rather than a single registration form. Supported sources include SOCU, Kobo Collect, ODK, Excel/CSV upload, API integration, and existing government systems. Every record is tagged with its provenance so the origin and lineage of each beneficiary are always traceable. Each record stores at minimum: Registration Source, Owner MDA, Registration Date, Import Batch, and Original Record ID (from the source system).

**Note (v1.2):** there is no manual single-record creation path. All beneficiary and household records enter via bulk/source import, and each import is performed in the context of a registered activity (see §6.4 and FR-REG-10).

### 6.2 Beneficiary Ownership Model
The MDA that first registers a beneficiary becomes the Owner MDA and is the only party permitted to edit the core beneficiary profile. Other MDAs may request access to deliver services without taking ownership. This keeps accountability clear while enabling collaboration. The system may also automatically route or assign a beneficiary to an MDA whose programme matches an identified need, subject to ownership and consent rules.

**Request-to-serve approval (v1.2).** When duplicate verification links an incoming record to an existing beneficiary owned by another MDA, the requesting MDA cannot deliver an intervention unilaterally. It must submit a service request to the Owner MDA, which accepts or declines it (a decline may carry a reason). Only on acceptance may the requesting MDA record its intervention against the existing beneficiary. Upon acceptance the requesting MDA is also granted read access to the full beneficiary record, so both the owning and serving MDAs can see the complete profile and benefit history. Ownership and edit rights remain solely with the Owner MDA. Every request, acceptance, and decline is written to the audit log.

### 6.3 Duplicate Verification
Before any new beneficiary is saved, the system checks for existing matches using configurable rules. If a likely duplicate is found, the requesting MDA is shown the existing record and can choose to provide a service to that beneficiary instead of creating a new record. This is the mechanism that operationally enforces the "register once, serve many times" principle.

**Default matching cascade (v1.2).** The shipped default configuration evaluates candidates in stages and stops at the first deterministic (exact) match:
1. **Exact NIN** — if the incoming record has a NIN and it exactly matches an existing record, that is a definitive duplicate; evaluation stops.
2. **Exact BVN** — if no NIN match is found (or no NIN is present), and a BVN is present, an exact BVN match is a definitive duplicate; evaluation stops.
3. **Fuzzy name / phone** — if no exact identifier match is found (or neither NIN nor BVN is present), the record is scored against existing records on name and phone (and other configured signals). A score in the review band produces a probable match.

This cascade is the default configuration only, not hard-coded logic: which fields participate, the order, the weights, and the thresholds all remain admin-configurable (FR-DUP-02, FR-DUP-03). Where an identifier is absent, that stage is skipped and the record falls through to the next.

**Where the officer decides (v1.2).** The officer adjudicates whether a candidate is truly the same person only for probable (fuzzy-band) matches (FR-DUP-09). An exact NIN/BVN match is treated as a definitive duplicate and is not adjudicated. In every case where a match exists, the officer still chooses what to do next — discard the incoming duplicate row (keeping the existing beneficiary) or provide a service to the existing beneficiary via request-to-serve. Nothing is ever silently auto-merged.

### 6.4 Activity Beneficiary Involvement & Upload (v1.2, revised v1.5)
Beneficiary data is uploaded in the context of a registered activity. During activity creation the MDA selects a catalog programme, enters the activity details, and declares **whether the activity involves beneficiaries** (✚):
- **If yes:** a **required target-beneficiaries count** is captured and a **beneficiary upload is mandatory** — the officer must provide the data. Validation and duplicate verification run in preview **before the activity is saved**; on confirm, the activity is saved with its new beneficiaries (interventions recorded under it) and a **pending Service Request** under it for each served duplicate (see §8.1).
- **If no:** there is no target field and no upload step; the activity is saved alone.
Data for an involving activity may also be uploaded later via the Import Center bound to that activity. Every activity has a **View Activity** detail view (programme, fields, target vs actual counts, beneficiaries/interventions, import summary, and pending service requests). See FR-REG-10, FR-REG-11, FR-PRG-05, and the flow in §8.1.

---

### 6.5 Data Import & Mapping ✜ (v1.7)

MDAs keep their own file formats; SP-MIS keeps one **canonical beneficiary schema**. Between a raw upload
and validation/dedup sits a mapping + normalization layer:

- **Column mapping.** On upload the system detects the file's columns and suggests mappings to canonical
  fields (name, phone, NIN, BVN, DOB, gender, address, LGA/Ward, household ref). The raw file is never
  altered; a canonical representation is produced for processing.
- **Identity-field confirmation (hard rule).** Mappings for **NIN, BVN, full name, and phone** must be
  explicitly confirmed (or marked "not present") on **every** import before any row is processed —
  suggestions are never silently applied. This prevents, e.g., a column named `ID` being misread as NIN.
- **Learnable templates.** A confirmed mapping is saved per MDA + source format and pre-fills future
  uploads of the same shape — but identity fields still require confirmation, and a changed format
  triggers re-mapping.
- **Normalization.** Names (case/whitespace), phones (country code/leading zero), dates, and NIN/BVN
  (spacing/hyphens) are normalized for **comparison** by the duplicate engine, while the **original**
  value is stored on the record for display and audit.
- **Preview.** Before commit, the user sees mapped canonical columns, normalized-vs-original samples, and
  which rows will reject (malformed identity, FR-REG-05).

The canonical rows then flow into the existing validation and the default duplicate cascade unchanged
(exact NIN → exact BVN → fuzzy name/phone, FR-DUP-08). Both upload paths (activity wizard and Import
Center) share this one stage. See FR-REG-12..17 and the revised flow in §8.1.

## 7. Functional Requirements

Requirements are grouped by module. Each has a unique ID for traceability and a MoSCoW priority. "Must" items define the minimum viable product. Items new or revised in v1.2 are marked ▸; in v1.3, ◆.

### 7.1 User & Access Management

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-UAM-01 | The system shall provide role-based access control with predefined roles (Executive, SP Coordination, M&E Officer, MDA Admin, Development Partner, System Administrator) ✱. | Must |
| FR-UAM-02 | Administrators shall create, edit, suspend, and deactivate user accounts and assign each user to an MDA and role. | Must |
| FR-UAM-03 | Each user shall be scoped to data belonging to their MDA, except where cross-MDA access is explicitly granted (including read access granted via an accepted request-to-serve, per FR-OWN-07). | Must |
| FR-UAM-04 | Authentication shall enforce strong passwords and support multi-factor authentication (MFA) for administrative and executive roles. | Must |
| FR-UAM-05 | Permissions shall be configurable at module and action level (view, create, edit, approve, export). | Should |
| FR-UAM-06 | The system shall enforce session timeout and lock accounts after repeated failed login attempts. | Should |
| ✚ FR-UAM-07 | The System Administrator role shall have a governance console that composes existing capabilities (user/access, organizations, programme catalog, registry & data quality, matching rules, integrations, audit & security, reports, and platform settings) for administration, configuration, and oversight. The console shall not include infrastructure/system-health monitoring or programme-delivery operations. | Should |

### 7.2 Beneficiary Registry (Hybrid)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-REG-01 | The system shall support both individual-based and household-based registration. | Must |
| FR-REG-02 | The system shall ingest beneficiary data from SOCU, Kobo Collect, ODK, Excel/CSV upload, REST API, and existing government systems. | Must |
| FR-REG-03 | Each beneficiary record shall store registration source, owner MDA, registration date, import batch, and original record ID. | Must |
| FR-REG-04 | The system shall capture core identity fields: NIN, BVN, phone number, full name, date of birth, gender, address, LGA/Ward, and household reference. | Must |
| ▸ FR-REG-05 | The system shall validate mandatory fields and data formats on entry and import. A row whose core identity value — full name, phone number, NIN, or BVN — is present but malformed shall be rejected in whole to the error report and shall not be saved to the data pool. (An absent optional NIN/BVN is valid and does not cause rejection.) | Must |
| FR-REG-06 | Bulk import shall provide a preview, a validation summary, and row-level error reporting before records are committed. | Should |
| FR-REG-07 | The system shall allow supporting documents to be attached to a beneficiary record. | Should |
| FR-REG-08 | The system shall support offline data capture that synchronizes when connectivity is restored. | Could |
| ▸ FR-REG-09 | For non-identity fields that fail validation, the system shall exclude (or null) the offending field for that row, note the issue in the row's validation report, and still save the row. Identity fields (name, phone, NIN, BVN) shall never be partially saved — see FR-REG-05. | Should |
| ▸✦ FR-REG-10 | Beneficiary data shall be uploaded in the context of a registered activity; the upload may occur inline during activity creation (FR-REG-11) or later via bulk import bound to the activity. Upload occurs only for activities that involve beneficiaries (FR-REG-11). The beneficiary still enters the shared registry under the first-importer ownership rule (FR-OWN-01). | Must |
| ✦✚ FR-REG-11 | An activity shall declare whether it involves beneficiaries. If it does, a target-beneficiaries count and a beneficiary upload are **required**: validation (FR-REG-05/09) and duplicate verification (FR-DUP-08) run in preview **before the activity is saved**; on confirm, the activity is saved, new (non-duplicate) beneficiaries are recorded under it with their interventions, and a pending Service Request (FR-OWN-06) is created under the activity for each served duplicate (intervention deferred until acceptance, FR-BEN-06). If the activity does not involve beneficiaries, no target and no upload are captured. Data for an involving activity may also be uploaded later via bulk import bound to it. Every activity has a View Activity detail view. | Must |

| ✜ FR-REG-12 | The system shall maintain a canonical beneficiary schema (aligned to FR-REG-04) that the validation and duplicate engine consume, independent of any MDA's source format. | Must |
| ✜ FR-REG-13 | On import the system shall detect source columns and suggest mappings to canonical fields; the raw file shall never be mutated. | Must |
| ✜ FR-REG-14 | Mappings for identity fields (NIN, BVN, full name, phone) shall be explicitly confirmed (or marked absent) on **every** import before processing; the system shall never auto-apply an identity-field mapping without confirmation. | Must |
| ✜ FR-REG-15 | The system shall save confirmed mappings as per-MDA, per-source-format templates that pre-fill subsequent imports; identity-field confirmation shall still be required, and a changed source format shall trigger re-mapping. | Should |
| ✜ FR-REG-16 | The system shall normalize values for comparison (name case/whitespace, phone country-code/leading-zero, date formats, NIN/BVN spacing) while preserving and storing the original value; matching operates on normalized values, display/audit uses originals. | Must |
| ✜ FR-REG-17 | The system shall present an import preview (mapped columns, normalized-vs-original samples, and rows that will reject per FR-REG-05) before commit. Both upload paths (activity wizard, Import Center) share the mapping+normalization stage. | Should |

| ❖ FR-REG-18 | Each import shall be tagged with a data source (SOCU/mined or self-sourced by the MDA); SOCU records shall carry a per-record source ID (the beneficiary's original record ID in SOCU, stored as `source_record_id`). The registration source and the owner MDA are distinct: ownership is set by first import (FR-OWN-01) and SOCU provenance never confers or changes ownership. | Must |
| ❖ FR-REG-19 | Identity-field validation shall apply concrete, configurable formats: NIN and BVN = exactly 11 numeric digits; phone = a valid Nigerian number (after normalization); dates = real, non-future where applicable. A present-but-malformed identity field rejects the row (FR-REG-05) and the row-level error report shall state which field failed and why. | Must |
| ❖ FR-REG-20 | For unattended sync ingestion, identity-field mapping shall be confirmed once at connector **configuration** time (not per import), and re-confirmed when the source schema changes; a connector with an unconfirmed or stale mapping shall not sync. | Should |

### 7.3 Duplicate Verification

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-DUP-01 | The system shall run a duplicate check before persisting any new beneficiary. | Must |
| FR-DUP-02 | Matching rules shall be configurable across NIN, BVN, phone, full name, date of birth, gender, address, and household information. | Must |
| FR-DUP-03 | The system shall support deterministic matching (e.g. exact NIN/BVN) and fuzzy matching (e.g. name/date of birth) with configurable thresholds. | Must |
| FR-DUP-04 | When a match is found, the system shall display the existing beneficiary name and ID, owner MDA(s), data source, programme(s), registration date, LGA/Ward, and status (services and benefits received). | Must |
| ▸ FR-DUP-05 | The requesting MDA shall be able to request to provide services to the existing beneficiary instead of creating a duplicate record. This request-to-serve is subject to Owner-MDA approval (FR-OWN-06); interventions may be recorded only after acceptance. | Must |
| FR-DUP-06 | Every duplicate decision (keep, link, request service) shall be recorded in the audit log. | Should |
| FR-DUP-07 | The system shall support AI-assisted match scoring. | Could |
| ▸ FR-DUP-08 | The system shall ship a default matching cascade (configurable, not hard-coded): exact NIN, then exact BVN, then fuzzy name/phone (with other configured signals). Evaluation shall stop at the first exact (deterministic) match; where an identifier is absent, that stage is skipped and the record falls through to the next. | Must |
| ▸ FR-DUP-09 | The officer shall adjudicate a candidate as "same person / not the same person" only for probable (fuzzy-band) matches. Exact matches shall be treated as definitive duplicates without adjudication. In all match cases the discard-or-serve action still applies, and no record shall be silently auto-merged. | Must |

| ❖ FR-DUP-10 | When duplicate verification finds a confirmed match whose owner is the **uploading MDA itself**, the system shall resolve it as "already owned" — it shall not create a duplicate record and shall not raise a request-to-serve (an MDA does not request to serve its own beneficiary). It shall offer to record a new intervention on the existing beneficiary under the current activity. | Must |

### 7.4 Beneficiary Ownership

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-OWN-01 | The first MDA to register a beneficiary shall be set as the Owner MDA. | Must |
| FR-OWN-02 | Only the Owner MDA shall be able to edit the core beneficiary profile. | Must |
| FR-OWN-03 | Other MDAs shall be able to request service access without transferring ownership. | Must |
| FR-OWN-04 | The system shall be able to automatically assign or route a beneficiary to an MDA whose programme matches an identified need. | Should |
| FR-OWN-05 | Ownership transfer shall require Owner MDA approval and shall be logged. | Should |
| ▸ FR-OWN-06 | A non-owner MDA's request-to-serve shall require Owner-MDA approval (accept, or decline with optional reason). The requesting MDA may record interventions against the existing beneficiary only after acceptance. Every request and decision shall be logged (FR-AUD-01). | Must |
| ▸ FR-OWN-07 | Upon acceptance of a request-to-serve, the requesting MDA shall be granted read access to the full beneficiary record. Ownership and edit rights remain solely with the Owner MDA. | Must |

| ✜ FR-OWN-08 | Cross-MDA read access granted on request-to-serve acceptance (FR-OWN-07) shall be **revocable by the Owner MDA** (System Administrator override). Revocation shall take effect immediately (all read/serve gates deny), be audited (FR-AUD-01) and notify the affected serving MDA. Revocation withdraws ongoing read access only — it shall not delete interventions already recorded, nor change ownership. | Must |

### 7.5 Referral & Linkage

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-REF-01 | An MDA shall be able to create a referral to another MDA, specifying the beneficiary, the identified need, and notes. | Must |
| FR-REF-02 | The receiving MDA shall be able to accept, reject (with reason), or request more information. | Must |
| FR-REF-03 | The service outcome shall be recorded against the referral; ownership shall remain with the originating MDA. | Must |
| FR-REF-04 | Referral status shall be tracked through its lifecycle (Created → Accepted → In Progress → Completed/Closed) with timestamps and configurable SLAs. | Should |
| FR-REF-05 | Both MDAs shall be notified at each referral status change. | Should |

**Referral vs. request-to-serve (v1.2 clarification).** A referral (FR-REF) is outbound: the Owner MDA routes a beneficiary to another MDA for a need outside its mandate. A request-to-serve (FR-OWN-06) is inbound: a non-owner MDA that has matched a duplicate asks the Owner MDA for permission to deliver its own intervention. They are distinct workflows and must be named and tracked separately.

### 7.6 Benefit Tracking (Benefit Ledger)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-BEN-01 | The system shall maintain a complete benefit history for each beneficiary. | Must |
| FR-BEN-02 | Each benefit record shall store programme, activity, MDA, benefit type, quantity, monetary value, funding source, delivery date, status, and verification method. | Must |
| FR-BEN-03 | The benefit ledger shall be viewable per beneficiary and aggregatable by programme, MDA, and LGA/Ward. | Must |
| FR-BEN-04 | The system shall support verification of delivery (e.g. field-officer confirmation, OTP, signature, or biometric reference). | Should |
| FR-BEN-05 | The system shall flag potential double-dipping where the same benefit type is delivered by multiple MDAs within a defined period. | Should |
| ▸ FR-BEN-06 | An intervention delivered under an accepted request-to-serve shall be recorded against the existing beneficiary and attributed to the delivering MDA and its activity; the beneficiary's ownership shall be unchanged. Every intervention accumulates in the beneficiary's single ledger so the full cross-MDA history is preserved. | Must |

### 7.7 Programme & Activity Management

| ID | Requirement | Priority |
|----|-------------|----------|
| ◆ FR-PRG-01 | The System Administrator (and optionally the SP Coordination Unit) shall create and maintain a **global catalog of programmes**, each capturing type-level attributes only: name, objective, type (household/individual), benefit category, and standard eligibility. Programmes are shared across all MDAs and are not owned by any single MDA; they are readable by all MDAs for selection. MDA roles (Officer, MDA Admin) shall not create, edit, or delete programmes. | Must |
| ◆❖ FR-PRG-02 | An MDA shall create and **own activities** under a selected catalog programme, capturing target, **location (a set of LGAs with optional wards per LGA — see FR-PRG-08)**, schedule, budget, funding source, period, and activity-level eligibility. The same catalog programme may be delivered by multiple MDAs, each through its own separate activity. | Must |
| FR-PRG-03 | The system shall allow beneficiaries to be enrolled or assigned to programmes and activities. | Should |
| ◆ FR-PRG-04 | The system shall track budget allocated versus utilized **per activity**, and aggregate to the programme level as the sum of its activities across MDAs. | Should |
| ▸✦ FR-PRG-05 | An activity must exist before beneficiaries can be uploaded to it (FR-REG-10). Beneficiary upload is bound to a selected registered activity, and interventions produced by that upload roll up to the activity and its (catalog) programme. When the activity involves beneficiaries, activity creation includes the mandatory inline upload (FR-REG-11), and the activity is created and the rows bind to it within the same commit. | Must |
| ◆ FR-PRG-06 | The programme catalog shall be globally readable by all authenticated roles for activity creation and reporting, while create/edit/delete is restricted to the System Administrator (optionally SP Coordination). Programmes shall not be MDA-scoped. | Must |
| ✚ FR-PRG-07 | An activity may be attributed to a funding **Development Partner** (a property of the activity, never the programme) so partner reporting can scope to funded programmes. Funding attribution confers reporting-visibility only and shall never grant access to beneficiary records. | Should |

| ❖ FR-PRG-08 | An activity's location shall be a **set**: one or more LGAs, each with zero or more wards (no wards for an LGA = the whole LGA). Locations reference LGA/Ward **reference-data lookups** (not free text). The location set is descriptive — for planning and coverage reporting — and is **not** enforced against beneficiary locations. This revises the single-location FR-PRG-02. | Should |

### 7.8 Grievance Redress (GRM)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-GRM-01 | The system shall capture grievances with category, channel, beneficiary link, and description. | Must |
| FR-GRM-02 | Grievances shall be assignable and trackable through to resolution, with resolution notes and timestamps. | Must |
| FR-GRM-03 | The system shall track SLAs and escalate overdue grievances. | Should |

### 7.9 Graduation Management

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-GRD-01 | The system shall allow graduation criteria to be defined per programme. | Should |
| FR-GRD-02 | The system shall track beneficiary progress toward graduation and record graduation events. | Should |

### 7.10 Notifications

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-NOT-01 | The system shall provide in-app notifications for referrals, approvals (including request-to-serve decisions), grievances, and key system events. | Must |
| FR-NOT-02 | The system shall support email notifications, with SMS/WhatsApp in a later phase. | Should |

| ❖ FR-NOT-03 | When a request-to-serve is raised, the system shall email the owner MDA (action-required: log in to review and accept/decline). The email shall contain no beneficiary PII (NIN/BVN/full identity) and shall link to the in-app request. In-app notification (FR-NOT-01) also applies. | Should |

### 7.11 Reporting & Analytics

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RPT-01 | The system shall provide an executive dashboard with real-time KPIs (beneficiaries, programmes, benefits disbursed, coverage by LGA). | Must |
| FR-RPT-02 | The system shall provide MDA and Partner dashboards scoped to permitted data. | Must |
| FR-RPT-03 | The system shall generate standard and ad-hoc reports and export to PDF, Excel, and CSV. | Must |
| FR-RPT-04 | The system shall support scheduled, automated report generation and distribution. | Should |
| ✚ FR-RPT-05 | Export of beneficiary data shall be governed by a permission matrix: a distinct `export` permission (System Administrator = all; SP Coordination/M&E = cross-MDA; MDA Admin = own MDA; Development Partners and Executives = aggregate reports only, never the beneficiary registry). Exports inherit the caller's scope, mask NIN/BVN by default, and are audited. | Must |
| ✚ FR-RPT-06 | Unmasked NIN/BVN export shall require a separate `export.reveal_pii` permission (System Administrator only by default; granting otherwise is a DPO decision). A data grid may expose an "export this list" of its current filtered view, reusing the export service and honouring the matrix. | Must |
| ✚ FR-RPT-07 | The executive dashboard (FR-RPT-01) shall be delivered as a multi-tab reporting suite (overview, programmes, registry, coordination, coverage map), read-only and aggregate-only. The headline measure shall be **net unique beneficiaries** (never gross registrations); coverage shall be shown as absolute counts where no population/eligibility denominator is loaded. | Should |
| ✚ FR-RPT-08 | The partner dashboard (FR-RPT-02) shall be delivered as a multi-tab reporting suite scoped to the partner's funded programmes (via activity funding attribution, FR-PRG-07). Funding shall be presented as allocated (activity budget) → delivered (benefit value) → remaining, labelled as delivery value versus budget — not treasury expenditure (§2.3). | Should |

**Reporting principles (v1.5).** Executive/partner/admin reporting is read-only and aggregate-only (no raw PII on dashboards); the headline is net unique beneficiaries, not gross registrations; coverage is absolute until a denominator exists; partner funding is delivery-value-vs-budget, not expenditure; all exports obey the FR-RPT-05/06 matrix.

| ❖ FR-RPT-09 | The system shall provide a filtered report builder whose filterable dimensions are derived from the canonical schema (segmentable fields only — e.g. gender, age band, LGA/ward, programme, activity, source, date range, status; identity fields are excluded from filtering and masked in output). It shall return a table with export (CSV/Excel/PDF) and an optional chart, enforce the export permission matrix and role/MDA scoping (never a bypass), audit every run, and apply a configurable **minimum cell-size guard** on aggregate/cross-MDA tiers so small groups cannot re-identify individuals (the guard does not restrict an MDA segmenting its own beneficiaries). | Should |
| ❖ FR-RPT-10 | The Overview page shall show a summary of the reporting dashboard (read from the same aggregation source as the full page) that expands/deep-links into the full dashboard in the Reports section. | Should |

### 7.12 GIS Dashboard

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-GIS-01 | The system shall map beneficiaries, programmes, and coverage by LGA/Ward. | Should |
| FR-GIS-02 | The system shall provide heat maps of coverage and need. | Could |

### 7.13 Audit, Data Sharing & Synchronization

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-AUD-01 | The system shall keep an immutable audit log of all create, edit, delete, access, and approval actions (including request-to-serve requests and decisions), recording user, timestamp, and before/after values. | Must |
| FR-DSH-01 | Data sharing between MDAs shall be controlled and governed by ownership and consent rules (including the request-to-serve approval gate, FR-OWN-06). | Must |
| FR-DSH-02 | The system shall synchronize data with external and source systems on a schedule or trigger. | Should |

---

## 8. Key User Flows

### 8.1 Register a Beneficiary with Duplicate Check ▸ (revised v1.2) ✦ (extended v1.4)
1. An MDA Admin creates (or selects) an activity under a catalog programme (FR-PRG-05, FR-PRG-02) and declares whether it involves beneficiaries. If it does NOT, the activity is saved and this flow ends. If it does, a target-beneficiaries count is required and beneficiary data must be uploaded — as the activity's mandatory upload step (FR-REG-11) or later via the Import Center bound to that activity. The upload passes through the Data Import & Mapping layer (§6.5): columns are mapped to the canonical schema (identity-field mappings confirmed, FR-REG-14), values normalized (FR-REG-16), and previewed **before** validation and duplicate detection run on the canonical rows.
2. The officer uploads beneficiary data for the activity from a source (Excel, CSV, SOCU, Kobo Collect, ODK, REST API, or an existing government system). There is no manual single-record entry.
3. The system validates each row **in preview, before the activity is committed**. Rows whose identity fields (name, phone, NIN, BVN) are present but malformed are rejected to the error report and not saved (FR-REG-05); non-identity field errors are dropped/flagged while the row saves (FR-REG-09).
4. For each valid row the system runs duplicate verification using the configured cascade — exact NIN → exact BVN → fuzzy name/phone (FR-DUP-08), stopping at the first exact match.
5. For probable matches the officer first adjudicates same-person / not (FR-DUP-09); exact matches are definitive. For each match the system reveals the existing beneficiary, owner MDA, source, programmes, and benefits received (FR-DUP-04).
6. The officer resolves each row — discard (drop the incoming duplicate; the existing beneficiary is untouched) or provide a service (request-to-serve).
7. On confirm, the system commits atomically: the **activity is saved**; each no-match row is saved as a new beneficiary owned by the uploading MDA (FR-OWN-01) with its intervention recorded under the activity; and each served duplicate creates a **pending Service Request under the activity** (§8.4) — the intervention deferred until the Owner MDA accepts (FR-BEN-06). Every decision is logged (FR-DUP-06).

### 8.2 Refer a Beneficiary to Another MDA
1. Owner MDA identifies a need outside its mandate (e.g. Women Affairs identifies a health need).
2. A referral is created to the relevant MDA (e.g. Ministry of Health) with the need and notes.
3. The receiving MDA accepts, rejects (with reason), or requests more information.
4. On acceptance, the receiving MDA delivers the service and records the outcome.
5. Ownership remains with the originating MDA; both parties are notified at each step.

### 8.3 Record a Benefit Delivery
1. An MDA selects a beneficiary and the relevant activity (which carries its catalog programme).
2. The officer records benefit type, quantity, monetary value, funding source, and delivery date.
3. Delivery is verified and the status is set.
4. The benefit is appended to the beneficiary's ledger and rolls up into activity, programme, MDA, and LGA reporting.

### 8.4 Request-to-Serve a Matched Beneficiary ▸ (new v1.2)
1. During duplicate resolution (§8.1, step 7) a non-owner MDA elects to provide a service to an existing beneficiary owned by another MDA.
2. The system creates a service request to the Owner MDA identifying the beneficiary, the requesting MDA, and the intended activity; both parties are notified.
3. The Owner MDA accepts or declines (a decline may carry a reason). The decision is logged (FR-OWN-06, FR-AUD-01).
4. On acceptance: the requesting MDA gains read access to the full beneficiary record (FR-OWN-07) and may record its intervention against the existing beneficiary under its activity (FR-BEN-06). Ownership is unchanged.
5. On decline: no intervention is recorded; the requesting MDA is notified. It may re-request with additional justification if appropriate.
6. **Revocation (FR-OWN-08):** at any time after acceptance the Owner MDA (or a System Administrator) may revoke the serving MDA's read access. Revocation is immediate, audited, and notifies the serving MDA; interventions already recorded remain, and ownership is unchanged.

---

## 9. Data Model (Key Entities)

The following core entities and relationships describe the conceptual data model. Detailed schema design will follow in technical design. Entities new or revised in v1.2 are marked ▸; in v1.3, ◆.

| Entity | Description | Key Attributes |
|--------|-------------|----------------|
| Beneficiary ❖ | Individual served by one or more programmes. Source and owner are distinct (owner = first importer). | ID, NIN, BVN, name, DOB, gender, phone, address, LGA/Ward, owner MDA, **registration_source (incl. SOCU)**, **source_record_id (SOCU/original ID, nullable)**, registration date, status. |
| Household | Optional grouping of beneficiaries. | Household ID, head, members, address, LGA/Ward. |
| ❖ LGA / Ward (reference data) | Administrative geography lookups (Jigawa: 27 LGAs, ~287 wards). | LGA (id, name, code); Ward (id, lga_id, name, code); optional PostGIS geometry for GIS. |
| ◆ MDA | Ministry, Department, or Agency. | ID, name, type, contact, activities owned. |
| ◆ Programme | **Global catalog** service type, created centrally (System Admin / SP Coordination). Not MDA-owned. | ID, name, objective, type (HH/individual), benefit_category, standard_eligibility, status. |
| ◆✚❖ Activity | **MDA-owned** unit of work under a catalog programme; must exist before beneficiaries are uploaded to it; may be attributed to a funding partner (reporting only). | ID, programme (catalog), owner MDA, funding_partner (nullable), involves_beneficiaries, target_beneficiaries, target, **location set → activity_locations (lga_id, ward_id nullable; multi-LGA, wards optional per LGA, descriptive)**, schedule, budget, funding_source, period, eligibility, status. |
| ▸◆ Benefit / Intervention | A benefit delivered to a beneficiary; programme-typed, delivered via an MDA activity; may be delivered by a non-owner MDA under an accepted request-to-serve. | ID, beneficiary, programme (catalog), activity (MDA-owned), delivering MDA, type, quantity, value, funding source, delivery date, status, verification. |
| Referral | A request to serve a beneficiary across MDAs (outbound). | ID, beneficiary, from MDA, to MDA, need, status, outcome, timestamps. |
| ▸ Service Request (Request-to-Serve) | An inbound request by a non-owner MDA to deliver an intervention to an existing beneficiary. | ID, beneficiary, requesting MDA, owner MDA, activity, status (pending/accepted/declined), decided_by, decision reason, timestamps. |
| Grievance | A complaint or query. | ID, beneficiary, category, channel, status, resolution, timestamps. |
| User | A system user. | ID, name, MDA, role, permissions, status. |
| Audit Log | Immutable record of actions. | ID, user, action, entity, before/after, timestamp. |

---

## 10. Integration Requirements

- **Data capture tools:** Kobo Collect and ODK form submissions ingested via export or API.
- **File-based import:** Excel/CSV upload with validation and batch tracking.
- **SOCU and existing government systems:** import and periodic synchronization of beneficiary data.
- **Open API:** a documented REST API for inbound registration and outbound reporting, secured by authentication and rate limiting.
- **Identity (future):** integration with national identity (e.g. NIMC/NIN) to strengthen duplicate verification, subject to authorization.
- **Messaging (future):** SMS/WhatsApp gateway for notifications and beneficiary engagement.

---

## 11. Non-Functional Requirements

Targets marked "proposed" are recommended baselines to be confirmed during planning.

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-SEC-01 | All data shall be encrypted in transit (TLS 1.2+) and at rest. Passwords shall be securely hashed. The application shall follow secure-development practices addressing the OWASP Top 10. | Must |
| NFR-SEC-02 | Privileged and executive accounts shall require MFA; access shall follow least privilege. | Must |
| NFR-PRV-01 | The system shall comply with applicable data protection law (e.g. NDPA/NDPR), capture consent where required, minimize PII, and enforce a defined data-retention policy. | Must |
| NFR-PERF-01 | Standard pages shall load within 3 seconds, and duplicate verification shall return results within 5 seconds, under normal load (proposed). | Should |
| NFR-SCAL-01 | The system shall scale to millions of beneficiary records and at least 500 concurrent users via horizontal scaling (proposed). | Should |
| NFR-AVAIL-01 | The system shall target 99.5% availability, with daily backups and defined RPO/RTO (proposed). | Should |
| NFR-USE-01 | The interface shall be responsive (desktop and mobile browsers), accessible, and usable by non-technical MDA staff with minimal training. | Should |
| NFR-AUD-01 | All security-relevant and data-changing actions shall be auditable and tamper-evident. | Must |
| NFR-INT-01 | The system shall be API-first and interoperable to support current and future integrations. | Should |
| NFR-MAINT-01 | The system shall be containerized for consistent deployment and maintainability. | Should |

---

## 12. Technical Architecture

The recommended stack is a modern, API-first web application designed for scale, background processing, and geospatial reporting.

| Layer | Technology | Purpose |
|-------|------------|---------|
| Backend / API | Laravel 12 (PHP) | Business logic, REST API, authentication, RBAC. |
| Frontend | React + TypeScript | Single-page application for all dashboards and workflows. |
| Database | PostgreSQL + PostGIS | Relational data plus geospatial queries for GIS mapping. |
| Caching | Redis | Performance caching and session/state support. |
| Queue / Workers | RabbitMQ | Asynchronous jobs: bulk imports, duplicate matching, notifications, synchronization. |
| Deployment | Docker (containers) | Consistent, portable, scalable deployment. |

**Architectural notes:** The API-first design lets data capture tools, partner systems, and future channels integrate cleanly. Heavy operations (imports, fuzzy matching, report generation) run on queue workers so the user interface stays responsive. PostGIS supports LGA/Ward mapping today and heat maps later. The duplicate-matching cascade (FR-DUP-08) and the request-to-serve approval workflow (FR-OWN-06) both run within this architecture — matching on workers, approvals as auditable state transitions on the Service Request entity. Programmes are a shared, globally-readable catalog; activities are MDA-scoped.

---

## 13. Success Metrics

The project will be judged on the outcomes below. Targets are proposed starting points to be agreed with stakeholders and baselined before launch.

| Metric | Proposed Target |
|--------|-----------------|
| Reduction in duplicate registrations | ≥ 80% reduction vs. baseline within 12 months |
| MDA adoption | ≥ 90% of participating MDAs actively using the platform within 6 months |
| Referral completion rate | ≥ 70% of accepted referrals completed |
| Benefit history coverage | 100% of recorded benefits captured in the ledger |
| Executive dashboard freshness | Real-time / within 24 hours |
| Report automation | ≥ 80% of standard reports generated automatically |
| Data quality | ≥ 95% of records passing validation rules |

---

## 14. Assumptions, Dependencies & Constraints

### 14.1 Assumptions
- Participating MDAs will adopt SP-MIS as the system of record for social protection beneficiaries and benefits.
- Source data (SOCU, Kobo/ODK, Excel) is available and reasonably complete for initial loading.
- A governance/legal framework authorizes data sharing across MDAs (including the request-to-serve consent model).

### 14.2 Dependencies
- Availability of hosting/infrastructure (cloud or on-premises) and connectivity at MDA sites.
- Access to identity references (NIN/BVN) and, for future phases, integration approval from identity authorities.
- Stakeholder availability to define matching thresholds, eligibility criteria, and SLAs.

### 14.3 Constraints
- Intermittent connectivity at some field locations (mitigated by offline capture in a later phase).
- Compliance with applicable data protection law and government IT policy.

---

## 15. Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Low MDA adoption / change resistance | High | Executive sponsorship, training, phased rollout, clear ownership model that protects MDA control (including the request-to-serve approval gate that keeps owners in charge). |
| Poor source data quality | High | Validation on import, row-level rejection of malformed identity data, error reporting, data-cleaning support, staged onboarding. |
| Identity matching errors (false matches/misses) | Medium | Configurable thresholds, human review of probable matches, deterministic checks on NIN/BVN. |
| Data privacy / breach | High | Encryption, RBAC, MFA, audit logs, NDPA/NDPR compliance, consent capture, approval-gated cross-MDA access. |
| Connectivity limitations in the field | Medium | Offline capture and sync in a later phase; lightweight UI. |
| Funding / sustainability | Medium | Phased delivery to show early value; open standards to avoid lock-in. |

---

## 16. Release Plan & Future Enhancements

### 16.1 Phased Roadmap (conceptual)

| Phase | Focus | Key Capabilities |
|-------|-------|------------------|
| Phase 1 (MVP) | Core registry & coordination | User/RBAC, hybrid registry (Excel, Kobo/ODK), duplicate verification, ownership, basic benefit tracking, MDA & executive dashboards, audit logs. |
| Phase 2 | Coordination & reporting depth | Referrals, grievance redress, programme catalog / activity management, partner dashboard, reporting & exports, email notifications, basic GIS. |
| Phase 3 | Integration & reach | Graduation management, GIS heat maps, API/identity integration, SMS/WhatsApp, scheduled reports, offline capture. |
| Phase 4 (Future) | Intelligence | AI-assisted duplicate detection, predictive analytics, national identity integration. |

The table above is the conceptual roadmap. Detailed, sequential build phasing and per-phase acceptance criteria are maintained in the repository's PHASE-N-BUILD-PROMPTS.md files.

### 16.2 Future Enhancements
- AI-assisted duplicate detection.
- WhatsApp and SMS integration.
- National identity integration.
- GIS heat maps.
- Predictive analytics.

### 16.3 Expected Benefits
- Stronger coordination across MDAs and reduced duplication.
- Greater transparency, accountability, and budget efficiency.
- Improved service delivery and referral outcomes.
- Evidence-based planning, monitoring, and policy decisions.

---

## 17. Glossary

| Term | Meaning |
|------|---------|
| SP-MIS | Social Protection Management Information System. |
| MDA | Ministry, Department, or Agency. |
| Owner MDA | The MDA that first registers a beneficiary and controls the core profile. |
| Programme (catalog) | ◆ A shared, centrally-created service type (e.g. Cash Transfer); not owned by any MDA. |
| Activity | ◆ An MDA-owned unit of work delivering a catalog programme, carrying its own budget, funding, schedule, and location. |
| SOCU | Social Operations / data source feeding beneficiary data. |
| Kobo Collect / ODK | Mobile data-collection tools used for field registration. |
| NIN / BVN | National Identification Number / Bank Verification Number, used for identity matching. |
| LGA / Ward | Local Government Area / Ward — administrative geography for coverage. |
| GRM | Grievance Redress Mechanism. |
| RBAC | Role-Based Access Control. |
| MoSCoW | Prioritization scheme: Must / Should / Could / Won't have. |
| RPO / RTO | Recovery Point Objective / Recovery Time Objective for backups and recovery. |
| ▸ Intervention | A service/benefit delivered to a beneficiary and recorded in the benefit ledger; programme-typed, delivered via an activity; may be delivered by a non-owner MDA under an accepted request-to-serve. |
| ▸ Request-to-Serve / Service Request | An inbound request by a non-owner MDA for the Owner MDA's approval to deliver an intervention to an existing beneficiary. Distinct from a Referral. |
| ▸ Matching Cascade | The default, configurable evaluation order for duplicate detection: exact NIN → exact BVN → fuzzy name/phone. |
| ✚ Funding-partner attribution | An activity's link to a funding Development Partner, used to scope partner reporting; reporting-visibility only, never data access. |
| ✚ Export permission matrix | The role-based governance of beneficiary-data export (`export`), with a separate `export.reveal_pii` for unmasked NIN/BVN. |
| ✚ Reporting suite / Admin console | Role-based multi-tab views (Executive, Partner) and the System Administrator governance console, all read/compose layers over existing data. |