<?php

declare(strict_types=1);

namespace App\Domain\Library\Enums;

/**
 * The publication lifecycle of a library item.
 *
 * This enum is the boundary between the administration console and the open
 * internet, so it is worth being precise about each value:
 *
 *  - Draft     — being prepared. Never served publicly, not even by direct id.
 *  - Published — visible on /resources and downloadable by anyone, no login.
 *  - Archived  — withdrawn. Stops serving immediately, but the row and its file are
 *                retained: download counts and the audit trail are history, and
 *                CLAUDE.md §10 forbids hard-deleting a record that carries any.
 *
 * Archived is deliberately distinct from a soft delete. Archiving is an editorial
 * act the administrator takes and can reverse; the `deleted_at` column is for
 * genuine removal of something created in error.
 */
enum LibraryItemStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    /** The one question the public endpoints ask. */
    public function isPublic(): bool
    {
        return $this === self::Published;
    }
}
