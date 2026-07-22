<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Retention;

use App\Domain\Privacy\Enums\RetentionAction;

/**
 * A single, immutable retention rule built from configuration (NFR-PRV-01). It
 * selects a cohort — beneficiaries in one of `$statuses` whose `$ageField` is older
 * than `$ageDays` — and names the `$action` to apply. Never hard-codes legal
 * periods; every field comes from config/privacy.php (DPO-owned).
 */
final class RetentionPolicy
{
    /**
     * @param  list<string>  $statuses  beneficiary statuses the policy applies to (empty = any)
     */
    public function __construct(
        public readonly string $key,
        public readonly bool $enabled,
        public readonly array $statuses,
        public readonly string $ageField,
        public readonly int $ageDays,
        public readonly RetentionAction $action,
    ) {}

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            key: (string) ($config['key'] ?? ''),
            enabled: (bool) ($config['enabled'] ?? false),
            statuses: array_values(array_map('strval', (array) ($config['status'] ?? []))),
            ageField: (string) ($config['age_field'] ?? 'updated_at'),
            ageDays: (int) ($config['age_days'] ?? 0),
            action: RetentionAction::from((string) ($config['action'] ?? 'flag')),
        );
    }
}
