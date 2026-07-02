<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Services\HouseholdMembershipService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\AddHouseholdMemberRequest;
use App\Http\Requests\Registry\MoveHouseholdMemberRequest;
use App\Http\Resources\HouseholdMembershipResource;
use App\Support\ApiResponse;
use DomainException;
use Illuminate\Http\JsonResponse;

/**
 * Household membership lifecycle endpoints (PRD FR-REG-01, §9). Every action is
 * an owner-only household edit; the single-open-membership rule and history
 * retention are enforced in HouseholdMembershipService.
 */
class HouseholdMemberController extends Controller
{
    public function __construct(private readonly HouseholdMembershipService $memberships) {}

    /** Add a beneficiary to the household as a new open membership. */
    public function store(AddHouseholdMemberRequest $request, string $household): JsonResponse
    {
        $householdModel = $this->authorizedHousehold($household);
        $beneficiary = Beneficiary::query()->findOrFail($request->input('beneficiary_id'));

        try {
            $membership = $this->memberships->add(
                $householdModel,
                $beneficiary,
                HouseholdRole::from($request->string('role_in_household')->value()),
            );
        } catch (DomainException $e) {
            return ApiResponse::error('MEMBERSHIP_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new HouseholdMembershipResource($membership))->resolve(), status: 201);
    }

    /** Move a beneficiary into this household, closing their current membership. */
    public function move(MoveHouseholdMemberRequest $request, string $household): JsonResponse
    {
        $householdModel = $this->authorizedHousehold($household);
        $beneficiary = Beneficiary::query()->findOrFail($request->input('beneficiary_id'));

        $role = $request->filled('role_in_household')
            ? HouseholdRole::from($request->string('role_in_household')->value())
            : HouseholdRole::Other;

        try {
            $membership = $this->memberships->move($householdModel, $beneficiary, $role);
        } catch (DomainException $e) {
            return ApiResponse::error('MEMBERSHIP_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new HouseholdMembershipResource($membership))->resolve(), status: 201);
    }

    /** Remove a beneficiary from the household (closes their open membership). */
    public function destroy(string $household, string $beneficiary): JsonResponse
    {
        $householdModel = $this->authorizedHousehold($household);
        $beneficiaryModel = Beneficiary::query()->findOrFail($beneficiary);

        try {
            $this->memberships->remove($householdModel, $beneficiaryModel);
        } catch (DomainException $e) {
            return ApiResponse::error('MEMBERSHIP_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success(['message' => 'Member removed from household.']);
    }

    /**
     * Resolve the household without the owner scope and enforce owner-only edit
     * (403 for a non-owner, not 404).
     */
    private function authorizedHousehold(string $id): Household
    {
        $household = Household::query()->withoutGlobalScope(MdaScope::class)->findOrFail($id);
        $this->authorize('update', $household);

        return $household;
    }
}
