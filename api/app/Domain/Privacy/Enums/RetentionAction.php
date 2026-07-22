<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Enums;

/**
 * What a retention policy does to a record that has aged past its period
 * (NFR-PRV-01). Legal choice of action per cohort is a DPO decision (config).
 */
enum RetentionAction: string
{
    /** Mark for DPO review; change no data. */
    case Flag = 'flag';

    /** Remove direct identifiers but keep de-identified quasi fields for statistics. */
    case Aggregate = 'aggregate';

    /** Remove direct AND quasi identifiers; keep the row, history and audit trail. */
    case Anonymize = 'anonymize';

    /** Soft-delete; anonymize instead when operational history must survive. */
    case Delete = 'delete';

    public function label(): string
    {
        return match ($this) {
            self::Flag => 'Flag for review',
            self::Aggregate => 'Aggregate (de-identify, keep statistics)',
            self::Anonymize => 'Anonymize',
            self::Delete => 'Delete',
        };
    }
}
