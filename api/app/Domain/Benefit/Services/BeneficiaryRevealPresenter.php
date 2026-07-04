<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Builds the programme(s) + benefits-received sections of the FR-DUP-04 match
 * reveal from real enrolment + ledger data. The reveal is a cross-MDA coordination
 * signal (so it reads across MDAs), but it respects visibility: programme names,
 * benefit types/dates and the delivering MDA are shown to any reveal viewer, while
 * exact monetary values are visible only to the beneficiary's owner MDA or
 * oversight.
 */
class BeneficiaryRevealPresenter
{
    private const MAX_ITEMS = 10;

    /**
     * @return array{programmes: list<array<string, mixed>>, benefits: array<string, mixed>}
     */
    public function sections(Beneficiary $beneficiary, ?User $viewer): array
    {
        $canSeeValue = $viewer !== null
            && ($viewer->canAccessAllMdas() || $viewer->mda_id === $beneficiary->owner_mda_id);

        return [
            'programmes' => $this->programmes($beneficiary),
            'benefits' => $this->benefits($beneficiary, $canSeeValue),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function programmes(Beneficiary $beneficiary): array
    {
        return Enrollment::query()
            ->withoutGlobalScopes()
            ->where('beneficiary_id', $beneficiary->id)
            ->with(['programme' => fn ($q) => $q->withoutGlobalScopes()
                ->with(['ownerMda' => fn ($m) => $m->withoutGlobalScopes()->select('id', 'name')])])
            ->get()
            ->map(fn (Enrollment $e) => [
                'programme_id' => $e->programme_id,
                'name' => $e->programme->name,
                'owner_mda' => ['id' => $e->programme->ownerMda->id, 'name' => $e->programme->ownerMda->name],
                'status' => $e->status->value,
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function benefits(Beneficiary $beneficiary, bool $canSeeValue): array
    {
        $benefits = Benefit::query()
            ->withoutGlobalScopes()
            ->where('beneficiary_id', $beneficiary->id)
            ->where('status', '!=', BenefitStatus::Reversed->value)
            ->with(['mda' => fn ($m) => $m->withoutGlobalScopes()->select('id', 'name')])
            ->latest('delivery_date')
            ->latest('id')
            ->get();

        $items = $benefits->take(self::MAX_ITEMS)->map(fn (Benefit $b) => [
            'benefit_type' => $b->benefit_type->value,
            'delivery_date' => $b->delivery_date->toDateString(),
            'delivering_mda' => ['id' => $b->mda->id, 'name' => $b->mda->name],
            // Monetary value is masked from non-owner MDAs (visibility rule).
            'monetary_value' => $canSeeValue ? $b->monetary_value : null,
        ])->values()->all();

        return [
            'summary' => [
                'count' => $benefits->count(),
                'total_value' => $canSeeValue ? (int) $benefits->sum('monetary_value') : null,
                'last_delivery_date' => $benefits->first()?->delivery_date?->toDateString(),
                'types' => $benefits->map(fn (Benefit $b) => $b->benefit_type->value)->unique()->values()->all(),
            ],
            'items' => $items,
        ];
    }
}
