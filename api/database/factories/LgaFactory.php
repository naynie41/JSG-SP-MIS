<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Reference\Models\Lga;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * TEST FIXTURES ONLY.
 *
 * Draws from the real 27-LGA enum (which is committed reference data, not a guess) so
 * fixtures stay consistent with validation. It is never used by a seeder — production
 * LGA rows come only from the maintainer-supplied dataset.
 *
 * @extends Factory<Lga>
 */
class LgaFactory extends Factory
{
    protected $model = Lga::class;

    /**
     * Walks the 27 enum cases in order rather than picking at random.
     *
     * `lgas.code` is unique, so a random pick makes any test that creates two LGAs
     * fail roughly one run in twenty-seven — a flake that looks like an unrelated bug
     * and is nearly impossible to reproduce on demand.
     */
    private static int $next = 0;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cases = LgaEnum::cases();
        $lga = $cases[self::$next % count($cases)];
        self::$next++;

        return [
            'code' => $lga->value,
            'name' => $lga->label(),
            'state' => 'Jigawa',
            'latitude' => null,
            'longitude' => null,
            'geometry' => null,
        ];
    }

    public function forEnum(LgaEnum $lga): self
    {
        return $this->state(fn (): array => ['code' => $lga->value, 'name' => $lga->label()]);
    }
}
