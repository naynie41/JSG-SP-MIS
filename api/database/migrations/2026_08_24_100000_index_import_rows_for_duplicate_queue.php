<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The duplicate queue asks one question of `import_rows`: "flagged rows in these
 * batches that nobody has decided yet".
 *
 * The table was indexed on `import_batch_id` and `beneficiary_id` only, so that question
 * scanned every row of every batch it touched. The queue previously hid this by fetching
 * whole batches and filtering in the browser — which is exactly why it could only ever
 * see one page. Paginating it server-side makes the index load-bearing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_rows', function (Blueprint $table): void {
            $table->index(['import_batch_id', 'match_band', 'resolution'], 'import_rows_duplicate_queue_index');
        });
    }

    public function down(): void
    {
        Schema::table('import_rows', function (Blueprint $table): void {
            $table->dropIndex('import_rows_duplicate_queue_index');
        });
    }
};
