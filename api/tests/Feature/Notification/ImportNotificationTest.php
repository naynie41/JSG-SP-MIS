<?php

declare(strict_types=1);

namespace Tests\Feature\Notification;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Notification\Models\Notification;
use App\Domain\Registry\Events\ImportBatchCompleted;
use App\Domain\Registry\Events\ImportDuplicatesSurfaced;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Import notifications (FR-NOT-01 over the Phase 5 Notifier): duplicate alerts and
 * import results.
 *
 * An import is asynchronous — the officer who uploaded or confirmed it has usually
 * navigated away by the time screening or the commit finishes, so the outcome has to
 * reach them through the bell. Both events carry COUNTS only: the message travels by
 * email as well as in-app, so no name or identifier may ride on it.
 */
class ImportNotificationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Mda $otherMda;

    private User $uploader;

    private User $colleague;

    private User $outsider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->uploader = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->colleague = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->outsider = $this->user($this->otherMda, RoleKey::MdaAdmin);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function batch(?User $uploadedBy): ImportBatch
    {
        return ImportBatch::create([
            'owner_mda_id' => $this->mda->id,
            'uploaded_by' => $uploadedBy?->id,
            'original_filename' => 'dutse-q1.csv',
            'stored_path' => 'imports/dutse-q1.csv',
            'source' => 'csv',
            'status' => 'preview_ready',
        ]);
    }

    /** @return list<Notification> */
    private function notificationsFor(User $user, string $type): array
    {
        return Notification::query()
            ->where('recipient_user_id', $user->id)
            ->where('type', $type)
            ->get()
            ->all();
    }

    /* ------------------------------------------------------- duplicate alerts */

    public function test_surfaced_duplicates_notify_the_uploader(): void
    {
        ImportDuplicatesSurfaced::dispatch($this->batch($this->uploader), 2, 3);

        $received = $this->notificationsFor($this->uploader, 'import.duplicates_surfaced');

        $this->assertCount(1, $received, 'one notification per batch, not per row');
        $this->assertStringContainsString('5', $received[0]->subject);
        $this->assertStringContainsString('dutse-q1.csv', (string) $received[0]->body);
    }

    public function test_a_duplicate_alert_deep_links_to_the_batch(): void
    {
        $batch = $this->batch($this->uploader);
        ImportDuplicatesSurfaced::dispatch($batch, 1, 0);

        $notification = $this->notificationsFor($this->uploader, 'import.duplicates_surfaced')[0];

        // The client resolves (type, id) to a route — `import_batch` is the morph alias.
        $this->assertSame('import_batch', $notification->related_type);
        $this->assertSame($batch->id, $notification->related_id);
    }

    public function test_a_duplicate_alert_separates_exact_from_probable(): void
    {
        ImportDuplicatesSurfaced::dispatch($this->batch($this->uploader), 4, 7);

        $notification = $this->notificationsFor($this->uploader, 'import.duplicates_surfaced')[0];

        // The two bands mean different work — one is settled, one needs a judgement —
        // so the message must not merge them into a single number.
        $this->assertStringContainsString('4 matched an existing record on an identifier', (string) $notification->body);
        $this->assertStringContainsString('7 need a same-person decision', (string) $notification->body);
        $this->assertSame(['exact' => 4, 'probable' => 7], $notification->payload);
    }

    public function test_a_clean_import_raises_no_duplicate_alert(): void
    {
        // The event is only dispatched when something matched; nothing to assert but the
        // absence, which is the point — a clean file must not ping anyone.
        $this->assertCount(0, $this->notificationsFor($this->uploader, 'import.duplicates_surfaced'));
    }

    /* --------------------------------------------------------- import results */

    public function test_a_completed_import_notifies_the_uploader_with_its_tallies(): void
    {
        ImportBatchCompleted::dispatch($this->batch($this->uploader), 120, 5, 3);

        $received = $this->notificationsFor($this->uploader, 'import.completed');

        $this->assertCount(1, $received);
        $this->assertStringContainsString('dutse-q1.csv', $received[0]->subject);
        $this->assertStringContainsString('120 registered', (string) $received[0]->body);
        $this->assertStringContainsString('5 linked to an existing record', (string) $received[0]->body);
        $this->assertSame(['committed' => 120, 'served' => 5, 'skipped' => 3], $received[0]->payload);
    }

    /* ------------------------------------------------------------- MDA scoping */

    public function test_an_import_notification_never_reaches_another_mda(): void
    {
        ImportDuplicatesSurfaced::dispatch($this->batch($this->uploader), 1, 1);
        ImportBatchCompleted::dispatch($this->batch($this->uploader), 1, 0, 0);

        $this->assertCount(0, Notification::query()->where('recipient_user_id', $this->outsider->id)->get()->all());
    }

    public function test_it_notifies_the_uploader_and_not_the_whole_mda(): void
    {
        ImportBatchCompleted::dispatch($this->batch($this->uploader), 10, 0, 0);

        // A routine import is the uploader's business; fanning it out would make the bell
        // useless for everyone else in the MDA.
        $this->assertCount(1, $this->notificationsFor($this->uploader, 'import.completed'));
        $this->assertCount(0, $this->notificationsFor($this->colleague, 'import.completed'));
    }

    public function test_an_unattributed_batch_falls_back_to_the_owning_mdas_importers(): void
    {
        // An API-intake batch has no interactive uploader. The result must not be lost —
        // and must still stay inside the owning MDA.
        ImportBatchCompleted::dispatch($this->batch(null), 7, 0, 0);

        $inside = array_merge(
            $this->notificationsFor($this->uploader, 'import.completed'),
            $this->notificationsFor($this->colleague, 'import.completed'),
        );

        $this->assertNotEmpty($inside, 'an unattributed result must still reach the owning MDA');
        $this->assertCount(0, Notification::query()->where('recipient_user_id', $this->outsider->id)->get()->all());
    }

    /* ------------------------------------------------------------------ no PII */

    public function test_an_import_notification_carries_no_identity_data(): void
    {
        $batch = $this->batch($this->uploader);
        ImportDuplicatesSurfaced::dispatch($batch, 3, 2);
        ImportBatchCompleted::dispatch($batch, 3, 2, 0);

        $json = (string) json_encode(
            Notification::query()->where('recipient_user_id', $this->uploader->id)->get()->toArray()
        );

        // Counts and a filename only — these messages also go out by email.
        foreach (['nin', 'bvn', 'first_name', 'last_name', 'beneficiary_id'] as $leak) {
            $this->assertStringNotContainsString($leak, $json, "an import notification must not carry {$leak}");
        }
    }

    /* -------------------------------------------------- reaches the bell endpoint */

    public function test_the_uploader_sees_both_events_in_their_notification_list(): void
    {
        $batch = $this->batch($this->uploader);
        ImportDuplicatesSurfaced::dispatch($batch, 1, 1);
        ImportBatchCompleted::dispatch($batch, 1, 1, 0);

        $response = $this->withToken($this->uploader->createToken('t')->plainTextToken)
            ->getJson('/api/v1/notifications')->assertOk();
        $this->app['auth']->forgetGuards();

        $types = array_column($response->json('data'), 'type');
        $this->assertContains('import.duplicates_surfaced', $types);
        $this->assertContains('import.completed', $types);

        // …and the unread badge counts them.
        $count = $this->withToken($this->uploader->createToken('t2')->plainTextToken)
            ->getJson('/api/v1/notifications/unread-count')->assertOk()->json('data.unread');
        $this->app['auth']->forgetGuards();

        $this->assertSame(2, $count);
    }
}
