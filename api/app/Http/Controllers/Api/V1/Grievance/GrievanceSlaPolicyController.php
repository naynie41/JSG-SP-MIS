<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Grievance;

use App\Domain\Grievance\Enums\GrievanceCategory;
use App\Domain\Grievance\Models\GrievanceSlaPolicy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Grievance\UpdateGrievanceSlaRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Admin management of grievance SLA windows (PRD FR-GRM-03). Global config — gated by
 * `grievance-sla.edit`. One window (hours) per grievance category; edits are audited
 * via the model's Auditable trait.
 */
class GrievanceSlaPolicyController extends Controller
{
    public function index(): JsonResponse
    {
        $policies = GrievanceSlaPolicy::query()->orderBy('category')->get()
            ->map(fn (GrievanceSlaPolicy $p): array => ['category' => $p->category, 'hours' => $p->hours])
            ->all();

        return ApiResponse::success(['sla_policies' => $policies]);
    }

    /** Upsert the SLA window (hours) for a category. */
    public function update(UpdateGrievanceSlaRequest $request, string $category): JsonResponse
    {
        $categoryEnum = GrievanceCategory::tryFrom($category);
        if ($categoryEnum === null) {
            return ApiResponse::error('INVALID_CATEGORY', 'Unknown grievance category.', [], 422);
        }

        $policy = GrievanceSlaPolicy::query()->updateOrCreate(
            ['category' => $categoryEnum->value],
            ['hours' => $request->integer('hours')],
        );

        return ApiResponse::success(['category' => $policy->category, 'hours' => $policy->hours]);
    }
}
