<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Benefit\Models\DoubleDippingRule;
use Illuminate\Database\Seeder;

/**
 * Seeds the DEFAULT double-dipping rule (PRD FR-BEN-05) so detection works out of
 * the box. Idempotent: no-op once any rule exists. The window is a configurable
 * default — tune it via the admin API.
 */
class DoubleDippingRuleSeeder extends Seeder
{
    public function run(): void
    {
        if (DoubleDippingRule::query()->exists()) {
            return;
        }

        DoubleDippingRule::create([
            'name' => 'Default — same type across MDAs',
            'period_days' => 30,
            'benefit_types' => null, // all types
            'is_active' => true,
            'description' => 'Flags the same benefit type delivered to a beneficiary by more than one MDA within 30 days.',
        ]);
    }
}
