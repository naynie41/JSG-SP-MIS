<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServeRequest;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ad-hoc "serve many" duplicate search + request-to-serve (PRD FR-DUP-04/05,
 * FR-OWN-03): the same engine runs outside the import flow, returns ranked
 * reveal-only candidates across MDAs, and a request-to-serve can be raised from a
 * result without transferring ownership. Reuses the 3.5 serve seam + auditing.
 */
class ServeSearchTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA; // the searching MDA

    private Mda $mdaB; // owner of the existing records

    private Beneficiary $exact;    // matched by NIN → exact band

    private Beneficiary $probable; // matched fuzzily → probable band

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->exact = Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaB->id, 'nin' => '55511122233',
            'first_name' => 'Zainab', 'last_name' => 'Ibrahim', 'date_of_birth' => '1975-05-05',
        ]);
        $this->probable = Beneficiary::factory()->withoutNin()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaB->id,
            'first_name' => 'Sadiq', 'last_name' => 'Kano', 'date_of_birth' => '1990-01-01',
            'phone' => '08070000000', 'lga' => 'kano', 'ward' => 'Ward 1',
        ]);
    }

    private function signIn(Mda $mda, RoleKey $role): User
    {
        $user = User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
        $this->withToken($user->createToken('test')->plainTextToken);

        return $user;
    }

    /** @return array<string, string> */
    private function crossQuery(): array
    {
        // Hits the NIN block (→ $exact) and the name+DOB / phone blocks (→ $probable).
        return [
            'nin' => '55511122233',
            'first_name' => 'Sadiq',
            'last_name' => 'Kano',
            'date_of_birth' => '1990-01-01',
            'phone' => '08070000000',
            'lga' => 'kano',
            'ward' => 'Ward 1',
        ];
    }

    public function test_search_returns_ranked_reveal_only_candidates_across_mdas(): void
    {
        $this->signIn($this->mdaA, RoleKey::MdaOfficer);

        $response = $this->getJson('/api/v1/beneficiaries/search?'.http_build_query($this->crossQuery()))
            ->assertOk();

        // Ranked: the NIN-exact match outranks the fuzzy/probable one.
        $response->assertJsonPath('data.candidates.0.band', 'exact')
            ->assertJsonPath('data.candidates.0.beneficiary.id', $this->exact->id)
            ->assertJsonPath('data.candidates.1.band', 'probable')
            ->assertJsonPath('data.candidates.1.beneficiary.id', $this->probable->id);

        $this->assertContains('nin', $response->json('data.candidates.0.matched_fields'));

        // Cross-MDA visibility: the owner MDA is revealed on another MDA's record.
        $response->assertJsonPath('data.candidates.0.beneficiary.owner_mda.id', $this->mdaB->id)
            ->assertJsonPath('data.candidates.0.beneficiary.owner_mda.name', 'MDA B');

        // Reveal-only: no protected identifiers leak.
        $reveal = $response->json('data.candidates.0.beneficiary');
        foreach (['nin', 'bvn', 'phone', 'date_of_birth', 'address', 'middle_name'] as $hidden) {
            $this->assertArrayNotHasKey($hidden, $reveal);
        }
        $this->assertArrayHasKey('full_name', $reveal);
        $this->assertArrayHasKey('programmes', $reveal);
    }

    public function test_search_requires_a_blocking_field(): void
    {
        $this->signIn($this->mdaA, RoleKey::MdaOfficer);

        // first_name alone yields no blocking key — rejected before any scan.
        $this->getJson('/api/v1/beneficiaries/search?'.http_build_query(['first_name' => 'Sadiq']))
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR');
    }

    public function test_search_is_audited_without_leaking_values(): void
    {
        $this->signIn($this->mdaA, RoleKey::MdaOfficer);

        $this->getJson('/api/v1/beneficiaries/search?'.http_build_query($this->crossQuery()))->assertOk();

        $entry = AuditLog::query()->where('action', 'beneficiary.match_search')->firstOrFail();
        $this->assertContains('nin', $entry->after['by']);
        $this->assertSame(2, $entry->after['matches']);
        // The raw identifier value must never appear in the audit payload.
        $this->assertStringNotContainsString('55511122233', json_encode($entry->after));
    }

    public function test_search_requires_the_lookup_permission(): void
    {
        // Development Partner has beneficiary.view but not beneficiary-lookup.view.
        $this->signIn($this->mdaA, RoleKey::DevelopmentPartner);

        $this->getJson('/api/v1/beneficiaries/search?'.http_build_query($this->crossQuery()))
            ->assertStatus(403);
    }

    public function test_request_to_serve_can_be_raised_from_a_result_without_changing_ownership(): void
    {
        $user = $this->signIn($this->mdaA, RoleKey::MdaOfficer);

        $this->postJson('/api/v1/serve-requests', [
            'beneficiary_id' => $this->exact->id,
            'reason' => 'Enrolling into our programme',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.from_mda_id', $this->mdaA->id)
            ->assertJsonPath('data.to_mda_id', $this->mdaB->id);

        $serve = ServeRequest::query()->firstOrFail();
        $this->assertSame($this->exact->id, $serve->beneficiary_id);
        $this->assertSame($user->id, $serve->requested_by);

        // Ownership is untouched, and the raise is audited (Auditable → created).
        $this->assertSame($this->mdaB->id, $this->exact->fresh()->owner_mda_id);
        $this->assertDatabaseHas('audit_log', [
            'action' => 'serve_request.created',
            'entity_id' => $serve->id,
            'actor_id' => $user->id,
        ]);
    }

    public function test_cannot_request_to_serve_a_beneficiary_you_already_own(): void
    {
        // An officer of the OWNING MDA tries to serve its own record.
        $this->signIn($this->mdaB, RoleKey::MdaOfficer);

        $this->postJson('/api/v1/serve-requests', ['beneficiary_id' => $this->exact->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'SERVE_REQUEST_INVALID');

        $this->assertSame(0, ServeRequest::query()->count());
    }

    public function test_raising_a_request_requires_the_create_permission(): void
    {
        // M&E Officer can search (lookup.view) but cannot raise (no beneficiary.create).
        $this->signIn($this->mdaA, RoleKey::MneOfficer);

        $this->postJson('/api/v1/serve-requests', ['beneficiary_id' => $this->exact->id])
            ->assertStatus(403);
    }
}
