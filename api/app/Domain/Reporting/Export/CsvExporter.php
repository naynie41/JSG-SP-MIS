<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Reporting\Export\Contracts\ReportExporter;

/**
 * CSV export (PRD FR-RPT-03) — the plain data export. Header row of column labels
 * followed by masked cell values.
 */
class CsvExporter implements ReportExporter
{
    public function format(): ReportFormat
    {
        return ReportFormat::Csv;
    }

    public function render(ReportData $data): string
    {
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, array_map(fn (ReportColumn $c): string => $c->label, $data->columns));
        foreach ($data->rows as $row) {
            fputcsv($handle, array_map(fn (ReportColumn $c): string => $data->cell($row, $c), $data->columns));
        }

        rewind($handle);
        $csv = (string) stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }
}
