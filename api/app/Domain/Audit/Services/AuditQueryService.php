<?php

declare(strict_types=1);

namespace App\Domain\Audit\Services;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * READ side of the immutable audit log (FR-AUD-01), for the administration console's
 * Audit & Security section. It only ever SELECTs — writing stays with
 * {@see AuditLogger} and the Auditable trait, so there is no second logging path.
 *
 * PII SAFETY: the projection never returns the `before`/`after` payloads. Those are
 * already scrubbed at write time (secrets redacted, PII masked — SECURITY.md §6), but
 * this surface goes further and exposes only the CHANGED FIELD NAMES, so a reviewer can
 * see what changed without any value ever reaching the client. The values remain in the
 * tamper-evident log for forensic access through the database/CLI.
 */
class AuditQueryService
{
    /**
     * Action taxonomy for the console's filters. Anything unlisted is "activity"
     * (ordinary create/update/delete on a domain entity).
     *
     * @var array<string, list<string>>
     */
    public const CATEGORIES = [
        // Authentication + account security (Phase 1).
        'security' => [
            'auth.login', 'auth.login_failed', 'auth.logout', 'auth.account_locked',
            'mfa.enrolled', 'mfa.disabled', 'mfa.challenge_failed',
            'user.mfa_reset', 'user.password_reset_forced',
        ],
        // Permission / access-grant changes (Phase 1 + cross-MDA sharing).
        'permission' => [
            'cross_mda.granted', 'cross_mda.revoked',
            'beneficiary.access_granted', 'beneficiary.access_revoked',
            'role.created', 'role.updated', 'role.deleted',
        ],
        // Request-to-serve decisions (Phase 3 ownership → Phase 5 coordination).
        'service_request' => [
            'service_request.created', 'service_request.accepted', 'service_request.declined',
            'ownership_transfer.approved', 'ownership_transfer.rejected',
        ],
        // Data access + egress (Phase 6 exports, document downloads).
        'data_access' => [
            'beneficiary.exported', 'beneficiary_document.downloaded',
            'dashboard.exported', 'report.generated', 'report.downloaded',
            'audit_log.exported',
        ],
    ];

    public const DEFAULT_CATEGORY = 'activity';

    /**
     * Filtered, paginated audit entries, newest first.
     *
     * @param  array<string, mixed>  $filters  category, action, actor_id, entity_type, from, to, q
     * @return LengthAwarePaginator<int, AuditLog>
     */
    public function paginate(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage);
    }

    /**
     * The same filtered set, capped, for export.
     *
     * @param  array<string, mixed>  $filters
     * @return Collection<int, AuditLog>
     */
    public function forExport(array $filters, int $limit = 5000): Collection
    {
        return $this->query($filters)->limit($limit)->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<AuditLog>
     */
    private function query(array $filters): Builder
    {
        $query = AuditLog::query()->orderByDesc('created_at')->orderByDesc('chain_position');

        $category = $filters['category'] ?? null;
        if (is_string($category) && $category !== '') {
            if ($category === self::DEFAULT_CATEGORY) {
                // "Activity" is everything not claimed by a named category.
                $query->whereNotIn('action', $this->allCategorisedActions());
            } else {
                $query->whereIn('action', self::CATEGORIES[$category] ?? ['__none__']);
            }
        }

        if (is_string($filters['action'] ?? null) && $filters['action'] !== '') {
            $query->where('action', $filters['action']);
        }
        if (is_string($filters['actor_id'] ?? null) && $filters['actor_id'] !== '') {
            $query->where('actor_id', $filters['actor_id']);
        }
        if (is_string($filters['entity_type'] ?? null) && $filters['entity_type'] !== '') {
            $query->where('entity_type', 'like', '%'.$filters['entity_type'].'%');
        }
        if (is_string($filters['from'] ?? null) && $filters['from'] !== '') {
            $query->whereDate('created_at', '>=', $filters['from']);
        }
        if (is_string($filters['to'] ?? null) && $filters['to'] !== '') {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        // Free-text search runs over the action and entity type only — never over the
        // before/after payloads, so a search can never surface a personal value.
        $term = $filters['q'] ?? null;
        if (is_string($term) && trim($term) !== '') {
            $like = '%'.trim($term).'%';
            $query->where(fn (Builder $w) => $w->where('action', 'like', $like)->orWhere('entity_type', 'like', $like));
        }

        return $query;
    }

    /**
     * Project entries for the client: the envelope plus the NAMES of changed fields.
     * Values are deliberately absent (see the class docblock).
     *
     * @param  iterable<int, AuditLog>  $entries
     * @return list<array<string, mixed>>
     */
    public function present(iterable $entries): array
    {
        $collection = collect($entries);

        $actors = User::query()
            ->whereIn('id', $collection->pluck('actor_id')->filter()->unique()->all())
            ->pluck('name', 'id');
        $mdas = Mda::query()
            ->whereIn('id', $collection->pluck('actor_mda_id')->filter()->unique()->all())
            ->pluck('name', 'id');

        $out = [];
        foreach ($collection as $entry) {
            $out[] = [
                'id' => $entry->id,
                'action' => $entry->action,
                'category' => $this->categoryFor($entry->action),
                'entity_type' => $entry->entity_type === null ? null : class_basename($entry->entity_type),
                'entity_id' => $entry->entity_id,
                'actor' => $entry->actor_id === null ? 'System' : ($actors[$entry->actor_id] ?? 'Deleted user'),
                'actor_id' => $entry->actor_id,
                'actor_mda' => $entry->actor_mda_id === null ? null : ($mdas[$entry->actor_mda_id] ?? null),
                'ip_address' => $entry->ip_address,
                'correlation_id' => $entry->correlation_id,
                'chain_position' => $entry->chain_position,
                // Field NAMES only — never the recorded values.
                'changed_fields' => $this->changedFields($entry),
                'at' => $entry->created_at?->toIso8601String(),
            ];
        }

        return $out;
    }

    public function categoryFor(string $action): string
    {
        foreach (self::CATEGORIES as $category => $actions) {
            if (in_array($action, $actions, true)) {
                return $category;
            }
        }

        return self::DEFAULT_CATEGORY;
    }

    /**
     * The distinct actions present in the log — powers the console's filter list
     * without hard-coding a taxonomy the data might outgrow.
     *
     * @return list<string>
     */
    public function knownActions(): array
    {
        return AuditLog::query()->distinct()->orderBy('action')->pluck('action')->values()->all();
    }

    /**
     * Names of the fields an entry touched, taken from the scrubbed snapshots.
     *
     * @return list<string>
     */
    private function changedFields(AuditLog $entry): array
    {
        $keys = array_merge(
            array_keys($entry->after ?? []),
            array_keys($entry->before ?? []),
        );

        return array_values(array_unique(array_map('strval', $keys)));
    }

    /**
     * @return list<string>
     */
    private function allCategorisedActions(): array
    {
        return array_merge(...array_values(self::CATEGORIES));
    }
}
