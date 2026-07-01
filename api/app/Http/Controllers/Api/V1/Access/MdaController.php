<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Models\Mda;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\StoreMdaRequest;
use App\Http\Requests\Access\UpdateMdaRequest;
use App\Http\Resources\MdaResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Admin management of MDAs (PRD FR-UAM-02). List/show are MDA-scoped by the
 * model's global scope; every action declares its permission in routes/api.php;
 * every mutation is audited by the Auditable model.
 */
class MdaController extends Controller
{
    public function index(): JsonResponse
    {
        $mdas = Mda::query()->orderBy('name')->get();

        return ApiResponse::success(['mdas' => MdaResource::collection($mdas)->resolve()]);
    }

    public function store(StoreMdaRequest $request): JsonResponse
    {
        $mda = Mda::create($request->validated());

        return ApiResponse::success((new MdaResource($mda))->resolve(), status: 201);
    }

    public function show(Mda $mda): JsonResponse
    {
        return ApiResponse::success((new MdaResource($mda))->resolve());
    }

    public function update(UpdateMdaRequest $request, Mda $mda): JsonResponse
    {
        $mda->update($request->validated());

        return ApiResponse::success((new MdaResource($mda->fresh()))->resolve());
    }

    public function deactivate(Mda $mda): JsonResponse
    {
        $mda->update(['status' => MdaStatus::Inactive]);

        return ApiResponse::success((new MdaResource($mda->fresh()))->resolve());
    }

    public function activate(Mda $mda): JsonResponse
    {
        $mda->update(['status' => MdaStatus::Active]);

        return ApiResponse::success((new MdaResource($mda->fresh()))->resolve());
    }
}
