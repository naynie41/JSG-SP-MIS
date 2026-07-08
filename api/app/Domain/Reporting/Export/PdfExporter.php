<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Reporting\Export\Contracts\ReportExporter;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

/**
 * Branded PDF export (PRD FR-RPT-03) via Dompdf, rendering the design-system report
 * template (`resources/views/reports/pdf.blade.php`) — forest letterhead with a crest
 * slot, lime accent, and a readable table. DejaVu Sans is used so the ₦ symbol and
 * the masking glyph render.
 */
class PdfExporter implements ReportExporter
{
    public function format(): ReportFormat
    {
        return ReportFormat::Pdf;
    }

    public function render(ReportData $data): string
    {
        $html = View::make('reports.pdf', ['data' => $data])->render();

        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return (string) $dompdf->output();
    }
}
