<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

/**
 * Lets a validation rule quote what the SOURCE FILE said, not the normalized value it
 * is judging (FR-REG-16, FR-REG-05).
 *
 * Validation deliberately runs on normalized values — `+234 803 123 4567` and
 * `08031234567` are one number and must be judged as one. But an error report is read by
 * someone holding the original spreadsheet: telling them `8031234567` is the wrong
 * length, when their cell reads `+234 (0) 803 123 456`, sends them looking for a string
 * that is not in their file.
 *
 * The importer puts the untouched row under {@see self::ORIGINALS_KEY} in the data it
 * validates. Paths that have no separate original — the REST intake, where the submitted
 * value IS the original — simply omit it and the rule falls back to the value in hand.
 */
trait QuotesOriginalInput
{
    /** @var array<string, mixed> */
    protected array $data = [];

    /**
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /** What the source wrote for this field, falling back to the value being judged. */
    protected function originalFor(string $attribute, mixed $value): string
    {
        $originals = $this->data[OriginalInput::KEY] ?? [];
        $original = is_array($originals) ? ($originals[$attribute] ?? null) : null;

        return (string) ($original === null || $original === '' ? $value : $original);
    }
}
