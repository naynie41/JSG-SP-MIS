<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Seeded records must sit in a real Jigawa LGA.
 *
 * `HouseholdFactory` used `fake()->city()`, so households landed in invented places —
 * "Abernathyburgh", "Sterlingberg". Nothing rejected them, because a factory bypasses
 * the import validation that would have. They then surfaced as coverage rows the map
 * could never draw, and the coverage panel counted "27 of 29 areas" for a state with 27.
 *
 * An LGA value outside {@see Lga} is data the application itself would refuse on import;
 * a fixture has no licence to create it.
 */
class FactoryAreaValuesTest extends TestCase
{
    use RefreshDatabase;

    /** @return list<string> */
    private function codes(): array
    {
        return array_map(fn (Lga $lga) => $lga->value, Lga::cases());
    }

    public function test_a_seeded_household_sits_in_a_real_lga(): void
    {
        $codes = $this->codes();

        // Many, because the failure was a random generator: one draw could pass by luck.
        foreach (Household::factory()->count(30)->create() as $household) {
            $this->assertContains($household->lga, $codes, "Household LGA [{$household->lga}] is not a Jigawa LGA.");
        }
    }

    public function test_a_seeded_beneficiary_sits_in_a_real_lga(): void
    {
        $codes = $this->codes();

        foreach (Beneficiary::factory()->count(30)->create() as $beneficiary) {
            $this->assertContains($beneficiary->lga, $codes, "Beneficiary LGA [{$beneficiary->lga}] is not a Jigawa LGA.");
        }
    }
}
