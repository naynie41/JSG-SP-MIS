<?php

declare(strict_types=1);

namespace App\Domain\Access\Services;

use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

/**
 * TOTP MFA helper (PRD FR-UAM-04). Wraps the google2fa library for secret
 * generation, provisioning URIs and code verification, and generates one-time
 * recovery codes. No secret is ever logged.
 */
class MfaService
{
    public function __construct(private readonly Google2FA $google2fa) {}

    /**
     * Generate a new base32 TOTP secret.
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Build the otpauth:// provisioning URI the authenticator app encodes as a QR.
     */
    public function provisioningUri(string $accountIdentifier, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            (string) config('security.mfa.issuer'),
            $accountIdentifier,
            $secret,
        );
    }

    /**
     * Verify a TOTP code against the secret, tolerating clock drift via the
     * configured window.
     */
    public function verifyCode(string $secret, string $code): bool
    {
        $code = preg_replace('/\s+/', '', $code) ?? '';

        if ($code === '') {
            return false;
        }

        return $this->google2fa->verifyKey($secret, $code, (int) config('security.mfa.window'));
    }

    /**
     * Generate a fresh set of one-time recovery codes (plaintext). Callers must
     * store them encrypted and show them to the user only once.
     *
     * @return list<string>
     */
    public function generateRecoveryCodes(): array
    {
        $count = max(1, (int) config('security.mfa.recovery_code_count'));

        return collect(range(1, $count))
            ->map(fn (): string => Str::upper(Str::random(5).'-'.Str::random(5)))
            ->values()
            ->all();
    }
}
