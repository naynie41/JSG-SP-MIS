<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services\Verifiers;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Contracts\BenefitVerifier;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Exceptions\VerificationUnavailableException;
use App\Domain\Benefit\Models\Benefit;

/**
 * Beneficiary signature (PRD FR-BEN-04): the stored reference points to a captured
 * signature (e.g. an uploaded document id or capture reference). Always available;
 * the caller must supply the reference (enforced by the request rules).
 */
class SignatureVerifier implements BenefitVerifier
{
    public function method(): VerificationMethod
    {
        return VerificationMethod::Signature;
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function verify(Benefit $benefit, ?string $reference, User $actor): string
    {
        $reference = $reference !== null ? trim($reference) : '';
        if ($reference === '') {
            // Defensive: the request already requires this for signature.
            throw new VerificationUnavailableException('A signature reference is required for signature verification.');
        }

        return $reference;
    }
}
