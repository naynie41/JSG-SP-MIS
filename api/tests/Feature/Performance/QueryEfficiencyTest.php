<?php

declare(strict_types=1);

namespace Tests\Feature\Performance;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Query-efficiency regression (NFR-PERF-01, NFR-SCAL-01): the hot list endpoints
 * must issue a BOUNDED number of queries regardless of how many rows they return
 * (no N+1), and the hot-path composite indexes must exist. This locks in the
 * efficiency so a future eager-load regression fails loudly here.
 */
class QueryEfficiencyTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Mda $mda;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create();
        $this->admin = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
        $this->programme = Programme::factory()->individual()->create();
    }

    /** @return int the number of DB queries the request issued */
    private function queriesFor(string $url): int
    {
        $token = $this->admin->createToken('t')->plainTextToken; // token minted before measuring
        DB::flushQueryLog();
        DB::enableQueryLog();
        $this->withToken($token)->getJson($url)->assertOk();
        $count = count(DB::getQueryLog());
        DB::disableQueryLog();
        $this->app['auth']->forgetGuards();

        return $count;
    }

    public function test_beneficiary_list_does_not_n_plus_one(): void
    {
        Beneficiary::factory()->count(25)->create(['owner_mda_id' => $this->mda->id]);

        // A 25-row page must not issue per-row queries. The constant covers auth +
        // permission + count + the single page select (well under one-query-per-row).
        $this->assertLessThanOrEqual(15, $this->queriesFor('/api/v1/beneficiaries?per_page=25'));
    }

    public function test_benefit_ledger_does_not_n_plus_one(): void
    {
        $beneficiaries = Beneficiary::factory()->count(25)->create(['owner_mda_id' => $this->mda->id]);
        foreach ($beneficiaries as $beneficiary) {
            Benefit::factory()->create([
                'beneficiary_id' => $beneficiary->id,
                'programme_id' => $this->programme->id,
                'mda_id' => $this->mda->id,
            ]);
        }

        $this->assertLessThanOrEqual(15, $this->queriesFor('/api/v1/benefits?per_page=25'));
    }

    public function test_hot_path_indexes_exist(): void
    {
        $expected = [
            'beneficiaries_owner_reg_date_idx',
            'benefits_mda_status_idx',
            'benefits_mda_delivery_idx',
            'enrollments_beneficiary_status_idx',
            'consents_beneficiary_purpose_idx',
        ];

        foreach ($expected as $index) {
            $found = DB::selectOne("SELECT name FROM sqlite_master WHERE type = 'index' AND name = ?", [$index]);
            $this->assertNotNull($found, "Missing performance index: {$index}");
        }
    }
}
