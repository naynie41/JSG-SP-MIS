<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Contracts\BeneficiaryRouter;
use App\Domain\Registry\Models\Beneficiary;

/**
 * The Phase 4 implementation of the FR-OWN-04 auto-route hook: it suggests an MDA a
 * beneficiary could be routed to for an identified need (top eligible suggestion
 * from {@see ProgrammeMatcher}). Programmes are a GLOBAL catalog (§10) with no owner,
 * so the suggested MDA is one that RUNS the matching programme through one of its
 * activities. It only SUGGESTS — it never assigns or changes ownership; a human
 * confirms the assignment via the routing endpoint.
 */
class ProgrammeMatchingRouter implements BeneficiaryRouter
{
    public function __construct(private readonly ProgrammeMatcher $matcher) {}

    public function suggestMdaFor(Beneficiary $beneficiary, ?string $need = null): ?string
    {
        foreach ($this->matcher->suggest($beneficiary, $need) as $suggestion) {
            if ($suggestion['eligible'] !== true) {
                continue;
            }

            // An MDA that runs this catalog programme (has an activity for it).
            $mdaId = Activity::query()
                ->withoutGlobalScope(MdaScope::class)
                ->where('programme_id', $suggestion['programme_id'])
                ->value('owner_mda_id');

            if ($mdaId !== null) {
                return (string) $mdaId;
            }
        }

        return null;
    }
}
