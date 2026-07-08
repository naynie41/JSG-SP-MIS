<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\DocumentType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A supporting document attached to a beneficiary (PRD FR-REG-07). MDA-scoped via
 * the denormalised `owner_mda_id`; upload/delete are audited via Auditable. The
 * file itself is stored on a private disk (outside the web root) and only ever
 * streamed through the authorized download endpoint — never served statically.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $owner_mda_id
 * @property string|null $uploaded_by
 * @property DocumentType $document_type
 * @property string $original_filename
 * @property string $stored_path
 * @property string $mime_type
 * @property int $size_bytes
 * @property string $checksum_sha256
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Mda|null $ownerMda
 * @property-read User|null $uploadedBy
 */
class BeneficiaryDocument extends Model implements MdaScoped
{
    use Auditable, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'beneficiary_documents';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'owner_mda_id',
        'uploaded_by',
        'document_type',
        'original_filename',
        'stored_path',
        'mime_type',
        'size_bytes',
        'checksum_sha256',
    ];

    /**
     * The stored path is an internal filesystem location — never expose it.
     *
     * @var list<string>
     */
    protected $hidden = [
        'stored_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'document_type' => DocumentType::class,
            'size_bytes' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
