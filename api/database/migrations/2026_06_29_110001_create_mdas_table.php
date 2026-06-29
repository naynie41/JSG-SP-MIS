<?php

declare(strict_types=1);

use App\Domain\Access\Enums\MdaStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ministries, Departments and Agencies (PRD §9). An MDA owns the records it
 * originates and the users that belong to it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mdas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('type'); // App\Domain\Access\Enums\MdaType
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default(MdaStatus::Active->value); // App\Domain\Access\Enums\MdaStatus
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mdas');
    }
};
