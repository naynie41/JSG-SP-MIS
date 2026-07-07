<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Domain\Notification\Models\Notification;
use App\Domain\Notification\Models\NotificationPreference;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\UpdateNotificationPreferencesRequest;
use App\Http\Resources\NotificationResource;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The caller's own notification inbox (PRD FR-NOT-01/02). Every query is scoped to
 * `recipient_user_id = the authenticated user` (on top of the model's MDA scope),
 * so a user only ever sees, reads, or clears their own notifications.
 */
class NotificationController extends Controller
{
    /** List my notifications; `filter[status]=unread` limits to unread. */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 20), 1), 100);

        $page = $this->mine($request)
            ->when($request->input('filter.status') === 'unread', fn ($q) => $q->whereNull('read_at'))
            ->latest('created_at')
            ->paginate($perPage);

        return ApiResponse::paginated(
            NotificationResource::collection($page->items())->resolve(),
            $page,
            ['unread' => $this->mine($request)->whereNull('read_at')->count()],
        );
    }

    /** Unread count for the notification bell. */
    public function unreadCount(Request $request): JsonResponse
    {
        return ApiResponse::success(['unread' => $this->mine($request)->whereNull('read_at')->count()]);
    }

    /** Mark one of my notifications read. */
    public function markRead(Request $request, string $notification): JsonResponse
    {
        $model = $this->mine($request)->findOrFail($notification);

        if ($model->read_at === null) {
            $model->update(['read_at' => now()]);
        }

        return ApiResponse::success((new NotificationResource($model))->resolve());
    }

    /** Mark all my notifications read. */
    public function markAllRead(Request $request): JsonResponse
    {
        $count = $this->mine($request)->whereNull('read_at')->update(['read_at' => now()]);

        return ApiResponse::success(['marked_read' => $count]);
    }

    public function preferences(Request $request): JsonResponse
    {
        $pref = NotificationPreference::query()->where('user_id', $request->user()->id)->first();

        return ApiResponse::success(['email_enabled' => $pref === null ? true : $pref->email_enabled]);
    }

    public function updatePreferences(UpdateNotificationPreferencesRequest $request): JsonResponse
    {
        $pref = NotificationPreference::query()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['email_enabled' => $request->boolean('email_enabled')],
        );

        return ApiResponse::success(['email_enabled' => $pref->email_enabled]);
    }

    /**
     * Base query scoped to the authenticated recipient.
     *
     * @return Builder<Notification>
     */
    private function mine(Request $request): Builder
    {
        return Notification::query()->where('recipient_user_id', $request->user()->id);
    }
}
