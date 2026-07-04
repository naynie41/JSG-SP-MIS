<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\BenefitType;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\DoubleDippingDetector;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Illuminate\Database\Seeder;

/**
 * Synthetic Phase 4 sample data (PRD FR-PRG/FR-BEN): individual + household
 * programmes with activities, enrolments, and a spread of recorded benefits across
 * both sample MDAs — including a cross-MDA double-dipping case that raises a flag.
 * LOCAL/STAGING ONLY — never real PII, never production. Idempotent (no-op once any
 * programme exists). Money values are data only (kobo); no money is moved.
 */
class ProgrammeSampleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production') || Programme::query()->exists()) {
            return;
        }

        $health = Mda::query()->where('name', 'Ministry of Health')->first();
        $women = Mda::query()->where('name', 'Ministry of Women Affairs & Social Development')->first();
        if ($health === null || $women === null) {
            return;
        }

        $healthBeneficiaries = Beneficiary::query()->where('owner_mda_id', $health->id)->get();
        $womenBeneficiaries = Beneficiary::query()->where('owner_mda_id', $women->id)->get();
        if ($healthBeneficiaries->isEmpty() || $womenBeneficiaries->isEmpty()) {
            return;
        }

        // --- MDA A (Health): an individual cash programme + a household food programme.
        $cash = $this->programme($health, 'Conditional Cash Transfer', 'individual', 50_000_000);
        $cashActivity = Activity::factory()->forProgramme($cash)->create(['name' => 'Q1 Cash Round', 'budget_amount' => 20_000_000]);
        foreach ($healthBeneficiaries->take(2) as $beneficiary) {
            $this->enrol($cash, beneficiary: $beneficiary);
            $this->deliver($beneficiary, $cash, $cashActivity, $health, BenefitType::Cash, 3_000_000, verified: true);
        }

        $food = $this->programme($health, 'Household Food Support', 'household', 30_000_000);
        $foodActivity = Activity::factory()->forProgramme($food)->create(['name' => 'Dry-season Distribution', 'budget_amount' => 15_000_000]);
        $household = Household::query()->where('owner_mda_id', $health->id)->first();
        if ($household !== null) {
            $this->enrol($food, household: $household);
            $this->deliver($healthBeneficiaries->first(), $food, $foodActivity, $health, BenefitType::Food, 1_200_000, verified: false);
        }

        // --- MDA B (Women Affairs): an individual livelihood programme.
        $grant = $this->programme($women, 'Women Livelihood Grant', 'individual', 40_000_000);
        $grantActivity = Activity::factory()->forProgramme($grant)->create(['name' => 'Grant Disbursement', 'budget_amount' => 25_000_000]);
        foreach ($womenBeneficiaries->take(2) as $beneficiary) {
            $this->enrol($grant, beneficiary: $beneficiary);
            $this->deliver($beneficiary, $grant, $grantActivity, $women, BenefitType::Cash, 4_000_000, verified: true);
        }

        // --- Double-dipping case: a Health-owned beneficiary also served by Women
        // Affairs; both deliver CASH within the window → a flag is raised (never blocks).
        $shared = $healthBeneficiaries->first();
        $this->enrol($grant, beneficiary: $shared); // Women Affairs serves a non-owned beneficiary
        $benefitA = $this->deliver($shared, $cash, $cashActivity, $health, BenefitType::Cash, 3_000_000, verified: true);
        $benefitB = $this->deliver($shared, $grant, $grantActivity, $women, BenefitType::Cash, 3_500_000, verified: false);
        app(DoubleDippingDetector::class)->check($benefitB);
        app(DoubleDippingDetector::class)->check($benefitA);
    }

    private function programme(Mda $mda, string $name, string $type, int $budgetKobo): Programme
    {
        return Programme::factory()->{$type}()->create([
            'owner_mda_id' => $mda->id,
            'name' => $name,
            'status' => 'active',
            'budget_amount' => $budgetKobo,
            'eligibility' => null,
        ]);
    }

    private function enrol(Programme $programme, ?Beneficiary $beneficiary = null, ?Household $household = null): void
    {
        Enrollment::query()->firstOrCreate(
            [
                'programme_id' => $programme->id,
                'beneficiary_id' => $beneficiary?->id,
                'household_id' => $household?->id,
                'status' => EnrollmentStatus::Enrolled->value,
            ],
            [
                'mda_id' => $programme->owner_mda_id,
                'enrolled_on' => now()->toDateString(),
            ],
        );
    }

    private function deliver(Beneficiary $beneficiary, Programme $programme, Activity $activity, Mda $mda, BenefitType $type, int $valueKobo, bool $verified): Benefit
    {
        return Benefit::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'programme_id' => $programme->id,
            'activity_id' => $activity->id,
            'mda_id' => $mda->id,
            'benefit_type' => $type,
            'monetary_value' => $valueKobo,
            'quantity' => $type === BenefitType::Food ? 2 : 1,
            'unit' => $type === BenefitType::Food ? 'bags' : 'transfer',
            'delivery_date' => now()->toDateString(),
            'lga' => $beneficiary->lga,
            'ward' => $beneficiary->ward,
            'status' => $verified ? BenefitStatus::Verified : BenefitStatus::Recorded,
            'verification_method' => $verified ? VerificationMethod::FieldConfirmation : VerificationMethod::None,
            'verification_reference' => $verified ? 'Field-confirmed (sample)' : null,
        ]);
    }
}
