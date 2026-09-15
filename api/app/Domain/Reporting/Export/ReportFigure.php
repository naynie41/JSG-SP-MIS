<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

/**
 * One chart card in a PDF report: a title, the chart as an image, and its values in
 * words beside it — a chart never carries a number the reader cannot also read.
 *
 * `wide` cards take the full width; the rest are laid out two to a row.
 */
final readonly class ReportFigure
{
    /**
     * @param  list<array{label: string, value: string, color?: string}>  $items
     */
    public function __construct(
        public string $title,
        public ?string $subtitle = null,
        public ?string $image = null,
        public int $imageWidth = 0,
        public int $imageHeight = 0,
        public array $items = [],
        public ?string $note = null,
        public bool $wide = false,
    ) {}
}
