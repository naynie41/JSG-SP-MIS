<?php

declare(strict_types=1);

namespace App\Domain\Programme\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use RuntimeException;

/**
 * Refuses to archive a programme that MDAs are still running (PRD §10).
 *
 * Archiving would strand those activities against a catalog entry nobody can see or
 * select, so the blocking activities are named in the response — an MDA cannot act on
 * "some activities exist", and the person archiving usually does not own them.
 */
class ProgrammeHasActiveActivities extends RuntimeException implements Responsable
{
    /**
     * @param  list<array<string, mixed>>  $activities
     */
    public function __construct(
        private readonly string $programmeName,
        private readonly array $activities,
    ) {
        parent::__construct(sprintf(
            '"%s" still has %d active %s.',
            $programmeName,
            count($activities),
            count($activities) === 1 ? 'activity' : 'activities',
        ));
    }

    public function toResponse($request): JsonResponse
    {
        return ApiResponse::error(
            'PROGRAMME_HAS_ACTIVE_ACTIVITIES',
            sprintf(
                '"%s" cannot be archived: %d active %s still run under it. Archive or complete %s first.',
                $this->programmeName,
                count($this->activities),
                count($this->activities) === 1 ? 'activity' : 'activities',
                count($this->activities) === 1 ? 'it' : 'them',
            ),
            $this->activities,
            409,
        );
    }
}
