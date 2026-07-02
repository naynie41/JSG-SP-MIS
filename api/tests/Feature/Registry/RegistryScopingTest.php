<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegistryScopingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Beneficiary $benA;

    private Beneficiary $benB;

    private Household $householdA;

    private Household $householdB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->benA = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->benB = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);
        $this->householdA = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->householdB = Household::factory()->create(['owner_mda_id' => $this->mdaB->id]);
    }

    private function userInMdaA(RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $this->mdaA->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    public function test_beneficiary_default_queries_are_owner_scoped(): void
    {
        Sanctum::actingAs($this->userInMdaA(RoleKey::MdaOfficer));

        $this->assertSame([$this->mdaA->id], Beneficiary::query()->pluck('owner_mda_id')->unique()->values()->all());
        $this->assertNotNull(Beneficiary::find($this->benA->id));
        $this->assertNull(Beneficiary::find($this->benB->id));
    }

    public function test_beneficiary_route_model_binding_404s_out_of_scope(): void
    {
        Sanctum::actingAs($this->userInMdaA(RoleKey::MdaOfficer));

        $model = new Beneficiary;
        // Implicit route-model binding resolves through the scoped query.
        $this->assertNull($model->resolveRouteBinding($this->benB->id));
        $this->assertInstanceOf(Beneficiary::class, $model->resolveRouteBinding($this->benA->id));
    }

    public function test_household_default_queries_and_binding_are_owner_scoped(): void
    {
        Sanctum::actingAs($this->userInMdaA(RoleKey::MdaOfficer));

        $this->assertSame([$this->mdaA->id], Household::query()->pluck('owner_mda_id')->unique()->values()->all());
        $this->assertNull(Household::find($this->householdB->id));
        $this->assertNull((new Household)->resolveRouteBinding($this->householdB->id));
        $this->assertInstanceOf(Household::class, (new Household)->resolveRouteBinding($this->householdA->id));
    }

    public function test_cross_mda_view_role_sees_all_mdas(): void
    {
        // SP Coordination holds cross-mda.view → scope is bypassed.
        Sanctum::actingAs($this->userInMdaA(RoleKey::SpCoordination));

        $this->assertSame(2, Beneficiary::query()->count());
        $this->assertNotNull(Beneficiary::find($this->benB->id));
        $this->assertNotNull((new Beneficiary)->resolveRouteBinding($this->benB->id));
    }

    public function test_write_path_is_audited(): void
    {
        Sanctum::actingAs($this->userInMdaA(RoleKey::MdaOfficer));

        $this->benA->update(['last_name' => 'Updated']);

        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.updated',
            'entity_type' => 'beneficiary',
            'entity_id' => $this->benA->id,
        ]);
    }
}
