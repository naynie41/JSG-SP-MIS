<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Owner-scoped beneficiary browse + owner-only correction (PRD FR-REG-04,
 * FR-OWN-02): list/filter/search, scoping, owner-only edit + soft delete, audit.
 * Ingestion is source-only, so there is no manual create path (see
 * NoManualCreateRouteTest); records here are set up via factories.
 */
class BeneficiaryRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        // Created up front so an Auditable model is never created between
        // sub-requests with a stale Auth::user (see RbacTest note).
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['partnerA'] = $this->user($this->mdaA, RoleKey::DevelopmentPartner);
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

    public function test_list_is_owner_scoped(): void
    {
        Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id]);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);

        $response = $this->withToken($this->tokenFor('officerA'))
            ->getJson('/api/v1/beneficiaries')
            ->assertOk()
            ->assertJsonPath('meta.pagination.total', 2);

        foreach ($response->json('data') as $row) {
            $this->assertSame($this->mdaA->id, $row['owner_mda_id']);
        }
    }

    public function test_list_can_be_filtered_by_lga_status_and_search(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'last_name' => 'Findme']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'gumel']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'status' => BeneficiaryStatus::Suspended]);

        // Filter by LGA.
        $this->withToken($this->tokenFor('officerA'))
            ->getJson('/api/v1/beneficiaries?filter[lga]=dutse')
            ->assertOk()
            ->assertJsonPath('meta.pagination.total', 2);

        $this->app['auth']->forgetGuards();

        // Filter by LGA + status.
        $this->withToken($this->tokenFor('officerA'))
            ->getJson('/api/v1/beneficiaries?filter[lga]=dutse&filter[status]=active')
            ->assertOk()
            ->assertJsonPath('meta.pagination.total', 1);

        $this->app['auth']->forgetGuards();

        // Search by name.
        $this->withToken($this->tokenFor('officerA'))
            ->getJson('/api/v1/beneficiaries?search=Findme')
            ->assertOk()
            ->assertJsonPath('meta.pagination.total', 1);
    }

    public function test_show_returns_owned_record_and_404s_out_of_scope(): void
    {
        $benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $benB = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);

        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/{$benA->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $benA->id);

        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/{$benB->id}")
            ->assertStatus(404);
    }

    public function test_owner_can_update_but_non_owner_cannot(): void
    {
        $benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'last_name' => 'Original']);

        $this->withToken($this->tokenFor('officerA'))
            ->patchJson("/api/v1/beneficiaries/{$benA->id}", ['last_name' => 'Updated'])
            ->assertOk()
            ->assertJsonPath('data.last_name', 'Updated');

        $this->app['auth']->forgetGuards();

        // MDA B officer holds beneficiary.edit but does not own the record → 403.
        $this->withToken($this->tokenFor('officerB'))
            ->patchJson("/api/v1/beneficiaries/{$benA->id}", ['last_name' => 'Hacked'])
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'FORBIDDEN');

        $this->assertDatabaseHas('beneficiaries', ['id' => $benA->id, 'last_name' => 'Updated']);
    }

    public function test_owner_can_soft_delete_but_non_owner_cannot(): void
    {
        $benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // Non-owner denied.
        $this->withToken($this->tokenFor('officerB'))
            ->deleteJson("/api/v1/beneficiaries/{$benA->id}")
            ->assertStatus(403);

        $this->app['auth']->forgetGuards();

        // Owner soft-deletes, and it is audited.
        $this->withToken($this->tokenFor('officerA'))
            ->deleteJson("/api/v1/beneficiaries/{$benA->id}")
            ->assertOk();

        $this->assertSoftDeleted('beneficiaries', ['id' => $benA->id]);
        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.deleted',
            'entity_type' => 'beneficiary',
            'entity_id' => $benA->id,
        ]);
    }
}
