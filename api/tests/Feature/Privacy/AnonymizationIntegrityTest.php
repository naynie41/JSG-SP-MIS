<?php

declare(strict_types=1);

namespace Tests\Feature\Privacy;

use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Privacy\Services\AnonymizationService;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\DocumentType;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryDocument;
use App\Domain\Registry\Services\BeneficiaryLookupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Anonymization integrity (NFR-PRV-01): de-identifying a beneficiary removes every
 * identifier (and the lookup hashes, so they can never be re-identified) WHILE
 * preserving the audit trail and the operational/aggregate history that must remain.
 * Attached PII documents are purged. Idempotent.
 */
class AnonymizationIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_anonymization_preserves_audit_and_aggregates_but_removes_identifiers(): void
    {
        Storage::fake('local');

        $beneficiary = Beneficiary::factory()->create([
            'first_name' => 'Amina',
            'last_name' => 'Bello',
            'nin' => '22200000011',
            'bvn' => '33300000012',
            'lga' => 'dutse',
        ]);
        $programme = Programme::factory()->individual()->create();
        Enrollment::factory()->create(['programme_id' => $programme->id, 'mda_id' => $beneficiary->owner_mda_id, 'beneficiary_id' => $beneficiary->id]);
        Benefit::factory()->count(2)->create([
            'beneficiary_id' => $beneficiary->id,
            'programme_id' => $programme->id,
            'mda_id' => $beneficiary->owner_mda_id,
            'monetary_value' => 400_000,
        ]);

        // A stored PII document.
        $path = 'documents/'.$beneficiary->id.'/id-card.pdf';
        Storage::disk('local')->put($path, 'sensitive');
        $document = BeneficiaryDocument::create([
            'beneficiary_id' => $beneficiary->id,
            'owner_mda_id' => $beneficiary->owner_mda_id,
            'document_type' => DocumentType::cases()[0],
            'original_filename' => 'amina-id-card.pdf',
            'stored_path' => $path,
            'mime_type' => 'application/pdf',
            'size_bytes' => 9,
            'checksum_sha256' => hash('sha256', 'sensitive'),
        ]);

        // Aggregate snapshot BEFORE anonymization (join-free ledger total by programme).
        $totalBefore = (int) Benefit::query()->where('programme_id', $programme->id)->sum('monetary_value');
        $benefitCountBefore = Benefit::query()->where('beneficiary_id', $beneficiary->id)->count();
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.created', 'entity_id' => $beneficiary->id]);

        app(AnonymizationService::class)->anonymize($beneficiary, keepQuasi: false, policyKey: 'test');

        // ---- Identifiers removed, including the lookup hashes. ----
        $raw = DB::table('beneficiaries')->where('id', $beneficiary->id)->first();
        $this->assertNull($raw->nin);
        $this->assertNull($raw->bvn);
        $this->assertNull($raw->nin_hash);
        $this->assertNull($raw->bvn_hash);
        $this->assertNull($raw->lga);
        $this->assertNotSame('Amina', $raw->first_name);
        $this->assertNotNull($raw->anonymized_at);

        // ---- Cannot be re-identified via the exact-match lookup seam. ----
        $this->assertCount(0, app(BeneficiaryLookupService::class)->findByIdentity(nin: '22200000011'));

        // ---- Audit trail preserved (append-only): the original event AND the new one. ----
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.created', 'entity_id' => $beneficiary->id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.anonymized', 'entity_id' => $beneficiary->id]);

        // ---- Aggregates intact: benefit rows + totals unchanged (FKs untouched). ----
        $this->assertSame($benefitCountBefore, Benefit::query()->where('beneficiary_id', $beneficiary->id)->count());
        $this->assertSame($totalBefore, (int) Benefit::query()->where('programme_id', $programme->id)->sum('monetary_value'));
        $this->assertDatabaseHas('enrollments', ['beneficiary_id' => $beneficiary->id]);

        // Ledger aggregator (the real aggregate surface) still counts the beneficiary.
        $aggregate = app(LedgerAggregator::class)->aggregate('programme', []);
        $this->assertNotEmpty($aggregate);

        // ---- PII documents purged (file + row). ----
        Storage::disk('local')->assertMissing($path);
        $this->assertDatabaseMissing('beneficiary_documents', ['id' => $document->id]);

        // ---- Idempotent: a second call is a no-op (no second anonymized audit). ----
        app(AnonymizationService::class)->anonymize($beneficiary->fresh(), keepQuasi: false, policyKey: 'test');
        $this->assertSame(1, DB::table('audit_log')->where('action', 'beneficiary.anonymized')->where('entity_id', $beneficiary->id)->count());
    }
}
