<?php

declare(strict_types=1);

namespace App\Domain\Notification\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * A channel-agnostic notification payload (PRD FR-NOT-01). The subscriber builds
 * one of these per domain event; each channel renders it for its medium. An
 * optional `related` model is captured as a `(type, id)` reference so the client
 * can deep-link to the originating entity — without embedding its PII.
 *
 * `actionPath` is what turns an announcement into something actionable. It is a
 * RELATIVE SPA path, never an absolute URL: the message must stay
 * environment-agnostic (dev, staging, the state's own domain) and it is the channel
 * that knows the base — see `config('notifications.app_url')`. Email needs it most,
 * because unlike the in-app bell an email has no surrounding application to click
 * into; a mail saying "a decision is waiting" with no way to reach it is a dead end.
 */
final readonly class NotificationMessage
{
    public ?string $relatedType;

    public ?string $relatedId;

    /**
     * @param  array<string, mixed>  $payload
     * @param  string|null  $actionPath  relative SPA path, e.g. `/service-requests`
     * @param  string|null  $actionLabel  the button/link text, e.g. `Review the request`
     */
    public function __construct(
        public string $type,
        public string $subject,
        public ?string $body = null,
        public array $payload = [],
        ?Model $related = null,
        public ?string $actionPath = null,
        public ?string $actionLabel = null,
    ) {
        $this->relatedType = $related !== null ? Str::snake(class_basename($related)) : null;
        $this->relatedId = $related?->getKey() !== null ? (string) $related->getKey() : null;
    }

    /** The absolute destination for a channel that has to render a real link. */
    public function actionUrl(): ?string
    {
        if ($this->actionPath === null) {
            return null;
        }

        return rtrim((string) config('notifications.app_url'), '/').'/'.ltrim($this->actionPath, '/');
    }
}
