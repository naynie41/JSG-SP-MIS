<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Models\Programme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Assign a catalog programme to an MDA in a test's arrangement (§10, revised
 * 2026-11-05).
 *
 * Since that revision an MDA sees and may use only the programmes an administrator
 * assigned it, so a test that puts an MDA to work under a catalog programme has to
 * say that the MDA was assigned it. Written straight to the pivot rather than through
 * `PUT /mdas/{mda}/programmes`, so arranging a test needs no administrator account and
 * does not depend on the endpoint under test elsewhere.
 */
trait AssignsProgrammes
{
    /**
     * @param  Programme|list<Programme>  $programmes
     */
    protected function assignProgrammes(Mda $mda, Programme|array $programmes, string $source = 'inventory'): void
    {
        $rows = [];

        foreach (is_array($programmes) ? $programmes : [$programmes] as $programme) {
            $already = DB::table('mda_programme')
                ->where('mda_id', $mda->id)
                ->where('programme_id', $programme->id)
                ->exists();

            if ($already) {
                continue;
            }

            $rows[] = [
                'id' => (string) Str::uuid(),
                'mda_id' => $mda->id,
                'programme_id' => $programme->id,
                'source' => $source,
                'assigned_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($rows !== []) {
            DB::table('mda_programme')->insert($rows);
        }
    }
}
