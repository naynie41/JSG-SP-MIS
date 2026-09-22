<?php

declare(strict_types=1);

namespace App\Domain\Programme\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use RuntimeException;

/**
 * Refuses to complete or archive an activity that still owes someone a decision.
 *
 * The blocking case is a pending request-to-serve: another MDA has asked to deliver to
 * a beneficiary this activity owns, and nobody has answered. Filing the activity away
 * would leave that request in a queue attached to something no longer being worked,
 * which is how a request quietly waits forever.
 *
 * Named counts rather than "this activity has pending work", because the person acting
 * has to be able to go and clear them — and on the scheduled path, because the log has
 * to say what it skipped and why.
 */
class ActivityHasPendingWork extends RuntimeException implements Responsable
{
    public function __construct(
        private readonly string $activityName,
        private readonly int $pendingServiceRequests,
    ) {
        parent::__construct(sprintf(
            '"%s" still has %d request-to-serve %s awaiting a decision.',
            $activityName,
            $pendingServiceRequests,
            $pendingServiceRequests === 1 ? 'decision' : 'decisions',
        ));
    }

    public function pendingServiceRequests(): int
    {
        return $this->pendingServiceRequests;
    }

    public function toResponse($request): JsonResponse
    {
        return ApiResponse::error(
            'ACTIVITY_HAS_PENDING_WORK',
            $this->getMessage(),
            [[
                'field' => 'service_requests',
                'message' => 'Accept or decline the outstanding requests first, then try again.',
            ]],
            422,
        );
    }
}
