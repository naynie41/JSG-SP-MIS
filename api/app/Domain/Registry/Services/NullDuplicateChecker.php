<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Registry\Contracts\DuplicateChecker;

/**
 * Default no-op duplicate checker (Phase 2). Fuzzy matching / reveal lands in
 * Phase 3 by binding a real implementation to the DuplicateChecker contract.
 */
class NullDuplicateChecker implements DuplicateChecker
{
    public function check(array $attributes, string $ownerMdaId): void
    {
        // Intentionally does nothing until Phase 3.
    }
}
