<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Permissions modelled at module + action level (PRD FR-UAM-05). `key` is the
 * canonical `module.action` string code authorizes against (e.g. user.create);
 * future modules register their own permissions using the shared actions.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique(); // e.g. "user.create"
            $table->string('module');        // e.g. "user"
            $table->string('action');        // App\Domain\Access\Enums\PermissionAction
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('module');
            $table->unique(['module', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
