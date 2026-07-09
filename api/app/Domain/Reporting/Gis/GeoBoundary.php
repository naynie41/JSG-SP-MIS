<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Gis;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * An administrative boundary (PRD FR-GIS-01): an LGA (level 2) or Ward (level 3). The
 * GeoJSON `geometry` drives the choropleth; `code` is the slug used to join coverage
 * aggregates to the shape.
 *
 * @property string $id
 * @property string $level
 * @property string $code
 * @property string $name
 * @property string|null $parent_code
 * @property array<string, mixed> $geometry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class GeoBoundary extends Model
{
    use HasUuids;

    public const LEVEL_LGA = 'lga';

    public const LEVEL_WARD = 'ward';

    protected $table = 'geo_boundaries';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'level', 'code', 'name', 'parent_code', 'geometry',
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
}
