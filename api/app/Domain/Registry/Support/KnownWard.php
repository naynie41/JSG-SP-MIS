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

        // With a known LGA the ward must belong to THAT LGA. Without one (the LGA was
        // itself unrecognised and is being dropped) fall back to "a real ward somewhere",
        // so one bad cell does not cascade into a second misleading error on the row.
        $allowed = $lga !== null && isset($index[$lga])
            ? $index[$lga]
            : array_merge(...array_values($index));

        if ($ward === null || ! isset($allowed[$ward])) {
            $fail($lga !== null && isset($index[$lga])
                ? "Ward must be one of the wards recorded for the LGA on this row ({$this->countOf($index[$lga])} available)."
                : 'Ward must be one of the wards in the administrative division list.');
        }
    }

    /** @param  array<string, string>  $wards */
    private function countOf(array $wards): string
    {
        return (string) count($wards);
    }
}
