<?php

declare(strict_types=1);

use App\Domain\Access\Support\TokenAbility;
use App\Http\Controllers\Api\V1\Access\AccessController;
use App\Http\Controllers\Api\V1\Access\MdaAccessGrantController;
use App\Http\Controllers\Api\V1\Access\MdaController;
use App\Http\Controllers\Api\V1\Access\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Benefit\BenefitController;
use App\Http\Controllers\Api\V1\Benefit\BenefitFlagController;
use App\Http\Controllers\Api\V1\Benefit\BenefitImportController;
use App\Http\Controllers\Api\V1\Benefit\DoubleDippingRuleController;
use App\Http\Controllers\Api\V1\Grievance\GrievanceController;
use App\Http\Controllers\Api\V1\Grievance\GrievanceSlaPolicyController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\Matching\MatchingConfigController;
use App\Http\Controllers\Api\V1\MfaController;
use App\Http\Controllers\Api\V1\Notification\NotificationController;
use App\Http\Controllers\Api\V1\Programme\ActivityController;
use App\Http\Controllers\Api\V1\Programme\EnrollmentController;
use App\Http\Controllers\Api\V1\Programme\ProgrammeController;
use App\Http\Controllers\Api\V1\Referral\ReferralController;
use App\Http\Controllers\Api\V1\Referral\ReferralSlaPolicyController;
use App\Http\Controllers\Api\V1\Registry\ActivityImportController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryDocumentController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryIntakeController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryRoutingController;
use App\Http\Controllers\Api\V1\Registry\HouseholdController;
use App\Http\Controllers\Api\V1\Registry\HouseholdMemberController;
use App\Http\Controllers\Api\V1\Registry\ImportBatchController;
use App\Http\Controllers\Api\V1\Registry\OwnershipTransferController;
use App\Http\Controllers\Api\V1\Registry\ServiceRequestController;
use App\Http\Controllers\Api\V1\Reporting\AdHocReportController;
use App\Http\Controllers\Api\V1\Reporting\DashboardController;
use App\Http\Controllers\Api\V1\Reporting\GisController;
use App\Http\Controllers\Api\V1\Reporting\ReportController;
use App\Http\Controllers\Api\V1\Reporting\ReportDefinitionController;
use App\Http\Controllers\Api\V1\Reporting\ReportScheduleController;
use App\Http\Controllers\Api\V1\Sharing\DataSharingController;
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
        Route::get('/beneficiaries/imports', [ImportBatchController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.imports.index');
        Route::post('/beneficiaries/imports', [ImportBatchController::class, 'store'])
            ->middleware('permission:beneficiary.create')->name('beneficiaries.imports.store');
        Route::get('/beneficiaries/imports/{batch}', [ImportBatchController::class, 'show'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.imports.show');
        // Resolve a flagged row: new (with justification) / link-serve / skip (FR-DUP-05).
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
            ->middleware('permission:beneficiary.export')->name('beneficiaries.export');
        // NOTE: no manual create endpoint — beneficiaries enter only via source
        // ingestion (bulk import + REST intake). See docs/registry-intake.md.
        Route::get('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'show'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.show');
        // Cross-MDA data-sharing consent (NFR-PRV-01) — owner MDA records grant/withdraw.
        Route::put('/beneficiaries/{beneficiary}/consent', [BeneficiaryController::class, 'consent'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.consent');
        Route::match(['put', 'patch'], '/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.update');
        Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.destroy');

        // Data-sharing oversight (FR-DSH-01): who can access what across MDAs, and why.
        Route::get('/data-sharing/grants', [DataSharingController::class, 'grants'])
            ->middleware('permission:cross-mda.view')->name('data-sharing.grants');

        // Supporting documents (FR-REG-07): owner-only upload/delete, in-scope
        // list/download. Files are streamed via the download action, never static.
        Route::get('/beneficiaries/{beneficiary}/documents', [BeneficiaryDocumentController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.documents.index');
        Route::post('/beneficiaries/{beneficiary}/documents', [BeneficiaryDocumentController::class, 'store'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.documents.store');
        Route::get('/beneficiaries/{beneficiary}/documents/{document}/download', [BeneficiaryDocumentController::class, 'download'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.documents.download');
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
        Route::post('/programmes', [ProgrammeController::class, 'store'])
            ->middleware('permission:programme.create')->name('programmes.store');
        Route::get('/programmes/{programme}/budget', [ProgrammeController::class, 'budget'])
            ->middleware('permission:programme.view')->name('programmes.budget');
        Route::get('/programmes/{programme}', [ProgrammeController::class, 'show'])
            ->middleware('permission:programme.view')->name('programmes.show');
        Route::match(['put', 'patch'], '/programmes/{programme}', [ProgrammeController::class, 'update'])
            ->middleware('permission:programme.edit')->name('programmes.update');
        Route::post('/programmes/{programme}/archive', [ProgrammeController::class, 'archive'])
            ->middleware('permission:programme.edit')->name('programmes.archive');

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
            ->middleware('permission:activity.create')->name('activity-imports.store');
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
            ->middleware('permission:benefit.create')->name('benefit-imports.store');
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
        Route::get('/reports/adhoc/datasets', [AdHocReportController::class, 'datasets'])
            ->middleware('permission:reporting.view')->name('reports.adhoc.datasets');
        Route::post('/reports/adhoc/preview', [AdHocReportController::class, 'preview'])
            ->middleware('permission:reporting.view')->name('reports.adhoc.preview');
        Route::post('/reports/adhoc', [AdHocReportController::class, 'export'])
            ->middleware('permission:reporting.export')->name('reports.adhoc.export');

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
            ->middleware('permission:reporting.export')->name('report-definitions.run');

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
            ->middleware('permission:reporting.export')->name('reports.store');
        Route::get('/reports/{report}', [ReportController::class, 'show'])
            ->middleware('permission:reporting.view')->name('reports.show');
        Route::get('/reports/{report}/download', [ReportController::class, 'download'])
            ->middleware('permission:reporting.export')->name('reports.download');
    });
});
