<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * TEST FIXTURES ONLY.
 *
 * Ward names are SYNTHETIC and obviously so ("Test Ward 3"). Real ward names are not
 * invented anywhere in this repository — they come only from the maintainer-supplied
 * dataset. Keeping fixtures visibly fake means a fixture can never be mistaken for
 * reference data if it leaks into a database someone is looking at.
 *
 * @extends Factory<Ward>
 */
class WardFactory extends Factory
{
    protected $model = Ward::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Test Ward '.$this->faker->unique()->numberBetween(1, 100000);

        return [
            'lga_id' => Lga::factory(),
            'name' => $name,
            'code' => Str::of($name)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_')->value(),
            'latitude' => null,
            'longitude' => null,
            'geometry' => null,
        ];
    }
}
