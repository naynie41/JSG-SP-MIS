<?php

declare(strict_types=1);

namespace App\Domain\Notification\Services;

use App\Domain\Access\Models\User;
use App\Domain\Notification\Contracts\NotificationChannel;
use App\Domain\Notification\Models\NotificationPreference;
use App\Domain\Notification\Support\NotificationMessage;

/**
 * Fans a {@see NotificationMessage} out to recipients across the registered
 * channels (PRD FR-NOT-01/02). A channel is used only when it is **available**
 * (stubs are skipped) and the recipient's preferences allow it. In-app is always
 * delivered — it is the inbox. Recipients are de-duplicated.
 */
class Notifier
{
    /**
     * @param  iterable<NotificationChannel>  $channels
     */
    public function __construct(private readonly iterable $channels) {}

    /**
     * @param  iterable<User|null>  $recipients
     */
    public function notify(NotificationMessage $message, iterable $recipients): void
    {
        $seen = [];
        foreach ($recipients as $recipient) {
            if ($recipient === null || isset($seen[$recipient->id])) {
                continue;
            }
            $seen[$recipient->id] = true;

            foreach ($this->channels as $channel) {
                if (! $channel->isAvailable() || ! $this->allows($recipient, $channel->key())) {
                    continue;
                }
                $channel->send($message, $recipient);
            }
        }
    }

    /** Whether the recipient's preferences permit this channel (in-app is always on). */
    private function allows(User $recipient, string $channelKey): bool
    {
        if ($channelKey === 'in_app') {
            return true;
        }

        if ($channelKey === 'email') {
            $preference = NotificationPreference::query()->where('user_id', $recipient->id)->first();

            return $preference === null || $preference->email_enabled;
        }

        return true; // stubbed channels are already gated by isAvailable()
    }
}
