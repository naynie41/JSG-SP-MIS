<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Models\ReportSchedule;
use App\Domain\Reporting\Services\ReportScheduleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reporting\StoreReportScheduleRequest;
use App\Http\Requests\Reporting\UpdateReportScheduleRequest;
use App\Http\Resources\ReportScheduleResource;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Manage scheduled reports (PRD FR-RPT-04): create / list / edit / pause / delete. The
 * owner's scope is captured and recipients validated to cover it, so a schedule can
 * never deliver out-of-scope data. Personal to owner; every change is audited.
 */
class ReportScheduleController extends Controller
{
    public function __construct(private readonly ReportScheduleService $schedules) {}

    public function index(Request $request): JsonResponse
    {
        $schedules = $this->mine($request)->latest('created_at')->get();

        return ApiResponse::success(['schedules' => ReportScheduleResource::collection($schedules)->resolve()]);
    }

    public function store(StoreReportScheduleRequest $request): JsonResponse
    {
        try {
            $schedule = $this->schedules->create($request->user(), $request->validated());
        } catch (RuntimeException $e) {
            return ApiResponse::error('SCHEDULE_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ReportScheduleResource($schedule))->resolve(), status: 201);
    }

    public function show(Request $request, string $schedule): JsonResponse
    {
        $model = $this->mine($request)->findOrFail($schedule);

        return ApiResponse::success((new ReportScheduleResource($model))->resolve());
    }

    public function update(UpdateReportScheduleRequest $request, string $schedule): JsonResponse
    {
        $model = $this->mine($request)->findOrFail($schedule);

        try {
            $model = $this->schedules->update($model, $request->validated(), $request->user());
        } catch (RuntimeException $e) {
            return ApiResponse::error('SCHEDULE_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ReportScheduleResource($model))->resolve());
    }

    public function destroy(Request $request, string $schedule): JsonResponse
    {
        $model = $this->mine($request)->findOrFail($schedule);
        $this->schedules->delete($model, $request->user());

        return ApiResponse::success(['deleted' => true]);
    }

    /**
     * @return Builder<ReportSchedule>
     */
    private function mine(Request $request): Builder
    {
        return ReportSchedule::query()->where('owner_user_id', $request->user()->id);
    }
}
