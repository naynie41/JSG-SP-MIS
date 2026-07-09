<?php

declare(strict_types=1);

namespace App\Domain\Programme\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;

/**
 * Authorization for enrollments (PRD FR-PRG-03, §10). Any MDA user may enroll into
 * a GLOBAL catalog programme — the enrolling MDA is their own MDA. Whether they may
 * enroll a specific non-owned beneficiary is the serve-seam check in
 * EnrollmentService. Oversight (`cross-mda.view`) reads across MDAs but never enrolls.
 */
class EnrollmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('enrollment.view');
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->hasPermission('enrollment.view')
            && ($user->mda_id === $enrollment->mda_id || $user->canAccessAllMdas());
    }

    /** Enroll into a catalog programme — any MDA user; the enrolling MDA is their own. */
    public function create(User $user, Programme $programme): bool
    {
        return $user->hasPermission('enrollment.create') && $user->mda_id !== null;
    }

    /** Update an enrollment (exit/suspend/graduate) — the enrolling MDA only. */
    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->hasPermission('enrollment.edit') && $user->mda_id === $enrollment->mda_id;
    }
}
