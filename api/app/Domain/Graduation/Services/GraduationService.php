<?php

declare(strict_types=1);

namespace App\Domain\Graduation\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Graduation\Events\BeneficiaryGraduated;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Graduation\Models\GraduationEvent;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use DomainException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Records a graduation (FR-GRD-02). It flips the ENROLMENT status to graduated and
 * writes a permanent {@see GraduationEvent} — but NEVER deletes or alters the
 * beneficiary or their benefit ledger; the full history is preserved. An explicit
 * officer action (nothing is ever silently auto-graduated); every graduation is
 * audited and notifies the relevant parties.
 */
class GraduationService
{
    public function __construct(
        private readonly AuditLogger $audit,
        private readonly GraduationProgressService $progress,
    ) {}

    public function graduate(Enrollment $enrollment, ?string $reason, ?User $actor): GraduationEvent
    {
        if ($enrollment->status === EnrollmentStatus::Graduated) {
            throw new DomainException('This enrolment has already graduated.');
        }

        $criteria = $this->progress->activeCriteria($enrollment);

        $event = DB::transaction(function () use ($enrollment, $reason, $actor, $criteria): GraduationEvent {
            // The enrolment's status changes — the beneficiary + ledger are untouched.
            $enrollment->update(['status' => EnrollmentStatus::Graduated]);

            return GraduationEvent::create([
                'enrollment_id' => $enrollment->id,
                'beneficiary_id' => $enrollment->beneficiary_id,
                'household_id' => $enrollment->household_id,
                'programme_id' => $enrollment->programme_id,
                'activity_id' => $enrollment->activity_id,
                'mda_id' => $enrollment->mda_id,
                'criteria_id' => $criteria?->id,
                'reason' => $reason,
                'decided_by' => $actor?->id,
                'graduated_at' => Carbon::now(),
            ]);
        });

        $this->audit->record('graduation.recorded', $event, after: [
            'enrollment_id' => $enrollment->id,
            'beneficiary_id' => $enrollment->beneficiary_id,
            'programme_id' => $enrollment->programme_id,
            'criteria_id' => $criteria?->id,
            'reason' => $reason,
        ], actor: $actor);

        BeneficiaryGraduated::dispatch($event, $actor);

        return $event;
    }

    /** Deactivate any other active criteria set for the same (programme, MDA). */
    public function activateExclusively(GraduationCriteria $criteria): void
    {
        GraduationCriteria::query()
            ->where('programme_id', $criteria->programme_id)
            ->where('owner_mda_id', $criteria->owner_mda_id)
            ->where('id', '!=', $criteria->id)
            ->update(['is_active' => false]);
    }
}
