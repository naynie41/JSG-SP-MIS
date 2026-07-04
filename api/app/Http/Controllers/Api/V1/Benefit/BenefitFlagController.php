<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Benefit;

use App\Domain\Benefit\Enums\BenefitFlagStatus;
use App\Domain\Benefit\Models\BenefitFlag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Benefit\ReviewBenefitFlagRequest;
use App\Http\Resources\BenefitFlagResource;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Double-dipping flags (PRD FR-BEN-05): surfaced for review; never block delivery.
 * Listed for the MDAs involved (or oversight); reviewed to confirm/dismiss.
 */
class BenefitFlagController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', BenefitFlag::class);
        $user = $request->user();

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $page = BenefitFlag::query()
            ->when(! $user->canAccessAllMdas(), fn (Builder $q) => $q->where(
                fn (Builder $inner) => $inner->where('from_mda_id', $user->mda_id)->orWhere('other_mda_id', $user->mda_id),
            ))
            ->when($request->filled('filter.status'), fn (Builder $q) => $q->where('status', $request->input('filter.status')))
            ->when($request->filled('filter.beneficiary_id'), fn (Builder $q) => $q->where('beneficiary_id', $request->input('filter.beneficiary_id')))
            ->latest('created_at')
            ->paginate($perPage);

        return ApiResponse::paginated(BenefitFlagResource::collection($page->items())->resolve(), $page);
    }

    public function review(ReviewBenefitFlagRequest $request, string $flag): JsonResponse
    {
        $model = BenefitFlag::query()->findOrFail($flag);
        $this->authorize('review', $model);

        $model->update([
            'status' => BenefitFlagStatus::from($request->string('status')->value()),
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
        ]);

        return ApiResponse::success((new BenefitFlagResource($model->fresh()))->resolve());
    }
}
