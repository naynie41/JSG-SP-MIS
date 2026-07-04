<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Illuminate\Support\Carbon;

/**
 * Evaluates a programme's structured eligibility criteria against an enrollment
 * target (PRD FR-PRG-03). Advisory by default — the caller decides whether an
 * unmet result flags or blocks (per the programme's `enforce_eligibility`).
 *
 * A criterion is `{attribute, operator?, value}`. Recognised attributes are
 * evaluated; unrecognised/label-only criteria are treated as informational and
 * never cause a flag (so existing loosely-structured eligibility stays valid).
 */
class EligibilityEvaluator
{
    /** Attributes this evaluator understands; others are informational only. */
    private const EVALUABLE = ['lga', 'ward', 'gender', 'status', 'age_min', 'age_max'];

    /**
     * @param  array<int, mixed>|null  $criteria  raw JSON criteria (items may be malformed)
     * @return array{eligible: bool, unmet: list<string>}
     */
    public function evaluate(?array $criteria, Beneficiary|Household $target): array
    {
        $subject = $this->subjectAttributes($target);
        $unmet = [];

        foreach ($criteria ?? [] as $criterion) {
            $attribute = is_array($criterion) ? ($criterion['attribute'] ?? null) : null;
            if (! is_string($attribute) || ! in_array($attribute, self::EVALUABLE, true)) {
                continue; // informational / not evaluable
            }
            if (! $this->satisfies($attribute, $criterion['value'] ?? null, $subject)) {
                $unmet[] = $attribute;
            }
        }

        return ['eligible' => $unmet === [], 'unmet' => array_values(array_unique($unmet))];
    }

    /**
     * @param  array<string, mixed>  $subject
     */
    private function satisfies(string $attribute, mixed $value, array $subject): bool
    {
        $age = $subject['age'] ?? null;

        return match ($attribute) {
            'age_min' => is_numeric($value) && $age !== null && $age >= (int) $value,
            'age_max' => is_numeric($value) && $age !== null && $age <= (int) $value,
            default => $this->matches($subject[$attribute] ?? null, $value),
        };
    }

    /** Equality, or membership when the criterion value is a list. */
    private function matches(mixed $actual, mixed $expected): bool
    {
        if ($actual === null) {
            return false;
        }
        $actual = mb_strtolower((string) $actual);

        if (is_array($expected)) {
            return in_array($actual, array_map(static fn ($v) => mb_strtolower((string) $v), $expected), true);
        }

        return $actual === mb_strtolower((string) $expected);
    }

    /**
     * @return array<string, mixed>
     */
    private function subjectAttributes(Beneficiary|Household $target): array
    {
        if ($target instanceof Household) {
            return ['lga' => $target->lga, 'ward' => $target->ward];
        }

        $age = $target->date_of_birth !== null
            ? Carbon::parse($target->date_of_birth)->age
            : null;

        return [
            'lga' => $target->lga,
            'ward' => $target->ward,
            'gender' => $target->gender?->value,
            'status' => $target->status->value,
            'age' => $age,
        ];
    }
}
