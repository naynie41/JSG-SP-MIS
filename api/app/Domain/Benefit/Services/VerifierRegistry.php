<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Benefit\Contracts\BenefitVerifier;
use App\Domain\Benefit\Enums\VerificationMethod;
use InvalidArgumentException;

/**
 * Resolves the {@see BenefitVerifier} for a verification method (PRD FR-BEN-04).
 * Register a method's implementation here; enabling a stubbed method later means
 * swapping its binding, with no change to callers.
 */
class VerifierRegistry
{
    /** @var array<string, BenefitVerifier> */
    private array $verifiers = [];

    /**
     * @param  iterable<BenefitVerifier>  $verifiers
     */
    public function __construct(iterable $verifiers = [])
    {
        foreach ($verifiers as $verifier) {
            $this->verifiers[$verifier->method()->value] = $verifier;
        }
    }

    public function for(VerificationMethod $method): BenefitVerifier
    {
        return $this->verifiers[$method->value]
            ?? throw new InvalidArgumentException("No verifier registered for {$method->value}.");
    }

    /**
     * Verification methods that can actually run right now (for surfacing to the UI).
     *
     * @return list<string>
     */
    public function availableMethods(): array
    {
        return array_values(array_map(
            static fn (BenefitVerifier $v) => $v->method()->value,
            array_filter($this->verifiers, static fn (BenefitVerifier $v) => $v->isAvailable()),
        ));
    }
}
