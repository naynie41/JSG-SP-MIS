<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Account lockout (PRD FR-UAM-06, SECURITY.md §2)
    |--------------------------------------------------------------------------
    |
    | After `max_attempts` failed logins (wrong password or failed MFA) the
    | account is locked. Each subsequent lock is longer, growing by `multiplier`
    | from `decay_minutes`, capped at `max_minutes` (exponential backoff).
    |
    */

    'lockout' => [
        'max_attempts' => (int) env('AUTH_LOCKOUT_MAX_ATTEMPTS', 5),
        'decay_minutes' => (int) env('AUTH_LOCKOUT_DECAY_MINUTES', 1),
        'multiplier' => (int) env('AUTH_LOCKOUT_MULTIPLIER', 2),
        'max_minutes' => (int) env('AUTH_LOCKOUT_MAX_MINUTES', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-factor authentication (PRD FR-UAM-04)
    |--------------------------------------------------------------------------
    */

    'mfa' => [
        'issuer' => env('MFA_ISSUER', env('APP_NAME', 'SP-MIS')),
        'recovery_code_count' => (int) env('MFA_RECOVERY_CODE_COUNT', 8),
        // TOTP verification window (number of 30s steps tolerated each side).
        'window' => (int) env('MFA_WINDOW', 1),
        // Lifetime of the short-lived challenge / setup token issued during login.
        'challenge_ttl_minutes' => (int) env('MFA_CHALLENGE_TTL_MINUTES', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session / token lifetimes (SECURITY.md §2)
    |--------------------------------------------------------------------------
    |
    | idle_timeout_minutes: a token unused for this long is rejected & revoked.
    | absolute_lifetime_minutes: wires config('sanctum.expiration'); a token is
    | invalid this long after issue regardless of activity.
    |
    */

    'session' => [
        'idle_timeout_minutes' => (int) env('SESSION_IDLE_TIMEOUT_MINUTES', 30),
        'absolute_lifetime_minutes' => (int) env('SESSION_ABSOLUTE_LIFETIME_MINUTES', 480),
    ],

];
