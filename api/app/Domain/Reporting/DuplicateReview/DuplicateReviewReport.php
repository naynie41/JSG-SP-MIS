<?php

declare(strict_types=1);

namespace App\Domain\Reporting\DuplicateReview;

use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Export\ReportSummarySection;
use App\Domain\Reporting\Reports\AdHoc\AdHocDatasetRegistry;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * The duplicate review report (FR-DUP): where the match queue stands.
 *
 * It replaced a generic "group by band / count rows" dataset, which could only say how
 * many rows existed. The questions an MDA actually brings are different — how many
 * matches are still waiting, for how long, what was decided, and which upload the
 * backlog came from — so the report answers those and offers no builder.
 *
 * Counts only. A match row carries a person's identity in its payload; nothing here
 * reads it. Upload file names and activity names are the MDA's own operational labels.
 */
class DuplicateReviewReport
{
    /**
     * A batch in one of these states accepts no more decisions, so an undecided row in
     * it is not "waiting" — the upload finished or failed without one. Counting those as
     * a backlog would show a queue nobody can clear.
     */
    private const CLOSED = [ImportStatus::Completed->value, ImportStatus::Failed->value];

    /** Uploads listed in the per-upload table; the ones with the most waiting come first. */
    public const BATCH_LIMIT = 50;

    /** The same scopes the old `duplicates` dataset reached: governance, or an MDA's own. */
    public static function availableTo(DashboardScope $scope): bool
    {
        return AdHocDatasetRegistry::availableTo('duplicates', $scope);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(DashboardScope $scope, DuplicateReviewFilter $filter): array
    {
        $rows = $this->base($scope, $filter);

        $byBand = $this->countBy($rows, 'import_rows.match_band');
        $byResolution = $this->countBy($rows, 'import_rows.resolution');

        $surfaced = array_sum($byBand);
        $undecided = $byResolution[''] ?? 0;
        $closedUndecided = (clone $rows)->whereNull('import_rows.resolution')
            ->whereIn('import_batches.status', self::CLOSED)->count();
        $awaiting = $undecided - $closedUndecided;

        $batches = $this->batches($rows);

        return [
            'scope' => ['kind' => $scope->kind, 'label' => $scope->label],
            'filters' => $filter->toArray(),
            'totals' => [
                'surfaced' => $surfaced,
                'exact' => $byBand['exact'] ?? 0,
                'probable' => $byBand['probable'] ?? 0,
                'decided' => $surfaced - $undecided,
                'awaiting' => $awaiting,
                'closed_undecided' => $closedUndecided,
            ],
            'decisions' => array_combine(
                array_map(static fn (ImportRowResolution $r): string => $r->value, ImportRowResolution::cases()),
                array_map(static fn (ImportRowResolution $r): int => $byResolution[$r->value] ?? 0, ImportRowResolution::cases()),
            ),
            'waiting' => $this->waiting($rows),
            'median_hours_to_decide' => $this->medianHoursToDecide($rows),
            'batches' => array_slice($batches, 0, self::BATCH_LIMIT),
            'batches_total' => count($batches),
            'computed_at' => Carbon::now()->toIso8601String(),
        ];
    }

    /** The same report as a file: the per-upload table under the queue's headline counts. */
    public function toReportData(DashboardScope $scope, DuplicateReviewFilter $filter): ReportData
    {
        $report = $this->build($scope, $filter);
        $totals = $report['totals'];
        $number = static fn (int $n): string => number_format($n);
        $showMda = $scope->kind !== DashboardScope::KIND_MDA;

        $decisions = [];
        foreach (self::decisionLabels() as $key => $label) {
            $decisions[] = ['label' => $label, 'value' => $number($report['decisions'][$key] ?? 0)];
        }

        $summary = [
            new ReportSummarySection('Matches found', [
                ['label' => 'Total', 'value' => $number($totals['surfaced'])],
                ['label' => 'Exact', 'value' => $number($totals['exact'])],
                ['label' => 'Probable', 'value' => $number($totals['probable'])],
            ]),
            new ReportSummarySection('Review progress', [
                ['label' => 'Decided', 'value' => $number($totals['decided'])],
                ['label' => 'Awaiting a decision', 'value' => $number($totals['awaiting'])],
                ['label' => 'Upload finished before a decision', 'value' => $number($totals['closed_undecided'])],
                ['label' => 'Median time to decide', 'value' => self::duration($report['median_hours_to_decide'])],
            ]),
            new ReportSummarySection('Decisions taken', $decisions),
            new ReportSummarySection('How long matches have waited', array_map(
                static fn (array $band): array => ['label' => $band['label'], 'value' => $number($band['count'])],
                $report['waiting'],
            )),
        ];

        $columns = [
            new ReportColumn('file', 'Upload'),
            new ReportColumn('activity', 'Activity'),
            ...($showMda ? [new ReportColumn('mda', 'MDA')] : []),
            new ReportColumn('uploaded_at', 'Uploaded'),
            new ReportColumn('source', 'Source'),
            new ReportColumn('matches', 'Matches', numeric: true),
            new ReportColumn('exact', 'Exact', numeric: true),
            new ReportColumn('probable', 'Probable', numeric: true),
            new ReportColumn('decided', 'Decided', numeric: true),
            new ReportColumn('awaiting', 'Awaiting', numeric: true),
        ];

        $rows = array_map(static fn (array $batch): array => [
            ...$batch,
            'activity' => $batch['activity'] ?? '—',
            'mda' => $batch['mda'] ?? '—',
            'uploaded_at' => $batch['uploaded_at'] !== null ? Carbon::parse($batch['uploaded_at'])->toDateString() : '—',
            'source' => self::sourceLabel($batch['source']),
        ], $report['batches']);

        return new ReportData(
            reportKey: 'duplicate_review',
            title: 'Duplicate review',
            subtitle: $filter->label(),
            scopeLabel: $scope->label,
            generatedAt: Carbon::now(),
            columns: $columns,
            rows: $rows,
            summary: $summary,
            crest: true,
        );
    }

    /**
     * Plain-language names for each decision, matching the words on the resolution
     * screen so an officer recognises what they chose.
     *
     * @return array<string, string>
     */
    public static function decisionLabels(): array
    {
        return [
            ImportRowResolution::New->value => 'Created as a new person',
            ImportRowResolution::Link->value => 'Linked to another MDA’s record',
            ImportRowResolution::Own->value => 'Already your beneficiary',
            ImportRowResolution::Skip->value => 'Skipped',
        ];
    }

    /**
     * Flagged import rows in scope. Explicitly scoped by the batch's owner MDA — this is
     * a query-builder read, so no model scope is here to do it implicitly.
     */
    private function base(DashboardScope $scope, DuplicateReviewFilter $filter): Builder
    {
        return DB::table('import_rows')
            ->join('import_batches', 'import_rows.import_batch_id', '=', 'import_batches.id')
            ->whereIn('import_rows.match_band', $filter->band !== null ? [$filter->band] : DuplicateReviewFilter::bands())
            ->when($scope->mdaIds !== null, fn (Builder $q) => $q->whereIn('import_batches.owner_mda_id', $scope->mdaIds))
            ->when($filter->dateFrom !== null, fn (Builder $q) => $q->whereDate('import_rows.created_at', '>=', $filter->dateFrom))
            ->when($filter->dateTo !== null, fn (Builder $q) => $q->whereDate('import_rows.created_at', '<=', $filter->dateTo));
    }

    /**
     * @return array<string, int> keyed by value; a null value is keyed ''
     */
    private function countBy(Builder $rows, string $column): array
    {
        $out = [];
        foreach ((clone $rows)->selectRaw("{$column} as k, count(*) as c")->groupBy($column)->get() as $row) {
            $out[(string) ($row->k ?? '')] = (int) $row->c;
        }

        return $out;
    }

    /**
     * Rows still awaiting a decision, by how long ago the match was found.
     *
     * @return list<array{key: string, label: string, count: int}>
     */
    private function waiting(Builder $rows): array
    {
        [$short, $long] = array_map('intval', (array) config('reporting.duplicate_review_waiting_days', [7, 30])) + [7, 30];
        $now = Carbon::now();
        $pending = (clone $rows)->whereNull('import_rows.resolution')->whereNotIn('import_batches.status', self::CLOSED);

        return [
            [
                'key' => 'recent',
                'label' => "Under {$short} days",
                'count' => (clone $pending)->where('import_rows.created_at', '>', $now->copy()->subDays($short))->count(),
            ],
            [
                'key' => 'weeks',
                'label' => "{$short} to {$long} days",
                'count' => (clone $pending)
                    ->where('import_rows.created_at', '<=', $now->copy()->subDays($short))
                    ->where('import_rows.created_at', '>=', $now->copy()->subDays($long))
                    ->count(),
            ],
            [
                'key' => 'old',
                'label' => "Over {$long} days",
                'count' => (clone $pending)->where('import_rows.created_at', '<', $now->copy()->subDays($long))->count(),
            ],
        ];
    }

    /**
     * From the match being found to the decision on it. The median, because one upload
     * left over a holiday would drag an average far from what a typical match waits.
     */
    private function medianHoursToDecide(Builder $rows): ?float
    {
        $seconds = [];
        (clone $rows)
            ->whereNotNull('import_rows.resolved_at')
            ->select(['import_rows.id', 'import_rows.created_at', 'import_rows.resolved_at'])
            ->orderBy('import_rows.id')
            ->chunk(1000, function ($chunk) use (&$seconds): void {
                foreach ($chunk as $row) {
                    $seconds[] = abs(Carbon::parse($row->resolved_at)->getTimestamp() - Carbon::parse($row->created_at)->getTimestamp());
                }
            });

        if ($seconds === []) {
            return null;
        }

        sort($seconds);
        $middle = intdiv(count($seconds), 2);
        $median = count($seconds) % 2 === 1 ? $seconds[$middle] : ($seconds[$middle - 1] + $seconds[$middle]) / 2;

        return round($median / 3600, 1);
    }

    /**
     * One entry per upload that produced a match, the most waiting first.
     *
     * @return list<array<string, mixed>>
     */
    private function batches(Builder $rows): array
    {
        $closed = self::CLOSED;

        $grouped = (clone $rows)
            ->leftJoin('activities', 'import_batches.activity_id', '=', 'activities.id')
            ->leftJoin('mdas', 'import_batches.owner_mda_id', '=', 'mdas.id')
            ->selectRaw(
                'import_batches.id as id, import_batches.original_filename as file, import_batches.source as source, '
                .'import_batches.status as status, import_batches.created_at as uploaded_at, activities.name as activity, '
                .'mdas.name as mda, count(*) as matches, '
                .'sum(case when import_rows.match_band = ? then 1 else 0 end) as exact, '
                .'sum(case when import_rows.resolution is null then 1 else 0 end) as undecided',
                ['exact'],
            )
            ->groupBy(
                'import_batches.id', 'import_batches.original_filename', 'import_batches.source',
                'import_batches.status', 'import_batches.created_at', 'activities.name', 'mdas.name',
            )
            ->get();

        $out = [];
        foreach ($grouped as $batch) {
            $matches = (int) $batch->matches;
            $undecided = (int) $batch->undecided;
            $isClosed = in_array((string) $batch->status, $closed, true);

            $out[] = [
                'id' => (string) $batch->id,
                'file' => (string) $batch->file,
                'activity' => $batch->activity !== null ? (string) $batch->activity : null,
                'mda' => $batch->mda !== null ? (string) $batch->mda : null,
                'source' => (string) $batch->source,
                'status' => (string) $batch->status,
                'uploaded_at' => $batch->uploaded_at !== null ? Carbon::parse($batch->uploaded_at)->toIso8601String() : null,
                'matches' => $matches,
                'exact' => (int) $batch->exact,
                'probable' => $matches - (int) $batch->exact,
                'decided' => $matches - $undecided,
                'awaiting' => $isClosed ? 0 : $undecided,
                'closed_undecided' => $isClosed ? $undecided : 0,
            ];
        }

        usort($out, static fn (array $a, array $b): int => [$b['awaiting'], $b['uploaded_at']] <=> [$a['awaiting'], $a['uploaded_at']]);

        return $out;
    }

    private static function duration(?float $hours): string
    {
        return match (true) {
            $hours === null => 'No decisions yet',
            $hours < 1 => 'Under an hour',
            $hours < 48 => rtrim(rtrim(number_format($hours, 1), '0'), '.').' hours',
            default => rtrim(rtrim(number_format($hours / 24, 1), '0'), '.').' days',
        };
    }

    private static function sourceLabel(string $source): string
    {
        return RegistrationSource::tryFrom($source)?->label() ?? Str::headline($source);
    }
}
