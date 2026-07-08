<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export\Contracts;

use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Export\ReportFormat;

/**
 * Renders a {@see ReportData} to the bytes of one file format (PRD FR-RPT-03). Pure
 * data → bytes; the caller stores/streams the result. Sensitive cells are already
 * masked by {@see ReportData::cell()}.
 */
interface ReportExporter
{
    public function format(): ReportFormat;

    public function render(ReportData $data): string;
}
