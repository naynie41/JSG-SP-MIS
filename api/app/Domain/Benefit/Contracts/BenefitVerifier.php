<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Contracts;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Exceptions\VerificationUnavailableException;
use App\Domain\Benefit\Models\Benefit;

/**
 * A pluggable delivery-verification strategy (PRD FR-BEN-04). Field-confirmation and
 * signature are implemented now; OTP and biometric are stubbed as unavailable
 * behind this same interface until the external access they need is provided —
 * enabling one is just binding a real implementation (no caller changes).
 */
interface BenefitVerifier
{
    public function method(): VerificationMethod;

    /** Whether this method can actually verify in the current environment. */
    public function isAvailable(): bool;

    /**
     * Verify the delivery and return the reference to store on the ledger entry.
     *
     * @throws VerificationUnavailableException when the method is not available
     */
    public function verify(Benefit $benefit, ?string $reference, User $actor): string;
}
