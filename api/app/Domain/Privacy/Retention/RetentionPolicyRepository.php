<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Retention;

/**
 * Reads retention policies from config/privacy.php (NFR-PRV-01). The engine consults
 * this rather than the raw config so policies validate to typed value objects in one
 * place and disabled/invalid entries are filtered out.
 */
class RetentionPolicyRepository
{
    /** Whether the retention engine is switched on at all (DPO/config). */
    public function enabled(): bool
    {
        return (bool) config('privacy.retention.enabled', false);
    }

    public function batchLimit(): int
    {
        return max(1, (int) config('privacy.retention.batch_limit', 500));
    }

    /** Whether a `delete` action may hard-delete a history-free record. */
    public function deleteHard(): bool
    {
        return (bool) config('privacy.retention.delete_hard', false);
    }

    /**
     * The enabled, well-formed policies (a policy with no age period is skipped so a
     * misconfiguration can never sweep the whole registry).
     *
     * @return list<RetentionPolicy>
     */
    public function enabledPolicies(): array
    {
        $policies = [];
        foreach ((array) config('privacy.retention.policies', []) as $config) {
            if (! is_array($config)) {
                continue;
            }
            $policy = RetentionPolicy::fromConfig($config);
            if ($policy->enabled && $policy->key !== '' && $policy->ageDays > 0) {
                $policies[] = $policy;
            }
        }

        return $policies;
    }
}
