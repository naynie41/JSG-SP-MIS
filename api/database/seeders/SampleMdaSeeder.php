<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Models\Mda;
use Illuminate\Database\Seeder;

/**
 * A couple of sample MDAs so the app has data to show on first run. Idempotent
 * (keyed by name). Safe for local/staging; real MDAs are created via the admin UI.
 */
class SampleMdaSeeder extends Seeder
{
    /**
     * @var list<array{name: string, type: MdaType, contact_person: string, contact_email: string}>
     */
    private const MDAS = [
        [
            'name' => 'Ministry of Health',
            'type' => MdaType::Ministry,
            'contact_person' => 'Health Programmes Desk',
            'contact_email' => 'health@spmis.local',
        ],
        [
            'name' => 'Ministry of Women Affairs & Social Development',
            'type' => MdaType::Ministry,
            'contact_person' => 'Social Development Desk',
            'contact_email' => 'women.affairs@spmis.local',
        ],
    ];

    public function run(): void
    {
        foreach (self::MDAS as $mda) {
            Mda::updateOrCreate(
                ['name' => $mda['name']],
                [
                    'type' => $mda['type'],
                    'contact_person' => $mda['contact_person'],
                    'contact_email' => $mda['contact_email'],
                    'status' => MdaStatus::Active,
                ],
            );
        }
    }
}
