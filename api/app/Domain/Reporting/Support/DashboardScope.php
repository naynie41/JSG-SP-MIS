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
     */
    private function __construct(
        public readonly string $kind,
        public readonly ?array $mdaIds,
        public readonly ?array $programmeIds,
        public readonly string $label,
    ) {}

    public static function stateWide(): self
    {
        return new self(self::KIND_STATE_WIDE, null, null, 'State-wide');
    }

    /**
     * @param  list<string>  $mdaIds
     */
    public static function mda(array $mdaIds, string $label = 'My MDA'): self
    {
        sort($mdaIds);

        return new self(self::KIND_MDA, $mdaIds, null, $label);
    }

    /**
     * @param  list<string>  $programmeIds
     */
    public static function partner(array $programmeIds, string $label = 'Funded programmes'): self
    {
        sort($programmeIds);

        return new self(self::KIND_PARTNER, null, $programmeIds, $label);
    }

    /**
     * Reconstruct a scope from its persisted parts (e.g. a captured report run), so a
     * queued job applies the exact scope resolved at request time.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     */
    public static function rehydrate(string $kind, ?array $mdaIds, ?array $programmeIds, string $label): self
    {
        return match ($kind) {
            self::KIND_STATE_WIDE => self::stateWide(),
            self::KIND_PARTNER => self::partner($programmeIds ?? [], $label),
            default => self::mda($mdaIds ?? [], $label),
        };
    }

    public function isStateWide(): bool
    {
        return $this->kind === self::KIND_STATE_WIDE;
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
     * A stable key identifying this scope — the primary key of its snapshot row and
     * the cache key. Two callers with the same effective scope share one snapshot.
     */
    public function key(): string
    {
        return match ($this->kind) {
            self::KIND_STATE_WIDE => 'state_wide',
            self::KIND_PARTNER => 'partner:'.($this->programmeIds === [] ? 'none' : implode(',', $this->programmeIds)),
            default => 'mda:'.($this->mdaIds === [] ? 'none' : implode(',', $this->mdaIds)),
        };
    }
}
