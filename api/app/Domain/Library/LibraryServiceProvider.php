<?php

declare(strict_types=1);

namespace App\Domain\Library;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Library\Models\LibraryItem;
use App\Domain\Library\Policies\LibraryItemPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Library domain: the public resource library (PRD §7.14).
 *
 * Three permissions, all of them administrative. Reading is NOT among them — the
 * public endpoints are unauthenticated by design, so a `library.read` permission
 * would gate nothing that is actually gated. `library.view` is the ADMIN list,
 * which is a different thing: it includes drafts and archived items.
 *
 * There is deliberately no separate `library.publish`. Publishing is a status
 * change made by the same person who may edit the item, and only the System
 * Administrator holds any of these — a fourth permission would describe a
 * distinction the system does not draw, which is the same reasoning that keeps
 * ReferenceServiceProvider free of permissions entirely.
 */
class LibraryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('library', PermissionAction::View, 'View the resource library administration list (incl. drafts)')
            ->register('library', PermissionAction::Create, 'Add a resource to the public library')
            ->register('library', PermissionAction::Edit, 'Edit, publish, archive or remove a library resource');

        Gate::policy(LibraryItem::class, LibraryItemPolicy::class);
    }
}
