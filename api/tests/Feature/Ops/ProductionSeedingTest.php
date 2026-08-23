<?php

declare(strict_types=1);

namespace Tests\Feature\Ops;

use App\Domain\Access\Models\Mda;
use App\Domain\Grievance\Models\GrievanceSlaPolicy;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Referral\Models\ReferralSlaPolicy;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * What a PRODUCTION database must and must not contain after bootstrapping
 * (docs/DEPLOY.md §2.6).
 *
 * Two failures this guards against, both of which look like success:
 *
 *  - CONFIG THAT DOES NOT ARRIVE. Duplicate screening reads the active matching
 *    config, and {@see MatchingConfigService::activeOrNull()} has no lazy default —
 *    with no config the import pipeline SKIPS screening silently. Nothing errors,
 *    nothing warns, and the registry fills with duplicates. The same applies to the
 *    SLA windows and double-dipping rules: they are real configuration that happens
 *    to be delivered by a seeder, not sample data.
 *
 *  - SAMPLE DATA THAT DOES. A demo MDA in a live database is not cosmetic:
 *    `beneficiaries.owner_mda_id` points at it, so a fictional ministry acquires real
 *    records and cannot simply be deleted afterwards.
 */
class ProductionSeedingTest extends TestCase
{
    use RefreshDatabase;

    /** The seeders that deliver real CONFIGURATION and must run in production. */
    private const CONFIG_SEEDERS = [
        'RolesAndPermissionsSeeder',
        'MatchingConfigSeeder',
        'DoubleDippingRuleSeeder',
        'ReferralSlaSeeder',
        'GrievanceSlaSeeder',
    ];

    /** The seeders that deliver DEMO data and must never run in production. */
    private const SAMPLE_SEEDERS = [
        'SampleMdaSeeder',
        'SampleMdaUserSeeder',
        'DevUserSeeder',
        'RegistrySampleSeeder',
        'ProgrammeSampleSeeder',
        'ReferralSampleSeeder',
        'GrievanceSampleSeeder',
        'NotificationSampleSeeder',
        'ReportingSampleSeeder',
    ];

    public function test_every_sample_seeder_refuses_to_run_in_production(): void
    {
        foreach (self::SAMPLE_SEEDERS as $seeder) {
            $path = database_path("seeders/{$seeder}.php");
            $this->assertTrue(File::exists($path), "{$seeder} is listed here but does not exist");

            $this->assertStringContainsString(
                "environment('production')",
                (string) File::get($path),
                "{$seeder} writes demo data with no production guard — a bare `db:seed --force` would put it in the live database",
            );
        }
    }

    public function test_seeding_in_production_creates_no_sample_data(): void
    {
        $this->seedAsProduction();

        // withoutGlobalScopes throughout: Mda is ScopedToMda, so an unauthenticated
        // count returns 0 whether or not the rows exist — the assertion would pass
        // against a database full of demo ministries.
        $this->assertSame(
            0,
            Mda::query()->withoutGlobalScopes()->count(),
            'a fictional ministry would acquire real beneficiaries via owner_mda_id',
        );
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScopes()->count());
        $this->assertDatabaseCount('users', 0);
    }

    public function test_seeding_in_production_still_installs_the_operating_configuration(): void
    {
        $this->seedAsProduction();

        // The one that fails silently: no active config → no duplicate screening.
        $this->assertNotNull(
            app(MatchingConfigService::class)->activeOrNull(),
            'without an active matching config the import pipeline skips duplicate screening entirely',
        );

        $this->assertDatabaseCount('roles', 6);
        $this->assertTrue(GrievanceSlaPolicy::query()->exists());
        $this->assertTrue(ReferralSlaPolicy::query()->exists());
    }

    public function test_the_deploy_runbook_seeds_every_configuration_seeder(): void
    {
        // The runbook is the only thing that decides what a real deployment runs, so it
        // is what has to name them. A seeder that exists but is never invoked delivers
        // nothing.
        $runbook = (string) File::get(base_path('../docs/DEPLOY.md'));

        foreach (self::CONFIG_SEEDERS as $seeder) {
            $this->assertStringContainsString(
                $seeder,
                $runbook,
                "docs/DEPLOY.md never runs {$seeder}, so that configuration would be absent in production",
            );
        }
    }

    /**
     * Run the real seeder graph with the app reporting `production`.
     *
     * Invoked directly rather than through `db:seed`, which prompts for confirmation in
     * production and has no console to answer it here. The point is what the seeders DO
     * when they check the environment, not how the command is dispatched.
     */
    private function seedAsProduction(): void
    {
        $this->app->detectEnvironment(static fn (): string => 'production');

        (new DatabaseSeeder)->setContainer($this->app)->run();
    }
}
