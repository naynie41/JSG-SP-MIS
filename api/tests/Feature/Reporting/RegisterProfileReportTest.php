<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Export\RegisterProfileExportBuilder;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Segments\SegmentAccess;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * "People in the register" (FR-RPT-12) — the chart-only profile of a whole scope.
 *
 * The things worth pinning are what it REFUSES to be: it carries no rows, it takes no
 * filters, and it is a PDF whatever anyone asks for. Each of those is a property someone
 * could undo by adding a convenience later.
 */
class RegisterProfileReportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
    }

    private function admin(): User
    {
        return User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function person(Gender $gender, ?int $age): Beneficiary
    {
        return Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'gender' => $gender->value,
            'date_of_birth' => $age === null ? null : Carbon::today()->subYears($age)->toDateString(),
        ]);
    }

    private function accessFor(User $user): SegmentAccess
    {
        return SegmentAccess::forUser($user, app(DashboardScopeResolver::class)->forUser($user));
    }

    /* ------------------------------------------------------------- the report */

    public function test_it_carries_figures_and_never_rows(): void
    {
        $this->person(Gender::Female, 30);
        $this->person(Gender::Male, 40);

        $data = app(RegisterProfileExportBuilder::class)->build($this->accessFor($this->admin()));

        $this->assertSame('People in the register', $data->title);
        // The whole safety argument rests on this: no rows means nothing to mask.
        $this->assertSame([], $data->rows);
        $this->assertSame([], $data->columns);
        $this->assertNotEmpty($data->figures);
        $this->assertTrue($data->crest, 'The report must open with the state crest.');
    }

    public function test_the_pyramid_leads_and_is_drawn_as_an_image(): void
    {
        foreach ([12, 25, 44, 70] as $age) {
            $this->person(Gender::Female, $age);
            $this->person(Gender::Male, $age);
        }

        $data = app(RegisterProfileExportBuilder::class)->build($this->accessFor($this->admin()));
        $first = $data->figures[0];

        $this->assertSame('Gender and age', $first->title);
        $this->assertNotNull($first->image);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $first->image);

        $svg = base64_decode(substr($first->image, strlen('data:image/svg+xml;base64,')), true);
        $this->assertIsString($svg);

        // Both wings present, labelled, and oldest band first — a pyramid read from the
        // top down. A chart that silently dropped one gender would still be an image.
        $this->assertStringContainsString('Female', $svg);
        $this->assertStringContainsString('Male', $svg);
        $this->assertLessThan(
            strpos($svg, 'Children'),
            strpos($svg, 'Elderly'),
            'The oldest band must be drawn first, at the top of the pyramid.',
        );
    }

    /**
     * The chart can only show people whose age is known. Where most are unknown, the
     * report has to say so beside the chart rather than let the shape stand unqualified.
     */
    public function test_it_states_how_many_people_the_pyramid_leaves_out(): void
    {
        $this->person(Gender::Female, 30);
        foreach (range(1, 9) as $_) {
            $this->person(Gender::Male, null);
        }

        $data = app(RegisterProfileExportBuilder::class)->build($this->accessFor($this->admin()));
        $note = (string) $data->figures[0]->note;

        $this->assertStringContainsString('9 of 10', $note);
        $this->assertStringContainsString('no recorded date of birth', $note);
        $this->assertStringContainsString('minority of the register', $note);
    }

    public function test_with_no_dates_of_birth_it_says_so_instead_of_drawing_nothing(): void
    {
        $this->person(Gender::Female, null);
        $this->person(Gender::Male, null);

        $data = app(RegisterProfileExportBuilder::class)->build($this->accessFor($this->admin()));
        $first = $data->figures[0];

        $this->assertNull($first->image);
        $this->assertStringContainsString('placed on an age band', (string) $first->note);
        $this->assertStringContainsString('No date of birth is recorded', (string) $first->note);
    }

    public function test_it_describes_the_whole_scope_and_ignores_any_filters_sent(): void
    {
        $this->person(Gender::Female, 30);
        $this->person(Gender::Male, 40);
        $token = $this->admin()->createToken('t')->plainTextToken;

        // Filters are not part of the contract; sending them changes nothing.
        $this->withToken($token)
            ->postJson('/api/v1/reports/register-profile', ['filters' => ['gender' => ['op' => 'in', 'values' => ['female']]]])
            ->assertStatus(202);

        $run = ReportRun::query()->latest('created_at')->firstOrFail();

        $this->assertSame(ReportRun::KEY_REGISTER_PROFILE, $run->report_key);
        $this->assertSame('pdf', $run->format, 'This report is a PDF whatever was asked for.');
        $this->assertNull($run->definition, 'Nothing to capture — the report always means the whole scope.');
    }

    /* ---------------------------------------------------------------- access */

    public function test_it_needs_the_reporting_export_permission(): void
    {
        $this->postJson('/api/v1/reports/register-profile')->assertUnauthorized();
    }

    public function test_it_is_scoped_to_the_callers_own_organisation(): void
    {
        $other = Mda::factory()->create(['name' => 'Ministry of Education']);
        Beneficiary::factory()->count(4)->create(['owner_mda_id' => $other->id, 'gender' => Gender::Female->value]);
        $this->person(Gender::Male, 30);

        $data = app(RegisterProfileExportBuilder::class)->build($this->accessFor($this->admin()));

        $overview = collect($data->summary)->firstWhere('title', 'Overview');
        $total = collect($overview->items)->firstWhere('label', 'People in this report')['value'];

        $this->assertSame('1', $total, 'Another MDA\'s people must not be counted.');
    }
}
