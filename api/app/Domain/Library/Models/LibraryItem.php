<?php

declare(strict_types=1);

namespace App\Domain\Library\Models;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Library\Enums\LibraryItemKind;
use App\Domain\Library\Enums\LibraryItemStatus;
use Database\Factories\LibraryItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * One entry in the public resource library — a policy, guideline, tool or report the
 * State publishes for anyone to read (PRD §7.14, FR-RES-01..05).
 *
 * The name is LibraryItem rather than Resource on purpose: `Resource` collides with
 * Laravel's API-resource vocabulary that the rest of this codebase uses heavily, and
 * `ResourceResource` is not a class anyone should have to read. The user-facing word
 * stays "resource" everywhere in the interface.
 *
 * **This model is NOT MdaScoped, and that is deliberate.** Every other table holding
 * a file belongs to the organisation that created it. These belong to the State and
 * are served to the open internet, so an ownership column would be answering a
 * question that does not apply. Access is decided by {@see $status} instead.
 *
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property string $category
 * @property bool $featured
 * @property LibraryItemKind $kind
 * @property string|null $stored_path
 * @property string|null $original_filename
 * @property string|null $mime_type
 * @property int|null $size_bytes
 * @property string|null $checksum_sha256
 * @property string|null $external_url
 * @property string|null $thumbnail_path
 * @property string|null $thumbnail_mime
 * @property LibraryItemStatus $status
 * @property Carbon|null $published_at
 * @property int $download_count
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class LibraryItem extends Model
{
    /** @use HasFactory<LibraryItemFactory> */
    use Auditable, HasFactory, HasUuids, SoftDeletes;

    protected $table = 'library_items';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'featured',
        'kind',
        'stored_path',
        'original_filename',
        'mime_type',
        'size_bytes',
        'checksum_sha256',
        'external_url',
        'thumbnail_path',
        'thumbnail_mime',
        'status',
        'published_at',
        'created_by',
    ];

    /**
     * `download_count` is absent from $fillable on purpose — it is incremented by an
     * atomic statement from an UNAUTHENTICATED endpoint, and mass assignment is not a
     * road that should exist to it.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'kind' => LibraryItemKind::class,
            'status' => LibraryItemStatus::class,
            'published_at' => 'datetime',
            'size_bytes' => 'integer',
            'download_count' => 'integer',
        ];
    }

    /**
     * THE public gate. Every unauthenticated read goes through this scope and nothing
     * else — a controller writing its own `where('status', …)` is how a draft
     * eventually reaches the internet, so there is exactly one place to get it right
     * and a test pinning it.
     *
     * @param  Builder<LibraryItem>  $query
     * @return Builder<LibraryItem>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', LibraryItemStatus::Published->value);
    }

    /** Whether this item may be served to someone who is not signed in. */
    public function isPublic(): bool
    {
        return $this->status->isPublic();
    }

    /** A file item carries an upload; a link item points away and has nothing stored. */
    public function isFile(): bool
    {
        return $this->kind === LibraryItemKind::File;
    }

    /** The human label for this item's category, or the raw key if it was retired. */
    public function categoryLabel(): string
    {
        /** @var array<string, string> $categories */
        $categories = config('library.categories', []);

        return $categories[$this->category] ?? $this->category;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Models live under App\Domain\…, so Laravel's default guess
     * (Database\Factories\Domain\Library\Models\LibraryItemFactory) misses. Every
     * domain model in this codebase names its factory explicitly for the same reason.
     */
    protected static function newFactory(): LibraryItemFactory
    {
        return LibraryItemFactory::new();
    }
}
