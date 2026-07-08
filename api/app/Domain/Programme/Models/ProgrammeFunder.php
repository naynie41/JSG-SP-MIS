<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Association between a Development Partner user and a programme they fund/monitor
 * (PRD §4, FR-RPT-02). Makes the partner dashboard's "funded programmes only" scope
 * concrete and queryable. Managed by admins; read-only from the partner's side.
 *
 * @property string $id
 * @property string $programme_id
 * @property string $user_id
 */
class ProgrammeFunder extends Model
{
    use HasUuids;

    protected $table = 'programme_funders';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'programme_id',
        'user_id',
    ];

    /**
     * Programme ids funded by a given partner user.
     *
     * @return list<string>
     */
    public static function programmeIdsForUser(string $userId): array
    {
        return self::query()->where('user_id', $userId)->pluck('programme_id')->all();
    }
}
