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
 */
final readonly class NotificationMessage
{
    public ?string $relatedType;

    public ?string $relatedId;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public string $type,
        public string $subject,
        public ?string $body = null,
        public array $payload = [],
        ?Model $related = null,
    ) {
        $this->relatedType = $related !== null ? Str::snake(class_basename($related)) : null;
        $this->relatedId = $related?->getKey() !== null ? (string) $related->getKey() : null;
    }
}
