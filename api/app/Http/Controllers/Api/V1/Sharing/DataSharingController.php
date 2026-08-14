<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Sharing;

use App\Domain\Access\Models\MdaAccessGrant;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Sharing\DataSharingGuard;
use App\Domain\Sharing\SharingBasis;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Data-sharing oversight (FR-DSH-01): who can access what across MDAs, and why.
 *
 * Lists every ACTIVE cross-MDA grant, of both kinds, because they differ in the way
 * that matters to whoever reviews them:
 *
 *  - **Service-Request grant** — per BENEFICIARY. The owner MDA approved one record.
 *  - **Administrative grant** (FR-UAM-03) — per MDA. An administrator opened another
 *    MDA's scoped data to a named user, with a reason and an optional expiry. This is
 *    the widest grant in the system; a report that omitted it would answer "who can
 *    access what" wrongly.
 *
 * Each row carries its {@see SharingBasis}, its scope, and whether the consent gate
 * currently makes it EFFECTIVE. Oversight-only (`cross-mda.view`); names only — never
 * raw identifiers.
 */
class DataSharingController extends Controller
{
    public function grants(Request $request, DataSharingGuard $guard): JsonResponse
    {
        $rows = $this->serviceGrants($guard)
            ->merge($this->adminGrants())
            ->sortByDesc('granted_at')
            ->values();

        // Both sources are bounded lists of ACTIVE grants; paginating in memory keeps
        // one ordered, comparable view rather than two paginators the reader must merge.
        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $page = max($request->integer('page', 1), 1);

        return ApiResponse::success([
            'grants' => $rows->forPage($page, $perPage)->values()->all(),
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $rows->count(),
                'total_pages' => (int) max(1, ceil($rows->count() / $perPage)),
            ],
            'summary' => [
                'service_request' => $rows->where('basis', SharingBasis::ServiceGrant->value)->count(),
                'admin_grant' => $rows->where('basis', SharingBasis::AdminGrant->value)->count(),
                // Grants currently suspended by a withdrawn/absent consent. Not a
                // failure — it is the consent gate doing its job — but oversight should
                // see how much access is dormant rather than assume every grant is live.
                'ineffective' => $rows->where('consent.effective', false)->count(),
            ],
        ]);
    }

    /**
     * Per-beneficiary grants opened by an accepted Service Request.
     *
     * Returned as a BASE collection (`toBase`): mapping an Eloquent collection to
     * arrays keeps the Eloquent class, whose `merge()` expects models and calls
     * `getKey()` on them.
     */
    private function serviceGrants(DataSharingGuard $guard): Collection
    {
        return BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class) // oversight sees every grant
            ->whereNull('revoked_at')
            ->with([
                'beneficiary' => fn ($q) => $q->withoutGlobalScope(MdaScope::class)
                    ->with(['ownerMda' => fn ($o) => $o->withoutGlobalScope(MdaScope::class)->select('id', 'name')]),
                'mda:id,name',
            ])
            ->get()
            ->map(fn (BeneficiaryServiceGrant $grant): array => $this->serviceGrantRow($grant, $guard))
            ->toBase();
    }

    /**
     * One service-grant row. The beneficiary relation is eager-loaded and backed by a
     * foreign key, so it is present; the guard is asked for the consent verdict rather
     * than the status being re-interpreted here.
     *
     * @return array<string, mixed>
     */
    private function serviceGrantRow(BeneficiaryServiceGrant $grant, DataSharingGuard $guard): array
    {
        $beneficiary = $grant->beneficiary;

        return [
            'id' => $grant->id,
            'basis' => SharingBasis::ServiceGrant->value,
            'basis_label' => SharingBasis::ServiceGrant->label(),
            'scope' => SharingBasis::ServiceGrant->scope(),
            'beneficiary_id' => $grant->beneficiary_id,
            'beneficiary_name' => $beneficiary->fullName(),
            'owner_mda' => $this->mdaRef($beneficiary->ownerMda),
            'granted_mda' => $this->mdaRef($grant->mda),
            'granted_to_user' => null, // a service grant is held by the MDA, not a person
            'service_request_id' => $grant->service_request_id,
            'reason' => null,
            'expires_at' => null,
            'granted_at' => $grant->granted_at->toIso8601String(),
            'consent' => [
                'status' => $beneficiary->sharing_consent->value,
                'required' => $guard->consentRequired(),
                'effective' => $guard->consentSatisfied($beneficiary),
            ],
        ];
    }

    /**
     * Whole-MDA administrative grants (FR-UAM-03). These have no single subject, so
     * `beneficiary_id` is null and the consent column reports the GATE rather than one
     * person's status — the gate is evaluated per record at read time.
     */
    private function adminGrants(): Collection
    {
        $consentRequired = (bool) config('sharing.admin_grant_requires_consent', true);

        // `active()` excludes REVOKED as well as expired. Since revocation now retains
        // the row, a plain expiry filter would report withdrawn access as current — the
        // oversight view answers "who can read this today", not "who ever could".
        return MdaAccessGrant::query()
            ->active()
            ->with(['user:id,name,mda_id', 'user.mda:id,name', 'mda:id,name', 'grantedBy:id,name'])
            ->get()
            ->map(fn (MdaAccessGrant $grant): array => [
                'id' => $grant->id,
                'basis' => SharingBasis::AdminGrant->value,
                'basis_label' => SharingBasis::AdminGrant->label(),
                'scope' => SharingBasis::AdminGrant->scope(),
                'beneficiary_id' => null,
                'beneficiary_name' => null,
                // The MDA whose data is opened…
                'owner_mda' => $this->mdaRef($grant->mda),
                // …and the grantee's own MDA, plus the person who holds it.
                'granted_mda' => $this->mdaRef($grant->user?->mda),
                'granted_to_user' => $grant->user ? ['id' => $grant->user->id, 'name' => $grant->user->name] : null,
                'service_request_id' => null,
                'reason' => $grant->reason,
                'expires_at' => $grant->expires_at?->toIso8601String(),
                'granted_at' => $grant->created_at?->toIso8601String(),
                'granted_by' => $grant->grantedBy ? ['id' => $grant->grantedBy->id, 'name' => $grant->grantedBy->name] : null,
                'consent' => [
                    'status' => null, // no single subject — evaluated per record at read time
                    'required' => $consentRequired,
                    'effective' => true,
                ],
            ])
            ->toBase();
    }

    /** @param object|null $mda */
    private function mdaRef($mda): ?array
    {
        return $mda === null ? null : ['id' => $mda->id, 'name' => $mda->name];
    }
}
