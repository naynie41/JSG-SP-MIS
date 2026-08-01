<?php

declare(strict_types=1);

namespace App\Domain\Programme\Rules;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A funding-partner attribution must reference a **Development Partner** user (Phase 6P).
 * Null/empty is allowed (state-funded / not attributed).
 */
class IsFundingPartner implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        // Bypass global scopes: this is a role check on any user, not a scoped read.
        $isPartner = User::query()
            ->withoutGlobalScopes()
            ->where('id', $value)
            ->whereHas('role', fn ($q) => $q->where('key', RoleKey::DevelopmentPartner->value))
            ->exists();

        if (! $isPartner) {
            $fail('The selected funding partner must be a Development Partner.');
        }
    }
}
