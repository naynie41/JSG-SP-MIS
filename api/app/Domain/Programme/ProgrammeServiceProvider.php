<?php

declare(strict_types=1);

namespace App\Domain\Programme;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Policies\ActivityPolicy;
use App\Domain\Programme\Policies\EnrollmentPolicy;
use App\Domain\Programme\Policies\ProgrammePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Programme domain (PRD FR-PRG-01/02): its module permissions and the
 * owner-only authorization policies for programmes and activities.
 */
class ProgrammeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('programme', PermissionAction::View, 'View the programme catalog')
            ->register('programme', PermissionAction::Create, 'Create programmes — the central catalog (catalog admin), or one owned by your own MDA')
            ->register('programme', PermissionAction::Edit, 'Edit/archive programmes — the catalog, or your own MDA while it waits for approval')
            ->register('programme', PermissionAction::Approve, 'Approve or send back a programme an MDA created (System Administrator)')
            ->register('activity', PermissionAction::View, 'View activities')
            ->register('activity', PermissionAction::Create, 'Create MDA-owned activities')
            ->register('activity', PermissionAction::Edit, 'Edit/archive activities (owner MDA only)')
            ->register('enrollment', PermissionAction::View, 'View programme enrollments')
            ->register('enrollment', PermissionAction::Create, 'Enroll beneficiaries/households into programmes')
            ->register('enrollment', PermissionAction::Edit, 'Update enrollments (exit/suspend/graduate)');

        Gate::policy(Programme::class, ProgrammePolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::policy(Enrollment::class, EnrollmentPolicy::class);
    }
}
