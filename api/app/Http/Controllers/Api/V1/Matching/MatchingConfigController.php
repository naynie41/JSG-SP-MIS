<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Matching;

use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Matching\UpdateMatchingConfigRequest;
use App\Http\Resources\MatchingConfigResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Admin management of the duplicate-matching configuration (PRD FR-DUP-02).
 * Read is gated by `matching.view`; publishing a new version by `matching.edit`
 * (System Administrator). Every publish is a new immutable version, audited.
 */
class MatchingConfigController extends Controller
{
    public function __construct(private readonly MatchingConfigService $configs) {}

    /** The current active configuration. */
    public function show(): JsonResponse
    {
        return ApiResponse::success((new MatchingConfigResource($this->configs->active()))->resolve());
    }

    /** Publish a new active version. */
    public function update(UpdateMatchingConfigRequest $request): JsonResponse
    {
        $config = $this->configs->publish($request->validated(), $request->user());

        return ApiResponse::success((new MatchingConfigResource($config))->resolve());
    }

    /** Full version history, newest first. */
    public function versions(): JsonResponse
    {
        $versions = MatchingConfig::query()->orderByDesc('version')->get();

        return ApiResponse::success(['versions' => MatchingConfigResource::collection($versions)->resolve()]);
    }
}
