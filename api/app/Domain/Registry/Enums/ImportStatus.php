<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Lifecycle of a bulk import batch (PRD FR-REG-02/06).
 *
 * pending → processing → preview_ready → (confirm) → committing → completed.
 * `failed` is terminal for an unrecoverable parse/commit error.
 */
enum ImportStatus: string
{
    case Pending = 'pending';
    /**
     * Uploaded and profiled, waiting for a human to confirm which source column is
     * which canonical field (CLAUDE.md §11). Nothing is parsed or screened in this
     * state — an unconfirmed identity mapping must never reach the duplicate cascade.
     */
    case MappingRequired = 'mapping_required';
    case Processing = 'processing';
    case PreviewReady = 'preview_ready';
    case Committing = 'committing';
    case Completed = 'completed';
    case Failed = 'failed';
}
