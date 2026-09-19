<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\FuzzyDuplicateFinder;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Support\DashboardFilter;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * A development partner that BOTH funds and implements (revises PRD §11).
 *
 * The organisation exists twice, on purpose, as two accounts:
 *
 *  - a DELIVERY organisation — an `mdas` row of type `partner` whose staff own
 *    programmes, activities and beneficiaries exactly as a ministry's do;
 *  - a FUNDER — the Development Partner account, read-only and funded-scope, which
 *    `mdas.funder_user_id` ties back to the organisation.
 *
 * The point of modelling delivery as an MDA row is that nothing had to be taught
 * about partners: ownership, scoping, oversight visibility and duplicate detection
 * all key off `owner_mda_id`. These tests hold that line — a partner's records obey
 * every rule a ministry's records obey, in both directions.
 */
class ImplementingPartnerTest extends TestCase
{
    use RefreshDatabase;

    private Mda $partnerOrg;

    private Mda $ministry;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        // The duplicate check reads the ACTIVE matching config; without it the finder
        // cannot run at all.
        $this->seed(MatchingConfigSeeder::class);

        $this->ministry = Mda::factory()->create(['name' => 'Ministry of Health', 'type' => MdaType::Ministry]);

        // The funder account — state-level, no MDA of its own, as today.
        $this->users['funder'] = $this->user(null, RoleKey::DevelopmentPartner);

        // The same organisation as a DELIVERY body, linked to that funder account.
        $this->partnerOrg = Mda::factory()
            ->partner($this->users['funder']->id)
            ->create(['name' => 'Save the Children']);

        // Its implementing staff: an ordinary MDA Admin whose MDA happens to be the
        // partner organisation. No new role — the permission set is already right.
        $this->users['partnerStaff'] = $this->user($this->partnerOrg, RoleKey::MdaAdmin);
        $this->users['ministryStaff'] = $this->user($this->ministry, RoleKey::MdaAdmin);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
    }

    /* --------------------------------------------------------------- ownership */

    public function test_a_partner_organisation_owns_records_exactly_as_a_ministry_does(): void
    {
        $theirs = Beneficiary::factory()->create(['owner_mda_id' => $this->partnerOrg->id]);

        // Its own staff read it…
        $this->send('partnerStaff', 'GET', "/api/v1/beneficiaries/{$theirs->id}")->assertOk();

        // …and the organisation is linked back to the account that funds through it.
        $this->assertSame($this->users['funder']->id, $this->partnerOrg->fresh()->funder_user_id);
        $this->assertFalse($this->partnerOrg->isGovernment());
        $this->assertTrue($this->ministry->isGovernment());
    }

    public function test_another_agency_cannot_see_a_partners_beneficiaries(): void
    {
        $theirs = Beneficiary::factory()->create(['owner_mda_id' => $this->partnerOrg->id]);

        // MdaScope decides this, not a controller: a partner owner is just an owner.
        $this->send('ministryStaff', 'GET', "/api/v1/beneficiaries/{$theirs->id}")->assertStatus(404);
    }

    public function test_oversight_sees_partner_delivered_records(): void
    {
        // Stakeholder decision 2026-09-20: a partner's beneficiary data IS visible to
        // government oversight. It needs no code — `cross-mda.view` bypasses the scope.
        $theirs = Beneficiary::factory()->create(['owner_mda_id' => $this->partnerOrg->id]);

        $this->send('exec', 'GET', "/api/v1/beneficiaries/{$theirs->id}")->assertOk();
    }

    public function test_the_funder_account_still_cannot_read_beneficiaries(): void
    {
        // §11 survives the two-account model: the funding ROLE never reaches PII,
        // even for the organisation it funds through.
        $theirs = Beneficiary::factory()->create(['owner_mda_id' => $this->partnerOrg->id]);

        $this->send('funder', 'GET', "/api/v1/beneficiaries/{$theirs->id}")->assertStatus(404);
    }

    /* ------------------------------------------------------------- duplicates */

    public function test_a_partners_record_surfaces_in_cross_agency_duplicate_matching(): void
    {
        // Stakeholder decision 2026-09-20: partner-delivered people join state-wide
        // duplicate detection. Otherwise the registry has a hole the size of an NGO.
        $person = Beneficiary::factory()->create([
            'owner_mda_id' => $this->partnerOrg->id,
            'first_name' => 'Amina', 'last_name' => 'Yusuf',
            'date_of_birth' => '1990-01-01', 'gender' => 'female',
            'phone' => '08030000001',
        ]);

        // The same screening every import and lookup runs. It gathers candidates
        // across ALL owners on purpose, so a partner's record is screened against
        // exactly like a ministry's — the behaviour Q2 asked for, inherited rather
        // than added.
        $matches = app(FuzzyDuplicateFinder::class)->find([
            'first_name' => 'Amina', 'last_name' => 'Yusuf',
            'date_of_birth' => '1990-01-01', 'gender' => 'female',
            'phone' => '08030000001',
        ]);

        $this->assertNotEmpty($matches, 'a partner-owned record must be screened against');
        $this->assertContains($person->id, array_map(
            static fn (MatchResult $m): ?string => $m->reference,
            $matches,
        ));
    }

    /* ---------------------------------------------------------------- funding */

    public function test_government_cannot_fund_a_partner_organisations_activity(): void
    {
        // Stakeholder decision 2026-09-20 (Q4): no MDA→partner funding.
        $programme = Programme::factory()->individual()->create(['status' => 'active']);

        $this->send('partnerStaff', 'POST', '/api/v1/activities', [
            'programme_id' => $programme->id,
            'name' => 'Nutrition round',
            'involves_beneficiaries' => false,
            'funding_type' => 'government',
        ])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'funding_type']);

        $this->send('partnerStaff', 'POST', '/api/v1/activities', [
            'programme_id' => $programme->id,
            'name' => 'Nutrition round',
            'involves_beneficiaries' => false,
            'funding_type' => 'partner',
            'funding_partner_id' => $this->users['funder']->id,
            'co_funded_by_government' => true,
        ])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'co_funded_by_government']);
    }

    public function test_a_ministry_may_still_be_government_funded(): void
    {
        $programme = Programme::factory()->individual()->create(['status' => 'active']);

        $this->send('ministryStaff', 'POST', '/api/v1/activities', [
            'programme_id' => $programme->id,
            'name' => 'Cash round',
            'involves_beneficiaries' => false,
            'funding_type' => 'government',
        ])->assertCreated();
    }

    public function test_a_partner_funds_its_own_activity_and_it_is_counted_once(): void
    {
        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        $activity = Activity::factory()->forProgramme($programme, $this->partnerOrg)->create([
            'funding_type' => 'partner',
            'funding_partner_id' => $this->users['funder']->id,
        ]);
        $person = Beneficiary::factory()->create(['owner_mda_id' => $this->partnerOrg->id]);
        Benefit::factory()->create([
            'beneficiary_id' => $person->id, 'programme_id' => $programme->id,
            'mda_id' => $this->partnerOrg->id, 'activity_id' => $activity->id,
            'monetary_value' => 250_000, 'status' => 'verified',
        ]);

        // It is their delivery AND their funding, so it shows in both views — but the
        // state headline must still count the person and the value exactly once.
        $state = app(DashboardService::class)->forUser($this->users['exec'], DashboardFilter::none())['metrics'];
        $this->assertSame(1, $state['population']['net_unique_served']);
        $this->assertSame(250_000, $state['benefits']['disbursed']['total_value']);

        $byAgency = collect($state['mda_delivery'])->firstWhere('mda_id', $this->partnerOrg->id);
        $this->assertNotNull($byAgency);
        $this->assertSame('partner', $byAgency['kind']);
        $this->assertSame(250_000, $byAgency['delivered_value']);

        $funded = app(DashboardService::class)->forUser($this->users['funder'], DashboardFilter::none())['metrics'];
        $this->assertSame(250_000, $funded['partner_funding']['delivered_value']);
    }

    public function test_delivery_by_agency_tells_government_and_partner_apart(): void
    {
        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        foreach ([$this->ministry, $this->partnerOrg] as $owner) {
            $activity = Activity::factory()->forProgramme($programme, $owner)->create();
            $person = Beneficiary::factory()->create(['owner_mda_id' => $owner->id]);
            Benefit::factory()->create([
                'beneficiary_id' => $person->id, 'programme_id' => $programme->id,
                'mda_id' => $owner->id, 'activity_id' => $activity->id,
                'monetary_value' => 100_000, 'status' => 'verified',
            ]);
        }

        $rows = app(DashboardService::class)->forUser($this->users['exec'], DashboardFilter::none())['metrics']['mda_delivery'];
        $kinds = collect($rows)->pluck('kind', 'mda')->all();

        $this->assertSame('government', $kinds['Ministry of Health']);
        $this->assertSame('partner', $kinds['Save the Children']);
    }

    /* ---------------------------------------------------------------- helpers */

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** @param array<string, mixed> $body */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }
}
