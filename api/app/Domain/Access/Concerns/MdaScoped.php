<?php

declare(strict_types=1);

namespace App\Domain\Access\Concerns;

/**
 * Implemented (via the ScopedToMda trait) by models that carry an owning-MDA
 * column, so the global scope can resolve the column in a type-safe way.
 */
interface MdaScoped
{
    public function mdaOwnershipColumn(): string;
}
