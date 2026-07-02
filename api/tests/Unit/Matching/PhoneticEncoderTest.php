<?php

declare(strict_types=1);

namespace Tests\Unit\Matching;

use App\Domain\Matching\Support\PhoneticEncoder;
use PHPUnit\Framework\TestCase;

class PhoneticEncoderTest extends TestCase
{
    private PhoneticEncoder $encoder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->encoder = new PhoneticEncoder;
    }

    /**
     * @dataProvider hausaVariants
     */
    public function test_hausa_name_variants_share_a_blocking_key(string $a, string $b): void
    {
        $this->assertSame(
            $this->encoder->block($a),
            $this->encoder->block($b),
            "Expected '{$a}' and '{$b}' to block together",
        );
    }

    /**
     * @return list<array{string, string}>
     */
    public static function hausaVariants(): array
    {
        return [
            ['Muhammad', 'Mohammed'],
            ['Muhammad', 'Muhammadu'],
            ['Ibrahim', 'Ibraheem'],
            ['Sani', 'Saani'],
            ['Sadiq', 'Saddiq'],
            ['Sadiq', 'Sadqi'],   // transposition
            ['Yusuf', 'Yusufu'],
        ];
    }

    public function test_unrelated_names_do_not_share_a_blocking_key(): void
    {
        $this->assertNotSame($this->encoder->block('Sani'), $this->encoder->block('Bello'));
        $this->assertNotSame($this->encoder->block('Ibrahim'), $this->encoder->block('Yusuf'));
    }

    public function test_empty_input_encodes_to_empty(): void
    {
        $this->assertSame('', $this->encoder->code('   '));
        $this->assertSame('', $this->encoder->block('!!!'));
    }
}
