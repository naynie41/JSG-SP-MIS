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
use App\Domain\Registry\Imports\ColumnMapper;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Services\ImportMappingService;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The point of the whole mapping + normalization layer, end to end.
 *
 * Two MDAs hold records for the SAME people and describe them completely differently:
 * different column names, different column order, and different value formats — one
 * writes `+234 803…` and `12/03/1995`, the other `0803…` and `1995-03-12`.
 *
 * On the raw files these are not comparable at all; the columns do not even share names.
 * After mapping onto the canonical schema and normalizing, the duplicate cascade sees
 * the same person and says so. That is the claim this file exists to prove — and to keep
 * proving, because it is the property that silently breaks when someone "tidies up" a
 * normalization rule.
 *
 * The cascade itself is untouched: the same seeded config, the same deterministic
 * NIN → BVN → fuzzy order, the same thresholds.
 */
class CrossFormatDedupTest extends TestCase
{
    use RefreshDatabase;

    private Mda $health;

    private Mda $education;

    /** @var array<string, User> */
    private array $users = [];

    /** @var array<string, Activity> */
    private array $activities = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->health = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->education = Mda::factory()->create(['name' => 'Ministry of Education']);

        $programme = Programme::factory()->individual()->create();
        foreach (['health' => $this->health, 'education' => $this->education] as $key => $mda) {
            $this->users[$key] = User::factory()->create([
                'mda_id' => $mda->id,
                'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
            ]);
            $this->activities[$key] = Activity::factory()->forProgramme($programme, $mda)->create();
        }
    }

    /* --------------------------------------------------------------- the two files */

    /**
     * Health's export: surname first, `National ID`, international phone, day-first date.
     */
    private function healthCsv(): string
    {
        return implode("\n", [
            'Surname,Given Name,National ID,Mobile Number,Birth Date,Sex,Council,Ward Name',
            'Okoye,Ada,22200000011,+234 803 123 4567,12/03/1995,female,dutse,Ward 1',
            'Bello,Musa,33300000022,+234 805 222 3333,05/07/1988,male,kazaure,Ward 2',
        ]);
    }

    /**
     * Education's export of the SAME two people: different header names, different
     * column ORDER, national phone format, ISO dates.
     */
    private function educationCsv(): string
    {
        return implode("\n", [
            'forename,family_name,nin_number,msisdn,dob,gender,lga,ward',
            'Ada,Okoye,22200000011,08031234567,1995-03-12,female,dutse,Ward 1',
            'Musa,Bello,33300000022,08052223333,1988-07-05,male,kazaure,Ward 2',
        ]);
    }

    /* --------------------------------------------------------------- the machinery */

    /**
     * Two DIFFERENT MDAs act in one test, so the guard is reset BEFORE each request
     * rather than after. Resetting afterwards leaves whatever ran in between — here the
     * mapping confirmation and the sync parse — able to re-resolve the previous user,
     * and the next request then authenticates as the wrong MDA.
     */
    private function as(string $mdaKey): self
    {
        $this->app['auth']->forgetGuards();

        return $this->withToken($this->users[$mdaKey]->createToken('t')->plainTextToken);
    }

    private function upload(string $mdaKey, string $csv): ImportBatch
    {
        $response = $this->as($mdaKey)
            ->post('/api/v1/beneficiaries/imports', [
                'file' => UploadedFile::fake()->createWithContent('cohort.csv', $csv),
                'activity_id' => $this->activities[$mdaKey]->id,
            ], ['Accept' => 'application/json'])
            ->assertCreated();

        return ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
    }

    /**
     * Confirm the mapping the way an officer would — accepting the suggestions — then
     * let the existing pipeline run.
     */
    private function confirmMapping(ImportBatch $batch, string $mdaKey): ImportBatch
    {
        $columnMap = [];
        foreach (app(ColumnMapper::class)->suggest($batch->detected_headers ?? []) as $field => $suggestion) {
            $columnMap[$field] = $suggestion['header'];
        }

        app(ImportMappingService::class)->confirm($batch, $columnMap, $this->users[$mdaKey]);
        ParseImportBatch::dispatchSync($batch->id);

        return $batch->fresh();
    }

    private function commit(string $mdaKey, ImportBatch $batch): TestResponse
    {
        return $this->as($mdaKey)->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm");
    }

    /* ------------------------------------------- the raw files are not comparable */

    public function test_the_two_files_share_no_column_names_or_value_formats(): void
    {
        $health = $this->upload('health', $this->healthCsv());
        $education = $this->upload('education', $this->educationCsv());

        $shared = array_intersect($health->detected_headers ?? [], $education->detected_headers ?? []);

        // Establishes the premise: on the raw files there is nothing to compare. Without
        // the mapping layer these two MDAs could not detect each other's duplicates at all.
        $this->assertSame([], $shared, 'the two exports have no column name in common');
        $this->assertNotSame($health->source_signature, $education->source_signature);
    }

    /* ----------------------------------------------- …but the canonical rows are */

    public function test_mapping_and_normalization_make_the_two_formats_comparable(): void
    {
        $health = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');
        $education = $this->confirmMapping($this->upload('education', $this->educationCsv()), 'education');

        $healthRow = $health->rows()->where('row_number', 1)->firstOrFail();
        $educationRow = $education->rows()->where('row_number', 1)->firstOrFail();

        // Same person, described two ways, reduced to the same canonical values.
        foreach (['first_name', 'last_name', 'nin', 'date_of_birth'] as $field) {
            $this->assertSame(
                $healthRow->payload[$field],
                $educationRow->payload[$field],
                "{$field} did not reconcile across the two formats",
            );
        }
    }

    public function test_the_second_mda_upload_is_flagged_as_a_duplicate_of_the_first(): void
    {
        // Health imports and commits first — it becomes the owner (FR-OWN-01).
        $health = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');
        $this->commit('health', $health)->assertOk();
        $this->assertSame(2, Beneficiary::query()->withoutGlobalScopes()->count());

        // Education then uploads the same people in its own format.
        $education = $this->confirmMapping($this->upload('education', $this->educationCsv()), 'education');

        // The cascade recognises them — on the RAW files it could not have.
        foreach ([1, 2] as $rowNumber) {
            $row = $education->rows()->where('row_number', $rowNumber)->firstOrFail();
            $this->assertSame('exact', $row->match_band, "row {$rowNumber} should match the existing record");
        }
    }

    public function test_a_duplicate_is_not_committed_as_a_second_record(): void
    {
        $health = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');
        $this->commit('health', $health)->assertOk();

        $education = $this->confirmMapping($this->upload('education', $this->educationCsv()), 'education');
        $this->commit('education', $education)->assertOk();

        // Two people, two files, one registry entry each — the entire purpose of the
        // hybrid registry (CLAUDE.md §1).
        $this->assertSame(2, Beneficiary::query()->withoutGlobalScopes()->count());

        // …and ownership stayed with the first importer.
        foreach (Beneficiary::query()->withoutGlobalScopes()->get() as $beneficiary) {
            $this->assertSame($this->health->id, $beneficiary->owner_mda_id);
        }
    }

    public function test_a_genuinely_new_person_in_the_second_file_still_registers(): void
    {
        $health = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');
        $this->commit('health', $health)->assertOk();

        $withNewcomer = $this->educationCsv()."\nHalima,Sule,44400000033,08061234567,1990-01-01,female,gumel,Ward 3";
        $education = $this->confirmMapping($this->upload('education', $withNewcomer), 'education');

        $newRow = $education->rows()->where('row_number', 3)->firstOrFail();
        $this->assertSame('none', $newRow->match_band, 'a person the registry has never seen is not a duplicate');

        $this->commit('education', $education)->assertOk();

        // The newcomer registers under Education; the two duplicates do not.
        $this->assertSame(3, Beneficiary::query()->withoutGlobalScopes()->count());
        $this->assertSame(
            $this->education->id,
            Beneficiary::query()->withoutGlobalScopes()->where('first_name', 'Halima')->firstOrFail()->owner_mda_id,
        );
    }

    /* ------------------------------------------------ the written values survive */

    public function test_each_mda_keeps_the_phone_number_as_its_own_source_wrote_it(): void
    {
        $health = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');
        $this->commit('health', $health)->assertOk();

        $ada = Beneficiary::query()->withoutGlobalScopes()->where('first_name', 'Ada')->firstOrFail();

        // Normalization is for COMPARISON; the record keeps what the MDA submitted
        // (CLAUDE.md §11).
        $this->assertSame('+234 803 123 4567', $ada->phone);
        $this->assertSame('08031234567', $ada->phone_normalized);
    }

    public function test_a_day_first_date_is_not_read_as_month_first(): void
    {
        $health = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');
        $this->commit('health', $health)->assertOk();

        // Health wrote 12/03/1995 and Education wrote 1995-03-12 for the same person.
        // Read month-first this would be 3 December — a different date, a different
        // blocking key, and the duplicate above would never have been found.
        $ada = Beneficiary::query()->withoutGlobalScopes()->where('first_name', 'Ada')->firstOrFail();
        $this->assertSame('1995-03-12', $ada->date_of_birth?->toDateString());
    }

    /* --------------------------------------------------- history + raw retention */

    public function test_the_batch_records_the_mapping_it_was_read_with(): void
    {
        $batch = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');

        $data = $this->as('health')
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()->json('data.mapping');

        // "Which column did we believe held the NIN, and who said so" has to be
        // answerable long after the import.
        // Headers are canonicalised by the reader before they are ever offered for
        // mapping, so the stored answer is the canonical form of the source's own name.
        $this->assertSame('national_id', $data['column_map']['nin']);
        $this->assertSame($this->users['health']->name, $data['confirmed_by']);
        $this->assertNotNull($data['confirmed_at']);
        $this->assertNotNull($data['source_signature']);
    }

    public function test_the_raw_file_is_retained_unmutated_after_commit(): void
    {
        $batch = $this->confirmMapping($this->upload('health', $this->healthCsv()), 'health');
        $this->commit('health', $batch)->assertOk();

        // The upload survives the whole pipeline exactly as submitted — the canonical
        // representation lives in import_rows, never over the source.
        $stored = Storage::disk('local')->get($batch->fresh()->stored_path);
        $this->assertSame($this->healthCsv(), $stored);
        $this->assertStringContainsString('National ID', (string) $stored);
        $this->assertStringContainsString('+234 803 123 4567', (string) $stored);
    }
}
