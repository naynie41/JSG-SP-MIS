<?php

declare(strict_types=1);

namespace App\Domain\Shared\Concerns;

use App\Domain\Shared\Scopes\ArchivedScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Opt-in archiving for a model that carries history (PRD §10).
 *
 * Adding this trait applies {@see ArchivedScope}, so archived rows drop out of every
 * ordinary query while remaining fully present for audit and historical reporting.
 *
 * This is NOT SoftDeletes. A soft delete says "this should not have existed"; an
 * archive says "this happened and is finished". They coexist: a model may use both,
 * and archiving never touches `deleted_at`.
 *
 * Writing the archive columns is deliberately NOT exposed here — an archive has rules
 * (what blocks it, what it must record, what it audits) that belong to a service, and
 * a model method would let any caller skip them.
 *
 * @method static Builder withArchived()
 * @method static Builder onlyArchived()
 */
trait Archivable
{
    public static function bootArchivable(): void
    {
        static::addGlobalScope(new ArchivedScope(static::archivedAtColumn()));
    }

    /**
     * The column holding the archive timestamp; override where it differs.
     *
     * Static so the boot method can read it without instantiating the model.
     */
    public static function archivedAtColumn(): string
    {
        return 'archived_at';
    }

    public function isArchived(): bool
    {
        return $this->getAttribute(static::archivedAtColumn()) !== null;
    }

    /** Include archived rows — for audit trails and historical reporting. */
    public function scopeWithArchived(Builder $query): Builder
    {
        return $query->withoutGlobalScope(ArchivedScope::class);
    }

    /** Only archived rows — for an "archive" view. */
    public function scopeOnlyArchived(Builder $query): Builder
    {
        return $query
            ->withoutGlobalScope(ArchivedScope::class)
            ->whereNotNull($this->getTable().'.'.static::archivedAtColumn());
    }
}
