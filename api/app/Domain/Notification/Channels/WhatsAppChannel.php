<?php

declare(strict_types=1);

namespace App\Domain\Notification\Channels;

use App\Domain\Access\Models\User;
use App\Domain\Notification\Contracts\NotificationChannel;
use App\Domain\Notification\Exceptions\ChannelUnavailableException;
use App\Domain\Notification\Support\NotificationMessage;

/**
 * WhatsApp channel — STUBBED. Unavailable until a WhatsApp Business API provider is
 * provided; it never fabricates a send (CLAUDE §8). Wire a real client in `send()`
 * and flip `isAvailable()` once credentials exist.
 */
class WhatsAppChannel implements NotificationChannel
{
    public function key(): string
    {
        return 'whatsapp';
    }

    public function isAvailable(): bool
    {
        return false; // needs an external WhatsApp Business API provider
    }

    public function send(NotificationMessage $message, User $recipient): void
    {
        throw new ChannelUnavailableException('WhatsApp delivery is not available — configure a WhatsApp Business API provider first.');
    }
}
