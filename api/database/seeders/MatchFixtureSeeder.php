<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Seeder;

/**
 * A small, LABELLED, fully synthetic duplicate-matching fixture set (PRD
 * FR-DUP-01/03) spread across two MDAs so cross-MDA detection ("register once,
 * serve many") is exercised. Never contains real PII.
 *
 * The seeder plants the EXISTING registry records; the accuracy tests plant these
 * and then run the labelled {@see self::probes()} (incoming candidates with a
 * known expected outcome) through the real matching engine to measure
 * precision/recall and assert band behaviour. Idempotent.
 *
 * Deliberately NOT part of DatabaseSeeder — it exists for tests and for manually
 * demoing the duplicate flow (run explicitly with `db:seed --class`).
 */
class MatchFixtureSeeder extends Seeder
{
    public const MDA_A = 'Fixture MDA — Health';

    public const MDA_B = 'Fixture MDA — Women Affairs';

    public function run(): void
    {
        $mdas = [
            'A' => $this->mda(self::MDA_A),
            'B' => $this->mda(self::MDA_B),
        ];

        foreach (self::existing() as $record) {
            $owner = $mdas[$record['mda']];
            Beneficiary::updateOrCreate(
                [
                    'owner_mda_id' => $owner->id,
                    'first_name' => $record['first_name'],
                    'last_name' => $record['last_name'],
                    'date_of_birth' => $record['date_of_birth'],
                ],
                [
                    'registration_source' => RegistrationSource::Manual,
                    'registration_date' => now()->toDateString(),
                    'nin' => $record['nin'] ?? null,
                    'bvn' => null,
                    'phone' => $record['phone'] ?? null,
                    'gender' => $record['gender'] ?? Gender::Male,
                    'address' => $record['address'] ?? null,
                    'lga' => $record['lga'],
                    'ward' => $record['ward'],
                    'status' => BeneficiaryStatus::Active,
                    'block_name_dob' => Beneficiary::blockNameDobFor($record['last_name'], $record['date_of_birth']),
                ],
            );
        }
    }

    private function mda(string $name): Mda
    {
        return Mda::updateOrCreate(
            ['name' => $name],
            [
                'type' => MdaType::Ministry,
                'contact_person' => 'Fixture Desk',
                'contact_email' => str($name)->slug().'@fixture.local',
                'status' => MdaStatus::Active,
            ],
        );
    }

    /**
     * The existing registry records, labelled and split across the two MDAs.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function existing(): array
    {
        return [
            // Strong-identifier records (NIN present) — deterministic targets.
            'E1' => ['mda' => 'A', 'nin' => '10000000001', 'first_name' => 'Mohammed', 'last_name' => 'Saani', 'date_of_birth' => '1990-05-10', 'phone' => '08031234567', 'lga' => 'dutse', 'ward' => 'Ward 9', 'address' => '5 Market Rd', 'gender' => Gender::Male],
            'E2' => ['mda' => 'B', 'nin' => '10000000002', 'first_name' => 'Ibrahim', 'last_name' => 'Yusuf', 'date_of_birth' => '1979-11-30', 'phone' => '08030000004', 'lga' => 'hadejia', 'ward' => 'Ward 5', 'address' => '10 Unity Road', 'gender' => Gender::Male],
            // No-identifier record — fuzzy target.
            'E3' => ['mda' => 'A', 'nin' => null, 'first_name' => 'Aisha', 'last_name' => 'Bello', 'date_of_birth' => '1985-06-15', 'phone' => '08030000003', 'lga' => 'kazaure', 'ward' => 'Ward 2', 'gender' => Gender::Female],
            // Unrelated record so the registry is not trivially small.
            'E4' => ['mda' => 'B', 'nin' => '10000000004', 'first_name' => 'Grace', 'last_name' => 'Adamu', 'date_of_birth' => '2000-01-01', 'phone' => '08039999999', 'lga' => 'ringim', 'ward' => 'Ward 1', 'gender' => Gender::Female],
        ];
    }

    /**
     * Labelled incoming candidates with the expected outcome. `band` is the
     * expected top-match band; `duplicate` is the ground-truth label used for
     * precision/recall; `owner` is the MDA that owns the record it should match.
     *
     * @return array<string, array{query: array<string, mixed>, band: string, duplicate: bool, owner: ?string}>
     */
    public static function probes(): array
    {
        return [
            // Exact — deterministic NIN match (name spelled differently).
            'exact NIN (same MDA)' => [
                'query' => ['nin' => '10000000001', 'first_name' => 'Muhammadu', 'last_name' => 'Sani', 'date_of_birth' => '1990-05-10', 'phone' => '08031234567', 'lga' => 'dutse', 'ward' => 'Ward 3'],
                'band' => 'exact', 'duplicate' => true, 'owner' => 'A',
            ],
            // Exact — deterministic NIN match against a record owned by the OTHER MDA.
            'exact NIN (cross-MDA)' => [
                'query' => ['nin' => '10000000002', 'first_name' => 'Ibrahim', 'last_name' => 'Yusufu', 'date_of_birth' => '1979-11-30'],
                'band' => 'exact', 'duplicate' => true, 'owner' => 'B',
            ],
            // Probable — fuzzy (spelling variant + DOB typo, no identifier).
            'probable fuzzy (spelling + DOB typo)' => [
                'query' => ['first_name' => 'Aishatu', 'last_name' => 'Bello', 'date_of_birth' => '1985-06-18', 'phone' => '08030000003', 'lga' => 'kazaure', 'ward' => 'Ward 7'],
                'band' => 'probable', 'duplicate' => true, 'owner' => 'A',
            ],
            // Strong fuzzy — near-identical but no identifier supplied. Stays PROBABLE:
            // fuzzy-only scoring caps below the auto-accept threshold (address isn't a
            // blocking/gathered field), so it never silently auto-accepts as exact.
            'strong fuzzy (near-identical, no identifier)' => [
                'query' => ['first_name' => 'Ibrahim', 'last_name' => 'Yusuf', 'date_of_birth' => '1979-11-30', 'phone' => '08030000004', 'lga' => 'hadejia', 'ward' => 'Ward 5', 'address' => '10 Unity Road'],
                'band' => 'probable', 'duplicate' => true, 'owner' => 'B',
            ],
            // Near-miss — shares the surname+DOB-year block with E3 but is a different person.
            'near-miss (same surname/year block)' => [
                'query' => ['first_name' => 'Chidi', 'last_name' => 'Bello', 'date_of_birth' => '1985-01-01', 'lga' => 'jahun', 'ward' => 'Ward 9'],
                'band' => 'none', 'duplicate' => false, 'owner' => null,
            ],
            // Clear non-duplicate — shares no blocking key with anyone.
            'clear non-duplicate' => [
                'query' => ['first_name' => 'Grace', 'last_name' => 'Okoro', 'date_of_birth' => '2000-01-01', 'phone' => '08035550000', 'lga' => 'ringim', 'ward' => 'Ward 1'],
                'band' => 'none', 'duplicate' => false, 'owner' => null,
            ],
        ];
    }
}
