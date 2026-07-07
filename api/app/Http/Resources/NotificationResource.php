<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Notification\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Notification
 */
class NotificationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'subject' => $this->subject,
            'body' => $this->body,
            'payload' => $this->payload,
            'related' => $this->related_type !== null
                ? ['type' => $this->related_type, 'id' => $this->related_id]
                : null,
            'read_at' => $this->read_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
