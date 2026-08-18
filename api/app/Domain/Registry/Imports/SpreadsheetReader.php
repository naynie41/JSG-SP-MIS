<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports;

use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Reads an uploaded Excel/CSV file into normalised, header-keyed rows
 * (PRD FR-REG-02). Header names are canonicalised (lower snake_case) so the file
 * may use "First Name", "NIN", "Date of Birth", etc. Values are returned as
 * trimmed strings; numeric cells are stringified without scientific notation so
 * long identifiers (NIN/BVN) survive intact.
 */
class SpreadsheetReader
{
    /**
     * How far to look for the header row. Deep enough for a letterhead of a few title
     * lines plus a spacer, shallow enough that a genuinely header-less file cannot have
     * a data row deep in the sheet mistaken for its headers.
     */
    private const HEADER_SCAN_ROWS = 10;

    /**
     * @return array{headers: list<string>, rows: list<array{number: int, values: array<string, string>}>}
     */
    public function read(string $absolutePath, string $extension): array
    {
        $reader = match (strtolower($extension)) {
            'csv', 'txt' => IOFactory::createReader('Csv'),
            'xlsx' => IOFactory::createReader('Xlsx'),
            'xls' => IOFactory::createReader('Xls'),
            default => throw new InvalidArgumentException("Unsupported import file type: {$extension}"),
        };
        $reader->setReadDataOnly(true);

        $sheet = $reader->load($absolutePath)->getActiveSheet();
        /** @var list<list<mixed>> $matrix */
        $matrix = $sheet->toArray(null, false, false, false);

        $headerIndex = $this->headerRowIndex($matrix);

        $headers = [];
        $rows = [];
        $rowNumber = 0;

        foreach ($matrix as $index => $cells) {
            if ($index < $headerIndex) {
                continue; // title banner / blank rows above the real header row
            }
            if ($index === $headerIndex) {
                $headers = array_map(fn ($h) => $this->canonicalHeader((string) $this->stringify($h)), $cells);

                continue;
            }

            $values = [];
            foreach ($headers as $col => $header) {
                if ($header === '') {
                    continue;
                }
                $values[$header] = trim($this->stringify($cells[$col] ?? null));
            }

            // Skip entirely blank rows.
            if (implode('', $values) === '') {
                continue;
            }

            $rowNumber++;
            $rows[] = ['number' => $rowNumber, 'values' => $values];
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * Which row holds the column headers.
     *
     * Government spreadsheets routinely open with a letterhead: a merged title cell, a
     * department line, an "Activity: …" note, a blank row, and only THEN the headers.
     * Taking row 1 on faith turned such a file into a single column named after the
     * ministry, and the mapping screen offered nothing to map.
     *
     * The rule: within the first {@see self::HEADER_SCAN_ROWS} rows, take the row with
     * the most non-empty cells, earliest wins on a tie. A banner has one filled cell (the
     * merge leaves the rest null) and a spacer has none, so both lose to the real header
     * row; an ordinary file's row 1 has the most and ties are broken back to it.
     *
     * This is a heuristic, and it is allowed to be one because it cannot pass unnoticed:
     * the headers it picks are shown on the mapping screen with real sample values, and a
     * person confirms every identity field against them (CLAUDE.md §11). A wrong guess
     * here is visible before anything is parsed, never silent.
     *
     * @param  list<list<mixed>>  $matrix
     */
    private function headerRowIndex(array $matrix): int
    {
        $best = 0;
        $bestFilled = 0;

        foreach (array_slice($matrix, 0, self::HEADER_SCAN_ROWS, true) as $index => $cells) {
            $filled = 0;
            foreach ($cells as $cell) {
                if (trim($this->stringify($cell)) !== '') {
                    $filled++;
                }
            }

            if ($filled > $bestFilled) {
                $bestFilled = $filled;
                $best = $index;
            }
        }

        return $best;
    }

    private function canonicalHeader(string $header): string
    {
        $header = strtolower(trim($header));

        return (string) preg_replace('/[\s\-]+/', '_', $header);
    }

    private function stringify(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_float($value)) {
            // Whole numbers (e.g. an 11-digit NIN read as a float) stay integer-like.
            return floor($value) === $value ? sprintf('%.0f', $value) : (string) $value;
        }

        return (string) $value;
    }
}
