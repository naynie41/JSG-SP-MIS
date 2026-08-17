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

    /**
     * Revoke a cross-MDA access grant.
     *
     * A SOFT revoke: the row is retained with `revoked_at`/`revoked_by`/reason set
     * (NFR-PRV-01, FR-AUD-01). Deleting it would erase the evidence that the access ever
     * existed, leaving an auditor unable to distinguish "access was held and withdrawn"
     * from "access was never granted" — the opposite of what an access trail is for.
     *
     * Idempotent: revoking an already-revoked grant changes nothing and does not
     * re-stamp the original actor or time, because the FIRST withdrawal is the one that
     * ended the access.
     */
    public function destroy(Request $request, MdaAccessGrant $grant): JsonResponse
    {
        if ($grant->revoked_at !== null) {
            return ApiResponse::success([
                'message' => 'This access had already been revoked; nothing changed.',
                'revoked' => false,
            ]);
        }

        CrossMdaAccessRevoked::dispatch($grant, $request->user());

        $grant->update([
            'revoked_at' => now(),
            'revoked_by' => $request->user()->id,
            'revocation_reason' => $request->string('reason')->value() ?: null,
        ]);

        return ApiResponse::success([
            'message' => 'Cross-MDA access revoked.',
            'revoked' => true,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function present(MdaAccessGrant $grant): array
    {
        $grant->loadMissing(['user:id,name,email', 'mda:id,name', 'grantedBy:id,name', 'revokedBy:id,name']);

        return [
            'id' => $grant->id,
            'user' => $grant->user ? ['id' => $grant->user->id, 'name' => $grant->user->name, 'email' => $grant->user->email] : null,
            'mda' => $grant->mda ? ['id' => $grant->mda->id, 'name' => $grant->mda->name] : null,
            'granted_by' => $grant->grantedBy?->name,
            'granted_at' => $grant->created_at?->toIso8601String(),
            'reason' => $grant->reason,
            'expires_at' => $grant->expires_at?->toIso8601String(),
            // Computed from the model so a REVOKED grant can never report as active —
            // reading `expires_at` alone would say a withdrawn grant is still live.
            'active' => $grant->isActive(),
            // The full history stays on the row after revocation (NFR-PRV-01): when it
            // ended, who ended it, and why.
            'revoked_at' => $grant->revoked_at?->toIso8601String(),
            'revoked_by' => $grant->revokedBy?->name,
            'revocation_reason' => $grant->revocation_reason,
            'created_at' => $grant->created_at?->toIso8601String(),
        ];
    }
}
