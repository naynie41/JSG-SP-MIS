<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds a single System Administrator account for LOCAL DEVELOPMENT ONLY so the
 * stack is usable out of the box. Never runs in production. Credentials come
 * from env placeholders (SEED_ADMIN_EMAIL / SEED_ADMIN_PASSWORD) — change them.
 */
class DevUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $adminRole = Role::where('key', RoleKey::SystemAdministrator->value)->first();

        User::updateOrCreate(
            ['email' => (string) env('SEED_ADMIN_EMAIL', 'admin@spmis.local')],
            [
                'name' => 'SP-MIS Administrator',
                'password' => (string) env('SEED_ADMIN_PASSWORD', 'ChangeMe!Admin12345'),
                'role_id' => $adminRole?->id,
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ],
        );
    }
}
