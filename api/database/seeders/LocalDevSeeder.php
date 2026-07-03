<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Illuminate\Database\Seeder;

/**
 * LOCAL DEVELOPMENT ONLY convenience seeder for clicking through the app in a
 * browser. It is deliberately NOT part of DatabaseSeeder so the committed seed
 * baseline keeps its production posture (privileged roles require MFA — FR-UAM-04,
 * asserted by SeederTest). Never runs in production.
 *
 * Run it explicitly, last, after the normal seed:
 *   php artisan db:seed --class="Database\\Seeders\\LocalDevSeeder"
 *
 * It:
 *  - relaxes the MFA requirement on the privileged roles so you can sign in
 *    without enrolling an authenticator, and
 *  - creates two MDA-scoped accounts (an officer and an owning-MDA admin) so the
 *    cross-MDA duplicate-resolution and request-to-serve flow is testable.
 *
 * Every password comes from an env placeholder with a documented default — change
 * them for anything shared.
 */
class LocalDevSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        // Make sure the roles, sample MDAs and the admin account exist (idempotent).
        $this->call([
            RolesAndPermissionsSeeder::class,
            SampleMdaSeeder::class,
            DevUserSeeder::class,
        ]);

        // Local convenience: let the privileged roles sign in without MFA. This is
        // reverted the moment you re-run the normal seed (RolesAndPermissionsSeeder
        // restores requires_mfa), so it never leaks into a real environment.
        Role::query()
            ->whereIn('key', [RoleKey::SystemAdministrator->value, RoleKey::Executive->value])
            ->update(['requires_mfa' => false]);

        $women = Mda::query()->where('name', 'Ministry of Women Affairs & Social Development')->first();
        $health = Mda::query()->where('name', 'Ministry of Health')->first();

        // Requesting side: an officer who can search + resolve imports + raise serve requests.
        $this->seedUser(
            (string) env('SEED_MDA_OFFICER_EMAIL', 'officer@spmis.local'),
            'SP-MIS MDA Officer',
            (string) env('SEED_MDA_OFFICER_PASSWORD', 'ChangeMe!Officer12345'),
            RoleKey::MdaOfficer,
            $women,
        );

        // Owning side: an admin who can accept/decline the serve requests.
        $this->seedUser(
            (string) env('SEED_MDA_ADMIN_EMAIL', 'mda.admin@spmis.local'),
            'SP-MIS MDA Admin',
            (string) env('SEED_MDA_ADMIN_PASSWORD', 'ChangeMe!MdaAdmin12345'),
            RoleKey::MdaAdmin,
            $health,
        );
    }

    private function seedUser(string $email, string $name, string $password, RoleKey $roleKey, ?Mda $mda): void
    {
        $role = Role::query()->where('key', $roleKey->value)->first();

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role_id' => $role?->id,
                'mda_id' => $mda?->id,
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
                'mfa_enabled' => false,
            ],
        );
    }
}
