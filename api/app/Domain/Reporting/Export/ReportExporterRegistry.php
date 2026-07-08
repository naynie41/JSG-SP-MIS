<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Reporting\Export\Contracts\ReportExporter;

/**
 * Resolves the {@see ReportExporter} for a format. One seam for the three writers so
 * a new format (or a swapped library) is a single addition.
 */
class ReportExporterRegistry
{
    public function __construct(
        private readonly CsvExporter $csv,
        private readonly ExcelExporter $excel,
        private readonly PdfExporter $pdf,
    ) {}

    public function for(ReportFormat $format): ReportExporter
    {
        return match ($format) {
            ReportFormat::Csv => $this->csv,
            ReportFormat::Excel => $this->excel,
            ReportFormat::Pdf => $this->pdf,
        };
    }
}
