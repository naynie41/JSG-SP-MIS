<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Exceptions\ProgrammeAssignmentInUse;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Which catalog programmes an MDA may deliver (§10, revised 2026-11-05).
 *
 * The state catalog holds 115 programmes; no MDA runs more than nineteen of them. An
 * administrator ASSIGNS the ones each MDA actually offers, and every place an MDA
 * selects a programme — activity creation, benefit recording, both import paths,
 * auto-route confirmation — is narrowed to that assignment. Before this, all of them
 * offered the entire catalog, so an officer at Health scrolled past the agriculture
 * inventory to find a health programme.
 *
 * This service is the ONE implementation of three questions that must never disagree:
 * whether a caller's catalog narrows at all, which programmes it narrows to, and
 * whether a specific programme is among them. They are asked from a controller and
 * from validation rules, which is exactly the split where two implementations drift
 * and the picker ends up showing what the API then refuses.
 *
 * What it deliberately is NOT:
 *
 *  - **Not ownership.** An assigned programme is still a central catalog entry
 *    (`owner_mda_id` NULL) that any number of MDAs may be assigned. §10 keeps "one
 *    programme, many MDAs, separate activities".
 *  - **Not a second approval queue.** Assignment is administrator-push only
 *    (stakeholder decision, 2026-11-05). An MDA that wants a programme it has not been
 *    assigned either asks an administrator, or creates one of its own and submits it —
 *    which is the pre-existing approval flow, untouched by this.
 *  - **Not a replacement for the approval gate.** An MDA's own programme still waits
 *    for the System Administrator; approval is what writes its assignment row.
 */
class ProgrammeAssignmentService
{
    /**
     * Activity states that still count as work in progress, so that unassigning
     * would strand them. Draft is included for the reason {@see ProgrammeArchiver}
     * includes it: a draft activity is one an officer is still preparing.
     */
    private const BLOCKING_STATUSES = [
        ActivityStatus::Draft,
        ActivityStatus::Active,
    ];

    public function __construct(private readonly AuditLogger $audit) {}

    /* ------------------------------------------------------------------ reading */

    /**
     * Does this caller see a catalog narrowed to assignments?
     *
     * Three kinds of caller do not, each for its own reason:
     *
     *  - **Oversight** (`cross-mda.view`: System Administrator, SP Coordination, M&E,
     *    Executive) — they administer or report on the whole catalog, and a narrowed
     *    view would hide what they are responsible for.
     *  - **No MDA at all** — there is nothing to narrow to; a state-level account
     *    without an organisation would otherwise see an empty catalog.
     *  - **A PARTNER organisation.** A Development Partner funds across MDAs and holds
     *    no assignments of its own, so narrowing would empty every list it reads. The
     *    question this gate answers is "what does this government body deliver", which
     *    is not a question about a funder.
     */
    public function narrows(?User $user): bool
    {
        if (! $user instanceof User || $user->canAccessAllMdas() || $user->mda_id === null) {
            return false;
        }

        // Without the scope: a user may always read their OWN MDA, but relying on the
        // scope to allow it makes this answer depend on a second rule.
        $mda = Mda::query()->withoutGlobalScope(MdaScope::class)->find($user->mda_id);

        return $mda !== null && $mda->isGovernment();
    }

    /**
     * The programme ids assigned to an MDA.
     *
     * Read off the pivot table directly rather than through the relation: the related
     * Programme is MDA-scoped and archive-filtered, and this is the one place that
     * wants the raw assignment — the caller decides what to do with an id whose
     * programme is archived or still pending.
     *
     * @return list<string>
     */
    public function assignedProgrammeIds(string $mdaId): array
    {
        return DB::table('mda_programme')
            ->where('mda_id', $mdaId)
            ->pluck('programme_id')
            ->map(fn (mixed $id) => (string) $id)
            ->values()
            ->all();
    }

    /**
     * Narrow a programme query to what this caller may deliver. A no-op for every
     * caller {@see narrows()} returns false for, so it is safe to apply unconditionally.
     *
     * An MDA's OWN programme is included whether or not an assignment row exists yet:
     * one that is still pending has none (approval writes it), and without this the
     * author of a submission could not see the thing they had just submitted.
     *
     * @param  Builder<Programme>  $query
     * @return Builder<Programme>
     */
    public function narrow(Builder $query, ?User $user): Builder
    {
        if (! $this->narrows($user)) {
            return $query;
        }

        $mdaId = (string) $user->mda_id;
        $assigned = $this->assignedProgrammeIds($mdaId);

        return $query->where(fn (Builder $inner) => $inner
            ->whereIn($inner->qualifyColumn('id'), $assigned)
            ->orWhere($inner->qualifyColumn('owner_mda_id'), $mdaId));
    }

    /**
     * May this caller use this programme? The question the validation rules ask.
     *
     * Takes the programme id rather than the model so a rule can ask before loading
     * anything, and so an id the caller cannot see answers false rather than throwing.
     */
    public function mayUse(?User $user, string $programmeId, ?string $ownerMdaId = null): bool
    {
        if (! $this->narrows($user)) {
            return true;
        }

        $mdaId = (string) $user->mda_id;

        // Its own programme: usable once approved, which the caller checks separately.
        if ($ownerMdaId !== null && $ownerMdaId === $mdaId) {
            return true;
        }

        return DB::table('mda_programme')
            ->where('mda_id', $mdaId)
            ->where('programme_id', $programmeId)
            ->exists();
    }

    /* ------------------------------------------------------------------ writing */

    /**
     * Set an MDA's assignments to exactly this list of programme ids.
     *
     * Whole-list rather than add/remove because that is what the console sends: an
     * administrator ticks boxes and saves, and a diff computed in the browser would be
     * wrong the moment two administrators had the page open at once.
     *
     * Rows this service did not create are preserved as they are. An inventory row or
     * an approval row that stays in the list keeps its `source`, so re-saving the page
     * cannot quietly rewrite where an assignment came from.
     *
     * @param  list<string>  $programmeIds
     * @return array{assigned: list<string>, unassigned: list<string>, unchanged: int}
     *
     * @throws ProgrammeAssignmentInUse
     */
    public function sync(Mda $mda, array $programmeIds, User $actor): array
    {
        $wanted = array_values(array_unique($programmeIds));
        $current = $this->assignedProgrammeIds($mda->id);

        $toAdd = array_values(array_diff($wanted, $current));
        $toRemove = array_values(array_diff($current, $wanted));

        if ($toRemove !== []) {
            $blocking = $this->blockingActivities($mda, $toRemove);

            if ($blocking !== []) {
                throw new ProgrammeAssignmentInUse($mda->name, $blocking);
            }
        }

        if ($toAdd === [] && $toRemove === []) {
            return ['assigned' => [], 'unassigned' => [], 'unchanged' => count($current)];
        }

        DB::transaction(function () use ($mda, $toAdd, $toRemove, $actor): void {
            if ($toRemove !== []) {
                DB::table('mda_programme')
                    ->where('mda_id', $mda->id)
                    ->whereIn('programme_id', $toRemove)
                    ->delete();
            }

            $now = now();

            foreach (array_chunk($toAdd, 200) as $chunk) {
                DB::table('mda_programme')->insert(array_map(fn (string $programmeId) => [
                    'id' => (string) Str::uuid(),
                    'mda_id' => $mda->id,
                    'programme_id' => $programmeId,
                    // An administrator's decision: a stronger claim than the seeded
                    // inventory, and a different one from an MDA's own approval.
                    'source' => 'assignment',
                    'assigned_by' => $actor->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $chunk));
            }
        });

        $this->audit->record(
            'mda.programmes_assigned',
            $mda,
            before: ['programme_ids' => $current],
            after: [
                'programme_ids' => $wanted,
                'assigned' => $toAdd,
                'unassigned' => $toRemove,
            ],
            actor: $actor,
        );

        return [
            'assigned' => $toAdd,
            'unassigned' => $toRemove,
            'unchanged' => count(array_intersect($current, $wanted)),
        ];
    }

    /**
     * Take one programme away from one MDA. The single-row path the console's remove
     * button uses, with the same refusal as {@see sync()} — a narrower call must not
     * be a way around a guard.
     *
     * @throws ProgrammeAssignmentInUse
     */
    public function detach(Mda $mda, Programme $programme, User $actor): bool
    {
        $blocking = $this->blockingActivities($mda, [$programme->id]);

        if ($blocking !== []) {
            throw new ProgrammeAssignmentInUse($mda->name, $blocking);
        }

        $deleted = DB::table('mda_programme')
            ->where('mda_id', $mda->id)
            ->where('programme_id', $programme->id)
            ->delete();

        if ($deleted === 0) {
            return false;
        }

        $this->audit->record(
            'mda.programmes_assigned',
            $mda,
            before: ['programme_ids' => [$programme->id]],
            after: ['programme_ids' => [], 'unassigned' => [$programme->id]],
            actor: $actor,
        );

        return true;
    }

    /**
     * The activities that stop these programmes being unassigned from this MDA, as a
     * list the response can name.
     *
     * MdaScope is dropped deliberately. An administrator holds `cross-mda.view` so the
     * scope would not bite today, but this guard protects ANOTHER organisation's work:
     * it must not depend on the caller happening to be able to see it.
     *
     * @param  list<string>  $programmeIds
     * @return list<array<string, mixed>>
     */
    public function blockingActivities(Mda $mda, array $programmeIds): array
    {
        if ($programmeIds === []) {
            return [];
        }

        return Activity::query()
            ->withoutGlobalScope(MdaScope::class)
            ->with('programme:id,name')
            ->where('owner_mda_id', $mda->id)
            ->whereIn('programme_id', $programmeIds)
            ->whereIn('status', array_map(fn (ActivityStatus $s) => $s->value, self::BLOCKING_STATUSES))
            ->orderBy('name')
            ->get()
            ->map(fn (Activity $activity) => [
                'field' => 'programme_id',
                'message' => sprintf(
                    '%s (%s) — under %s',
                    $activity->name,
                    $activity->status->value,
                    // programme_id is NOT NULL with an FK, so the relation resolves.
                    $activity->programme->name,
                ),
                'activity_id' => $activity->id,
                'programme_id' => $activity->programme_id,
            ])
            ->values()
            ->all();
    }
}
