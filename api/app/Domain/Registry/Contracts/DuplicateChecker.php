<?php

declare(strict_types=1);

namespace App\Domain\Registry\Contracts;

/**
 * Pre-save duplicate-check extension point (PRD FR-DUP, Phase 3).
 *
 * Manual registration (FR-REG-01) calls this immediately before persisting a new
 * beneficiary. Phase 2 ships a no-op implementation (NullDuplicateChecker) so the
 * seam exists without changing behaviour; Phase 3 binds a real checker that runs
 * fuzzy matching and either blocks the save or surfaces reveal candidates — no
 * caller changes required.
 */
interface DuplicateChecker
{
    /**
     * Inspect a candidate registration before it is saved. Implementations may
     * throw to block the save (e.g. a hard NIN collision) or record/flag likely
     * duplicates for review. The no-op implementation does nothing.
     *
     * @param  array<string, mixed>  $attributes  the validated registration payload
     * @param  string  $ownerMdaId  the MDA that will own the new record
     */
    public function check(array $attributes, string $ownerMdaId): void;
}
