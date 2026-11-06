<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\ProgrammeAssignmentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programme\SyncMdaProgrammesRequest;
use App\Http\Resources\ProgrammeResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Assigning catalog programmes to an MDA (§10, revised 2026-11-05).
 *
 * The state catalog is shared, but no MDA delivers all of it. An administrator records
 * which programmes each organisation actually offers, and that assignment is what every
 * MDA-facing programme picker is built from. Composed onto the EXISTING `/mdas/{mda}`
 * resource rather than given a catalog of its own, for the reason the admin console
 * already composes `/programmes`: there is one catalog, read different ways.
 *
 * Administrator-push only — there is no endpoint here for an MDA to assign itself a
 * programme, or to request one. An MDA that wants a programme it has not been assigned
 * creates one of its own and submits it for approval, which is the pre-existing flow and
 * is untouched (`POST /programmes`, then `programmes.approve`).
 */
class MdaProgrammeAssignmentController extends Controller
{
    /**
     * The programmes this MDA is assigned, with the provenance of each assignment —
     * seeded from the inventory, derived from existing activities, written by an
     * approval, or chosen by an administrator. An administrator about to untick
     * something should be able to see where it came from.
     */
    public function index(Request $request, string $mda): JsonResponse
    {
        $this->authorize('assign', Programme::class);

        $organisation = $this->organisation($mda);

        $programmes = $organisation->assignedProgrammes()
            // withArchived: an assignment outlives the programme's own lifecycle, and
            // hiding the archived ones here would make them look unassigned while the
            // row still exists — then silently drop them on the next save.
            ->withArchived()
            ->withCount(['activities as activities_count' => fn ($query) => $query
                ->withoutGlobalScope(MdaScope::class)
                ->where('owner_mda_id', $organisation->id)])
            ->orderBy('name')
            ->get();

        return ApiResponse::success([
            'mda' => ['id' => $organisation->id, 'name' => $organisation->name],
            'programmes' => ProgrammeResource::collection($programmes)->resolve(),
        ]);
    }

    /**
     * Replace this MDA's assignments with the submitted list.
     *
     * ProgrammeAssignmentInUse is Responsable, so Laravel renders its 409 with the
     * blocking activities named — no try/catch, the same as programme archiving.
     */
    public function sync(SyncMdaProgrammesRequest $request, string $mda, ProgrammeAssignmentService $assignments): JsonResponse
    {
        $this->authorize('assign', Programme::class);

        $organisation = $this->organisation($mda);

        /** @var list<string> $ids */
        $ids = array_values(array_map('strval', $request->validated('programme_ids')));

        $result = $assignments->sync($organisation, $ids, $request->user());

        return ApiResponse::success([
            'mda' => ['id' => $organisation->id, 'name' => $organisation->name],
            'assigned' => $result['assigned'],
            'unassigned' => $result['unassigned'],
            'unchanged' => $result['unchanged'],
            'total' => count($ids),
        ]);
    }

    /** Take one programme away from one MDA. Same refusal as a save that unticks it. */
    public function destroy(Request $request, string $mda, string $programme, ProgrammeAssignmentService $assignments): JsonResponse
    {
        $this->authorize('assign', Programme::class);

        $organisation = $this->organisation($mda);
        $entry = Programme::query()->withArchived()->findOrFail($programme);

        $removed = $assignments->detach($organisation, $entry, $request->user());

        return ApiResponse::success([
            'mda' => ['id' => $organisation->id, 'name' => $organisation->name],
            'programme_id' => $entry->id,
            // False means it was not assigned in the first place. Reported rather than
            // raised: the end state the caller asked for is the end state they have.
            'removed' => $removed,
        ]);
    }

    /**
     * The MDA being administered, resolved WITHOUT the MDA scope.
     *
     * Assigning is cross-organisational by nature — an administrator assigns to MDAs
     * other than their own, and most hold no `mda_id` at all. The boundary is the
     * policy above, checked before this runs, so dropping the scope here cannot widen
     * who may do this; it only stops a legitimate administrator getting a 404 for an
     * organisation they are entitled to configure.
     */
    private function organisation(string $mda): Mda
    {
        return Mda::query()->withoutGlobalScope(MdaScope::class)->findOrFail($mda);
    }
}
