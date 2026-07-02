<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Services\HouseholdMembershipService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\DesignateHeadRequest;
use App\Http\Requests\Registry\UpdateHouseholdRequest;
use App\Http\Resources\HouseholdResource;
use App\Support\ApiResponse;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Owner-scoped household browse + owner-only correction (PRD §9). Households are
 * NOT created here — they are formed by source ingestion (see
 * HouseholdIngestionService). List/show are owner-scoped by the model's global
 * MdaScope; edit/delete/head + membership changes are owner-only via
 * HouseholdPolicy; every mutation is audited.
 */
class HouseholdController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Household::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $page = Household::query()
            ->with('currentMemberships')
            ->latest('registration_date')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(HouseholdResource::collection($page->items())->resolve(), $page);
    }

    public function show(string $household): JsonResponse
    {
        $model = Household::query()->findOrFail($household);

        $this->authorize('view', $model);

        $model->load([
            'currentMemberships.beneficiary',
            'memberships' => fn ($query) => $query->latest('joined_at'),
            'memberships.beneficiary',
        ]);

        return ApiResponse::success((new HouseholdResource($model))->resolve());
    }

    /** Owner-only edit of core fields (resolved without scope → 403, not 404). */
    public function update(UpdateHouseholdRequest $request, string $household): JsonResponse
    {
        $model = Household::query()->withoutGlobalScope(MdaScope::class)->findOrFail($household);

        $this->authorize('update', $model);

        $model->update($request->validated());

        return ApiResponse::success(
            (new HouseholdResource($model->fresh()->load('currentMemberships.beneficiary')))->resolve(),
        );
    }

    /** Owner-only soft delete (memberships are retained as history). */
    public function destroy(string $household): JsonResponse
    {
        $model = Household::query()->withoutGlobalScope(MdaScope::class)->findOrFail($household);

        $this->authorize('delete', $model);

        $model->delete();

        return ApiResponse::success(['message' => 'Household deleted.']);
    }

    /** Designate/replace the head (must be a current member). Owner-only. */
    public function designateHead(DesignateHeadRequest $request, string $household, HouseholdMembershipService $memberships): JsonResponse
    {
        $model = Household::query()->withoutGlobalScope(MdaScope::class)->findOrFail($household);

        $this->authorize('update', $model);

        $beneficiary = Beneficiary::query()->findOrFail($request->input('beneficiary_id'));

        try {
            $memberships->designateHead($model, $beneficiary);
        } catch (DomainException $e) {
            return ApiResponse::error('HOUSEHOLD_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success(
            (new HouseholdResource($model->fresh()->load('currentMemberships.beneficiary')))->resolve(),
        );
    }
}
