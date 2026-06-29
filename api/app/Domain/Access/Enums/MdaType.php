<?php

declare(strict_types=1);

namespace App\Domain\Access\Enums;

/**
 * The kind of government body an MDA represents (PRD §9: MDA "type").
 */
enum MdaType: string
{
    case Ministry = 'ministry';
    case Department = 'department';
    case Agency = 'agency';

    public function label(): string
    {
        return match ($this) {
            self::Ministry => 'Ministry',
            self::Department => 'Department',
            self::Agency => 'Agency',
        };
    }
}
