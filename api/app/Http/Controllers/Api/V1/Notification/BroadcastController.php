<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Domain\Access\Models\User;
use App\Domain\Notification\Services\BroadcastService;
use App\Domain\Notification\Services\Notifier;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\BroadcastRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * System-wide announcements from the administration console (FR-NOT-01). Delivery runs
 * through the existing Phase 5 {@see Notifier}, so
 * channel availability and recipient preferences are honoured unchanged.
 */
class BroadcastController extends Controller
{
    public function __construct(private readonly BroadcastService $broadcasts) {}

    /** How many active users a given audience filter reaches (pre-send confirmation). */
    public function audience(Request $request): JsonResponse
    {
        $count = $this->broadcasts->audienceCount(
            $request->query('role_key') !== null ? (string) $request->query('role_key') : null,
            $request->query('mda_id') !== null ? (string) $request->query('mda_id') : null,
        );

        return ApiResponse::success(['recipient_count' => $count]);
    }

    public function store(BroadcastRequest $request): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $data = $request->validated();

        $count = $this->broadcasts->send(
            (string) $data['subject'],
            isset($data['body']) ? (string) $data['body'] : null,
            [
                'role_key' => isset($data['role_key']) ? (string) $data['role_key'] : null,
                'mda_id' => isset($data['mda_id']) ? (string) $data['mda_id'] : null,
            ],
            $actor,
        );

        return ApiResponse::success([
            'recipient_count' => $count,
            'message' => "Broadcast sent to {$count} ".($count === 1 ? 'recipient' : 'recipients').'.',
        ], status: 201);
    }
}
