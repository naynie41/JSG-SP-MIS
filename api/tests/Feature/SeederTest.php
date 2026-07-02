<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_produces_expected_baseline(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Seven predefined roles, each present.
        $this->assertCount(7, Role::all());
        foreach (RoleKey::cases() as $roleKey) {
            $this->assertDatabaseHas('roles', ['key' => $roleKey->value, 'is_system' => true]);
        }

        // MFA is mandatory for the privileged roles.
        $this->assertTrue(Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->requires_mfa);
        $this->assertTrue(Role::where('key', RoleKey::Executive->value)->firstOrFail()->requires_mfa);

        // All registered permissions are synced and System Administrator holds them all.
        $this->assertGreaterThanOrEqual(13, Permission::count());
        $admin = Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail();
        $this->assertSame(Permission::count(), $admin->permissions()->count());

        // Sample MDAs.
        $this->assertDatabaseHas('mdas', ['name' => 'Ministry of Health']);
        $this->assertDatabaseHas('mdas', ['name' => 'Ministry of Women Affairs & Social Development']);
        $this->assertSame(2, Mda::count());

        // One seeded System Administrator account, MFA not yet enrolled.
        $superuser = User::where('email', 'admin@spmis.local')->firstOrFail();
        $this->assertSame(RoleKey::SystemAdministrator->value, $superuser->role?->key);
        $this->assertFalse($superuser->mfa_enabled);
        $this->assertTrue($superuser->mfaRequired());

        // Sample beneficiaries spread across both sample MDAs (cross-MDA test data).
        $this->assertSame(6, Beneficiary::count());
        $this->assertSame(2, Beneficiary::query()->distinct()->count('owner_mda_id'));
    }
}
