<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services\Verifiers;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Contracts\BenefitVerifier;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Models\Benefit;

/**
 * Field-officer confirmation (PRD FR-BEN-04): the recording officer attests to the
 * delivery. Always available; the stored reference is the officer's note, or an
 * attestation stamp when none is supplied.
 */
class FieldConfirmationVerifier implements BenefitVerifier
{
    public function method(): VerificationMethod
    {
        return VerificationMethod::FieldConfirmation;
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function verify(Benefit $benefit, ?string $reference, User $actor): string
    {
        $reference = $reference !== null ? trim($reference) : '';

        return $reference !== '' ? $reference : 'Field-confirmed by officer '.$actor->id;
    }
}
