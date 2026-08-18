<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Console\Command;

/**
 * Repairs beneficiaries whose name was doubled by an early import.
 *
 *   php artisan registry:repair-doubled-names            # report only
 *   php artisan registry:repair-doubled-names --apply    # write the fix
 *
 * Before {@see NameSplitter} existed, a source file with one `Name` column could only be
 * mapped by pointing BOTH `first_name` and `last_name` at it, which stored the whole name
 * twice — "Rekiya Bagwai Rekiya Bagwai". New imports cannot produce this; these are the
 * records made before the fix.
 *
 * The repair re-splits by the same rule the importer now uses, so a record repaired here
 * is indistinguishable from one imported today.
 *
 * Three things make this safe to run against real data:
 *
 *  - **Narrow by construction.** Only rows where `first_name` is identical to `last_name`
 *    AND contains a space. A genuine "Musa Musa" has no space in either field and is left
 *    alone — the command cannot invent a correction for a name that was never doubled.
 *  - **Saved through the model.** `last_name` feeds `block_name_dob`, the fuzzy blocking
 *    key (FR-DUP-03). A bulk query update would leave that key pointing at the old
 *    surname and silently degrade duplicate detection; the model's `saving` hook
 *    recomputes it, and `Auditable` records the before/after per record.
 *  - **Idempotent.** After the repair `first_name !== last_name`, so a second run finds
 *    nothing.
 *
 * Reports by name SHAPE (token counts), never by name: this is PII and CLAUDE.md §8 says
 * it is never logged. The shape is also the more useful check — it shows the rule being
 * applied, which a list of names would not.
 */
class RepairDoubledNames extends Command
{
    protected $signature = 'registry:repair-doubled-names
        {--apply : Write the corrections. Without this the command only reports.}
        {--batch= : Limit to one import batch id.}';

    protected $description = 'Re-split beneficiary names doubled by an import made before the full-name split existed';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $batchId = $this->option('batch');

        $query = Beneficiary::query()->withoutGlobalScopes()
            ->whereColumn('first_name', 'last_name')
            ->where('first_name', 'like', '% %');

        if (is_string($batchId) && $batchId !== '') {
            $query->where('import_batch_id', $batchId);
        }

        $affected = $query->get();

        if ($affected->isEmpty()) {
            $this->info('No doubled names found.');

            return self::SUCCESS;
        }

        /** @var array<int, int> $shapes token count => how many records */
        $shapes = [];
        $repaired = 0;
        $skipped = 0;

        foreach ($affected as $beneficiary) {
            $split = NameSplitter::split($beneficiary->first_name);

            // Belt and braces: the `like` filter already guarantees a space, so a null
            // last name here would mean the filter and the splitter disagree. Skip rather
            // than write a beneficiary with no surname — it is a required identity field.
            if ($split['first_name'] === null || $split['last_name'] === null) {
                $skipped++;

                continue;
            }

            $tokens = count(explode(' ', (string) preg_replace('/\s+/', ' ', trim((string) $beneficiary->first_name))));
            $shapes[$tokens] = ($shapes[$tokens] ?? 0) + 1;

            if ($apply) {
                // Through the model: recomputes `block_name_dob` and audits the change.
                $beneficiary->first_name = $split['first_name'];
                $beneficiary->last_name = $split['last_name'];
                $beneficiary->save();
            }

            $repaired++;
        }

        $this->reportShapes($shapes, $skipped);

        if (! $apply) {
            $this->newLine();
            $this->warn("{$repaired} records would be repaired. Re-run with --apply to write the change.");

            return self::SUCCESS;
        }

        $this->recordSummary($repaired, $skipped, $shapes, is_string($batchId) ? $batchId : null);

        $this->newLine();
        $this->info("Repaired {$repaired} records.");

        if ($skipped > 0) {
            $this->warn("{$skipped} skipped — see audit action=registry.doubled_names_repaired.");
        }

        return self::SUCCESS;
    }

    /**
     * @param  array<int, int>  $shapes
     */
    private function reportShapes(array $shapes, int $skipped): void
    {
        ksort($shapes);

        $this->line('Doubled names by shape (no names shown — they are PII):');
        foreach ($shapes as $tokens => $count) {
            // e.g. 3 tokens → first = 1 token, last = 2 tokens.
            $this->line(sprintf(
                '  %d-token name  ×%-5d  →  first = 1 token, last = %d token%s',
                $tokens,
                $count,
                $tokens - 1,
                $tokens - 1 === 1 ? '' : 's',
            ));
        }

        if ($skipped > 0) {
            $this->line("  unsplittable ×{$skipped}  →  left unchanged");
        }
    }

    /**
     * One summary entry so the repair itself is on the record, separate from the
     * per-record `beneficiary.updated` entries the model wrote. Counts only — the names
     * are already in those per-record entries and must not be duplicated here.
     *
     * @param  array<int, int>  $shapes
     */
    private function recordSummary(int $repaired, int $skipped, array $shapes, ?string $batchId): void
    {
        ksort($shapes);

        AuditLog::create([
            'actor_id' => null, // a maintenance command has no authenticated user
            'actor_mda_id' => null,
            'action' => 'registry.doubled_names_repaired',
            'entity_type' => 'beneficiary',
            'entity_id' => null,
            'before' => null,
            'after' => [
                'repaired' => $repaired,
                'skipped' => $skipped,
                'shapes' => $shapes,
                'import_batch_id' => $batchId,
                'rule' => 'first token = first_name, remaining tokens = last_name',
            ],
            'created_at' => now(),
        ]);
    }
}
