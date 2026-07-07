<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Grievance\Models\GrievanceSlaPolicy;
use Illuminate\Database\Seeder;

/**
 * Seeds the DEFAULT grievance SLA windows (PRD FR-GRM-03) from `config/sla.php` into
 * the admin-editable `grievance_sla_policies` table. Real config, not sample data.
 * Idempotent — existing rows are left untouched so admin edits survive.
 */
class GrievanceSlaSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<string, int> $windows */
        $windows = config('sla.grievance.windows', []);

        foreach ($windows as $category => $hours) {
            GrievanceSlaPolicy::query()->firstOrCreate(
                ['category' => $category],
                ['hours' => (int) $hours],
            );
        }
    }
}
