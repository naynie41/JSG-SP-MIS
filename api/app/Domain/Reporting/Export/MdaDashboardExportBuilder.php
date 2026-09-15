<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Reporting\Support\DashboardFilter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * The MDA Reports dashboard as a PDF: the page the officer was looking at, on paper.
 *
 * It replaced the executive export for MDA scopes, which printed a different report
 * under different words — "Net-unique beneficiaries" there was another figure from the
 * tile of the same name on screen. Here every block reads the SAME fields the dashboard
 * renders (the tiles' figures are the ones `summariseReporting()` shows), in the same
 * words, so a number quoted from the PDF is the number on the screen.
 *
 * Aggregates only. The small-cell rule the dashboard publishes (`min_cell_size`) is
 * applied to every count here as it is on screen; it is null for an MDA's own data.
 */
class MdaDashboardExportBuilder
{
    private const STATUS_LABELS = [
        'active' => 'Active',
        'flagged' => 'Flagged for review',
        'suspended' => 'Suspended',
    ];

    private const LIGHT_LABELS = [
        'green' => 'On target',
        'yellow' => 'Behind',
        'red' => 'Off target',
        'unrated' => 'No target set',
    ];

    private const HOUSEHOLD_BANDS = ['1' => '1 person', '2-3' => '2 to 3 people', '4-6' => '4 to 6 people', '7+' => '7 or more people'];

    private const LGA_LIMIT = 10;

    private ?int $minimum = null;

    /**
     * @param  array<string, mixed>  $dashboard  the payload `DashboardService::forUser()` returns
     * @param  string|null  $scopeLabel  the MDA's name for the letterhead; the scope's own label otherwise
     */
    public function build(array $dashboard, DashboardFilter $filter, ?string $scopeLabel = null): ReportData
    {
        $m = (array) ($dashboard['metrics'] ?? []);
        $this->minimum = isset($dashboard['min_cell_size']) ? (int) $dashboard['min_cell_size'] : null;

        $summary = array_values(array_filter([
            $this->atAGlance($m),
            $this->quality($m),
            $this->gender($m),
            $this->ages($m),
            $this->households($m),
            $this->sources($m),
            $this->statuses($m),
            $this->benefits($m),
            $this->lgas($m),
            $this->trend($m),
        ]));

        [$columns, $rows] = $this->programmes($m);

        return new ReportData(
            reportKey: 'mda-dashboard',
            title: 'MDA dashboard',
            subtitle: $this->filterLabel($filter),
            scopeLabel: $scopeLabel ?? (string) ($dashboard['scope']['label'] ?? ''),
            generatedAt: Carbon::now(),
            columns: $columns,
            rows: $rows,
            summary: $summary,
            crest: true,
        );
    }

    /* ------------------------------------------------------------------ blocks */

    /** @param array<string, mixed> $m */
    private function atAGlance(array $m): ReportSummarySection
    {
        $population = (array) ($m['population'] ?? []);

        $items = [
            $this->item('Net-unique beneficiaries', $this->count($m['registry']['beneficiaries']['total'] ?? 0)),
            $this->item('Households', $this->count($m['registry']['households']['total'] ?? 0)),
            $this->item('Active programmes', $this->count($m['programmes']['active'] ?? 0)),
            $this->item('Active activities', $this->count($m['programmes']['activities_active'] ?? 0)),
            $this->item('Benefit deliveries', $this->count($m['benefits']['disbursed']['benefit_count'] ?? 0)),
            $this->item('Value delivered', $this->naira($m['benefits']['disbursed']['total_value'] ?? 0)),
            $this->item('Duplicates surfaced', $this->count($m['duplicates']['matches_surfaced'] ?? 0)),
        ];

        if ($population !== []) {
            $items[] = $this->item(
                'New in the last '.(int) ($population['period_days'] ?? 0).' days',
                $this->count($population['new_registrations_period'] ?? 0),
            );
        }

        return new ReportSummarySection('At a glance', $items);
    }

    /** @param array<string, mixed> $m */
    private function quality(array $m): ?ReportSummarySection
    {
        $quality = (array) ($m['registry_quality'] ?? []);
        $total = (int) ($quality['total'] ?? 0);
        if ($total === 0) {
            return null;
        }

        return new ReportSummarySection('Quality of your records', [
            $this->item('Verified', $this->percent(($quality['verified'] ?? 0) / $total)),
            $this->item('NIN recorded', $this->percent($quality['nin_completeness'] ?? null)),
            $this->item('Phone recorded', $this->percent($quality['phone_completeness'] ?? null)),
            $this->item('Overall completeness', $this->percent($quality['data_completeness'] ?? null)),
        ]);
    }

    /** @param array<string, mixed> $m */
    private function gender(array $m): ?ReportSummarySection
    {
        $demographics = (array) ($m['demographics'] ?? []);
        if ($demographics === []) {
            return null;
        }

        $by = (array) ($demographics['by_gender'] ?? []);
        $women = (int) ($by['female'] ?? 0);
        $men = (int) ($by['male'] ?? 0);
        $other = (int) ($by['other'] ?? 0);

        $items = [
            $this->item('Women', $this->count($women)),
            $this->item('Men', $this->count($men)),
        ];
        if ($other > 0) {
            $items[] = $this->item('Other', $this->count($other));
        }
        $items[] = $this->item('Not recorded', $this->count($by['unspecified'] ?? 0));
        // A share computed from a withheld count would give the count back.
        $items[] = $this->item('Share who are women', $this->held($women) || $this->held($men) ? '—' : $this->percent($demographics['female_pct'] ?? null));

        return new ReportSummarySection('Women and men', $items);
    }

    /** @param array<string, mixed> $m */
    private function ages(array $m): ?ReportSummarySection
    {
        $bands = (array) ($m['demographics']['age_bands'] ?? []);
        if ($bands === []) {
            return null;
        }

        $items = [];
        foreach ((array) config('reporting.age_bands', []) as $key => $range) {
            [$min, $max] = $range;
            $ages = $max === null ? "{$min}+" : $min.'–'.((int) $max - 1);
            $items[] = $this->item(Str::headline((string) $key)." ({$ages})", $this->count($bands[$key] ?? 0));
        }
        $items[] = $this->item('Not recorded', $this->count($bands['unknown'] ?? 0));

        return new ReportSummarySection('Age groups', $items);
    }

    /** @param array<string, mixed> $m */
    private function households(array $m): ?ReportSummarySection
    {
        $sizes = (array) ($m['household_size'] ?? []);
        $split = (array) ($m['demographics']['household_vs_individual'] ?? []);
        if ($sizes === [] && $split === []) {
            return null;
        }

        $items = [$this->item('Households', $this->count($sizes['total_households'] ?? 0))];
        if (isset($sizes['average_size'])) {
            $items[] = $this->item('People per household, on average', number_format((float) $sizes['average_size'], 1));
        }
        foreach (self::HOUSEHOLD_BANDS as $key => $label) {
            $items[] = $this->item("Households of {$label}", $this->count($sizes['bands'][$key] ?? 0));
        }
        $items[] = $this->item('People in a household', $this->count($split['in_household'] ?? 0));
        $items[] = $this->item('Registered as individuals', $this->count($split['individual'] ?? 0));

        return new ReportSummarySection('Household size', $items);
    }

    /** @param array<string, mixed> $m */
    private function sources(array $m): ?ReportSummarySection
    {
        $bySource = (array) ($m['registry']['beneficiaries']['by_source'] ?? []);
        if ($bySource === []) {
            return null;
        }
        arsort($bySource);

        $items = [];
        foreach ($bySource as $value => $n) {
            $items[] = $this->item(RegistrationSource::tryFrom((string) $value)?->label() ?? Str::headline((string) $value), $this->count($n));
        }

        return new ReportSummarySection('How records came in', $items);
    }

    /** @param array<string, mixed> $m */
    private function statuses(array $m): ReportSummarySection
    {
        $byStatus = (array) ($m['registry']['beneficiaries']['by_status'] ?? []);

        $items = [];
        foreach (self::STATUS_LABELS as $key => $label) {
            $items[] = $this->item($label, $this->count($byStatus[$key] ?? 0));
        }

        return new ReportSummarySection('Status of records', $items);
    }

    /** @param array<string, mixed> $m */
    private function benefits(array $m): ?ReportSummarySection
    {
        $groups = (array) ($m['benefits']['by_type'] ?? []);
        if ($groups === []) {
            return null;
        }
        usort($groups, static fn (array $a, array $b): int => ($b['benefit_count'] ?? 0) <=> ($a['benefit_count'] ?? 0));

        $items = [];
        foreach ($groups as $group) {
            $label = isset($group['key']) && $group['key'] !== '' ? Str::headline((string) $group['key']) : 'Unspecified';
            $items[] = $this->item($label, $this->count($group['benefit_count'] ?? 0).' · '.$this->naira($group['total_value'] ?? 0));
        }

        return new ReportSummarySection('Benefits delivered', $items);
    }

    /** @param array<string, mixed> $m */
    private function lgas(array $m): ?ReportSummarySection
    {
        $byLga = (array) ($m['registry']['beneficiaries']['by_lga'] ?? []);
        if ($byLga === []) {
            return null;
        }
        arsort($byLga);

        $items = [];
        foreach (array_slice($byLga, 0, self::LGA_LIMIT, true) as $lga => $n) {
            $items[] = $this->item($lga === 'unspecified' ? 'Not recorded' : Str::headline((string) $lga), $this->count($n));
        }
        $rest = array_slice($byLga, self::LGA_LIMIT, null, true);
        if ($rest !== []) {
            $items[] = $this->item(count($rest).' other LGAs', $this->count(array_sum($rest)));
        }

        return new ReportSummarySection('Largest LGAs', $items);
    }

    /** @param array<string, mixed> $m */
    private function trend(array $m): ?ReportSummarySection
    {
        $points = array_slice((array) ($m['trends']['registrations'] ?? []), -12);
        if ($points === []) {
            return null;
        }

        $items = [];
        foreach ($points as $point) {
            $items[] = $this->item(Carbon::createFromFormat('Y-m', (string) $point['month'])->format('M Y'), $this->count($point['value'] ?? 0));
        }

        return new ReportSummarySection('New registrations by month', $items);
    }

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

    /** @return array{label: string, value: string} */
    private function item(string $label, string $value): array
    {
        return ['label' => $label, 'value' => $value];
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
