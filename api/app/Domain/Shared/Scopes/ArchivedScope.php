<?php

declare(strict_types=1);

namespace App\Domain\Shared\Scopes;

use App\Domain\Access\Scopes\MdaScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Hides archived records from ordinary queries (PRD §10).
 *
 * Archiving is the "delete" for anything carrying history — activities, enrolments,
 * ledger entries, graduation events. The row must survive for audit and historical
 * reporting while disappearing from the lists people pick from.
 *
 * Deliberately built like {@see MdaScope}: a global scope
 * applied by an opt-in trait, so exclusion is the DEFAULT and each place that wants
 * history has to say so. The reverse — filtering in each query — means a forgotten
 * filter silently offers an archived programme for selection, and nothing fails.
 *
 * The column is passed in rather than read off the model: `Scope::apply()` receives a
 * bare Model, which knows nothing of the Archivable trait.
 *
 * History views escape with `withArchived()` / `onlyArchived()` on the trait.
 */
class ArchivedScope implements Scope
{
    public function __construct(private readonly string $column = 'archived_at') {}

    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNull($model->getTable().'.'.$this->column);
    }
}
