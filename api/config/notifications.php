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

    /*
    |--------------------------------------------------------------------------
    | Where an email sends the reader
    |--------------------------------------------------------------------------
    | The SPA origin, NOT APP_URL — APP_URL is the API. An action-required email
    | that cannot get the officer to the thing that needs deciding is a bare
    | announcement, so every link is built from this base.
    |
    | Deliberately separate from CORS_ALLOWED_ORIGINS: that list can hold several
    | origins and says who may CALL us, which is a different question from where a
    | human should be sent.
    */
    'app_url' => rtrim((string) env('SPA_URL', 'http://localhost:5173'), '/'),

    /*
    | Where a recipient turns these emails off. Preferences are the unsubscribe
    | mechanism (FR-NOT-02) — these are operational notices to named officers about
    | their own work, not marketing, so the control lives behind their login rather
    | than on a public one-click token endpoint.
    */
    'preferences_path' => '/settings/notifications',
];
