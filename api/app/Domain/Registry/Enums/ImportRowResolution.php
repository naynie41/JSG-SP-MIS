<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * How the importing officer resolved a flagged import row (PRD FR-DUP-05, §9).
 *
 * - New  — adjudicate as a DISTINCT person and create a new beneficiary (requires a
 *          justification). Valid only for a **probable** (fuzzy) match; an exact
 *          match is definitive and is never adjudicated as new.
 * - Link — provide-service: do not create; raise a Service Request against the
 *          matched existing beneficiary. Available at every band.
 * - Skip — discard this row. Available at every band.
 *
 * A null resolution means "unresolved"; a non-flagged row defaults to New at commit.
 */
enum ImportRowResolution: string
{
    case New = 'new';
    case Link = 'link';
    case Skip = 'skip';
}
