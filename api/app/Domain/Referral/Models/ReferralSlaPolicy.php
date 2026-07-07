<?php

declare(strict_types=1);

namespace App\Domain\Referral\Models;

use App\Domain\Audit\Concerns\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * An admin-editable SLA window for a referral status (PRD FR-REF-04/05): how many
 * hours a referral may sit in `status` before it is overdue. Global config (not
 * MDA-scoped); changes are audited.
 *
 * @property string $id
 * @property string $status
 * @property int $hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ReferralSlaPolicy extends Model
{
    use Auditable, HasUuids;

    protected $table = 'referral_sla_policies';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'status',
        'hours',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hours' => 'integer',
        ];
    }
}
