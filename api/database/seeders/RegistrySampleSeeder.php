<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Seeder;

/**
 * Synthetic beneficiaries spread across the two sample MDAs, so MDA data-scoping
 * can be seen/tested out of the box. LOCAL/STAGING ONLY — never real PII, never
 * production. Idempotent: no-op once beneficiaries exist.
 */
class RegistrySampleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production') || Beneficiary::query()->exists()) {
            return;
        }

        $mdas = Mda::query()
            ->whereIn('name', ['Ministry of Health', 'Ministry of Women Affairs & Social Development'])
            ->get();

        foreach ($mdas as $mda) {
            Beneficiary::factory()->count(3)->create(['owner_mda_id' => $mda->id]);
        }
    }
}
