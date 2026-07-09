<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Models\Mda;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Contracts\BeneficiaryRouter;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FR-OWN-04 auto-route hook under the catalog model (§10): programmes are global, so
 * the suggested MDA is one that RUNS the matching programme via an activity.
 */
class ProgrammeMatchingRouterTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggests_an_mda_that_runs_the_matching_catalog_programme(): void
    {
        $runner = Mda::factory()->create(['name' => 'Runner MDA']);
        $beneficiary = Beneficiary::factory()->create(['lga' => 'dutse']);

        // A global catalog programme the beneficiary is eligible for…
        $programme = Programme::factory()->individual()->create([
            'status' => 'active',
            'eligibility' => [['attribute' => 'lga', 'value' => 'dutse']],
        ]);
        // …run by an MDA through its own activity.
        Activity::factory()->forProgramme($programme, $runner)->create();

        $suggested = app(BeneficiaryRouter::class)->suggestMdaFor($beneficiary);

        $this->assertSame($runner->id, $suggested);
    }

    public function test_suggests_no_mda_when_the_matching_programme_is_unrun(): void
    {
        $beneficiary = Beneficiary::factory()->create(['lga' => 'dutse']);

        // A matching catalog programme with NO activity (no MDA runs it yet).
        Programme::factory()->individual()->create([
            'status' => 'active',
            'eligibility' => [['attribute' => 'lga', 'value' => 'dutse']],
        ]);

        $this->assertNull(app(BeneficiaryRouter::class)->suggestMdaFor($beneficiary));
    }
}
