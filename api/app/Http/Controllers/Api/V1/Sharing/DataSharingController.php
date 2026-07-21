<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Sharing;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Sharing\DataSharingGuard;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Data-sharing oversight (FR-DSH-01): who can access which beneficiary across MDAs,
 * and on what basis. Lists the active cross-MDA read/serve grants (each opened by an
 * accepted Service Request) with the granting owner MDA, the granted MDA, the source
 * request, and whether consent currently makes the grant EFFECTIVE. Oversight-only
 * (`cross-mda.view`); names only — never raw identifiers.
 */
class DataSharingController extends Controller
{
    public function grants(Request $request, DataSharingGuard $guard): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $page = BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class) // oversight sees every grant
            ->whereNull('revoked_at')
            ->with([
                'beneficiary' => fn ($q) => $q->withoutGlobalScope(MdaScope::class)
                    ->with(['ownerMda' => fn ($o) => $o->withoutGlobalScope(MdaScope::class)->select('id', 'name')]),
                'mda:id,name',
            ])
            ->latest('granted_at')
            ->paginate($perPage);

        $rows = collect($page->items())->map(function (BeneficiaryServiceGrant $grant) use ($guard): array {
            $beneficiary = $grant->beneficiary;

            return [
                'id' => $grant->id,
                'basis' => 'service_request', // the only cross-MDA sharing basis that opens a grant
                'beneficiary_id' => $grant->beneficiary_id,
                'beneficiary_name' => $beneficiary?->fullName(),
                'owner_mda' => $beneficiary?->ownerMda ? ['id' => $beneficiary->ownerMda->id, 'name' => $beneficiary->ownerMda->name] : null,
                'granted_mda' => $grant->mda ? ['id' => $grant->mda->id, 'name' => $grant->mda->name] : null,
                'service_request_id' => $grant->service_request_id,
                'granted_at' => $grant->granted_at?->toIso8601String(),
                'consent' => [
                    'status' => $beneficiary?->sharing_consent->value,
                    // Whether the grant is currently effective under the consent gate.
                    'effective' => $beneficiary !== null && $guard->consentSatisfied($beneficiary),
                ],
            ];
        })->all();

        return ApiResponse::paginated($rows, $page);
    }
}
