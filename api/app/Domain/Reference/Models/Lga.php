<?php

declare(strict_types=1);

namespace App\Domain\Reference\Models;

use Database\Factories\LgaFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * A Local Government Area — reference data, not owned by any MDA.
 *
 * Deliberately NOT MdaScoped and NOT Auditable: this is a shared lookup list every
 * MDA reads identically, and it changes only when a maintainer loads a new
 * authoritative dataset (which the loader reports on) — not through user action.
 *
 * `code` is the slug shared with {@see \App\Domain\Registry\Enums\Lga} and
 * `geo_boundaries.code`. The enum remains the validation authority for
 * `beneficiaries.lga` (FR-REG-04/05, a locked decision); this table is the
 * navigable hierarchy that the enum cannot express, since the enum has no wards.
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string $state
 * @property string|null $latitude
 * @property string|null $longitude
 * @property array<string, mixed>|null $geometry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Ward> $wards
 * @property-read int|null $wards_count
 */
class Lga extends Model
{
    /** @use HasFactory<LgaFactory> */
    use HasFactory, HasUuids;

    protected $table = 'lgas';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code', 'name', 'state', 'latitude', 'longitude', 'geometry',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'geometry' => 'array',
        ];
    }

    /**
     * @return HasMany<Ward, $this>
     */
    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }

    protected static function newFactory(): LgaFactory
    {
        return LgaFactory::new();
    }
}
