<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Support\PasswordRules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Creates the initial System Administrator in production (the DevUserSeeder is
 * local-only). The password is PROMPTED (never an argument/env, so it stays out of
 * shell history and process listings) and validated against the app's password
 * policy (min 12, breached-password check). No control is weakened — the account
 * is subject to the same RBAC and mandatory MFA (SysAdmin role) as any other; the
 * admin completes MFA enrolment on first login.
 */
class CreateAdminUser extends Command
{
    protected $signature = 'spmis:create-admin {email} {--name=SP-MIS Administrator}';

    protected $description = 'Create the initial System Administrator (password prompted, not passed as an argument)';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $name = (string) $this->option('name');

        $role = Role::where('key', RoleKey::SystemAdministrator->value)->first();
        if ($role === null) {
            $this->error('The System Administrator role is missing. Seed roles first: php artisan db:seed --class=RolesAndPermissionsSeeder --force');

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("A user with email {$email} already exists.");

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password (min 12 chars, not a breached password)');
        $confirm = (string) $this->secret('Confirm password');

        $validator = Validator::make(
            ['email' => $email, 'password' => $password, 'password_confirmation' => $confirm],
            ['email' => ['required', 'email'], 'password' => PasswordRules::default()],
        );
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password, // hashed by the model cast
            'role_id' => $role->id,
            'mda_id' => null, // System Administrator is cross-MDA
            'status' => UserStatus::Active,
            'email_verified_at' => now(),
        ]);

        $this->info("System Administrator {$email} created. MFA enrolment is required on first login.");

        return self::SUCCESS;
    }
}
