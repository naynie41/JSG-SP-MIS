<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Support;

/**
 * A resolved dashboard scope (PRD FR-DSH-01). It expresses WHICH data a caller may
 * see, independent of the implicit request-time MdaScope, so the reporting layer can
 * apply the same constraint whether it runs in a request or on the (unauthenticated)
 * scheduler/queue. Three kinds:
 *
 *  - state_wide — oversight (cross-mda.view): all MDAs, all programmes.
 *  - mda        — an MDA user: limited to `mdaIds` (own MDA + any cross-MDA grants).
 *  - partner    — a Development Partner: limited to their funded `programmeIds`.
 *
 * `mdaIds`/`programmeIds` are `null` when that axis is unconstrained (state-wide).
 */
final class DashboardScope
{
    public const KIND_STATE_WIDE = 'state_wide';

    public const KIND_MDA = 'mda';

    public const KIND_PARTNER = 'partner';

    /**
     * @param  list<string>|null  $mdaIds  null = all MDAs
     * @param  list<string>|null  $programmeIds  null = all programmes; set for partner
     * @param  string|null  $partnerId  the funding partner's user id (partner scope only)
     * @param  bool  $governance  whether GOVERNANCE data (users, audit, imports, the
     *                            administrative datasets) is in scope. This is a second,
     *                            independent axis: state-wide answers "how much of the
     *                            PROGRAMME data may you see", governance answers "may you
     *                            see who did what". Executive/SP Coordination are
     *                            state-wide but NOT governance; only a System
     *                            Administrator is both.
     */
    private function __construct(
        public readonly string $kind,
        public readonly ?array $mdaIds,
        public readonly ?array $programmeIds,
        public readonly ?string $partnerId,
        public readonly string $label,
        public readonly bool $governance = false,
    ) {}

    public static function stateWide(bool $governance = false): self
    {
        return new self(self::KIND_STATE_WIDE, null, null, null, 'State-wide', $governance);
    }

    /**
     * @param  list<string>  $mdaIds
     */
    public static function mda(array $mdaIds, string $label = 'My MDA'): self
    {
        sort($mdaIds);

        return new self(self::KIND_MDA, $mdaIds, null, null, $label);
    }

    /**
     * A Development Partner scope. `$programmeIds` are the partner's FUNDED programmes
     * (the distinct programmes of the activities they fund, `activities.funding_partner_id`);
     * `$partnerId` identifies the partner so activity-precise funding metrics resolve.
     *
     * @param  list<string>  $programmeIds
     */
    public static function partner(array $programmeIds, ?string $partnerId = null, string $label = 'Funded programmes'): self
    {
        sort($programmeIds);

        return new self(self::KIND_PARTNER, null, $programmeIds, $partnerId, $label);
    }

    /**
     * Reconstruct a scope from its persisted parts (e.g. a captured report run), so a
     * queued job applies the exact scope resolved at request time.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     */
    public static function rehydrate(string $kind, ?array $mdaIds, ?array $programmeIds, string $label, bool $governance = false): self
    {
        return match ($kind) {
            self::KIND_STATE_WIDE => self::stateWide($governance),
            self::KIND_PARTNER => self::partner($programmeIds ?? [], null, $label),
            default => self::mda($mdaIds ?? [], $label),
        };
    }

    public function isStateWide(): bool
    {
        return $this->kind === self::KIND_STATE_WIDE;
    }

    /**
     * The oversight TIER this scope represents (PRD role tiering). Governor/Executive
     * (and SP Coordination) resolve to state-wide; a Development Partner to their funded
     * programmes; every other MDA user to an operational/own-MDA tier. Purely a label —
     * the actual enforcement is the scope's `mdaIds`/`programmeIds` applied in every query.
     */
    public function tier(): string
    {
        return match ($this->kind) {
            self::KIND_STATE_WIDE => 'statewide',
            self::KIND_PARTNER => 'partner',
            default => 'operational',
        };
    }

    public function isPartner(): bool
    {
        return $this->kind === self::KIND_PARTNER;
    }

    /** Coordination metrics (referrals/grievances/duplicates) apply to MDA/state scopes, not partners. */
    public function includesCoordinationMetrics(): bool
    {
        return $this->kind !== self::KIND_PARTNER;
    }

    /**
     * Whether the administrative/governance datasets (users, MDAs, programmes,
     * duplicates, audit, imports) are in scope. State-wide oversight is NOT enough —
     * an Executive sees all programme data but not who did what.
     */
    public function includesGovernanceData(): bool
    {
        return $this->governance;
    }

    /**
     * Whether this scope is entitled to at least everything `$other` shows (PRD
     * FR-RPT-04). Used to check a recipient (this = recipient's scope) may receive a
     * report scoped to `$other` — so a schedule can never deliver out-of-scope data:
     *
     *  - a GOVERNANCE report is covered only by a governance scope — state-wide is not
     *    enough, so an Executive can never be sent an admin (user/audit/import) report
     *    even though they out-rank the MDA axis;
     *  - state-wide covers everything else;
     *  - an MDA scope covers another MDA scope only if its MDAs are a superset;
     *  - a partner scope covers another partner scope only if its programmes are a superset;
     *  - the axes never cross (an MDA scope cannot cover a partner/state-wide report).
     */
    public function covers(self $other): bool
    {
        if ($other->governance && ! $this->governance) {
            return false;
        }

        if ($this->isStateWide()) {
            return true;
        }

        if ($this->isPartner()) {
            return $other->kind === self::KIND_PARTNER
                && array_diff($other->programmeIds ?? [], $this->programmeIds ?? []) === [];
        }

        return $other->kind === self::KIND_MDA
            && array_diff($other->mdaIds ?? [], $this->mdaIds ?? []) === [];
    }

    /**
     * A stable key identifying this scope — the primary key of its snapshot row and
     * the cache key. Two callers with the same effective scope share one snapshot.
     */
    public function key(): string
    {
        // Governance is deliberately NOT part of the key: it gates administrative
        // DATASETS, not dashboard metrics, so an admin shares the state-wide snapshot.
        return match ($this->kind) {
            self::KIND_STATE_WIDE => 'state_wide',
            self::KIND_PARTNER => 'partner:'.($this->partnerId ?? ($this->programmeIds === [] ? 'none' : implode(',', $this->programmeIds))),
            default => 'mda:'.($this->mdaIds === [] ? 'none' : implode(',', $this->mdaIds)),
        };
    }
}
