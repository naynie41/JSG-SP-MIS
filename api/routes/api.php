<?php

declare(strict_types=1);

use App\Domain\Access\Support\TokenAbility;
use App\Http\Controllers\Api\V1\Access\AccessController;
use App\Http\Controllers\Api\V1\Access\MdaAccessGrantController;
use App\Http\Controllers\Api\V1\Access\MdaController;
use App\Http\Controllers\Api\V1\Access\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\MfaController;
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
    });
});
