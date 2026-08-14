<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Imports\ColumnMapper;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Services\ImportMappingService;

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
}
