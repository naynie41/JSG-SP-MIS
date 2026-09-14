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
        $html = View::make('reports.pdf', [
            'data' => $data,
            'crest' => $data->crest ? $this->crest() : null,
        ])->render();

        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return (string) $dompdf->output();
    }

    /**
     * The crest, inlined. Remote loading stays off — a report renderer that fetches URLs
     * is a server-side request forgery surface — so the image travels inside the HTML.
     */
    private function crest(): ?string
    {
        $path = resource_path('brand/jigawa-crest.png');

        return is_file($path)
            ? 'data:image/png;base64,'.base64_encode((string) file_get_contents($path))
            : null;
    }
}
