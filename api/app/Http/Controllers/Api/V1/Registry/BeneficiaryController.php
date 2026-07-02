<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Contracts\DuplicateChecker;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\BeneficiaryLookupService;
use App\Domain\Registry\Services\BeneficiaryRegistrar;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\BeneficiaryLookupRequest;
use App\Http\Requests\Registry\StoreBeneficiaryRequest;
use App\Http\Requests\Registry\UpdateBeneficiaryRequest;
use App\Http\Resources\BeneficiaryResource;
use App\Http\Resources\BeneficiaryRevealResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Manual individual registration + owner-scoped CRUD (PRD FR-REG-01/04/05,
 * FR-OWN-01/02). List/show are owner-scoped by the model's global MdaScope;
 * edit/delete are owner-only via BeneficiaryPolicy; every mutation is audited.
 */
class BeneficiaryController extends Controller
{
    /** List beneficiaries owned by (or visible to) the caller's MDA. */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Beneficiary::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $page = Beneficiary::query()
            ->latest('registration_date')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::success(
            BeneficiaryResource::collection($page->items())->resolve(),
            [
                'pagination' => [
                    'current_page' => $page->currentPage(),
                    'per_page' => $page->perPage(),
                    'total' => $page->total(),
                    'last_page' => $page->lastPage(),
                ],
            ],
        );
    }

    /**
     * Register an individual (FR-REG-01). The record is owned by the caller's MDA
     * (FR-OWN-01) and stamped as a manual registration (FR-REG-03). An optional
     * client-supplied idempotency_key makes the intake safe to retry (FR-REG-08):
     * a repeat with the same key returns the existing record (200) not a duplicate
     * (201). The pre-save duplicate check (Phase 3) plugs in via DuplicateChecker.
     */
    public function store(StoreBeneficiaryRequest $request, DuplicateChecker $duplicates, BeneficiaryRegistrar $registrar): JsonResponse
    {
        $this->authorize('create', Beneficiary::class);

        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can register beneficiaries.', [], 422);
        }

        $data = $request->validated();

        // EXTENSION POINT (Phase 3): fuzzy duplicate check runs here before save.
        $duplicates->check($data, $mdaId);

        $beneficiary = $registrar->register(
            Arr::except($data, ['idempotency_key', 'original_record_id']),
            $mdaId,
            RegistrationSource::Manual,
            $data['original_record_id'] ?? null,
            null,
            $data['idempotency_key'] ?? null,
        );

        return ApiResponse::success(
            (new BeneficiaryResource($beneficiary->load('ownerMda')))->resolve(),
            status: $beneficiary->wasRecentlyCreated ? 201 : 200,
        );
    }

    /** Show a single beneficiary. Out-of-scope records 404 via the global scope. */
    public function show(string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->findOrFail($beneficiary);

        $this->authorize('view', $model);

        return ApiResponse::success((new BeneficiaryResource($model->load('ownerMda')))->resolve());
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
}
