<?php

declare(strict_types=1);

namespace App\Domain\Matching;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Matching\Contracts\MatchScorer;
use App\Domain\Matching\Scoring\RuleBasedMatchScorer;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Matching domain (PRD FR-DUP): the pluggable scorer binding and the
 * admin permissions for viewing/configuring the matching rules. A future AI
 * scorer (FR-DUP-07) is enabled by rebinding {@see MatchScorer} here only.
 */
class MatchingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Built-in deterministic + fuzzy scorer (autowired dependencies).
        $this->app->bind(MatchScorer::class, RuleBasedMatchScorer::class);
    }

    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('matching', PermissionAction::View, 'View duplicate-matching configuration')
            ->register('matching', PermissionAction::Edit, 'Configure duplicate-matching rules');
    }
}
