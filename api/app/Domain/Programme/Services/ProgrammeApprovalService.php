<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Events\ProgrammeApproved;
use App\Domain\Programme\Events\ProgrammeRejected;
use App\Domain\Programme\Events\ProgrammeSubmitted;
use App\Domain\Programme\Models\Programme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * The decision on an MDA's own programme (revises PRD §10).
 *
 * The state change, the participation record and the event all belong together: an
 * approval that forgot the `mda_programme` row would leave the owning MDA's console
 * saying it does not run the programme it just had approved, and one that forgot the
 * event would leave the MDA waiting for a decision that had already been made.
 */
class ProgrammeApprovalService
{
    /** Put it in front of the System Administrator (on create, or after a rejection). */
    public function submit(Programme $programme, ?User $actor = null): Programme
    {
        $programme->submitForApproval();

        ProgrammeSubmitted::dispatch($programme, $actor);

        return $programme;
    }

    /**
     * Clear it for use. The MDA is recorded as RUNNING it in the same breath, with
     * `source: 'mda'` — a programme the MDA declared itself is a stronger claim than
     * a seeded inventory row, and the difference stays visible.
     */
    public function approve(Programme $programme, User $approver, ?string $note = null): Programme
    {
        DB::transaction(function () use ($programme, $approver, $note): void {
            $programme->approveBy($approver, $note);
            $this->recordParticipation($programme);
        });

        ProgrammeApproved::dispatch($programme, $approver);

        return $programme;
    }

    /** Send it back with a reason the owning MDA can act on. */
    public function reject(Programme $programme, User $approver, string $reason): Programme
    {
        $programme->rejectBy($approver, $reason);

        ProgrammeRejected::dispatch($programme, $approver);

        return $programme;
    }

    /**
     * Record that the owning MDA runs this programme. Idempotent in the database
     * (unique on mda_id + programme_id); the read here only avoids using an
     * exception for control flow, as the catalog seeder does.
     */
    private function recordParticipation(Programme $programme): void
    {
        if ($programme->owner_mda_id === null) {
            return; // a central entry is run by whoever creates activities under it
        }

        $exists = DB::table('mda_programme')
            ->where('mda_id', $programme->owner_mda_id)
            ->where('programme_id', $programme->id)
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('mda_programme')->insert([
            'id' => (string) Str::uuid(),
            'mda_id' => $programme->owner_mda_id,
            'programme_id' => $programme->id,
            'source' => 'mda',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
