<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services\Verifiers;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Contracts\BenefitVerifier;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Exceptions\VerificationUnavailableException;
use App\Domain\Benefit\Models\Benefit;

/**
 * OTP verification (PRD FR-BEN-04) — STUB. Requires an external SMS/OTP gateway
 * that is not configured, so it reports unavailable and never fabricates a result.
 * Provide the gateway credentials and bind a real implementation to enable it.
 */
class OtpVerifier implements BenefitVerifier
{
    public function method(): VerificationMethod
    {
        return VerificationMethod::Otp;
    }

    public function isAvailable(): bool
    {
        return false;
    }

    public function verify(Benefit $benefit, ?string $reference, User $actor): string
    {
        throw new VerificationUnavailableException(
            'OTP verification is unavailable: it requires an external SMS/OTP gateway that is not configured. '
            .'Provide the gateway access to enable it.',
        );
    }
}
