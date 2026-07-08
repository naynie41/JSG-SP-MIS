<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            MatchingConfigSeeder::class,
            DoubleDippingRuleSeeder::class,
            ReferralSlaSeeder::class,
            GrievanceSlaSeeder::class,
            SampleMdaSeeder::class,
            DevUserSeeder::class,
            SampleMdaUserSeeder::class,
            RegistrySampleSeeder::class,
            ProgrammeSampleSeeder::class,
            ReferralSampleSeeder::class,
            GrievanceSampleSeeder::class,
            NotificationSampleSeeder::class,
        ]);
    }
}
