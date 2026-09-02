<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Exceptions\ProgrammeHasActiveActivities;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Illuminate\Support\Facades\DB;

/**
 * Archives and restores catalog programmes (PRD §10).
 *
 * The ONLY writer of `archived_at` / `archived_by` / `archive_reason` and of the
 * archived status. Everything the archive means — the block, the provenance, the
 * audit entry, keeping the status enum in step with the authoritative timestamp —
 * lives here, so no caller can perform half an archive.
 */
class ProgrammeArchiver
{
    /**
     * Activity states that still count as "running". Draft is included
     * deliberately: a draft activity is one an MDA is still preparing, and
     * archiving the programme out from under it destroys work in progress.
     */
    private const BLOCKING_STATUSES = [
        ActivityStatus::Draft,
        ActivityStatus::Active,
    ];

    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * @throws ProgrammeHasActiveActivities
     */
    public function archive(Programme $programme, User $actor, ?string $reason = null): Programme
    {
        if ($programme->isArchived()) {
            return $programme;
        }

        $blocking = $this->blockingActivities($programme);

        if ($blocking !== []) {
            throw new ProgrammeHasActiveActivities($programme->name, $blocking);
        }

        DB::transaction(function () use ($programme, $actor, $reason): void {
            $programme->forceFill([
                'archived_at' => now(),
                'archived_by' => $actor->id,
                'archive_reason' => $reason,
                'status' => ProgrammeStatus::Archived,
            ])->save();
        });

        $this->audit->record('programme.archived', $programme, actor: $actor);

        return $programme->fresh() ?? $programme;
    }

    /**
     * Restore an archived programme to the catalog. Reversible by design — an
     * archive is a filing decision, not a destruction, and filing decisions are
     * sometimes wrong.
     */
    public function unarchive(Programme $programme, User $actor): Programme
    {
        if (! $programme->isArchived()) {
            return $programme;
        }

        DB::transaction(function () use ($programme): void {
            $programme->forceFill([
                'archived_at' => null,
                'archived_by' => null,
                'archive_reason' => null,
                // Returns to Draft, never straight to Active: whether a restored
                // programme should immediately be runnable is a catalog-admin
                // decision, not a side effect of un-archiving.
                'status' => ProgrammeStatus::Draft,
            ])->save();
        });

        $this->audit->record('programme.unarchived', $programme, actor: $actor);

        return $programme->fresh() ?? $programme;
    }

    /**
     * Activities that block archiving, as a caller-usable list.
     *
     * MdaScope is dropped deliberately: the blocking activities usually belong to
     * OTHER MDAs, and a catalog admin scoped to their own would see an empty list,
     * archive the programme, and strand every one of them.
     *
     * @return list<array<string, mixed>>
     */
    public function blockingActivities(Programme $programme): array
    {
        return Activity::query()
            ->withoutGlobalScope(MdaScope::class)
            ->with('ownerMda:id,name')
            ->where('programme_id', $programme->id)
            ->whereIn('status', array_map(fn (ActivityStatus $s) => $s->value, self::BLOCKING_STATUSES))
            ->orderBy('name')
            ->get()
            ->map(fn (Activity $a) => [
                'field' => 'activity',
                'message' => sprintf(
                    '%s (%s) — %s',
                    $a->name,
                    $a->status->value,
                    // owner_mda_id is NOT NULL with an FK, so the relation always
                    // resolves — a fallback here would be unreachable.
                    $a->ownerMda->name,
                ),
                'activity_id' => $a->id,
            ])
            ->values()
            ->all();
    }
}
