<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Export\Charts\SvgChart;
use App\Domain\Reporting\Segments\SegmentAccess;
use App\Domain\Reporting\Segments\SegmentDefinition;
use App\Domain\Reporting\Segments\SegmentReportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * "People in the register" — a picture of the whole registered population (FR-RPT-12).
 *
 * Deliberately NOT a filtered report and NOT a list. It answers one question — who is
 * on the register — and answers it in charts: the gender/age pyramid first, then
 * status, how people were registered, and where they are.
 *
 * Three things follow from that, and each is a constraint rather than a preference:
 *
 *  - **No rows.** `ReportData::$rows` is empty by construction, so there is nothing to
 *    leak and nothing to mask. This report is safe for every tier that may see counts
 *    at all, and it does not touch the export matrix that governs the row-level
 *    beneficiary export (SECURITY.md §3) — that is still a separate, separately
 *    permissioned thing.
 *  - **No filters.** The definition is always empty, so the figures describe the whole
 *    of the caller's scope. A filtered version of this would be the segment builder,
 *    which already exists.
 *  - **PDF only.** A CSV of these figures would be a table of totals with none of the
 *    shape that is the entire point; the format is fixed at the controller.
 *
 * The small-cell guard still applies: a band too small to publish is marked rather than
 * drawn, on exactly the terms {@see CellSizeGuard} sets everywhere else.
 */
class RegisterProfileExportBuilder
{
    /** A4 content width at 96dpi, matching the dashboard board export. */
    private const FULL = 686;

    private const HALF = 330;

    public function __construct(private readonly SegmentReportService $segments) {}

    public function build(SegmentAccess $access): ReportData
    {
        $definition = SegmentDefinition::empty();
        $summary = $this->segments->summary($definition, $access);
        $base = $this->segments->baseQuery($definition, $access);

        $sections = [];
        foreach ($summary as $section) {
            $sections[$section->title] = $section->items;
        }

        $total = $this->figureOf($sections['Overview'] ?? [], 'People in this report');
        $noAge = $this->figureOf($sections['Age group'] ?? [], 'Not recorded');
        $minimum = $access->cellSizeGuard ? $this->segments->minimumCellSize() : null;

        return new ReportData(
            reportKey: 'register_profile',
            title: 'People in the register',
            subtitle: 'Who is registered, by gender and age, status, source and location',
            scopeLabel: $access->scope->label,
            generatedAt: Carbon::now(),
            // No columns and no rows: this report is figures only, on purpose.
            columns: [],
            rows: [],
            summary: $summary,
            crest: true,
            figures: array_values(array_filter([
                $this->pyramid($base, $total, $noAge, $minimum),
                $this->status($sections['Status'] ?? [], $minimum),
                $this->sources($sections['How they were registered'] ?? [], $minimum),
                $this->locations($sections['Local government area'] ?? [], $minimum),
            ])),
        );
    }

    /* ---------------------------------------------------------------- figures */

    /**
     * The headline: the population's shape, women left and men right.
     *
     * The note under it is not boilerplate. Percentages are of the people ON the chart,
     * and anyone without a date of birth is not on it — so wherever date-of-birth
     * coverage is poor, the chart describes a minority of the register and must say so
     * in the same breath. A reader who takes "70% female" from a chart covering 12% of
     * the register has been misled by the report, not by their own carelessness.
     *
     * Always returns a figure, unlike the bar charts beside it. When there is nothing to
     * draw it returns one carrying the explanation instead — the absence of an age
     * pyramid is itself a finding about the register's data quality, and dropping the
     * figure silently would hide it.
     *
     * @param  Builder<Beneficiary>  $base
     */
    private function pyramid($base, int $total, int $noAge, ?int $minimum): ReportFigure
    {
        $rows = $this->segments->genderByAge($base);
        $chart = SvgChart::pyramid($rows, self::FULL, $minimum);

        if ($chart === null) {
            return new ReportFigure(
                'Gender and age',
                'How the register is made up',
                note: $noAge > 0
                    ? 'No date of birth is recorded for any of these '.number_format($total).' people, so none of them can be placed on an age band.'
                    : 'No gender or age data is recorded for this scope.',
                wide: true,
            );
        }

        $charted = (int) (array_sum(array_column($rows, 'female')) + array_sum(array_column($rows, 'male')));

        $note = 'Percentages are of the '.number_format($charted).' people shown here, so both sides together make 100%.';
        if ($noAge > 0) {
            $note .= ' '.number_format($noAge).' of '.number_format($total)
                .' people on the register have no recorded date of birth and cannot be placed on an age band, so they are absent from this chart.'
                .($charted < $total / 2 ? ' Read the shape with that in mind: it describes a minority of the register.' : '');
        }

        return new ReportFigure(
            'Gender and age',
            'The shape of the register: women left, men right, oldest at the top',
            $chart['uri'],
            $chart['width'],
            $chart['height'],
            // No item list: the chart labels every band, and the Gender and Age group
            // breakdowns are printed in full at the end of the report.
            [],
            $note,
            wide: true,
        );
    }

    /** @param list<array{label: string, value: string}> $items */
    private function status(array $items, ?int $minimum): ?ReportFigure
    {
        $chart = SvgChart::bars($this->toRows($items), self::HALF, $minimum);

        return $chart === null
            ? null
            : new ReportFigure('Status', 'Where each person stands', $chart['uri'], $chart['width'], $chart['height']);
    }

    /** @param list<array{label: string, value: string}> $items */
    private function sources(array $items, ?int $minimum): ?ReportFigure
    {
        $chart = SvgChart::bars($this->toRows($items), self::HALF, $minimum);

        return $chart === null
            ? null
            : new ReportFigure('How they were registered', 'The source each record came from', $chart['uri'], $chart['width'], $chart['height']);
    }

    /**
     * Locations, longest bar first and capped — a full LGA list is a table, not a chart,
     * and the rest are printed in the summary sections regardless.
     *
     * @param  list<array{label: string, value: string}>  $items
     */
    private function locations(array $items, ?int $minimum): ?ReportFigure
    {
        $top = array_slice($items, 0, 12);
        $chart = SvgChart::bars($this->toRows($top), self::FULL, $minimum);

        if ($chart === null) {
            return null;
        }

        $note = count($items) > count($top)
            ? 'The '.count($top).' largest areas are charted. All '.count($items).' are listed in the breakdown at the end of this report.'
            : null;

        return new ReportFigure('Local government area', 'Where people are registered', $chart['uri'], $chart['width'], $chart['height'], [], $note, wide: true);
    }

    /* ---------------------------------------------------------------- helpers */

    /**
     * Summary items back into chart rows.
     *
     * A suppressed count arrives as "< 5" rather than a number, and becomes 0 here: the
     * guard has already decided it may not be published, and inventing a bar height for
     * it would undo that decision in pixels.
     *
     * @param  list<array{label: string, value: string}>  $items
     * @return list<array{label: string, count: int}>
     */
    private function toRows(array $items): array
    {
        $rows = [];

        foreach ($items as $item) {
            $rows[] = [
                'label' => $item['label'],
                'count' => (int) str_replace(',', '', preg_match('/^\d[\d,]*$/', $item['value']) === 1 ? $item['value'] : '0'),
            ];
        }

        return $rows;
    }

    /** @param list<array{label: string, value: string}> $items */
    private function figureOf(array $items, string $label): int
    {
        foreach ($items as $item) {
            if ($item['label'] === $label) {
                return (int) str_replace(',', '', preg_match('/^\d[\d,]*$/', $item['value']) === 1 ? $item['value'] : '0');
            }
        }

        return 0;
    }
}
