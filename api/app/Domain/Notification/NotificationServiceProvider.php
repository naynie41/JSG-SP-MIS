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
    public function register(): void
    {
        $this->app->singleton(Notifier::class, fn (): Notifier => new Notifier([
            new InAppChannel,
            new EmailChannel,
            new SmsChannel,       // stub — unavailable until an SMS gateway is provided
            new WhatsAppChannel,  // stub — unavailable until a WhatsApp provider is provided
        ]));
    }

    public function boot(): void
    {
        Event::subscribe(NotificationSubscriber::class);
    }
}
