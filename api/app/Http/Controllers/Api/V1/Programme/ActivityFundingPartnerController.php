<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * The social protection partners an activity can be linked to: the active Development
 * Partner accounts, by name.
 *
 * A partner account belongs to no MDA, so the MDA scope would hide every partner from
 * the officer choosing one. The scope is lifted for this one read, and it is bounded
 * three ways: only the Development Partner role, only active accounts, and only an id
 * and a name — never an email or anything else about the account.
 */
class ActivityFundingPartnerController extends Controller
{
    public function index(): JsonResponse
    {
        $partners = User::query()
            ->withoutGlobalScope(MdaScope::class)
            ->whereHas('role', fn ($q) => $q->where('key', RoleKey::DevelopmentPartner->value))
            ->where('status', UserStatus::Active->value)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (User $partner): array => ['id' => $partner->id, 'name' => $partner->name])
            ->all();

        return ApiResponse::success(['partners' => $partners]);
    }
}
