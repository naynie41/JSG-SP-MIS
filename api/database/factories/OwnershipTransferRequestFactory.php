<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\OwnershipTransferStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\OwnershipTransferRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OwnershipTransferRequest>
 */
class OwnershipTransferRequestFactory extends Factory
{
    protected $model = OwnershipTransferRequest::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'beneficiary_id' => Beneficiary::factory(),
            'from_mda_id' => Mda::factory(),
            'to_mda_id' => Mda::factory(),
            'status' => OwnershipTransferStatus::Pending,
            'reason' => fake()->sentence(),
        ];
    }
}
