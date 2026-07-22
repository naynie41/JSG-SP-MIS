<?php

declare(strict_types=1);

namespace Tests\Feature\Privacy;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Right-of-access export — a Data Subject Access Request (NFR-PRV-01). The owner MDA
 * (data controller) or oversight can export the subject's FULL record + benefit
 * history, unmasked; another MDA cannot (404, no leak); a user without the distinct
 * permission cannot (403). Every fulfilment is audited.
 */
class RightOfAccessTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['ownerAdmin'] = $this->user($this->mdaA, RoleKey::MdaAdmin);   // controller + access_request
        $this->users['otherAdmin'] = $this->user($this->mdaB, RoleKey::MdaAdmin);   // has perm, but not owner
        $this->users['officer'] = $this->user($this->mdaA, RoleKey::MdaOfficer);    // no access_request perm
        $this->users['sysadmin'] = $this->user($this->mdaA, RoleKey::SystemAdministrator);

        $this->beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'first_name' => 'Amina',
            'last_name' => 'Bello',
            'nin' => '22200000011',
        ]);
        $programme = Programme::factory()->individual()->create();
        Enrollment::factory()->create(['programme_id' => $programme->id, 'mda_id' => $this->mdaA->id, 'beneficiary_id' => $this->beneficiary->id]);
        Benefit::factory()->create(['beneficiary_id' => $this->beneficiary->id, 'programme_id' => $programme->id, 'mda_id' => $this->mdaA->id, 'monetary_value' => 750_000]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function download(string $key): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)
            ->get("/api/v1/beneficiaries/{$this->beneficiary->id}/access-request");
        $this->app['auth']->forgetGuards();

        return $response;
    }

    public function test_owner_mda_exports_the_full_unmasked_record_with_benefit_history(): void
    {
        $response = $this->download('ownerAdmin')->assertOk();
        $json = $response->streamedContent();

        // Full unmasked identity + benefit history are present.
        $this->assertStringContainsString('22200000011', $json); // NIN unmasked (subject's own data)
        $this->assertStringContainsString('Amina', $json);
        $this->assertStringContainsString('750000', $json);       // benefit monetary value

        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.access_request',
            'entity_id' => $this->beneficiary->id,
            'actor_id' => $this->users['ownerAdmin']->id,
        ]);
    }

    public function test_oversight_may_also_fulfil_an_access_request(): void
    {
        $this->download('sysadmin')->assertOk();
    }

    public function test_a_non_owner_mda_cannot_export_another_mdas_subject(): void
    {
        // Has the permission (MDA Admin) but is neither controller nor oversight → 404.
        $this->download('otherAdmin')->assertStatus(404);
    }

    public function test_a_user_without_the_access_request_permission_is_denied(): void
    {
        $this->download('officer')->assertStatus(403);
    }
}
