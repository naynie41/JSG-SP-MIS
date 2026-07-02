<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use App\Domain\Access\Enums\PermissionAction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * A single `module.action` permission (PRD FR-UAM-05). The `key` (e.g.
 * "user.create") is the stable identifier code authorizes against.
 *
 * @property string $id
 * @property string $key
 * @property string $module
 * @property PermissionAction $action
 * @property string|null $description
 */
class Permission extends Model
{
    use HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'module',
        'action',
        'description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'action' => PermissionAction::class,
        ];
    }

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission')->withTimestamps();
    }
}
