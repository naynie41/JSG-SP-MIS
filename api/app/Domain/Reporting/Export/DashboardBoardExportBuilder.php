<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Reporting\Export\Charts\SvgChart;
use App\Domain\Reporting\Support\DashboardFilter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * The MDA Reports dashboard as a PDF: the page the officer was looking at, on paper —
 * headline tiles, the same charts and the LGA map, then the programme table.
 *
 * Every figure reads the SAME fields the dashboard renders, in the same words, so a
 * number quoted from the PDF is the number on the screen. Charts are drawn server-side
 * ({@see SvgChart}) and each prints its values beside it.
 *
 * Aggregates only. The small-cell rule the dashboard publishes (`min_cell_size`) is
 * applied to every count here as it is on screen; it is null for an MDA's own data.
 */
class DashboardBoardExportBuilder
{
    /** Chart widths in the PDF's px: half a content column, and the full column. */
    private const HALF = 330;

    private const CATEGORY_COLORS = ['#008300', '#2A78D6', '#EDA100', '#E87BA4'];

    private const OTHER_COLOR = '#9A9C93';

    private const STATUSES = [
        'active' => ['Active', '#2F7D3B'],
        'flagged' => ['Flagged for review', '#B23A31'],
        'suspended' => ['Suspended', '#B4791E'],
    ];

    /** Mirrors the web map's band fills (web/src/features/gis/choropleth.ts). */
    private const BANDS = [
        'green' => ['High', '#2F7D3B'],
        'yellow' => ['Moderate', '#B4791E'],
        'red' => ['Low', '#B23A31'],
        'grey' => ['No coverage', '#C9CBC1'],
    ];

    private const LIGHT_LABELS = [
        'green' => 'On target',
        'yellow' => 'Behind',
        'red' => 'Off target',
        'unrated' => 'No target set',
    ];

    private const HOUSEHOLD_BANDS = ['1' => '1 person', '2-3' => '2–3 people', '4-6' => '4–6 people', '7+' => '7+ people'];

    private const LGA_LIMIT = 10;

    private ?int $minimum = null;

    /**
     * @param  array<string, mixed>  $dashboard  the payload `DashboardService::forUser()` returns
     * @param  string|null  $scopeLabel  the MDA's name for the letterhead; the scope's own label otherwise
     * @param  array{rows: list<array<string, mixed>>, boundaries: list<array{code: string, name: string, geometry: mixed}>}|null  $map
     *                                                                                                                                   LGA coverage and boundary shapes; null when no boundaries are loaded
     */
    public function build(array $dashboard, DashboardFilter $filter, ?string $scopeLabel = null, ?array $map = null): ReportData
    {
        $m = (array) ($dashboard['metrics'] ?? []);
        $this->minimum = isset($dashboard['min_cell_size']) ? (int) $dashboard['min_cell_size'] : null;

        [$columns, $rows] = $this->programmes($m);

        return new ReportData(
            reportKey: 'mda-dashboard',
            title: 'MDA dashboard',
            subtitle: $this->filterLabel($filter),
            scopeLabel: $scopeLabel ?? (string) ($dashboard['scope']['label'] ?? ''),
            generatedAt: Carbon::now(),
            columns: $columns,
            rows: $rows,
            crest: true,
            highlights: $this->highlights($m),
            figures: [
                $this->registrations($m),
                $this->valueDelivered($m),
                $this->quality($m),
                $this->gender($m),
                $this->ages($m),
                $this->households($m),
                $this->coverageMap($map),
                $this->largestLgas($m),
                $this->benefits($m),
                $this->records($m),
            ],
        );
    }

    /* ---------------------------------------------------------------- tiles */

    /**
     * The dashboard's headline tiles, same labels and same fields.
     *
     * @param  array<string, mixed>  $m
     * @return list<array{label: string, value: string, note?: string}>
     */
    private function highlights(array $m): array
    {
        $population = (array) ($m['population'] ?? []);

        $tiles = [
            ['label' => 'Total beneficiaries', 'value' => $this->count($m['registry']['beneficiaries']['total'] ?? 0), 'note' => 'each person counted once'],
            ['label' => 'Households', 'value' => $this->count($m['registry']['households']['total'] ?? 0)],
            ['label' => 'Active programmes', 'value' => $this->count($m['programmes']['active'] ?? 0), 'note' => 'of '.number_format((int) ($m['programmes']['total'] ?? 0)).' in view'],
            ['label' => 'Active activities', 'value' => $this->count($m['programmes']['activities_active'] ?? 0), 'note' => 'of '.number_format((int) ($m['programmes']['activities_total'] ?? 0)).' in view'],
            ['label' => 'Benefit deliveries', 'value' => $this->count($m['benefits']['disbursed']['benefit_count'] ?? 0)],
            ['label' => 'Value delivered', 'value' => $this->naira($m['benefits']['disbursed']['total_value'] ?? 0)],
            ['label' => 'Possible duplicates found', 'value' => $this->count($m['duplicates']['matches_surfaced'] ?? 0), 'note' => 'people who may already be registered'],
        ];

        if ($population !== []) {
            $tiles[] = [
                'label' => 'New registrations',
                'value' => $this->count($population['new_registrations_period'] ?? 0),
                'note' => 'in the last '.(int) ($population['period_days'] ?? 0).' days',
            ];
        }

        return $tiles;
    }

    /* -------------------------------------------------------------- figures */

    /** @param array<string, mixed> $m */
    private function registrations(array $m): ReportFigure
    {
        $points = $this->points($m['trends']['registrations'] ?? []);
        $chart = SvgChart::area($points, self::HALF, 170, static fn (float $v): string => SvgChart::compact($v), static fn (float $v): string => number_format($v));

        return $this->figure('New registrations by month', 'People newly registered in each month', $chart, $this->trendItems($points, fn (float $v): string => $this->count($v)), 'No registrations recorded in this period.');
    }

    /** @param array<string, mixed> $m */
    private function valueDelivered(array $m): ReportFigure
    {
        $points = $this->points($m['trends']['disbursement'] ?? []);
        $chart = SvgChart::area($points, self::HALF, 170, static fn (float $v): string => SvgChart::compactNaira($v), static fn (float $v): string => SvgChart::compactNaira($v));

        return $this->figure('Value delivered by month', 'Recorded value of benefits delivered each month', $chart, $this->trendItems($points, fn (float $v): string => $this->naira($v)), 'No benefits delivered in this period.');
    }

    /** @param array<string, mixed> $m */
    private function quality(array $m): ReportFigure
    {
        $quality = (array) ($m['registry_quality'] ?? []);
        $total = (int) ($quality['total'] ?? 0);
        if ($total === 0) {
            return new ReportFigure('Quality of your records', note: 'No records in this view yet, so there is nothing to measure.');
        }

        $meters = [
            ['label' => 'Verified', 'ratio' => ($quality['verified'] ?? 0) / $total],
            ['label' => 'NIN recorded', 'ratio' => $this->ratio($quality['nin_completeness'] ?? null)],
            ['label' => 'Phone recorded', 'ratio' => $this->ratio($quality['phone_completeness'] ?? null)],
            ['label' => 'All details recorded', 'ratio' => $this->ratio($quality['data_completeness'] ?? null)],
        ];

        // The weakest single detail, not "overall", which averages the others.
        $weakest = null;
        foreach (array_slice($meters, 0, 3) as $meter) {
            if ($meter['ratio'] !== null && $meter['ratio'] < 1 && ($weakest === null || $meter['ratio'] < $weakest['ratio'])) {
                $weakest = $meter;
            }
        }

        $chart = SvgChart::rings(array_map(static fn (array $meter): array => [
            'label' => $meter['label'],
            'ratio' => $meter['ratio'],
            'weakest' => $weakest !== null && $meter['label'] === $weakest['label'],
        ], $meters), self::HALF);

        return $this->figure(
            'Quality of your records',
            'Share of your '.number_format($total).' records carrying each detail',
            $chart,
            [],
            null,
            $weakest !== null ? "{$weakest['label']} is the weakest detail at ".$this->percent($weakest['ratio']).'.' : 'Every record carries each of these details.',
        );
    }

    /** @param array<string, mixed> $m */
    private function gender(array $m): ReportFigure
    {
        $demographics = (array) ($m['demographics'] ?? []);
        $by = (array) ($demographics['by_gender'] ?? []);
        $women = (int) ($by['female'] ?? 0);
        $men = (int) ($by['male'] ?? 0);

        $slices = [
            ['label' => 'Women', 'value' => $women, 'color' => self::CATEGORY_COLORS[0]],
            ['label' => 'Men', 'value' => $men, 'color' => self::CATEGORY_COLORS[1]],
        ];
        if ((int) ($by['other'] ?? 0) > 0) {
            $slices[] = ['label' => 'Other', 'value' => (int) $by['other'], 'color' => self::CATEGORY_COLORS[2]];
        }
        $slices[] = ['label' => 'Not recorded', 'value' => (int) ($by['unspecified'] ?? 0), 'color' => self::OTHER_COLOR];

        // A share computed from a withheld count would give the count back.
        $shareHidden = $this->held($women) || $this->held($men);
        $chart = SvgChart::donut($slices, 140, $shareHidden ? '—' : $this->percent($demographics['female_pct'] ?? null), 'are women');

        return $this->figure(
            'Women and men',
            number_format((int) ($demographics['gender_known'] ?? 0)).' with a recorded gender',
            $chart,
            $this->shareItems($slices),
            'No genders recorded in this view yet.',
        );
    }

    /** @param array<string, mixed> $m */
    private function ages(array $m): ReportFigure
    {
        $bands = (array) ($m['demographics']['age_bands'] ?? []);

        $rows = [];
        foreach ((array) config('reporting.age_bands', []) as $key => $range) {
            [$min, $max] = $range;
            $ages = $max === null ? "{$min}+" : $min.'–'.((int) $max - 1);
            $rows[] = ['label' => Str::headline((string) $key).' '.$ages, 'count' => (int) ($bands[$key] ?? 0)];
        }
        $rows[] = ['label' => 'Not recorded', 'count' => (int) ($bands['unknown'] ?? 0)];

        return $this->figure(
            'Age groups',
            'From date of birth, in the bands the state reports on',
            SvgChart::columns($rows, self::HALF, 170, $this->minimum),
            [],
            'No dates of birth recorded in this view yet.',
        );
    }

    /** @param array<string, mixed> $m */
    private function households(array $m): ReportFigure
    {
        $sizes = (array) ($m['household_size'] ?? []);
        $split = (array) ($m['demographics']['household_vs_individual'] ?? []);

        $rows = [];
        foreach (self::HOUSEHOLD_BANDS as $key => $label) {
            $rows[] = ['label' => $label, 'count' => (int) ($sizes['bands'][$key] ?? 0)];
        }

        $subtitle = number_format((int) ($sizes['total_households'] ?? 0)).' households';
        if (isset($sizes['average_size'])) {
            $subtitle .= ' · '.number_format((float) $sizes['average_size'], 1).' people on average';
        }

        return $this->figure(
            'Household size',
            $subtitle,
            SvgChart::columns($rows, self::HALF, 150, $this->minimum),
            [
                $this->item('People in a household', $this->count($split['in_household'] ?? 0)),
                $this->item('Registered as individuals', $this->count($split['individual'] ?? 0)),
            ],
            'No households in this view yet.',
        );
    }

    /**
     * @param  array{rows: list<array<string, mixed>>, boundaries: list<array{code: string, name: string, geometry: mixed}>}|null  $map
     */
    private function coverageMap(?array $map): ReportFigure
    {
        $title = 'Coverage across your LGAs';
        $subtitle = 'Each LGA shaded by how many of your beneficiaries live there';

        if ($map === null || $map['boundaries'] === []) {
            return new ReportFigure($title, $subtitle, note: 'The LGA boundary map is not loaded on this server, so coverage is listed under Largest LGAs.');
        }

        $bandByCode = [];
        foreach ($map['rows'] as $row) {
            $bandByCode[(string) ($row['key'] ?? '')] = (string) ($row['band'] ?? 'grey');
        }

        $areas = [];
        $counts = array_fill_keys(array_keys(self::BANDS), 0);
        foreach ($map['boundaries'] as $boundary) {
            $band = $bandByCode[$boundary['code']] ?? 'grey';
            $band = isset(self::BANDS[$band]) ? $band : 'grey';
            $counts[$band]++;
            $geometry = is_string($boundary['geometry']) ? (array) json_decode($boundary['geometry'], true) : (array) $boundary['geometry'];
            $areas[] = ['geometry' => $geometry, 'color' => self::BANDS[$band][1]];
        }

        $green = (int) config('reporting.coverage_bands.green_min', 1000);
        $yellow = (int) config('reporting.coverage_bands.yellow_min', 250);
        $ranges = [
            'green' => number_format($green).' or more',
            'yellow' => number_format($yellow).'–'.number_format($green - 1),
            'red' => '1–'.number_format($yellow - 1),
            'grey' => 'no',
        ];

        $items = [];
        foreach (self::BANDS as $band => [$label, $color]) {
            $items[] = ['label' => "{$label} · {$ranges[$band]} beneficiaries", 'value' => $counts[$band].($counts[$band] === 1 ? ' LGA' : ' LGAs'), 'color' => $color];
        }

        return $this->figure($title, $subtitle, SvgChart::map($areas, self::HALF, 280), $items, 'No boundary shapes to draw.');
    }

    /** @param array<string, mixed> $m */
    private function largestLgas(array $m): ReportFigure
    {
        $byLga = (array) ($m['registry']['beneficiaries']['by_lga'] ?? []);
        arsort($byLga);

        $rows = [];
        foreach (array_slice($byLga, 0, self::LGA_LIMIT, true) as $lga => $n) {
            $rows[] = ['label' => $lga === 'unspecified' ? 'Not recorded' : Str::headline((string) $lga), 'count' => (int) $n];
        }
        $rest = array_slice($byLga, self::LGA_LIMIT, null, true);

        return $this->figure(
            'Largest LGAs',
            'Beneficiaries by local government area',
            SvgChart::bars($rows, self::HALF, $this->minimum),
            [],
            'No beneficiaries recorded in this view yet.',
            $rest !== [] ? 'And '.count($rest).' more LGAs with '.$this->count(array_sum($rest)).' beneficiaries between them.' : null,
        );
    }

    /** @param array<string, mixed> $m */
    private function benefits(array $m): ReportFigure
    {
        $groups = (array) ($m['benefits']['by_type'] ?? []);
        usort($groups, static fn (array $a, array $b): int => ($b['benefit_count'] ?? 0) <=> ($a['benefit_count'] ?? 0));

        $rows = [];
        $items = [];
        foreach ($groups as $group) {
            $label = isset($group['key']) && $group['key'] !== '' ? Str::headline((string) $group['key']) : 'Unspecified';
            $rows[] = ['label' => $label, 'count' => (int) ($group['benefit_count'] ?? 0)];
            $items[] = $this->item("{$label} · value delivered", $this->naira($group['total_value'] ?? 0));
        }

        return $this->figure(
            'Benefits delivered',
            'Deliveries recorded, by type of benefit',
            SvgChart::bars($rows, self::HALF, $this->minimum),
            $items,
            'No benefits delivered in this view yet.',
        );
    }

    /** @param array<string, mixed> $m */
    private function records(array $m): ReportFigure
    {
        $bySource = (array) ($m['registry']['beneficiaries']['by_source'] ?? []);
        arsort($bySource);
        $byStatus = (array) ($m['registry']['beneficiaries']['by_status'] ?? []);

        $sources = [];
        $index = 0;
        $other = 0;
        foreach ($bySource as $value => $n) {
            if ($index < count(self::CATEGORY_COLORS)) {
                $sources[] = [
                    'label' => RegistrationSource::tryFrom((string) $value)?->label() ?? Str::headline((string) $value),
                    'value' => (int) $n,
                    'color' => self::CATEGORY_COLORS[$index],
                ];
            } else {
                $other += (int) $n;
            }
            $index++;
        }
        if ($other > 0) {
            $sources[] = ['label' => 'Other sources', 'value' => $other, 'color' => self::OTHER_COLOR];
        }

        $statuses = [];
        foreach (self::STATUSES as $key => [$label, $color]) {
            $statuses[] = ['label' => $label, 'value' => (int) ($byStatus[$key] ?? 0), 'color' => $color];
        }

        $chart = SvgChart::splitBars([
            ['title' => 'How they were registered', 'segments' => $sources],
            ['title' => 'Status of records', 'segments' => $statuses],
        ], self::HALF);

        return $this->figure(
            'Records',
            'How they came in, and where they stand',
            $chart,
            [...$this->shareItems($sources), ...$this->shareItems($statuses)],
            'No records in this view yet.',
        );
    }

    /* ---------------------------------------------------------------- table */

    /**
     * The programme table: reached against target, value delivered against budget.
     *
     * @param  array<string, mixed>  $m
     * @return array{0: list<ReportColumn>, 1: list<array<string, string>>}
     */
    private function programmes(array $m): array
    {
        $programmes = (array) ($m['programme_performance'] ?? []);
        usort($programmes, static fn (array $a, array $b): int => ($b['reached'] ?? 0) <=> ($a['reached'] ?? 0));

        $columns = [
            new ReportColumn('programme', 'Programme'),
            new ReportColumn('reached', 'Reached', numeric: true),
            new ReportColumn('target', 'Target', numeric: true),
            new ReportColumn('progress', 'Progress', numeric: true),
            new ReportColumn('delivered', 'Value delivered', numeric: true),
            new ReportColumn('budget', 'Budget', numeric: true),
            new ReportColumn('status', 'Status'),
        ];

        $rows = array_map(fn (array $p): array => [
            'programme' => (string) ($p['name'] ?? 'Unnamed programme'),
            'reached' => $this->count($p['reached'] ?? 0),
            'target' => ($p['target'] ?? 0) > 0 ? number_format((int) $p['target']) : '—',
            'progress' => ($p['target'] ?? 0) > 0 ? $this->percent($p['completion_rate'] ?? null) : '—',
            'delivered' => $this->naira($p['budget']['spent'] ?? 0),
            'budget' => ($p['budget']['allocated'] ?? 0) > 0 ? $this->naira($p['budget']['allocated']) : '—',
            'status' => self::LIGHT_LABELS[(string) ($p['traffic_light'] ?? 'unrated')] ?? 'No target set',
        ], $programmes);

        return [$columns, $rows];
    }

    /* --------------------------------------------------------------- wording */

    /** The filters in force, in words, so the file says what it covers. */
    private function filterLabel(DashboardFilter $filter): string
    {
        $programme = null;
        if ($filter->programmeId !== null) {
            $name = Programme::query()->whereKey($filter->programmeId)->value('name');
            $programme = is_string($name) ? $name : 'Selected programme';
        }

        $period = match (true) {
            $filter->year !== null && $filter->quarter !== null => "Q{$filter->quarter} {$filter->year}",
            $filter->year !== null && $filter->month !== null => Carbon::create($filter->year, $filter->month, 1)->format('F Y'),
            $filter->year !== null => (string) $filter->year,
            $filter->quarter !== null => "Q{$filter->quarter}, all years",
            default => 'All periods',
        };

        return implode(' · ', [
            $period,
            $programme ?? 'All programmes',
            $filter->lga !== null ? Str::headline($filter->lga) : 'All LGAs',
            ...($filter->ward !== null ? [Str::headline($filter->ward)] : []),
        ]);
    }

    /**
     * @param  array{uri: string, width: int, height: int}|null  $chart
     * @param  list<array{label: string, value: string, color?: string}>  $items
     */
    private function figure(string $title, string $subtitle, ?array $chart, array $items, ?string $emptyNote, ?string $note = null): ReportFigure
    {
        if ($chart === null) {
            return new ReportFigure($title, $subtitle, note: $emptyNote);
        }

        return new ReportFigure($title, $subtitle, $chart['uri'], $chart['width'], $chart['height'], $items, $note);
    }

    /**
     * @param  mixed  $raw
     * @return list<array{month: string, value: float}>
     */
    private function points($raw): array
    {
        return array_values(array_map(
            static fn (array $point): array => ['month' => (string) ($point['month'] ?? ''), 'value' => (float) ($point['value'] ?? 0)],
            array_slice(array_filter((array) $raw, 'is_array'), -12),
        ));
    }

    /**
     * Latest, highest and total, so the chart's shape comes with its numbers.
     *
     * @param  list<array{month: string, value: float}>  $points
     * @param  callable(float): string  $format
     * @return list<array{label: string, value: string}>
     */
    private function trendItems(array $points, callable $format): array
    {
        if ($points === []) {
            return [];
        }

        $values = array_map(static fn (array $p): float => $p['value'], $points);
        $latest = $points[count($points) - 1];
        $highest = $points[(int) array_search(max($values), $values, true)];

        return [
            $this->item('Latest, '.$this->monthLong($latest['month']), $format($latest['value'])),
            $this->item('Highest, '.$this->monthLong($highest['month']), $format($highest['value'])),
            $this->item('Last '.count($points).' months', $format(array_sum($values))),
        ];
    }

    /**
     * @param  list<array{label: string, value: int, color: string}>  $parts
     * @return list<array{label: string, value: string, color: string}>
     */
    private function shareItems(array $parts): array
    {
        $total = array_sum(array_map(static fn (array $p): int => $p['value'], $parts));

        return array_map(fn (array $part): array => [
            'label' => $part['label'],
            'value' => $this->count($part['value']).($this->held($part['value']) || $total === 0 ? '' : ' · '.round($part['value'] / $total * 100).'%'),
            'color' => $part['color'],
        ], $parts);
    }

    /** @return array{label: string, value: string} */
    private function item(string $label, string $value): array
    {
        return ['label' => $label, 'value' => $value];
    }

    private function monthLong(string $ym): string
    {
        $date = Carbon::createFromFormat('Y-m', $ym);

        return $date === null ? $ym : $date->format('M Y');
    }

    private function ratio(mixed $value): ?float
    {
        return $value === null ? null : (float) $value;
    }

    private function held(int $n): bool
    {
        return $this->minimum !== null && $this->minimum > 0 && $n > 0 && $n < $this->minimum;
    }

    private function count(mixed $n): string
    {
        $value = (int) $n;

        return $this->held($value) ? '< '.$this->minimum : number_format($value);
    }

    private function naira(mixed $kobo): string
    {
        return '₦'.number_format(((int) $kobo) / 100, 2);
    }

    private function percent(mixed $ratio): string
    {
        return $ratio === null ? '—' : round((float) $ratio * 100).'%';
    }
}
