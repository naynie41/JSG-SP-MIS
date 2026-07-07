<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\OwnershipTransferStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\OwnershipTransferRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ownership model acceptance (PRD FR-OWN-02/03/05): owner-only edit, the
 * cross-MDA lookup/serve seam, and owner-approved ownership transfer.
 */
class OwnershipTest extends TestCase
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

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        // Beneficiary owned by MDA A, with a known NIN for the lookup test and
        // distinguishing full-record fields (absent from the reveal projection).
        $this->benA = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'nin' => '12345678901',
            'first_name' => 'Amina',
            'last_name' => 'Yusuf',
            'date_of_birth' => '1990-05-05',
            'gender' => 'female',
            'phone' => '08030000001',
        ]);

        // All users are created up front (before any request) so an Auditable
        // model is never created between sub-requests with a stale Auth::user.
        $this->users['owner_officer'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['nonowner_officer'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['owner_admin'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['nonowner_admin'] = $this->user($this->mdaB, RoleKey::MdaAdmin);
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

    public function test_owner_mda_can_edit_core_profile(): void
    {
        $this->withToken($this->tokenFor('owner_officer'))
            ->patchJson("/api/v1/beneficiaries/{$this->benA->id}", ['last_name' => 'Ibrahim'])
            ->assertOk()
            ->assertJsonPath('data.id', $this->benA->id);

        $this->assertDatabaseHas('beneficiaries', [
            'id' => $this->benA->id,
            'last_name' => 'Ibrahim',
        ]);
    }

    public function test_non_owner_mda_cannot_edit_core_profile(): void
    {
        // The non-owner holds beneficiary.edit but does not own the record, so the
        // policy denies with 403 (not 404 — it can already see it via lookup).
        $this->withToken($this->tokenFor('nonowner_officer'))
            ->patchJson("/api/v1/beneficiaries/{$this->benA->id}", ['last_name' => 'Hacked'])
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'FORBIDDEN');

        $this->assertDatabaseHas('beneficiaries', [
            'id' => $this->benA->id,
            'last_name' => 'Yusuf',
        ]);
    }

    public function test_non_owner_can_look_up_and_sees_only_reveal_fields(): void
    {
        $response = $this->withToken($this->tokenFor('nonowner_officer'))
            ->getJson('/api/v1/beneficiaries/lookup?nin=12345678901')
            ->assertOk()
            ->assertJsonPath('data.matches.0.id', $this->benA->id)
            ->assertJsonPath('data.matches.0.full_name', 'Amina Yusuf')
            ->assertJsonPath('data.matches.0.owner_mda.id', $this->mdaA->id);

        $match = $response->json('data.matches.0');

        // The reveal projection must never leak identifiers or sensitive fields.
        foreach (['nin', 'bvn', 'phone', 'address', 'date_of_birth'] as $forbidden) {
            $this->assertArrayNotHasKey($forbidden, $match, "Lookup leaked {$forbidden}");
        }
    }

    public function test_full_record_read_opens_only_after_service_request_acceptance(): void
    {
        // --- BEFORE acceptance: reveal-only, no full-record read. ---
        // The cross-MDA reveal shows only the permitted fields (name+id, owner MDA,
        // source, registration date, LGA/Ward, status) + programme/benefit summary.
        $reveal = $this->withToken($this->tokenFor('nonowner_officer'))
            ->getJson('/api/v1/beneficiaries/lookup?nin=12345678901')
            ->assertOk()
            ->assertJsonPath('data.matches.0.full_name', 'Amina Yusuf')
            ->assertJsonPath('data.matches.0.owner_mda.id', $this->mdaA->id)
            ->json('data.matches.0');
        $this->assertArrayHasKey('programmes', $reveal);
        $this->assertArrayHasKey('benefits', $reveal);
        foreach (['date_of_birth', 'gender', 'phone', 'nin', 'bvn', 'address', 'first_name'] as $forbidden) {
            $this->assertArrayNotHasKey($forbidden, $reveal, "Reveal leaked full-record field {$forbidden}");
        }
        $this->app['auth']->forgetGuards();

        // Full-record read is refused (404) — no accepted Service Request yet.
        $this->withToken($this->tokenFor('nonowner_officer'))
            ->getJson("/api/v1/beneficiaries/{$this->benA->id}")
            ->assertStatus(404);
        $this->app['auth']->forgetGuards();

        // --- Raise a Service Request and have the owner accept it. ---
        $requestId = $this->withToken($this->tokenFor('nonowner_officer'))
            ->postJson('/api/v1/service-requests', ['beneficiary_id' => $this->benA->id])
            ->assertCreated()
            ->json('data.id');
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('owner_admin'))
            ->postJson("/api/v1/service-requests/{$requestId}/accept")
            ->assertOk()
            ->assertJsonPath('data.status', 'accepted');
        $this->app['auth']->forgetGuards();

        // --- AFTER acceptance: the serving MDA reads the FULL record (read-only). ---
        $this->withToken($this->tokenFor('nonowner_officer'))
            ->getJson("/api/v1/beneficiaries/{$this->benA->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $this->benA->id)
            ->assertJsonPath('data.date_of_birth', '1990-05-05')
            ->assertJsonPath('data.gender', 'female')
            ->assertJsonPath('data.phone', '08030000001');
        $this->app['auth']->forgetGuards();

        // Ownership never moved, and read access is not edit access.
        $this->assertSame($this->mdaA->id, $this->benA->fresh()->owner_mda_id);
        $this->withToken($this->tokenFor('nonowner_officer'))
            ->patchJson("/api/v1/beneficiaries/{$this->benA->id}", ['last_name' => 'Nope'])
            ->assertStatus(403);
    }

    public function test_ownership_transfer_requires_owner_approval_and_is_audited(): void
    {
        // Non-owner MDA B requests ownership of MDA A's beneficiary.
        $create = $this->withToken($this->tokenFor('nonowner_admin'))
            ->postJson("/api/v1/beneficiaries/{$this->benA->id}/ownership-transfers", ['reason' => 'Serving under programme X'])
            ->assertCreated()
            ->assertJsonPath('data.status', OwnershipTransferStatus::Pending->value);

        $transferId = $create->json('data.id');
        $this->app['auth']->forgetGuards();

        // The current owner (MDA A) approves.
        $this->withToken($this->tokenFor('owner_admin'))
            ->postJson("/api/v1/ownership-transfers/{$transferId}/approve")
            ->assertOk()
            ->assertJsonPath('data.status', OwnershipTransferStatus::Approved->value);

        // Ownership actually moved to MDA B.
        $this->assertDatabaseHas('beneficiaries', [
            'id' => $this->benA->id,
            'owner_mda_id' => $this->mdaB->id,
        ]);

        // The transfer decision and the ownership change are both audited.
        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.ownership_transferred',
            'entity_type' => 'beneficiary',
            'entity_id' => $this->benA->id,
        ]);
    }

    public function test_non_owner_cannot_approve_transfer(): void
    {
        $transfer = OwnershipTransferRequest::create([
            'beneficiary_id' => $this->benA->id,
            'from_mda_id' => $this->mdaA->id,
            'to_mda_id' => $this->mdaB->id,
            'status' => OwnershipTransferStatus::Pending,
            'requested_by' => $this->users['nonowner_admin']->id,
        ]);

        // MDA B (the requester, not the owner) may not approve its own request.
        $this->withToken($this->tokenFor('nonowner_admin'))
            ->postJson("/api/v1/ownership-transfers/{$transfer->id}/approve")
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'FORBIDDEN');

        $this->assertDatabaseHas('ownership_transfer_requests', [
            'id' => $transfer->id,
            'status' => OwnershipTransferStatus::Pending->value,
        ]);
    }
}
