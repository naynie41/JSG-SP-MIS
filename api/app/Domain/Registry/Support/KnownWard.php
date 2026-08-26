<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Reference\Services\ReferenceDataCache;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Ward must resolve to the ward lookup — WHEN that lookup exists (FR-REG-09).
 *
 * Ward is the one administrative field with no committed enum behind it. LGA has 27
 * values in code; wards come from an AUTHORITATIVE maintainer-supplied dataset that is
 * loaded with `reference:load-divisions`, and GEO.1 locked the reason: a guessed or
 * partial ward list is worse than free text, because it looks authoritative.
 *
 * So the rule follows the data. With wards loaded, a ward that is not in the list for
 * the row's LGA is dropped (a ward valid in a DIFFERENT LGA is still wrong here — that
 * is the mistake this actually catches). With the table empty, EVERY value passes,
 * because an empty allowed-set is not a strict lookup — it is a rule that would silently
 * null the ward on every row of every import, deleting real data to satisfy a list
 * nobody has supplied yet.
 *
 * Ward is NON-identity, so a failure drops the field and keeps the row.
 */
class KnownWard implements DataAwareRule, DescribesConstraint, ValidationRule
{
    /** @var array<string, mixed> */
    private array $data = [];

    public function __construct(
        private readonly ReferenceDataCache $reference = new ReferenceDataCache,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function constraintToken(): string
    {
        return 'in:wards';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return; // presence is `required`'s job, not this rule's
        }

        $index = $this->reference->wardKeysByLgaCode();

        if ($index === []) {
            return; // no authoritative list loaded — see the class docblock
        }

        $normalizer = new NormalizationService;
        $ward = $normalizer->enumKey((string) $value);
        $lga = $normalizer->enumKey(is_string($this->data['lga'] ?? null) ? $this->data['lga'] : '');

        /*
         * COVERAGE IS PER-LGA, and so is the rule.
         *
         * The supplied dataset lists wards for some LGAs and not others — the source
         * gives only prose for several ("covers multiple historical rural and urban
         * wards"). For those, the table holds the LGA with zero wards, and that absence
         * says nothing about whether a given ward is real. Enforcing a list we do not
         * have would drop every ward in those LGAs from every import: silent, total,
         * and indistinguishable from the data never having been sent.
         *
         * So the rule binds exactly where there is something to bind to. It is the same
         * reasoning that keeps it silent while the whole table is empty, applied one
         * level down.
         */
        if ($lga === null || ! isset($index[$lga]) || $index[$lga] === []) {
            return;
        }

        $allowed = $index[$lga];

        if ($ward === null || ! isset($allowed[$ward])) {
            $fail("Ward must be one of the wards recorded for the LGA on this row ({$this->countOf($allowed)} available).");
        }
    }

    /** @param  array<string, string>  $wards */
    private function countOf(array $wards): string
    {
        return (string) count($wards);
    }
}
