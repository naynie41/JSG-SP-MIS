<?php

declare(strict_types=1);

namespace App\Domain\Programme\Rules;

use App\Domain\Programme\Models\Programme;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A programme that can carry NEW work (PRD §10): available to the caller, cleared
 * for use, and not archived.
 *
 * Before this, `exists:programmes,id` was the only check anywhere a programme was
 * selected, so nothing stopped an archived catalog entry being used — the archive
 * hid it from the picker and no more. Hiding is not enforcing: the API is reachable
 * without the picker. The same argument covers the two cases ownership added: an id
 * belonging to another MDA, and one still waiting for approval. `exists:` reads the
 * table directly and so sees past the MDA scope entirely; the lookup here is scoped.
 *
 * {@see IsAvailableProgramme} is this rule without the archive check, for the places
 * where work under an archived programme is legitimate (a final payment to someone
 * already enrolled). The two are kept as separate rules rather than one with a flag:
 * a validation rule that means different things depending on a constructor argument
 * is the kind of thing that gets passed the wrong argument.
 *
 * The archive check stays scoped to ARCHIVED only, deliberately. Draft and closed
 * programmes are equally selectable today; widening this rule to the whole lifecycle
 * would be a second, unrelated behaviour change riding along.
 */
class IsRunnableProgramme implements ValidationRule
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

        if ($programme->isArchived()) {
            $fail(sprintf(
                'The programme "%s" is archived and cannot be used for new activities.',
                $programme->name,
            ));

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
