
PRODUCT REQUIREMENTS DOCUMENT
State Social Protection
Management Information System
(SP-MIS)

A unified platform for coordinating, monitoring, and managing
social protection programmes across MDAs and partners
Version 1.1  ·  Draft for Review
Prepared by: Project Team
Date: June 2026

Document Control
Version History
Version	Date	Author	Summary of Changes
1.0	June 2026	Project Team	Initial draft.
1.1	June 2026	Project Team	Restructured into a full PRD: added goals/non-goals, requirement IDs and priorities, non-functional requirements, data model, user flows, success targets, risks, and a phased roadmap.

Approvals
Role	Name	Signature	Date
Project Sponsor			
SP Coordination Lead			
Technical Lead			
Data Protection Officer			

Status legend (priorities use MoSCoW): Must = required for launch, Should = important but not launch-blocking, Could = desirable, Won't (this release) = explicitly deferred.












1. Executive Summary
The State Social Protection Management Information System (SP-MIS) is a centralized digital platform for coordinating, monitoring, and managing social protection programmes delivered by Ministries, Departments, and Agencies (MDAs), development partners, and other stakeholders across the state.
Today, each MDA runs its programmes on separate systems and registers beneficiaries independently. The same individual or household is often recorded many times, no agency can see the full picture of who is being served and with what, and there is no reliable way to coordinate, refer, or report across programmes. The result is duplication, wasted budget, and weak oversight.
SP-MIS resolves this by providing a single environment where MDAs register beneficiaries, manage programmes, track benefits, coordinate referrals, monitor performance, and generate evidence-based reports — while each MDA retains ownership of the records it originates. Its design rests on three core ideas:
A hybrid beneficiary registry that accepts data from many sources (SOCU, Kobo Collect, ODK, Excel uploads, APIs, and existing government systems) and supports both household-based and individual-based programmes.
A beneficiary ownership model in which the first MDA to register a beneficiary owns the core profile, while other MDAs can request to deliver services without taking ownership — enabling collaboration without turf conflict.
Duplicate verification that runs before a record is saved and uses configurable matching rules to surface existing beneficiaries, so agencies serve people instead of re-registering them.
On top of this foundation sit referral management, a complete benefit ledger, grievance redress, GIS mapping, and role-based dashboards and reporting that give everyone from frontline officers to the Governor a shared, real-time view of social protection in the state.






2. Product Vision, Goals & Non-Goals
2.1 Vision
To provide a secure, scalable, and integrated Social Protection Management Information System that improves coordination, transparency, accountability, and efficiency across all social protection programmes within the state.
2.2 Goals (Objectives)
The system shall enable the state to:
Operate a single, centralized social protection platform shared by all participating MDAs.
Eliminate duplicate beneficiary registration through pre-save verification.
Preserve beneficiary ownership by the originating MDA while enabling cross-MDA service delivery.
Coordinate referrals and linkages between MDAs and record their outcomes.
Maintain a complete, auditable history of every benefit each beneficiary receives.
Support programme monitoring, evaluation, and graduation.
Improve data quality, transparency, and budget utilization.
Deliver executive dashboards and automated, evidence-based reports.
Provide an open foundation for future integration with national and external systems.
2.3 Non-Goals (Out of Scope for this Release)
To keep the first release focused, the following are explicitly out of scope and noted here to manage expectations:
SP-MIS is not a payment or disbursement engine; it records benefits delivered, but money movement remains with existing financial channels (integration may follow later).
It is not a national identity system; it consumes identity numbers (e.g. NIN, BVN) for matching but is not the system of record for identity.
It does not replace MDAs' specialized operational systems; it coordinates and consolidates rather than absorbing every internal workflow.
Public/citizen self-service portals, mobile money integration, and predictive analytics are deferred to later phases (see Section 16).




3. Problem Statement
Social protection programmes are currently managed independently by different MDAs, each with its own tools and registers. This fragmentation produces a recurring set of problems:
Current Problem	Impact
Duplicate beneficiary registrations	The same person/household is enrolled multiple times, inflating numbers and cost.
Fragmented beneficiary databases	No single source of truth; data is inconsistent and hard to reconcile.
Limited coordination between MDAs	Programmes overlap or leave gaps; effort is duplicated.
Poor referral mechanisms	Identified needs are not reliably routed to the right MDA.
Difficulty tracking benefits received	No complete view of what a beneficiary has been given, by whom.
Inconsistent reporting	Reports are manual, slow, and not comparable across MDAs.
Inefficient budget utilization	Funds are spent on duplicates and poorly targeted delivery.
Lack of centralized monitoring	Leadership has no real-time, state-wide view of performance.

SP-MIS addresses these problems through one unified platform built around shared data with clear ownership.
4. Target Users & Roles
The system serves several distinct user groups. Access is governed by role-based access control (RBAC); each user sees and acts only on data permitted for their role and MDA.
User Group	Who	What they do in SP-MIS
Executive Users	Governor, Deputy Governor, Commissioners, Permanent Secretaries, Executive Council	View state-wide dashboards, coverage, and performance; consume high-level reports. Read-only.
SP Coordination Unit	State SP Coordination Office; Monitoring & Evaluation Officers	Coordinate across MDAs, configure programmes/rules, monitor performance, run M&E and reporting.
MDA Users	Programme officers and MDA administrators	Register beneficiaries, manage programmes and activities, validate duplicates, deliver services, raise/accept referrals, generate reports.
Development Partners	Funding and implementing partners	Monitor funded programmes, track budget utilization, view performance, generate reports (scoped to their programmes).
System Administrators	Platform/IT team	Manage users and MDAs, configure programmes and matching rules, run data synchronization, monitor system health.
5. Product Scope
5.1 In Scope (Functional Modules)
Module	Module	Module
User & Access Management	Beneficiary Registry (Hybrid)	Duplicate Verification
Beneficiary Ownership	Referral & Linkage	Benefit Tracking (Ledger)
Programme Management	Activity Management	Graduation Management
Grievance Redress (GRM)	Notifications	Reports & Analytics
Executive / MDA / Partner Dashboards	GIS Dashboard	Audit Logs
Document Management	Data Sharing	Data Synchronization

Household Registry is supported as an optional capability for programmes that operate at household level.
5.2 Out of Scope / Deferred
See Section 2.3 (Non-Goals) and Section 16 (Future Enhancements) for capabilities deliberately deferred to later releases.
6. Key Concepts
Three concepts underpin the whole system and are referenced throughout the requirements.
6.1 Hybrid Beneficiary Registry
Beneficiary data enters SP-MIS from many sources rather than a single registration form. Supported sources include SOCU, Kobo Collect, ODK, Excel/CSV upload, API integration, and existing government systems. Every record is tagged with its provenance so the origin and lineage of each beneficiary are always traceable. Each record stores at minimum:
Registration Source
Owner MDA
Registration Date
Import Batch
Original Record ID (from the source system)
6.2 Beneficiary Ownership Model
The MDA that first registers a beneficiary becomes the Owner MDA and is the only party permitted to edit the core beneficiary profile. Other MDAs may request access to deliver services without taking ownership. This keeps accountability clear while enabling collaboration. The system may also automatically route or assign a beneficiary to an MDA whose programme matches an identified need, subject to ownership and consent rules.
6.3 Duplicate Verification
Before any new beneficiary is saved, the system checks for existing matches using configurable rules. If a likely duplicate is found, the requesting MDA is shown the existing record and can choose to provide a service to that beneficiary instead of creating a new record. This is the mechanism that operationally enforces the “register once, serve many times” principle.
7. Functional Requirements
Requirements are grouped by module. Each has a unique ID for traceability and a MoSCoW priority. “Must” items define the minimum viable product.
7.1 User & Access Management
ID	Requirement	Priority
FR-UAM-01	The system shall provide role-based access control with predefined roles (Executive, SP Coordination, M&E Officer, MDA Officer, MDA Admin, Development Partner, System Administrator).	Must
FR-UAM-02	Administrators shall create, edit, suspend, and deactivate user accounts and assign each user to an MDA and role.	Must
FR-UAM-03	Each user shall be scoped to data belonging to their MDA, except where cross-MDA access is explicitly granted.	Must
FR-UAM-04	Authentication shall enforce strong passwords and support multi-factor authentication (MFA) for administrative and executive roles.	Must
FR-UAM-05	Permissions shall be configurable at module and action level (view, create, edit, approve, export).	Should
FR-UAM-06	The system shall enforce session timeout and lock accounts after repeated failed login attempts.	Should

7.2 Beneficiary Registry (Hybrid)
ID	Requirement	Priority
FR-REG-01	The system shall support both individual-based and household-based registration.	Must
FR-REG-02	The system shall ingest beneficiary data from SOCU, Kobo Collect, ODK, Excel/CSV upload, REST API, and existing government systems.	Must
FR-REG-03	Each beneficiary record shall store registration source, owner MDA, registration date, import batch, and original record ID.	Must
FR-REG-04	The system shall capture core identity fields: NIN, BVN, phone number, full name, date of birth, gender, address, LGA/Ward, and household reference.	Must
FR-REG-05	The system shall validate mandatory fields and data formats on entry and import, and reject or flag invalid records with an error report.	Must
FR-REG-06	Bulk import shall provide a preview, a validation summary, and row-level error reporting before records are committed.	Should
FR-REG-07	The system shall allow supporting documents to be attached to a beneficiary record.	Should
FR-REG-08	The system shall support offline data capture that synchronizes when connectivity is restored.	Could

7.3 Duplicate Verification
ID	Requirement	Priority
FR-DUP-01	The system shall run a duplicate check before persisting any new beneficiary.	Must
FR-DUP-02	Matching rules shall be configurable across NIN, BVN, phone, full name, date of birth, gender, address, and household information.	Must
FR-DUP-03	The system shall support deterministic matching (e.g. exact NIN/BVN) and fuzzy matching (e.g. name/date of birth) with configurable thresholds.	Must
FR-DUP-04	When a match is found, the system shall display the existing beneficiary name and ID, owner MDA(s), data source, programme(s), registration date, LGA/Ward, and status (services and benefits received).	Must
FR-DUP-05	The requesting MDA shall be able to request to provide services to the existing beneficiary instead of creating a duplicate record.	Must
FR-DUP-06	Every duplicate decision (keep, link, request service) shall be recorded in the audit log.	Should
FR-DUP-07	The system shall support AI-assisted match scoring.	Could

7.4 Beneficiary Ownership
ID	Requirement	Priority
FR-OWN-01	The first MDA to register a beneficiary shall be set as the Owner MDA.	Must
FR-OWN-02	Only the Owner MDA shall be able to edit the core beneficiary profile.	Must
FR-OWN-03	Other MDAs shall be able to request service access without transferring ownership.	Must
FR-OWN-04	The system shall be able to automatically assign or route a beneficiary to an MDA whose programme matches an identified need.	Should
FR-OWN-05	Ownership transfer shall require Owner MDA approval and shall be logged.	Should

7.5 Referral & Linkage
ID	Requirement	Priority
FR-REF-01	An MDA shall be able to create a referral to another MDA, specifying the beneficiary, the identified need, and notes.	Must
FR-REF-02	The receiving MDA shall be able to accept, reject (with reason), or request more information.	Must
FR-REF-03	The service outcome shall be recorded against the referral; ownership shall remain with the originating MDA.	Must
FR-REF-04	Referral status shall be tracked through its lifecycle (Created → Accepted → In Progress → Completed/Closed) with timestamps and configurable SLAs.	Should
FR-REF-05	Both MDAs shall be notified at each referral status change.	Should

7.6 Benefit Tracking (Benefit Ledger)
ID	Requirement	Priority
FR-BEN-01	The system shall maintain a complete benefit history for each beneficiary.	Must
FR-BEN-02	Each benefit record shall store programme, activity, MDA, benefit type, quantity, monetary value, funding source, delivery date, status, and verification method.	Must
FR-BEN-03	The benefit ledger shall be viewable per beneficiary and aggregatable by programme, MDA, and LGA/Ward.	Must
FR-BEN-04	The system shall support verification of delivery (e.g. field-officer confirmation, OTP, signature, or biometric reference).	Should
FR-BEN-05	The system shall flag potential double-dipping where the same benefit type is delivered by multiple MDAs within a defined period.	Should

7.7 Programme & Activity Management
ID	Requirement	Priority
FR-PRG-01	An MDA shall be able to create and configure programmes (name, objective, type, eligibility criteria, funding source, budget, period).	Must
FR-PRG-02	An MDA shall be able to create activities under a programme with targets, location, schedule, and budget.	Must
FR-PRG-03	The system shall allow beneficiaries to be enrolled or assigned to programmes and activities.	Should
FR-PRG-04	The system shall track budget allocated versus utilized per programme and activity.	Should

7.8 Grievance Redress (GRM)
ID	Requirement	Priority
FR-GRM-01	The system shall capture grievances with category, channel, beneficiary link, and description.	Must
FR-GRM-02	Grievances shall be assignable and trackable through to resolution, with resolution notes and timestamps.	Must
FR-GRM-03	The system shall track SLAs and escalate overdue grievances.	Should

7.9 Graduation Management
ID	Requirement	Priority
FR-GRD-01	The system shall allow graduation criteria to be defined per programme.	Should
FR-GRD-02	The system shall track beneficiary progress toward graduation and record graduation events.	Should

7.10 Notifications
ID	Requirement	Priority
FR-NOT-01	The system shall provide in-app notifications for referrals, approvals, grievances, and key system events.	Must
FR-NOT-02	The system shall support email notifications, with SMS/WhatsApp in a later phase.	Should

7.11 Reporting & Analytics
ID	Requirement	Priority
FR-RPT-01	The system shall provide an executive dashboard with real-time KPIs (beneficiaries, programmes, benefits disbursed, coverage by LGA).	Must
FR-RPT-02	The system shall provide MDA and Partner dashboards scoped to permitted data.	Must
FR-RPT-03	The system shall generate standard and ad-hoc reports and export to PDF, Excel, and CSV.	Must
FR-RPT-04	The system shall support scheduled, automated report generation and distribution.	Should

7.12 GIS Dashboard
ID	Requirement	Priority
FR-GIS-01	The system shall map beneficiaries, programmes, and coverage by LGA/Ward.	Should
FR-GIS-02	The system shall provide heat maps of coverage and need.	Could

7.13 Audit, Data Sharing & Synchronization
ID	Requirement	Priority
FR-AUD-01	The system shall keep an immutable audit log of all create, edit, delete, access, and approval actions, recording user, timestamp, and before/after values.	Must
FR-DSH-01	Data sharing between MDAs shall be controlled and governed by ownership and consent rules.	Must
FR-DSH-02	The system shall synchronize data with external and source systems on a schedule or trigger.	Should
8. Key User Flows
8.1 Register a Beneficiary with Duplicate Check
An MDA officer upload beneficiary data from data sources like excel, CSV, SOCU, Kobo Collect, ODK, Rest API and any other existing government system
The system runs duplicate verification against configured matching rules.
If no match: the record is saved and the MDA becomes the Owner MDA.
If a match is found: the system displays the existing beneficiary, owner MDA, source, programmes, and benefits received.
The officer either links to the existing record and requests to provide a service, or (with justification) proceeds appropriately. The decision is logged.
8.2 Refer a Beneficiary to Another MDA
Owner MDA identifies a need outside its mandate (e.g. Women Affairs identifies a health need).
A referral is created to the relevant MDA (e.g. Ministry of Health) with the need and notes.
The receiving MDA accepts, rejects (with reason), or requests more information.
On acceptance, the receiving MDA delivers the service and records the outcome.
Ownership remains with the originating MDA; both parties are notified at each step.
8.3 Record a Benefit Delivery
An MDA selects a beneficiary and the relevant programme/activity.
The officer records benefit type, quantity, monetary value, funding source, and delivery date.
Delivery is verified and the status is set.
The benefit is appended to the beneficiary's ledger and rolls up into programme, MDA, and LGA reporting.
9. Data Model (Key Entities)
The following core entities and relationships describe the conceptual data model. Detailed schema design will follow in technical design.
Entity	Description	Key Attributes
Beneficiary	Individual served by one or more programmes.	ID, NIN, BVN, name, DOB, gender, phone, address, LGA/Ward, owner MDA, source, registration date, status.
Household	Optional grouping of beneficiaries.	Household ID, head, members, address, LGA/Ward.
MDA	Ministry, Department, or Agency.	ID, name, type, contact, programmes owned.
Programme	A social protection programme run by an MDA.	ID, name, type (HH/individual), eligibility, funding source, budget, period.
Activity	A unit of work under a programme.	ID, programme, target, location, schedule, budget.
Benefit	A benefit delivered to a beneficiary.	ID, beneficiary, programme, activity, MDA, type, quantity, value, funding source, delivery date, status, verification.
Referral	A request to serve a beneficiary across MDAs.	ID, beneficiary, from MDA, to MDA, need, status, outcome, timestamps.
Grievance	A complaint or query.	ID, beneficiary, category, channel, status, resolution, timestamps.
User	A system user.	ID, name, MDA, role, permissions, status.
Audit Log	Immutable record of actions.	ID, user, action, entity, before/after, timestamp.
10. Integration Requirements
Data capture tools: Kobo Collect and ODK form submissions ingested via export or API.
File-based import: Excel/CSV upload with validation and batch tracking.
SOCU and existing government systems: import and periodic synchronization of beneficiary data.
Open API: a documented REST API for inbound registration and outbound reporting, secured by authentication and rate limiting.
Identity (future): integration with national identity (e.g. NIMC/NIN) to strengthen duplicate verification, subject to authorization.
Messaging (future): SMS/WhatsApp gateway for notifications and beneficiary engagement.



11. Non-Functional Requirements
Targets marked “proposed” are recommended baselines to be confirmed during planning.
ID	Requirement	Priority
NFR-SEC-01	All data shall be encrypted in transit (TLS 1.2+) and at rest. Passwords shall be securely hashed. The application shall follow secure-development practices addressing the OWASP Top 10.	Must
NFR-SEC-02	Privileged and executive accounts shall require MFA; access shall follow least privilege.	Must
NFR-PRV-01	The system shall comply with applicable data protection law (e.g. NDPA/NDPR), capture consent where required, minimize PII, and enforce a defined data-retention policy.	Must
NFR-PERF-01	Standard pages shall load within 3 seconds, and duplicate verification shall return results within 5 seconds, under normal load (proposed).	Should
NFR-SCAL-01	The system shall scale to millions of beneficiary records and at least 500 concurrent users via horizontal scaling (proposed).	Should
NFR-AVAIL-01	The system shall target 99.5% availability, with daily backups and defined RPO/RTO (proposed).	Should
NFR-USE-01	The interface shall be responsive (desktop and mobile browsers), accessible, and usable by non-technical MDA staff with minimal training.	Should
NFR-AUD-01	All security-relevant and data-changing actions shall be auditable and tamper-evident.	Must
NFR-INT-01	The system shall be API-first and interoperable to support current and future integrations.	Should
NFR-MAINT-01	The system shall be containerized for consistent deployment and maintainability.	Should
12. Technical Architecture
The recommended stack is a modern, API-first web application designed for scale, background processing, and geospatial reporting.
Layer	Technology	Purpose
Backend / API	Laravel 12 (PHP)	Business logic, REST API, authentication, RBAC.
Frontend	React + TypeScript	Single-page application for all dashboards and workflows.
Database	PostgreSQL + PostGIS	Relational data plus geospatial queries for GIS mapping.
Caching	Redis	Performance caching and session/state support.
Queue / Workers	RabbitMQ	Asynchronous jobs: bulk imports, duplicate matching, notifications, synchronization.
Deployment	Docker (containers)	Consistent, portable, scalable deployment.

Architectural notes: The API-first design lets data capture tools, partner systems, and future channels integrate cleanly. Heavy operations (imports, fuzzy matching, report generation) run on queue workers so the user interface stays responsive. PostGIS supports LGA/Ward mapping today and heat maps later.
13. Success Metrics
The project will be judged on the outcomes below. Targets are proposed starting points to be agreed with stakeholders and baselined before launch.
Metric	Proposed Target
Reduction in duplicate registrations	≥ 80% reduction vs. baseline within 12 months
MDA adoption	≥ 90% of participating MDAs actively using the platform within 6 months
Referral completion rate	≥ 70% of accepted referrals completed
Benefit history coverage	100% of recorded benefits captured in the ledger
Executive dashboard freshness	Real-time / within 24 hours
Report automation	≥ 80% of standard reports generated automatically
Data quality	≥ 95% of records passing validation rules
14. Assumptions, Dependencies & Constraints
14.1 Assumptions
Participating MDAs will adopt SP-MIS as the system of record for social protection beneficiaries and benefits.
Source data (SOCU, Kobo/ODK, Excel) is available and reasonably complete for initial loading.
A governance/legal framework authorizes data sharing across MDAs.


14.2 Dependencies
Availability of hosting/infrastructure (cloud or on-premises) and connectivity at MDA sites.
Access to identity references (NIN/BVN) and, for future phases, integration approval from identity authorities.
Stakeholder availability to define matching thresholds, eligibility criteria, and SLAs.
14.3 Constraints
Intermittent connectivity at some field locations (mitigated by offline capture in a later phase).
Compliance with applicable data protection law and government IT policy.
15. Risks & Mitigations
Risk	Impact	Mitigation
Low MDA adoption / change resistance	High	Executive sponsorship, training, phased rollout, clear ownership model that protects MDA control.
Poor source data quality	High	Validation on import, error reporting, data-cleaning support, staged onboarding.
Identity matching errors (false matches/misses)	Medium	Configurable thresholds, human review of probable matches, deterministic checks on NIN/BVN.
Data privacy / breach	High	Encryption, RBAC, MFA, audit logs, NDPA/NDPR compliance, consent capture.
Connectivity limitations in the field	Medium	Offline capture and sync in a later phase; lightweight UI.
Funding / sustainability	Medium	Phased delivery to show early value; open standards to avoid lock-in.






16. Release Plan & Future Enhancements
16.1 Phased Roadmap
Phase	Focus	Key Capabilities
Phase 1 (MVP)	Core registry & coordination	User/RBAC, hybrid registry (manual, Excel, Kobo/ODK), duplicate verification, ownership, basic benefit tracking, MDA & executive dashboards, audit logs.
Phase 2	Coordination & reporting depth	Referrals, grievance redress, programme/activity management, partner dashboard, reporting & exports, email notifications, basic GIS.
Phase 3	Integration & reach	Graduation management, GIS heat maps, API/identity integration, SMS/WhatsApp, scheduled reports, offline capture.
Phase 4 (Future)	Intelligence	AI-assisted duplicate detection, predictive analytics, national identity integration.

16.2 Future Enhancements
AI-assisted duplicate detection.
WhatsApp and SMS integration.
National identity integration.
GIS heat maps.
Predictive analytics.
16.3 Expected Benefits
Stronger coordination across MDAs and reduced duplication.
Greater transparency, accountability, and budget efficiency.
Improved service delivery and referral outcomes.
Evidence-based planning, monitoring, and policy decisions.




17. Glossary
Term	Meaning
SP-MIS	Social Protection Management Information System.
MDA	Ministry, Department, or Agency.
Owner MDA	The MDA that first registers a beneficiary and controls the core profile.
SOCU	Social Operations / data source feeding beneficiary data.
Kobo Collect / ODK	Mobile data-collection tools used for field registration.
NIN / BVN	National Identification Number / Bank Verification Number, used for identity matching.
LGA / Ward	Local Government Area / Ward — administrative geography for coverage.
GRM	Grievance Redress Mechanism.
RBAC	Role-Based Access Control.
MoSCoW	Prioritization scheme: Must / Should / Could / Won't have.
RPO / RTO	Recovery Point Objective / Recovery Time Objective for backups and recovery.
