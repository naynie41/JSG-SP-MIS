<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Imports\ColumnMapper;
use App\Domain\Registry\Imports\SpreadsheetReader;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportMappingTemplate;
use App\Domain\Registry\Support\CanonicalSchema;
use DomainException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * The Data Import & Mapping stage (CLAUDE.md §11, PRD v1.7): between raw upload and
 * validation/dedup.
 *
 * Three responsibilities, in order:
 *  1. PROFILE the uploaded file — read its headers, fingerprint its shape, and propose a
 *     mapping (from a saved template if one fits, otherwise from header heuristics).
 *  2. GUARD the confirmation — NIN, BVN, name and phone must be explicitly answered
 *     before anything is parsed. This is the rule the layer exists for.
 *  3. REMEMBER a confirmed mapping as a template for the next file of the same shape,
 *     which pre-fills but never pre-confirms.
 *
 * The uploaded file is only ever READ. The canonical representation lives in
 * `import_rows.payload`; the original is left exactly as it arrived.
 */
class ImportMappingService
{
    public function __construct(
        private readonly SpreadsheetReader $reader,
        private readonly ColumnMapper $mapper,
        private readonly AuditLogger $audit,
    ) {}

    /**
     * Read the file's columns and park the batch in `mapping_required`.
     *
     * Called at upload, before any parsing. A batch that cannot be read is failed here
     * rather than presenting an empty mapping screen the officer cannot act on.
     */
    public function profile(ImportBatch $batch): ImportBatch
    {
        try {
            $path = Storage::disk('local')->path($batch->stored_path);
            $extension = pathinfo($batch->stored_path, PATHINFO_EXTENSION);
            $headers = $this->reader->read($path, $extension)['headers'];
        } catch (\Throwable $e) {
            $batch->update([
                'status' => ImportStatus::Failed,
                'error' => 'Could not read the file: '.mb_substr($e->getMessage(), 0, 300),
            ]);

            return $batch;
        }

        $signature = $this->mapper->signature($headers);
        $template = $this->templateFor($batch, $signature);

        $batch->update([
            'detected_headers' => $headers,
            'source_signature' => $signature,
            // Pre-filled, NOT confirmed: `mapping_confirmed_at` stays null either way.
            'column_map' => $template?->column_map ?? [],
            'status' => ImportStatus::MappingRequired,
        ]);

        return $batch->fresh();
    }

    /**
     * What the mapping screen needs: the file's columns, the proposal, and which
     * identity fields are still unanswered.
     *
     * @return array<string, mixed>
     */
    public function proposal(ImportBatch $batch): array
    {
        $headers = $batch->detected_headers ?? [];
        $template = $batch->source_signature === null ? null : $this->templateFor($batch, $batch->source_signature);
        $confirmed = $batch->column_map ?? [];

        return [
            'detected_headers' => $headers,
            'suggestions' => $this->mapper->suggest($headers),
            'column_map' => $confirmed,
            'template' => $template === null ? null : ['id' => $template->id, 'name' => $template->name],
            'identity_fields' => CanonicalSchema::confirmationRequiredFields(),
            'unconfirmed_identity_fields' => $this->mapper->unconfirmedIdentityFields($confirmed),
            'unknown_headers' => $this->mapper->unknownHeaders($confirmed, $headers),
            'mapping_confirmed_at' => $batch->mapping_confirmed_at?->toIso8601String(),
        ];
    }

    /**
     * Confirm the mapping and release the batch for parsing.
     *
     * @param  array<string, string|null>  $columnMap
     *
     * @throws DomainException when an identity field is unanswered or a header is unknown
     */
    public function confirm(ImportBatch $batch, array $columnMap, User $by, ?string $saveTemplateAs = null): ImportBatch
    {
        $unconfirmed = $this->mapper->unconfirmedIdentityFields($columnMap);
        if ($unconfirmed !== []) {
            throw new DomainException(
                'Confirm which column holds each identity field (or mark it not present): '.implode(', ', $unconfirmed).'.'
            );
        }

        $unknown = $this->mapper->unknownHeaders($columnMap, $batch->detected_headers ?? []);
        if ($unknown !== []) {
            // Usually a stale template applied to a changed export. Silently mapping the
            // field to nothing would look like a source that simply omitted it.
            throw new DomainException('These columns are not in this file: '.implode(', ', $unknown).'.');
        }

        $batch->update([
            'column_map' => $columnMap,
            'mapping_confirmed_at' => Carbon::now(),
            'mapping_confirmed_by' => $by->id,
            'status' => ImportStatus::Pending,
        ]);

        // Audited with the MAPPING, not the data: which column was declared to hold the
        // NIN is the decision worth being able to review later (FR-AUD-01).
        $this->audit->record('import.mapping_confirmed', $batch, after: [
            'import_batch_id' => $batch->id,
            'source_signature' => $batch->source_signature,
            'column_map' => $columnMap,
            'identity_fields' => $this->identityMapSummary($columnMap),
        ], actor: $by);

        if ($saveTemplateAs !== null && $saveTemplateAs !== '') {
            $this->saveTemplate($batch, $columnMap, $by, $saveTemplateAs);
        }

        return $batch->fresh();
    }

    /**
     * Remember this mapping for the next file with the same shape. Updates the existing
     * template for that (MDA, source, signature) rather than accumulating near-copies.
     *
     * @param  array<string, string|null>  $columnMap
     */
    private function saveTemplate(ImportBatch $batch, array $columnMap, User $by, string $name): void
    {
        $template = ImportMappingTemplate::withoutGlobalScopes()->updateOrCreate(
            [
                'owner_mda_id' => $batch->owner_mda_id,
                'source' => $batch->source->value,
                'source_signature' => (string) $batch->source_signature,
            ],
            ['name' => $name, 'column_map' => $columnMap, 'created_by' => $by->id],
        );

        $this->audit->record('import.mapping_template_saved', $template, after: [
            'name' => $name,
            'source_signature' => $batch->source_signature,
            'column_map' => $columnMap,
        ], actor: $by);
    }

    /** The saved template for this batch's MDA, source and file shape, if any. */
    private function templateFor(ImportBatch $batch, string $signature): ?ImportMappingTemplate
    {
        return ImportMappingTemplate::withoutGlobalScopes()
            ->where('owner_mda_id', $batch->owner_mda_id)
            ->where('source', $batch->source->value)
            ->where('source_signature', $signature)
            ->first();
    }

    /**
     * Identity mappings as a reviewable summary — "nin ← national_id" or "bvn: not
     * present". This is what makes a wrong mapping auditable after the fact.
     *
     * @param  array<string, string|null>  $columnMap
     * @return array<string, string>
     */
    private function identityMapSummary(array $columnMap): array
    {
        $summary = [];
        foreach (CanonicalSchema::confirmationRequiredFields() as $field) {
            $summary[$field] = $columnMap[$field] ?? 'not present';
        }

        return $summary;
    }
}
