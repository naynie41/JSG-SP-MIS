<?php

declare(strict_types=1);

use App\Domain\Access\Support\TokenAbility;
use App\Http\Controllers\Api\V1\Access\AccessController;
use App\Http\Controllers\Api\V1\Access\MdaAccessGrantController;
use App\Http\Controllers\Api\V1\Access\MdaController;
use App\Http\Controllers\Api\V1\Access\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\Matching\MatchingConfigController;
use App\Http\Controllers\Api\V1\MfaController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryDocumentController;
use App\Http\Controllers\Api\V1\Registry\BeneficiaryIntakeController;
use App\Http\Controllers\Api\V1\Registry\HouseholdController;
use App\Http\Controllers\Api\V1\Registry\HouseholdMemberController;
use App\Http\Controllers\Api\V1\Registry\ImportBatchController;
use App\Http\Controllers\Api\V1\Registry\OwnershipTransferController;
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
        // Declared before the wildcard so `lookup`/`imports` are never treated as ids.
        Route::get('/beneficiaries/lookup', [BeneficiaryController::class, 'lookup'])
            ->middleware('permission:beneficiary-lookup.view')->name('beneficiaries.lookup');

        // Bulk import (Excel/CSV) — upload → preview → confirm → commit (FR-REG-02/06).
        Route::get('/beneficiaries/imports', [ImportBatchController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.imports.index');
        Route::post('/beneficiaries/imports', [ImportBatchController::class, 'store'])
            ->middleware('permission:beneficiary.create')->name('beneficiaries.imports.store');
        Route::get('/beneficiaries/imports/{batch}', [ImportBatchController::class, 'show'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.imports.show');
        Route::post('/beneficiaries/imports/{batch}/confirm', [ImportBatchController::class, 'confirm'])
            ->middleware('permission:beneficiary.create')->name('beneficiaries.imports.confirm');

        // Inbound REST registration intake (FR-REG-02, source=api) — rate limited.
        Route::post('/beneficiaries/intake', [BeneficiaryIntakeController::class, 'store'])
            ->middleware(['permission:beneficiary.create', 'throttle:registration-intake'])
            ->name('beneficiaries.intake');

        Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.index');
        // NOTE: no manual create endpoint — beneficiaries enter only via source
        // ingestion (bulk import + REST intake). See docs/registry-intake.md.
        Route::get('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'show'])
            ->middleware('permission:beneficiary.view')->name('beneficiaries.show');
        Route::match(['put', 'patch'], '/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.update');
        Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])
            ->middleware('permission:beneficiary.edit')->name('beneficiaries.destroy');

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
    });
});
