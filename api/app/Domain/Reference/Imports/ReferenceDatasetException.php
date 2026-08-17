<?php

declare(strict_types=1);

namespace App\Domain\Reference\Imports;

use RuntimeException;

/**
 * Raised when the authoritative administrative-divisions dataset is absent or is not
 * credibly a Jigawa dataset.
 *
 * Every message here is written for a maintainer standing at a terminal: it says what
 * was wrong, where the file was looked for, and where a real one is sourced. The
 * alternative — seeding placeholder wards — is the failure this class exists to
 * prevent: a fabricated ward list is worse than free text, because free text is
 * visibly unverified while a populated lookup table looks authoritative.
 */
class ReferenceDatasetException extends RuntimeException
{
    /** Where a maintainer obtains a real dataset. Appended to the absent-file message. */
    private const SOURCES = <<<'TXT'
        Source an authoritative dataset from one of:
          - OCHA HDX      "Nigeria - Administrative Boundaries" (admin 1-3), data.humdata.org
          - GRID3 Nigeria operational LGA + Ward boundaries, grid3.org
          - Jigawa State administrative records (the State's own ward register)
        Filter to Jigawa State, then export the columns described in
        app/Domain/Reference/README.md.
        TXT;

    public static function fileMissing(string $path): self
    {
        return new self(
            "Administrative-divisions dataset not found at:\n  {$path}\n\n".
            "This seeder will NOT invent LGA or ward data, so there is nothing to seed.\n".
            "Place the file at that path, or point REFERENCE_DIVISIONS_PATH at it.\n\n".
            self::SOURCES
        );
    }

    public static function unreadable(string $path): self
    {
        return new self("Administrative-divisions dataset at {$path} could not be read.");
    }

    public static function empty(string $path): self
    {
        return new self(
            "Administrative-divisions dataset at {$path} contains no rows. ".
            'An empty dataset is treated as an error rather than a no-op, because seeding '.
            'nothing looks identical to seeding successfully.'
        );
    }

    public static function unsupportedFormat(string $extension): self
    {
        return new self(
            "Unsupported dataset format '.{$extension}'. Supply a .csv or .json file ".
            '(see app/Domain/Reference/README.md).'
        );
    }

    public static function malformed(string $path, string $detail): self
    {
        return new self("Administrative-divisions dataset at {$path} is malformed: {$detail}");
    }

    /**
     * @param  list<string>  $missing
     */
    public static function missingColumns(array $missing, string $found): self
    {
        return new self(
            'Administrative-divisions dataset is missing required column(s): '.implode(', ', $missing).".\n".
            "Found: {$found}\n".
            'Required: lga_name, ward_name (lga_code and ward_code are optional and are '.
            'derived from the names when absent).'
        );
    }

    /**
     * The dataset names LGAs that are not Jigawa's — almost always the wrong file, or
     * the national file left unfiltered.
     *
     * @param  list<string>  $unknown
     */
    public static function unknownLgas(array $unknown): self
    {
        sort($unknown);

        return new self(
            "The dataset contains LGA(s) that are not in Jigawa State:\n  ".implode(', ', $unknown)."\n\n".
            'Jigawa has exactly 27 LGAs (App\Domain\Registry\Enums\Lga). This usually means the '.
            'file covers all of Nigeria and was not filtered to Jigawa, or the LGA names do not '.
            "match the registry's spelling. Nothing was loaded."
        );
    }

    /**
     * The dataset covers only part of the state. Loading it would produce a lookup
     * table that silently omits real places — the "partial list that looks
     * authoritative" case.
     *
     * @param  list<string>  $missing
     */
    public static function incompleteLgas(array $missing): self
    {
        sort($missing);

        return new self(
            'The dataset covers '.(27 - count($missing))." of Jigawa's 27 LGAs. Missing:\n  ".
            implode(', ', $missing)."\n\n".
            'A partial dataset is rejected rather than loaded: a lookup table missing real LGAs '.
            'looks complete to every user of the cascading selector. Nothing was loaded.'
        );
    }

    public static function conflictingWard(string $lgaCode, string $wardCode, string $first, string $second): self
    {
        return new self(
            "The dataset gives two different names for ward '{$wardCode}' in LGA '{$lgaCode}': ".
            "'{$first}' and '{$second}'. Resolve the conflict in the source file — the loader will ".
            'not guess which is correct. Nothing was loaded.'
        );
    }
}
