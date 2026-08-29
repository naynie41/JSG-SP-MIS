<?php

declare(strict_types=1);

namespace Tests\Feature\Graduation;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Graduation\Models\GraduationEvent;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The graduation RECORD as an officer reads it (FR-GRD-02).
 *
 * The history endpoint returned ids and nothing else — enrolment, beneficiary, criteria,
 * decider, all UUIDs. That is a record only a database can read: the people accountable
 * for these judgements cannot review a page of identifiers, so nothing in the UI ever
 * rendered it. These tests pin the names that make it legible, and the scope that keeps
 * them safe to show.
 */
class GraduationRecordTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Mda $otherMda;

    private Programme $programme;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['officer'] = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->users['outsider'] = $this->user($this->otherMda, RoleKey::MdaAdmin);

        $this->programme = Programme::factory()->create();
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $url): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->getJson($url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function criteria(): GraduationCriteria
    {
        return GraduationCriteria::query()->create([
            'name' => 'Two years of support',
            'programme_id' => $this->programme->id,
            'owner_mda_id' => $this->mda->id,
            'logic' => 'all',
            'rules' => [['type' => 'months_enrolled', 'threshold' => 24]],
            'is_active' => true,
            'created_by' => $this->users['officer']->id,
        ]);
    }

    private function event(Mda $mda, ?Beneficiary $beneficiary = null, ?Household $household = null): GraduationEvent
    {
        $enrollment = Enrollment::factory()->create([
            'programme_id' => $this->programme->id,
            'mda_id' => $mda->id,
            'beneficiary_id' => $beneficiary?->id,
            'household_id' => $household?->id,
            'enrolled_on' => now()->subMonths(30)->toDateString(),
        ]);

        return GraduationEvent::query()->create([
            'enrollment_id' => $enrollment->id,
            'beneficiary_id' => $beneficiary?->id,
            'household_id' => $household?->id,
            'programme_id' => $this->programme->id,
            'mda_id' => $mda->id,
            'criteria_id' => $this->criteria()->id,
            'reason' => 'Income sustained above the threshold.',
            'decided_by' => $this->users['officer']->id,
            'graduated_at' => now(),
        ]);
    }

    /* ------------------------------------------------------------- it reads as a record */

    public function test_the_record_names_the_person_the_decider_and_the_criteria(): void
    {
        $beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'first_name' => 'Ada',
            'last_name' => 'Okoye',
        ]);
        $event = $this->event($this->mda, beneficiary: $beneficiary);

        $row = collect($this->send('officer', '/api/v1/graduation-events')->assertOk()->json('data'))
            ->firstWhere('id', $event->id);

        $this->assertSame('beneficiary', $row['subject']['type']);
        $this->assertStringContainsString('Ada', (string) $row['subject']['name']);
        $this->assertSame($this->users['officer']->name, $row['decided_by_name']);
        $this->assertSame('Two years of support', $row['criteria_name']);
        $this->assertSame('Income sustained above the threshold.', $row['reason']);
    }

    public function test_a_household_graduation_is_named_by_its_head(): void
    {
        // A household has no name of its own, and "household 01a04…" tells a reviewer
        // nothing about which family this decision was about.
        $head = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'first_name' => 'Musa',
            'last_name' => 'Danjuma',
        ]);
        $household = Household::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'head_beneficiary_id' => $head->id,
        ]);
        $event = $this->event($this->mda, household: $household);

        $row = collect($this->send('officer', '/api/v1/graduation-events')->assertOk()->json('data'))
            ->firstWhere('id', $event->id);

        $this->assertSame('household', $row['subject']['type']);
        $this->assertStringContainsString('Musa', (string) $row['subject']['name']);
    }

    /* --------------------------------------------------------------------- scope */

    public function test_the_record_never_names_another_mdas_graduation(): void
    {
        // The names are only safe to show because the history is scoped to the MDA that
        // ran the programme. If that ever stopped holding, this is where it would surface.
        $theirs = Beneficiary::factory()->create([
            'owner_mda_id' => $this->otherMda->id,
            'first_name' => 'Halima',
            'last_name' => 'Yusuf',
        ]);
        $this->event($this->otherMda, beneficiary: $theirs);

        $body = $this->send('officer', '/api/v1/graduation-events')->assertOk();

        $this->assertSame([], $body->json('data'));
        $this->assertStringNotContainsString('Halima', $body->getContent());
    }

    public function test_the_history_can_be_narrowed_to_one_programme(): void
    {
        $mine = $this->event($this->mda, beneficiary: Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]));

        $other = Programme::factory()->create();
        $rows = $this->send('officer', "/api/v1/graduation-events?programme_id={$other->id}")->assertOk()->json('data');

        $this->assertNotContains($mine->id, array_column($rows, 'id'));
    }
}
