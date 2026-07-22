<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Audit\Models\AuditLog;
use Illuminate\Console\Command;

/**
 * Walks the audit-log hash chain and proves it is intact (NFR-AUD-01): every
 * chained entry must link to its predecessor's hash and re-hash to its stored
 * `entry_hash`. Any mutation, deletion, or reordering of a chained row surfaces
 * here as the first broken link. Intended for scheduled/ops use and for the
 * external pen-test to exercise.
 */
class VerifyAuditChain extends Command
{
    protected $signature = 'audit:verify-chain {--from=1 : Chain position to start from}';

    protected $description = 'Verify the tamper-evident hash chain of the audit log';

    public function handle(): int
    {
        $from = max(1, (int) $this->option('from'));

        $legacy = AuditLog::query()->whereNull('chain_position')->count();
        if ($legacy > 0) {
            $this->line("{$legacy} pre-chain (legacy) entries predate hash-chaining and are not part of the chain.");
        }

        $expectedPosition = $from;
        $expectedPrev = null; // resolved from the row before --from (or genesis)

        if ($from > 1) {
            $anchor = AuditLog::query()->where('chain_position', $from - 1)->first();
            if ($anchor === null) {
                $this->error("Cannot anchor at position {$from}: entry ".($from - 1).' is missing.');

                return self::FAILURE;
            }
            $expectedPrev = (string) $anchor->entry_hash;
        } else {
            $expectedPrev = AuditLog::GENESIS_HASH;
        }

        $checked = 0;
        $broken = null;

        AuditLog::query()
            ->whereNotNull('chain_position')
            ->where('chain_position', '>=', $from)
            ->orderBy('chain_position')
            ->chunk(500, function ($entries) use (&$expectedPosition, &$expectedPrev, &$checked, &$broken): bool {
                foreach ($entries as $entry) {
                    /** @var AuditLog $entry */
                    if ((int) $entry->chain_position !== $expectedPosition) {
                        $broken = "Gap in chain: expected position {$expectedPosition}, found {$entry->chain_position} — an entry was removed.";

                        return false;
                    }
                    if ($entry->prev_hash !== $expectedPrev) {
                        $broken = "Entry {$entry->chain_position} does not link to its predecessor (prev_hash mismatch).";

                        return false;
                    }
                    if ($entry->computeEntryHash() !== $entry->entry_hash) {
                        $broken = "Entry {$entry->chain_position} fails re-hashing — its content was modified.";

                        return false;
                    }

                    $expectedPrev = (string) $entry->entry_hash;
                    $expectedPosition++;
                    $checked++;
                }

                return true;
            });

        if ($broken !== null) {
            $this->error("TAMPER EVIDENT: {$broken}");
            $this->error("Verified {$checked} entries before the break.");

            return self::FAILURE;
        }

        $this->info("Audit chain intact: {$checked} chained entries verified.");

        return self::SUCCESS;
    }
}
