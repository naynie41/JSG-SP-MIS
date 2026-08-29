<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryDocument;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Rate limits on the egress paths the hardening pass did not reach (SECURITY.md A04).
 *
 * The export matrix is throttled, but two classes beside it were not:
 *
 *  - **Document download.** A beneficiary document is the most sensitive artefact the
 *    system holds — an ID card scan is the identity document itself, not a field derived
 *    from it. Every bulk-PII path had a per-user ceiling except this one, so a compromised
 *    account could pull documents one request at a time as fast as the network allowed,
 *    which is the same exfiltration the export limiter exists to turn into noise and audit.
 *  - **Report preview.** Segment and ad-hoc previews run full aggregate queries on demand
 *    and were added after the pass. Unbounded, they are a cheap way to load the database
 *    from an ordinary reporting account.
 *
 * Preview gets its own, looser ceiling rather than the export one: previewing is what an
 * officer does repeatedly while narrowing a filter, and throttling that at export rates
 * would break the normal use of the screen.
 */
class EgressRateLimitTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Storage::fake('local');

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function document(): BeneficiaryDocument
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);
        Storage::disk('local')->put('documents/'.$beneficiary->id.'/id-card.pdf', '%PDF-1.4 test');

        return BeneficiaryDocument::query()->create([
            'beneficiary_id' => $beneficiary->id,
            'owner_mda_id' => $this->mda->id,
            'document_type' => 'national_id',
            'original_filename' => 'id-card.pdf',
            'stored_path' => 'documents/'.$beneficiary->id.'/id-card.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => 13,
            'checksum_sha256' => hash('sha256', '%PDF-1.4 test'),
            'uploaded_by' => $this->officer->id,
        ]);
    }

    public function test_document_download_is_rate_limited_per_user(): void
    {
        RateLimiter::clear('exports|'.$this->officer->id);
        $limit = (int) config('security.rate_limits.exports_per_minute');
        $document = $this->document();
        $url = "/api/v1/beneficiaries/{$document->beneficiary_id}/documents/{$document->id}/download";

        $token = $this->officer->createToken('t')->plainTextToken;
        for ($i = 0; $i < $limit; $i++) {
            $this->withToken($token)->get($url);
            $this->app['auth']->forgetGuards();
        }

        $this->withToken($token)->get($url)->assertStatus(429);
    }

    public function test_report_preview_is_rate_limited_per_user(): void
    {
        RateLimiter::clear('reports|'.$this->officer->id);
        $limit = (int) config('security.rate_limits.report_previews_per_minute', 30);

        $token = $this->officer->createToken('t')->plainTextToken;
        for ($i = 0; $i < $limit; $i++) {
            $this->withToken($token)->postJson('/api/v1/reports/segments/preview', ['dimensions' => []]);
            $this->app['auth']->forgetGuards();
        }

        $this->withToken($token)
            ->postJson('/api/v1/reports/segments/preview', ['dimensions' => []])
            ->assertStatus(429);
    }

    public function test_preview_is_looser_than_bulk_export(): void
    {
        // Deliberate: narrowing a filter is normal work, exporting the result is not.
        // If these ever equalise, the preview screen becomes unusable before the export
        // ceiling does any protecting.
        $this->assertGreaterThan(
            (int) config('security.rate_limits.exports_per_minute'),
            (int) config('security.rate_limits.report_previews_per_minute', 30),
        );
    }
}
