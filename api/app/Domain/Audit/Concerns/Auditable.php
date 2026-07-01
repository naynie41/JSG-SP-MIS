<?php

declare(strict_types=1);

namespace App\Domain\Audit\Concerns;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Audit\Support\AuditScrubber;
use Illuminate\Support\Str;

/**
 * Records create/update/delete of a model to the audit log (FR-AUD-01) with
 * scrubbed before/after snapshots. Add this trait to any model that should be
 * audited — future modules get consistent auditing for free.
 *
 * Override auditExcluded() for operational columns that should not generate
 * audit noise (e.g. last_login_at). Secrets/PII are handled globally by the
 * AuditScrubber (config/audit.php).
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model): void {
            $model->writeAudit('created', null, $model->auditSnapshot($model->getAttributes()));
        });

        static::updated(function ($model): void {
            $changedKeys = array_diff(array_keys($model->getChanges()), $model->allAuditExcluded());

            if ($changedKeys === []) {
                return; // only excluded/operational columns changed — no audit noise
            }

            $before = [];
            $after = [];
            foreach ($changedKeys as $key) {
                $before[$key] = $model->getOriginal($key);
                $after[$key] = $model->getAttribute($key);
            }

            $model->writeAudit('updated', $model->scrubAttributes($before), $model->scrubAttributes($after));
        });

        static::deleted(function ($model): void {
            $model->writeAudit('deleted', $model->auditSnapshot($model->getOriginal()), null);
        });
    }

    /**
     * Columns that never produce an audit entry on change.
     *
     * @return list<string>
     */
    protected function auditExcluded(): array
    {
        return [];
    }

    /**
     * The entity name used in the action and entity_type (e.g. "user").
     */
    public function auditEntityName(): string
    {
        return Str::snake(class_basename($this));
    }

    /**
     * Model-specific secret fields to redact (merged with the global list).
     *
     * @return list<string>
     */
    protected function auditOmit(): array
    {
        return [];
    }

    /**
     * Model-specific PII fields to mask (merged with the global list).
     *
     * @return list<string>
     */
    protected function auditMask(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function scrubAttributes(array $attributes): array
    {
        return app(AuditScrubber::class)->scrub($attributes, $this->auditOmit(), $this->auditMask());
    }

    /**
     * @return list<string>
     */
    private function allAuditExcluded(): array
    {
        return array_merge(['created_at', 'updated_at', 'deleted_at'], $this->auditExcluded());
    }

    /**
     * Filter out excluded columns, then scrub secrets/PII.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function auditSnapshot(array $attributes): array
    {
        $excluded = $this->allAuditExcluded();
        $filtered = array_filter(
            $attributes,
            fn (string $key) => ! in_array($key, $excluded, true),
            ARRAY_FILTER_USE_KEY,
        );

        return $this->scrubAttributes($filtered);
    }

    /**
     * @param  array<string, mixed>|null  $before
     * @param  array<string, mixed>|null  $after
     */
    private function writeAudit(string $event, ?array $before, ?array $after): void
    {
        app(AuditLogger::class)->record(
            $this->auditEntityName().'.'.$event,
            $this,
            $before,
            $after,
        );
    }
}
