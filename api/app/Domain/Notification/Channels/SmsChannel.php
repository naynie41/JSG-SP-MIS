<?php

declare(strict_types=1);

namespace App\Domain\Notification\Channels;

use App\Domain\Access\Models\User;
use App\Domain\Notification\Contracts\NotificationChannel;
use App\Domain\Notification\Exceptions\ChannelUnavailableException;
use App\Domain\Notification\Support\NotificationMessage;

/**
 * SMS channel — STUBBED. Unavailable until an external SMS gateway is provided;
 * it never fabricates a send (CLAUDE §8). Wire a real client in `send()` and flip
 * `isAvailable()` once credentials exist.
 */
class SmsChannel implements NotificationChannel
{
    public function key(): string
    {
        return 'sms';
    }

    public function isAvailable(): bool
    {
        return false; // needs an external SMS gateway
    }

    public function send(NotificationMessage $message, User $recipient): void
    {
        throw new ChannelUnavailableException('SMS delivery is not available — configure an external SMS gateway first.');
    }
}
