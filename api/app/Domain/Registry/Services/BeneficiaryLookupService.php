<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Support\IdentifierHasher;
use Illuminate\Support\Collection;

/**
 * The controlled cross-MDA lookup/serve seam (PRD FR-OWN-03).
 *
 * Deliberately bypasses the owner MdaScope so a non-owner MDA can find that a
 * beneficiary already exists — but callers only ever receive the reveal fields
 * (see BeneficiaryRevealResource), never the full profile, and this path grants
 * no edit rights. Every lookup is audited (without logging the raw identifiers).
 *
 * This is an EXACT-key access path (NIN/BVN/phone). Fuzzy duplicate matching and
 * the request-to-serve workflow build on top of it in Phase 3.
 */
class BeneficiaryLookupService
{
    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * Find beneficiaries by any provided exact identifier, across all MDAs.
     *
     * @return Collection<int, Beneficiary>
     */
    public function findByIdentity(?string $nin = null, ?string $bvn = null, ?string $phone = null): Collection
    {
        $nin = Beneficiary::normalizeDigits($nin);
        $bvn = Beneficiary::normalizeDigits($bvn);
        $phone = Beneficiary::normalizeDigits($phone);

        $matches = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            // The owner MDA is itself MDA-scoped, so bypass its scope too — otherwise
            // a non-owner caller would see a null owner on the very record they revealed.
            ->with(['ownerMda' => fn ($query) => $query->withoutGlobalScope(MdaScope::class)->select('id', 'name')])
            ->where(function ($query) use ($nin, $bvn, $phone): void {
                // NIN/BVN are encrypted at rest — exact lookup runs on the hashes.
                if ($nin !== null) {
                    $query->orWhere('nin_hash', IdentifierHasher::hash($nin));
                }
                if ($bvn !== null) {
                    $query->orWhere('bvn_hash', IdentifierHasher::hash($bvn));
                }
                if ($phone !== null) {
                    $query->orWhere('phone', $phone);
                }
            })
            ->get();

        // Audit the lookup — record WHICH identifiers were used, never their values.
        $this->audit->record('beneficiary.lookup', null, after: [
            'by' => array_values(array_filter([
                $nin !== null ? 'nin' : null,
                $bvn !== null ? 'bvn' : null,
                $phone !== null ? 'phone' : null,
            ])),
            'matches' => $matches->count(),
        ]);

        return $matches;
    }
}
