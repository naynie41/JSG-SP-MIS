<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

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
 * The FR-DUP-04 match reveal now shows real programmes + benefits-received for a
 * matched beneficiary (PRD FR-DUP-04), within visibility rules: programme names,
 * benefit types/dates and the delivering MDA are visible to any reveal viewer,
 * while exact monetary values are visible only to the owner MDA or oversight.
 */
class RevealPopulationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $ownerMda;

    private Beneficiary $beneficiary;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->ownerMda = Mda::factory()->create(['name' => 'Owner MDA']);
        $otherMda = Mda::factory()->create(['name' => 'Other MDA']);
        $this->users['owner'] = $this->user($this->ownerMda, RoleKey::MdaOfficer);
        $this->users['other'] = $this->user($otherMda, RoleKey::MdaOfficer);
        $this->users['oversight'] = $this->user($otherMda, RoleKey::Executive);

        $this->beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->ownerMda->id, 'nin' => '22200033344']);
        $programme = Programme::factory()->individual()->create(['name' => 'Cash Transfer']);
        Enrollment::factory()->create(['programme_id' => $programme->id, 'mda_id' => $this->ownerMda->id, 'beneficiary_id' => $this->beneficiary->id]);
        Benefit::factory()->create(['programme_id' => $programme->id, 'mda_id' => $this->ownerMda->id, 'beneficiary_id' => $this->beneficiary->id, 'benefit_type' => 'cash', 'monetary_value' => 500_000]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function lookup(string $key): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)
            ->getJson('/api/v1/beneficiaries/lookup?nin=22200033344');
        $this->app['auth']->forgetGuards();

        return $response;
    }

    public function test_reveal_shows_programmes_and_benefits_for_all_viewers(): void
    {
        $response = $this->lookup('other')->assertOk();

        $response->assertJsonPath('data.matches.0.programmes.0.name', 'Cash Transfer')
            ->assertJsonPath('data.matches.0.benefits.summary.count', 1)
            ->assertJsonPath('data.matches.0.benefits.items.0.benefit_type', 'cash')
            ->assertJsonPath('data.matches.0.benefits.items.0.delivering_mda.name', 'Owner MDA');
    }

    public function test_monetary_value_is_masked_from_non_owner_mdas(): void
    {
        $this->lookup('other')
            ->assertOk()
            ->assertJsonPath('data.matches.0.benefits.items.0.monetary_value', null)
            ->assertJsonPath('data.matches.0.benefits.summary.total_value', null);
    }

    public function test_owner_and_oversight_see_monetary_value(): void
    {
        $this->lookup('owner')
            ->assertOk()
            ->assertJsonPath('data.matches.0.benefits.items.0.monetary_value', 500_000)
            ->assertJsonPath('data.matches.0.benefits.summary.total_value', 500_000);

        $this->lookup('oversight')
            ->assertOk()
            ->assertJsonPath('data.matches.0.benefits.summary.total_value', 500_000);
    }
}
