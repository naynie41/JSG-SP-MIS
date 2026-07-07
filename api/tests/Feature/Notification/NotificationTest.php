<?php

declare(strict_types=1);

namespace Tests\Feature\Notification;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Notification\Channels\SmsChannel;
use App\Domain\Notification\Channels\WhatsAppChannel;
use App\Domain\Notification\Mail\NotificationMail;
use App\Domain\Notification\Models\Notification;
use App\Domain\Notification\Models\NotificationPreference;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Notification system (PRD FR-NOT-01/02): domain events produce in-app + queued
 * email notifications to the right recipients; stubbed channels stay inert;
 * preferences gate email; users list/mark-read only their own (scoped).
 */
class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $ownerMda;    // owns the beneficiary + approves requests

    private Mda $servingMda;  // raises the request

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->ownerMda = Mda::factory()->create(['name' => 'Owner MDA']);
        $this->servingMda = Mda::factory()->create(['name' => 'Serving MDA']);

        $this->users['ownerAdmin'] = $this->user($this->ownerMda, RoleKey::MdaAdmin);   // beneficiary.approve
        $this->users['servingOfficer'] = $this->user($this->servingMda, RoleKey::MdaOfficer); // beneficiary.create
        $this->users['servingAdmin'] = $this->user($this->servingMda, RoleKey::MdaAdmin);     // can request a transfer

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->ownerMda->id]);
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

    private function raiseServiceRequest(): string
    {
        return $this->send('servingOfficer', 'POST', '/api/v1/service-requests', ['beneficiary_id' => $this->beneficiary->id])
            ->assertCreated()
            ->json('data.id');
    }

    public function test_raising_a_service_request_notifies_owner_approvers_in_app_and_by_email(): void
    {
        Mail::fake();

        $this->raiseServiceRequest();

        // In-app notification to the owner MDA's approver, referencing the request.
        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['ownerAdmin']->id,
            'recipient_mda_id' => $this->ownerMda->id,
            'type' => 'service_request.created',
            'related_type' => 'service_request',
        ]);
        // The requester (different MDA) is NOT an approver → not notified.
        $this->assertDatabaseMissing('notifications', ['recipient_user_id' => $this->users['servingOfficer']->id]);

        // A queued email went to the approver (SMTP config; log mailer in dev).
        Mail::assertQueued(NotificationMail::class, fn (NotificationMail $mail) => $mail->hasTo($this->users['ownerAdmin']->email));
    }

    public function test_stubbed_channels_are_inert_and_report_unavailable(): void
    {
        $this->assertFalse((new SmsChannel)->isAvailable());
        $this->assertFalse((new WhatsAppChannel)->isAvailable());

        // The dispatch completes without invoking the stubs (they would throw).
        Mail::fake();
        $this->raiseServiceRequest();
        $this->assertSame(1, Notification::query()->withoutGlobalScopes()->count());
    }

    public function test_email_channel_respects_the_recipient_preference(): void
    {
        // The approver opts out of email; in-app still delivered.
        NotificationPreference::create(['user_id' => $this->users['ownerAdmin']->id, 'email_enabled' => false]);

        Mail::fake();
        $this->raiseServiceRequest();

        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['ownerAdmin']->id,
            'type' => 'service_request.created',
        ]);
        Mail::assertNothingQueued();
    }

    public function test_accepting_a_service_request_notifies_the_requester(): void
    {
        $requestId = $this->raiseServiceRequest();
        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$requestId}/accept")->assertOk();

        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['servingOfficer']->id,
            'type' => 'service_request.accepted',
            'related_type' => 'service_request',
        ]);
    }

    public function test_declining_a_service_request_notifies_the_requester(): void
    {
        $requestId = $this->raiseServiceRequest();
        $this->send('ownerAdmin', 'POST', "/api/v1/service-requests/{$requestId}/decline", ['reason' => 'Not eligible'])->assertOk();

        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['servingOfficer']->id,
            'type' => 'service_request.declined',
        ]);
        $declined = ServiceRequest::query()->withoutGlobalScopes()->findOrFail($requestId);
        $this->assertSame('declined', $declined->status->value);
    }

    public function test_ownership_transfer_request_notifies_the_current_owner(): void
    {
        // The serving MDA (admin — has beneficiary.approve) requests ownership.
        $this->send('servingAdmin', 'POST', "/api/v1/beneficiaries/{$this->beneficiary->id}/ownership-transfers", [])
            ->assertCreated();

        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['ownerAdmin']->id,
            'type' => 'ownership_transfer.requested',
            'related_type' => 'ownership_transfer_request',
        ]);
    }

    public function test_user_lists_and_marks_only_their_own_notifications(): void
    {
        // One notification for each user.
        $mine = Notification::create([
            'recipient_user_id' => $this->users['ownerAdmin']->id,
            'recipient_mda_id' => $this->ownerMda->id,
            'type' => 'test.mine', 'subject' => 'Mine',
        ]);
        Notification::create([
            'recipient_user_id' => $this->users['servingOfficer']->id,
            'recipient_mda_id' => $this->servingMda->id,
            'type' => 'test.theirs', 'subject' => 'Theirs',
        ]);

        // The owner admin sees only their own.
        $this->send('ownerAdmin', 'GET', '/api/v1/notifications')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.subject', 'Mine')
            ->assertJsonPath('meta.unread', 1);

        // Unread filter + count.
        $this->send('ownerAdmin', 'GET', '/api/v1/notifications?filter[status]=unread')->assertOk()->assertJsonCount(1, 'data');
        $this->send('ownerAdmin', 'GET', '/api/v1/notifications/unread-count')->assertOk()->assertJsonPath('data.unread', 1);

        // Mark one read.
        $this->send('ownerAdmin', 'POST', "/api/v1/notifications/{$mine->id}/read")->assertOk();
        $this->assertNotNull($mine->fresh()->read_at);
        $this->send('ownerAdmin', 'GET', '/api/v1/notifications/unread-count')->assertOk()->assertJsonPath('data.unread', 0);

        // Cannot mark another user's notification (not in my scope → 404).
        $theirs = Notification::query()->withoutGlobalScopes()->where('type', 'test.theirs')->firstOrFail();
        $this->send('ownerAdmin', 'POST', "/api/v1/notifications/{$theirs->id}/read")->assertStatus(404);
    }

    public function test_mark_all_read_and_preferences(): void
    {
        Notification::create(['recipient_user_id' => $this->users['ownerAdmin']->id, 'recipient_mda_id' => $this->ownerMda->id, 'type' => 't.a', 'subject' => 'A']);
        Notification::create(['recipient_user_id' => $this->users['ownerAdmin']->id, 'recipient_mda_id' => $this->ownerMda->id, 'type' => 't.b', 'subject' => 'B']);

        $this->send('ownerAdmin', 'POST', '/api/v1/notifications/read-all')->assertOk()->assertJsonPath('data.marked_read', 2);
        $this->send('ownerAdmin', 'GET', '/api/v1/notifications/unread-count')->assertOk()->assertJsonPath('data.unread', 0);

        // Preferences default on, then toggled.
        $this->send('ownerAdmin', 'GET', '/api/v1/notifications/preferences')->assertOk()->assertJsonPath('data.email_enabled', true);
        $this->send('ownerAdmin', 'PUT', '/api/v1/notifications/preferences', ['email_enabled' => false])
            ->assertOk()->assertJsonPath('data.email_enabled', false);
    }
}
