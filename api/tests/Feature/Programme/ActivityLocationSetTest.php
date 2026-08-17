<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\ActivityLocation;
use App\Domain\Programme\Models\Programme;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The activity location SET: many LGAs, many wards per LGA, whole-LGA rows, and the
 * per-LGA ward validation.
 */
class ActivityLocationSetTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    private Lga $dutse;

    private Lga $kiyawa;

    /** @var array<string, Ward> */
    private array $wards = [];

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->programme = Programme::factory()->create();

        $this->dutse = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        $this->kiyawa = Lga::factory()->forEnum(LgaEnum::Kiyawa)->create();

        foreach (['limawa', 'madobi'] as $code) {
            $this->wards['dutse_'.$code] = Ward::factory()->create([
                'lga_id' => $this->dutse->id, 'code' => $code, 'name' => ucfirst($code),
            ]);
        }
        foreach (['kwanda', 'garko'] as $code) {
            $this->wards['kiyawa_'.$code] = Ward::factory()->create([
                'lga_id' => $this->kiyawa->id, 'code' => $code, 'name' => ucfirst($code),
            ]);
        }
    }

    private function send(string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'programme_id' => $this->programme->id,
            'involves_beneficiaries' => false,
            'name' => 'Multi-area activity',
        ], $overrides);
    }

    // ------------------------------------------------ multi-LGA / multi-ward

    public function test_an_activity_can_declare_several_lgas_with_several_wards_each(): void
    {
        $response = $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [
                ['lga_id' => $this->dutse->id, 'ward_ids' => [$this->wards['dutse_limawa']->id, $this->wards['dutse_madobi']->id]],
                ['lga_id' => $this->kiyawa->id, 'ward_ids' => [$this->wards['kiyawa_kwanda']->id]],
            ],
        ]))->assertCreated();

        $activity = Activity::query()->firstOrFail();

        // 3 rows: two wards in Dutse, one in Kiyawa.
        $this->assertSame(3, $activity->locations()->count());
        $this->assertSame(2, $activity->locations()->where('lga_id', $this->dutse->id)->count());
        $this->assertSame(1, $activity->locations()->where('lga_id', $this->kiyawa->id)->count());

        // ...and it reads back grouped LGA → wards, ordered by name.
        $locations = $response->json('data.locations');
        $this->assertCount(2, $locations);
        $this->assertSame('Dutse', $locations[0]['lga_name']);
        $this->assertSame(['Limawa', 'Madobi'], array_column($locations[0]['wards'], 'ward_name'));
        $this->assertSame('Kiyawa', $locations[1]['lga_name']);
        $this->assertSame(['Kwanda'], array_column($locations[1]['wards'], 'ward_name'));
    }

    public function test_whole_lga_is_stored_as_a_single_null_ward_row(): void
    {
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [['lga_id' => $this->dutse->id, 'whole_lga' => true]],
        ]))
            ->assertCreated()
            ->assertJsonPath('data.locations.0.whole_lga', true)
            ->assertJsonPath('data.locations.0.wards', []);

        $this->assertDatabaseHas('activity_locations', [
            'lga_id' => $this->dutse->id,
            'ward_id' => null,
        ]);
        $this->assertSame(1, ActivityLocation::query()->count());
    }

    public function test_an_lga_with_no_wards_selected_means_the_whole_lga(): void
    {
        // Choosing an LGA and no wards says the same thing as ticking "whole LGA";
        // storing it two different ways would make the same declaration unequal to itself.
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [['lga_id' => $this->dutse->id, 'ward_ids' => []]],
        ]))
            ->assertCreated()
            ->assertJsonPath('data.locations.0.whole_lga', true);

        $this->assertDatabaseHas('activity_locations', ['lga_id' => $this->dutse->id, 'ward_id' => null]);
    }

    public function test_whole_lga_and_specific_wards_can_be_mixed_across_lgas(): void
    {
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [
                ['lga_id' => $this->dutse->id, 'whole_lga' => true],
                ['lga_id' => $this->kiyawa->id, 'ward_ids' => [$this->wards['kiyawa_garko']->id]],
            ],
        ]))->assertCreated();

        $activity = Activity::query()->firstOrFail();
        $this->assertNull($activity->locations()->where('lga_id', $this->dutse->id)->firstOrFail()->ward_id);
        $this->assertSame(
            $this->wards['kiyawa_garko']->id,
            $activity->locations()->where('lga_id', $this->kiyawa->id)->firstOrFail()->ward_id,
        );
    }

    public function test_an_activity_can_be_created_with_no_locations_at_all(): void
    {
        // Location is descriptive; an activity that has not decided where it runs yet is
        // a legitimate draft, not a validation error.
        $this->send('POST', '/api/v1/activities', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.locations', []);
    }

    // ------------------------------------------------ per-LGA ward validation

    public function test_a_ward_from_another_lga_is_rejected(): void
    {
        // The rule that matters: ward codes repeat across Jigawa, so a ward id filed
        // under the wrong LGA is a silent way to mis-target an activity.
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [
                ['lga_id' => $this->dutse->id, 'ward_ids' => [$this->wards['kiyawa_kwanda']->id]],
            ],
        ]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'locations.0.ward_ids.0'])
            ->assertJsonFragment(['message' => 'That ward does not belong to the selected LGA.']);

        $this->assertSame(0, Activity::query()->count());
    }

    public function test_the_offending_ward_is_identified_by_position(): void
    {
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [
                ['lga_id' => $this->kiyawa->id, 'ward_ids' => [$this->wards['kiyawa_kwanda']->id]],
                ['lga_id' => $this->dutse->id, 'ward_ids' => [$this->wards['dutse_limawa']->id, $this->wards['kiyawa_garko']->id]],
            ],
        ]))
            ->assertStatus(422)
            // Second entry, second ward — the form has to be able to mark the right chip.
            ->assertJsonFragment(['field' => 'locations.1.ward_ids.1']);
    }

    public function test_an_unknown_ward_or_lga_is_rejected(): void
    {
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [['lga_id' => $this->dutse->id, 'ward_ids' => ['01930000-0000-7000-8000-000000000000']]],
        ]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'locations.0.ward_ids.0']);

        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [['lga_id' => '01930000-0000-7000-8000-000000000000', 'whole_lga' => true]],
        ]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'locations.0.lga_id']);
    }

    public function test_the_same_lga_cannot_appear_twice(): void
    {
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [
                ['lga_id' => $this->dutse->id, 'ward_ids' => [$this->wards['dutse_limawa']->id]],
                ['lga_id' => $this->dutse->id, 'ward_ids' => [$this->wards['dutse_madobi']->id]],
            ],
        ]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'locations']);
    }

    public function test_whole_lga_and_wards_together_is_rejected(): void
    {
        // Contradictory: "everywhere in Dutse" and "only Limawa" are different claims.
        $this->send('POST', '/api/v1/activities', $this->payload([
            'locations' => [[
                'lga_id' => $this->dutse->id,
                'whole_lga' => true,
                'ward_ids' => [$this->wards['dutse_limawa']->id],
            ]],
        ]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'locations.0.whole_lga']);
    }

    // ------------------------------------------------ update semantics

    public function test_submitting_a_location_set_replaces_the_previous_one(): void
    {
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->create(['involves_beneficiaries' => false])
            ->refresh();

        $this->send('PATCH', "/api/v1/activities/{$activity->id}", [
            'locations' => [['lga_id' => $this->dutse->id, 'ward_ids' => [$this->wards['dutse_limawa']->id]]],
        ])->assertOk();

        $this->send('PATCH', "/api/v1/activities/{$activity->id}", [
            'locations' => [['lga_id' => $this->kiyawa->id, 'whole_lga' => true]],
        ])->assertOk();

        // Removing an LGA removes its wards with it.
        $this->assertSame(1, $activity->locations()->count());
        $this->assertSame($this->kiyawa->id, $activity->locations()->firstOrFail()->lga_id);
    }

    public function test_an_update_that_omits_locations_leaves_the_set_untouched(): void
    {
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inWards($this->dutse, [$this->wards['dutse_limawa'], $this->wards['dutse_madobi']])
            ->create(['involves_beneficiaries' => false]);

        // Editing the budget must not wipe an activity's declared coverage.
        $this->send('PATCH', "/api/v1/activities/{$activity->id}", ['budget_amount' => 999])->assertOk();

        $this->assertSame(2, $activity->locations()->count());
    }

    public function test_an_empty_location_set_clears_the_declaration(): void
    {
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inLga($this->dutse)
            ->create(['involves_beneficiaries' => false]);

        $this->send('PATCH', "/api/v1/activities/{$activity->id}", ['locations' => []])->assertOk();

        $this->assertSame(0, $activity->locations()->count());
    }

    public function test_deleting_an_activity_removes_its_locations(): void
    {
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inWards($this->dutse, [$this->wards['dutse_limawa']])
            ->create();

        $activity->forceDelete();

        $this->assertSame(0, ActivityLocation::query()->count());
    }

    // ------------------------------------------------ descriptive only

    public function test_no_beneficiary_location_check_is_performed(): void
    {
        // DESCRIPTIVE ONLY. The declared set is a plan; the uploaded people are the
        // fact. A beneficiary outside the declared LGAs must be accepted and flagged
        // nowhere — this test exists to fail loudly if anyone later adds that check.
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inWards($this->dutse, [$this->wards['dutse_limawa']])
            ->create();

        $beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'lga' => LgaEnum::Maigatari->value, // nowhere near the declared set
            'ward' => 'Somewhere Else',
        ]);

        $this->assertDatabaseHas('beneficiaries', ['id' => $beneficiary->id, 'lga' => 'maigatari']);
        $this->assertSame(1, $activity->locations()->count());

        // The activity's declaration is unchanged, and nothing rejected the beneficiary.
        $this->send('GET', "/api/v1/activities/{$activity->id}")
            ->assertOk()
            ->assertJsonPath('data.locations.0.lga_code', 'dutse');
    }

    // ------------------------------------------------ aggregation seam

    public function test_coverage_can_be_grouped_by_the_new_ids(): void
    {
        Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inWards($this->dutse, [$this->wards['dutse_limawa'], $this->wards['dutse_madobi']])
            ->create();
        Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inLga($this->kiyawa)
            ->create();

        // Real ids, so a coverage aggregation is a group-by rather than a string match.
        $byLga = ActivityLocation::query()
            ->selectRaw('lga_id, count(*) as c')
            ->groupBy('lga_id')
            ->pluck('c', 'lga_id');

        $this->assertSame(2, (int) $byLga[$this->dutse->id]);
        $this->assertSame(1, (int) $byLga[$this->kiyawa->id]);
    }

    public function test_the_declared_in_scope_matches_lgas_and_wards(): void
    {
        $inDutseWard = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inWards($this->dutse, [$this->wards['dutse_limawa']])->create();
        $wholeKiyawa = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->inLga($this->kiyawa)->create();

        $this->assertSame(
            [$inDutseWard->id],
            Activity::query()->declaredIn('dutse', null)->pluck('id')->all(),
        );

        // A whole-LGA declaration covers every ward in it, so a ward filter finds it.
        $this->assertSame(
            [$wholeKiyawa->id],
            Activity::query()->declaredIn(null, 'Kwanda')->pluck('id')->all(),
        );

        $this->assertSame(
            [$inDutseWard->id],
            Activity::query()->declaredIn(null, 'Limawa')->pluck('id')->all(),
        );
    }
}
