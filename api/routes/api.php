<?php

declare(strict_types=1);

use App\Domain\Access\Support\TokenAbility;
use App\Http\Controllers\Api\V1\Access\AccessController;
use App\Http\Controllers\Api\V1\Access\LoginActivityController;
use App\Http\Controllers\Api\V1\Access\MdaAccessGrantController;
use App\Http\Controllers\Api\V1\Access\MdaController;
use App\Http\Controllers\Api\V1\Access\UserController;
use App\Http\Controllers\Api\V1\Audit\AuditLogController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Benefit\BenefitController;
use App\Http\Controllers\Api\V1\Benefit\BenefitFlagController;
use App\Http\Controllers\Api\V1\Benefit\BenefitImportController;
use App\Http\Controllers\Api\V1\Benefit\DoubleDippingRuleController;
use App\Http\Controllers\Api\V1\Graduation\GraduationController;
use App\Http\Controllers\Api\V1\Grievance\GrievanceController;
use App\Http\Controllers\Api\V1\Grievance\GrievanceSlaPolicyController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\Matching\MatchingConfigController;
use App\Http\Controllers\Api\V1\MfaController;
use App\Http\Controllers\Api\V1\Notification\BroadcastController;
use App\Http\Controllers\Api\V1\Notification\NotificationController;
use App\Http\Controllers\Api\V1\Programme\ActivityController;
use App\Http\Controllers\Api\V1\Programme\EnrollmentController;
use App\Http\Controllers\Api\V1\Programme\ProgrammeController;
use App\Http\Controllers\Api\V1\Reference\AdministrativeDivisionController;
use App\Http\Controllers\Api\V1\Referral\ReferralController;
use App\Http\Controllers\Api\V1\Referral\ReferralSlaPolicyController;
use App\Http\Controllers\Api\V1\Registry\ActivityImportController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryDocumentController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryIntakeController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryRoutingController;
use App\Http\Controllers\Api\V1\Registry\DuplicateQueueController;
use App\Http\Controllers\Api\V1\Registry\HouseholdController;
use App\Http\Controllers\Api\V1\Registry\HouseholdMemberController;
use App\Http\Controllers\Api\V1\Registry\ImportBatchController;
use App\Http\Controllers\Api\V1\Registry\OwnershipTransferController;
use App\Http\Controllers\Api\V1\Registry\RegistryRulesController;
use App\Http\Controllers\Api\V1\Registry\ServiceRequestController;
use App\Http\Controllers\Api\V1\Reporting\AdHocReportController;
use App\Http\Controllers\Api\V1\Reporting\AdminOrganizationController;
use App\Http\Controllers\Api\V1\Reporting\AdminSettingsController;
use App\Http\Controllers\Api\V1\Reporting\AdminSummaryController;
use App\Http\Controllers\Api\V1\Reporting\DashboardController;
use App\Http\Controllers\Api\V1\Reporting\DashboardExportController;
use App\Http\Controllers\Api\V1\Reporting\GisController;
use App\Http\Controllers\Api\V1\Reporting\MdaActionRequiredController;
use App\Http\Controllers\Api\V1\Reporting\ReportController;
use App\Http\Controllers\Api\V1\Reporting\ReportDefinitionController;
use App\Http\Controllers\Api\V1\Reporting\ReportScheduleController;
use App\Http\Controllers\Api\V1\Reporting\SegmentReportController;
use App\Http\Controllers\Api\V1\Sharing\DataSharingController;
use App\Http\Controllers\Api\V1\Sync\SyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
|
| All SP-MIS endpoints live under the /api/v1 prefix (URI versioning).
| Keep new resources inside this group so a future v2 can coexist.
|
*/

Route::prefix('v1')->group(function (): void {
    Route::get('/health', [HealthController::class, 'show'])->name('health');

    Route::prefix('auth')->group(function (): void {
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:login')
            ->name('auth.login');

        // MFA challenge: only the short-lived challenge token may call this.
        Route::post('/mfa/challenge', [MfaController::class, 'challenge'])
            ->middleware(['auth:sanctum', 'ability:'.TokenAbility::MFA_CHALLENGE, 'throttle:mfa'])
            ->name('auth.mfa.challenge');

        // MFA enrol/verify: a full token (opting in) OR a setup token (required
        // role completing first-time setup) may call these.
        Route::middleware(['auth:sanctum', 'ability:'.TokenAbility::MFA_SETUP])->group(function (): void {
            Route::post('/mfa/enroll', [MfaController::class, 'enroll'])->name('auth.mfa.enroll');
            Route::post('/mfa/verify', [MfaController::class, 'verify'])->name('auth.mfa.verify');
        });

        // Fully-authenticated endpoints (full token only) + idle-timeout guard.
        Route::middleware(['idle.timeout', 'auth:sanctum', 'ability:'.TokenAbility::FULL])->group(function (): void {
            Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('/password', [AuthController::class, 'changePassword'])->name('auth.password');
            Route::post('/mfa/disable', [MfaController::class, 'disable'])->name('auth.mfa.disable');
        });
    });

    /*
    | RBAC administration (read-only). Every endpoint declares the permission it
    | requires; the `permission` middleware denies by default (FR-UAM-05).
    */
    Route::middleware(['idle.timeout', 'auth:sanctum', 'ability:'.TokenAbility::FULL])->group(function (): void {
        Route::get('/permissions', [AccessController::class, 'permissions'])
            ->middleware('permission:permission.view')->name('permissions.index');

        Route::get('/roles', [AccessController::class, 'roles'])
            ->middleware('permission:role.view')->name('roles.index');

        Route::get('/access/matrix', [AccessController::class, 'matrix'])
            ->middleware('permission:permission.view')->name('access.matrix');

        /*
        | Reference data — Jigawa LGAs and their wards (the cascading selector).
        |
        | Authenticated but NOT permission-gated, and that is deliberate: this is a
        | non-PII, MDA-independent lookup list that every role needs to render a form
        | or a filter. A permission granted to all six roles would deny nothing while
        | making the RBAC set look like it draws a distinction here.
        |
        | Read-only — the list comes from an authoritative dataset loaded by a
        | maintainer (`php artisan reference:load-divisions`), never from the API.
        */
        Route::get('/reference/lgas', [AdministrativeDivisionController::class, 'lgas'])
            ->name('reference.lgas');
        Route::get('/reference/wards', [AdministrativeDivisionController::class, 'wards'])
            ->name('reference.wards');

        // MDA management (PRD FR-UAM-02). List/show are MDA-scoped.
        Route::get('/mdas', [MdaController::class, 'index'])
            ->middleware('permission:mda.view')->name('mdas.index');
        Route::post('/mdas', [MdaController::class, 'store'])
            ->middleware('permission:mda.create')->name('mdas.store');
        Route::get('/mdas/{mda}', [MdaController::class, 'show'])
            ->middleware('permission:mda.view')->name('mdas.show');
        Route::match(['put', 'patch'], '/mdas/{mda}', [MdaController::class, 'update'])
            ->middleware('permission:mda.edit')->name('mdas.update');
        Route::post('/mdas/{mda}/deactivate', [MdaController::class, 'deactivate'])
            ->middleware('permission:mda.edit')->name('mdas.deactivate');
        Route::post('/mdas/{mda}/activate', [MdaController::class, 'activate'])
            ->middleware('permission:mda.edit')->name('mdas.activate');

        // User management (PRD FR-UAM-02, FR-UAM-03). List/show are MDA-scoped.
        Route::get('/users', [UserController::class, 'index'])
            ->middleware('permission:user.view')->name('users.index');
        Route::post('/users', [UserController::class, 'store'])
            ->middleware('permission:user.create')->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->middleware('permission:user.view')->name('users.show');
        Route::match(['put', 'patch'], '/users/{user}', [UserController::class, 'update'])
            ->middleware('permission:user.edit')->name('users.update');
        Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])
            ->middleware('permission:user.edit')->name('users.suspend');
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->middleware('permission:user.edit')->name('users.deactivate');
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])
            ->middleware('permission:user.edit')->name('users.activate');
        Route::post('/users/{user}/force-password-reset', [UserController::class, 'forcePasswordReset'])
            ->middleware('permission:user.edit')->name('users.force-password-reset');
        Route::post('/users/{user}/reset-mfa', [UserController::class, 'resetMfa'])
            ->middleware('permission:user.edit')->name('users.reset-mfa');

        // Cross-MDA access grants (admin-managed, logged).
        Route::get('/mda-access-grants', [MdaAccessGrantController::class, 'index'])
            ->middleware('permission:mda-access.view')->name('mda-access-grants.index');
        Route::post('/mda-access-grants', [MdaAccessGrantController::class, 'store'])
            ->middleware('permission:mda-access.create')->name('mda-access-grants.store');
        Route::delete('/mda-access-grants/{grant}', [MdaAccessGrantController::class, 'destroy'])
            ->middleware('permission:mda-access.edit')->name('mda-access-grants.destroy');

        // Duplicate-matching configuration (PRD FR-DUP-02/03) — admin-managed, versioned.
        Route::get('/matching/config', [MatchingConfigController::class, 'show'])
            ->middleware('permission:matching.view')->name('matching.config.show');
        Route::put('/matching/config', [MatchingConfigController::class, 'update'])
            ->middleware('permission:matching.edit')->name('matching.config.update');
        Route::get('/matching/config/versions', [MatchingConfigController::class, 'versions'])
            ->middleware('permission:matching.view')->name('matching.config.versions');

        /*
        | Beneficiary registry (PRD FR-OWN). Owner-only edit is enforced by the
        | BeneficiaryPolicy; the lookup/serve seam is a distinct, permission-gated
        | cross-MDA path that bypasses the owner scope but exposes reveal fields only.
        */
        // Declared before the wildcard so `lookup`/`search`/`imports` are never treated as ids.
        Route::get('/beneficiaries/lookup', [BeneficiaryController::class, 'lookup'])
            ->middleware('permission:beneficiary-lookup.view')->name('beneficiaries.lookup');
        // Fuzzy "serve many" duplicate search — same engine, reveal-only (FR-DUP-04).
        Route::get('/beneficiaries/search', [BeneficiaryController::class, 'search'])
            ->middleware('permission:beneficiary-lookup.view')->name('beneficiaries.search');

        // Bulk import (Excel/CSV) — upload → preview → confirm → commit (FR-REG-02/06).
        /*
        | The duplicate QUEUE: flagged rows across every import this MDA owns, paginated
        | server-side (FR-DUP-01/05). Distinct from the per-batch view below — the
        | console used to build this by fetching batches and flattening in the browser,
        | which could only ever reach the first page.
        */
        Route::get('/beneficiaries/duplicates', [DuplicateQueueController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.duplicates');
        Route::get('/beneficiaries/imports', [ImportBatchController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.imports.index');
        Route::post('/beneficiaries/imports', [ImportBatchController::class, 'store'])
            ->middleware(['permission:beneficiary.create', 'throttle:imports'])->name('beneficiaries.imports.store');
        Route::get('/beneficiaries/imports/{batch}', [ImportBatchController::class, 'show'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.imports.show');
        // Resolve a flagged row: new (with justification) / link-serve / skip (FR-DUP-05).
        /*
        | Data Import & Mapping (CLAUDE.md §11). Between raw upload and validation:
        | the officer confirms which source column holds each canonical field. NIN,
        | BVN, name and phone must be answered explicitly on every import — a saved
        | template pre-fills the proposal but never satisfies the confirmation.
        */
        Route::get('/beneficiaries/imports/{batch}/mapping', [ImportBatchController::class, 'mapping'])
            ->middleware('permission:beneficiary.view')->name('imports.mapping.show');
        Route::put('/beneficiaries/imports/{batch}/mapping', [ImportBatchController::class, 'confirmMapping'])
            ->middleware('permission:beneficiary.create')->name('imports.mapping.confirm');

        Route::post('/beneficiaries/imports/{batch}/rows/{rowNumber}/resolve', [ImportBatchController::class, 'resolveRow'])
            ->middleware('permission:beneficiary.create')->name('beneficiaries.imports.rows.resolve');
        Route::post('/beneficiaries/imports/{batch}/confirm', [ImportBatchController::class, 'confirm'])
            ->middleware('permission:beneficiary.create')->name('beneficiaries.imports.confirm');

        // Service Request (§12, FR-OWN-06/07): a non-owner MDA raises a request;
        // the OWNER MDA accepts (opening a read-access grant) or declines (reason
        // required). Ownership never changes. Inbox = routed to me; outbox = raised
        // by me. Distinct from the Referral flow.
        Route::post('/service-requests', [ServiceRequestController::class, 'store'])
            ->middleware('permission:beneficiary.create')->name('service-requests.store');
        Route::get('/service-requests/inbox', [ServiceRequestController::class, 'inbox'])
            ->middleware('permission:beneficiary.view')->name('service-requests.inbox');
        Route::get('/service-requests/outbox', [ServiceRequestController::class, 'outbox'])
            ->middleware('permission:beneficiary.view')->name('service-requests.outbox');
        Route::post('/service-requests/{serviceRequest}/accept', [ServiceRequestController::class, 'accept'])
            ->middleware('permission:beneficiary.approve')->name('service-requests.accept');
        Route::post('/service-requests/{serviceRequest}/decline', [ServiceRequestController::class, 'decline'])
            ->middleware('permission:beneficiary.approve')->name('service-requests.decline');
        /*
        | Withdraw the read grant an acceptance opened (FR-OWN-07). No `permission:`
        | middleware here: two different capabilities may revoke — the owner MDA via
        | `beneficiary.approve` and a System Administrator via `mda-access.edit` — and
        | route middleware can only require one. OwnerMdaPolicy::revoke authorizes both.
        */
        Route::post('/service-grants/{grant}/revoke', [ServiceRequestController::class, 'revoke'])
            ->name('service-grants.revoke');
        // Who holds cross-MDA access to MY beneficiary. Distinct from the platform-wide
        // /data-sharing/grants oversight view (cross-mda.view, which no MDA role holds):
        // this is the owner's own record, and an owner must see access to exercise
        // revocation over it. OwnerMdaPolicy::viewGrants bounds it to the owner.
        Route::get('/beneficiaries/{beneficiary}/service-grants', [ServiceRequestController::class, 'grants'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.service-grants');

        // Inbound REST registration intake (FR-REG-02, source=api) — rate limited.
        Route::post('/beneficiaries/intake', [BeneficiaryIntakeController::class, 'store'])
            ->middleware(['permission:beneficiary.create', 'throttle:registration-intake'])
            ->name('beneficiaries.intake');

        Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.index');
        // Export the current filtered list (CSV/Excel) via the shared Phase 6
        // exporters. Declared before the /{beneficiary} wildcard so `export` is a
        // literal segment, not a record id.
        Route::get('/beneficiaries/export', [BeneficiaryController::class, 'export'])
            ->middleware(['permission:beneficiary.export', 'throttle:exports'])->name('beneficiaries.export');
        // NOTE: no manual create endpoint — beneficiaries enter only via source
        // ingestion (bulk import + REST intake). See docs/registry-intake.md.
        Route::get('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'show'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.show');
        // Cross-MDA data-sharing consent (NFR-PRV-01) — owner MDA records grant/withdraw.
        Route::put('/beneficiaries/{beneficiary}/consent', [BeneficiaryController::class, 'consent'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.consent');
        // Right-of-access (DSAR, NFR-PRV-01): export the subject's full record + history.
        // Distinct permission (data-controller obligation), rate-limited like exports.
        Route::get('/beneficiaries/{beneficiary}/access-request', [BeneficiaryController::class, 'accessRequest'])
            ->middleware(['permission:beneficiary.access_request', 'throttle:exports'])->name('beneficiaries.access-request');
        Route::match(['put', 'patch'], '/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.update');
        Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.destroy');

        // Data-sharing oversight (FR-DSH-01): who can access what across MDAs, and why.
        Route::get('/data-sharing/grants', [DataSharingController::class, 'grants'])
            ->middleware('permission:cross-mda.view')->name('data-sharing.grants');

        /*
        | Data synchronization (FR-DSH-02, FR-REG-08). Connector sync + logs are
        | System-Admin territory (sync.view/run); offline-batch flush is done by the
        | capturing MDA officer (beneficiary.create). Every record runs the SAME
        | validation + dedup + ownership pipeline as import.
        */
        Route::get('/sync/connectors', [SyncController::class, 'connectors'])
            ->middleware('permission:sync.view')->name('sync.connectors');
        Route::get('/sync/runs', [SyncController::class, 'runs'])
            ->middleware('permission:sync.view')->name('sync.runs');
        Route::get('/sync/runs/{run}', [SyncController::class, 'run'])
            ->middleware('permission:sync.view')->name('sync.runs.show');
        /*
        | A connector's standing column mapping (CLAUDE.md §11). Unattended ingestion
        | cannot ask "which field is the NIN" on every run, so the confirmation is given
        | once here and bounded by the source's shape — if its fields change, the
        | connector stops until someone re-confirms.
        */
        Route::get('/sync/connectors/{connector}/mapping', [SyncController::class, 'mapping'])
            ->middleware('permission:sync.view')->name('sync.connectors.mapping');
        Route::put('/sync/connectors/{connector}/mapping', [SyncController::class, 'confirmMapping'])
            ->middleware('permission:sync.run')->name('sync.connectors.mapping.confirm');
        Route::put('/sync/connectors/{connector}/activity', [SyncController::class, 'setActivity'])
            ->middleware('permission:sync.run')->name('sync.connectors.activity');

        // Enabling is refused while the mapping is unconfirmed or stale — the same guard
        // as the run, applied where the decision is actually made.
        Route::put('/sync/connectors/{connector}/enabled', [SyncController::class, 'setEnabled'])
            ->middleware('permission:sync.run')->name('sync.connectors.enabled');

        Route::post('/sync/connectors/{connector}/run', [SyncController::class, 'trigger'])
            ->middleware('permission:sync.run')->name('sync.connectors.run');
        Route::post('/sync/offline-batches', [SyncController::class, 'offlineBatch'])
            ->middleware(['permission:beneficiary.create', 'throttle:imports'])->name('sync.offline-batches');

        /*
        | Graduation management (FR-GRD-01, FR-GRD-02). Per-programme criteria are
        | admin-editable config (graduation.edit); progress is tracked against real
        | ledger/enrolment data; recording a graduation flips the ENROLMENT status but
        | NEVER deletes the beneficiary or their ledger — the history is preserved.
        */
        Route::get('/programmes/{programme}/graduation-criteria', [GraduationController::class, 'criteriaIndex'])
            ->middleware('permission:graduation.view')->name('graduation.criteria.index');
        Route::post('/programmes/{programme}/graduation-criteria', [GraduationController::class, 'criteriaStore'])
            ->middleware('permission:graduation.edit')->name('graduation.criteria.store');
        Route::match(['put', 'patch'], '/graduation-criteria/{criterion}', [GraduationController::class, 'criteriaUpdate'])
            ->middleware('permission:graduation.edit')->name('graduation.criteria.update');
        Route::delete('/graduation-criteria/{criterion}', [GraduationController::class, 'criteriaDestroy'])
            ->middleware('permission:graduation.edit')->name('graduation.criteria.destroy');

        Route::get('/enrollments/{enrollment}/graduation', [GraduationController::class, 'progress'])
            ->middleware('permission:graduation.view')->name('graduation.progress');
        Route::post('/enrollments/{enrollment}/graduate', [GraduationController::class, 'graduate'])
            ->middleware('permission:graduation.edit')->name('graduation.graduate');
        Route::get('/graduation-events', [GraduationController::class, 'history'])
            ->middleware('permission:graduation.view')->name('graduation.events');

        // Supporting documents (FR-REG-07): owner-only upload/delete, in-scope
        // list/download. Files are streamed via the download action, never static.
        Route::get('/beneficiaries/{beneficiary}/documents', [BeneficiaryDocumentController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.documents.index');
        Route::post('/beneficiaries/{beneficiary}/documents', [BeneficiaryDocumentController::class, 'store'])
            ->middleware(['permission:beneficiary.edit', 'throttle:imports'])->name('beneficiaries.documents.store');
        Route::get('/beneficiaries/{beneficiary}/documents/{document}/download', [BeneficiaryDocumentController::class, 'download'])
            ->middleware('permission:beneficiary.view', 'throttle:exports')->name('beneficiaries.documents.download');
        Route::delete('/beneficiaries/{beneficiary}/documents/{document}', [BeneficiaryDocumentController::class, 'destroy'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.documents.destroy');

        // Ownership transfer workflow (FR-OWN-05): request → owner approval.
        Route::post('/beneficiaries/{beneficiary}/ownership-transfers', [OwnershipTransferController::class, 'store'])
            ->middleware('permission:beneficiary.approve')->name('ownership-transfers.store');
        Route::post('/ownership-transfers/{transfer}/approve', [OwnershipTransferController::class, 'approve'])
            ->middleware('permission:beneficiary.approve')->name('ownership-transfers.approve');
        Route::post('/ownership-transfers/{transfer}/reject', [OwnershipTransferController::class, 'reject'])
            ->middleware('permission:beneficiary.approve')->name('ownership-transfers.reject');

        /*
        | Households (PRD FR-REG-01 household path, §9). Owner-only mutation via
        | HouseholdPolicy; membership changes preserve history (household_memberships).
        */
        Route::get('/households', [HouseholdController::class, 'index'])
            ->middleware('permission:household.view')->name('households.index');
        // NOTE: no manual create endpoint — households are formed by source
        // ingestion from the household-reference field (see HouseholdIngestionService).
        Route::get('/households/{household}', [HouseholdController::class, 'show'])
            ->middleware('permission:household.view')->name('households.show');
        Route::match(['put', 'patch'], '/households/{household}', [HouseholdController::class, 'update'])
            ->middleware('permission:household.edit')->name('households.update');
        Route::delete('/households/{household}', [HouseholdController::class, 'destroy'])
            ->middleware('permission:household.edit')->name('households.destroy');
        Route::post('/households/{household}/head', [HouseholdController::class, 'designateHead'])
            ->middleware('permission:household.edit')->name('households.head');

        // Membership lifecycle: add / move (with history) / remove.
        Route::post('/households/{household}/members', [HouseholdMemberController::class, 'store'])
            ->middleware('permission:household.edit')->name('households.members.store');
        Route::post('/households/{household}/members/move', [HouseholdMemberController::class, 'move'])
            ->middleware('permission:household.edit')->name('households.members.move');
        Route::delete('/households/{household}/members/{beneficiary}', [HouseholdMemberController::class, 'destroy'])
            ->middleware('permission:household.edit')->name('households.members.destroy');

        /*
        | Programmes & activities (PRD FR-PRG-01/02). List/show are MDA-scoped
        | (oversight sees all); create/update/archive are owner-MDA only via policy.
        */
        Route::get('/programmes', [ProgrammeController::class, 'index'])
            ->middleware('permission:programme.view')->name('programmes.index');
        // The archive, for audit + historical reporting. Declared BEFORE
        // /programmes/{programme} so "archived" is not swallowed as an id.
        Route::get('/programmes/archived', [ProgrammeController::class, 'archived'])
            ->middleware('permission:programme.view')->name('programmes.archived');
        Route::post('/programmes', [ProgrammeController::class, 'store'])
            ->middleware('permission:programme.create')->name('programmes.store');
        Route::get('/programmes/{programme}/budget', [ProgrammeController::class, 'budget'])
            ->middleware('permission:programme.view')->name('programmes.budget');
        Route::get('/programmes/{programme}', [ProgrammeController::class, 'show'])
            ->middleware('permission:programme.view')->name('programmes.show');
        Route::match(['put', 'patch'], '/programmes/{programme}', [ProgrammeController::class, 'update'])
            ->middleware('permission:programme.edit')->name('programmes.update');
        // Archive IS the delete for a programme — it carries activities, ledger and
        // graduation history, so nothing is ever destroyed (PRD §10). There is
        // deliberately no DELETE route here.
        Route::post('/programmes/{programme}/archive', [ProgrammeController::class, 'archive'])
            ->middleware('permission:programme.edit')->name('programmes.archive');
        Route::post('/programmes/{programme}/unarchive', [ProgrammeController::class, 'unarchive'])
            ->middleware('permission:programme.edit')->name('programmes.unarchive');

        Route::get('/activities', [ActivityController::class, 'index'])
            ->middleware('permission:activity.view')->name('activities.index');
        Route::post('/activities', [ActivityController::class, 'store'])
            ->middleware('permission:activity.create')->name('activities.store');
        Route::get('/activities/{activity}/budget', [ActivityController::class, 'budget'])
            ->middleware('permission:activity.view')->name('activities.budget');
        Route::get('/activities/{activity}', [ActivityController::class, 'show'])
            ->middleware('permission:activity.view')->name('activities.show');
        Route::match(['put', 'patch'], '/activities/{activity}', [ActivityController::class, 'update'])
            ->middleware('permission:activity.edit')->name('activities.update');
        Route::post('/activities/{activity}/archive', [ActivityController::class, 'archive'])
            ->middleware('permission:activity.edit')->name('activities.archive');

        // Activity-creation wizard — OPTIONAL inline upload (§10). Preview stages an
        // UNBOUND import batch (dedup runs before saving) reusing the /beneficiaries/
        // imports preview + row-resolve endpoints; confirm atomically creates the
        // activity and commits the file under it (served duplicates → pending SRs).
        Route::post('/activity-imports', [ActivityImportController::class, 'store'])
            ->middleware(['permission:activity.create', 'throttle:imports'])->name('activity-imports.store');
        Route::post('/activity-imports/{batch}/confirm', [ActivityImportController::class, 'confirm'])
            ->middleware('permission:activity.create')->name('activity-imports.confirm');

        // Enrollment / assignment (FR-PRG-03): single + bulk into a programme, by the
        // owner MDA; a served (non-owned) beneficiary is allowed via the serve seam.
        Route::get('/enrollments', [EnrollmentController::class, 'index'])
            ->middleware('permission:enrollment.view')->name('enrollments.index');
        Route::post('/programmes/{programme}/enrollments', [EnrollmentController::class, 'store'])
            ->middleware('permission:enrollment.create')->name('programmes.enrollments.store');
        Route::post('/programmes/{programme}/enrollments/bulk', [EnrollmentController::class, 'bulk'])
            ->middleware('permission:enrollment.create')->name('programmes.enrollments.bulk');
        Route::match(['put', 'patch'], '/enrollments/{enrollment}', [EnrollmentController::class, 'update'])
            ->middleware('permission:enrollment.edit')->name('enrollments.update');

        /*
        | Benefit ledger (FR-BEN-01/02/04, §8.3). Records DELIVERY, never money.
        | List/show scoped to the delivering MDA; the per-beneficiary ledger reads
        | across MDAs for the owner/deliverer/oversight. Recording requires the
        | beneficiary be enrolled (which is the serve gate for a non-owned one).
        */
        Route::get('/benefits', [BenefitController::class, 'index'])
            ->middleware('permission:benefit.view')->name('benefits.index');
        // Declared before the {benefit} wildcard so `aggregate` is never treated as an id.
        Route::get('/benefits/aggregate', [BenefitController::class, 'aggregate'])
            ->middleware('permission:benefit.view')->name('benefits.aggregate');
        Route::post('/benefits', [BenefitController::class, 'store'])
            ->middleware('permission:benefit.create')->name('benefits.store');
        Route::get('/benefits/{benefit}', [BenefitController::class, 'show'])
            ->middleware('permission:benefit.view')->name('benefits.show');
        Route::post('/benefits/{benefit}/verify', [BenefitController::class, 'verify'])
            ->middleware('permission:benefit.approve')->name('benefits.verify');
        Route::get('/beneficiaries/{beneficiary}/benefits', [BenefitController::class, 'ledger'])
            ->middleware('permission:benefit.view')->name('beneficiaries.benefits');

        // Auto-route / programme matching (FR-OWN-04): suggest, then confirm (audited).
        Route::get('/beneficiaries/{beneficiary}/routing-suggestions', [BeneficiaryRoutingController::class, 'suggestions'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.routing.suggestions');
        Route::post('/beneficiaries/{beneficiary}/routing-assignments', [BeneficiaryRoutingController::class, 'assign'])
            ->middleware('permission:enrollment.create')->name('beneficiaries.routing.assign');

        // Bulk benefit delivery (§8.3): upload a delivery list keyed to an activity
        // → preview → confirm → commit benefits. Reuses the Phase 2 import lifecycle.
        Route::get('/benefit-imports', [BenefitImportController::class, 'index'])
            ->middleware('permission:benefit.view')->name('benefit-imports.index');
        Route::post('/benefit-imports', [BenefitImportController::class, 'store'])
            ->middleware(['permission:benefit.create', 'throttle:imports'])->name('benefit-imports.store');
        Route::get('/benefit-imports/{batch}', [BenefitImportController::class, 'show'])
            ->middleware('permission:benefit.view')->name('benefit-imports.show');
        Route::post('/benefit-imports/{batch}/confirm', [BenefitImportController::class, 'confirm'])
            ->middleware('permission:benefit.create')->name('benefit-imports.confirm');

        // Double-dipping (FR-BEN-05): configurable rules (admin) + flags for review.
        Route::get('/double-dipping-rules', [DoubleDippingRuleController::class, 'index'])
            ->middleware('permission:double-dipping.view')->name('double-dipping-rules.index');
        Route::post('/double-dipping-rules', [DoubleDippingRuleController::class, 'store'])
            ->middleware('permission:double-dipping.edit')->name('double-dipping-rules.store');
        Route::match(['put', 'patch'], '/double-dipping-rules/{rule}', [DoubleDippingRuleController::class, 'update'])
            ->middleware('permission:double-dipping.edit')->name('double-dipping-rules.update');
        Route::delete('/double-dipping-rules/{rule}', [DoubleDippingRuleController::class, 'destroy'])
            ->middleware('permission:double-dipping.edit')->name('double-dipping-rules.destroy');

        Route::get('/benefit-flags', [BenefitFlagController::class, 'index'])
            ->middleware('permission:benefit.view')->name('benefit-flags.index');
        Route::post('/benefit-flags/{flag}/review', [BenefitFlagController::class, 'review'])
            ->middleware('permission:benefit.approve')->name('benefit-flags.review');

        /*
        | Referrals (FR-REF-01/02/04, §8.2). Two-party scoped (both MDAs see it).
        | Create = originating MDA; the {referral} transition routes are gated by
        | referral.edit and the policy checks the acting party. Reject needs a reason.
        */
        Route::get('/referrals', [ReferralController::class, 'index'])
            ->middleware('permission:referral.view')->name('referrals.index');
        Route::post('/referrals', [ReferralController::class, 'store'])
            ->middleware('permission:referral.create')->name('referrals.store');
        Route::get('/referrals/{referral}', [ReferralController::class, 'show'])
            ->middleware('permission:referral.view')->name('referrals.show');
        Route::post('/referrals/{referral}/accept', [ReferralController::class, 'accept'])
            ->middleware('permission:referral.edit')->name('referrals.accept');
        Route::post('/referrals/{referral}/reject', [ReferralController::class, 'reject'])
            ->middleware('permission:referral.edit')->name('referrals.reject');
        Route::post('/referrals/{referral}/request-info', [ReferralController::class, 'requestInfo'])
            ->middleware('permission:referral.edit')->name('referrals.request-info');
        Route::post('/referrals/{referral}/respond-info', [ReferralController::class, 'respondInfo'])
            ->middleware('permission:referral.edit')->name('referrals.respond-info');
        Route::post('/referrals/{referral}/start', [ReferralController::class, 'start'])
            ->middleware('permission:referral.edit')->name('referrals.start');
        Route::post('/referrals/{referral}/complete', [ReferralController::class, 'complete'])
            ->middleware('permission:referral.edit')->name('referrals.complete');
        Route::post('/referrals/{referral}/close', [ReferralController::class, 'close'])
            ->middleware('permission:referral.edit')->name('referrals.close');

        // Referral SLA windows (FR-REF-04/05) — admin config, audited.
        Route::get('/referral-sla-policies', [ReferralSlaPolicyController::class, 'index'])
            ->middleware('permission:referral-sla.edit')->name('referral-sla-policies.index');
        Route::match(['put', 'patch'], '/referral-sla-policies/{status}', [ReferralSlaPolicyController::class, 'update'])
            ->middleware('permission:referral-sla.edit')->name('referral-sla-policies.update');

        /*
        | Grievances / GRM (FR-GRM-01/02, §8.4). MDA-scoped (handling MDA sees it).
        | Staff capture on behalf of beneficiaries; transitions gated by grievance.edit
        | and the policy checks the handling MDA. Resolve requires resolution notes.
        */
        Route::get('/grievances', [GrievanceController::class, 'index'])
            ->middleware('permission:grievance.view')->name('grievances.index');
        Route::post('/grievances', [GrievanceController::class, 'store'])
            ->middleware('permission:grievance.create')->name('grievances.store');
        Route::get('/grievances/{grievance}', [GrievanceController::class, 'show'])
            ->middleware('permission:grievance.view')->name('grievances.show');
        Route::post('/grievances/{grievance}/assign', [GrievanceController::class, 'assign'])
            ->middleware('permission:grievance.edit')->name('grievances.assign');
        Route::post('/grievances/{grievance}/start', [GrievanceController::class, 'start'])
            ->middleware('permission:grievance.edit')->name('grievances.start');
        Route::post('/grievances/{grievance}/resolve', [GrievanceController::class, 'resolve'])
            ->middleware('permission:grievance.edit')->name('grievances.resolve');
        Route::post('/grievances/{grievance}/close', [GrievanceController::class, 'close'])
            ->middleware('permission:grievance.edit')->name('grievances.close');

        // Grievance SLA windows per category (FR-GRM-03) — admin config, audited.
        Route::get('/grievance-sla-policies', [GrievanceSlaPolicyController::class, 'index'])
            ->middleware('permission:grievance-sla.edit')->name('grievance-sla-policies.index');
        Route::match(['put', 'patch'], '/grievance-sla-policies/{category}', [GrievanceSlaPolicyController::class, 'update'])
            ->middleware('permission:grievance-sla.edit')->name('grievance-sla-policies.update');

        /*
        | Notifications (FR-NOT-01/02). Personal to the caller — no permission gate;
        | every query is scoped to the authenticated recipient. Static paths precede
        | the {notification} route.
        */
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::get('/notifications/preferences', [NotificationController::class, 'preferences'])->name('notifications.preferences.show');
        Route::match(['put', 'patch'], '/notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.preferences.update');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

        /*
        | Dashboards (FR-RPT-01/02, FR-DSH-01). Consolidated, de-identified metrics
        | for the caller's resolved scope, served from the summary snapshot.
        */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard.view')->name('dashboard.index');

        // Export the current (scoped + filtered) dashboard as CSV/Excel/PDF (FR-RPT-03).
        // Aggregate-only — gated by reporting.export, never beneficiary.export.
        Route::get('/dashboard/export', [DashboardExportController::class, 'export'])
            ->middleware(['permission:reporting.export', 'throttle:exports'])->name('dashboard.export');

        // Operational metrics for monitoring (NFR-AVAIL-01) — non-PII; gated so it is
        // not a public information leak. Liveness/readiness stay public at /up + /health.
        Route::get('/health/metrics', [HealthController::class, 'metrics'])
            ->middleware('permission:dashboard.view')->name('health.metrics');

        /*
        | System Administrator console — GOVERNANCE summary (FR-UAM-01, FR-AUD-01):
        | provisioning/catalog KPIs, user adoption, registry data quality, administrative
        | alerts and recent audit activity. Gated to the ROLE, not a permission: a System
        | Administrator implicitly holds every permission, so no permission is exclusive
        | to them. Deliberately NOT system health (that is /health/metrics, ops territory).
        */
        Route::get('/admin/summary', [AdminSummaryController::class, 'show'])
            ->middleware('role:system_administrator')->name('admin.summary');

        // Login activity — a read-only projection of the EXISTING audit trail (auth.*,
        // mfa.*), not a second login log. Console territory, so gated to the role.
        Route::get('/admin/login-activity', [LoginActivityController::class, 'index'])
            ->middleware('role:system_administrator')->name('admin.login-activity');

        // Organization roll-up: user allocation, MDA administrators and owned activities
        // per organization, plus funded delivery per Development Partner. READ ONLY —
        // organizations are still managed through /mdas and /users.
        Route::get('/admin/organizations', [AdminOrganizationController::class, 'index'])
            ->middleware('role:system_administrator')->name('admin.organizations');

        // The canonical registry validation rules, READ ONLY (FR-REG-04/05). Derived
        // from BeneficiaryRules so the console cannot drift from what ingestion
        // enforces; identity-field handling is a locked decision, never admin-editable.
        Route::get('/admin/registry-rules', [RegistryRulesController::class, 'index'])
            ->middleware('role:system_administrator')->name('admin.registry-rules');

        /*
        | Audit & Security (FR-AUD-01, FR-RPT-03) — READ + EXPORT over the immutable,
        | tamper-evident log. Select-only: writing stays with the Auditable trait and
        | AuditLogger, so there is no second logging path. The projection carries the
        | envelope + changed FIELD NAMES only; recorded values never leave the server.
        | Export additionally needs `reporting.export` and is itself audited.
        */
        Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])
            ->middleware('role:system_administrator')->name('admin.audit-logs');
        Route::get('/admin/audit-logs/export', [AuditLogController::class, 'export'])
            ->middleware(['role:system_administrator', 'permission:reporting.export', 'throttle:exports'])
            ->name('admin.audit-logs.export');

        /*
        | MDA console — action-required counters (live, directional, counts only).
        | Deliberately outside the Phase 6 snapshot: a work queue must not be 15
        | minutes stale, and "awaiting ME" is the inbound side, which the dashboard's
        | two-party referral block cannot express. Any MDA user may see the counts;
        | acting on a request-to-serve still needs `beneficiary.approve`.
        */
        Route::get('/mda/action-required', [MdaActionRequiredController::class, 'index'])
            ->middleware('permission:dashboard.view')->name('mda.action-required');

        /*
        | Settings (console). READ-ONLY projection of the EFFECTIVE configuration —
        | there is no console settings store. The only writes reachable from Settings
        | are the permission matrix (role_permission, below) and the caller's own
        | notification preferences (/notifications/preferences).
        */
        Route::get('/admin/settings', [AdminSettingsController::class, 'index'])
            ->middleware('role:system_administrator')->name('admin.settings');

        /*
        | Permission-matrix editor (FR-UAM-05). Writes the EXISTING role_permission
        | pivot that User::permissionKeys() reads, so a change takes effect on the next
        | request. RolePermissionService enforces the SECURITY.md invariants (the
        | System Administrator role is not editable; export.reveal_pii is never granted
        | to a role) and audits every change.
        */
        Route::put('/roles/{role}/permissions', [AccessController::class, 'updatePermissions'])
            ->middleware(['role:system_administrator', 'permission:role.edit'])
            ->name('roles.permissions.update');

        /*
        | System broadcast (FR-NOT-01) — fans out through the Phase 5 Notifier, so
        | channel availability and recipient preferences apply unchanged.
        */
        Route::get('/notifications/broadcast/audience', [BroadcastController::class, 'audience'])
            ->middleware('role:system_administrator')->name('notifications.broadcast.audience');
        Route::post('/notifications/broadcast', [BroadcastController::class, 'store'])
            ->middleware('role:system_administrator')->name('notifications.broadcast');

        // GIS coverage map (FR-GIS-01): choropleth when boundaries are loaded, else a
        // ranked-table fallback. Scoped to the caller.
        Route::get('/gis/coverage', [GisController::class, 'coverage'])
            ->middleware('permission:dashboard.view')->name('gis.coverage');

        /*
        | Standard reports (FR-RPT-03). Catalogue + runs are scoped to the caller;
        | generation is queued and downloads are audited. Static paths precede the
        | {report} routes.
        */
        Route::get('/reports/catalogue', [ReportController::class, 'catalogue'])
            ->middleware('permission:reporting.view')->name('reports.catalogue');

        /*
        | Ad-hoc report builder (FR-RPT-03). Compose from a whitelisted dataset →
        | preview → export. Registered before the /reports/{report} wildcard.
        */
        /*
        | Segment builder (FR-RPT-03): filter the registry by segmentable dimensions,
        | preview a table/breakdown, export via the shared pipeline. `reporting.view`
        | opens the builder; the EXPORT MATRIX is enforced inside (SegmentAccess), which
        | is what decides rows-vs-counts — a permission on the route could not express
        | "you may run this, but only aggregates come back".
        */
        Route::get('/reports/segments/dimensions', [SegmentReportController::class, 'dimensions'])
            ->middleware('permission:reporting.view')->name('reports.segments.dimensions');
        Route::post('/reports/segments/preview', [SegmentReportController::class, 'preview'])
            ->middleware('permission:reporting.view', 'throttle:reports')->name('reports.segments.preview');
        Route::post('/reports/segments/export', [SegmentReportController::class, 'export'])
            ->middleware(['permission:reporting.export', 'throttle:exports'])->name('reports.segments.export');
        Route::get('/reports/adhoc/datasets', [AdHocReportController::class, 'datasets'])
            ->middleware('permission:reporting.view')->name('reports.adhoc.datasets');
        Route::post('/reports/adhoc/preview', [AdHocReportController::class, 'preview'])
            ->middleware('permission:reporting.view', 'throttle:reports')->name('reports.adhoc.preview');
        Route::post('/reports/adhoc', [AdHocReportController::class, 'export'])
            ->middleware(['permission:reporting.export', 'throttle:exports'])->name('reports.adhoc.export');

        // Saved ad-hoc definitions (reusable; basis for scheduling in 6.6).
        Route::get('/report-definitions', [ReportDefinitionController::class, 'index'])
            ->middleware('permission:reporting.view')->name('report-definitions.index');
        Route::post('/report-definitions', [ReportDefinitionController::class, 'store'])
            ->middleware('permission:reporting.view')->name('report-definitions.store');
        Route::get('/report-definitions/{definition}', [ReportDefinitionController::class, 'show'])
            ->middleware('permission:reporting.view')->name('report-definitions.show');
        Route::delete('/report-definitions/{definition}', [ReportDefinitionController::class, 'destroy'])
            ->middleware('permission:reporting.view')->name('report-definitions.destroy');
        Route::post('/report-definitions/{definition}/run', [ReportDefinitionController::class, 'run'])
            ->middleware(['permission:reporting.export', 'throttle:exports'])->name('report-definitions.run');

        // Scheduled reports (FR-RPT-04) — generate on schedule + deliver to validated recipients.
        Route::get('/report-schedules', [ReportScheduleController::class, 'index'])
            ->middleware('permission:reporting.view')->name('report-schedules.index');
        Route::post('/report-schedules', [ReportScheduleController::class, 'store'])
            ->middleware('permission:reporting.export')->name('report-schedules.store');
        Route::get('/report-schedules/{schedule}', [ReportScheduleController::class, 'show'])
            ->middleware('permission:reporting.view')->name('report-schedules.show');
        Route::match(['put', 'patch'], '/report-schedules/{schedule}', [ReportScheduleController::class, 'update'])
            ->middleware('permission:reporting.export')->name('report-schedules.update');
        Route::delete('/report-schedules/{schedule}', [ReportScheduleController::class, 'destroy'])
            ->middleware('permission:reporting.export')->name('report-schedules.destroy');

        Route::get('/reports', [ReportController::class, 'index'])
            ->middleware('permission:reporting.view')->name('reports.index');
        Route::post('/reports', [ReportController::class, 'store'])
            ->middleware(['permission:reporting.export', 'throttle:exports'])->name('reports.store');
        Route::get('/reports/{report}', [ReportController::class, 'show'])
            ->middleware('permission:reporting.view')->name('reports.show');
        Route::get('/reports/{report}/download', [ReportController::class, 'download'])
            ->middleware(['permission:reporting.export', 'throttle:exports'])->name('reports.download');
    });
});
