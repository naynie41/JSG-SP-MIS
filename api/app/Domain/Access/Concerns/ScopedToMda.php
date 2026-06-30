<?php

declare(strict_types=1);

namespace App\Domain\Access\Concerns;

use App\Domain\Access\Scopes\MdaScope;

/**
 * Opt-in MDA data-scoping for a model. Adding this trait applies the central
 * MdaScope global scope, so the model is automatically restricted to the
 * authenticated user's accessible MDAs (FR-UAM-03, FR-DSH-01).
 *
 * Phase 2+ models (e.g. beneficiaries with an `owner_mda_id`) just `use
 * ScopedToMda`. Models whose MDA column differs override mdaOwnershipColumn().
 */
trait ScopedToMda
{
    public static function bootScopedToMda(): void
    {
        static::addGlobalScope(new MdaScope);
    }

    /**
     * The column holding the owning MDA's id. Defaults to the Phase 2 ownership
     * convention; override where the column differs (e.g. User uses `mda_id`).
     */
    public function mdaOwnershipColumn(): string
    {
        return 'owner_mda_id';
    }
}
