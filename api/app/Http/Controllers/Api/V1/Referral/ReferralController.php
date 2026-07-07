<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Referral;

use App\Domain\Referral\Exceptions\InvalidReferralTransitionException;
use App\Domain\Referral\Models\Referral;
use App\Domain\Referral\Services\ReferralService;
use App\Domain\Registry\Models\Beneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referral\StoreReferralRequest;
use App\Http\Requests\Referral\TransitionReferralRequest;
use App\Http\Resources\ReferralResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Referral endpoints (PRD FR-REF-01/02/04, §8.2). List/show are two-party scoped
 * (both MDAs see the referral). Create is the originating MDA; the receiving MDA
 * drives accept/reject/request-info + the working states; the originating MDA
 * responds to a more-info request. Invalid transitions are refused (422).
 */
class ReferralController extends Controller
{
    public function __construct(private readonly ReferralService $referrals) {}

    /** Referrals involving the caller's MDA (`filter[direction]`, `filter[status]`). */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Referral::class);

        $mdaId = $request->user()->mda_id;
        $direction = $request->input('filter.direction');
        $status = $request->input('filter.status');
        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $page = Referral::query()
            ->when($direction === 'incoming', fn ($q) => $q->where('to_mda_id', $mdaId))
            ->when($direction === 'outgoing', fn ($q) => $q->where('from_mda_id', $mdaId))
            ->when(is_string($status) && $status !== '', fn ($q) => $q->where('status', $status))
            ->latest('created_at')
            ->paginate($perPage);

        return ApiResponse::paginated(ReferralResource::collection($page->items())->resolve(), $page);
    }

    public function show(string $referral): JsonResponse
    {
        $model = Referral::query()->findOrFail($referral);
        $this->authorize('view', $model);

        return ApiResponse::success($this->withLedger($model));
    }

    /** Create a referral to another MDA (originating MDA). */
    public function store(StoreReferralRequest $request): JsonResponse
    {
        $this->authorize('create', Referral::class);

        // The originating MDA must be able to see the beneficiary it refers.
        $beneficiary = Beneficiary::query()->findOrFail($request->input('beneficiary_id'));

        try {
            $referral = $this->referrals->create(
                $beneficiary,
                (string) $request->input('to_mda_id'),
                $request->user(),
                (string) $request->input('need'),
                $request->input('notes'),
            );
        } catch (InvalidReferralTransitionException $e) {
            return ApiResponse::error('REFERRAL_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ReferralResource($referral))->resolve(), status: 201);
    }

    public function accept(TransitionReferralRequest $request, string $referral): JsonResponse
    {
        return $this->receiving($request, $referral, fn (Referral $r) => $this->referrals->accept($r, $request->user()));
    }

    public function reject(TransitionReferralRequest $request, string $referral): JsonResponse
    {
        return $this->receiving($request, $referral, fn (Referral $r) => $this->referrals->reject($r, $request->user(), (string) $request->input('reason')));
    }

    public function requestInfo(TransitionReferralRequest $request, string $referral): JsonResponse
    {
        return $this->receiving($request, $referral, fn (Referral $r) => $this->referrals->requestInfo($r, $request->user(), $request->input('note')));
    }

    public function start(TransitionReferralRequest $request, string $referral): JsonResponse
    {
        return $this->receiving($request, $referral, fn (Referral $r) => $this->referrals->start($r, $request->user()));
    }

    public function complete(TransitionReferralRequest $request, string $referral): JsonResponse
    {
        $model = Referral::query()->findOrFail($referral);
        $this->authorize('receive', $model);

        try {
            $this->referrals->complete($model, $request->user(), $request->input('outcome'));
        } catch (InvalidReferralTransitionException $e) {
            return ApiResponse::error('INVALID_TRANSITION', $e->getMessage(), [], 422);
        }

        // The completed referral reconciles with the ledger (FR-REF-03).
        return ApiResponse::success($this->withLedger($model->fresh()));
    }

    public function close(TransitionReferralRequest $request, string $referral): JsonResponse
    {
        return $this->receiving($request, $referral, fn (Referral $r) => $this->referrals->close($r, $request->user(), $request->input('outcome')));
    }

    public function respondInfo(TransitionReferralRequest $request, string $referral): JsonResponse
    {
        $model = Referral::query()->findOrFail($referral);
        $this->authorize('originate', $model);

        return $this->run(fn () => $this->referrals->respondInfo($model, $request->user(), $request->input('note')));
    }

    /**
     * Resolve + authorize a receiving-MDA transition, then run it.
     *
     * @param  callable(Referral): Referral  $action
     */
    private function receiving(TransitionReferralRequest $request, string $referral, callable $action): JsonResponse
    {
        $model = Referral::query()->findOrFail($referral);
        $this->authorize('receive', $model);

        return $this->run(fn () => $action($model));
    }

    /**
     * The referral plus its ledger reconciliation (FR-REF-03).
     *
     * @return array<string, mixed>
     */
    private function withLedger(Referral $referral): array
    {
        return [
            ...(new ReferralResource($referral))->resolve(),
            'ledger' => $this->referrals->reconciliation($referral),
        ];
    }

    /**
     * @param  callable(): Referral  $action
     */
    private function run(callable $action): JsonResponse
    {
        try {
            $referral = $action();
        } catch (InvalidReferralTransitionException $e) {
            return ApiResponse::error('INVALID_TRANSITION', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ReferralResource($referral->fresh()))->resolve());
    }
}
