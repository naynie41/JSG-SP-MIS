<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Links a PARTNER organisation's two identities (revises PRD §11).
 *
 * A development partner that both funds and implements exists twice in this system,
 * on two different axes, and until now nothing joined them:
 *
 *  - as a DELIVERY organisation — a row in `mdas` (type `partner`) that owns
 *    programmes, activities and beneficiaries through `owner_mda_id`;
 *  - as a FUNDER — a `users` row with the Development Partner role, which is what
 *    `activities.funding_partner_id` points at and what the funded-scope dashboard
 *    is resolved from.
 *
 * `funder_user_id` is that join. It is nullable because every government MDA has no
 * funder identity, and unique because one funder account belongs to one
 * organisation — without the constraint two orgs could claim the same funder and
 * the funded dashboard would silently merge them.
 *
 * Deliberately NOT a replacement for `activities.funding_partner_id`: funding stays
 * attributed per activity (§11), and this column only says which organisation a
 * funder account belongs to.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mdas', function (Blueprint $table): void {
            $table->uuid('funder_user_id')->nullable()->after('type');

            // nullOnDelete, not cascade: deleting a funder account must never delete
            // the organisation and, with it, every beneficiary it owns.
            $table->foreign('funder_user_id')->references('id')->on('users')->nullOnDelete();
            $table->unique('funder_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('mdas', function (Blueprint $table): void {
            $table->dropForeign(['funder_user_id']);
            $table->dropUnique(['funder_user_id']);
            $table->dropColumn('funder_user_id');
        });
    }
};
