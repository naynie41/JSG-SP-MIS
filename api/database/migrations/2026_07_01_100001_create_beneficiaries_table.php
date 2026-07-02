<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\BeneficiaryStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Beneficiaries — the core registry record (PRD FR-REG-01/03/04, FR-OWN-01).
 *
 * `owner_mda_id` is the ownership + MDA-scoping column. NIN/BVN are stored
 * plain (not encrypted) because they must be uniquely indexed and matched
 * (Phase 3); at-rest protection is via disk/volume encryption per SECURITY.md.
 * Partial unique indexes make NIN/BVN unique only when present; the phone and
 * (last_name, date_of_birth) indexes make Phase 3 matching cheap.
 *
 * `import_batch_id` is a plain nullable column here; its FK is added when the
 * import pipeline (import_batches) lands.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_mda_id');

            // Provenance (FR-REG-03).
            $table->string('registration_source');
            $table->date('registration_date');
            $table->uuid('import_batch_id')->nullable();
            $table->string('original_record_id')->nullable();

            // Core identity (FR-REG-04).
            $table->string('nin')->nullable();
            $table->string('bvn')->nullable();
            $table->string('phone')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('lga')->nullable();
            $table->string('ward')->nullable();

            $table->string('status')->default(BeneficiaryStatus::Active->value);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('owner_mda_id')->references('id')->on('mdas');

            $table->index('owner_mda_id');
            $table->index('phone');
            $table->index(['last_name', 'date_of_birth']);
            $table->index('import_batch_id');
            $table->index('status');
        });

        // Conditional (partial) uniqueness: NIN/BVN unique only when non-null.
        // Portable across PostgreSQL and sqlite (both support WHERE on indexes).
        DB::statement('CREATE UNIQUE INDEX beneficiaries_nin_unique ON beneficiaries (nin) WHERE nin IS NOT NULL');
        DB::statement('CREATE UNIQUE INDEX beneficiaries_bvn_unique ON beneficiaries (bvn) WHERE bvn IS NOT NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
