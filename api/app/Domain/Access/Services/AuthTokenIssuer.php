<?php

declare(strict_types=1);

namespace App\Domain\Access\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Support\TokenAbility;

/**
 * Issues Sanctum tokens for the various stages of the authentication flow.
 */
class AuthTokenIssuer
{
    /**
     * Complete authentication: clear lockout, stamp last login, and issue a
     * full-access token. Returned to the client only after MFA (if required).
     */
    public function issueFull(User $user): string
    {
        $user->clearLockout();
        $user->forceFill(['last_login_at' => now()])->save();

        return $user->createToken('api', [TokenAbility::FULL])->plainTextToken;
    }

    /**
     * Short-lived token that may ONLY be used to pass the MFA challenge.
     */
    public function issueChallenge(User $user): string
    {
        return $user->createToken(
            'mfa-challenge',
            [TokenAbility::MFA_CHALLENGE],
            now()->addMinutes($this->ttl()),
        )->plainTextToken;
    }

    /**
     * Short-lived token that may ONLY be used to enrol/verify MFA (for users in
     * MFA-required roles who have not enrolled yet).
     */
    public function issueSetup(User $user): string
    {
        return $user->createToken(
            'mfa-setup',
            [TokenAbility::MFA_SETUP],
            now()->addMinutes($this->ttl()),
        )->plainTextToken;
    }

    private function ttl(): int
    {
        return (int) config('security.mfa.challenge_ttl_minutes');
    }
}
