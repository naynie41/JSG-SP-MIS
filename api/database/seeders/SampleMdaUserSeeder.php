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
 * An admin + an officer inside each sample MDA so coordination flows (referrals,
 * grievances, notifications) have real actors and recipients out of the box.
 * LOCAL/STAGING ONLY — never production. Idempotent (keyed by email). Passwords come
 * from a documented placeholder (SEED_SAMPLE_PASSWORD) — change them.
 */
class SampleMdaUserSeeder extends Seeder
{
    /** @var list<array{mda: string, slug: string}> */
    private const MDAS = [
        ['mda' => 'Ministry of Health', 'slug' => 'health'],
        ['mda' => 'Ministry of Women Affairs & Social Development', 'slug' => 'women'],
    ];

    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $password = (string) env('SEED_SAMPLE_PASSWORD', 'ChangeMe!Sample12345');
        $adminRole = Role::where('key', RoleKey::MdaAdmin->value)->first();
        if ($adminRole === null) {
            return;
        }

        foreach (self::MDAS as $entry) {
            $mda = Mda::query()->where('name', $entry['mda'])->first();
            if ($mda === null) {
                continue;
            }

            // TWO MDA Admins per MDA, not an admin and an officer: the Officer role was
            // merged into Admin (FR-UAM-01). Coordination flows need two distinct actors
            // inside one MDA — a requester and a decider — which one account cannot
            // provide.
            //
            // The `*.officer@` address is KEPT deliberately. ReferralSampleSeeder,
            // GrievanceSampleSeeder and NotificationSampleSeeder resolve this user by
            // that literal email; renaming it would make them silently seed nothing.
            $this->user("{$entry['slug']}.admin@spmis.local", ucfirst($entry['slug']).' MDA Admin', $mda, $adminRole, $password);
            $this->user("{$entry['slug']}.officer@spmis.local", ucfirst($entry['slug']).' MDA Admin (2)', $mda, $adminRole, $password);
        }
    }

    private function user(string $email, string $name, Mda $mda, Role $role, string $password): void
    {
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password, // hashed by the model cast
                'role_id' => $role->id,
                'mda_id' => $mda->id,
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ],
        );
    }
}
