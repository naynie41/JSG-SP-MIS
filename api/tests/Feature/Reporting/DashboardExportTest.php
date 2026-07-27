<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Executive dashboard export (PRD FR-RPT-03): CSV/Excel/PDF of the current scoped +
 * filtered view. Gated by `reporting.export`; AGGREGATE-only — never raw PII.
 */
class DashboardExportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->programme = Programme::factory()->individual()->create(['status' => 'active']);
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)->create(['status' => 'active', 'lga' => 'dutse', 'budget_amount' => 1_000_000, 'target_beneficiaries' => 4]);

        // A distinctively-named beneficiary so we can prove the export never leaks it.
        $ben = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id, 'lga' => 'dutse', 'first_name' => 'Zzxq', 'last_name' => 'Secretname']);
        Benefit::factory()->create(['beneficiary_id' => $ben->id, 'programme_id' => $this->programme->id, 'mda_id' => $this->mda->id, 'activity_id' => $activity->id, 'lga' => 'dutse', 'monetary_value' => 250_000, 'status' => 'verified']);

        app(DashboardSnapshotService::class)->refreshAll();
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function download(User $user, string $query = ''): TestResponse
    {
        $token = $user->createToken('t')->plainTextToken;

        return $this->withToken($token)->get('/api/v1/dashboard/export'.$query);
    }

    public function test_executive_exports_aggregate_csv(): void
    {
        $response = $this->download($this->user(null, RoleKey::Executive), '?format=csv')->assertOk();

        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
        $body = $response->streamedContent();

        // Aggregate metric labels are present…
        $this->assertStringContainsString('Net-unique beneficiaries', $body);
        $this->assertStringContainsString('Active programmes', $body);

        // …and NO raw beneficiary-level data leaks (never a name or id column).
        $this->assertStringNotContainsString('Secretname', $body);
        $this->assertStringNotContainsString('beneficiary_id', $body);
    }

    public function test_export_supports_excel_and_pdf(): void
    {
        $exec = $this->user(null, RoleKey::Executive);

        $xlsx = $this->download($exec, '?format=xlsx')->assertOk();
        $this->assertStringContainsString('spreadsheetml', (string) $xlsx->headers->get('content-type'));

        $pdf = $this->download($exec, '?format=pdf')->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
    }

    public function test_export_reflects_the_active_filter(): void
    {
        // Filtering to a programme with no deliveries → net-unique 0 in the file.
        $other = Programme::factory()->individual()->create(['status' => 'active']);
        Activity::factory()->forProgramme($other, $this->mda)->create(['status' => 'active']);

        $body = $this->download($this->user(null, RoleKey::Executive), '?format=csv&programme_id='.$other->id)->assertOk()->streamedContent();

        // The unfiltered export has "…,1" for net-unique; the filtered one has 0.
        $this->assertMatchesRegularExpression('/Net-unique beneficiaries.*0/', $body);
    }

    public function test_export_requires_the_reporting_export_permission(): void
    {
        $noRole = User::factory()->create(['mda_id' => $this->mda->id, 'role_id' => null]);

        $this->download($noRole, '?format=csv')->assertStatus(403);
    }
}
