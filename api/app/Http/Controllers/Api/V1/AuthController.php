<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Events\AccountLocked;
use App\Domain\Access\Models\User;
use App\Domain\Access\Services\AuthTokenIssuer;
use App\Domain\Audit\Services\AuditLogger;
use App\Http\Controllers\Concerns\AuthResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use AuthResponses;

    public function __construct(
        private readonly AuthTokenIssuer $tokens,
        private readonly AuditLogger $audit,
    ) {}

    /**
     * Verify credentials and either issue a full token, or hand off to the MFA
     * flow (PRD FR-UAM-04). All credential failures return the same generic
     * error so account existence, status and lock state are never revealed
     * (SECURITY.md §2). Failed attempts drive account lockout (FR-UAM-06).
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        // Already locked: tell the user it's temporarily locked.
        if ($user !== null && $user->isLocked()) {
            return $this->accountLocked($user);
        }

        if (! $this->credentialsAreValid($user, $credentials['password'])) {
            // Audited without the attempted email (no PII); actor set if known.
            $this->audit->record('auth.login_failed', $user, actor: $user);

            if ($user !== null && $user->registerFailedAttempt()) {
                AccountLocked::dispatch($user, $request->ip());

                return $this->accountLocked($user);
            }

            return $this->invalidCredentials();
        }

        // Password is correct. Decide whether MFA is still required.
        if ($user->mfa_enabled) {
            return ApiResponse::success([
                'mfa_required' => true,
                'token_type' => 'Bearer',
                'mfa_token' => $this->tokens->issueChallenge($user),
            ]);
        }

        if ($user->mfaRequired()) {
            // MFA is mandatory for this role but not yet enrolled.
            return ApiResponse::success([
                'mfa_setup_required' => true,
                'token_type' => 'Bearer',
                'mfa_token' => $this->tokens->issueSetup($user),
            ]);
        }

        return $this->fullTokenResponse($user, $request);
    }

    /**
     * Return the authenticated user with role, permissions and MDA.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role.permissions', 'mda');

        return ApiResponse::success((new UserResource($user))->resolve($request));
    }

    /**
     * Revoke the token used for the current request.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        $this->audit->record('auth.logout', $user, actor: $user);

        return ApiResponse::success(['message' => 'Logged out.']);
    }

    /**
     * Change the authenticated user's password. Saving a changed password
     * invalidates every existing token (handled on the User model).
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->password = $request->validated()['password'];
        $user->save();

        return ApiResponse::success([
            'message' => 'Password updated. Please sign in again.',
        ]);
    }

    /**
     * Build the standard full-login response (token + user).
     */
    private function fullTokenResponse(User $user, Request $request): JsonResponse
    {
        $token = $this->tokens->issueFull($user);

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => (new UserResource($user->load('role.permissions', 'mda')))->resolve($request),
        ]);
    }

    /**
     * Constant-ish time credential check that also enforces account status.
     */
    private function credentialsAreValid(?User $user, string $password): bool
    {
        if ($user === null) {
            Hash::make($password); // equalize timing for unknown accounts

            return false;
        }

        return Hash::check($password, $user->password)
            && $user->status === UserStatus::Active;
    }
}
