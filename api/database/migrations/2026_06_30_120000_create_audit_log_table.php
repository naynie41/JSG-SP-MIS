<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Immutable, append-only audit log (PRD FR-AUD-01, NFR-AUD-01).
 *
 * No foreign keys: audit history must survive deletion of the actor/entity it
 * references, so identifiers are stored as plain columns. Only created_at — rows
 * are never updated. Tamper-evidence (no UPDATE/DELETE) is enforced by triggers
 * in the following migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Actor (who) — nullable for system/unauthenticated actions.
            $table->uuid('actor_id')->nullable()->index();
            $table->uuid('actor_mda_id')->nullable();

            // What happened, and to which entity.
            $table->string('action')->index();          // e.g. user.created, auth.login
            $table->string('entity_type')->nullable();  // e.g. user, mda
            $table->string('entity_id')->nullable();

            // Before/after snapshots (secrets omitted, PII masked).
            $table->json('before')->nullable();
            $table->json('after')->nullable();

            // Request context.
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->uuid('correlation_id')->nullable()->index();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
