<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Access\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Serializes the authenticated user with their role, permissions and MDA.
 * JSON keys are snake_case per CONVENTIONS.md. Never exposes password/MFA secret.
 *
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status->value,
            // Lockout is a runtime state (FR-UAM-06), distinct from the stored status:
            // an account can be `active` yet temporarily locked after failed attempts.
            'is_locked' => $this->isLocked(),
            'locked_until' => $this->locked_until?->toIso8601String(),
            'mfa_enabled' => (bool) $this->mfa_enabled,
            // Whether the ROLE mandates MFA (FR-UAM-04) — the enforcement policy, so an
            // administrator can see compliance (required vs actually enrolled).
            'mfa_required' => $this->mfaRequired(),
            // Drives the SPA's forced change-password screen; the API enforces it
            // independently in RequirePasswordChange (FR-UAM-06).
            'must_change_password' => (bool) $this->must_change_password,
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'mda' => $this->mda ? [
                'id' => $this->mda->id,
                'name' => $this->mda->name,
                'type' => $this->mda->type->value,
            ] : null,
            'role' => $this->role ? [
                'key' => $this->role->key,
                'name' => $this->role->name,
            ] : null,
            'permissions' => $this->role
                ? $this->role->permissions->pluck('key')->values()->all()
                : [],
        ];
    }
}
