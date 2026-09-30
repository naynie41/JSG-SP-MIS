<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Reporting\Export\Charts\SvgChart;
use App\Domain\Reporting\Services\MonthlyProjectService;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Support\Carbon;

/**
 * "Monthly project report" — how an MDA's activities did in one month (FR-RPT-12b).
 *
 * The document answers three questions in order, because that is the order an officer
 * asks them: what moved this month, how does that compare with last month, and which
 * projects need looking at.
 *
 * WHY THE TABLE HAS TWO REACH COLUMNS. "This month" and "To date" are different kinds
 * of number — flow and stock — and the report keeps them in separate, separately
 * headed columns rather than blending them into one progress figure. The traffic light
 * reads only the cumulative side; see {@see MonthlyProjectService} for why dividing a
 * month's delivery by a lifetime target makes every healthy project look red.
 *
 * It carries no personal data: every row is one ACTIVITY, and the only counts are of
 * people reached, never of people named. That keeps it under `reporting.export` rather
 * than the beneficiary export matrix (SECURITY.md §3), and the small-cell guard does
 * not apply — an MDA counting its own beneficiaries is not a disclosure (CLAUDE.md §11).
 */
class MonthlyProjectReportBuilder
{
    /** A4 content width at 96dpi, matching the other PDF builders. */
    private const FULL = 686;

    public function __construct(private readonly MonthlyProjectService $projects) {}

    public function build(DashboardScope $scope, int $year, int $month): ReportData
    {
        $d = $this->projects->build($scope, $year, $month);

        return new ReportData(
            reportKey: 'monthly_project',
            title: 'Monthly project report',
            subtitle: $d['period']['label'].' · how projects are doing',
            scopeLabel: $scope->label,
            generatedAt: Carbon::now(),
            columns: $this->columns(),
            rows: $this->rows($d['projects']),
            summary: $this->summary($d),
            crest: true,
            figures: array_values(array_filter([
                $this->movement($d),
                $this->delivery($d['projects']),
            ])),
        );
    }

    /* ---------------------------------------------------------------- the table */

    /** @return list<ReportColumn> */
    private function columns(): array
    {
        return [
            new ReportColumn('name', 'Project'),
            new ReportColumn('programme', 'Programme'),
            new ReportColumn('status', 'Status'),
            new ReportColumn('timeline', 'Timeline'),
            // Deliberately headed so the two kinds of number cannot be confused at a
            // glance. A reader who takes the monthly figure for a total, or the total
            // for the month's work, has been misled by the table.
            new ReportColumn('month_reached', 'Reached this month', numeric: true),
            new ReportColumn('month_value', 'Delivered this month', numeric: true),
            new ReportColumn('to_date', 'Reached to date', numeric: true),
            new ReportColumn('budget', 'Budget used', numeric: true),
            new ReportColumn('standing', 'Standing'),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $projects
     * @return list<array<string, string>>
     */
    private function rows(array $projects): array
    {
        $rows = [];

        foreach ($projects as $p) {
            $tracks = (bool) $p['tracks_people'];
            $target = $p['target'];
            $toDate = $p['to_date']['reached'];

            $rows[] = [
                'name' => (string) $p['name'],
                'programme' => (string) ($p['programme'] ?? '—'),
                'status' => ucfirst((string) $p['status']),
                'timeline' => $this->timeline($p['starts_on'], $p['ends_on']),
                // An activity that does not involve beneficiaries gets an em dash, not
                // a zero: it has no target to miss, and a 0 would read as failure.
                'month_reached' => $tracks ? number_format((int) $p['month']['reached']) : '—',
                'month_value' => $this->naira((int) $p['month']['value']),
                'to_date' => $tracks
                    ? number_format((int) $toDate).($target ? ' of '.number_format((int) $target) : '')
                    : '—',
                'budget' => $this->budget((int) $p['budget']['allocated'], (int) $p['budget']['spent']),
                'standing' => $this->standing($p['traffic_light'], $p['completion_rate']),
            ];
        }

        return $rows;
    }

    /* -------------------------------------------------------------- the figures */

    /**
     * This month against last, side by side.
     *
     * Two bars, not a trend line: one month of change is a comparison, not a trend, and
     * drawing it as a line would imply a direction the data cannot support.
     */
    private function movement(array $d): ?ReportFigure
    {
        $now = $d['totals'];
        $was = $d['previous_totals'];

        if ($now['reached'] === 0 && $now['value'] === 0 && $was['reached'] === 0 && $was['value'] === 0) {
            return null;
        }

        $chart = SvgChart::bars([
            ['label' => $d['previous']['label'].' — people reached', 'count' => (int) $was['reached']],
            ['label' => $d['period']['label'].' — people reached', 'count' => (int) $now['reached']],
        ], self::FULL);

        if ($chart === null) {
            return null;
        }

        return new ReportFigure(
            'Month on month',
            'People reached, against the month before',
            $chart['uri'],
            $chart['width'],
            $chart['height'],
            [
                ['label' => 'Delivered '.$d['period']['label'], 'value' => $this->naira((int) $now['value'])],
                ['label' => 'Delivered '.$d['previous']['label'], 'value' => $this->naira((int) $was['value'])],
                ['label' => 'Change', 'value' => $this->change((int) $was['value'], (int) $now['value'])],
            ],
            note: 'These are the people reached and the value delivered WITHIN each month. They are not running totals.',
            wide: true,
        );
    }

    /** Where the month's delivery actually went — the projects that moved. */
    private function delivery(array $projects): ?ReportFigure
    {
        $rows = [];
        foreach ($projects as $p) {
            if ((int) $p['month']['value'] > 0) {
                $rows[] = ['label' => (string) $p['name'], 'count' => (int) $p['month']['value']];
            }
        }

        if ($rows === []) {
            return null;
        }

        $top = array_slice($rows, 0, 12);
        $chart = SvgChart::bars($top, self::FULL, null, static fn (float $v): string => SvgChart::compactNaira($v));

        if ($chart === null) {
            return null;
        }

        return new ReportFigure(
            'Where the month went',
            'Value delivered this month, by project',
            $chart['uri'],
            $chart['width'],
            $chart['height'],
            [],
            count($rows) > count($top)
                ? 'The '.count($top).' largest are charted; all '.count($rows).' appear in the table.'
                : null,
            wide: true,
        );
    }

    /* -------------------------------------------------------------- the summary */

    /** @return list<ReportSummarySection> */
    private function summary(array $d): array
    {
        $sections = [new ReportSummarySection('The month', [
            ['label' => 'Period', 'value' => $d['period']['label']],
            ['label' => 'Projects in this report', 'value' => number_format((int) $d['totals']['activities'])],
            ['label' => 'Of those, currently running', 'value' => number_format((int) $d['totals']['active'])],
            ['label' => 'People reached this month', 'value' => number_format((int) $d['totals']['reached'])],
            ['label' => 'Value delivered this month', 'value' => $this->naira((int) $d['totals']['value'])],
        ])];

        if ($d['attention'] !== []) {
            $sections[] = new ReportSummarySection('Needs attention', array_map(
                static fn (array $a): array => ['label' => $a['name'], 'value' => $a['reason']],
                $d['attention'],
            ));
        }

        if ($d['ending_soon'] !== []) {
            $sections[] = new ReportSummarySection('Ending next month', array_map(
                static fn (array $e): array => [
                    'label' => $e['name'],
                    'value' => Carbon::parse($e['ends_on'])->format('j M Y'),
                ],
                $d['ending_soon'],
            ));
        }

        return $sections;
    }

    /* ---------------------------------------------------------------- formatting */

    private function timeline(?string $from, ?string $to): string
    {
        if ($from === null && $to === null) {
            return 'Not set';
        }
        $f = $from === null ? '?' : Carbon::parse($from)->format('M Y');
        $t = $to === null ? 'open' : Carbon::parse($to)->format('M Y');

        return $f.' – '.$t;
    }

    private function budget(int $allocated, int $spent): string
    {
        if ($allocated <= 0) {
            return $spent > 0 ? $this->naira($spent).' (no budget set)' : '—';
        }

        return $this->naira($spent).' of '.$this->naira($allocated)
            .' ('.round($spent / $allocated * 100).'%)';
    }

    /**
     * The standing column spells the rating out rather than printing a colour word
     * alone: "green" means nothing on a monochrome print, and a percentage does.
     */
    private function standing(?string $light, ?float $completion): string
    {
        if ($light === null) {
            return 'Not measured by reach';
        }
        if ($completion === null) {
            return 'No target set';
        }

        $pct = round($completion * 100).'% of target';

        return match ($light) {
            'green' => 'On track — '.$pct,
            'yellow' => 'Behind — '.$pct,
            'red' => 'Well behind — '.$pct,
            default => $pct,
        };
    }

    private function change(int $was, int $now): string
    {
        if ($was === 0) {
            return $now === 0 ? 'No change' : 'New delivery this month';
        }

        $delta = round(($now - $was) / $was * 100);

        return ($delta > 0 ? '+' : '').$delta.'% against '.$this->naira($was);
    }

    /** Kobo to naira, matching how value is presented everywhere else. */
    private function naira(int $kobo): string
    {
        return '₦'.number_format($kobo / 100, 2);
    }
}
