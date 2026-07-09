<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Auto-route / programme matching (PRD FR-OWN-04): suggest programmes whose type +
 * eligibility match a beneficiary's need; assignment is an explicit, audited
 * enrolment — never silent, never transferring ownership.
 */
class AutoRouteTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA; // owns the beneficiary

    private Mda $mdaB; // owns the matching programme

    private Beneficiary $beneficiary;

    private Programme $cashProgramme;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);

        // A matching, eligible catalog programme…
        $this->cashProgramme = Programme::factory()->individual()->create([
            'name' => 'Cash Transfer', 'status' => 'active',
            'eligibility' => [['attribute' => 'lga', 'value' => 'dutse']],
        ]);
        // …and a non-matching one (wrong LGA + unrelated need).
        Programme::factory()->individual()->create([
            'name' => 'Housing Support', 'status' => 'active',
            'eligibility' => [['attribute' => 'lga', 'value' => 'gumel']],
        ]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    public function test_suggestions_match_type_and_eligibility_for_the_need(): void
    {
        $response = $this->send('officerA', 'GET', "/api/v1/beneficiaries/{$this->beneficiary->id}/routing-suggestions?need=cash")->assertOk();

        $suggestions = collect($response->json('data.suggestions'));
        $cash = $suggestions->firstWhere('programme_id', $this->cashProgramme->id);
        $this->assertNotNull($cash);
        $this->assertTrue($cash['eligible']);
        // Programmes are a global catalog (§10) — suggestions carry the catalog entry,
        // not an owning MDA.
        $this->assertSame('Cash Transfer', $cash['name']);

        // The unrelated-need / wrong-LGA programme is not suggested for "cash".
        $this->assertNull($suggestions->firstWhere('name', 'Housing Support'));

        // Read-only: suggesting assigns nothing.
        $this->assertSame(0, Enrollment::query()->withoutGlobalScopes()->count());
    }

    public function test_assignment_requires_serve_access_and_is_audited(): void
    {
        // Without serve access, MDA B cannot assign MDA A's beneficiary.
        $this->send('officerB', 'POST', "/api/v1/beneficiaries/{$this->beneficiary->id}/routing-assignments", ['programme_id' => $this->cashProgramme->id, 'need' => 'cash'])
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'SERVICE_REQUEST_REQUIRED');

        // Grant serve access via an accepted Service Request's read-access grant.
        $serviceRequest = ServiceRequest::create(['beneficiary_id' => $this->beneficiary->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => ServiceRequestStatus::Accepted]);
        BeneficiaryServiceGrant::create(['beneficiary_id' => $this->beneficiary->id, 'mda_id' => $this->mdaB->id, 'service_request_id' => $serviceRequest->id, 'granted_at' => now()]);

        $this->send('officerB', 'POST', "/api/v1/beneficiaries/{$this->beneficiary->id}/routing-assignments", ['programme_id' => $this->cashProgramme->id, 'need' => 'cash'])
            ->assertCreated()
            ->assertJsonPath('data.beneficiary_id', $this->beneficiary->id)
            ->assertJsonPath('data.mda_id', $this->mdaB->id);

        // Ownership unchanged; the routing decision is audited.
        $this->assertSame($this->mdaA->id, $this->beneficiary->fresh()->owner_mda_id);
        $this->assertSame(1, Enrollment::query()->withoutGlobalScopes()->count());
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.routed', 'entity_id' => $this->beneficiary->id]);
    }
}
