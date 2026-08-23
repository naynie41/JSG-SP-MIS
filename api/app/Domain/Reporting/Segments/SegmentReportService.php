<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
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
    public function toReportData(SegmentDefinition $definition, SegmentAccess $access): ReportData
    {
        $generatedAt = Carbon::now();

        if (! $access->showsRows()) {
            return $this->aggregateReportData($definition, $access, $generatedAt);
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
            title: 'Beneficiary segment',
            subtitle: $definition->label(),
            scopeLabel: $access->scope->label,
            generatedAt: $generatedAt,
            columns: $this->columns($access),
            rows: $rows,
        );
    }

    private function aggregateReportData(SegmentDefinition $definition, SegmentAccess $access, Carbon $generatedAt): ReportData
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
            title: 'Beneficiary segment (aggregate)',
            subtitle: $definition->label(),
            scopeLabel: $access->scope->label,
            generatedAt: $generatedAt,
            columns: [
                new ReportColumn('group', 'Group'),
                new ReportColumn('count', 'Beneficiaries'),
            ],
            rows: $rows,
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
        return match ($dimension->kind) {
            SegmentDimension::KIND_AGE => null, // bands, not raw dates — handled separately
            default => str_contains($dimension->column, '.') || $dimension->column === 'household_membership'
                ? null
                : $dimension->column,
        };
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
