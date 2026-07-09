<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Programme matching for auto-routing (PRD FR-OWN-04). Given a beneficiary and an
 * optional identified need, it SUGGESTS active programmes across MDAs whose type
 * fits and whose eligibility the beneficiary meets — ranked eligible-first. It only
 * suggests; assignment is an explicit, separate, audited step (never silent).
 */
class ProgrammeMatcher
{
    private const MAX_SUGGESTIONS = 20;

    public function __construct(private readonly EligibilityEvaluator $eligibility) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function suggest(Beneficiary $beneficiary, ?string $need = null): array
    {
        $alreadyEnrolled = Enrollment::query()
            ->withoutGlobalScopes()
            ->where('beneficiary_id', $beneficiary->id)
            ->where('status', 'enrolled')
            ->pluck('programme_id')
            ->all();

        // The global catalog: suggest any active individual programme.
        $programmes = Programme::query()
            ->where('type', ProgrammeType::Individual) // routing an individual beneficiary
            ->where('status', ProgrammeStatus::Active)
            ->whereNotIn('id', $alreadyEnrolled)
            ->get();

        $needTerm = $need !== null ? mb_strtolower(trim($need)) : null;

        return $programmes
            ->map(function (Programme $programme) use ($beneficiary, $needTerm): array {
                $eligibility = $this->eligibility->evaluate($programme->eligibility, $beneficiary);

                return [
                    'programme_id' => $programme->id,
                    'name' => $programme->name,
                    'type' => $programme->type->value,
                    'benefit_category' => $programme->benefit_category,
                    'eligible' => $eligibility['eligible'],
                    'unmet' => $eligibility['unmet'],
                    'matches_need' => $this->matchesNeed($programme, $needTerm),
                ];
            })
            ->filter(fn (array $s) => $s['matches_need'])
            ->sortByDesc(fn (array $s) => $s['eligible'] ? 1 : 0)
            ->take(self::MAX_SUGGESTIONS)
            ->values()
            ->all();
    }

    private function matchesNeed(Programme $programme, ?string $needTerm): bool
    {
        if ($needTerm === null || $needTerm === '') {
            return true;
        }

        $haystack = mb_strtolower($programme->name.' '.($programme->objective ?? ''));
        if (str_contains($haystack, $needTerm)) {
            return true;
        }

        foreach ($programme->eligibility ?? [] as $criterion) {
            $value = $criterion['value'] ?? null;
            if (is_scalar($value) && str_contains(mb_strtolower((string) $value), $needTerm)) {
                return true;
            }
        }

        return false;
    }
}
