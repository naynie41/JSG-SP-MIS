<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;

/**
 * Programme matching for auto-routing (PRD FR-OWN-04). Given a beneficiary and an
 * optional identified need, it SUGGESTS active programmes whose type fits and whose
 * eligibility the beneficiary meets — ranked eligible-first. It only suggests;
 * assignment is an explicit, separate, audited step (never silent).
 *
 * "Across MDAs" until 2026-11-05: the suggestions are now narrowed to the programmes
 * the CALLER's MDA has been assigned, because the caller is the MDA that would enrol
 * the beneficiary, and §10 no longer lets it enrol into a programme it does not
 * deliver. Oversight roles still see the whole catalog.
 */
class ProgrammeMatcher
{
    private const MAX_SUGGESTIONS = 20;

    public function __construct(
        private readonly EligibilityEvaluator $eligibility,
        private readonly ProgrammeAssignmentService $assignments,
    ) {}

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

        // The catalog the CALLER may deliver: suggest any active individual programme
        // assigned to their MDA (§10, revised 2026-11-05).
        //
        // Narrowed here as well as at the confirm step, because suggest-then-confirm
        // only works if the two agree: offering a programme the assignment check then
        // refuses turns a suggestion into a dead end, and the officer has no way to
        // tell which of the twenty suggestions are real.
        $programmes = $this->assignments->narrow(Programme::query(), Auth::user())
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
