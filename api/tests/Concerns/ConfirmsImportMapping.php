<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Imports\ColumnMapper;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Services\ImportMappingService;
use App\Domain\Registry\Support\CanonicalSchema;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Services\ConnectorMappingService;

/**
 * Performs the mapping confirmation an officer performs, for tests whose subject is
 * something DOWNSTREAM of it (parsing, dedup, commit, households).
 *
 * Since the Data Import & Mapping layer (CLAUDE.md §11) an upload stops at
 * `mapping_required` until a human says which column holds the NIN, BVN, name and phone.
 * That gate is deliberately not skippable in application code — including for a file
 * whose headers already happen to be the canonical names, because "the column is called
 * nin" is exactly the assumption the rule exists to stop being made automatically.
 *
 * So tests satisfy it the way a user does: take the suggested mapping and accept it.
 */
trait ConfirmsImportMapping
{
    /**
     * Accept the suggested mapping for a profiled batch and run the pipeline.
     *
     * `ColumnMapper::suggest()` returns an entry for every canonical field — a header or
     * null — so accepting it answers every identity field, which is what the guard asks.
     */
    protected function confirmImportMapping(ImportBatch $batch, ?User $actor = null): ImportBatch
    {
        $suggestions = app(ColumnMapper::class)->suggest($batch->detected_headers ?? []);

        $columnMap = [];
        foreach ($suggestions as $field => $suggestion) {
            $columnMap[$field] = $suggestion['header'];
        }

        $actor ??= $batch->uploadedBy ?? User::factory()->create();

        app(ImportMappingService::class)->confirm($batch, $columnMap, $actor);

        ParseImportBatch::dispatchSync($batch->id);

        return $batch->fresh();
    }

    /**
     * Give a sync connector its STANDING mapping confirmation (CLAUDE.md §11).
     *
     * A connector runs unattended, so the approval is given once at configuration time
     * instead of per run — but it is still an approval a person gave, and a connector
     * without one refuses to run. Tests whose subject is downstream of that satisfy it
     * the way an administrator does.
     */
    protected function confirmConnectorMapping(SyncConnector $connector, ?User $actor = null): SyncConnector
    {
        /*
         * The mock sources return records keyed by the canonical names, so an
         * administrator confirming one would map each field to the field of the same
         * name. Built from the schema rather than from a live sample because a connector
         * is usually configured before its source has anything to return — and a mapping
         * confirmed against an EMPTY sample would answer "not present" for everything,
         * which the engine would faithfully apply by blanking every field.
         *
         * The household and provenance fields are deliberately left OUT: a field absent
         * from the map falls back to the adapter, which is what keeps source-specific
         * spellings working — a record id in `_id` or `instanceID`, a household
         * reference in `household_id` rather than `household_ref`.
         */
        $columnMap = [];
        foreach (CanonicalSchema::fields() as $field) {
            $columnMap[$field] = $field;
        }

        return app(ConnectorMappingService::class)
            ->confirm($connector, $columnMap, $actor ?? User::factory()->create());
    }
}
