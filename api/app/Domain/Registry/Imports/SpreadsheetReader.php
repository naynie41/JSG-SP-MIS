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

        $headers = [];
        $rows = [];
        $rowNumber = 0;

        foreach ($matrix as $index => $cells) {
            if ($index === 0) {
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
