<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\User;
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
    public function forcePasswordReset(User $user): JsonResponse
    {
        $user->tokens()->delete();

        $this->audit->record('user.password_reset_forced', $user);

        return ApiResponse::success(['message' => 'Password reset triggered; the user must sign in again and set a new password.']);
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
