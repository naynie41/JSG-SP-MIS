<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Registry\Services\MatchRevealAssembler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\DuplicateQueueRequest;
use App\Http\Resources\ImportRowResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

/**
 * The duplicate queue: every flagged row across this MDA's imports, paginated
 * (FR-DUP-01/05).
 *
 * This exists because the console previously assembled the queue in the browser — fetch
 * page one of BATCHES, then one detail request per batch, then flatten. Three
 * consequences, all silent: only the first page of batches was reachable at all, the
 * page blocked until every fan-out request resolved, and a failed batch request was
 * skipped so the list was quietly incomplete. A module for clearing a backlog could not
 * see the backlog.
 *
 * Paginating ROWS instead of batches answers the question the officer is actually
 * asking. Scope comes from the global {@see MdaScope} on
 * `import_batches` via the join — this endpoint adds no authorization surface of its own.
 */
class DuplicateQueueController extends Controller
{
    private const PER_PAGE = 25;

    private const MAX_PER_PAGE = 100;

    public function __construct(private readonly MatchRevealAssembler $reveals) {}

    /**
     * Outstanding and total flagged rows per band, across everything in scope.
     *
     * Two aggregates rather than a page of rows: the tab needs a number, not a list.
     *
     * @return array<string, array<string, int>>
     */
    private function counts(): array
    {
        $rows = ImportRow::query()
            ->toBase()
            ->whereIn('import_batch_id', ImportBatch::query()->select('id'))
            ->leftJoin('import_batches', 'import_batches.id', '=', 'import_rows.import_batch_id')
            ->whereIn('match_band', ['exact', 'probable'])
            ->selectRaw(
                'match_band, count(*) as total, '.
                'sum(case when resolution is null and import_batches.status = ? then 1 else 0 end) as awaiting',
                [ImportStatus::PreviewReady->value],
            )
            ->groupBy('match_band')
            ->get();

        $counts = [
            'exact' => ['awaiting' => 0, 'total' => 0],
            'probable' => ['awaiting' => 0, 'total' => 0],
        ];

        foreach ($rows as $row) {
            $counts[(string) $row->match_band] = [
                'awaiting' => (int) $row->awaiting,
                'total' => (int) $row->total,
            ];
        }

        return $counts;
    }

    /** @return array<string, float|null>|null */
    private function activeThresholds(): ?array
    {
        $config = MatchingConfig::query()->where('is_active', true)->first();

        return $config === null ? null : [
            'review' => (float) $config->review_threshold,
            'auto_accept' => $config->auto_accept_threshold === null ? null : (float) $config->auto_accept_threshold,
        ];
    }

    public function index(DuplicateQueueRequest $request): JsonResponse
    {
        $band = $request->string('band')->toString();
        $state = $request->string('state', 'awaiting')->toString();
        $perPage = min((int) $request->integer('per_page', self::PER_PAGE), self::MAX_PER_PAGE);

        $query = ImportRow::query()
            // The MDA-scoped side of the join. `whereHas` keeps the global scope on
            // ImportBatch in play, so a row is visible exactly when its batch is.
            ->whereHas('batch')
            ->whereIn('match_band', $band === '' ? ['exact', 'probable'] : [$band]);

        // "Awaiting" is the working queue; "decided" is the record of what was done;
        // "all" is both, for an officer reconciling the two.
        if ($state === 'decided') {
            $query->whereNotNull('resolution');
        } elseif ($state !== 'all') {
            // Outstanding work means work that can still be DONE. A committed batch no
            // longer accepts a resolution (the confirm endpoint refuses it), so its
            // undecided rows are history, not a queue — counting them would send an
            // officer looking for a decision the server would reject.
            $query->whereNull('resolution')
                ->whereHas('batch', fn ($batch) => $batch->where('status', ImportStatus::PreviewReady->value));
        }

        $page = $query
            ->orderByDesc('created_at')
            ->orderBy('row_number')
            ->paginate($perPage);

        /** @var Collection<int, ImportRow> $rows */
        $rows = collect($page->items());

        // One query for the batches these rows belong to, then one for every matched
        // record across the whole page — not one per row.
        $batches = ImportBatch::query()
            ->whereIn('id', $rows->pluck('import_batch_id')->unique()->all())
            ->get()
            ->keyBy('id');

        $this->reveals->attach($rows, $batches);

        $thresholds = $this->activeThresholds();

        return ApiResponse::success([
            // Scope-wide, not page-wide: a tab label counts the work outstanding across
            // every import, which is the number the officer is deciding whether to open.
            'counts' => $this->counts(),
            'items' => $rows->map(function (ImportRow $row) use ($batches, $thresholds): array {
                $batch = $batches->get($row->import_batch_id);

                return [
                    ...(new ImportRowResource($row))->resolve(),
                    // The queue spans files, so each row has to say which one it came
                    // from — on a single batch page that context is the page itself.
                    'batch' => [
                        'id' => $row->import_batch_id,
                        'original_filename' => $batch?->original_filename,
                        'status' => $batch?->status->value,
                        // The match-strength band reads these; without them a row in the
                        // queue could not show how strong its match was.
                        'matching_thresholds' => $thresholds,
                    ],
                ];
            })->all(),
            'pagination' => [
                'page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'total_pages' => $page->lastPage(),
            ],
        ]);
    }
}
