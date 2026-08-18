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
use App\Domain\Registry\Support\NormalizationService;
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
        private readonly NormalizationService $normalizer = new NormalizationService,
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

        // A saved template is the deliberate case. The ORDINARY case is an MDA uploading
        // the same export again having never named a template — so fall back to the last
        // mapping this MDA actually confirmed for this exact file shape. Without this,
        // recognising a layout depends on someone having thought to save one, and the
        // second file of a familiar shape is mapped from scratch.
        $previous = $template !== null ? null : $this->lastConfirmedBatchFor($batch, $signature);

        // Explicit rather than a chain of ?->/??: the two sources are mutually exclusive
        // by construction above, and spelling that out keeps it obvious which one won.
        $prefill = [];
        if ($template !== null) {
            $prefill = $template->column_map;
        } elseif ($previous !== null) {
            $prefill = $previous->column_map ?? [];
        }

        $batch->update([
            'detected_headers' => $headers,
            'source_signature' => $signature,
            // Pre-filled, NOT confirmed: `mapping_confirmed_at` stays null either way,
            // so the identity fields are still answered by a person every import
            // (CLAUDE.md §11).
            'column_map' => $prefill,
            // Which saved mapping this came from, so "what else used that template" is
            // answerable when one turns out to be wrong.
            'mapping_template_id' => $template?->id,
            // ...and where a template-less pre-fill came from, for the same reason.
            'mapping_prefilled_from_id' => $previous?->id,
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
        $sampleRows = $this->sampleRows($batch);

        return [
            'detected_headers' => $headers,
            'suggestions' => $this->mapper->suggest($headers),
            'column_map' => $confirmed,
            // A few real values per column. Deciding whether a column called
            // `national_id` actually holds NINs is guesswork from the header alone and
            // obvious from three values — this is what makes the confirmation a real
            // decision rather than a click-through.
            'samples' => $this->samples($headers, $sampleRows),
            'normalized_preview' => $this->normalizedPreview($confirmed, $sampleRows),
            'template' => $template === null ? null : ['id' => $template->id, 'name' => $template->name],
            // Where a pre-filled mapping came from, so the reviewer is not asked to
            // confirm choices that appeared from nowhere. Null when this shape is new.
            'prefilled_from' => $this->prefillProvenance($batch, $template),
            'identity_fields' => CanonicalSchema::confirmationRequiredFields(),
            'unconfirmed_identity_fields' => $this->mapper->unconfirmedIdentityFields($confirmed),
            'unknown_headers' => $this->mapper->unknownHeaders($confirmed, $headers),
            'mapping_confirmed_at' => $batch->mapping_confirmed_at?->toIso8601String(),
        ];
    }

    /**
     * The first few raw rows, read fresh from the stored file. The upload is only ever
     * READ — nothing here writes back to it.
     *
     * @return list<array<string, string>>
     */
    private function sampleRows(ImportBatch $batch, int $limit = 3): array
    {
        try {
            $path = Storage::disk('local')->path($batch->stored_path);
            $extension = pathinfo($batch->stored_path, PATHINFO_EXTENSION);
            $rows = $this->reader->read($path, $extension)['rows'];
        } catch (\Throwable) {
            return [];
        }

        return array_map(
            static fn (array $row): array => $row['values'],
            array_slice($rows, 0, $limit),
        );
    }

    /**
     * Up to three example values per source column, blanks skipped.
     *
     * @param  list<string>  $headers
     * @param  list<array<string, string>>  $rows
     * @return array<string, list<string>>
     */
    private function samples(array $headers, array $rows): array
    {
        $samples = [];
        foreach ($headers as $header) {
            $values = [];
            foreach ($rows as $row) {
                $value = trim((string) ($row[$header] ?? ''));
                if ($value !== '') {
                    $values[] = $value;
                }
            }
            $samples[$header] = $values;
        }

        return $samples;
    }

    /**
     * What the CURRENT mapping would produce: the value as written beside the value the
     * matcher will compare on.
     *
     * Shown before anything is committed, because this is where a wrong mapping becomes
     * visible — a "NIN" column normalising to something that is not eleven digits, or a
     * date read as the wrong month, is obvious here and invisible later.
     *
     * @param  array<string, string|null>  $columnMap
     * @param  list<array<string, string>>  $rows
     * @return list<array{field: string, header: string, original: string, normalized: ?string}>
     */
    private function normalizedPreview(array $columnMap, array $rows): array
    {
        if ($rows === []) {
            return [];
        }

        $first = $rows[0];
        $preview = [];

        foreach ($columnMap as $field => $header) {
            if ($header === null || ! array_key_exists($field, CanonicalSchema::FIELDS)) {
                continue;
            }

            $original = trim((string) ($first[$header] ?? ''));
            if ($original === '') {
                continue;
            }

            $preview[] = [
                'field' => $field,
                'header' => $header,
                'original' => $original,
                'normalized' => $this->normalizer->forField($field, $original),
            ];
        }

        return $preview;
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

        // A mapping with no name source at all guarantees every row fails validation on a
        // missing required name. Caught here, at the one decision point, rather than as
        // several hundred identical rejections after the file has been parsed.
        $first = $columnMap['first_name'] ?? null;
        $last = $columnMap['last_name'] ?? null;

        // Pointing first AND last name at the SAME column cannot be right: it stores the
        // whole name twice and produces "Rekiya Bagwai Rekiya Bagwai". It is the natural
        // thing to try when a file has one name column, which is exactly why it has to be
        // refused here rather than discovered in the data afterwards.
        if ($first !== null && $first === $last) {
            throw new DomainException(
                'First name and last name are both mapped to “'.$first.'”, which would store the whole name twice. '
                .'Map that column to “Full name (one column)” instead and leave first and last name not present — '
                .'SP-MIS will split it.'
            );
        }

        $hasSplitName = $first !== null && $last !== null;
        if (! $hasSplitName && ($columnMap['full_name'] ?? null) === null) {
            throw new DomainException(
                'This mapping has no name: point first and last name at columns, or map a single full-name column.'
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

        // The batch that CREATED a template is as much a user of it as one that was
        // pre-filled by it — both should turn up when auditing that template's reach.
        $batch->update(['mapping_template_id' => $template->id]);

        $this->audit->record('import.mapping_template_saved', $template, after: [
            'name' => $name,
            'source_signature' => $batch->source_signature,
            'column_map' => $columnMap,
        ], actor: $by);
    }

    /**
     * Describes where this batch's pre-filled mapping came from.
     *
     * Two kinds, kept distinct because they warrant different scrutiny: a TEMPLATE is a
     * deliberate reusable artefact someone named, while a PREVIOUS IMPORT is "we
     * recognised the layout from the last file you mapped". Naming the earlier file and
     * who confirmed it is what turns the review into a check rather than a formality.
     *
     * @return array<string, mixed>|null
     */
    private function prefillProvenance(ImportBatch $batch, ?ImportMappingTemplate $template): ?array
    {
        if ($template !== null) {
            return ['type' => 'template', 'name' => $template->name];
        }

        $previous = $batch->mapping_prefilled_from_id === null
            ? null
            : ImportBatch::withoutGlobalScopes()->with('mappingConfirmedBy')->find($batch->mapping_prefilled_from_id);

        if ($previous === null) {
            return null;
        }

        return [
            'type' => 'previous_import',
            'name' => $previous->original_filename,
            'confirmed_by' => $previous->mappingConfirmedBy?->name,
            'confirmed_at' => $previous->mapping_confirmed_at?->toIso8601String(),
        ];
    }

    /**
     * The most recent batch this MDA already CONFIRMED a mapping for, with exactly this
     * file shape — the source of a template-less pre-fill.
     *
     * Scoped to the same MDA and source deliberately: a column layout is a fact about
     * one agency's export, and another MDA's decision about which column holds a NIN is
     * not evidence about this one's file. Only confirmed batches count — an abandoned
     * batch's half-finished mapping is not a decision anyone made.
     */
    private function lastConfirmedBatchFor(ImportBatch $batch, string $signature): ?ImportBatch
    {
        return ImportBatch::withoutGlobalScopes()
            ->where('owner_mda_id', $batch->owner_mda_id)
            ->where('source', $batch->source->value)
            ->where('source_signature', $signature)
            ->whereNotNull('mapping_confirmed_at')
            ->whereKeyNot($batch->getKey()) // never itself, on a re-profile
            ->latest('mapping_confirmed_at')
            ->first();
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
