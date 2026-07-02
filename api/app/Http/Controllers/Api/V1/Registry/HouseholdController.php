<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Services\HouseholdMembershipService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\DesignateHeadRequest;
use App\Http\Requests\Registry\StoreHouseholdRequest;
use App\Http\Requests\Registry\UpdateHouseholdRequest;
use App\Http\Resources\HouseholdResource;
use App\Support\ApiResponse;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Household registration + owner-scoped CRUD (PRD FR-REG-01 household path, §9).
 * List/show are owner-scoped by the model's global MdaScope; edit/delete/head are
 * owner-only via HouseholdPolicy; every mutation is audited.
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

        return ApiResponse::success(
            HouseholdResource::collection($page->items())->resolve(),
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
     * Create a household owned by the caller's MDA (FR-OWN-01), stamped as a manual
     * registration (FR-REG-03), optionally with initial members and a head. The
     * whole operation is transactional so a bad member rolls the household back.
     */
    public function store(StoreHouseholdRequest $request, HouseholdMembershipService $memberships): JsonResponse
    {
        $this->authorize('create', Household::class);

        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can create households.', [], 422);
        }

        $data = $request->validated();

        try {
            $household = DB::transaction(function () use ($data, $mdaId, $memberships): Household {
                $household = Household::create([
                    'owner_mda_id' => $mdaId,
                    'registration_source' => RegistrationSource::Manual,
                    'address' => $data['address'] ?? null,
                    'lga' => $data['lga'],
                    'ward' => $data['ward'],
                ]);

                foreach ($data['members'] ?? [] as $member) {
                    // Scoped resolution ensures members belong to the caller's MDA.
                    $beneficiary = Beneficiary::query()->findOrFail($member['beneficiary_id']);
                    $memberships->add($household, $beneficiary, HouseholdRole::from($member['role_in_household']));
                }

                if (! empty($data['head_beneficiary_id'])) {
                    $head = Beneficiary::query()->findOrFail($data['head_beneficiary_id']);
                    $memberships->designateHead($household, $head);
                }

                return $household;
            });
        } catch (DomainException $e) {
            return ApiResponse::error('HOUSEHOLD_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success(
            (new HouseholdResource($household->fresh()->load('currentMemberships.beneficiary')))->resolve(),
            status: 201,
        );
    }

    public function show(string $household): JsonResponse
    {
        $model = Household::query()->findOrFail($household);

        $this->authorize('view', $model);

        return ApiResponse::success((new HouseholdResource($model->load('currentMemberships.beneficiary')))->resolve());
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
