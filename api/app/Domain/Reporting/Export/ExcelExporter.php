<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Reporting\Export\Contracts\ReportExporter;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Excel (.xlsx) export (PRD FR-RPT-03) via PhpSpreadsheet — the shared data export in
 * spreadsheet form. A title/scope/generated header block, then a bold column header
 * row and the masked data rows.
 *
 * A report carrying a summary gets it on its own first sheet. Stacking it above the
 * table would put free text where a filter or pivot expects a header row, and the data
 * sheet is the one people sort.
 */
class ExcelExporter implements ReportExporter
{
    public function format(): ReportFormat
    {
        return ReportFormat::Excel;
    }

    public function render(ReportData $data): string
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(mb_substr($data->title, 0, 31));

        $lastCol = max(1, count($data->columns));
        $lastColLetter = Coordinate::stringFromColumnIndex($lastCol);

        // Header block.
        $sheet->setCellValue('A1', $data->title);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A2', $data->subtitle.' — '.$data->scopeLabel);
        $sheet->setCellValue('A3', 'Generated '.$data->generatedAt->format('d M Y H:i'));
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->mergeCells("A3:{$lastColLetter}3");

        // Column headers (row 5).
        $headerRow = 5;
        foreach ($data->columns as $index => $column) {
            $letter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$letter}{$headerRow}", $column->label);
            $sheet->getStyle("{$letter}{$headerRow}")->getFont()->setBold(true);
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }

        // Data rows.
        $row = $headerRow + 1;
        foreach ($data->rows as $record) {
            foreach ($data->columns as $index => $column) {
                $letter = Coordinate::stringFromColumnIndex($index + 1);
                $sheet->setCellValueExplicit(
                    "{$letter}{$row}",
                    $data->cell($record, $column),
                    DataType::TYPE_STRING,
                );
            }
            $row++;
        }

        if ($data->summary !== []) {
            $this->summarySheet($spreadsheet, $data);
        }

        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer->save($tempPath);
        $bytes = (string) file_get_contents($tempPath);
        @unlink($tempPath);
        $spreadsheet->disconnectWorksheets();

        return $bytes;
    }

    private function summarySheet(Spreadsheet $spreadsheet, ReportData $data): void
    {
        $sheet = $spreadsheet->createSheet(0);
        $sheet->setTitle('Summary');

        // The crest takes the first rows of column A; the heading sits beside it.
        $crest = resource_path('brand/jigawa-crest.png');
        $textColumn = 'A';
        if ($data->crest && is_file($crest)) {
            $drawing = new Drawing;
            $drawing->setName('Jigawa State crest');
            $drawing->setPath($crest);
            $drawing->setHeight(64);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
            $sheet->getColumnDimension('A')->setWidth(12);
            $textColumn = 'B';
        }

        $sheet->setCellValue("{$textColumn}1", 'Jigawa State · Social Protection MIS');
        $sheet->setCellValue("{$textColumn}2", $data->title);
        $sheet->getStyle("{$textColumn}2")->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue("{$textColumn}3", $data->subtitle.' — '.$data->scopeLabel);
        $sheet->setCellValue("{$textColumn}4", 'Generated '.$data->generatedAt->format('d M Y H:i'));

        $labelColumn = $textColumn;
        $valueColumn = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($textColumn) + 1);
        $row = 6;

        foreach ($data->summary as $section) {
            $sheet->setCellValue("{$labelColumn}{$row}", $section->title);
            $sheet->getStyle("{$labelColumn}{$row}")->getFont()->setBold(true);
            $row++;

            foreach ($section->items as $item) {
                $sheet->setCellValue("{$labelColumn}{$row}", $item['label']);
                $sheet->setCellValueExplicit("{$valueColumn}{$row}", $item['value'], DataType::TYPE_STRING);
                $sheet->getStyle("{$valueColumn}{$row}")->getAlignment()->setHorizontal('right');
                $row++;
            }

            $row++;
        }

        $sheet->getColumnDimension($labelColumn)->setAutoSize(true);
        $sheet->getColumnDimension($valueColumn)->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0);
    }
}
