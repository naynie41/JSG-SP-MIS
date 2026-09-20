<?php

declare(strict_types=1);

namespace App\Domain\Library\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Library\Models\LibraryItem;

/**
 * Authorization for the resource library's ADMINISTRATION surface only.
 *
 * The public read and download paths are not covered here and must not be: they
 * are unauthenticated, there is no $user to pass, and the only question they ask
 * is {@see LibraryItem::isPublic()}. Routing a public request through a policy
 * would quietly make the gate depend on whoever happens to be signed in.
 *
 * There is no ownership dimension either — a library item belongs to the State,
 * not to an MDA — so these are flat permission checks. That is the whole rule.
 */
class LibraryItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('library.view');
    }

    public function view(User $user, LibraryItem $item): bool
    {
        return $user->hasPermission('library.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('library.create');
    }

    /** Covers editing the metadata, replacing the file, publishing and archiving. */
    public function update(User $user, LibraryItem $item): bool
    {
        return $user->hasPermission('library.edit');
    }

    /**
     * Soft-delete. Reserved for something created in error — withdrawing a published
     * resource is an ARCHIVE (an update), so that its download history survives.
     */
    public function delete(User $user, LibraryItem $item): bool
    {
        return $user->hasPermission('library.edit');
    }
}
