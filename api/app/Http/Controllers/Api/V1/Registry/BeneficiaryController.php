<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Export\BeneficiaryListExport;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\BeneficiaryLookupService;
use App\Domain\Registry\Services\ConsentService;
use App\Domain\Registry\Services\FuzzyDuplicateFinder;
use App\Domain\Reporting\Export\ReportExporterRegistry;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\ReportService;
use App\Domain\Reporting\Support\DashboardScope;
use App\Domain\Sharing\DataSharingGuard;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\BeneficiaryLookupRequest;
use App\Http\Requests\Registry\BeneficiaryMatchSearchRequest;
use App\Http\Requests\Registry\ExportBeneficiariesRequest;
use App\Http\Requests\Registry\RecordConsentRequest;
use App\Http\Requests\Registry\UpdateBeneficiaryRequest;
use App\Http\Resources\BeneficiaryResource;
use App\Http\Resources\BeneficiaryRevealResource;
use App\Http\Resources\MatchCandidateResource;
use App\Http\Resources\ReportRunResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Owner-scoped beneficiary browse + owner-only correction (PRD FR-REG-04,
 * FR-OWN-02/03). Beneficiaries are NOT created here — ingestion is source-only
 * (bulk import + REST intake). List/show are owner-scoped by the model's global
 * MdaScope; edit/delete are owner-only via BeneficiaryPolicy; the lookup seam is
 * the cross-MDA serve path. Every mutation is audited.
 */
class BeneficiaryController extends Controller
{
    /**
     * List beneficiaries owned by (or visible to) the caller's MDA, with optional
     * search and LGA/Ward/status/source/batch filters (FR-REG-04). Search matches
     * name or the exact NIN/BVN; filters use the documented `filter[...]` params.
     * The filter/order logic is shared with {@see self::export()} so the export
     * always reflects exactly the same view.
     */
    public function index(Request $request, BeneficiaryListExport $export): JsonResponse
    {
        $this->authorize('viewAny', Beneficiary::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $query = $export->applyFilters(Beneficiary::query(), $export->filtersFromRequest($request));
        $page = $export->ordered($query)->paginate($perPage);

        return ApiResponse::paginated(BeneficiaryResource::collection($page->items())->resolve(), $page);
    }

    /**
     * Export the current filtered list to CSV/Excel (FR-REG-04 + FR-RPT-03). Reuses
     * the shared Phase 6 exporters — no bespoke CSV/Excel logic. The rows are exactly
     * what {@see self::index()} returns: same MDA scope (global MdaScope), same
     * filters/search. NIN/BVN are masked unless the caller holds the reveal
     * permission. Small exports stream immediately; large ones are queued and the
     * requester is notified when the file is ready. Every export is audited.
     */
    public function export(
        ExportBeneficiariesRequest $request,
        BeneficiaryListExport $export,
        ReportService $reports,
        DashboardScopeResolver $scopeResolver,
        ReportExporterRegistry $exporters,
        AuditLogger $audit,
    ): JsonResponse|StreamedResponse {
        $this->authorize('viewAny', Beneficiary::class);

        $format = $request->input('format') === 'excel' ? ReportFormat::Excel : ReportFormat::Csv;
        $reveal = $request->user()->hasPermission(BeneficiaryListExport::REVEAL_PERMISSION);
        $filters = $export->filtersFromRequest($request);
        $scope = $scopeResolver->forUser($request->user());

        $scoped = $export->applyFilters(Beneficiary::query(), $filters);
        $count = (clone $scoped)->count();

        // Large exports run on the queue and notify when ready (reuses the report
        // run pipeline: generation → ReportReady → download, all audited).
        if ($count > $export->syncMax()) {
            $run = $reports->queueBeneficiaryExport($request->user(), $filters, $format, $reveal);
            $audit->record('beneficiary.export_queued', $run, after: $this->exportAuditMeta($scope, $format, $filters, $reveal, $count));

            return ApiResponse::success((new ReportRunResource($run))->resolve(), status: 202);
        }

        $data = $export->toReportData($scoped, $reveal, $scope->label);
        $bytes = $exporters->for($format)->render($data);

        $audit->record('beneficiary.exported', after: $this->exportAuditMeta($scope, $format, $filters, $reveal, $data->rowCount()));

        $filename = 'beneficiaries-'.now()->format('Ymd-His').'.'.$format->extension();

        return response()->streamDownload(static function () use ($bytes): void {
            echo $bytes;
        }, $filename, ['Content-Type' => $format->mimeType()]);
    }

    /**
     * Audit metadata for an export (SECURITY.md — Export of beneficiary data): the
     * actor is recorded by the logger; here we capture the resolved SCOPE, the
     * filters, the format, the row count, and whether PII was revealed. Never the raw
     * search term (it can be an identifier) and never any beneficiary value.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    private function exportAuditMeta(DashboardScope $scope, ReportFormat $format, array $filters, bool $reveal, int $rowCount): array
    {
        return [
            'format' => $format->value,
            'revealed' => $reveal,
            'row_count' => $rowCount,
            'scope' => [
                'kind' => $scope->kind,
                'label' => $scope->label,
                'mda_ids' => $scope->mdaIds,
            ],
            'has_search' => trim((string) ($filters['search'] ?? '')) !== '',
            'filters' => array_filter([
                'lga' => $filters['lga'] ?? null,
                'ward' => $filters['ward'] ?? null,
                'status' => $filters['status'] ?? null,
                'source' => $filters['source'] ?? null,
                'batch' => $filters['batch'] ?? null,
            ], static fn ($v): bool => $v !== null && $v !== ''),
        ];
    }

    /**
     * Show a single beneficiary through the ONE governed data-sharing surface
     * (FR-DSH-01): owner, oversight, or a consent-satisfied Service-Request grant
     * (§12, FR-OWN-07). Any other cross-MDA read is denied — and, because it is a
     * deliberate out-of-scope attempt, logged — while still returning 404 so record
     * existence is never leaked.
     */
    public function show(Request $request, string $beneficiary, DataSharingGuard $sharing, AuditLogger $audit): JsonResponse
    {
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($beneficiary);

        abort_if($model === null, 404);

        if (! $sharing->canRead($request->user(), $model)) {
            $audit->record('beneficiary.access_denied', $model, after: [
                'requested_by_mda' => $request->user()->mda_id,
                'basis' => $sharing->basisFor($request->user(), $model)->value,
            ]);
            abort(404);
        }

        return ApiResponse::success(
            (new BeneficiaryResource($model->load('ownerMda', 'currentMembership')))->resolve(),
        );
    }

    /**
     * Record (grant/withdraw) the beneficiary's cross-MDA data-sharing consent
     * (NFR-PRV-01, FR-DSH-01) — owner MDA only. Updates the current status, appends
     * an immutable history entry, and audits the change. The consent gate then makes
     * any Service-Request grant effective or ineffective accordingly.
     */
    public function consent(RecordConsentRequest $request, string $beneficiary, ConsentService $consents): JsonResponse
    {
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);

        $this->authorize('update', $model); // owner MDA only (FR-OWN-02 semantics)

        $consents->record(
            $model,
            ConsentStatus::from((string) $request->input('status')),
            $request->user(),
            basis: $request->input('basis'),
            source: $request->input('source', 'owner_mda'),
            note: $request->input('note'),
        );

        return ApiResponse::success((new BeneficiaryResource($model->fresh()->load('ownerMda')))->resolve());
    }

    /**
     * Edit the core profile — owner MDA only (FR-OWN-02). The record is resolved
     * without the owner scope so a non-owner gets 403 (not 404): the policy is
     * the boundary, and non-owners can already see the record via the lookup seam.
     */
    public function update(UpdateBeneficiaryRequest $request, string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);

        $this->authorize('update', $model);

        $model->update($request->validated());

        return ApiResponse::success((new BeneficiaryResource($model->fresh()->load('ownerMda')))->resolve());
    }

    /** Soft-delete a beneficiary — owner MDA only, audited. */
    public function destroy(string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);

        $this->authorize('delete', $model);

        $model->delete();

        return ApiResponse::success(['message' => 'Beneficiary deleted.']);
    }

    /**
     * Cross-MDA lookup/serve path (FR-OWN-03): exact-identifier search returning
     * only the reveal fields. Route middleware enforces beneficiary-lookup.view.
     */
    public function lookup(BeneficiaryLookupRequest $request, BeneficiaryLookupService $lookup): JsonResponse
    {
        $matches = $lookup->findByIdentity(
            $request->input('nin'),
            $request->input('bvn'),
            $request->input('phone'),
        );

        return ApiResponse::success(['matches' => BeneficiaryRevealResource::collection($matches)->resolve()]);
    }

    /**
     * Fuzzy "serve many" search (FR-DUP-04): runs the SAME engine as import
     * screening against partial identity details and returns ranked candidates
     * (exact + probable) as reveal-only projections, across all MDAs. From a
     * result the caller can raise a Service Request (see ServiceRequestController).
     * Read-only and audited (identifiers used + hit count, never their values).
     */
    public function search(
        BeneficiaryMatchSearchRequest $request,
        FuzzyDuplicateFinder $finder,
        MatchingConfigService $configs,
        AuditLogger $audit,
    ): JsonResponse {
        $query = $request->canonicalQuery();
        $config = $configs->activeOrNull();

        // Matching not yet configured → nothing to rank against.
        $results = $config === null ? [] : $finder->find($query, $config);

        // Resolve the matched records reveal-only (bypass the owner scope, but only
        // ever expose reveal fields — the serve seam, same as import previews).
        $ids = array_values(array_filter(array_map(static fn (MatchResult $r) => $r->reference, $results)));
        $beneficiaries = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->with(['ownerMda' => fn ($q) => $q->withoutGlobalScope(MdaScope::class)->select('id', 'name')])
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $candidates = [];
        foreach ($results as $result) {
            $beneficiary = $beneficiaries->get($result->reference);
            if ($beneficiary !== null) {
                $candidates[] = ['result' => $result, 'beneficiary' => $beneficiary];
            }
        }

        $audit->record('beneficiary.match_search', null, after: [
            'by' => array_keys($query),
            'matches' => count($candidates),
        ]);

        return ApiResponse::success(['candidates' => MatchCandidateResource::collection($candidates)->resolve()]);
    }
}
