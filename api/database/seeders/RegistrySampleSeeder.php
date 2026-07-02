<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use Illuminate\Database\Seeder;

/**
 * Synthetic beneficiaries + households spread across the two sample MDAs, so MDA
 * data-scoping, ownership, and household membership can be seen/tested out of the
 * box. LOCAL/STAGING ONLY — never real PII, never production. Idempotent: no-op
 * once beneficiaries exist.
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
            $beneficiaries = Beneficiary::factory()->count(3)->create(['owner_mda_id' => $mda->id]);

            // One household per MDA grouping two of its beneficiaries, with a head.
            $household = Household::factory()->create([
                'owner_mda_id' => $mda->id,
                'head_beneficiary_id' => $beneficiaries[0]->id,
            ]);

            HouseholdMembership::factory()->create([
                'household_id' => $household->id,
                'beneficiary_id' => $beneficiaries[0]->id,
                'role_in_household' => HouseholdRole::Head,
                'left_at' => null,
            ]);
            HouseholdMembership::factory()->create([
                'household_id' => $household->id,
                'beneficiary_id' => $beneficiaries[1]->id,
                'role_in_household' => HouseholdRole::Child,
                'left_at' => null,
            ]);
        }
    }
}
