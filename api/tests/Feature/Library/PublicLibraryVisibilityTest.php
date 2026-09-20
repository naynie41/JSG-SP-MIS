<?php

declare(strict_types=1);

namespace Tests\Feature\Library;

use App\Domain\Library\Models\LibraryItem;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * What an ANONYMOUS visitor can and cannot reach (FR-RES-04).
 *
 * This is the file that matters most in the feature. `public/library` is, apart
 * from /health and login, the only part of SP-MIS that answers without a token, so
 * every test here is a negative one: the failure mode is not "the page is broken",
 * it is "something reached the internet that should not have".
 */
class PublicLibraryVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_the_public_list_needs_no_authentication(): void
    {
        LibraryItem::factory()->published()->create(['title' => 'State Social Protection Policy']);

        $this->getJson('/api/v1/public/library')
            ->assertOk()
            ->assertJsonPath('data.items.0.title', 'State Social Protection Policy');
    }

    public function test_only_published_resources_are_listed(): void
    {
        LibraryItem::factory()->published()->create(['title' => 'Published guidance']);
        LibraryItem::factory()->create(['title' => 'Unfinished draft']);
        LibraryItem::factory()->archived()->create(['title' => 'Withdrawn circular']);

        $titles = collect($this->getJson('/api/v1/public/library')->assertOk()->json('data.items'))
            ->pluck('title')->all();

        $this->assertSame(['Published guidance'], $titles);
    }

    /**
     * Knowing a draft's id must not be enough. This is the attack that a status
     * filter applied only to the LIST would leave wide open.
     */
    public function test_a_draft_cannot_be_downloaded_even_with_its_id(): void
    {
        $draft = LibraryItem::factory()->create();

        $this->getJson("/api/v1/public/library/{$draft->id}/download")->assertNotFound();
        $this->getJson("/api/v1/public/library/{$draft->id}/thumbnail")->assertNotFound();
    }

    public function test_archiving_stops_the_download_immediately(): void
    {
        Storage::fake('local');
        $item = $this->publishRealFile();

        $this->get("/api/v1/public/library/{$item->id}/download")->assertOk();

        $item->update(['status' => 'archived']);

        $this->getJson("/api/v1/public/library/{$item->id}/download")->assertNotFound();
    }

    /** A draft's artwork leaks its existence, its title and its subject. */
    public function test_a_draft_thumbnail_is_not_served(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('library/thumbnails/secret.png', 'x');

        $draft = LibraryItem::factory()->withThumbnail()->create([
            'thumbnail_path' => 'library/thumbnails/secret.png',
        ]);

        $this->getJson("/api/v1/public/library/{$draft->id}/thumbnail")->assertNotFound();
    }

    public function test_the_public_payload_carries_no_internal_fields(): void
    {
        LibraryItem::factory()->published()->create();

        $item = $this->getJson('/api/v1/public/library')->assertOk()->json('data.items.0');

        foreach (['stored_path', 'checksum_sha256', 'created_by', 'status', 'updated_at'] as $leak) {
            $this->assertArrayNotHasKey($leak, $item, "The public payload exposed `{$leak}`.");
        }
    }

    public function test_a_download_is_an_attachment_and_is_never_sniffed(): void
    {
        Storage::fake('local');
        $item = $this->publishRealFile();

        $response = $this->get("/api/v1/public/library/{$item->id}/download")->assertOk();

        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertStringStartsWith('attachment', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_downloading_increments_the_counter(): void
    {
        Storage::fake('local');
        $item = $this->publishRealFile();

        $this->get("/api/v1/public/library/{$item->id}/download")->assertOk();
        $this->get("/api/v1/public/library/{$item->id}/download")->assertOk();

        $this->assertSame(2, $item->fresh()->download_count);
    }

    /**
     * A row whose file has gone is a deployment fault. It must read as "not
     * available" rather than throwing on a page with no authentication in front of
     * it — a stack trace is the last thing that should reach an anonymous visitor.
     */
    public function test_a_missing_file_is_a_404_not_a_500(): void
    {
        Storage::fake('local');
        $item = LibraryItem::factory()->published()->create([
            'stored_path' => 'library/files/never-written.pdf',
        ]);

        $this->getJson("/api/v1/public/library/{$item->id}/download")->assertNotFound();
    }

    public function test_a_link_resource_offers_no_download(): void
    {
        $link = LibraryItem::factory()->published()->link('https://example.test/report')->create();

        $item = $this->getJson('/api/v1/public/library')->assertOk()->json('data.items.0');

        $this->assertSame('https://example.test/report', $item['external_url']);
        $this->assertArrayNotHasKey('download_url', $item);

        $this->getJson("/api/v1/public/library/{$link->id}/download")->assertNotFound();
    }

    public function test_the_list_can_be_filtered_and_searched(): void
    {
        LibraryItem::factory()->published()->create(['title' => 'Cash transfer manual', 'category' => 'guideline']);
        LibraryItem::factory()->published()->create(['title' => 'Annual report 2026', 'category' => 'report']);

        $this->getJson('/api/v1/public/library?category=report')
            ->assertOk()->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.title', 'Annual report 2026');

        $this->getJson('/api/v1/public/library?search=cash')
            ->assertOk()->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.title', 'Cash transfer manual');
    }

    public function test_featured_resources_are_pinned_above_the_rest(): void
    {
        LibraryItem::factory()->published()->create(['title' => 'Ordinary', 'published_at' => now()]);
        LibraryItem::factory()->published()->featured()->create(['title' => 'Key', 'published_at' => now()->subYear()]);

        $titles = collect($this->getJson('/api/v1/public/library')->json('data.items'))->pluck('title')->all();

        $this->assertSame(['Key', 'Ordinary'], $titles, 'A featured item must lead, even when older.');
    }

    /** Writes a real file to the faked disk so download() has something to stream. */
    private function publishRealFile(): LibraryItem
    {
        $path = UploadedFile::fake()->create('policy.pdf', 12, 'application/pdf')
            ->store('library/files', 'local');

        return LibraryItem::factory()->published()->create([
            'stored_path' => $path,
            'original_filename' => 'policy.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }
}
