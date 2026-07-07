<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Notification channels (PRD FR-NOT-01/02)
    |--------------------------------------------------------------------------
    | In-app is always on (the inbox / system of record). Email is a real queued
    | channel, toggled here. SMS and WhatsApp are stubbed and stay unavailable
    | until an external gateway is configured — they are never fabricated.
    */
    'channels' => [
        'email' => [
            'enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', true),
        ],
    ],
];
