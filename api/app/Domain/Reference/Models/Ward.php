<?php

declare(strict_types=1);

namespace App\Domain\Reference\Models;

use Database\Factories\WardFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A ward — reference data, always belonging to exactly one LGA.
 *
 * `code` is unique only WITHIN its LGA: ward names repeat across Jigawa, so a ward is
 * identified by the pair (lga_id, code) and never by code alone. Any future resolution
 * of a free-text ward value must therefore go through its LGA first.
 *
 * @property string $id
 * @property string $lga_id
 * @property string $code
 * @property string $name
 * @property string|null $latitude
 * @property string|null $longitude
 * @property array<string, mixed>|null $geometry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lga|null $lga
 */
class Ward extends Model
{
    /** @use HasFactory<WardFactory> */
    use HasFactory, HasUuids;

    protected $table = 'wards';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'lga_id', 'code', 'name', 'latitude', 'longitude', 'geometry',
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
     * @return BelongsTo<Lga, $this>
     */
    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    protected static function newFactory(): WardFactory
    {
        return WardFactory::new();
    }
}
