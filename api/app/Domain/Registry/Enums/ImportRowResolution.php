<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * How the importing officer resolved a flagged import row (PRD FR-DUP-05).
 *
 * - New  — create a new beneficiary despite the potential duplicate (requires a
 *          justification).
 * - Link — do not create; request to serve the matched existing beneficiary.
 * - Skip — do nothing with this row.
 *
 * A null resolution means "unresolved"; a non-flagged row defaults to New at commit.
 */
enum ImportRowResolution: string
{
    case New = 'new';
    case Link = 'link';
    case Skip = 'skip';
}
