<?php

declare(strict_types=1);

namespace App\Domain\Programme\Rules;

use App\Domain\Programme\Models\Programme;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A programme the caller may actually use: one their MDA can see, and one that has
 * been cleared for use (§10, revised).
 *
 * Split from {@see IsRunnableProgramme}, which adds the archive check on top. Some
 * work legitimately continues under an ARCHIVED programme — a final payment to
 * someone already enrolled — and blocking that here would change a settled rule
 * while fixing a different one. Every entry point that creates work still needs
 * these two checks, because `exists:programmes,id` reads the table directly and so
 * sees past the MDA scope entirely.
 *
 * The MDA-ASSIGNMENT check (§10, revised 2026-11-05) deliberately does NOT appear
 * here, only in {@see IsRunnableProgramme}.
 *
 * It is the same argument the archive check already settles, and the two must answer
 * the same way or the split is meaningless. This rule governs finishing work in
 * flight — recording a benefit for someone already enrolled — and an administrator
 * unassigning a programme is a statement about what the MDA may START, not an
 * instruction to abandon a payment owed to a beneficiary already on the register.
 * Blocking it here would strand exactly the case the split exists for, and would hide
 * the consequence inside an unrelated administrative action.
 *
 * The benefit-recording picker is narrowed all the same: it reads `/programmes`, which
 * returns the MDA's assigned catalog. A programme no longer offered therefore does not
 * appear for NEW delivery, while delivery under an existing enrolment still completes.
 */
class IsAvailableProgramme implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return; // `required`/`uuid`/`exists` report their own failures.
        }

        // Scoped on purpose: the central catalog plus the caller's own programmes.
        $programme = Programme::query()
            ->withArchived()
            ->whereKey($value)
            ->first();

        if ($programme === null) {
            // No such programme, or one belonging to another MDA. The message does not
            // distinguish the two, so it cannot confirm someone else's programme id.
            $fail('That programme is not available to your MDA.');

            return;
        }

        if (! $programme->isApproved()) {
            $fail(sprintf(
                'The programme "%s" is still waiting for approval and cannot be used yet.',
                $programme->name,
            ));
        }
    }
}
