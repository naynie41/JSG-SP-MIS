<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tamper-evidence for the audit log (NFR-AUD-01): database triggers that reject
 * any UPDATE, DELETE or TRUNCATE on audit_log, so even the application's own DB
 * role cannot modify or remove audit rows. INSERT and SELECT remain allowed.
 *
 * PostgreSQL only; a no-op on other drivers (the model-level guard still applies
 * in tests running on sqlite).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION spmis_prevent_audit_log_mutation()
            RETURNS trigger AS $$
            BEGIN
                RAISE EXCEPTION 'audit_log is append-only: % is not permitted', TG_OP;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER audit_log_no_update
                BEFORE UPDATE ON audit_log
                FOR EACH ROW EXECUTE FUNCTION spmis_prevent_audit_log_mutation();

            CREATE TRIGGER audit_log_no_delete
                BEFORE DELETE ON audit_log
                FOR EACH ROW EXECUTE FUNCTION spmis_prevent_audit_log_mutation();

            CREATE TRIGGER audit_log_no_truncate
                BEFORE TRUNCATE ON audit_log
                FOR EACH STATEMENT EXECUTE FUNCTION spmis_prevent_audit_log_mutation();
        SQL);
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared(<<<'SQL'
            DROP TRIGGER IF EXISTS audit_log_no_update ON audit_log;
            DROP TRIGGER IF EXISTS audit_log_no_delete ON audit_log;
            DROP TRIGGER IF EXISTS audit_log_no_truncate ON audit_log;
            DROP FUNCTION IF EXISTS spmis_prevent_audit_log_mutation();
        SQL);
    }
};
