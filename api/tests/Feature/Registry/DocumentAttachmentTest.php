<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryDocument;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Supporting-document attachment (PRD FR-REG-07, SECURITY.md §5): validated
 * uploads, private storage, owner-only mutation, in-scope download, and audit.
 */
class DocumentAttachmentTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Beneficiary $benA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function tokenFor(string $key): string
    {
        return $this->users[$key]->createToken('test')->plainTextToken;
    }

    /** A fake but content-valid PDF (finfo detects application/pdf from the magic). */
    private function pdf(string $name = 'id-card.pdf'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF");
    }

    private function upload(string $userKey): BeneficiaryDocument
    {
        $response = $this->withToken($this->tokenFor($userKey))
            ->post("/api/v1/beneficiaries/{$this->benA->id}/documents", [
                'file' => $this->pdf(),
                'document_type' => 'national_id',
            ], ['Accept' => 'application/json'])
            ->assertCreated();

        return BeneficiaryDocument::query()->withoutGlobalScope(MdaScope::class)
            ->findOrFail($response->json('data.id'));
    }

    public function test_owner_can_upload_a_document_which_is_stored_privately_and_audited(): void
    {
        $document = $this->upload('officerA');

        $this->assertSame($this->mdaA->id, $document->owner_mda_id);
        $this->assertSame('national_id', $document->document_type->value);
        $this->assertSame('id-card.pdf', $document->original_filename);
        $this->assertNotEmpty($document->checksum_sha256);

        // Stored on the private disk, under the beneficiary's folder.
        Storage::disk('local')->assertExists($document->stored_path);
        $this->assertStringStartsWith('documents/'.$this->benA->id.'/', $document->stored_path);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary_document.created',
            'entity_type' => 'beneficiary_document',
            'entity_id' => $document->id,
        ]);
    }

    public function test_upload_rejects_disallowed_file_type(): void
    {
        $this->withToken($this->tokenFor('officerA'))
            ->post("/api/v1/beneficiaries/{$this->benA->id}/documents", [
                'file' => UploadedFile::fake()->create('malware.exe', 10),
                'document_type' => 'national_id',
            ], ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonFragment(['field' => 'file']);

        $this->assertDatabaseCount('beneficiary_documents', 0);
    }

    public function test_upload_rejects_oversized_file(): void
    {
        $this->withToken($this->tokenFor('officerA'))
            ->post("/api/v1/beneficiaries/{$this->benA->id}/documents", [
                'file' => UploadedFile::fake()->create('big.pdf', 6000), // 6 MB > 5 MB
                'document_type' => 'national_id',
            ], ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'file']);
    }

    public function test_non_owner_cannot_upload(): void
    {
        $this->withToken($this->tokenFor('officerB'))
            ->post("/api/v1/beneficiaries/{$this->benA->id}/documents", [
                'file' => $this->pdf(),
                'document_type' => 'national_id',
            ], ['Accept' => 'application/json'])
            ->assertStatus(403);

        $this->assertDatabaseCount('beneficiary_documents', 0);
    }

    public function test_owner_can_list_and_download_and_download_is_audited(): void
    {
        $document = $this->upload('officerA');
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/{$this->benA->id}/documents")
            ->assertOk()
            ->assertJsonPath('data.documents.0.id', $document->id)
            ->assertJsonMissingPath('data.documents.0.stored_path');

        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->get("/api/v1/beneficiaries/{$this->benA->id}/documents/{$document->id}/download")
            ->assertOk()
            ->assertDownload('id-card.pdf');

        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary_document.downloaded',
            'entity_id' => $document->id,
        ]);
    }

    public function test_non_owner_out_of_scope_cannot_download(): void
    {
        $document = $this->upload('officerA');
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerB'))
            ->get("/api/v1/beneficiaries/{$this->benA->id}/documents/{$document->id}/download")
            ->assertStatus(404);
    }

    public function test_owner_can_soft_delete_a_document(): void
    {
        $document = $this->upload('officerA');
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->deleteJson("/api/v1/beneficiaries/{$this->benA->id}/documents/{$document->id}")
            ->assertOk();

        $this->assertSoftDeleted('beneficiary_documents', ['id' => $document->id]);
    }
}
