<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One declared place in an activity's location set: an LGA, and optionally one ward
 * within it. `ward_id === null` means "the whole LGA".
 *
 * DESCRIPTIVE ONLY — see the migration. Nothing validates uploaded beneficiaries
 * against these rows.
 *
 * Not MdaScoped: the parent {@see Activity} is, and every read goes through it. Not
 * Auditable either — a location set is edited as a whole, so the meaningful audit
 * entry is on the activity, not on individual rows appearing and disappearing.
 *
 * @property string $id
 * @property string $activity_id
 * @property string $lga_id
 * @property string|null $ward_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Activity $activity
 * @property-read Lga $lga
 * @property-read Ward|null $ward
 */
class ActivityLocation extends Model
{
    use HasUuids;

    protected $table = 'activity_locations';

    /**
     * @var list<string>
     */
    protected $fillable = ['activity_id', 'lga_id', 'ward_id'];

    /** True when this row declares the whole LGA rather than a single ward. */
    public function isWholeLga(): bool
    {
        return $this->ward_id === null;
    }

    /**
     * @return BelongsTo<Activity, $this>
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * @return BelongsTo<Lga, $this>
     */
    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    /**
     * @return BelongsTo<Ward, $this>
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }
}
