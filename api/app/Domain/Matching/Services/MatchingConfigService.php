<?php

declare(strict_types=1);

namespace App\Domain\Matching\Services;

use App\Domain\Access\Models\User;
use App\Domain\Matching\Models\MatchingConfig;
use Illuminate\Support\Facades\DB;

/**
 * Reads the active matching configuration and publishes new VERSIONS of it
 * (PRD FR-DUP-02). Publishing is append-only: the prior active version is
 * deactivated and a new numbered version is created, so history stays intact and
 * every change is audited via the model's Auditable trait.
 */
class MatchingConfigService
{
    public function active(): MatchingConfig
    {
        return MatchingConfig::query()->where('is_active', true)->orderByDesc('version')->firstOrFail();
    }

    /**
     * Publish a new active version from validated attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function publish(array $attributes, ?User $actor = null): MatchingConfig
    {
        return DB::transaction(function () use ($attributes, $actor): MatchingConfig {
            $current = MatchingConfig::query()->where('is_active', true)->first();
            $nextVersion = (int) MatchingConfig::query()->max('version') + 1;

            // Deactivate first so the single-active partial-unique index holds.
            $current?->update(['is_active' => false]);

            return MatchingConfig::create([
                ...$attributes,
                'version' => $nextVersion,
                'is_active' => true,
                'created_by' => $actor?->id,
            ]);
        });
    }
}
