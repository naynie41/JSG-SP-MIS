<?php

declare(strict_types=1);

namespace App\Domain\Registry\Contracts;

use App\Domain\Registry\Models\Beneficiary;

/**
 * Auto-route/assign extension point (PRD FR-OWN-04): decide the MDA a beneficiary
 * should be routed to based on an identified need (e.g. a programme match).
 *
 * Phase 2 ships a no-op implementation (NullBeneficiaryRouter). Programme
 * matching lands in Phase 4 by binding a real implementation to this contract —
 * no caller changes required.
 */
interface BeneficiaryRouter
{
    /**
     * Suggest the MDA id a beneficiary should be routed to for a given need,
     * or null when no routing applies. Never changes ownership by itself; the
     * caller decides whether to act (subject to ownership/consent rules).
     */
    public function suggestMdaFor(Beneficiary $beneficiary, ?string $need = null): ?string;
}
