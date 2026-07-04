<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Configurable double-dipping rules (PRD FR-BEN-05): each rule defines a window
 * (`period_days`) and the benefit types it applies to (`benefit_types`, empty =
 * all). Admin-editable and audited. System-wide config (not MDA-scoped).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('double_dipping_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->unsignedInteger('period_days');
            $table->json('benefit_types')->nullable(); // null/[] = applies to all types
            $table->boolean('is_active')->default(true);
            $table->string('description')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('double_dipping_rules');
    }
};
