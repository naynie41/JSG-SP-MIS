<?php

declare(strict_types=1);

namespace App\Domain\Graduation\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Graduation\Enums\CriteriaLogic;
use App\Domain\Graduation\Enums\GraduationCriterionType;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use Illuminate\Support\Carbon;

/**
 * Tracks a beneficiary/household's progress toward graduation (FR-GRD-02): it evaluates
 * the enrolling MDA's active {@see GraduationCriteria} for the enrolment's programme
 * against real data — benefits delivered, months enrolled, total value — and reports
 * per-rule and overall progress. Automatic rules are computed; the manual rule is only
 * ever met by an explicit officer decision (never auto-satisfied).
 */
class GraduationProgressService
{
    /**
     * @return array<string, mixed>
     */
    public function forEnrollment(Enrollment $enrollment): array
    {
        $criteria = $this->activeCriteria($enrollment);
        $alreadyGraduated = $enrollment->status === EnrollmentStatus::Graduated;

        if ($criteria === null) {
            return [
                'criteria_id' => null,
                'logic' => null,
                'eligible' => false,
                'progress' => 0.0,
                'rules' => [],
                'already_graduated' => $alreadyGraduated,
                'message' => 'No graduation criteria defined for this programme by this MDA.',
            ];
        }

        [$benefitCount, $benefitValue] = $this->ledgerTotals($enrollment);
        $months = $enrollment->enrolled_on->diffInMonths(Carbon::now());

        $rules = [];
        foreach ($criteria->rules as $rule) {
            $type = GraduationCriterionType::tryFrom((string) ($rule['type'] ?? ''));
            if ($type === null) {
                continue;
            }
            $threshold = (float) ($rule['threshold'] ?? 0);
            $current = match ($type) {
                GraduationCriterionType::BenefitsReceived => (float) $benefitCount,
                GraduationCriterionType::MonthsEnrolled => (float) $months,
                GraduationCriterionType::TotalBenefitValue => (float) $benefitValue,
                GraduationCriterionType::Manual => 0.0,
            };
            $met = $type->isAutomatic() && $threshold > 0 && $current >= $threshold;
            $progress = $type === GraduationCriterionType::Manual
                ? 0.0
                : ($threshold > 0 ? min(1.0, $current / $threshold) : ($met ? 1.0 : 0.0));

            $rules[] = [
                'type' => $type->value,
                'label' => $type->label(),
                'current' => $current,
                'threshold' => $threshold,
                'met' => $met,
                'progress' => round($progress, 3),
            ];
        }

        $metFlags = array_column($rules, 'met');
        $eligible = $rules !== [] && ($criteria->logic === CriteriaLogic::Any
            ? in_array(true, $metFlags, true)
            : ! in_array(false, $metFlags, true));

        $overall = $rules === [] ? 0.0 : round(array_sum(array_column($rules, 'progress')) / count($rules), 3);

        return [
            'criteria_id' => $criteria->id,
            'logic' => $criteria->logic->value,
            'eligible' => $eligible,
            'progress' => $overall,
            'rules' => $rules,
            'already_graduated' => $alreadyGraduated,
        ];
    }

    public function activeCriteria(Enrollment $enrollment): ?GraduationCriteria
    {
        return GraduationCriteria::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('programme_id', $enrollment->programme_id)
            ->where('owner_mda_id', $enrollment->mda_id)
            ->where('is_active', true)
            ->latest('created_at')
            ->first();
    }

    /**
     * Full-ledger totals for the beneficiary in this programme (cross-MDA history).
     *
     * @return array{0: int, 1: int}
     */
    private function ledgerTotals(Enrollment $enrollment): array
    {
        if ($enrollment->beneficiary_id === null) {
            return [0, 0];
        }

        $query = Benefit::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('beneficiary_id', $enrollment->beneficiary_id)
            ->where('programme_id', $enrollment->programme_id);

        return [(int) $query->count(), (int) $query->sum('monetary_value')];
    }
}
