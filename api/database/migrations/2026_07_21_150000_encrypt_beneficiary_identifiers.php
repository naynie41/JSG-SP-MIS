<?php

declare(strict_types=1);

use App\Domain\Registry\Support\IdentifierHasher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Encrypt NIN/BVN at rest (SECURITY.md §4, NFR-SEC-01 — Phase 7 hardening).
 *
 * The Phase 2 schema stored NIN/BVN plaintext because they must be uniquely
 * indexed and exact-matched. This migration closes that gap:
 *
 *  - `nin`/`bvn` become application-layer encrypted (Laravel `encrypted` cast);
 *  - new `nin_hash`/`bvn_hash` columns hold a keyed HMAC-SHA256 of the normalised
 *    identifier (see IdentifierHasher) — deterministic, so uniqueness and every
 *    exact-match path (duplicate cascade, lookup/serve, benefit import) now run
 *    against the hash;
 *  - the partial-unique indexes move from the plaintext columns to the hashes.
 *
 * Existing rows are backfilled in place (encrypt + hash). Fuzzy-matched fields
 * (name/phone) stay plaintext — a documented exception (FR-DUP-03 requires
 * similarity scoring, which ciphertext cannot support).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('nin_hash', 64)->nullable();
            $table->string('bvn_hash', 64)->nullable();
        });

        // The plaintext partial uniques go away before the columns are rewritten.
        DB::statement('DROP INDEX IF EXISTS beneficiaries_nin_unique');
        DB::statement('DROP INDEX IF EXISTS beneficiaries_bvn_unique');

        // Ciphertext is far longer than 11 digits.
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->text('nin')->nullable()->change();
            $table->text('bvn')->nullable()->change();
        });

        // Backfill existing plaintext rows: encrypt the value, store its keyed hash.
        DB::table('beneficiaries')
            ->where(fn ($q) => $q->whereNotNull('nin')->orWhereNotNull('bvn'))
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('beneficiaries')->where('id', $row->id)->update([
                        'nin' => $row->nin === null ? null : Crypt::encryptString($row->nin),
                        'nin_hash' => IdentifierHasher::hash($row->nin),
                        'bvn' => $row->bvn === null ? null : Crypt::encryptString($row->bvn),
                        'bvn_hash' => IdentifierHasher::hash($row->bvn),
                    ]);
                }
            });

        // Conditional uniqueness now lives on the deterministic hashes.
        DB::statement('CREATE UNIQUE INDEX beneficiaries_nin_hash_unique ON beneficiaries (nin_hash) WHERE nin_hash IS NOT NULL');
        DB::statement('CREATE UNIQUE INDEX beneficiaries_bvn_hash_unique ON beneficiaries (bvn_hash) WHERE bvn_hash IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS beneficiaries_nin_hash_unique');
        DB::statement('DROP INDEX IF EXISTS beneficiaries_bvn_hash_unique');

        DB::table('beneficiaries')
            ->where(fn ($q) => $q->whereNotNull('nin')->orWhereNotNull('bvn'))
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('beneficiaries')->where('id', $row->id)->update([
                        'nin' => $row->nin === null ? null : Crypt::decryptString($row->nin),
                        'bvn' => $row->bvn === null ? null : Crypt::decryptString($row->bvn),
                    ]);
                }
            });

        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn(['nin_hash', 'bvn_hash']);
        });

        DB::statement('CREATE UNIQUE INDEX beneficiaries_nin_unique ON beneficiaries (nin) WHERE nin IS NOT NULL');
        DB::statement('CREATE UNIQUE INDEX beneficiaries_bvn_unique ON beneficiaries (bvn) WHERE bvn IS NOT NULL');
    }
};
