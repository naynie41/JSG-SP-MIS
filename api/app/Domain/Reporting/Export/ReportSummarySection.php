<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

/**
 * One titled block of headline counts printed above a report's table — "Gender: Women
 * 34, Men 10" — so a reader gets the shape of the population before its rows.
 *
 * Values arrive as display strings. The report that builds a section has already applied
 * the small-cell guard, so an exporter prints what it is given and never has to know
 * which counts were withheld or why.
 */
final readonly class ReportSummarySection
{
    /**
     * @param  list<array{label: string, value: string}>  $items
     */
    public function __construct(
        public string $title,
        public array $items,
    ) {}
}
