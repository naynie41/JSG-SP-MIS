<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Phase 6P Funding-Partner demo data (PRD FR-RPT-02) — enough synthetic, activity-precise
 * data that EVERY partner tab renders meaningfully: the overview, per-programme results
 * (all four delivery statuses), the funded-cohort registry (with a reduced funnel), the
 * coordination landscape + PROGRAMME OVERLAP, and the investment/coverage map.
 *
 * Two development partners (World Bank + UNICEF) fund OVERLAPPING programmes in a SHARED
 * LGA through DIFFERENT MDAs (so the overlap detector fires), with committed budgets,
 * delivered benefits across historical periods, varied demographics (gender/age/NIN),
 * households, and enrolled-but-not-yet-served beneficiaries (so the funnel narrows).
 *
 * LOCAL/STAGING ONLY — never real PII (all factory-generated), never production. Idempotent.
 */
class PartnerDemoSeeder extends Seeder
{
    /** Demo sign-in password for the seeded partner accounts (LOCAL/STAGING ONLY). */
    public const DEMO_PASSWORD = 'partner-demo-1234';

    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }
        // Idempotent: seed once.
        if (User::query()->where('email', 'worldbank.partner@example.test')->exists()) {
            return;
        }

        $this->call([RolesAndPermissionsSeeder::class]);
        $roleId = Role::query()->where('key', RoleKey::DevelopmentPartner->value)->firstOrFail()->id;

        // Log-in-ready Development-Partner accounts (LOCAL/STAGING ONLY). Password is
        // hashed by the model cast; verified + active + MFA off so they sign in directly.
        $password = self::DEMO_PASSWORD;
        $worldBank = User::factory()->create(['name' => 'World Bank', 'email' => 'worldbank.partner@example.test', 'mda_id' => null, 'role_id' => $roleId, 'password' => $password]);
        $unicef = User::factory()->create(['name' => 'UNICEF', 'email' => 'unicef.partner@example.test', 'mda_id' => null, 'role_id' => $roleId, 'password' => $password]);

        // Reuse two existing MDAs (a populated dev DB already has them; names are unique),
        // otherwise mint them — so the overlap detector has two distinct implementing MDAs.
        $existing = Mda::query()->orderBy('created_at')->take(2)->get();
        $mdaA = $existing->get(0) ?? Mda::factory()->create(['name' => 'Ministry of Humanitarian Affairs']);
        $mdaB = $existing->get(1) ?? Mda::factory()->create(['name' => 'Ministry of Health']);

        $cct = Programme::factory()->individual()->create(['name' => 'Conditional Cash Transfer', 'status' => 'active']);
        $feeding = Programme::factory()->individual()->create(['name' => 'School Feeding', 'status' => 'active']);
        $health = Programme::factory()->individual()->create(['name' => 'Health Insurance', 'status' => 'active']);
        $youth = Programme::factory()->individual()->create(['name' => 'Youth Skills Training', 'status' => 'active']);
        $livelihood = Programme::factory()->individual()->create(['name' => 'Livelihood Support', 'status' => 'active']);

        $future = Carbon::now()->addMonths(6)->toDateString();
        $past = Carbon::now()->subMonths(1)->toDateString();

        // World Bank — four programmes calibrated to the four delivery statuses.
        $this->fund($worldBank, $cct, $mdaA, 'dutse', 5_000_000, 20, 17, $future, ['cash']);          // on track (0.85)
        $this->fund($worldBank, $cct, $mdaA, 'hadejia', 3_000_000, 15, 12, $future, ['cash']);         // (same programme, 2nd LGA)
        $this->fund($worldBank, $feeding, $mdaB, 'gumel', 3_000_000, 20, 19, $past, ['food']);         // completed (past end, 0.95)
        $this->fund($worldBank, $health, $mdaA, 'birnin_kudu', 4_000_000, 20, 12, $future, ['health']); // at risk (0.6)
        $this->fund($worldBank, $youth, $mdaB, 'kazaure', 2_000_000, 20, 4, $future, ['training']);    // delayed (0.2)

        // UNICEF — funds the SAME programme (CCT) in the SAME LGA (dutse) via a DIFFERENT
        // MDA → programme overlap; plus a sole-funded programme.
        $this->fund($unicef, $cct, $mdaB, 'dutse', 2_500_000, 15, 12, $future, ['cash']);
        $this->fund($unicef, $livelihood, $mdaA, 'jahun', 1_800_000, 15, 10, $future, ['cash', 'training']);

        app(DashboardSnapshotService::class)->refreshAll();

        $this->command->info('Partner demo seeded. Log in as a Development Partner:');
        $this->command->info("  worldbank.partner@example.test / {$password}   (funds 4 programmes; overlap + all 4 statuses)");
        $this->command->info("  unicef.partner@example.test    / {$password}   (co-funds CCT in Dutse -> overlap)");
    }

    /**
     * Fund an activity (committed budget) and deliver benefits to `$reached` distinct
     * beneficiaries across recent months, with varied demographics + a couple of
     * enrolled-but-not-yet-served beneficiaries so the reduced funnel narrows.
     *
     * @param  list<string>  $types  benefit types to cycle through
     */
    private function fund(User $partner, Programme $programme, Mda $mda, string $lga, int $budget, int $target, int $reached, ?string $endsOn, array $types): void
    {
        $activity = Activity::factory()->forProgramme($programme, $mda)->inLgaCode($lga)->create([
            'status' => 'active',
            'budget_amount' => $budget, 'target_beneficiaries' => $target,
            'funding_partner_id' => $partner->id,
            'starts_on' => Carbon::now()->subMonths(6)->toDateString(),
            'ends_on' => $endsOn,
        ]);

        for ($i = 0; $i < $reached; $i++) {
            $factory = Beneficiary::factory();
            if ($i % 4 === 0) {
                $factory = $factory->withoutNin(); // NIN-linkage variety for data quality
            }
            $ward = 'Ward '.(1 + $i % 6);
            $beneficiary = $factory->create([
                'owner_mda_id' => $mda->id, 'lga' => $lga, 'ward' => $ward,
                'gender' => $i % 3 === 0 ? 'male' : 'female',
                'date_of_birth' => Carbon::today()->subYears([8, 25, 42, 67][$i % 4])->toDateString(),
                'registration_date' => Carbon::now()->subMonths(1 + $i % 5)->toDateString(),
            ]);

            if ($i % 3 === 0) {
                HouseholdMembership::factory()->create([
                    'beneficiary_id' => $beneficiary->id,
                    'household_id' => Household::factory()->create(['owner_mda_id' => $mda->id, 'lga' => $lga])->id,
                ]);
            }

            Enrollment::factory()->create([
                'programme_id' => $programme->id, 'activity_id' => $activity->id, 'mda_id' => $mda->id,
                'beneficiary_id' => $beneficiary->id, 'status' => 'enrolled',
            ]);

            Benefit::factory()->create([
                'beneficiary_id' => $beneficiary->id, 'programme_id' => $programme->id, 'activity_id' => $activity->id,
                'mda_id' => $mda->id, 'benefit_type' => $types[$i % count($types)],
                // Kept below the per-activity budget so delivered value ≤ committed funding.
                'monetary_value' => 120_000 + ($i % 4) * 20_000, 'lga' => $lga, 'ward' => $ward,
                'delivery_date' => Carbon::now()->subMonths($i % 3)->toDateString(), 'status' => BenefitStatus::Verified,
            ]);
        }

        // Enrolled-but-not-yet-served (Registered → Enrolled → Receiving narrows).
        for ($j = 0; $j < 2; $j++) {
            $pending = Beneficiary::factory()->create([
                'owner_mda_id' => $mda->id, 'lga' => $lga, 'gender' => 'female',
                'date_of_birth' => Carbon::today()->subYears(30)->toDateString(),
            ]);
            Enrollment::factory()->create([
                'programme_id' => $programme->id, 'activity_id' => $activity->id, 'mda_id' => $mda->id,
                'beneficiary_id' => $pending->id, 'status' => 'enrolled',
            ]);
        }
    }
}
