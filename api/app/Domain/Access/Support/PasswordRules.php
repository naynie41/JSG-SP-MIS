<?php

declare(strict_types=1);

namespace App\Domain\Access\Support;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Central password policy (SECURITY.md §2): minimum 12 characters and checked
 * against known breached passwords (HaveIBeenPwned k-anonymity range API).
 *
 * Reused anywhere a password is set so the policy stays consistent.
 */
final class PasswordRules
{
    /**
     * @return array<int, Rule|Password|string>
     */
    public static function default(): array
    {
        return [
            'required',
            'string',
            'confirmed',
            Password::min(12)->uncompromised(),
        ];
    }
}
