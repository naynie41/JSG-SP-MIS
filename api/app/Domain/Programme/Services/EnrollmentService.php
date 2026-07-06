<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Services\ServiceRequestService;
use Illuminate\Database\UniqueConstraintViolationException;

/**
 * Enrollment/assignment of already-registered beneficiaries or households into a
 * programme (PRD FR-PRG-03). Enforces the serve seam (a non-owner MDA may enroll a
 * beneficiary it serves — never taking ownership), the advisory/enforced
 * eligibility check, and the single-open-enrollment rule. Read/creates only —
 * never registers a beneficiary and never changes ownership.
 */
class EnrollmentService
{
    public function __construct(private readonly EligibilityEvaluator $eligibility) {}

    /**
     * Whether the acting user's MDA may enroll (serve) this target: it owns the
     * target, holds a cross-MDA grant covering the owner, or — for a beneficiary —
     * holds an active read/serve grant opened by an ACCEPTED Service Request
     * (§12, FR-OWN-06/07). Serving a non-owned beneficiary requires that grant.
     */
    public function canServe(User $actor, Beneficiary|Household $target): bool
    {
        if (in_array($target->owner_mda_id, $actor->accessibleMdaIds(), true)) {
            return true;
        }

        if ($target instanceof Beneficiary && $actor->mda_id !== null) {
            return ServiceRequestService::hasActiveGrant($target->id, $actor->mda_id);
        }

        return false;
    }

    /**
     * Enroll a single target into a programme. Returns the outcome; nothing is
     * created on a non-`enrolled` outcome.
     *
     * @return array{status: 'enrolled'|'skipped'|'rejected', reason: ?string, enrollment: ?Enrollment, unmet: list<string>}
     */
    public function enroll(Programme $programme, Beneficiary|Household $target, ?string $activityId, User $actor): array
    {
        if (! $this->canServe($actor, $target)) {
            return $this->outcome('rejected', 'no_serve_access');
        }

        if ($this->hasOpenEnrollment($programme, $target)) {
            return $this->outcome('skipped', 'already_enrolled');
        }

        $result = $this->eligibility->evaluate($programme->eligibility, $target);
        if (! $result['eligible'] && $programme->enforce_eligibility) {
            return $this->outcome('rejected', 'ineligible', unmet: $result['unmet']);
        }

        $isBeneficiary = $target instanceof Beneficiary;

        try {
            $enrollment = Enrollment::create([
                'programme_id' => $programme->id,
                'activity_id' => $activityId,
                'mda_id' => $programme->owner_mda_id, // the enrolling MDA (programme owner)
                'beneficiary_id' => $isBeneficiary ? $target->id : null,
                'household_id' => $isBeneficiary ? null : $target->id,
                'status' => EnrollmentStatus::Enrolled,
                'enrolled_on' => now()->toDateString(),
                'eligibility_flagged' => ! $result['eligible'],
                'eligibility_notes' => $result['eligible'] ? null : $result['unmet'],
                'enrolled_by' => $actor->id,
            ]);
        } catch (UniqueConstraintViolationException) {
            // Raced against a concurrent enrollment of the same target.
            return $this->outcome('skipped', 'already_enrolled');
        }

        return $this->outcome('enrolled', null, $enrollment, $result['unmet']);
    }

    private function hasOpenEnrollment(Programme $programme, Beneficiary|Household $target): bool
    {
        $column = $target instanceof Beneficiary ? 'beneficiary_id' : 'household_id';

        return Enrollment::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('programme_id', $programme->id)
            ->where($column, $target->id)
            ->where('status', EnrollmentStatus::Enrolled)
            ->exists();
    }

    /**
     * @param  list<string>  $unmet
     * @return array{status: 'enrolled'|'skipped'|'rejected', reason: ?string, enrollment: ?Enrollment, unmet: list<string>}
     */
    private function outcome(string $status, ?string $reason, ?Enrollment $enrollment = null, array $unmet = []): array
    {
        return ['status' => $status, 'reason' => $reason, 'enrollment' => $enrollment, 'unmet' => $unmet];
    }
}
