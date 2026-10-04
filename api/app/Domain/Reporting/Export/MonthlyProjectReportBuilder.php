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

    /** Half the content width, for the two figures that sit side by side. */
    private const HALF = 330;

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
            highlights: $this->tiles($d),
            // No array_filter here, deliberately. Each of these ALWAYS returns a figure:
            // one that explains itself when there is nothing to plot, rather than
            // vanishing. A chart that silently removes itself when the data is thin is
            // indistinguishable from one that was never built — which is exactly how the
            // first version of this report shipped without its comparison.
            figures: [
                $this->trend($d),
                $this->standingFigure($d),
                $this->delivery($d),
                $this->budgetFigure($d['projects']),
            ],
        );
    }

    /* ------------------------------------------------------------------- the tiles */

    /**
     * The month in four numbers, each carrying its own movement.
     *
     * This is where the comparison with last month lives. It was a two-bar chart, which
     * is the wrong form twice over: a pair of values is a figure with a delta, not a
     * magnitude comparison, and drawing it as bars meant that when both months were
     * empty the whole comparison disappeared instead of saying "nothing moved".
     *
     * @return list<array{label: string, value: string, note?: string}>
     */
    private function tiles(array $d): array
    {
        $now = $d['totals'];
        $was = $d['previous_totals'];
        $prev = $d['previous']['label'];
        $rated = array_sum($d['standing']);

        return [
            [
                'label' => 'People reached',
                'value' => number_format((int) $now['reached']),
                'note' => $this->delta((int) $was['reached'], (int) $now['reached'], $prev),
            ],
            [
                'label' => 'Value delivered',
                'value' => $this->naira((int) $now['value']),
                'note' => $this->delta((int) $was['value'], (int) $now['value'], $prev),
            ],
            [
                'label' => 'Projects delivering',
                'value' => number_format((int) $d['delivering']),
                'note' => 'of '.number_format((int) $now['active']).' running this month',
            ],
            [
                'label' => 'On track',
                'value' => number_format((int) $d['standing']['green']),
                'note' => $rated > 0
                    ? 'of '.number_format($rated).' with a target set'
                    : 'no project has a target set',
            ],
        ];
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
     * Twelve months of delivered value, ending on the month this report covers.
     *
     * A run of months rather than this-against-last: one pair cannot tell a reader
     * whether a fall is a collapse or the normal shape of a programme that pays
     * quarterly. The pair is still there — in the tiles, where a delta belongs.
     */
    private function trend(array $d): ReportFigure
    {
        $points = $d['trend'];
        $delivered = array_sum(array_column($points, 'value'));

        if ($delivered <= 0) {
            return $this->empty(
                'Delivery over the year',
                'Value delivered each month, to '.$d['period']['label'],
                'No delivery is recorded against this '.'scope in the twelve months ending '.$d['period']['label'].'.',
            );
        }

        $chart = SvgChart::area(
            array_map(static fn (array $p): array => ['month' => $p['month'], 'value' => $p['value']], $points),
            self::FULL,
            150,
            static fn (float $v): string => SvgChart::compactNaira($v),
            static fn (float $v): string => SvgChart::compactNaira($v),
        );

        if ($chart === null) {
            return $this->empty('Delivery over the year', 'Value delivered each month, to '.$d['period']['label'],
                'The monthly series could not be drawn for this scope.');
        }

        return new ReportFigure(
            'Delivery over the year',
            'Value delivered each month, ending '.$d['period']['label'],
            $chart['uri'],
            $chart['width'],
            $chart['height'],
            [],
            'Each point is the value delivered WITHIN that month, not a running total. Months with no recorded delivery are plotted as zero rather than skipped.',
            wide: true,
        );
    }

    /**
     * How the portfolio stands: on track, behind, well behind.
     *
     * A donut earns its place here and almost nowhere else in this report — three
     * segments of a single whole, read at a glance. Projects with no target are absent
     * rather than lumped into a slice, because "unrated" is not a standing.
     */
    private function standingFigure(array $d): ReportFigure
    {
        $s = $d['standing'];
        $rated = (int) ($s['green'] + $s['yellow'] + $s['red']);

        if ($rated === 0) {
            return $this->empty(
                'How projects stand',
                'Against their targets, to date',
                'No project in this scope has a target set, so none can be rated against one.',
            );
        }

        $chart = SvgChart::donut([
            ['value' => (int) $s['green'], 'color' => SvgChart::STANDING_GOOD],
            ['value' => (int) $s['yellow'], 'color' => SvgChart::STANDING_FAIR],
            ['value' => (int) $s['red'], 'color' => SvgChart::STANDING_POOR],
            // Deliberately smaller than the half-width slot it sits in. At the full 330
            // the ring is taller than the bar chart beside it by three to one and reads
            // as the more important of the two, which it is not — it summarises what
            // the bars and the table already say.
        ], 240, number_format($rated), $rated === 1 ? 'project' : 'projects');

        return new ReportFigure(
            'How projects stand',
            'Against their targets, to date',
            $chart === null ? null : $chart['uri'],
            $chart === null ? 0 : $chart['width'],
            $chart === null ? 0 : $chart['height'],
            // The legend is not decoration: it is what stops the chart depending on
            // colour alone, which matters on a page that may be printed in grey.
            [
                ['label' => 'On track', 'value' => number_format((int) $s['green']), 'color' => SvgChart::STANDING_GOOD],
                ['label' => 'Behind', 'value' => number_format((int) $s['yellow']), 'color' => SvgChart::STANDING_FAIR],
                ['label' => 'Well behind', 'value' => number_format((int) $s['red']), 'color' => SvgChart::STANDING_POOR],
            ],
            'Measured on everything delivered to date against the target, never on one month alone.',
        );
    }

    /** Where the month's delivery actually went — the projects that moved. */
    private function delivery(array $d): ReportFigure
    {
        $rows = [];
        foreach ($d['projects'] as $p) {
            if ((int) $p['month']['value'] > 0) {
                $rows[] = ['label' => (string) $p['name'], 'count' => (int) $p['month']['value']];
            }
        }

        if ($rows === []) {
            return $this->empty(
                'Where the month went',
                'Value delivered in '.$d['period']['label'].', by project',
                'No delivery value was recorded against any project in '.$d['period']['label'].'.',
            );
        }

        $top = array_slice($rows, 0, 10);
        $chart = SvgChart::bars($top, self::HALF, null, static fn (float $v): string => SvgChart::compactNaira($v));

        return new ReportFigure(
            'Where the month went',
            'Value delivered in '.$d['period']['label'].', by project',
            $chart === null ? null : $chart['uri'],
            $chart === null ? 0 : $chart['width'],
            $chart === null ? 0 : $chart['height'],
            [],
            count($rows) > count($top)
                ? 'The '.count($top).' largest are charted; all '.count($rows).' appear in the table.'
                : null,
        );
    }

    /**
     * Budget consumed against budget set, as meters.
     *
     * A ratio against a limit is a meter, not a bar: the thing the reader is judging is
     * the distance to 100%, and `rings()` already turns the weakest amber and names it.
     */
    private function budgetFigure(array $projects): ReportFigure
    {
        $meters = [];
        foreach ($projects as $p) {
            $allocated = (int) $p['budget']['allocated'];
            if ($allocated > 0) {
                $meters[] = [
                    'label' => (string) $p['name'],
                    'ratio' => min(1.0, (int) $p['budget']['spent'] / $allocated),
                    'weakest' => false,
                ];
            }
        }

        if ($meters === []) {
            return $this->empty(
                'Budget used',
                'Delivered value against the budget set',
                'No project in this scope has a budget recorded against it, so there is nothing to measure use against.',
            );
        }

        // Fullest first, capped: these are read as a group, and a wall of rings stops
        // being a comparison and becomes wallpaper.
        usort($meters, static fn (array $a, array $b): int => $b['ratio'] <=> $a['ratio']);
        $shown = array_slice($meters, 0, 8);
        $shown[count($shown) - 1]['weakest'] = true;

        $chart = SvgChart::rings($shown, self::FULL);

        return new ReportFigure(
            'Budget used',
            'Delivered value against the budget set, to date',
            $chart['uri'],
            $chart['width'],
            $chart['height'],
            [],
            count($meters) > count($shown)
                ? 'The '.count($shown).' with the highest use are shown; the budget column in the table covers all '.count($meters).'.'
                : null,
            wide: true,
        );
    }

    /**
     * A figure that explains its own absence.
     *
     * The alternative — returning null and dropping the card — is how the first version
     * of this report lost its month-on-month comparison without anyone noticing: an
     * empty scope and a broken builder look identical on the page.
     */
    private function empty(string $title, string $subtitle, string $why): ReportFigure
    {
        return new ReportFigure($title, $subtitle, note: $why, wide: true);
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

    /**
     * The movement behind a tile, in words.
     *
     * Percentages are avoided where they would mislead: a rise from nothing is not an
     * increase of infinity, and a fall to nothing is not "-100%" in any useful sense —
     * both are better said plainly, naming the month being compared against so the
     * reader never has to guess the baseline.
     */
    private function delta(int $was, int $now, string $previousLabel): string
    {
        if ($was === 0 && $now === 0) {
            return 'Nothing recorded in '.$previousLabel.' either';
        }
        if ($was === 0) {
            return 'First month with anything recorded since '.$previousLabel;
        }
        if ($now === 0) {
            return 'Nothing recorded, after '.$previousLabel;
        }

        $delta = (int) round(($now - $was) / $was * 100);

        if ($delta === 0) {
            return 'Level with '.$previousLabel;
        }

        return ($delta > 0 ? '+' : '').$delta.'% on '.$previousLabel;
    }

    /** Kobo to naira, matching how value is presented everywhere else. */
    private function naira(int $kobo): string
    {
        return '₦'.number_format($kobo / 100, 2);
    }
}
