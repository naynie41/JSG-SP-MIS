<?php

declare(strict_types=1);

namespace Tests\Feature\Library;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Library\Models\LibraryItem;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Administration of the resource library (FR-RES-02/03): who may publish, and what
 * shapes the API refuses to store.
 *
 * The companion file is PublicLibraryVisibilityTest, which covers the anonymous
 * side. Between them the rule to hold is: only an administrator puts anything in,
 * and only a published thing comes out.
 */
class LibraryAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Storage::fake('local');
    }

    private function token(RoleKey $role): string
    {
        $needsMda = in_array($role, [RoleKey::MdaAdmin], true);

        return User::factory()->create([
            'mda_id' => $needsMda ? Mda::factory()->create()->id : null,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ])->createToken('t')->plainTextToken;
    }

    private function admin(): string
    {
        return $this->token(RoleKey::SystemAdministrator);
    }

    /**
     * Assert a 422 naming a specific field.
     *
     * Not `assertJsonValidationErrors`: this application does not use Laravel's
     * default `errors` envelope. bootstrap/app.php renders failures through
     * ApiResponse::error as `error.details[] = {field, message}`, so the framework
     * helper looks in a key that is never there and passes or fails for the wrong
     * reason.
     */
    private function assertRejected(TestResponse $response, string $field): void
    {
        $response->assertStatus(422);

        $fields = collect((array) $response->json('error.details'))->pluck('field')->all();

        $this->assertContains(
            $field,
            $fields,
            "Expected a validation error on `{$field}`; got: ".implode(', ', $fields ?: ['none']),
        );
    }

    /* ------------------------------------------------------------ who may write */

    public function test_an_anonymous_visitor_cannot_create_a_resource(): void
    {
        $this->postJson('/api/v1/library', ['title' => 'Sneaked in'])->assertUnauthorized();
    }

    public function test_an_mda_admin_cannot_create_a_resource(): void
    {
        $this->withToken($this->token(RoleKey::MdaAdmin))
            ->postJson('/api/v1/library', ['title' => 'Not mine to publish'])
            ->assertForbidden();
    }

    public function test_a_development_partner_cannot_create_a_resource(): void
    {
        $this->withToken($this->token(RoleKey::DevelopmentPartner))
            ->postJson('/api/v1/library', ['title' => 'Not mine to publish'])
            ->assertForbidden();
    }

    /*
     * One role per test, deliberately. Laravel's auth guard caches the resolved user
     * for the life of the application instance, so a second authenticated request in
     * the SAME test method is still answered as the first user — which made an
     * earlier version of this file report the administrator as forbidden. Separate
     * methods get separate instances, and the failure names the role.
     */

    public function test_the_admin_list_is_closed_to_anonymous_callers(): void
    {
        $this->getJson('/api/v1/library')->assertUnauthorized();
    }

    public function test_the_admin_list_is_closed_to_an_executive(): void
    {
        $this->withToken($this->token(RoleKey::Executive))
            ->getJson('/api/v1/library')->assertForbidden();
    }

    public function test_the_admin_list_is_open_to_an_administrator(): void
    {
        $this->withToken($this->admin())->getJson('/api/v1/library')->assertOk();
    }

    /* ------------------------------------------------------------- happy paths */

    public function test_an_administrator_uploads_a_file_resource_and_publishes_it(): void
    {
        $response = $this->withToken($this->admin())->post('/api/v1/library', [
            'title' => 'State Social Protection Policy',
            'description' => 'The governing policy document.',
            'category' => 'policy',
            'kind' => 'file',
            'status' => 'published',
            'featured' => true,
            'file' => UploadedFile::fake()->create('policy.pdf', 120, 'application/pdf'),
        ])->assertCreated();

        $id = $response->json('data.resource.id');
        $item = LibraryItem::findOrFail($id);

        $this->assertSame('published', $item->status->value);
        $this->assertNotNull($item->published_at, 'Publishing must stamp published_at.');
        $this->assertTrue($item->featured);
        $this->assertNotNull($item->checksum_sha256);
        Storage::disk('local')->assertExists($item->stored_path);

        // The stored name must not be the one the uploader chose.
        $this->assertStringNotContainsString('policy.pdf', $item->stored_path);
        $this->assertSame('policy.pdf', $item->original_filename);

        // And it is immediately on the public page.
        $this->getJson('/api/v1/public/library')
            ->assertOk()->assertJsonPath('data.items.0.title', 'State Social Protection Policy');
    }

    public function test_an_administrator_adds_a_link_resource(): void
    {
        $this->withToken($this->admin())->postJson('/api/v1/library', [
            'title' => 'National guidance portal',
            'category' => 'guideline',
            'kind' => 'link',
            'status' => 'published',
            'external_url' => 'https://example.test/guidance',
        ])->assertCreated();

        $item = LibraryItem::firstOrFail();
        $this->assertNull($item->stored_path);
        $this->assertSame('https://example.test/guidance', $item->external_url);
    }

    public function test_a_draft_can_be_published_later_without_re_uploading(): void
    {
        $draft = LibraryItem::factory()->create(['title' => 'Draft circular']);

        $this->withToken($this->admin())
            ->patchJson("/api/v1/library/{$draft->id}", ['status' => 'published'])
            ->assertOk()->assertJsonPath('data.resource.status', 'published');

        $this->assertNotNull($draft->fresh()->published_at);
    }

    public function test_archiving_keeps_the_row_and_its_download_history(): void
    {
        $item = LibraryItem::factory()->published()->create();
        LibraryItem::whereKey($item->id)->update(['download_count' => 17]);

        $this->withToken($this->admin())
            ->patchJson("/api/v1/library/{$item->id}", ['status' => 'archived'])
            ->assertOk();

        $fresh = $item->fresh();
        $this->assertNotNull($fresh, 'Archiving must never delete the row (CLAUDE.md §10).');
        $this->assertSame(17, $fresh->download_count);
    }

    /* ---------------------------------------------------- what is refused, and why */

    public function test_a_resource_cannot_be_both_a_file_and_a_link(): void
    {
        $response = $this->withToken($this->admin())->post('/api/v1/library', [
            'title' => 'Both at once',
            'category' => 'tool',
            'kind' => 'file',
            'external_url' => 'https://example.test/thing',
            'file' => UploadedFile::fake()->create('thing.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejected($response, 'external_url');
    }

    public function test_a_resource_cannot_be_neither(): void
    {
        $response = $this->withToken($this->admin())->postJson('/api/v1/library', [
            'title' => 'Nothing attached',
            'category' => 'tool',
            'kind' => 'file',
        ]);

        $this->assertRejected($response, 'file');
    }

    /**
     * The one that matters most. The download endpoint is unauthenticated, so a
     * document that renders from the State's own origin is a stored-XSS primitive
     * however the response is dressed.
     */
    public function test_an_html_upload_is_refused(): void
    {
        $response = $this->withToken($this->admin())->post('/api/v1/library', [
            'title' => 'Not a document',
            'category' => 'tool',
            'kind' => 'file',
            'file' => UploadedFile::fake()->create('payload.html', 4, 'text/html'),
        ]);

        $this->assertRejected($response, 'file');
    }

    public function test_an_svg_thumbnail_is_refused(): void
    {
        $response = $this->withToken($this->admin())->post('/api/v1/library', [
            'title' => 'Scriptable artwork',
            'category' => 'tool',
            'kind' => 'file',
            'file' => UploadedFile::fake()->create('fine.pdf', 10, 'application/pdf'),
            'thumbnail' => UploadedFile::fake()->create('logo.svg', 4, 'image/svg+xml'),
        ]);

        $this->assertRejected($response, 'thumbnail');
    }

    public function test_a_file_over_the_ceiling_is_refused(): void
    {
        $overLimit = ((int) config('library.max_file_kb')) + 1024;

        $response = $this->withToken($this->admin())->post('/api/v1/library', [
            'title' => 'Too large',
            'category' => 'report',
            'kind' => 'file',
            'file' => UploadedFile::fake()->create('huge.pdf', $overLimit, 'application/pdf'),
        ]);

        $this->assertRejected($response, 'file');
    }

    public function test_an_unknown_category_is_refused(): void
    {
        $response = $this->withToken($this->admin())->post('/api/v1/library', [
            'title' => 'Miscategorised',
            'category' => 'not-a-category',
            'kind' => 'file',
            'file' => UploadedFile::fake()->create('fine.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejected($response, 'category');
    }

    public function test_a_resource_cannot_be_created_already_archived(): void
    {
        $response = $this->withToken($this->admin())->post('/api/v1/library', [
            'title' => 'Born withdrawn',
            'category' => 'report',
            'kind' => 'file',
            'status' => 'archived',
            'file' => UploadedFile::fake()->create('fine.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejected($response, 'status');
    }

    /* ------------------------------------------------------------- replacement */

    public function test_replacing_a_file_removes_the_old_one_from_disk(): void
    {
        $token = $this->admin();

        $id = $this->withToken($token)->post('/api/v1/library', [
            'title' => 'Versioned guidance',
            'category' => 'guideline',
            'kind' => 'file',
            'file' => UploadedFile::fake()->create('v1.pdf', 10, 'application/pdf'),
        ])->assertCreated()->json('data.resource.id');

        $original = LibraryItem::findOrFail($id)->stored_path;

        $this->withToken($token)->post("/api/v1/library/{$id}", [
            '_method' => 'PATCH',
            'file' => UploadedFile::fake()->create('v2.pdf', 10, 'application/pdf'),
        ])->assertOk();

        $replacement = LibraryItem::findOrFail($id)->stored_path;

        $this->assertNotSame($original, $replacement);
        Storage::disk('local')->assertExists($replacement);
        Storage::disk('local')->assertMissing($original);
    }

    public function test_switching_a_file_resource_to_a_link_clears_the_file_columns(): void
    {
        $item = LibraryItem::factory()->published()->create();

        $this->withToken($this->admin())->patchJson("/api/v1/library/{$item->id}", [
            'kind' => 'link',
            'external_url' => 'https://example.test/moved',
        ])->assertOk();

        $fresh = $item->fresh();
        $this->assertNull($fresh->stored_path);
        $this->assertNull($fresh->mime_type);
        $this->assertSame('https://example.test/moved', $fresh->external_url);
    }

    public function test_editing_a_title_does_not_demand_the_file_again(): void
    {
        $item = LibraryItem::factory()->published()->create(['title' => 'Old name']);

        $this->withToken($this->admin())
            ->patchJson("/api/v1/library/{$item->id}", ['title' => 'New name'])
            ->assertOk()->assertJsonPath('data.resource.title', 'New name');
    }
}
