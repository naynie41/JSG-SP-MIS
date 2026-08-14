<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use InvalidArgumentException;

/**
 * Guards the one thing every registry record must be able to answer: where it came from
 * (FR-REG-03, CLAUDE.md §8).
 *
 * Ingestion is bulk/source-only, and each door — file import, REST intake, connector
 * sync, offline batch — knows its own source. So a record without one is not a user
 * error to be defaulted away; it is a code path that forgot, and defaulting it produces
 * a plausible-looking lie that the audit trail cannot distinguish from the truth.
 *
 * Shared by {@see Beneficiary} and
 * {@see Household} so the two cannot drift on a rule that
 * only means anything if it holds everywhere.
 */
final class RegistrationSourceRule
{
    /**
     * @throws InvalidArgumentException when the source is absent or not assignable
     */
    public static function assertAssignable(mixed $source, string $entity): void
    {
        if ($source === null || $source === '') {
            throw new InvalidArgumentException(
                "A {$entity} cannot be saved without a registration source. Ingestion is source-only "
                .'(CLAUDE.md §8); set the source of the door the record came through: '
                .implode(', ', RegistrationSource::assignableValues()).'.'
            );
        }

        $case = $source instanceof RegistrationSource
            ? $source
            : RegistrationSource::tryFrom((string) $source);

        if ($case === null) {
            throw new InvalidArgumentException(
                "“{$source}” is not a registration source. Allowed: "
                .implode(', ', RegistrationSource::assignableValues()).'.'
            );
        }

        if (! $case->isAssignable()) {
            throw new InvalidArgumentException(
                "A {$entity} cannot be saved with the “{$case->value}” registration source: manual "
                .'single-record entry was removed (CLAUDE.md §8), so no new record can legitimately '
                .'claim that origin. Historical rows keep it.'
            );
        }
    }
}
