<?php

declare(strict_types=1);

namespace App\Domain\Reference\Services;

use App\Domain\Reference\Imports\AdministrativeDivisionLoader;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use Illuminate\Support\Facades\Cache;

/**
 * Caches the administrative-division lookups.
 *
 * These lists are read on nearly every form and filter, are identical for every user
 * (no MDA scoping, no PII), and change only when a maintainer loads a new dataset —
 * the ideal cache shape.
 *
 * Invalidation is by VERSION COUNTER, not by cache tags: the default store is
 * `database` (see config/cache.php), and tags are unsupported there — a tag-based
 * flush would silently do nothing in production while passing in a redis-backed test.
 * Bumping an integer that is baked into every key works on every store, and leaves the
 * stale entries to expire on their own TTL.
 *
 * {@see AdministrativeDivisionLoader} calls flush() after
 * a load, so a re-seed is visible immediately.
 */
class ReferenceDataCache
{
    private const VERSION_KEY = 'reference.divisions.version';

    /** Safety net only — a load flushes explicitly, so this rarely decides anything. */
    private const TTL_SECONDS = 86400;

    /**
     * @return list<array<string, mixed>>
     */
    public function lgas(): array
    {
        return Cache::remember(
            $this->key('lgas'),
            self::TTL_SECONDS,
            fn (): array => Lga::query()
                ->withCount('wards')
                ->orderBy('name')
                ->get()
                ->map(fn (Lga $lga): array => [
                    'id' => $lga->id,
                    'code' => $lga->code,
                    'name' => $lga->name,
                    'state' => $lga->state,
                    'ward_count' => (int) ($lga->wards_count ?? 0),
                ])
                ->all(),
        );
    }

    /**
     * Wards of one LGA, ordered by name — the second step of the cascading selector.
     *
     * @return list<array<string, mixed>>
     */
    public function wardsFor(string $lgaId): array
    {
        return Cache::remember(
            $this->key("wards.{$lgaId}"),
            self::TTL_SECONDS,
            fn (): array => Ward::query()
                ->where('lga_id', $lgaId)
                ->orderBy('name')
                ->get()
                ->map(fn (Ward $ward): array => [
                    'id' => $ward->id,
                    'lga_id' => $ward->lga_id,
                    'code' => $ward->code,
                    'name' => $ward->name,
                ])
                ->all(),
        );
    }

    /**
     * Invalidate everything by moving the version every key is built from.
     */
    public function flush(): void
    {
        Cache::forever(self::VERSION_KEY, $this->version() + 1);
    }

    public function version(): int
    {
        return (int) Cache::rememberForever(self::VERSION_KEY, fn (): int => 1);
    }

    private function key(string $suffix): string
    {
        return 'reference.v'.$this->version().'.'.$suffix;
    }
}
