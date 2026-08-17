<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reference;

use App\Domain\Reference\Services\ReferenceDataCache;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reference\ListWardsRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * READ-ONLY LGA/Ward reference lookups (the cascading selector).
 *
 * There is no write side by design: this data comes from an authoritative dataset
 * loaded by a maintainer (`reference:load-divisions`), not from user input. An API
 * that let a user add a ward would let the lookup table drift away from the source it
 * is supposed to reproduce — the same reasoning that makes registry ingestion
 * bulk/source-only (CLAUDE.md §8).
 *
 * Both responses are cached; see {@see ReferenceDataCache} for the invalidation model.
 */
class AdministrativeDivisionController extends Controller
{
    public function __construct(private readonly ReferenceDataCache $cache) {}

    /**
     * All 27 LGAs, with the number of wards loaded for each.
     *
     * `ward_count` is what tells a client whether ward data is present at all: a fresh
     * install has LGAs from the dataset and zero wards until one is supplied.
     */
    public function lgas(): JsonResponse
    {
        $lgas = $this->cache->lgas();

        return ApiResponse::success(
            ['lgas' => $lgas],
            ['count' => count($lgas)],
        );
    }

    /**
     * The wards of one LGA — step two of the cascade.
     */
    public function wards(ListWardsRequest $request): JsonResponse
    {
        $lgaId = (string) $request->validated('lga_id');
        $wards = $this->cache->wardsFor($lgaId);

        return ApiResponse::success(
            ['wards' => $wards],
            ['count' => count($wards), 'lga_id' => $lgaId],
        );
    }
}
