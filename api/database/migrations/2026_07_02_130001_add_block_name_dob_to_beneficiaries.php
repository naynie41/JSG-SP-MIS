<?php

declare(strict_types=1);

use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Blocking key for fuzzy duplicate matching (PRD FR-DUP-03, NFR-PERF-01):
 * `phonetic(last_name) | dob_year`. Indexed so the fuzzy candidate gather is an
 * index lookup — we never score against the whole registry. Maintained by the
 * Beneficiary model on save; existing rows are backfilled here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('block_name_dob')->nullable()->after('ward');
            $table->index('block_name_dob');
        });

        Beneficiary::query()->withoutGlobalScopes()->chunkById(500, function ($beneficiaries): void {
            foreach ($beneficiaries as $beneficiary) {
                /** @var Beneficiary $beneficiary */
                DB::table('beneficiaries')->where('id', $beneficiary->id)->update([
                    'block_name_dob' => Beneficiary::blockNameDobFor(
                        $beneficiary->last_name,
                        $beneficiary->date_of_birth?->toDateString(),
                    ),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropIndex(['block_name_dob']);
            $table->dropColumn('block_name_dob');
        });
    }
};
