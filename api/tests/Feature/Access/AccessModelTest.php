<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_an_mda_and_a_user_linked_to_it(): void
    {
        $mda = Mda::factory()->create(['name' => 'Ministry of Health']);

        $user = User::factory()->forMda($mda)->create([
            'email' => 'officer@example.test',
        ]);

        $this->assertTrue($user->getKeyType() === 'string');
        $this->assertFalse($user->getIncrementing());
        $this->assertSame($mda->id, $user->mda_id);
        $this->assertSame('Ministry of Health', $user->mda->name);
        $this->assertTrue($mda->users->contains($user));
        $this->assertInstanceOf(UserStatus::class, $user->status);
        $this->assertSame(UserStatus::Active, $user->status);

        $this->assertDatabaseHas('users', ['email' => 'officer@example.test', 'mda_id' => $mda->id]);
    }

    public function test_mfa_secret_is_encrypted_at_rest(): void
    {
        $user = User::factory()->create();
        $user->mfa_secret = 'JBSWY3DPEHPK3PXP';
        $user->save();

        // Accessor decrypts transparently...
        $this->assertSame('JBSWY3DPEHPK3PXP', $user->fresh()->mfa_secret);

        // ...but the raw stored value is not the plaintext.
        $raw = $user->newQuery()->getConnection()
            ->table('users')->where('id', $user->id)->value('mfa_secret');
        $this->assertNotSame('JBSWY3DPEHPK3PXP', $raw);

        // And it is hidden from serialization.
        $this->assertArrayNotHasKey('mfa_secret', $user->toArray());
    }

    public function test_seeder_creates_seven_roles_with_permissions(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->assertCount(7, Role::all());
        foreach (RoleKey::cases() as $roleKey) {
            $this->assertDatabaseHas('roles', ['key' => $roleKey->value, 'is_system' => true]);
        }

        // System Administrator holds every permission; bundles wire up correctly.
        $admin = Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail();
        $this->assertSame(Permission::count(), $admin->permissions()->count());

        // The cross-MDA bypass permission exists and is granted to Executive.
        $this->assertDatabaseHas('permissions', ['key' => 'cross-mda.view']);
        $executive = Role::where('key', RoleKey::Executive->value)->firstOrFail();
        $this->assertTrue($executive->permissions->pluck('key')->contains('cross-mda.view'));
    }

    public function test_cross_mda_grant_links_user_to_another_mda(): void
    {
        $homeMda = Mda::factory()->create();
        $otherMda = Mda::factory()->create();
        $user = User::factory()->forMda($homeMda)->create();

        $grant = $user->mdaAccessGrants()->create([
            'mda_id' => $otherMda->id,
            'reason' => 'M&E oversight',
        ]);

        $this->assertSame($otherMda->id, $grant->mda->id);
        $this->assertSame($user->id, $grant->user->id);
        $this->assertTrue($otherMda->accessGrants->contains($grant));
    }
}
