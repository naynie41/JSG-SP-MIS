<?php

declare(strict_types=1);

namespace Tests\Feature\Notification;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Notification\Mail\NotificationMail;
use App\Domain\Notification\Models\Notification;
use App\Domain\Notification\Models\NotificationPreference;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\ServiceRequestService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The request-to-serve EMAIL to the owner MDA (FR-OWN-06, FR-NOT-01/02).
 *
 * Nothing reaches the beneficiary until an approver in the owning MDA decides, so an
 * unread request is a service not delivered. That is why this one is pushed rather than
 * left in a bell the approver may not open today.
 *
 * The constraint that shapes the content: email is NOT a secure channel (NDPA/NDPR
 * minimisation). It crosses relays nobody here controls and lands in inboxes on
 * unmanaged devices. So the message carries the ORGANISATIONS and the WORK — who is
 * asking, under which activity, and a link — and never the person. Identity is reached
 * by signing in, where scope, role and the audit trail still apply.
 */
class ServiceRequestEmailTest extends TestCase
{
    use RefreshDatabase;

    private Mda $ownerMda;

    private Mda $servingMda;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    private Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->ownerMda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->servingMda = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['ownerAdmin'] = $this->user($this->ownerMda, RoleKey::MdaAdmin);
        $this->users['servingOfficer'] = $this->user($this->servingMda, RoleKey::MdaAdmin);

        $this->activity = Activity::factory()->forProgramme(
            Programme::factory()->individual()->create(),
            $this->servingMda,
        )->create(['name' => 'Q3 School Feeding Round']);

        // A distinctive identity, so the no-PII assertions can look for real values
        // rather than for a placeholder that could never have leaked.
        $this->beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->ownerMda->id,
            'first_name' => 'Hauwa',
            'last_name' => 'Ibrahim',
            'nin' => '22200000011',
            'phone' => '08031234567',
        ]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /**
     * Raise the request the way the import committer does (§10) — through the service,
     * carrying the requester's activity.
     *
     * NOT through POST /service-requests: that endpoint accepts only a beneficiary and a
     * reason, so a manually-raised request has no activity to name. Going through the
     * service exercises the path that actually produces one, and keeps this test about
     * the notification rather than about another endpoint's contract.
     */
    private function raise(bool $withActivity = true): string
    {
        $this->actingAs($this->users['servingOfficer']);

        $request = app(ServiceRequestService::class)->request(
            $this->beneficiary,
            $this->servingMda->id,
            $this->users['servingOfficer'],
            null,
            null,
            $withActivity ? $this->activity->id : null,
        );

        $this->app['auth']->forgetGuards();

        return $request->id;
    }

    /** The rendered HTML of the one queued mail. */
    private function renderedMail(): string
    {
        $captured = null;
        Mail::assertQueued(NotificationMail::class, function (NotificationMail $mail) use (&$captured): bool {
            $captured = $mail;

            return true;
        });

        /** @var NotificationMail $captured */
        return (string) $captured->build()->render();
    }

    /* ------------------------------------------------------------ action-required */

    public function test_the_owner_mda_is_emailed_with_an_action_required_message_and_a_link(): void
    {
        Mail::fake();
        $this->raise();

        Mail::assertQueued(
            NotificationMail::class,
            fn (NotificationMail $mail): bool => $mail->hasTo($this->users['ownerAdmin']->email)
        );

        $html = $this->renderedMail();

        // Framing: something is waiting on THIS person, and here is where to do it.
        $this->assertStringContainsStringIgnoringCase('accept or decline', $html);
        $this->assertStringContainsString(
            rtrim((string) config('notifications.app_url'), '/').'/service-requests',
            $html,
            'an action-required email with no destination is just an announcement',
        );
    }

    public function test_the_email_names_the_requesting_mda_and_the_activity(): void
    {
        Mail::fake();
        $this->raise();

        $html = $this->renderedMail();

        // What the approver is being asked to allow, before they sign in.
        $this->assertStringContainsString('Ministry of Education', $html);
        $this->assertStringContainsString('Q3 School Feeding Round', $html);
    }

    public function test_a_request_without_an_activity_says_so_by_omission(): void
    {
        // A request can precede any activity. Naming one that does not exist would be a
        // fiction the approver might act on.
        Mail::fake();
        $this->raise(withActivity: false);

        $html = $this->renderedMail();

        $this->assertStringContainsString('Ministry of Education', $html);
        $this->assertStringNotContainsString('under the activity', $html);
    }

    /* ------------------------------------------------------------- the no-PII rule */

    public function test_the_email_carries_no_beneficiary_identity(): void
    {
        Mail::fake();
        $this->raise();

        $html = $this->renderedMail();

        foreach (['Hauwa', 'Ibrahim', '22200000011', '08031234567'] as $identity) {
            $this->assertStringNotContainsString(
                $identity,
                $html,
                "email is an insecure channel — “{$identity}” must never leave in the body",
            );
        }

        // Nor the id, which is a lookup key into the registry.
        $this->assertStringNotContainsString($this->beneficiary->id, $html);
    }

    public function test_the_in_app_notification_carries_no_beneficiary_identity_either(): void
    {
        // The bell is authenticated and scoped, so this is a lesser risk — but the SAME
        // composed text feeds both channels, and this is the assertion that fails first
        // if someone adds a name "just for the in-app version".
        Mail::fake();
        $this->raise();

        $notification = Notification::query()
            ->withoutGlobalScopes()
            ->where('type', 'service_request.created')
            ->firstOrFail();

        $text = $notification->subject.' '.$notification->body;
        foreach (['Hauwa', 'Ibrahim', '22200000011', '08031234567'] as $identity) {
            $this->assertStringNotContainsString($identity, $text);
        }
    }

    /* --------------------------------------------------------------- preferences */

    public function test_an_approver_who_turned_email_off_still_gets_the_in_app_notification(): void
    {
        NotificationPreference::create([
            'user_id' => $this->users['ownerAdmin']->id,
            'email_enabled' => false,
        ]);

        Mail::fake();
        $this->raise();

        Mail::assertNothingQueued();
        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['ownerAdmin']->id,
            'type' => 'service_request.created',
        ]);
    }

    public function test_the_email_tells_the_reader_how_to_turn_these_off(): void
    {
        // Preferences ARE the unsubscribe mechanism, so the mail has to say where they
        // live — an email the reader cannot stop is the part that draws complaints.
        Mail::fake();
        $this->raise();

        $this->assertStringContainsString(
            rtrim((string) config('notifications.app_url'), '/').config('notifications.preferences_path'),
            $this->renderedMail(),
        );
    }

    public function test_the_channel_can_be_switched_off_entirely(): void
    {
        config(['notifications.channels.email.enabled' => false]);

        Mail::fake();
        $this->raise();

        Mail::assertNothingQueued();
        $this->assertDatabaseHas('notifications', ['type' => 'service_request.created']);
    }

    /* ------------------------------------------------------------------- the log */

    public function test_every_send_is_logged_without_the_address_or_the_content(): void
    {
        Mail::fake();
        $this->raise();

        $log = AuditLog::query()->withoutGlobalScopes()
            ->where('action', 'notification.email_queued')
            ->firstOrFail();

        $this->assertSame($this->users['ownerAdmin']->id, $log->after['recipient_user_id']);
        $this->assertSame('service_request.created', $log->after['notification_type']);
        // Anchored to the request, so "what was sent about this" is one query.
        $this->assertSame('service_request', $log->entity_type);

        // Recording WHO and WHAT-KIND, never the address or the words: an audit trail
        // that quotes a notification re-creates the disclosure the message avoided.
        $encoded = json_encode($log->after);
        $this->assertStringNotContainsString($this->users['ownerAdmin']->email, (string) $encoded);
        $this->assertStringNotContainsString('Action required', (string) $encoded);
    }

    public function test_nothing_is_logged_when_the_preference_suppresses_the_email(): void
    {
        NotificationPreference::create([
            'user_id' => $this->users['ownerAdmin']->id,
            'email_enabled' => false,
        ]);

        Mail::fake();
        $this->raise();

        $this->assertSame(
            0,
            AuditLog::query()->withoutGlobalScopes()->where('action', 'notification.email_queued')->count(),
            'a suppressed email is not a send',
        );
    }

    /* --------------------------------------------------- closing the loop by email */

    public function test_accepting_emails_the_requesting_officer_with_a_link(): void
    {
        $id = $this->raise();

        Mail::fake();
        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$id}/accept")->assertOk();

        Mail::assertQueued(
            NotificationMail::class,
            fn (NotificationMail $mail): bool => $mail->hasTo($this->users['servingOfficer']->email)
        );

        $html = $this->renderedMail();
        $this->assertStringContainsStringIgnoringCase('accepted', $html);
        $this->assertStringContainsString('/service-requests', $html);
        $this->assertStringNotContainsString('Hauwa', $html);
    }

    public function test_declining_emails_the_requesting_officer_with_the_reason(): void
    {
        $id = $this->raise();

        Mail::fake();
        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$id}/decline", ['reason' => 'Already served this quarter'])
            ->assertOk();

        Mail::assertQueued(
            NotificationMail::class,
            fn (NotificationMail $mail): bool => $mail->hasTo($this->users['servingOfficer']->email)
        );

        $html = $this->renderedMail();
        $this->assertStringContainsString('Already served this quarter', $html);
        $this->assertStringNotContainsString('Hauwa', $html);
    }
}
