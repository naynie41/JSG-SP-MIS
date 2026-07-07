<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Grievance\Enums\GrievanceCategory;
use App\Domain\Grievance\Enums\GrievanceChannel;
use App\Domain\Grievance\Enums\GrievanceStatus;
use App\Domain\Grievance\Events\GrievanceAssigned;
use App\Domain\Grievance\Events\GrievanceResolved;
use App\Domain\Grievance\Exceptions\InvalidGrievanceTransitionException;
use App\Domain\Grievance\Models\Grievance;
use Illuminate\Support\Carbon;

/**
 * Grievance capture + lifecycle (PRD FR-GRM-01/02, §8.4). Staff log grievances on
 * behalf of beneficiaries; assignment + each transition are guarded by
 * {@see GrievanceStatus::canTransitionTo()}, timestamped, and audited explicitly.
 * The WHO (handling-MDA staff) is enforced by the policy; this owns the WHAT.
 */
class GrievanceService
{
    public function __construct(private readonly AuditLogger $audit) {}

    /** Staff logs a grievance on behalf of a beneficiary (optional link). */
    public function create(User $staff, GrievanceCategory $category, GrievanceChannel $channel, string $description, ?string $beneficiaryId): Grievance
    {
        // Auditable records grievance.created.
        return Grievance::create([
            'handling_mda_id' => $staff->mda_id,
            'beneficiary_id' => $beneficiaryId,
            'category' => $category,
            'channel' => $channel,
            'description' => $description,
            'status' => GrievanceStatus::Open,
            'submitted_by' => $staff->id,
        ]);
    }

    /** Assign (or reassign) to a user in the handling MDA; Open → Assigned. */
    public function assign(Grievance $grievance, User $assignee, User $actor): Grievance
    {
        if (! in_array($grievance->status, [GrievanceStatus::Open, GrievanceStatus::Assigned, GrievanceStatus::InProgress], true)) {
            throw new InvalidGrievanceTransitionException("A {$grievance->status->value} grievance cannot be assigned.");
        }
        if ($assignee->mda_id !== $grievance->handling_mda_id) {
            throw new InvalidGrievanceTransitionException('The assignee must belong to the handling MDA.');
        }

        $grievance->update([
            'assignee_user_id' => $assignee->id,
            'assigned_at' => Carbon::now(),
            'status' => $grievance->status === GrievanceStatus::Open ? GrievanceStatus::Assigned : $grievance->status,
        ]);

        $this->audit->record('grievance.assigned', $grievance, after: [
            'status' => $grievance->status->value,
            'assignee_user_id' => $assignee->id,
        ], actor: $actor);

        GrievanceAssigned::dispatch($grievance, $assignee, $actor);

        return $grievance;
    }

    public function start(Grievance $grievance, User $actor): Grievance
    {
        return $this->transition($grievance, GrievanceStatus::InProgress, 'started', $actor, ['started_at' => Carbon::now()]);
    }

    public function resolve(Grievance $grievance, User $actor, string $notes): Grievance
    {
        return $this->transition($grievance, GrievanceStatus::Resolved, 'resolved', $actor, [
            'resolved_at' => Carbon::now(),
            'resolution_notes' => $notes,
        ], onDone: fn (Grievance $g) => GrievanceResolved::dispatch($g, $actor));
    }

    public function close(Grievance $grievance, User $actor, ?string $notes): Grievance
    {
        $changes = ['closed_at' => Carbon::now()];
        if ($notes !== null) {
            $changes['resolution_notes'] = $notes;
        }

        return $this->transition($grievance, GrievanceStatus::Closed, 'closed', $actor, $changes);
    }

    /**
     * @param  array<string, mixed>  $changes
     * @param  (callable(Grievance): void)|null  $onDone
     */
    private function transition(Grievance $grievance, GrievanceStatus $to, string $action, User $actor, array $changes, ?callable $onDone = null): Grievance
    {
        if (! $grievance->status->canTransitionTo($to)) {
            throw new InvalidGrievanceTransitionException("A grievance in '{$grievance->status->value}' cannot be {$action}.");
        }

        $from = $grievance->status;
        $grievance->update(['status' => $to, ...$changes]);

        $meaningful = array_filter($changes, static fn (string $key): bool => ! str_ends_with($key, '_at'), ARRAY_FILTER_USE_KEY);
        $this->audit->record('grievance.'.$action, $grievance, before: ['status' => $from->value], after: [
            'status' => $to->value,
            ...$meaningful,
        ], actor: $actor);

        if ($onDone !== null) {
            $onDone($grievance);
        }

        return $grievance;
    }
}
