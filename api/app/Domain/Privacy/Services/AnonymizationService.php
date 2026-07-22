<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Irreversibly de-identifies a beneficiary (NFR-PRV-01) while PRESERVING everything
 * that must survive: the row and its id, all operational history (enrollments,
 * benefit ledger — their FKs are untouched), and the append-only audit trail.
 *
 * Direct identifiers are always removed; quasi identifiers are removed on a full
 * anonymize but kept on an `aggregate` (so de-identified statistics remain
 * possible). NIN/BVN and their lookup hashes are always cleared, so an anonymized
 * record can never again be matched or looked up. Which fields are direct vs quasi
 * is configuration (config/privacy.php), never hard-coded.
 *
 * The write is quiet (no model events) so it neither re-derives the identifier
 * hashes nor emits a PII-bearing "updated" audit diff; a single, metadata-only
 * `beneficiary.anonymized` entry is written instead.
 */
class AnonymizationService
{
    /** Beneficiary identity columns that are NOT NULL — redacted, not nulled. */
    private const NOT_NULL_FIELDS = ['first_name', 'last_name'];

    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * @param  bool  $keepQuasi  true for `aggregate` (keep de-identified quasi fields)
     */
    public function anonymize(
        Beneficiary $beneficiary,
        bool $keepQuasi = false,
        ?string $policyKey = null,
        ?User $actor = null,
        ?string $reason = null,
    ): void {
        if ($beneficiary->isAnonymized()) {
            return; // idempotent — never double-process
        }

        $token = (string) config('privacy.anonymization.redaction_token', '[redacted]');
        $direct = (array) config('privacy.anonymization.direct', []);
        $quasi = $keepQuasi ? [] : (array) config('privacy.anonymization.quasi', []);

        $updates = [];
        foreach ([...$direct, ...$quasi] as $field) {
            $updates[$field] = in_array($field, self::NOT_NULL_FIELDS, true) ? $token : null;
        }

        // Always destroy the lookup keys so the record can never be re-identified.
        $updates['nin'] = null;
        $updates['bvn'] = null;
        $updates['nin_hash'] = null;
        $updates['bvn_hash'] = null;
        $updates['block_name_dob'] = null;

        $updates['anonymized_at'] = Carbon::now();
        $updates['retention_policy'] = $policyKey;

        DB::transaction(function () use ($beneficiary, $updates): void {
            // Quiet write: no saving hook (would re-derive hashes), no Auditable diff
            // (would carry the PII we are removing).
            $beneficiary->forceFill($updates)->saveQuietly();

            $this->purgeDocuments($beneficiary);
        });

        $this->audit->record('beneficiary.anonymized', $beneficiary, after: [
            'mode' => $keepQuasi ? 'aggregate' : 'anonymize',
            'policy' => $policyKey,
            'reason' => $reason,
            'quasi_retained' => $keepQuasi,
        ], actor: $actor);
    }

    /**
     * Remove attached documents — they are PII files. The stored files are deleted
     * from the private disk and the rows are hard-deleted (their filenames can
     * themselves be identifying). Controlled by config; on by default.
     */
    private function purgeDocuments(Beneficiary $beneficiary): void
    {
        if (! (bool) config('privacy.anonymization.purge_documents', true)) {
            return;
        }

        foreach ($beneficiary->documents()->withTrashed()->get() as $document) {
            if ($document->stored_path !== null && Storage::disk('local')->exists($document->stored_path)) {
                Storage::disk('local')->delete($document->stored_path);
            }
            $document->forceDelete();
        }
    }
}
