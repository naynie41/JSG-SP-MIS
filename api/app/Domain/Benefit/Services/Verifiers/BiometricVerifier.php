<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services\Verifiers;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Contracts\BenefitVerifier;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Exceptions\VerificationUnavailableException;
use App\Domain\Benefit\Models\Benefit;

/**
 * Biometric-reference verification (PRD FR-BEN-04) — STUB. Requires external
 * biometric/NIMC access that is not configured, so it reports unavailable and
 * never fabricates a result. Bind a real implementation once that access exists.
 */
class BiometricVerifier implements BenefitVerifier
{
    public function method(): VerificationMethod
    {
        return VerificationMethod::Biometric;
    }

    public function isAvailable(): bool
    {
        return false;
    }

    public function verify(Benefit $benefit, ?string $reference, User $actor): string
    {
        throw new VerificationUnavailableException(
            'Biometric verification is unavailable: it requires external biometric/NIMC access that is not configured. '
            .'Provide that access to enable it.',
        );
    }
}
