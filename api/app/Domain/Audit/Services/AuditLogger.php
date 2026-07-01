<?php

declare(strict_types=1);

namespace App\Domain\Audit\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Single entry point for writing audit entries (FR-AUD-01). Captures the actor
 * and request context automatically; callers supply the action and (already
 * scrubbed) before/after snapshots.
 */
class AuditLogger
{
    /**
     * @param  array<string, mixed>|null  $before
     * @param  array<string, mixed>|null  $after
     * @param  array<string, mixed>  $context  optional overrides: entity_type, entity_id, actor_id, actor_mda_id
     */
    public function record(
        string $action,
        ?Model $entity = null,
        ?array $before = null,
        ?array $after = null,
        ?Authenticatable $actor = null,
        array $context = [],
    ): AuditLog {
        $actor ??= Auth::user();
        $request = request();

        return AuditLog::create([
            'actor_id' => $context['actor_id'] ?? $actor?->getAuthIdentifier(),
            'actor_mda_id' => $context['actor_mda_id'] ?? ($actor instanceof User ? $actor->mda_id : null),
            'action' => $action,
            'entity_type' => $context['entity_type'] ?? ($entity ? $this->entityName($entity) : null),
            'entity_id' => $context['entity_id'] ?? $entity?->getKey(),
            'before' => $before,
            'after' => $after,
            'ip_address' => $request?->ip(),
            'user_agent' => $request !== null ? mb_substr((string) $request->userAgent(), 0, 1000) : null,
            'correlation_id' => $request?->attributes->get('correlation_id'),
            'created_at' => now(),
        ]);
    }

    private function entityName(Model $entity): string
    {
        if (method_exists($entity, 'auditEntityName')) {
            return $entity->auditEntityName();
        }

        return class_basename($entity);
    }
}
