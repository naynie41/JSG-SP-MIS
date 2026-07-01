<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Audit value scrubbing (SECURITY.md §6)
    |--------------------------------------------------------------------------
    |
    | Secrets are never stored — their values become "[redacted]". PII fields are
    | masked (partially obscured) so changes are auditable without leaking the
    | underlying personal data. Matching is by attribute name (case-insensitive).
    |
    */

    // Replaced entirely with "[redacted]".
    'omit' => [
        'password',
        'password_confirmation',
        'current_password',
        'remember_token',
        'mfa_secret',
        'mfa_recovery_codes',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'token',
        'secret',
        'api_key',
    ],

    // Partially masked (e.g. "jo***"). Clearly-PII field names only — generic
    // names like a role/MDA "name" are not PII and stay readable; models add
    // their own PII fields via Auditable::auditMask() (e.g. User masks "name").
    'mask' => [
        'full_name',
        'email',
        'phone',
        'phone_number',
        'msisdn',
        'contact_person',
        'contact_email',
        'contact_phone',
        'address',
        'nin',
        'bvn',
        'dob',
        'date_of_birth',
    ],

    // Number of leading characters kept when masking a string value.
    'mask_keep' => 2,

];
