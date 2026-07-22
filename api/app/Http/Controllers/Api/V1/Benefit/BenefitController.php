<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Benefit;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Exceptions\DeliveryNotAuthorizedException;
use App\Domain\Benefit\Exceptions\NotEnrolledException;
use App\Domain\Benefit\Exceptions\ProcessingConsentRequiredException;
use App\Domain\Benefit\Exceptions\VerificationUnavailableException;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\BenefitRecorder;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Benefit\AggregateBenefitsRequest;
use App\Http\Requests\Benefit\RecordBenefitRequest;
use App\Http\Requests\Benefit\VerifyBenefitRequest;
use App\Http\Resources\BenefitResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The benefit ledger (PRD FR-BEN-01/02/04, §8.3). Records delivery only — it never
 * moves money (§2.3). List/show are scoped to the delivering MDA; the per-
 * beneficiary ledger reads across all MDAs for the beneficiary's owner, a
 * delivering MDA, or oversight.
 */
class BenefitController extends Controller
{
    public function __construct(private readonly BenefitRecorder $recorder) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Benefit::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $page = Benefit::query()
            ->when($request->filled('filter.programme_id'), fn ($q) => $q->where('programme_id', $request->input('filter.programme_id')))
            ->when($request->filled('filter.beneficiary_id'), fn ($q) => $q->where('beneficiary_id', $request->input('filter.beneficiary_id')))
            ->when($request->filled('filter.status'), fn ($q) => $q->where('status', $request->input('filter.status')))
            ->when($request->filled('filter.benefit_type'), fn ($q) => $q->where('benefit_type', $request->input('filter.benefit_type')))
            ->latest('delivery_date')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(BenefitResource::collection($page->items())->resolve(), $page);
    }

    /**
     * Aggregate the ledger by programme / activity / MDA / LGA / ward / beneficiary
     * / benefit type (FR-BEN-03). MDA-scoped to the caller; oversight sees all.
     */
    public function aggregate(AggregateBenefitsRequest $request, LedgerAggregator $aggregator): JsonResponse
    {
        $this->authorize('viewAny', Benefit::class);

        return ApiResponse::success($aggregator->aggregate((string) $request->input('group_by'), $request->validated()));
    }

    /** Record a benefit delivery against an enrolled beneficiary (§8.3). */
    public function store(RecordBenefitRequest $request): JsonResponse
    {
        $programme = Programme::query()->withoutGlobalScope(MdaScope::class)->findOrFail($request->input('programme_id'));
        $this->authorize('record', [Benefit::class, $programme]);

        if (($badActivity = $this->activityMismatch($programme, $request->input('activity_id'))) !== null) {
            return $badActivity;
        }

        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($request->input('beneficiary_id'));

        try {
            $benefit = $this->recorder->record($beneficiary, $programme, $request->input('activity_id'), $request->user(), [
                ...$request->safe()->except(['beneficiary_id', 'programme_id', 'activity_id']),
                'verification_method' => $request->filled('verification_method') ? $request->enum('verification_method', VerificationMethod::class) : VerificationMethod::None,
            ]);
        } catch (DeliveryNotAuthorizedException $e) {
            return ApiResponse::error('DELIVERY_NOT_AUTHORIZED', $e->getMessage(), [], 409);
        } catch (NotEnrolledException $e) {
            return ApiResponse::error('NOT_ENROLLED', $e->getMessage(), [], 422);
        } catch (ProcessingConsentRequiredException $e) {
            return ApiResponse::error('CONSENT_REQUIRED', $e->getMessage(), [], 422);
        } catch (VerificationUnavailableException $e) {
            return ApiResponse::error('VERIFICATION_UNAVAILABLE', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new BenefitResource($benefit))->resolve(), status: 201);
    }

    public function show(string $benefit): JsonResponse
    {
        $model = Benefit::query()->withoutGlobalScope(MdaScope::class)->findOrFail($benefit);
        $this->authorize('view', $model);

        return ApiResponse::success((new BenefitResource($model))->resolve());
    }

    /** Verify a recorded delivery (FR-BEN-04). Stubbed methods report unavailable. */
    public function verify(VerifyBenefitRequest $request, string $benefit): JsonResponse
    {
        $model = Benefit::query()->withoutGlobalScope(MdaScope::class)->findOrFail($benefit);
        $this->authorize('verify', $model);

        try {
            $verified = $this->recorder->verify(
                $model,
                $request->enum('verification_method', VerificationMethod::class),
                $request->input('verification_reference'),
                $request->user(),
            );
        } catch (VerificationUnavailableException $e) {
            return ApiResponse::error('VERIFICATION_UNAVAILABLE', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new BenefitResource($verified))->resolve());
    }

    /**
     * The beneficiary's complete benefit history across all MDAs (FR-BEN-01/03),
     * visible to the owner MDA, a delivering MDA, or oversight.
     */
    public function ledger(Request $request, string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);

        if (! $this->canViewLedger($request->user(), $model)) {
            return ApiResponse::error('FORBIDDEN', 'You may not view this beneficiary\'s benefit ledger.', [], 403);
        }

        $perPage = min(max($request->integer('per_page', 50), 1), 100);
        $page = Benefit::query()
            ->withoutGlobalScope(MdaScope::class) // complete history, all delivering MDAs
            ->where('beneficiary_id', $model->id)
            ->latest('delivery_date')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(BenefitResource::collection($page->items())->resolve(), $page);
    }

    private function canViewLedger(User $user, Beneficiary $beneficiary): bool
    {
        if ($user->canAccessAllMdas() || ($user->mda_id !== null && $user->mda_id === $beneficiary->owner_mda_id)) {
            return true;
        }

        return $user->mda_id !== null && Benefit::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('beneficiary_id', $beneficiary->id)
            ->where('mda_id', $user->mda_id)
            ->exists();
    }

    private function activityMismatch(Programme $programme, ?string $activityId): ?JsonResponse
    {
        if ($activityId === null) {
            return null;
        }
        $belongs = Activity::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('id', $activityId)
            ->where('programme_id', $programme->id)
            ->exists();

        return $belongs ? null : ApiResponse::error('ACTIVITY_MISMATCH', 'The activity does not belong to this programme.', [], 422);
    }
}
