<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Events\CrossMdaAccessGranted;
use App\Domain\Access\Events\CrossMdaAccessRevoked;
use App\Domain\Access\Models\MdaAccessGrant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\StoreMdaAccessGrantRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Admin management of cross-MDA access grants (PRD FR-UAM-03, FR-DSH-01). A grant
 * lets a user access another MDA's scoped data; grants are auditable (who granted,
 * why, until when) and emit events for the audit log.
 */
class MdaAccessGrantController extends Controller
{
    public function index(): JsonResponse
    {
        $grants = MdaAccessGrant::query()
            ->with(['user:id,name,email', 'mda:id,name', 'grantedBy:id,name'])
            ->latest()
            ->get()
            ->map(fn (MdaAccessGrant $grant) => $this->present($grant));

        return ApiResponse::success(['grants' => $grants]);
    }

    public function store(StoreMdaAccessGrantRequest $request): JsonResponse
    {
        $data = $request->validated();

        $grant = MdaAccessGrant::create([
            'user_id' => $data['user_id'],
            'mda_id' => $data['mda_id'],
            'granted_by' => $request->user()->id,
            'reason' => $data['reason'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        CrossMdaAccessGranted::dispatch($grant, $request->user());

        return ApiResponse::success($this->present($grant), status: 201);
    }

    public function destroy(Request $request, MdaAccessGrant $grant): JsonResponse
    {
        CrossMdaAccessRevoked::dispatch($grant, $request->user());

        $grant->delete();

        return ApiResponse::success(['message' => 'Cross-MDA access revoked.']);
    }

    /**
     * @return array<string, mixed>
     */
    private function present(MdaAccessGrant $grant): array
    {
        return [
            'id' => $grant->id,
            'user_id' => $grant->user_id,
            'mda_id' => $grant->mda_id,
            'granted_by' => $grant->granted_by,
            'reason' => $grant->reason,
            'expires_at' => $grant->expires_at?->toIso8601String(),
            'created_at' => $grant->created_at?->toIso8601String(),
        ];
    }
}
