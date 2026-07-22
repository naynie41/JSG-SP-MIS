<?php

declare(strict_types=1);

namespace App\Domain\Sync\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Imports\Adapters\RegistrationSourceAdapter;
use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use App\Domain\Registry\Imports\ImportRowValidator;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\BatchDuplicateScreener;
use App\Domain\Registry\Services\BeneficiaryRegistrar;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncRowOutcome;
use App\Domain\Sync\Enums\SyncStatus;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Models\SyncRun;
use App\Domain\Sync\Models\SyncRunRow;
use App\Domain\Sync\Sources\SyncSourceResolver;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Throwable;

/**
 * The synchronization engine (FR-DSH-02, FR-REG-08). Inbound records — whether pulled
 * from a scheduled/triggered connector or posted as an offline batch — run through the
 * SAME Phase 2 pipeline as a file import, with NOTHING bypassed:
 *   adapter map → identity-field validation (FR-REG-05) → duplicate screening
 *   (FR-DUP-08) → ownership + provenance + per-MDA idempotency (FR-REG-08).
 * The only sync-specific addition is automated, configurable conflict resolution
 * (there is no interactive officer): last-write-wins on a record the source's MDA owns,
 * otherwise flag-for-review. Nothing is ever silently auto-merged or cross-MDA
 * overwritten. Every record's outcome is logged to `sync_run_rows`.
 */
class SyncEngine
{
    /** Canonical beneficiary fields the adapter/validator produce. */
    private const CANONICAL_FIELDS = [
        'first_name', 'middle_name', 'last_name', 'nin', 'bvn', 'phone',
        'date_of_birth', 'gender', 'address', 'lga', 'ward',
    ];

    public function __construct(
        private readonly SourceAdapterRegistry $adapters,
        private readonly ImportRowValidator $validator,
        private readonly BatchDuplicateScreener $screener,
        private readonly MatchingConfigService $configs,
        private readonly BeneficiaryRegistrar $registrar,
        private readonly SyncSourceResolver $sources,
        private readonly AuditLogger $audit,
    ) {}

    /** Scheduled or manually-triggered sync from a configured connector. */
    public function runConnector(SyncConnector $connector, SyncTrigger $trigger, ?User $by = null): SyncRun
    {
        $run = $this->startRun([
            'connector_id' => $connector->id,
            'trigger' => $trigger->value,
            'source' => $connector->source->value,
            'owner_mda_id' => $connector->owner_mda_id,
            'conflict_policy' => $connector->conflict_policy->value,
            'triggered_by' => $by?->id,
        ]);

        $this->execute($run, function () use ($connector, $run): void {
            $records = $this->sources->for($connector)->fetch($connector);
            $this->process($run, $records, $connector->source, $connector->owner_mda_id, $connector->conflict_policy);
            $connector->update(['last_run_at' => Carbon::now()]);
        });

        $this->audit->record('sync.run', $run->fresh(), after: $this->summary($run->fresh()), actor: $by);

        return $run->fresh();
    }

    /**
     * Offline-batch sync (FR-REG-08): records captured offline are replayed through the
     * SAME pipeline. The per-MDA idempotency key means re-posting the same batch never
     * double-inserts.
     *
     * @param  iterable<int, array<string, mixed>>  $records
     */
    public function runOfflineBatch(string $ownerMdaId, RegistrationSource $source, iterable $records, ConflictPolicy $policy, ?User $by = null): SyncRun
    {
        $run = $this->startRun([
            'connector_id' => null,
            'trigger' => SyncTrigger::OfflineBatch->value,
            'source' => $source->value,
            'owner_mda_id' => $ownerMdaId,
            'conflict_policy' => $policy->value,
            'triggered_by' => $by?->id,
        ]);

        $this->execute($run, fn () => $this->process($run, $records, $source, $ownerMdaId, $policy));

        $this->audit->record('sync.offline_batch', $run->fresh(), after: $this->summary($run->fresh()), actor: $by);

        return $run->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function startRun(array $attributes): SyncRun
    {
        return SyncRun::create([
            ...$attributes,
            'status' => SyncStatus::Running->value,
            'started_at' => Carbon::now(),
        ]);
    }

    private function execute(SyncRun $run, callable $work): void
    {
        try {
            $work();
            $run->update(['status' => SyncStatus::Completed, 'finished_at' => Carbon::now()]);
        } catch (Throwable $e) {
            $run->update(['status' => SyncStatus::Failed, 'error' => Str::limit($e->getMessage(), 500), 'finished_at' => Carbon::now()]);
        }
    }

    /**
     * Run every raw record through the pipeline, logging each outcome and tallying.
     *
     * @param  iterable<int, array<string, mixed>>  $rawRecords
     */
    private function process(SyncRun $run, iterable $rawRecords, RegistrationSource $source, string $ownerMdaId, ConflictPolicy $policy): void
    {
        $adapter = $this->adapters->for($source);
        $config = $this->configs->activeOrNull(); // null → matching not configured; screening skipped

        $fetched = 0;
        $rowNumber = 0;
        $counts = ['created_count' => 0, 'updated_count' => 0, 'skipped_count' => 0, 'flagged_count' => 0, 'rejected_count' => 0, 'error_count' => 0];

        foreach ($rawRecords as $raw) {
            $fetched++;
            $rowNumber++;

            [$outcome, $beneficiaryId, $band, $detail] = $this->processRecord($raw, $adapter, $config, $source, $ownerMdaId, $policy, $rowNumber);

            SyncRunRow::create([
                'sync_run_id' => $run->id,
                'original_record_id' => $detail['original_record_id'] ?? null,
                'outcome' => $outcome->value,
                'beneficiary_id' => $beneficiaryId,
                'match_band' => $band,
                'detail' => ($clean = Arr::except($detail, ['original_record_id'])) === [] ? null : $clean,
            ]);

            $counts[$outcome->counter()]++;
        }

        $run->update(['fetched_count' => $fetched, ...$counts]);
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array{0: SyncRowOutcome, 1: ?string, 2: ?string, 3: array<string, mixed>}
     */
    private function processRecord(array $raw, RegistrationSourceAdapter $adapter, ?MatchingConfig $config, RegistrationSource $source, string $ownerMdaId, ConflictPolicy $policy, int $rowNumber): array
    {
        $mapped = $adapter->map($raw);
        $originalRecordId = $mapped['original_record_id'] ?? null;
        $meta = ['original_record_id' => $originalRecordId];

        $validated = $this->validator->validate($mapped);
        $payload = $validated['payload'];

        // FR-REG-05: a present-but-malformed identity field rejects the whole record.
        if ($validated['identity_errors'] !== []) {
            return [SyncRowOutcome::RejectedIdentity, null, null, [...$meta, 'errors' => $validated['identity_errors']]];
        }

        // FR-REG-08 idempotency: the same source record already synced under this owner.
        if ($originalRecordId !== null) {
            $existing = Beneficiary::query()->withoutGlobalScope(MdaScope::class)
                ->where('owner_mda_id', $ownerMdaId)
                ->where('idempotency_key', $originalRecordId)
                ->first();

            if ($existing !== null) {
                if ($policy === ConflictPolicy::LastWriteWins) {
                    $this->applyUpdate($existing, $payload);

                    return [SyncRowOutcome::Updated, $existing->id, null, [...$meta, 'reason' => 'idempotent_update']];
                }

                return [SyncRowOutcome::SkippedIdempotent, $existing->id, null, $meta];
            }
        }

        // FR-DUP-01/08 dedup surfacing against the registry.
        $match = $config !== null ? $this->screener->screen($payload, $rowNumber, $config) : ['band' => null, 'candidates' => null];
        $band = $match['band'];

        if ($band === 'exact' || $band === 'probable') {
            $matchedId = $this->firstRegistryReference($match['candidates'] ?? []);
            $matched = $matchedId !== null
                ? Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($matchedId)
                : null;

            // Last-write-wins applies ONLY to an exact match on a record this source's
            // MDA already owns. Anything else (probable, or another MDA's record) is
            // flagged — never auto-merged, never cross-MDA overwritten.
            if ($band === 'exact' && $policy === ConflictPolicy::LastWriteWins && $matched !== null && $matched->owner_mda_id === $ownerMdaId) {
                $this->applyUpdate($matched, $payload);

                return [SyncRowOutcome::Updated, $matched->id, $band, [...$meta, 'reason' => 'exact_match_owned']];
            }

            $reason = $band === 'probable'
                ? 'probable_match_needs_review'
                : ($matched !== null && $matched->owner_mda_id !== $ownerMdaId ? 'exact_match_other_mda' : 'exact_match_flagged');

            return [SyncRowOutcome::FlaggedConflict, $matchedId, $band, [...$meta, 'reason' => $reason]];
        }

        // No match → create through the registrar (idempotency + provenance + pre-save check).
        try {
            $beneficiary = $this->registrar->register(
                Arr::only($payload, self::CANONICAL_FIELDS),
                $ownerMdaId,
                $source,
                $originalRecordId,
                null,
                $originalRecordId, // idempotency key = the source record id
            );

            return [SyncRowOutcome::Created, $beneficiary->id, null, $meta];
        } catch (Throwable $e) {
            return [SyncRowOutcome::Error, null, null, [...$meta, 'error' => Str::limit($e->getMessage(), 300)]];
        }
    }

    /**
     * Last-write-wins: overwrite the record's canonical fields with the source values
     * that were provided (an omitted source field never wipes an existing value).
     *
     * @param  array<string, mixed>  $payload
     */
    private function applyUpdate(Beneficiary $beneficiary, array $payload): void
    {
        $provided = array_filter(
            Arr::only($payload, self::CANONICAL_FIELDS),
            static fn ($value): bool => $value !== null,
        );

        $beneficiary->fill($provided);
        $beneficiary->save();
    }

    /**
     * @param  list<array<string, mixed>>  $candidates
     */
    private function firstRegistryReference(array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (($candidate['type'] ?? null) === 'registry' && ! empty($candidate['reference'])) {
                return (string) $candidate['reference'];
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function summary(SyncRun $run): array
    {
        return [
            'trigger' => $run->trigger->value,
            'source' => $run->source,
            'status' => $run->status->value,
            'fetched' => $run->fetched_count,
            'created' => $run->created_count,
            'updated' => $run->updated_count,
            'skipped' => $run->skipped_count,
            'flagged' => $run->flagged_count,
            'rejected' => $run->rejected_count,
            'errors' => $run->error_count,
        ];
    }
}
