<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

/**
 * A report column definition. `sensitive` columns (e.g. NIN/BVN) are always masked in
 * the rendered output regardless of format (SECURITY.md).
 */
final readonly class ReportColumn
{
    public function __construct(
        public string $key,
        public string $label,
        public bool $sensitive = false,
        public bool $numeric = false,
    ) {}
}
