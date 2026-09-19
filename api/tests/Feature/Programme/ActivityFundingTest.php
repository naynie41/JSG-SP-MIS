<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Enums\FundingType;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Reporting\Services\DashboardMetricsService;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * How an activity is funded: government, a social protection partner (optionally
 * co-funded with government), or individuals.
 *
 * The partner link is the one with consequences — it is what brings the activity into
 * that partner's view — so it is valid only on a partner activity, on every path that
 * writes an activity, and the picker that offers partners gives out names and nothing else.
 */
class ActivityFundingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    private User $officer;

    private User $partner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->officer = $this->user($this->mda, RoleKey::MdaAdmin, ['name' => 'Health Officer']);
        $this->partner = $this->user(null, RoleKey::DevelopmentPartner, ['name' => 'UNICEF Nigeria', 'email' => 'unicef.partner@example.test']);
        $this->programme = Programme::factory()->create();
    }

    /* ------------------------------------------------------------ direct create */

    public function test_a_government_funded_activity_carries_no_partner(): void
    {
        $response = $this->send($this->officer, 'POST', '/api/v1/activities', $this->activity([
            'funding_type' => 'government',
        ]))->assertSuccessful();

        $response->assertJsonPath('data.funding_type', 'government')
            ->assertJsonPath('data.funding_partner', null)
            ->assertJsonPath('data.co_funded_by_government', false);
    }

    public function test_a_partner_activity_links_the_partner_and_enters_their_view(): void
    {
        $response = $this->send($this->officer, 'POST', '/api/v1/activities', $this->activity([
            'funding_type' => 'partner',
            'funding_partner_id' => $this->partner->id,
            'co_funded_by_government' => true,
        ]))->assertSuccessful();

        $response->assertJsonPath('data.funding_partner.name', 'UNICEF Nigeria')
            ->assertJsonPath('data.co_funded_by_government', true);
        $this->assertStringNotContainsString('unicef.partner@example.test', (string) $response->getContent());

        $scope = app(DashboardScopeResolver::class)->forUser($this->partner);
        $this->assertContains($this->programme->id, $scope->programmeIds ?? []);
    }

    public function test_a_partner_activity_must_name_its_partner(): void
    {
        $response = $this->send($this->officer, 'POST', '/api/v1/activities', $this->activity([
            'funding_type' => 'partner',
        ]))->assertStatus(422);

        $this->assertStringContainsString('Choose the partner funding this activity.', (string) $response->getContent());
    }

    public function test_only_a_partner_activity_can_carry_a_partner_or_co_funding(): void
    {
        $withPartner = $this->send($this->officer, 'POST', '/api/v1/activities', $this->activity([
            'funding_type' => 'government',
            'funding_partner_id' => $this->partner->id,
        ]))->assertStatus(422);
        $this->assertStringContainsString('Only an activity funded by a social protection partner', (string) $withPartner->getContent());

        $coFunded = $this->send($this->officer, 'POST', '/api/v1/activities', $this->activity([
            'funding_type' => 'individual',
            'co_funded_by_government' => true,
        ]))->assertStatus(422);
        $this->assertStringContainsString('applies only to an activity funded by a partner', (string) $coFunded->getContent());

        $this->assertSame(0, Activity::query()->count());
    }

    public function test_the_partner_must_be_a_development_partner_account(): void
    {
        $response = $this->send($this->officer, 'POST', '/api/v1/activities', $this->activity([
            'funding_type' => 'partner',
            'funding_partner_id' => $this->officer->id,
        ]))->assertStatus(422);

        $this->assertStringContainsString('must be a Development Partner', (string) $response->getContent());
    }

    /* --------------------------------------------------------------------- edit */

    public function test_an_edit_cannot_change_the_partner_without_stating_the_funding_type(): void
    {
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)->create();

        $this->send($this->officer, 'PATCH', "/api/v1/activities/{$activity->id}", [
            'funding_partner_id' => $this->partner->id,
        ])->assertStatus(422);

        $this->assertNull($activity->fresh()->funding_partner_id);
    }

    public function test_an_edit_can_link_and_then_unlink_a_partner(): void
    {
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)->create();

        $this->send($this->officer, 'PATCH', "/api/v1/activities/{$activity->id}", [
            'funding_type' => 'partner',
            'funding_partner_id' => $this->partner->id,
            'co_funded_by_government' => false,
        ])->assertSuccessful();

        $this->assertSame(FundingType::Partner, $activity->fresh()->funding_type);
        $this->assertSame($this->partner->id, $activity->fresh()->funding_partner_id);

        $this->send($this->officer, 'PATCH', "/api/v1/activities/{$activity->id}", [
            'funding_type' => 'government',
            'funding_partner_id' => null,
            'co_funded_by_government' => false,
        ])->assertSuccessful();

        $this->assertNull($activity->fresh()->funding_partner_id);
        $scope = app(DashboardScopeResolver::class)->forUser($this->partner);
        $this->assertNotContains($this->programme->id, $scope->programmeIds ?? []);
    }

    /* ----------------------------------------------------------- upload wizard */

    public function test_the_upload_wizard_applies_the_same_rules_and_keeps_the_funding(): void
    {
        Storage::fake('local');

        $refused = $this->upload(['funding_type' => 'partner']);
        $refused->assertStatus(422);
        $this->assertStringContainsString('Choose the partner funding this activity.', (string) $refused->getContent());

        $batchId = $this->upload([
            'funding_type' => 'partner',
            'funding_partner_id' => $this->partner->id,
            'co_funded_by_government' => '1',
        ])->assertCreated()->json('data.id');

        $draft = ImportBatch::query()->withoutGlobalScopes()->findOrFail($batchId)->draft_activity;
        $this->assertSame('partner', $draft['funding_type'] ?? null);
        $this->assertSame($this->partner->id, $draft['funding_partner_id'] ?? null);
        $this->assertTrue((bool) ($draft['co_funded_by_government'] ?? false));
    }

    /* ----------------------------------------------------------- partner picker */

    public function test_the_picker_lists_active_partner_accounts_by_name_only(): void
    {
        $this->user(null, RoleKey::DevelopmentPartner, ['name' => 'World Bank']);
        $this->user(null, RoleKey::DevelopmentPartner, ['name' => 'Former Donor', 'status' => UserStatus::Suspended->value]);

        $response = $this->send($this->officer, 'GET', '/api/v1/activities/funding-partners')->assertOk();

        $this->assertSame(['UNICEF Nigeria', 'World Bank'], array_column($response->json('data.partners'), 'name'));
        $this->assertSame(['id', 'name'], array_keys($response->json('data.partners.0')));
        $this->assertStringNotContainsString('unicef.partner@example.test', (string) $response->getContent());
    }

    public function test_the_picker_is_for_people_who_create_or_edit_activities(): void
    {
        $executive = $this->user($this->mda, RoleKey::Executive);

        $this->send($executive, 'GET', '/api/v1/activities/funding-partners')->assertForbidden();
        $this->send($this->partner, 'GET', '/api/v1/activities/funding-partners')->assertForbidden();
    }

    /* ------------------------------------------------------------ partner view */

    public function test_the_partner_view_shows_the_activity_period_budget_and_co_funding(): void
    {
        Activity::factory()->forProgramme($this->programme, $this->mda)->create([
            'status' => 'active',
            'budget_amount' => 1_000_000,
            'starts_on' => '2026-01-01',
            'ends_on' => '2026-06-30',
            'funding_type' => 'partner',
            'funding_partner_id' => $this->partner->id,
            'co_funded_by_government' => true,
        ]);

        $scope = app(DashboardScopeResolver::class)->forUser($this->partner);
        $row = app(DashboardMetricsService::class)->compute($scope)['partner_funding']['programmes'][0]['activities'][0];

        $this->assertSame('2026-01-01', $row['starts_on']);
        $this->assertSame('2026-06-30', $row['ends_on']);
        $this->assertTrue($row['co_funded_by_government']);
        $this->assertSame(1_000_000, $row['allocated']);
    }

    /* ---------------------------------------------------------------- helpers */

    /** @param array<string, mixed> $overrides */
    private function user(?Mda $mda, RoleKey $role, array $overrides = []): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
            ...$overrides,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function activity(array $overrides): array
    {
        return [
            'programme_id' => $this->programme->id,
            'involves_beneficiaries' => false,
            'name' => 'Dry-season round',
            'budget_amount' => 5_000_000,
            ...$overrides,
        ];
    }

    /** @param array<string, mixed> $overrides */
    private function upload(array $overrides): TestResponse
    {
        $csv = "first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward\nAmina,Yusuf,,,08031234567,1990-05-10,female,dutse,Ward 2";

        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)->post('/api/v1/activity-imports', [
            'programme_id' => $this->programme->id,
            'name' => 'Q1 Cash Round',
            'target_beneficiaries' => 1,
            'file' => UploadedFile::fake()->createWithContent('people.csv', $csv),
            ...$overrides,
        ], ['Accept' => 'application/json']);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** @param array<string, mixed> $body */
    private function send(User $user, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($user->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }
}
