<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\RegistrationSource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * A saved column mapping for one MDA's recurring file shape (CLAUDE.md §11).
 *
 * Keyed by (MDA, source, source signature) so it is offered only for a file with the
 * same columns — when an MDA changes its export the signature changes, the template no
 * longer matches, and the officer re-maps rather than having a stale mapping applied to
 * moved columns.
 *
 * A template PRE-FILLS a proposal. It never satisfies the identity-field confirmation:
 * NIN, BVN, name and phone are re-confirmed on every import regardless, because the
 * cost of a wrong identity mapping is a false duplicate merge, not a validation error.
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property RegistrationSource $source
 * @property string $source_signature
 * @property string $name
 * @property array<string, string|null> $column_map
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ImportMappingTemplate extends Model implements MdaScoped
{
    use Auditable, HasUuids, ScopedToMda;

    protected $table = 'import_mapping_templates';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'owner_mda_id',
        'source',
        'source_signature',
        'name',
        'column_map',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => RegistrationSource::class,
            'column_map' => 'array',
        ];
    }

    public function mdaOwnershipColumn(): string
    {
        return 'owner_mda_id';
    }
}
