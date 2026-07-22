<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Models\SyncConnector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SyncConnector>
 */
class SyncConnectorFactory extends Factory
{
    protected $model = SyncConnector::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'SOCU feed',
            'source' => RegistrationSource::Socu,
            'owner_mda_id' => Mda::factory(),
            'conflict_policy' => ConflictPolicy::FlagForReview,
            'credentials_ref' => 'socu',
            'enabled' => true,
            'schedule' => 'hourly',
        ];
    }
}
