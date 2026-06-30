<?php

declare(strict_types=1);

namespace App\Domain\Access\Support;

/**
 * Sanctum token ability constants used to distinguish stages of the auth flow.
 *
 *  - FULL: a fully authenticated session token (granted after MFA, if required).
 *  - MFA_CHALLENGE: short-lived token issued after password verification when the
 *    user must still pass a TOTP/recovery challenge. Can ONLY hit /mfa/challenge.
 *  - MFA_SETUP: short-lived token issued when an MFA-required user has not yet
 *    enrolled. Can ONLY hit the MFA enrol/verify endpoints.
 */
final class TokenAbility
{
    public const FULL = '*';

    public const MFA_CHALLENGE = 'mfa-challenge';

    public const MFA_SETUP = 'mfa-setup';
}
