<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Referral\Models\ReferralSlaPolicy;
use Illuminate\Database\Seeder;

/**
 * Seeds the DEFAULT referral SLA windows (PRD FR-REF-04/05) from `config/sla.php`
 * into the admin-editable `referral_sla_policies` table. Real config, not sample
 * data. Idempotent — existing rows are left untouched so admin edits survive.
 */
class ReferralSlaSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<string, int> $windows */
        $windows = config('sla.referral.windows', []);

        foreach ($windows as $status => $hours) {
            ReferralSlaPolicy::query()->firstOrCreate(
                ['status' => $status],
                ['hours' => (int) $hours],
            );
        }
    }
}
