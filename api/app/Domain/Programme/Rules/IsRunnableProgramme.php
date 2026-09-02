<?php

declare(strict_types=1);

namespace App\Domain\Programme\Rules;

use App\Domain\Programme\Models\Programme;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A programme must not be archived to carry NEW work (PRD §10).
 *
 * Before this, `exists:programmes,id` was the only check anywhere a programme was
 * selected, so nothing stopped an archived catalog entry being used — the archive
 * hid it from the picker and no more. Hiding is not enforcing: the API is reachable
 * without the picker.
 *
 * Scoped to ARCHIVED only, deliberately. Draft, closed and archived programmes are
 * all equally selectable today; widening this rule to the whole lifecycle would be a
 * second, unrelated behaviour change riding along on an archive feature. Existing
 * work under an archived programme is untouched — this gates new work only.
 */
class IsRunnableProgramme implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return; // `required`/`uuid`/`exists` report their own failures.
        }

        $programme = Programme::query()
            ->withArchived()
            ->whereKey($value)
            ->first();

        if ($programme === null) {
            return; // `exists` reports this.
        }

        if ($programme->isArchived()) {
            $fail(sprintf(
                'The programme "%s" is archived and cannot be used for new activities.',
                $programme->name,
            ));
        }
    }
}
