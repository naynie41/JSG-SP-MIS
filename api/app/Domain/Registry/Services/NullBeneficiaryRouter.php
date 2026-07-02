<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Registry\Contracts\BeneficiaryRouter;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Default no-op router (FR-OWN-04 stub). Programme-matching arrives in Phase 4;
 * until then, no auto-routing is suggested.
 */
class NullBeneficiaryRouter implements BeneficiaryRouter
{
    public function suggestMdaFor(Beneficiary $beneficiary, ?string $need = null): ?string
    {
        return null;
    }
}
