<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cross-MDA access grants (PRD FR-UAM-03, FR-DSH-01). By default a user only
 * sees their own MDA's data; a row here explicitly grants one user access to
 * another MDA. Grants are auditable (who granted, why) and may expire.
 *
 * This backs the central MDA data-scoping bypass implemented in the RBAC step.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mda_access_grants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('mda_id');           // the MDA being granted access to
            $table->uuid('granted_by')->nullable();
            $table->string('reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'mda_id']);
            $table->index('mda_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('mda_id')->references('id')->on('mdas')->cascadeOnDelete();
            $table->foreign('granted_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mda_access_grants');
    }
};
