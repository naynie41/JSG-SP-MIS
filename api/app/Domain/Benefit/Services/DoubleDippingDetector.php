<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Benefit\Enums\BenefitFlagStatus;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Models\BenefitFlag;
use App\Domain\Benefit\Models\DoubleDippingRule;
use Illuminate\Support\Carbon;

/**
 * Detects potential double-dipping (PRD FR-BEN-05): after a delivery is recorded,
 * it looks — across ALL MDAs — for another delivery of the SAME benefit type to the
 * SAME beneficiary by a DIFFERENT MDA within an active rule's window, and raises a
 * flag. It only flags; it NEVER blocks the delivery. Runs as a system function
 * (scope-bypassed).
 */
class DoubleDippingDetector
{
    public function check(Benefit $benefit): void
    {
        $type = $benefit->benefit_type->value;

        $rules = DoubleDippingRule::query()
            ->where('is_active', true)
            ->get()
            ->filter(fn (DoubleDippingRule $rule) => $rule->appliesTo($type));

        foreach ($rules as $rule) {
            $deliveredOn = Carbon::parse($benefit->delivery_date);

            $conflicts = Benefit::query()
                ->withoutGlobalScopes()
                ->where('beneficiary_id', $benefit->beneficiary_id)
                ->where('benefit_type', $type)
                ->where('mda_id', '!=', $benefit->mda_id) // a DIFFERENT delivering MDA
                ->where('status', '!=', BenefitStatus::Reversed->value)
                ->whereKeyNot($benefit->id)
                ->whereBetween('delivery_date', [
                    $deliveredOn->copy()->subDays($rule->period_days)->toDateString(),
                    $deliveredOn->copy()->addDays($rule->period_days)->toDateString(),
                ])
                ->get();

            foreach ($conflicts as $conflict) {
                $this->raiseFlag($benefit, $conflict, $rule);
            }
        }
    }

    private function raiseFlag(Benefit $new, Benefit $conflict, DoubleDippingRule $rule): void
    {
        BenefitFlag::firstOrCreate(
            ['benefit_id' => $new->id, 'related_benefit_id' => $conflict->id],
            [
                'beneficiary_id' => $new->beneficiary_id,
                'rule_id' => $rule->id,
                'benefit_type' => $new->benefit_type->value,
                'from_mda_id' => $new->mda_id,
                'other_mda_id' => $conflict->mda_id,
                'status' => BenefitFlagStatus::Open,
                'reason' => "Same benefit type '{$new->benefit_type->value}' delivered by another MDA within {$rule->period_days} days ({$rule->name}).",
            ],
        );
    }
}
