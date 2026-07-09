<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Duplicate verification wired into the import preview (PRD FR-DUP-01/04, §8.1):
 * each row is flagged exact/probable/none against the registry AND earlier rows,
 * with a reveal payload that exposes only the permitted fields.
 */
class ImportDuplicateScreeningTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private User $officer;

    private Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mdaA->id,
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);
        $this->activity = Activity::factory()->forProgramme(
            Programme::factory()->individual()->create(),
            $this->mdaA,
        )->create();
    }

    private function token(): string
    {
        return $this->officer->createToken('test')->plainTextToken;
    }

    private function preview(): array
    {
        // An existing beneficiary owned by ANOTHER MDA that row 1 duplicates by NIN.
        $existing = Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaB->id,
            'nin' => '22200000011',
            'first_name' => 'Zainab',
            'last_name' => 'Umar',
            'date_of_birth' => '1980-01-01',
        ]);

        $csv = implode("\n", [
            'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward',
            'Different,Person,22200000011,,08000000000,1999-09-09,male,kazaure,Ward 3', // row1: exact (NIN) vs existing
            'Muhammadu,Sani,,,08031234567,1990-05-10,male,gumel,Ward 2',               // row2
            'Mohammed,Saani,,,08031234567,1990-05-10,male,gumel,Ward 5',               // row3: probable vs row2 (within-batch)
            'Grace,Okoro,,,08099999999,2000-03-03,female,ringim,Ward 1',               // row4: none
        ]);

        $upload = $this->withToken($this->token())
            ->post('/api/v1/beneficiaries/imports', ['file' => UploadedFile::fake()->createWithContent('people.csv', $csv), 'activity_id' => $this->activity->id], ['Accept' => 'application/json'])
            ->assertCreated();

        $batchId = $upload->json('data.id');
        $this->app['auth']->forgetGuards();

        return [$existing, $this->withToken($this->token())->getJson("/api/v1/beneficiaries/imports/{$batchId}")->assertOk()];
    }

    public function test_a_registry_duplicate_is_flagged_exact_with_a_reveal_payload(): void
    {
        [$existing, $response] = $this->preview();

        $response
            ->assertJsonPath('data.rows.0.match.band', 'exact')
            ->assertJsonPath('data.rows.0.match.candidates.0.type', 'registry')
            ->assertJsonPath('data.rows.0.match.candidates.0.reveal.id', $existing->id)
            ->assertJsonPath('data.rows.0.match.candidates.0.reveal.full_name', 'Zainab Umar')
            ->assertJsonPath('data.rows.0.match.candidates.0.reveal.owner_mda.name', 'MDA B');

        $this->assertContains('nin', $response->json('data.rows.0.match.candidates.0.matched_fields'));
    }

    public function test_the_reveal_exposes_only_permitted_fields_and_programme_benefit_sections(): void
    {
        [, $response] = $this->preview();

        // Programme/benefit sections are present; this matched record has none yet.
        $response
            ->assertJsonPath('data.rows.0.match.candidates.0.reveal.programmes', [])
            ->assertJsonPath('data.rows.0.match.candidates.0.reveal.benefits.summary.count', 0)
            ->assertJsonPath('data.rows.0.match.candidates.0.reveal.benefits.items', []);

        // Sensitive fields are never leaked in the reveal.
        $reveal = $response->json('data.rows.0.match.candidates.0.reveal');
        foreach (['nin', 'bvn', 'phone', 'address', 'date_of_birth'] as $forbidden) {
            $this->assertArrayNotHasKey($forbidden, $reveal, "Reveal leaked {$forbidden}");
        }
    }

    public function test_a_within_batch_duplicate_is_flagged_probable_against_the_earlier_row(): void
    {
        [, $response] = $this->preview();

        $response
            ->assertJsonPath('data.rows.2.match.band', 'probable')
            ->assertJsonPath('data.rows.2.match.candidates.0.type', 'batch')
            ->assertJsonPath('data.rows.2.match.candidates.0.reveal.row_number', 2)
            ->assertJsonPath('data.rows.2.match.candidates.0.reveal.id', null);
    }

    public function test_a_unique_row_is_not_flagged(): void
    {
        [, $response] = $this->preview();

        $response
            ->assertJsonPath('data.rows.3.match.band', 'none')
            ->assertJsonPath('data.rows.3.match.candidates', []);
    }

    public function test_nothing_is_persisted_by_the_duplicate_check(): void
    {
        [$existing] = $this->preview();

        // Only the pre-existing record exists; screening created nothing.
        $this->assertSame(1, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());
        $this->assertSame($existing->id, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->first()->id);

        // The batch is a preview awaiting confirmation.
        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->firstOrFail();
        $this->assertSame('preview_ready', $batch->status->value);
    }
}
