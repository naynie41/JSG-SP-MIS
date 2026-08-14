<?php

declare(strict_types=1);

namespace App\Domain\Sync\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Imports\ColumnMapper;
use App\Domain\Registry\Support\CanonicalSchema;
use App\Domain\Registry\Support\NormalizationService;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Sources\SyncSourceResolver;
use DomainException;
use Illuminate\Support\Carbon;

/**
 * Column mapping for a SYNC CONNECTOR (CLAUDE.md §11).
 *
 * A file import asks a person which column holds the NIN every time. A connector cannot:
 * it runs on a schedule with nobody present. Removing the question for sync would be the
 * wrong resolution — a connector ingests continuously, so a wrong identity mapping would
 * merge citizens on every run rather than once.
 *
 * The confirmation therefore moves to CONFIGURATION time and stands for later runs. What
 * is preserved is the property that matters: no record reaches the duplicate cascade
 * under an identity mapping no person ever approved.
 *
 * The standing approval is bounded by the source's SHAPE. It was given for records that
 * looked a particular way; if the source starts returning a different set of fields, the
 * approval no longer covers what is arriving and the connector stops.
 */
class ConnectorMappingService
{
    public function __construct(
        private readonly SyncSourceResolver $sources,
        private readonly ColumnMapper $mapper,
        private readonly AuditLogger $audit,
        private readonly NormalizationService $normalizer = new NormalizationService,
    ) {}

    /**
     * Fetch a small sample from the source and propose a mapping for it — the connector
     * equivalent of profiling an uploaded file.
     *
     * @return array<string, mixed>
     */
    public function proposal(SyncConnector $connector): array
    {
        $sample = $this->sampleRecords($connector);
        $fields = $this->fieldsIn($sample);
        $confirmed = $connector->column_map ?? [];

        return [
            'detected_fields' => $fields,
            'suggestions' => $this->mapper->suggest($fields),
            'column_map' => $confirmed,
            'samples' => $this->samples($fields, $sample),
            'normalized_preview' => $this->normalizedPreview($confirmed, $sample),
            'identity_fields' => CanonicalSchema::confirmationRequiredFields(),
            'unconfirmed_identity_fields' => $this->mapper->unconfirmedIdentityFields($confirmed),
            'source_signature' => $this->mapper->signature($fields),
            'confirmed_signature' => $connector->source_signature,
            // True when the source's shape has moved since the mapping was approved —
            // the standing confirmation no longer covers what is arriving.
            'signature_changed' => $connector->source_signature !== null
                && $connector->source_signature !== $this->mapper->signature($fields),
            'mapping_confirmed_at' => $connector->mapping_confirmed_at?->toIso8601String(),
        ];
    }

    /**
     * Approve the mapping for this connector. Same identity guard as a file import:
     * NIN, BVN, name and phone must each be answered — a field, or explicitly null.
     *
     * @param  array<string, string|null>  $columnMap
     *
     * @throws DomainException when an identity field is unanswered
     */
    public function confirm(SyncConnector $connector, array $columnMap, User $by): SyncConnector
    {
        $unconfirmed = $this->mapper->unconfirmedIdentityFields($columnMap);
        if ($unconfirmed !== []) {
            throw new DomainException(
                'Confirm which source field holds each identity value (or mark it not present): '
                .implode(', ', $unconfirmed).'.'
            );
        }

        /*
         * Only bound the approval by shape when we could actually see the shape. If the
         * source returned nothing to sample, recording a signature for "no fields" would
         * block the first real run — claiming the schema had changed when in truth we
         * never observed one. A null signature means unbounded, which is honest.
         */
        $sample = $this->sampleRecords($connector);
        $signature = $sample === [] ? null : $this->mapper->signature($this->fieldsIn($sample));

        $connector->update([
            'column_map' => $columnMap,
            'source_signature' => $signature,
            'mapping_confirmed_at' => Carbon::now(),
            'mapping_confirmed_by' => $by->id,
            // Re-confirming IS the review a stale connector was waiting for.
            'mapping_stale_at' => null,
            'mapping_stale_reason' => null,
        ]);

        // A standing approval is a bigger commitment than a one-off import mapping, so
        // it is audited with the same detail: which field was declared to hold the NIN.
        $this->audit->record('sync.mapping_confirmed', $connector, after: [
            'connector_id' => $connector->id,
            'source' => $connector->source->value,
            'source_signature' => $signature,
            'column_map' => $columnMap,
            'identity_fields' => $this->identitySummary($columnMap),
        ], actor: $by);

        return $connector->fresh();
    }

    /**
     * Why this connector may not run, or null when it may.
     *
     * Returned as a reason rather than thrown so {@see SyncEngine} can record it on the
     * run — a scheduled job that simply stopped, with no explanation anywhere, is how a
     * connector goes quietly dead for a month.
     */
    public function blockedReason(SyncConnector $connector): ?string
    {
        if (! $connector->mappingIsConfirmed()) {
            return 'This connector has no confirmed column mapping. Confirm which source field holds the NIN, BVN, name and phone before it can run.';
        }

        if ($connector->mappingIsStale()) {
            // Held on the persisted flag, so a stale connector is not even contacted —
            // and the hold survives between runs instead of being rediscovered each time.
            return 'This connector’s column mapping needs review: '
                .($connector->mapping_stale_reason ?? 'the source’s fields changed since it was confirmed.')
                .' Re-confirm the identity fields before it can run again.';
        }

        return null;
    }

    /**
     * Flag the standing approval as no longer describing what the source is sending.
     *
     * Recorded rather than merely thrown, so the condition is visible in the connector
     * list between runs — the alternative is an administrator discovering weeks later
     * that a feed stopped, by reading run logs.
     */
    public function markStale(SyncConnector $connector, string $reason, ?User $by = null): SyncConnector
    {
        if ($connector->mappingIsStale()) {
            return $connector;
        }

        $connector->update([
            'mapping_stale_at' => Carbon::now(),
            'mapping_stale_reason' => $reason,
        ]);

        $this->audit->record('sync.mapping_stale', $connector, after: [
            'connector_id' => $connector->id,
            'reason' => $reason,
            'confirmed_signature' => $connector->source_signature,
        ], actor: $by);

        return $connector->fresh();
    }

    /**
     * Whether the records arriving still match the shape the mapping was approved for.
     *
     * @param  list<array<string, mixed>>  $records
     */
    public function signatureMatches(SyncConnector $connector, array $records): bool
    {
        if ($connector->source_signature === null || $records === []) {
            return true;
        }

        return $connector->source_signature === $this->mapper->signature($this->fieldsIn($records));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function sampleRecords(SyncConnector $connector, int $limit = 3): array
    {
        try {
            $records = $this->sources->for($connector)->fetch($connector);
        } catch (\Throwable) {
            return [];
        }

        $sample = [];
        foreach ($records as $record) {
            $sample[] = $record;
            if (count($sample) >= $limit) {
                break;
            }
        }

        return $sample;
    }

    /**
     * The union of keys across the sampled records — a source may omit an empty field on
     * one record and include it on the next.
     *
     * @param  list<array<string, mixed>>  $records
     * @return list<string>
     */
    private function fieldsIn(array $records): array
    {
        $fields = [];
        foreach ($records as $record) {
            foreach (array_keys($record) as $key) {
                $fields[(string) $key] = true;
            }
        }

        return array_keys($fields);
    }

    /**
     * @param  list<string>  $fields
     * @param  list<array<string, mixed>>  $records
     * @return array<string, list<string>>
     */
    private function samples(array $fields, array $records): array
    {
        $samples = [];
        foreach ($fields as $field) {
            $values = [];
            foreach ($records as $record) {
                $value = trim((string) ($record[$field] ?? ''));
                if ($value !== '') {
                    $values[] = $value;
                }
            }
            $samples[$field] = $values;
        }

        return $samples;
    }

    /**
     * @param  array<string, string|null>  $columnMap
     * @param  list<array<string, mixed>>  $records
     * @return list<array{field: string, header: string, original: string, normalized: ?string}>
     */
    private function normalizedPreview(array $columnMap, array $records): array
    {
        if ($records === []) {
            return [];
        }

        $preview = [];
        foreach ($columnMap as $field => $source) {
            if ($source === null || ! array_key_exists($field, CanonicalSchema::FIELDS)) {
                continue;
            }

            $original = trim((string) ($records[0][$source] ?? ''));
            if ($original === '') {
                continue;
            }

            $preview[] = [
                'field' => $field,
                'header' => $source,
                'original' => $original,
                'normalized' => $this->normalizer->forField($field, $original),
            ];
        }

        return $preview;
    }

    /**
     * @param  array<string, string|null>  $columnMap
     * @return array<string, string>
     */
    private function identitySummary(array $columnMap): array
    {
        $summary = [];
        foreach (CanonicalSchema::confirmationRequiredFields() as $field) {
            $summary[$field] = $columnMap[$field] ?? 'not present';
        }

        return $summary;
    }
}
