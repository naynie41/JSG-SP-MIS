<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Benefit;

use App\Domain\Benefit\Models\DoubleDippingRule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Benefit\StoreDoubleDippingRuleRequest;
use App\Http\Resources\DoubleDippingRuleResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Admin management of double-dipping rules (PRD FR-BEN-05). System-wide config,
 * gated by `double-dipping.view`/`.edit`; every change is audited.
 */
class DoubleDippingRuleController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', DoubleDippingRule::class);

        $rules = DoubleDippingRule::query()->latest('created_at')->get();

        return ApiResponse::success(['rules' => DoubleDippingRuleResource::collection($rules)->resolve()]);
    }

    public function store(StoreDoubleDippingRuleRequest $request): JsonResponse
    {
        $this->authorize('create', DoubleDippingRule::class);

        $rule = DoubleDippingRule::create([...$request->validated(), 'created_by' => $request->user()->id]);

        return ApiResponse::success((new DoubleDippingRuleResource($rule))->resolve(), status: 201);
    }

    public function update(StoreDoubleDippingRuleRequest $request, string $rule): JsonResponse
    {
        $this->authorize('update', DoubleDippingRule::class);

        $model = DoubleDippingRule::query()->findOrFail($rule);
        $model->update($request->validated());

        return ApiResponse::success((new DoubleDippingRuleResource($model->fresh()))->resolve());
    }

    public function destroy(string $rule): JsonResponse
    {
        $this->authorize('delete', DoubleDippingRule::class);

        DoubleDippingRule::query()->findOrFail($rule)->delete();

        return ApiResponse::success(['message' => 'Rule deleted.']);
    }
}
