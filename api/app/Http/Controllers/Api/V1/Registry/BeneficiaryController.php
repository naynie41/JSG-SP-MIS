<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\BeneficiaryLookupService;
use App\Domain\Registry\Services\FuzzyDuplicateFinder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\BeneficiaryLookupRequest;
use App\Http\Requests\Registry\BeneficiaryMatchSearchRequest;
use App\Http\Requests\Registry\UpdateBeneficiaryRequest;
use App\Http\Resources\BeneficiaryResource;
use App\Http\Resources\BeneficiaryRevealResource;
use App\Http\Resources\MatchCandidateResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * search and LGA/Ward/status filters (FR-REG-04). Search matches name or the
     * exact NIN/BVN; filters use the documented `filter[...]` params.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Beneficiary::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $search = trim((string) $request->input('search', ''));
        $lga = $request->input('filter.lga');
        $ward = $request->input('filter.ward');
        $status = $request->input('filter.status');

        $page = Beneficiary::query()
            ->when($search !== '', function ($query) use ($search): void {
                $digits = Beneficiary::normalizeDigits($search);
                $query->where(function ($q) use ($search, $digits): void {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                    if ($digits !== null) {
                        $q->orWhere('nin', $digits)->orWhere('bvn', $digits);
                    }
                });
            })
            ->when(is_string($lga) && $lga !== '', fn ($query) => $query->where('lga', $lga))
            ->when(is_string($ward) && $ward !== '', fn ($query) => $query->where('ward', $ward))
            ->when(is_string($status) && $status !== '', fn ($query) => $query->where('status', $status))
            ->latest('registration_date')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(BeneficiaryResource::collection($page->items())->resolve(), $page);
    }

    /** Show a single beneficiary. Out-of-scope records 404 via the global scope. */
    public function show(string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->findOrFail($beneficiary);

        $this->authorize('view', $model);

        return ApiResponse::success(
            (new BeneficiaryResource($model->load('ownerMda', 'currentMembership')))->resolve(),
        );
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
     * result the caller can raise a request-to-serve (see ServeRequestController).
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
