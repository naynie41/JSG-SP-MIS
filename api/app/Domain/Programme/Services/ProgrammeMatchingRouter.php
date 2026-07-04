<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Registry\Contracts\BeneficiaryRouter;
use App\Domain\Registry\Models\Beneficiary;

/**
 * The Phase 4 implementation of the FR-OWN-04 auto-route hook: it suggests the MDA
 * whose programme best matches an identified need (top eligible suggestion from
 * {@see ProgrammeMatcher}). It only SUGGESTS — it never assigns or changes
 * ownership; a human confirms the assignment via the routing endpoint.
 */
class ProgrammeMatchingRouter implements BeneficiaryRouter
{
    public function __construct(private readonly ProgrammeMatcher $matcher) {}

    public function suggestMdaFor(Beneficiary $beneficiary, ?string $need = null): ?string
    {
        foreach ($this->matcher->suggest($beneficiary, $need) as $suggestion) {
            if ($suggestion['eligible'] === true && $suggestion['owner_mda'] !== null) {
                return $suggestion['owner_mda']['id'];
            }
        }

        return null;
    }
}
