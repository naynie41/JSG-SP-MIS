<?php

declare(strict_types=1);

use App\Domain\Programme\Enums\ProgrammeApproval;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * MDA-owned programmes, approved centrally (revises PRD §10).
 *
 * The catalog was global and unowned: only the System Administrator and SP
 * Coordination could create an entry, and every MDA read all of it. An MDA may now
 * create a programme OF ITS OWN — visible to that MDA alone (plus oversight roles)
 * and usable only once the System Administrator has approved it.
 *
 * Two independent axes, deliberately kept apart:
 *
 *  - `owner_mda_id` — NULL is the CENTRAL catalog, i.e. every programme that exists
 *    today. Set means one MDA owns it and no other MDA ever sees it.
 *  - `approval_status` — the decision, separate from `status`, which is the delivery
 *    lifecycle (draft/active/closed/archived). Collapsing the two would have made
 *    every existing status filter in the product mean something new.
 *
 * Backfill is a no-op by construction: existing rows keep NULL ownership and default
 * to `approved`, so nothing already live changes meaning.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table): void {
            $table->uuid('owner_mda_id')->nullable()->after('id');
            $table->string('approval_status', 20)->default(ProgrammeApproval::Approved->value)->after('status');
            $table->timestamp('submitted_at')->nullable()->after('approval_status');
            $table->uuid('approved_by')->nullable()->after('submitted_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            // Why it was rejected (or a note on approval). Shown back to the MDA that
            // submitted it, so a rejection is actionable rather than a dead end.
            $table->text('decision_note')->nullable()->after('approved_at');

            $table->foreign('owner_mda_id')->references('id')->on('mdas')->cascadeOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();

            $table->index('owner_mda_id');
            $table->index('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table): void {
            $table->dropForeign(['owner_mda_id']);
            $table->dropForeign(['approved_by']);
            $table->dropIndex(['owner_mda_id']);
            $table->dropIndex(['approval_status']);
            $table->dropColumn([
                'owner_mda_id',
                'approval_status',
                'submitted_at',
                'approved_by',
                'approved_at',
                'decision_note',
            ]);
        });
    }
};
