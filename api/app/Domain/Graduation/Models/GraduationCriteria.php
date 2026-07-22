<?php

declare(strict_types=1);

namespace App\Domain\Graduation\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Graduation\Enums\CriteriaLogic;
use App\Domain\Programme\Models\Programme;
use Database\Factories\GraduationCriteriaFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An MDA-owned, admin-editable graduation criteria set for a catalog programme
 * (FR-GRD-01). The `rules` are configuration (a list of {type, threshold}), never
 * hard-coded logic. MDA-scoped on `owner_mda_id`, so each MDA defines its own criteria
 * for the programmes it runs without touching the shared programme catalog (§10).
 *
 * @property string $id
 * @property string $programme_id
 * @property string $owner_mda_id
 * @property string $name
 * @property CriteriaLogic $logic
 * @property list<array{type: string, threshold: int|float}> $rules
 * @property bool $is_active
 * @property string|null $created_by
 */
class GraduationCriteria extends Model implements MdaScoped
{
    /** @use HasFactory<GraduationCriteriaFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda;

    protected $table = 'graduation_criteria';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'programme_id',
        'owner_mda_id',
        'name',
        'logic',
        'rules',
        'is_active',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logic' => CriteriaLogic::class,
            'rules' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Programme, $this>
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    protected static function newFactory(): GraduationCriteriaFactory
    {
        return GraduationCriteriaFactory::new();
    }
}
