<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * The `mda_programme` row: one MDA has been assigned one catalog programme, and may
 * therefore deliver it (§10, revised 2026-11-05).
 *
 * A named pivot rather than the generic one, because the row carries meaning of its
 * own. `source` says what kind of claim it is, and the four values are a real
 * distinction an administrator reads off the console before changing anything:
 *
 *  - `inventory` — from the MDA programme inventory the catalog was seeded from. The
 *    weakest claim: a list someone compiled, not a decision taken in the system.
 *  - `derived` — inferred from an activity the MDA had already built under the
 *    programme, by the migration that introduced assignments or by a fixture. Honest
 *    but circumstantial: it records what was happening, not what was decided.
 *  - `mda` — written when the System Administrator APPROVED a programme the MDA created
 *    for itself. A decision, on that MDA's own submission.
 *  - `assignment` — a catalog administrator chose it, and `assigned_by` names them.
 *
 * `assigned_by` is nullable forever: only the last kind has an assigning administrator,
 * and putting a name against the other three would attribute a decision nobody made.
 *
 * Not Auditable. The pivot is written by {@see ProgrammeAssignmentService}, which audits
 * the whole change as one `mda.programmes_assigned` entry against the MDA — a row-level
 * trail would report six inserts and a delete where one administrator saved one list.
 *
 * @property string $id
 * @property string $mda_id
 * @property string $programme_id
 * @property string $source
 * @property string|null $assigned_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ProgrammeAssignment extends Pivot
{
    protected $table = 'mda_programme';

    /** The pivot carries a UUID primary key of its own, not a composite key. */
    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;
}
