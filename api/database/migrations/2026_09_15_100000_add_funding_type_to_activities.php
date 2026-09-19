<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * How an activity is funded, as a fixed choice rather than free text.
 *
 * `funding_source` was a free-text box, so nothing typed into it could drive anything —
 * least of all a partner's view. `funding_type` is one of government / partner /
 * individual; a partner activity carries `funding_partner_id` (which already scopes the
 * partner's dashboard) and may be marked `co_funded_by_government`.
 *
 * Backfill is deliberately narrow: an activity ALREADY linked to a partner is a partner
 * activity, which is a fact. Nothing else is inferred from the free text, which stays in
 * place and keeps displaying until someone edits the activity and chooses a type.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('funding_type', 20)->nullable()->after('funding_source');
            $table->boolean('co_funded_by_government')->default(false)->after('funding_partner_id');
            $table->index('funding_type');
        });

        DB::table('activities')->whereNotNull('funding_partner_id')->update(['funding_type' => 'partner']);
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['funding_type']);
            $table->dropColumn(['funding_type', 'co_funded_by_government']);
        });
    }
};
