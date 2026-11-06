<?php

declare(strict_types=1);

namespace App\Domain\Programme\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use RuntimeException;

/**
 * Refuses to take a programme away from an MDA that is still running it (§10,
 * revised 2026-11-05).
 *
 * The same argument as {@see ProgrammeHasActiveActivities}, one level out. Removing
 * the assignment does not delete the activities, which is exactly the problem: they
 * would go on existing under a programme the MDA can no longer see or select, so the
 * officer responsible for them cannot edit, report on or close them out.
 *
 * Only DRAFT and ACTIVE activities block. A completed or archived activity is history,
 * and history stays readable through the programme's own detail view whether or not the
 * assignment stands — an administrator ending an MDA's involvement in a finished
 * programme is doing something legitimate, and should not have to archive the past
 * first.
 */
class ProgrammeAssignmentInUse extends RuntimeException implements Responsable
{
    /**
     * @param  list<array<string, mixed>>  $blocking
     */
    public function __construct(
        private readonly string $mdaName,
        private readonly array $blocking,
    ) {
        parent::__construct(sprintf(
            '%s still runs %d %s under programmes being unassigned.',
            $mdaName,
            count($blocking),
            count($blocking) === 1 ? 'activity' : 'activities',
        ));
    }

    public function toResponse($request): JsonResponse
    {
        $count = count($this->blocking);

        return ApiResponse::error(
            'PROGRAMME_ASSIGNMENT_IN_USE',
            sprintf(
                '%s cannot be unassigned from %s: %d %s still %s under %s. Complete or archive %s first.',
                $count === 1 ? 'That programme' : 'Those programmes',
                $this->mdaName,
                $count,
                $count === 1 ? 'activity' : 'activities',
                $count === 1 ? 'runs' : 'run',
                $count === 1 ? 'it' : 'them',
                $count === 1 ? 'it' : 'them',
            ),
            $this->blocking,
            409,
        );
    }
}
