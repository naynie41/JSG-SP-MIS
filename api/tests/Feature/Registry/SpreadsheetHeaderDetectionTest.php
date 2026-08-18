<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Registry\Imports\SpreadsheetReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Tests\TestCase;

/**
 * Finding the header row in a real government spreadsheet.
 *
 * These files routinely open with a letterhead — a merged ministry title, a department
 * line, an "Activity: …" note, a blank spacer — and only then the headers. Reading row 1
 * on faith produced a single column named after the ministry and a mapping screen with
 * nothing to map.
 */
class SpreadsheetHeaderDetectionTest extends TestCase
{
    /**
     * Writes a real .xlsx from a matrix and reads it back through the reader.
     *
     * @param  list<list<string|null>>  $matrix
     * @return array{headers: list<string>, rows: list<array{number: int, values: array<string, string>}>}
     */
    private function readMatrix(array $matrix): array
    {
        $book = new Spreadsheet;
        $book->getActiveSheet()->fromArray($matrix, null, 'A1');

        $path = tempnam(sys_get_temp_dir(), 'sheet').'.xlsx';
        (new XlsxWriter($book))->save($path);

        try {
            return app(SpreadsheetReader::class)->read($path, 'xlsx');
        } finally {
            @unlink($path);
        }
    }

    public function test_it_skips_a_letterhead_and_finds_the_real_header_row(): void
    {
        // The MWASD shape: three banner lines, a spacer, then the headers on row 5.
        $result = $this->readMatrix([
            ['JIGAWA STATE MINISTRY OF WOMEN AFFAIRS', null, null, null],
            ['JIGAWA STATE WOMEN DEVELOPMENT', null, null, null],
            ['Activity: 2026 Q1 Skill Acquisition', null, null, null],
            [null, null, null, null],
            ['S/N', 'LG', 'Name of Beneficiary', 'NIN'],
            ['1', 'Auyo', 'Nasira Sule', '64225322619'],
        ]);

        $this->assertSame(['s/n', 'lg', 'name_of_beneficiary', 'nin'], $result['headers']);
        $this->assertCount(1, $result['rows']);
        $this->assertSame('Nasira Sule', $result['rows'][0]['values']['name_of_beneficiary']);
        // The banner must not survive as a column, or the mapping screen offers junk.
        $this->assertStringNotContainsString('jigawa_state', implode('|', $result['headers']));
    }

    public function test_an_ordinary_file_still_uses_its_first_row(): void
    {
        // The common case must not regress: headers on row 1, data below.
        $result = $this->readMatrix([
            ['First Name', 'Last Name', 'NIN'],
            ['Ada', 'Okoye', '22200000011'],
            ['Bala', 'Sule', '22200000012'],
        ]);

        $this->assertSame(['first_name', 'last_name', 'nin'], $result['headers']);
        $this->assertCount(2, $result['rows']);
    }

    public function test_a_tie_resolves_to_the_earliest_row(): void
    {
        // Header row and data rows have the same number of filled cells, which is the
        // NORMAL case — the tie-break is what keeps row 1 the headers.
        $result = $this->readMatrix([
            ['A', 'B', 'C'],
            ['1', '2', '3'],
            ['4', '5', '6'],
        ]);

        $this->assertSame(['a', 'b', 'c'], $result['headers']);
        $this->assertCount(2, $result['rows']);
    }

    public function test_rows_above_the_header_are_not_read_as_data(): void
    {
        // The banner rows must be dropped entirely, not silently become records with
        // every field null — 3 phantom beneficiaries per file would be worse than the bug.
        $result = $this->readMatrix([
            ['MINISTRY TITLE', null, null],
            [null, null, null],
            ['First Name', 'Last Name', 'NIN'],
            ['Ada', 'Okoye', '22200000011'],
        ]);

        $this->assertCount(1, $result['rows']);
        $this->assertSame(1, $result['rows'][0]['number'], 'row numbering restarts at the first data row');
    }

    public function test_a_two_cell_banner_still_loses_to_a_wider_header_row(): void
    {
        // A banner that happens to fill two cells is not a header row while a wider one
        // exists below it.
        $result = $this->readMatrix([
            ['Report:', 'Q1 2026', null, null],
            ['First Name', 'Last Name', 'NIN', 'Phone'],
            ['Ada', 'Okoye', '22200000011', '08031234567'],
        ]);

        $this->assertSame(['first_name', 'last_name', 'nin', 'phone'], $result['headers']);
    }

    public function test_long_identifiers_survive_the_deeper_header_row(): void
    {
        // Reading from a later row must not change how cells are stringified — an 11-digit
        // NIN read as a float and rendered as 6.4225322619E+10 is unusable.
        $result = $this->readMatrix([
            ['MINISTRY TITLE', null, null],
            [null, null, null],
            ['Name', 'NIN', 'BVN'],
            ['Nasira Sule', '64225322619', '38355488573'],
        ]);

        $this->assertSame('64225322619', $result['rows'][0]['values']['nin']);
        $this->assertSame('38355488573', $result['rows'][0]['values']['bvn']);
    }
}
