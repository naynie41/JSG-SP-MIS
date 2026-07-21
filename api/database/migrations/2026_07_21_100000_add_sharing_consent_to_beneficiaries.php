<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\ConsentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cross-MDA data-sharing consent (FR-DSH-01, NFR-PRV-01). The beneficiary carries a
 * denormalised current `sharing_consent` status (gated by the data-sharing framework),
 * and every change is written append-only to `beneficiary_consents` for a full,
 * auditable consent lifecycle. Fresh records default to `unknown` — no consent is
 * assumed until captured.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('sharing_consent')->default(ConsentStatus::Unknown->value)->after('status');
            $table->timestamp('sharing_consent_at')->nullable()->after('sharing_consent');
        });

        Schema::create('beneficiary_consents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->string('purpose')->default('cross_mda_sharing'); // what the consent covers
            $table->string('status');                                // granted | withdrawn | unknown
            $table->text('basis')->nullable();                       // legal basis / purpose note
            $table->string('source')->nullable();                    // how it was captured
            $table->text('note')->nullable();
            $table->uuid('recorded_by')->nullable();
            $table->timestamp('created_at')->useCurrent();           // append-only, no updated_at

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('recorded_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['beneficiary_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiary_consents');

        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn(['sharing_consent', 'sharing_consent_at']);
        });
    }
};
