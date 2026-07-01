<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Access\Events\AccountLocked;
use App\Domain\Access\Events\MfaChallengeFailed;
use App\Domain\Access\Events\MfaDisabled;
use App\Domain\Access\Events\MfaEnrolled;
use App\Domain\Access\Models\User;
use App\Domain\Access\Services\AuthTokenIssuer;
use App\Domain\Access\Services\MfaService;
use App\Domain\Access\Support\TokenAbility;
use App\Http\Controllers\Concerns\AuthResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MfaCodeRequest;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MfaController extends Controller
{
    use AuthResponses;

    public function __construct(
        private readonly MfaService $mfa,
        private readonly AuthTokenIssuer $tokens,
    ) {}

    /**
     * Begin enrolment: generate a secret + recovery codes and return the
     * provisioning URI. MFA is not active until verified. Secret and recovery
     * codes are stored encrypted; the plaintext recovery codes are shown ONCE.
     */
    public function enroll(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->mfa_enabled) {
            return ApiResponse::error('MFA_ALREADY_ENABLED', 'MFA is already enabled.', [], 409);
        }

        $secret = $this->mfa->generateSecret();
        $recoveryCodes = $this->mfa->generateRecoveryCodes();

        $user->forceFill([
            'mfa_secret' => $secret,
            'mfa_recovery_codes' => $recoveryCodes,
        ])->save();

        return ApiResponse::success([
            'secret' => $secret,
            'provisioning_uri' => $this->mfa->provisioningUri($user->email, $secret),
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    /**
     * Verify a TOTP code and enable MFA. If the caller used a short-lived setup
     * token (MFA-required role completing first-time setup), they are promoted
     * to a full token and considered logged in.
     */
    public function verify(MfaCodeRequest $request): JsonResponse
    {
        $user = $request->user();

        if (empty($user->mfa_secret)) {
            return ApiResponse::error('MFA_NOT_ENROLLED', 'Start MFA enrolment first.', [], 409);
        }

        if (! $this->mfa->verifyCode($user->mfa_secret, $request->validated()['code'])) {
            return ApiResponse::error('MFA_FAILED', 'Invalid verification code.', [], 422);
        }

        $user->forceFill(['mfa_enabled' => true])->save();
        MfaEnrolled::dispatch($user);

        // Completing setup via the setup token issues a full session token.
        if ($request->user()->tokenCan(TokenAbility::MFA_SETUP)
            && ! $request->user()->tokenCan(TokenAbility::FULL)) {
            $request->user()->currentAccessToken()->delete();

            return $this->fullTokenResponse($user, $request);
        }

        return ApiResponse::success(['mfa_enabled' => true]);
    }

    /**
     * Disable MFA. Forbidden for roles that require MFA. Requires a valid
     * current TOTP or recovery code to authorize the change.
     */
    public function disable(MfaCodeRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($user->mfaRequired()) {
            return ApiResponse::error('MFA_REQUIRED', 'MFA cannot be disabled for this role.', [], 403);
        }

        if (! $user->mfa_enabled) {
            return ApiResponse::error('MFA_NOT_ENABLED', 'MFA is not enabled.', [], 409);
        }

        if (! $this->verifyAnyCode($user, $request->validated()['code'])) {
            return ApiResponse::error('MFA_FAILED', 'Invalid verification code.', [], 422);
        }

        $user->forceFill([
            'mfa_enabled' => false,
            'mfa_secret' => null,
            'mfa_recovery_codes' => null,
        ])->save();

        MfaDisabled::dispatch($user);

        return ApiResponse::success(['mfa_enabled' => false]);
    }

    /**
     * Complete login by passing the MFA challenge (TOTP or recovery code).
     * On success the challenge token is swapped for a full session token.
     * Failures count toward account lockout (FR-UAM-06).
     */
    public function challenge(MfaCodeRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isLocked()) {
            return $this->accountLocked($user);
        }

        if (! $this->verifyAnyCode($user, $request->validated()['code'])) {
            MfaChallengeFailed::dispatch($user, $request->ip());

            if ($user->registerFailedAttempt()) {
                AccountLocked::dispatch($user, $request->ip());

                return $this->accountLocked($user);
            }

            return ApiResponse::error('MFA_FAILED', 'Invalid verification code.', [], 401);
        }

        // Swap the challenge token for a full session token.
        $request->user()->currentAccessToken()->delete();

        return $this->fullTokenResponse($user, $request);
    }

    /**
     * Accept either a valid TOTP code or a one-time recovery code.
     */
    private function verifyAnyCode(User $user, string $code): bool
    {
        if (! empty($user->mfa_secret) && $this->mfa->verifyCode($user->mfa_secret, $code)) {
            return true;
        }

        return $user->consumeRecoveryCode($code);
    }

    private function fullTokenResponse(User $user, Request $request): JsonResponse
    {
        $token = $this->tokens->issueFull($user);

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => (new UserResource($user->load('role.permissions', 'mda')))->resolve($request),
        ]);
    }
}
