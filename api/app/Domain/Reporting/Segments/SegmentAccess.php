<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Export\BeneficiaryListExport;
use App\Domain\Reporting\Support\DashboardScope;

/**
 * What a caller may get OUT of the segment builder (SECURITY.md §3 export matrix).
 *
 * The builder composes filters; it does not decide entitlement. Three questions are
 * answered here, once, so that the preview endpoint, the export endpoint and the queued
 * job cannot drift apart — the classic way a "report tool" becomes the hole in an export
 * policy is that one of those three forgets to ask.
 *
 *  1. May they see ROWS at all, or only counts?
 *     System Administrator, SP Coordination and MDA Admin export beneficiary data.
 *     Development Partners and Executives do NOT — aggregates only, never the registry.
 *     This is a TIER, derived from the export permission, not from the scope: a partner
 *     with a wide funded-programme scope still gets counts, and an MDA Admin with a
 *     narrow one still gets its own rows.
 *
 *  2. May identifiers be shown in the clear?
 *     Only with `export.reveal_pii`, which SECURITY.md reserves to the System
 *     Administrator by default. Everything else is masked, including for an MDA
 *     exporting the people it owns.
 *
 *  3. Does the small-cell guard apply?
 *     It applies wherever the output describes people the caller does not own — the
 *     aggregate tiers, and cross-MDA aggregates. It does NOT apply to an MDA
 *     segmenting its OWN beneficiaries: re-identification is not a risk against a
 *     population you already hold the records for, and suppressing there would break
 *     ordinary operational work like "which two women in this ward are still pending".
 */
final readonly class SegmentAccess
{
    public const TIER_ROWS = 'rows';

    public const TIER_AGGREGATE = 'aggregate';

    /** The permission that lets a caller pull beneficiary rows at all. */
    public const EXPORT_PERMISSION = 'beneficiary.export';

    private function __construct(
        public string $tier,
        public bool $revealPii,
        public bool $cellSizeGuard,
        public DashboardScope $scope,
    ) {}

    public static function forUser(User $user, DashboardScope $scope): self
    {
        $mayExportRows = $user->hasPermission(self::EXPORT_PERMISSION);
        $tier = $mayExportRows ? self::TIER_ROWS : self::TIER_AGGREGATE;

        // Own-MDA operational use is the ONE case the guard steps aside for, and it is
        // narrow on purpose: the row tier, an MDA-kind scope, and no borrowed reach
        // beyond the caller's own MDA. A cross-MDA grant widens what they can see, and
        // the people it reaches are not theirs — so the guard stays on.
        $ownMdaOnly = $tier === self::TIER_ROWS
            && $scope->kind === DashboardScope::KIND_MDA
            && $scope->mdaIds !== null
            && $user->mda_id !== null
            && $scope->mdaIds === [$user->mda_id];

        return new self(
            tier: $tier,
            revealPii: $mayExportRows && $user->hasPermission(BeneficiaryListExport::REVEAL_PERMISSION),
            cellSizeGuard: ! $ownMdaOnly,
            scope: $scope,
        );
    }

    /**
     * Rebuild from what was persisted on the run, so a queued job renders exactly the
     * entitlement resolved at request time — never a re-resolution against a user whose
     * roles may have changed since.
     *
     * @param  array<string, mixed>  $params
     */
    public static function fromParams(array $params, DashboardScope $scope): self
    {
        return new self(
            tier: (string) ($params['tier'] ?? self::TIER_AGGREGATE),
            revealPii: (bool) ($params['reveal'] ?? false),
            cellSizeGuard: (bool) ($params['cell_size_guard'] ?? true),
            scope: $scope,
        );
    }

    public function showsRows(): bool
    {
        return $this->tier === self::TIER_ROWS;
    }

    /** @return array<string, mixed> */
    public function toParams(): array
    {
        return [
            'tier' => $this->tier,
            'reveal' => $this->revealPii,
            'cell_size_guard' => $this->cellSizeGuard,
        ];
    }
}
