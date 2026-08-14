<?php

declare(strict_types=1);

use App\Domain\Registry\Support\NormalizationService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Separates the phone number a source WROTE from the form used to COMPARE it
 * (CLAUDE.md §11: "normalization is for comparison only — the original value is always
 * stored").
 *
 * `phone` previously held the normalized value, overwritten in the model's saving hook,
 * so the original was lost. Worse, that normalization only stripped non-digits: the same
 * subscriber written `+2348031234567` and `08031234567` reduced to two DIFFERENT strings,
 * and the exact phone comparator (weight 0.20 in the seeded cascade) could never match
 * them. Every existing row is backfilled below, so historical records become comparable
 * to each other for the first time.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('phone_normalized', 32)->nullable();
            $table->index('phone_normalized');
        });

        $normalizer = new NormalizationService;

        // Backfill in chunks: existing rows hold digit-stripped values, which the same
        // rule resolves to the canonical national form.
        DB::table('beneficiaries')
            ->select('id', 'phone')
            ->whereNotNull('phone')
            ->orderBy('id')
            ->chunk(500, function ($rows) use ($normalizer): void {
                foreach ($rows as $row) {
                    DB::table('beneficiaries')
                        ->where('id', $row->id)
                        ->update(['phone_normalized' => $normalizer->phone($row->phone)]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropIndex(['phone_normalized']);
            $table->dropColumn('phone_normalized');
        });
    }
};
