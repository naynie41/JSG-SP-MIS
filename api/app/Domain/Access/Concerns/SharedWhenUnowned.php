<?php

declare(strict_types=1);

namespace App\Domain\Access\Concerns;

use App\Domain\Access\Scopes\MdaScope;

/**
 * An MDA-scoped model whose ownership column may be NULL, where NULL means
 * "belongs to everyone" rather than "belongs to no one".
 *
 * {@see MdaScope} normally restricts to the caller's own
 * MDAs, which would hide every unowned row. A Programme is the case this exists for:
 * the central catalog carries no owner and must stay readable by every MDA, while a
 * programme an MDA creates for itself must never be visible to another MDA. Marking
 * the model widens the scope to `(owner IS NULL OR owner IN (...))` — one rule, in
 * the one place scoping is decided, rather than a second scope to keep in step.
 */
interface SharedWhenUnowned extends MdaScoped {}
