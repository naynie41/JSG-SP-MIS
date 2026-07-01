<?php

declare(strict_types=1);

namespace App\Domain\Access\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Support\TokenAbility;
use App\Domain\Audit\Services\AuditLogger;

/**
 * Issues Sanctum tokens for the various stages of the authentication flow.
 */
class AuthTokenIssuer
{
    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * Complete authentication: clear lockout, stamp last login, and issue a
     * full-access token. Returned to the client only after MFA (if required).
     * This is the single point where a login completes, so it is audited here.
     */
    public function issueFull(User $user): string
    {
        $user->clearLockout();
        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken('api', [TokenAbility::FULL])->plainTextToken;

        $this->audit->record('auth.login', $user, actor: $user);

        return $token;
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
