<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Export\ReportSummarySection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Assembles the segment builder's output (FR-RPT-03): the table, the optional chart
 * breakdown, and the {@see ReportData} the shared exporters render.
 *
 * One query definition drives all three, so the CSV a person downloads is the table
 * they were looking at and the chart is the same population counted a different way. A
 * chart built from a second query is a chart that can disagree with its own table.
 */
class SegmentReportService
{
    public function __construct(
        private readonly SegmentQueryBuilder $queries,
        private readonly SegmentDimensionRegistry $registry,
        private readonly CellSizeGuard $guard,
    ) {}

    public function pageSize(): int
    {
        return max(1, (int) config('reporting.segment_page_size', 50));
    }

    public function syncMax(): int
    {
        return max(1, (int) config('reporting.segment_sync_max', 2000));
    }

    public function total(SegmentDefinition $definition, SegmentAccess $access): int
    {
        return $this->queries->query($definition, $access->scope)->count();
    }

    /**
     * The on-screen result: a count always, rows only for a tier entitled to them.
     *
     * @return array<string, mixed>
     */
    public function preview(SegmentDefinition $definition, SegmentAccess $access, int $page = 1): array
    {
        $total = $this->total($definition, $access);

        // The TOTAL is suppressed only where the caller cannot see the rows. Hiding a
        // number from someone who is entitled to the underlying list is theatre — they
        // would count it — and it breaks pagination, which needs to know how many pages
        // there are. Group suppression still applies to their aggregates.
        $totalSuppressed = $this->guard->totalIsSuppressed(
            $total,
            $access->cellSizeGuard && ! $access->showsRows(),
        );

        return [
            'total' => $totalSuppressed ? null : $total,
            'total_suppressed' => $totalSuppressed,
            'tier' => $access->tier,
            'reveal_pii' => $access->revealPii,
            'cell_size_guard' => $access->cellSizeGuard,
            'minimum_cell_size' => $this->guard->minimum(),
            'columns' => $access->showsRows()
                ? array_map(static fn (ReportColumn $c): array => ['key' => $c->key, 'label' => $c->label], $this->columns($access))
                : [],
            'rows' => $access->showsRows() ? $this->rows($definition, $access, $page) : [],
            'page' => $page,
            'page_size' => $this->pageSize(),
            'breakdown' => $definition->breakdown === null
                ? null
                : $this->breakdown($definition, $access),
        ];
    }

    /**
     * The chart: the same population counted by one dimension.
     *
     * Available at EVERY tier, including the aggregate ones — a breakdown of counts is
     * exactly what a partner or an executive is entitled to. The guard is what makes
     * that safe, and it runs here regardless of tier flag ordering.
     *
     * @return array<string, mixed>
     */
    public function breakdown(SegmentDefinition $definition, SegmentAccess $access): array
    {
        $dimension = $this->registry->get((string) $definition->breakdown);
        if ($dimension === null) {
            return [];
        }

        $query = $this->queries->query($definition, $access->scope);
        $column = $this->groupableColumn($dimension);

        if ($column === null) {
            // A relationship dimension has no column on `beneficiaries` to group by.
            // Returning nothing is honest; inventing a join here would make the chart
            // count enrollments while the table counts people.
            return ['dimension' => $dimension->key, 'label' => $dimension->label, 'unsupported' => true, 'groups' => []];
        }

        /** @var array<string, int> $counts */
        $counts = $query->reorder()
            ->select($column)
            ->selectRaw('count(*) as aggregate')
            ->groupBy($column)
            ->pluck('aggregate', $column)
            ->all();

        $groups = [];
        foreach ($counts as $value => $count) {
            $key = (string) $value;
            $groups[] = [
                'key' => $key === '' ? 'unknown' : $key,
                'label' => $this->labelFor($dimension, $key),
                'count' => (int) $count,
            ];
        }

        usort($groups, static fn (array $a, array $b): int => $b['count'] <=> $a['count']);

        return [
            'dimension' => $dimension->key,
            'label' => $dimension->label,
            ...$this->guard->apply($groups, $access->cellSizeGuard),
        ];
    }

    /**
     * The exportable payload. Rows for an entitled tier; the breakdown otherwise, so a
     * partner or executive still gets a real file — an aggregate one.
     */
    public function toReportData(SegmentDefinition $definition, SegmentAccess $access, bool $withSummary = false): ReportData
    {
        $generatedAt = Carbon::now();
        $summary = $withSummary ? $this->summary($definition, $access) : [];

        if (! $access->showsRows()) {
            return $this->aggregateReportData($definition, $access, $generatedAt, $summary, $withSummary);
        }

        $rows = [];
        $this->queries->query($definition, $access->scope)
            ->orderBy('registration_date')->orderBy('id')
            ->chunk(500, function ($chunk) use (&$rows): void {
                foreach ($chunk as $beneficiary) {
                    $rows[] = $this->row($beneficiary);
                }
            });

        return new ReportData(
            reportKey: 'segment',
            title: 'People report',
            subtitle: $definition->label(),
            scopeLabel: $access->scope->label,
            generatedAt: $generatedAt,
            columns: $this->columns($access),
            rows: $rows,
            summary: $summary,
            crest: $withSummary,
        );
    }

    /**
     * Headline counts for the top of an exported segment: how many people, and how they
     * divide by gender, age group, household, status, source and LGA.
     *
     * Counted from the SAME query as the rows, so the summary describes exactly the
     * people in the table beneath it. The small-cell guard applies to each count on the
     * same terms as the chart breakdown; when the whole segment is too small to publish,
     * only the withheld total is printed — a divided-up small group is the disclosure the
     * guard exists to prevent.
     *
     * @return list<ReportSummarySection>
     */
    public function summary(SegmentDefinition $definition, SegmentAccess $access): array
    {
        $base = $this->queries->query($definition, $access->scope)->reorder();
        $total = (clone $base)->count();
        $withheld = '< '.$this->guard->minimum();

        if ($this->guard->totalIsSuppressed($total, $access->cellSizeGuard && ! $access->showsRows())) {
            return [new ReportSummarySection('Overview', [['label' => 'People in this report', 'value' => $withheld]])];
        }

        $count = fn (int $n): string => $this->guard->totalIsSuppressed($n, $access->cellSizeGuard) ? $withheld : number_format($n);
        $items = static function (array $counts) use ($count): array {
            $out = [];
            foreach ($counts as $label => $n) {
                $out[] = ['label' => (string) $label, 'value' => $count((int) $n)];
            }

            return $out;
        };

        $inHousehold = (clone $base)
            ->whereIn('beneficiaries.id', HouseholdMembership::query()->whereNull('left_at')->select('beneficiary_id'))
            ->count();

        $byGender = $this->countsBy($base, 'gender');
        $gender = [
            'Women' => $byGender[Gender::Female->value] ?? 0,
            'Men' => $byGender[Gender::Male->value] ?? 0,
            Gender::Other->label() => $byGender[Gender::Other->value] ?? 0,
            'Not recorded' => $byGender[''] ?? 0,
        ];

        $byStatus = $this->countsBy($base, 'status');
        $status = [];
        foreach (BeneficiaryStatus::cases() as $case) {
            $status[$case->label()] = $byStatus[$case->value] ?? 0;
        }

        $sources = [];
        foreach ($this->countsBy($base, 'registration_source') as $value => $n) {
            $sources[RegistrationSource::tryFrom($value)?->label() ?? Str::headline($value)] = $n;
        }
        arsort($sources);

        $lgas = [];
        foreach ($this->countsBy($base, 'lga') as $value => $n) {
            $lgas[$value === '' ? 'Not recorded' : (Lga::tryFrom($value)?->label() ?? Str::headline($value))] = $n;
        }
        arsort($lgas);

        return [
            new ReportSummarySection('Overview', $items([
                'People in this report' => $total,
                'In a household' => $inHousehold,
                'Registered as individuals' => max(0, $total - $inHousehold),
            ])),
            new ReportSummarySection('Gender', $items($gender)),
            new ReportSummarySection('Age group', $items($this->ageGroups($base))),
            new ReportSummarySection('Status', $items($status)),
            new ReportSummarySection('How they were registered', $items($sources)),
            new ReportSummarySection('Local government area', $items($lgas)),
        ];
    }

    /**
     * The population a definition describes, as a query for a caller to aggregate.
     *
     * Exposed for chart-led reports that need to count the same population several ways
     * ({@see RegisterProfileExportBuilder}) rather than materialise it. Returns a
     * scoped, unordered builder — every consumer clones it before adding conditions.
     *
     * @return Builder<Beneficiary>
     */
    public function baseQuery(SegmentDefinition $definition, SegmentAccess $access): Builder
    {
        return $this->queries->query($definition, $access->scope)->reorder();
    }

    /** The smallest group that may be published, for callers marking suppressed bars. */
    public function minimumCellSize(): int
    {
        return $this->guard->minimum();
    }

    /**
     * Gender against age band, for the register pyramid (FR-RPT-12).
     *
     * Returned OLDEST FIRST, because that is how a population pyramid is read — the
     * elderly at the top, children at the base. `config('reporting.age_bands')` is
     * written youngest first for every other consumer, so it is reversed here rather
     * than the config being reordered under them.
     *
     * "Not recorded" is deliberately absent: a band with no age is not a position on an
     * age axis, and drawing it as one would put a bar where no reader can place it. The
     * count is reported in the figures beside the chart instead.
     *
     * @param  Builder<Beneficiary>  $base
     * @return list<array{band: string, female: int, male: int}>
     */
    public function genderByAge(Builder $base): array
    {
        $today = Carbon::today();
        $bands = array_reverse((array) config('reporting.age_bands', []), true);
        $out = [];

        foreach ($bands as $key => $range) {
            [$min, $max] = $range;

            $inBand = (clone $base)
                ->whereNotNull('beneficiaries.date_of_birth')
                ->whereDate('beneficiaries.date_of_birth', '<=', $today->copy()->subYears((int) $min)->toDateString());

            if ($max !== null) {
                $inBand->whereDate('beneficiaries.date_of_birth', '>', $today->copy()->subYears((int) $max)->toDateString());
            }

            $byGender = $this->countsBy($inBand, 'gender');
            $ages = $max === null ? "{$min}+" : $min.'–'.((int) $max - 1);

            $out[] = [
                'band' => Str::headline((string) $key)." ({$ages})",
                'female' => (int) ($byGender[Gender::Female->value] ?? 0),
                'male' => (int) ($byGender[Gender::Male->value] ?? 0),
            ];
        }

        return $out;
    }

    /**
     * @param  Builder<Beneficiary>  $base
     * @return array<string, int> keyed by stored value; a missing value is keyed ''
     */
    private function countsBy(Builder $base, string $column): array
    {
        $out = [];
        $rows = (clone $base)->toBase()
            ->selectRaw("beneficiaries.{$column} as k, count(*) as c")
            ->groupBy("beneficiaries.{$column}")
            ->get();

        foreach ($rows as $row) {
            $out[(string) ($row->k ?? '')] = (int) $row->c;
        }

        return $out;
    }

    /**
     * The configured age bands (`reporting.age_bands`), labelled with their ages. Bands
     * are computed as birth-date boundaries so the edges are exact on every driver.
     *
     * @param  Builder<Beneficiary>  $base
     * @return array<string, int>
     */
    private function ageGroups(Builder $base): array
    {
        $today = Carbon::today();
        $out = [];

        foreach ((array) config('reporting.age_bands', []) as $key => $range) {
            [$min, $max] = $range;
            $query = (clone $base)
                ->whereNotNull('beneficiaries.date_of_birth')
                ->whereDate('beneficiaries.date_of_birth', '<=', $today->copy()->subYears((int) $min)->toDateString());

            if ($max !== null) {
                $query->whereDate('beneficiaries.date_of_birth', '>', $today->copy()->subYears((int) $max)->toDateString());
            }

            $ages = $max === null ? "{$min}+" : $min.'–'.((int) $max - 1);
            $out[Str::headline((string) $key)." ({$ages})"] = $query->count();
        }

        $out['Not recorded'] = (clone $base)->whereNull('beneficiaries.date_of_birth')->count();

        return $out;
    }

    /**
     * @param  list<ReportSummarySection>  $summary
     */
    private function aggregateReportData(SegmentDefinition $definition, SegmentAccess $access, Carbon $generatedAt, array $summary = [], bool $crest = false): ReportData
    {
        $breakdown = $definition->breakdown === null ? [] : $this->breakdown($definition, $access);
        $rows = [];

        foreach ($breakdown['groups'] ?? [] as $group) {
            $rows[] = [
                'group' => $group['label'],
                'count' => $group['suppressed'] ? CellSizeGuard::SUPPRESSED : (string) $group['count'],
            ];
        }

        if ($rows === []) {
            $total = $this->total($definition, $access);
            $rows[] = [
                'group' => 'All matching beneficiaries',
                'count' => $this->guard->totalIsSuppressed($total, $access->cellSizeGuard && ! $access->showsRows())
                    ? CellSizeGuard::SUPPRESSED
                    : (string) $total,
            ];
        }

        return new ReportData(
            reportKey: 'segment',
            title: 'People report (counts only)',
            subtitle: $definition->label(),
            scopeLabel: $access->scope->label,
            generatedAt: $generatedAt,
            columns: [
                new ReportColumn('group', 'Group'),
                new ReportColumn('count', 'Beneficiaries'),
            ],
            rows: $rows,
            summary: $summary,
            crest: $crest,
        );
    }

    /**
     * Row columns. NIN/BVN are marked SENSITIVE unless the caller may reveal them, so
     * {@see ReportData::cell()} masks them before any exporter sees the value — the
     * masking is not something each format has to remember.
     *
     * @return list<ReportColumn>
     */
    public function columns(SegmentAccess $access): array
    {
        return [
            new ReportColumn('first_name', 'First name'),
            new ReportColumn('last_name', 'Last name'),
            new ReportColumn('nin', 'NIN', sensitive: ! $access->revealPii),
            new ReportColumn('bvn', 'BVN', sensitive: ! $access->revealPii),
            new ReportColumn('gender', 'Gender'),
            new ReportColumn('age', 'Age'),
            new ReportColumn('lga', 'LGA'),
            new ReportColumn('ward', 'Ward'),
            new ReportColumn('status', 'Status'),
            new ReportColumn('registration_source', 'Source'),
            new ReportColumn('registration_date', 'Registered'),
        ];
    }

    /**
     * @return list<array<string, scalar|null>>
     */
    private function rows(SegmentDefinition $definition, SegmentAccess $access, int $page): array
    {
        $size = $this->pageSize();

        return $this->queries->query($definition, $access->scope)
            ->orderBy('registration_date')->orderBy('id')
            ->forPage(max(1, $page), $size)
            ->get()
            ->map(fn (Beneficiary $b): array => $this->row($b))
            ->all();
    }

    /**
     * @return array<string, scalar|null>
     */
    private function row(Beneficiary $beneficiary): array
    {
        return [
            'first_name' => $beneficiary->first_name,
            'last_name' => $beneficiary->last_name,
            'nin' => $beneficiary->nin,
            'bvn' => $beneficiary->bvn,
            'gender' => $beneficiary->gender?->value,
            'age' => $beneficiary->date_of_birth?->age,
            'lga' => $beneficiary->lga,
            'ward' => $beneficiary->ward,
            'status' => $beneficiary->status->value,
            'registration_source' => $beneficiary->registration_source->value,
            'registration_date' => $beneficiary->registration_date->toDateString(),
        ];
    }

    /** The `beneficiaries` column a dimension can be grouped by, or null. */
    private function groupableColumn(SegmentDimension $dimension): ?string
    {
        if (! $dimension->groupable || $dimension->kind === SegmentDimension::KIND_AGE) {
            // Age would group by raw birth date — one bar per person. Bands are a
            // separate feature; a thousand one-count bars is not a chart.
            return null;
        }

        return $dimension->column;
    }

    private function labelFor(SegmentDimension $dimension, string $value): string
    {
        if ($value === '') {
            return 'Unknown';
        }

        foreach ($dimension->options as $option) {
            if ($option['value'] === $value) {
                return $option['label'];
            }
        }

        if ($dimension->column === 'owner_mda_id') {
            return $this->nameOf($value);
        }

        return Str::headline($value);
    }

    private function nameOf(string $id): string
    {
        $name = Mda::query()->withoutGlobalScope(MdaScope::class)->whereKey($id)->value('name');

        return is_string($name) ? $name : 'Unknown';
    }
}
