<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

use App\Domain\Registry\Services\ImportCommitter;

/**
 * How the importing officer resolved a flagged import row (PRD FR-DUP-05, §9).
 *
 * - New  — adjudicate as a DISTINCT person and create a new beneficiary (requires a
 *          justification). Valid only for a **probable** (fuzzy) match; an exact
 *          match is definitive and is never adjudicated as new.
 * - Link — provide-service: do not create; raise a Service Request against the
 *          matched existing beneficiary. Available at every band, and ONLY against a
 *          record owned by ANOTHER MDA.
 * - Own  — the match is a beneficiary this MDA ALREADY OWNS: a re-upload of its own
 *          data. Do not create a second record, and do not raise a Service Request —
 *          an MDA does not ask permission to serve its own beneficiary. The existing
 *          person stays and receives a NEW INTERVENTION under this batch's
 *          programme/activity, because re-uploading own data usually means delivering
 *          again. The duplicate ROW is blocked; the person is not.
 * - Skip — discard this row. Available at every band.
 *
 * A null resolution means "unresolved"; a non-flagged row defaults to New at commit.
 *
 * Link and Own are not interchangeable labels for one act — which applies is decided by
 * OWNERSHIP of the matched record, so {@see ImportCommitter} re-derives it from the data
 * at commit instead of trusting what a client stored.
 */
enum ImportRowResolution: string
{
    case New = 'new';
    case Link = 'link';
    case Own = 'own';
    case Skip = 'skip';

    /**
     * The two resolutions that act on an EXISTING matched record rather than creating
     * one. Both need `resolved_beneficiary_id`, and the committer decides between them
     * by ownership.
     *
     * @return list<self>
     */
    public static function againstExisting(): array
    {
        return [self::Link, self::Own];
    }
}
