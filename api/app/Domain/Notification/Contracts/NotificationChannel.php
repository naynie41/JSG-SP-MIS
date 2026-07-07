<?php

declare(strict_types=1);

namespace App\Domain\Notification\Contracts;

use App\Domain\Access\Models\User;
use App\Domain\Notification\Support\NotificationMessage;

/**
 * A delivery channel for notifications (PRD FR-NOT-01/02). In-app and email are
 * implemented; SMS and WhatsApp are stubbed and report themselves **unavailable**
 * until an external gateway is provided (never fabricated — see CLAUDE §8).
 */
interface NotificationChannel
{
    /** Stable channel key (in_app | email | sms | whatsapp). */
    public function key(): string;

    /** Whether the channel can actually deliver right now (stubs return false). */
    public function isAvailable(): bool;

    /** Deliver the message to the recipient. Only called when {@see isAvailable()}. */
    public function send(NotificationMessage $message, User $recipient): void;
}
