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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Data-sharing oversight (FR-DSH-01): who can access what across MDAs, and why.
 *
 * Lists cross-MDA grants of both kinds, because they differ in the way that matters to
 * whoever reviews them:
 *
 *  - **Service-Request grant** — per BENEFICIARY. The owner MDA approved one record.
 *  - **Administrative grant** (FR-UAM-03) — per MDA. An administrator opened another
 *    MDA's scoped data to a named user, with a reason and an optional expiry. This is
 *    the widest grant in the system; a report that omitted it would answer "who can
 *    access what" wrongly.
 *
 * Each row carries its {@see SharingBasis}, its scope, whether it is still ACTIVE, and
 * whether the consent gate currently makes it EFFECTIVE. Oversight-only
 * (`cross-mda.view`); names only — never raw identifiers.
 *
 * ── Active vs revoked ────────────────────────────────────────────────────────────────
 * "Who can access what, and why" is two questions, and they need different answers.
 * Unfiltered, this report answers the LIVE one — who can read this today — because that
 * is what someone reviewing current exposure needs, and because counting withdrawn
 * access as current is the one mistake it must never make. `?status=revoked` and
 * `?status=all` answer the AUDIT one: access that was held and then withdrawn. Both
 * grant tables retain the revoked row precisely so that history can be shown; a report
 * that could only ever show current access would make that retention pointless.
 *
 * An unrecognised `status` is refused rather than defaulted, so a typo cannot widen the
 * answer into disclosing withdrawn access to a reader who asked for something else.
 */
class DataSharingController extends Controller
{
    public function grants(Request $request, DataSharingGuard $guard): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'in:active,revoked,all'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);
        $status = $validated['status'] ?? 'active';

        $rows = $this->serviceGrants($guard, $status)
            ->merge($this->adminGrants($status))
            ->sortByDesc('granted_at')
            ->values();

        // Both sources are bounded lists; paginating in memory keeps one ordered,
        // comparable view rather than two paginators the reader must merge.
        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $page = max($request->integer('page', 1), 1);

        // The summary counts CURRENT access by basis, whatever the filter is showing, so
        // "how much access exists" does not change meaning when someone opens the
        // history. Revoked rows are counted separately rather than folded in.
        $active = $rows->where('active', true);

        return ApiResponse::success([
            'status' => $status,
            'grants' => $rows->forPage($page, $perPage)->values()->all(),
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $rows->count(),
                'total_pages' => (int) max(1, ceil($rows->count() / $perPage)),
            ],
            'summary' => [
                'service_request' => $active->where('basis', SharingBasis::ServiceGrant->value)->count(),
                'admin_grant' => $active->where('basis', SharingBasis::AdminGrant->value)->count(),
                // Grants currently suspended by a withdrawn/absent consent. Not a
                // failure — it is the consent gate doing its job — but oversight should
                // see how much access is dormant rather than assume every grant is live.
                'ineffective' => $active->where('consent.effective', false)->count(),
                // Only ever non-zero when the reader asked for history.
                'revoked' => $rows->where('active', false)->count(),
            ],
        ]);
    }

    /**
     * The active/revoked filter.
     *
     * "Active" and "revoked" are not complements, and the two grant kinds disagree on
     * what active means: an administrative grant can also LAPSE. So `active` defers to
     * each model's own notion — which excludes expiry as well as revocation, because an
     * expired grant confers nothing and the unfiltered view answers who can read TODAY —
     * while `revoked` means exactly what an administrator withdrew, never what merely ran
     * out. A grant that is expired but not revoked is therefore reachable only under
     * `all`, which is the only status that promises everything.
     *
     * @param  Builder<MdaAccessGrant>|Builder<BeneficiaryServiceGrant>  $query
     * @param  callable(mixed): void  $active  each model's own "still confers access"
     */
    private function applyStatus(Builder $query, string $status, callable $active): void
    {
        match ($status) {
            'revoked' => $query->whereNotNull('revoked_at'),
            'all' => null,
            default => $active($query),
        };
    }

    /**
     * Per-beneficiary grants opened by an accepted Service Request.
     *
     * Returned as a BASE collection (`toBase`): mapping an Eloquent collection to
     * arrays keeps the Eloquent class, whose `merge()` expects models and calls
     * `getKey()` on them.
     */
    private function serviceGrants(DataSharingGuard $guard, string $status): Collection
    {
        $query = BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class) // oversight sees every grant
            ->with([
                'beneficiary' => fn ($q) => $q->withoutGlobalScope(MdaScope::class)
                    ->with(['ownerMda' => fn ($o) => $o->withoutGlobalScope(MdaScope::class)->select('id', 'name')]),
                'mda:id,name',
                'revokedBy:id,name',
            ]);
        // A service grant carries no expiry, so "not revoked" is the whole of active.
        $this->applyStatus($query, $status, fn ($q) => $q->whereNull('revoked_at'));

        return $query->get()
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
        $active = $grant->revoked_at === null;

        return [
            'id' => $grant->id,
            'basis' => SharingBasis::ServiceGrant->value,
            'basis_label' => SharingBasis::ServiceGrant->label(),
            'scope' => SharingBasis::ServiceGrant->scope(),
            'active' => $active,
            'beneficiary_id' => $grant->beneficiary_id,
            'beneficiary_name' => $beneficiary->fullName(),
            'owner_mda' => $this->mdaRef($beneficiary->ownerMda),
            'granted_mda' => $this->mdaRef($grant->mda),
            'granted_to_user' => null, // a service grant is held by the MDA, not a person
            'service_request_id' => $grant->service_request_id,
            'reason' => null,
            'expires_at' => null,
            'granted_at' => $grant->granted_at->toIso8601String(),
            ...$this->revocation($grant->revoked_at, $grant->revokedBy, $grant->revocation_reason),
            'consent' => [
                'status' => $beneficiary->sharing_consent->value,
                'required' => $guard->consentRequired(),
                // A revoked grant confers nothing regardless of consent. Reporting it as
                // effective would show withdrawn access as live.
                'effective' => $active && $guard->consentSatisfied($beneficiary),
            ],
        ];
    }

    /**
     * Whole-MDA administrative grants (FR-UAM-03). These have no single subject, so
     * `beneficiary_id` is null and the consent column reports the GATE rather than one
     * person's status — the gate is evaluated per record at read time.
     */
    private function adminGrants(string $status): Collection
    {
        $consentRequired = (bool) config('sharing.admin_grant_requires_consent', true);

        $query = MdaAccessGrant::query()
            ->with(['user:id,name,mda_id', 'user.mda:id,name', 'mda:id,name', 'grantedBy:id,name', 'revokedBy:id,name']);
        // An admin grant can lapse as well as be withdrawn, so active is its own scope.
        $this->applyStatus($query, $status, fn ($q) => $q->active());

        return $query->get()
            ->map(function (MdaAccessGrant $grant) use ($consentRequired): array {
                // An admin grant can also simply lapse. `isActive()` is the model's own
                // verdict and accounts for expiry as well as revocation.
                $active = $grant->isActive();

                return [
                    'id' => $grant->id,
                    'basis' => SharingBasis::AdminGrant->value,
                    'basis_label' => SharingBasis::AdminGrant->label(),
                    'scope' => SharingBasis::AdminGrant->scope(),
                    'active' => $active,
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
                    ...$this->revocation($grant->revoked_at, $grant->revokedBy, $grant->revocation_reason),
                    'consent' => [
                        'status' => null, // no single subject — evaluated per record at read time
                        'required' => $consentRequired,
                        'effective' => $active,
                    ],
                ];
            })
            ->toBase();
    }

    /**
     * The revocation columns, shaped identically for both grant kinds so a reader (or a
     * table) does not have to branch on which sort of grant a row is.
     *
     * @return array<string, mixed>
     */
    private function revocation(?Carbon $at, ?object $by, ?string $reason): array
    {
        return [
            'revoked_at' => $at?->toIso8601String(),
            'revoked_by' => $by === null ? null : ['id' => $by->id, 'name' => $by->name],
            'revocation_reason' => $reason,
        ];
    }

    /** @param object|null $mda */
    private function mdaRef($mda): ?array
    {
        return $mda === null ? null : ['id' => $mda->id, 'name' => $mda->name];
    }
}
