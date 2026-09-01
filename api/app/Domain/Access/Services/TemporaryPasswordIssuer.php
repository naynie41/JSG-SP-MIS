<?php

declare(strict_types=1);

namespace App\Domain\Access\Services;

use App\Domain\Access\Models\User;
use Illuminate\Support\Str;

/**
 * Issues a one-time temporary password for an administrator-initiated reset
 * (SECURITY.md §2, FR-UAM-06).
 *
 * The password is GENERATED rather than chosen by the administrator: a human
 * picking "a password to read down the phone" picks a weak one, and the account
 * being recovered may hold beneficiary PII.
 *
 * It is set directly on the model rather than run through PasswordRules, which
 * matters operationally: PasswordRules calls the HaveIBeenPwned range API, so
 * validating here would put an outbound network dependency on the ONLY account
 * recovery path. A 32-character random string is not in a breach corpus.
 */
final class TemporaryPasswordIssuer
{
    /**
     * Reset the user to a fresh temporary password and require them to change it.
     *
     * Returns the plaintext ONCE, for out-of-band handover. It is never audited,
     * logged, or persisted in plaintext — the model's `hashed` cast stores only
     * the hash.
     */
    public function issueFor(User $user): string
    {
        $password = $this->generate();

        // forceFill: `must_change_password` is deliberately absent from $fillable so
        // no request payload can ever clear it by mass assignment.
        $user->forceFill([
            'password' => $password,
            'must_change_password' => true,
        ])->save();

        // Any live session must not survive a reset.
        $user->tokens()->delete();

        return $password;
    }

    /**
     * 32 chars from Str::random (alphanumeric) plus fixed punctuation, so it also
     * satisfies any composition rule a future policy might add.
     */
    private function generate(): string
    {
        return Str::random(32).'aA1!';
    }
}
