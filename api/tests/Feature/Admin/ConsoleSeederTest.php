<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use Database\Seeders\AdminConsoleDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The console demo seeder: enough data that every console section renders something
 * real, assembled by REUSING the existing seeders and adding only the import history
 * none of them cover.
 */
class ConsoleSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_everything_the_console_sections_need(): void
    {
        $this->seed(AdminConsoleDemoSeeder::class);

        // An administrator to sign in with.
        $admin = User::query()->withoutGlobalScope(MdaScope::class)
            ->whereHas('role', fn ($q) => $q->where('key', RoleKey::SystemAdministrator->value))->first();
        $this->assertNotNull($admin, 'the console needs an administrator account');

        // Organizations + users (User & Access, Organizations).
        $this->assertGreaterThan(0, Mda::query()->withoutGlobalScopes()->count());
        $this->assertGreaterThan(3, User::query()->withoutGlobalScope(MdaScope::class)->count());

        // A development partner (Organizations → partners).
        $this->assertTrue(
            User::query()->withoutGlobalScope(MdaScope::class)
                ->whereHas('role', fn ($q) => $q->where('key', RoleKey::DevelopmentPartner->value))->exists(),
            'partner sections need at least one development partner',
        );

        // Programme catalogue.
        $this->assertGreaterThan(0, Programme::query()->withoutGlobalScopes()->count());

        // Import history + duplicate review (Registry & Data Quality, Integrations,
        // and the duplicates/imports report datasets).
        $this->assertGreaterThanOrEqual(4, ImportBatch::query()->withoutGlobalScopes()->count());
        $this->assertGreaterThan(0, ImportRow::query()->whereNotNull('match_band')->count());
        $this->assertGreaterThan(0, ImportRow::query()->whereNotNull('resolution')->count());
    }

    public function test_it_seeds_account_states_so_governance_alerts_are_meaningful(): void
    {
        $this->seed(AdminConsoleDemoSeeder::class);

        $users = User::query()->withoutGlobalScope(MdaScope::class)->get();

        $this->assertTrue(
            $users->contains(fn (User $u): bool => $u->status === UserStatus::Suspended),
            'a suspended account keeps the account-status KPI from being uniformly green',
        );
        $this->assertTrue($users->contains(fn (User $u): bool => ! $u->mfa_enabled));
    }

    public function test_it_seeds_a_range_of_import_outcomes(): void
    {
        $this->seed(AdminConsoleDemoSeeder::class);

        $statuses = ImportBatch::query()->withoutGlobalScopes()->get()
            ->map(fn (ImportBatch $b): string => $b->status->value)->unique();
        $this->assertContains('completed', $statuses);
        $this->assertContains('failed', $statuses, 'a failed batch makes "reprocess failed imports" meaningful');

        $sources = ImportBatch::query()->withoutGlobalScopes()->get()
            ->map(fn (ImportBatch $b): string => $b->source->value)->unique();
        $this->assertGreaterThan(1, $sources->count(), 'data-source monitoring needs more than one source');
    }

    public function test_it_is_idempotent(): void
    {
        $this->seed(AdminConsoleDemoSeeder::class);
        $batches = ImportBatch::query()->withoutGlobalScopes()->count();
        $rows = ImportRow::query()->count();

        $this->seed(AdminConsoleDemoSeeder::class);

        $this->assertSame($batches, ImportBatch::query()->withoutGlobalScopes()->count());
        $this->assertSame($rows, ImportRow::query()->count());
    }

    public function test_it_never_seeds_demo_data_in_production(): void
    {
        // Run the seeder directly: `$this->seed()` would hit Laravel's own
        // interactive production guard before reaching the seeder's check.
        app()->detectEnvironment(fn (): string => 'production');

        app(AdminConsoleDemoSeeder::class)->setContainer(app())->run();

        $this->assertSame(0, ImportBatch::query()->withoutGlobalScopes()->count());
        $this->assertSame(0, User::query()->withoutGlobalScope(MdaScope::class)->count());
    }
}
