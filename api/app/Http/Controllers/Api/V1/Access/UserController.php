<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\User;
use App\Domain\Access\Services\TemporaryPasswordIssuer;
use App\Domain\Audit\Services\AuditLogger;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\StoreUserRequest;
use App\Http\Requests\Access\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Admin management of users (PRD FR-UAM-02, FR-UAM-03). Results are MDA-scoped by
 * the User model's global scope; each route declares its permission; every
 * mutation is audited (create/update/status via the Auditable model, and the
 * token/MFA actions explicitly, since they touch non-audited columns).
 */
class UserController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function index(): JsonResponse
    {
        $users = User::query()->with('role.permissions', 'mda')->orderBy('name')->get();

        return ApiResponse::success(['users' => UserResource::collection($users)->resolve()]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        // The ADMINISTRATOR chose this password and must hand it over, so it is a
        // shared secret from the moment it is set. The account holder changes it on
        // first sign-in before doing anything else.
        $user->forceFill(['must_change_password' => true])->save();

        return ApiResponse::success(
            (new UserResource($user->load('role.permissions', 'mda')))->resolve(),
            status: 201,
        );
    }

    public function show(User $user): JsonResponse
    {
        return ApiResponse::success((new UserResource($user->load('role.permissions', 'mda')))->resolve());
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return ApiResponse::success((new UserResource($user->fresh()->load('role.permissions', 'mda')))->resolve());
    }

    public function suspend(User $user): JsonResponse
    {
        return $this->changeStatus($user, UserStatus::Suspended);
    }

    public function deactivate(User $user): JsonResponse
    {
        return $this->changeStatus($user, UserStatus::Deactivated);
    }

    public function activate(User $user): JsonResponse
    {
        return $this->changeStatus($user, UserStatus::Active);
    }

    /**
     * Force the user to set a new password: revoke their tokens (immediate
     * logout) and audit the request. Delivery of the reset link is a later phase.
     */
    /**
     * Reset a user to a one-time temporary password (FR-UAM-06, SECURITY.md §2).
     *
     * This previously only revoked tokens — the user signed back in with the SAME
     * password, which meant a forgotten password could not be recovered by anyone.
     *
     * The plaintext is returned ONCE for out-of-band handover (there is no mail
     * dependency in the auth flow by design) and is never audited or logged.
     */
    public function forcePasswordReset(User $user, TemporaryPasswordIssuer $issuer): JsonResponse
    {
        $temporaryPassword = $issuer->issueFor($user);

        $this->audit->record('user.password_reset_forced', $user);

        return ApiResponse::success([
            'message' => 'Temporary password issued. Give it to the user directly — it is shown only once, and they must change it at next sign-in.',
            'temporary_password' => $temporaryPassword,
        ]);
    }

    /**
     * Reset the user's MFA (e.g. lost device). Clears their secret/recovery codes
     * and revokes tokens; if their role requires MFA they must re-enrol at next
     * login (FR-UAM-04).
     */
    public function resetMfa(User $user): JsonResponse
    {
        $user->forceFill([
            'mfa_enabled' => false,
            'mfa_secret' => null,
            'mfa_recovery_codes' => null,
        ])->save();
        $user->tokens()->delete();

        $this->audit->record('user.mfa_reset', $user);

        return ApiResponse::success(['message' => 'MFA has been reset for the user.']);
    }

    private function changeStatus(User $user, UserStatus $status): JsonResponse
    {
        $user->update(['status' => $status]);

        // Suspended/deactivated users must not keep an active session.
        if ($status !== UserStatus::Active) {
            $user->tokens()->delete();
        }

        return ApiResponse::success((new UserResource($user->fresh()->load('role.permissions', 'mda')))->resolve());
    }
}
