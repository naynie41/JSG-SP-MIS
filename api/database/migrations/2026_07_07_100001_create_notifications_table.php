<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * In-app notifications (PRD FR-NOT-01/02). Per-user and MDA-scoped: each row is
 * addressed to one recipient user (and carries their MDA at send time for
 * ScopedToMda isolation). `type` names the event, `payload` carries structured
 * data, and `related_*` points at the originating entity (e.g. a service request).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('recipient_user_id');
            $table->uuid('recipient_mda_id')->nullable(); // scope column (recipient's MDA)
            $table->string('type');
            $table->string('subject');
            $table->text('body')->nullable();
            $table->json('payload')->nullable();
            $table->string('related_type')->nullable();
            $table->uuid('related_id')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('recipient_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('recipient_mda_id')->references('id')->on('mdas')->nullOnDelete();

            $table->index('recipient_mda_id');
            $table->index(['recipient_user_id', 'read_at']);
            $table->index(['related_type', 'related_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
