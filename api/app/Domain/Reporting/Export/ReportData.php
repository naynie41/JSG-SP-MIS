<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use Illuminate\Support\Carbon;

/**
 * Format-agnostic report payload built by a report definition from the aggregation
 * layer. Every exporter (CSV/Excel/PDF) renders the same `ReportData`, so the three
 * formats always agree. Cells are read through {@see self::cell()}, which masks any
 * column marked sensitive — sensitive values never reach an exporter unmasked.
 */
final readonly class ReportData
{
    /**
     * @param  list<ReportColumn>  $columns
     * @param  list<array<string, scalar|null>>  $rows  keyed by column key
     */
    public function __construct(
        public string $reportKey,
        public string $title,
        public string $subtitle,
        public string $scopeLabel,
        public Carbon $generatedAt,
        public array $columns,
        public array $rows,
    ) {}

    public function rowCount(): int
    {
        return count($this->rows);
    }

    /**
     * The display string for a cell — masked when the column is sensitive.
     *
     * @param  array<string, scalar|null>  $row
     */
    public function cell(array $row, ReportColumn $column): string
    {
        $raw = $row[$column->key] ?? '';
        $value = is_bool($raw) ? ($raw ? 'Yes' : 'No') : (string) $raw;

        return $column->sensitive ? SensitiveMasker::mask($value) : $value;
    }
}
