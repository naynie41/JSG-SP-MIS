<?php

declare(strict_types=1);

namespace App\Domain\Audit\Support;

/**
 * Removes secrets and masks PII from audit before/after snapshots so the audit
 * log never stores raw secrets or personal data (SECURITY.md §6).
 */
class AuditScrubber
{
    private const REDACTED = '[redacted]';

    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<string>  $extraOmit  model-specific secret fields
     * @param  list<string>  $extraMask  model-specific PII fields
     * @return array<string, mixed>
     */
    public function scrub(array $attributes, array $extraOmit = [], array $extraMask = []): array
    {
        $omit = array_map('strtolower', array_merge((array) config('audit.omit', []), $extraOmit));
        $mask = array_map('strtolower', array_merge((array) config('audit.mask', []), $extraMask));

        $scrubbed = [];

        foreach ($attributes as $key => $value) {
            $name = strtolower((string) $key);

            if (in_array($name, $omit, true)) {
                $scrubbed[$key] = self::REDACTED;
            } elseif (in_array($name, $mask, true)) {
                $scrubbed[$key] = $this->mask($value);
            } else {
                $scrubbed[$key] = $value;
            }
        }

        return $scrubbed;
    }

    private function mask(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (! is_scalar($value)) {
            return '[masked]';
        }

        $string = (string) $value;
        $keep = (int) config('audit.mask_keep', 2);

        if (mb_strlen($string) <= $keep) {
            return '***';
        }

        return mb_substr($string, 0, $keep).'***';
    }
}
