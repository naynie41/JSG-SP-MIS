<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use App\Domain\Audit\Concerns\Auditable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * A role is a named bundle of permissions (PRD FR-UAM-01). Authorization is
 * always checked against permissions, never role names (CONVENTIONS.md).
 *
 * @property string $id
 * @property string $key
 * @property string $name
 * @property string|null $description
 * @property bool $is_system
 * @property bool $requires_mfa
 * @property-read Collection<int, Permission> $permissions
 */
class Role extends Model
{
    use Auditable, HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'name',
        'description',
        'is_system',
        'requires_mfa',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'requires_mfa' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission')->withTimestamps();
    }
}
