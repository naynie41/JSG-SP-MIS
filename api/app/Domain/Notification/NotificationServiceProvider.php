<?php

declare(strict_types=1);

namespace App\Domain\Notification;

use App\Domain\Notification\Channels\EmailChannel;
use App\Domain\Notification\Channels\InAppChannel;
use App\Domain\Notification\Channels\SmsChannel;
use App\Domain\Notification\Channels\WhatsAppChannel;
use App\Domain\Notification\Listeners\NotificationSubscriber;
use App\Domain\Notification\Services\Notifier;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Notification domain (PRD FR-NOT-01/02): the Notifier with its channel
 * set (in-app + email real; SMS + WhatsApp stubbed/unavailable), and the
 * event-driven subscriber that turns domain events into notifications. Adding a
 * real SMS/WhatsApp client later is a one-line binding change here.
 */
class NotificationServiceProvider extends ServiceProvider
{
    /** The container key for the registered channel set. */
    public const CHANNELS = 'notification.channels';

    public function register(): void
    {
        // One channel set, resolved by everything that needs it — the Notifier that
        // sends, and the console's Settings projection that reports availability. A
        // second hard-coded list would let Settings claim a delivery path that does
        // not exist.
        $this->app->singleton(self::CHANNELS, fn (): array => [
            new InAppChannel,
            new EmailChannel,
            new SmsChannel,       // stub — unavailable until an SMS gateway is provided
            new WhatsAppChannel,  // stub — unavailable until a WhatsApp provider is provided
        ]);

        $this->app->singleton(Notifier::class, fn ($app): Notifier => new Notifier($app->make(self::CHANNELS)));
    }

    public function boot(): void
    {
        Event::subscribe(NotificationSubscriber::class);
    }
}
