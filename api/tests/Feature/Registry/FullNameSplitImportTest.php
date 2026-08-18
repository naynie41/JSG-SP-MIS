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
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Unit\Registry\NameSplitterTest;

/**
 * A single name column, split into first/last through the REAL pipeline.
 *
 * The unit rules live in {@see NameSplitterTest}; this proves the
 * split actually reaches staged rows, that a `full_name` mapping satisfies the identity
 * guard, and that explicit name columns still win.
 *
 * The defect it exists to prevent shipped: one `Name` column mapped to BOTH first and
 * last name, producing 220 records reading "Rekiya Bagwai Rekiya Bagwai".
 */
class FullNameSplitImportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Activity $activity;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $programme = Programme::factory()->individual()->create();
        $this->activity = Activity::factory()->forProgramme($programme, $this->mda)->create();
    }

    private function send(string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function upload(string $csv): ImportBatch
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)
            ->post('/api/v1/beneficiaries/imports', [
                'file' => UploadedFile::fake()->createWithContent('cohort.csv', $csv),
                'activity_id' => $this->activity->id,
            ], ['Accept' => 'application/json'])->assertCreated();

        $this->app['auth']->forgetGuards();

        return ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
    }

    /** The MoH shape: one `Name` column, no separate given/surname. */
    private function oneNameColumnCsv(): string
    {
        return implode("\n", [
            'Name,Date of Birth,Gender,National ID,Local Government,Ward',
            'Rekiya Bagwai,18/01/1999,Female,22200000011,dutse,Zungumba',
            'Barira Sadau Barde,02/02/1990,Female,22200000012,dutse,Zungumba',
            'Amina,03/03/1991,Female,22200000013,dutse,Zungumba',
        ]);
    }

    /** @return array<string, string|null> */
    private function fullNameMap(): array
    {
        return [
            'full_name' => 'name',
            'first_name' => null, // this file has no separate given-name column
            'last_name' => null,
            'nin' => 'national_id',
            'bvn' => null,
            'phone' => null,
            'date_of_birth' => 'date_of_birth',
            'gender' => 'gender',
            'lga' => 'local_government',
            'ward' => 'ward',
        ];
    }

    /** @return array<int, array<string, mixed>> row_number => staged payload */
    private function stagedRows(ImportBatch $batch): array
    {
        ParseImportBatch::dispatchSync($batch->id);

        return $batch->fresh()->rows()->orderBy('row_number')->get()
            ->mapWithKeys(fn ($row) => [$row->row_number => $row->payload])->all();
    }

    public function test_one_name_column_is_split_into_first_and_last(): void
    {
        $batch = $this->upload($this->oneNameColumnCsv());

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->fullNameMap()])
            ->assertOk();

        $rows = $this->stagedRows($batch);

        // Two names: exactly as written.
        $this->assertSame('Rekiya', $rows[1]['first_name']);
        $this->assertSame('Bagwai', $rows[1]['last_name']);

        // Three names: the middle token belongs to the SURNAME (the stated rule).
        $this->assertSame('Barira', $rows[2]['first_name']);
        $this->assertSame('Sadau Barde', $rows[2]['last_name']);
    }

    public function test_the_duplicated_name_defect_is_gone(): void
    {
        $batch = $this->upload($this->oneNameColumnCsv());
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->fullNameMap()]);

        foreach ($this->stagedRows($batch) as $payload) {
            $joined = trim(($payload['first_name'] ?? '').' '.($payload['last_name'] ?? ''));
            $this->assertStringNotContainsString(
                (string) $payload['first_name'].' '.(string) $payload['first_name'],
                $joined,
                'the name must not be repeated',
            );
        }
    }

    public function test_full_name_is_never_stored_as_a_field_of_its_own(): void
    {
        // It is a SOURCE shape, not a beneficiary column. Leaking it into the payload
        // would give the registry a second, unvalidated copy of the name.
        $batch = $this->upload($this->oneNameColumnCsv());
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->fullNameMap()]);

        foreach ($this->stagedRows($batch) as $payload) {
            $this->assertArrayNotHasKey('full_name', $payload);
        }
    }

    public function test_a_single_token_name_yields_no_surname_and_the_row_is_rejected(): void
    {
        // SP-MIS cannot invent a surname. `last_name` is required, so the row fails —
        // which is the honest outcome rather than a fabricated blocking key.
        $batch = $this->upload($this->oneNameColumnCsv());
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->fullNameMap()]);

        $rows = $this->stagedRows($batch);
        $this->assertSame('Amina', $rows[3]['first_name']);
        $this->assertNull($rows[3]['last_name']);

        $row = $batch->fresh()->rows()->where('row_number', 3)->firstOrFail();
        $this->assertFalse($row->is_valid);
    }

    public function test_explicit_name_columns_win_over_a_full_name_column(): void
    {
        // "if there is a first name and last name field in the data, use it".
        $csv = implode("\n", [
            'Name,First Name,Surname,Date of Birth,Gender,LGA,Ward',
            'IGNORE ME ENTIRELY,Ada,Okoye,12/03/1995,female,dutse,Ward 1',
        ]);
        $batch = $this->upload($csv);

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => [
            'full_name' => 'name',
            'first_name' => 'first_name',
            'last_name' => 'surname',
            'nin' => null, 'bvn' => null, 'phone' => null,
            'date_of_birth' => 'date_of_birth', 'gender' => 'gender', 'lga' => 'lga', 'ward' => 'ward',
        ]])->assertOk();

        $rows = $this->stagedRows($batch);
        $this->assertSame('Ada', $rows[1]['first_name']);
        $this->assertSame('Okoye', $rows[1]['last_name']);
    }

    public function test_a_full_name_column_fills_only_the_gaps_row_by_row(): void
    {
        // A file that carries separate names for some people and one field for others
        // keeps whatever it actually stated for each row.
        $csv = implode("\n", [
            'Name,First Name,Surname,Date of Birth,Gender,LGA,Ward',
            'Rekiya Bagwai,,,18/01/1999,female,dutse,Ward 1',
            'IGNORED,Ada,Okoye,12/03/1995,female,dutse,Ward 1',
        ]);
        $batch = $this->upload($csv);

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => [
            'full_name' => 'name', 'first_name' => 'first_name', 'last_name' => 'surname',
            'nin' => null, 'bvn' => null, 'phone' => null,
            'date_of_birth' => 'date_of_birth', 'gender' => 'gender', 'lga' => 'lga', 'ward' => 'ward',
        ]])->assertOk();

        $rows = $this->stagedRows($batch);
        $this->assertSame(['Rekiya', 'Bagwai'], [$rows[1]['first_name'], $rows[1]['last_name']]);
        $this->assertSame(['Ada', 'Okoye'], [$rows[2]['first_name'], $rows[2]['last_name']]);
    }

    // ------------------------------------------------------------- the guard

    public function test_a_mapping_with_no_name_source_at_all_is_refused(): void
    {
        // Caught at the one decision point rather than as several hundred identical
        // "missing last name" rejections after the file has been parsed.
        $batch = $this->upload($this->oneNameColumnCsv());

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => [
            'full_name' => null, 'first_name' => null, 'last_name' => null,
            'nin' => 'national_id', 'bvn' => null, 'phone' => null,
            'date_of_birth' => 'date_of_birth', 'gender' => 'gender',
            'lga' => 'local_government', 'ward' => 'ward',
        ]])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MAPPING_INCOMPLETE')
            ->assertJsonPath('error.message', 'This mapping has no name: point first and last name at columns, or map a single full-name column.');

        $this->assertSame(0, $batch->fresh()->rows()->count());
    }

    public function test_mapping_first_and_last_name_to_the_same_column_is_refused(): void
    {
        // The natural thing to try with a one-name-column file, and the exact mistake
        // that produced "Rekiya Bagwai Rekiya Bagwai" on 331 records. Refused at the
        // mapping step, with the fix named in the message.
        $batch = $this->upload($this->oneNameColumnCsv());

        $response = $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => [
            'full_name' => null,
            'first_name' => 'name',
            'last_name' => 'name',
            'nin' => 'national_id', 'bvn' => null, 'phone' => null,
            'date_of_birth' => 'date_of_birth', 'gender' => 'gender',
            'lga' => 'local_government', 'ward' => 'ward',
        ]])->assertStatus(422);

        $this->assertSame('MAPPING_INCOMPLETE', $response->json('error.code'));
        $this->assertStringContainsString('store the whole name twice', (string) $response->json('error.message'));
        $this->assertStringContainsString('Full name (one column)', (string) $response->json('error.message'));

        $this->assertNull($batch->fresh()->mapping_confirmed_at);
        $this->assertSame(0, $batch->fresh()->rows()->count());
    }

    public function test_two_different_name_columns_are_still_fine(): void
    {
        // The guard must catch only the SAME column twice, not separate name columns.
        $csv = "First Name,Surname,Date of Birth,Gender,LGA,Ward\nAda,Okoye,12/03/1995,female,dutse,Ward 1";
        $batch = $this->upload($csv);

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => [
            'full_name' => null, 'first_name' => 'first_name', 'last_name' => 'surname',
            'nin' => null, 'bvn' => null, 'phone' => null,
            'date_of_birth' => 'date_of_birth', 'gender' => 'gender', 'lga' => 'lga', 'ward' => 'ward',
        ]])->assertOk();
    }

    public function test_a_name_column_is_never_auto_mapped_without_confirmation(): void
    {
        // CLAUDE.md §11: a confident "Name" suggestion must not silently become the
        // identity of 220 people. `full_name` is confirmation-required for that reason.
        $batch = $this->upload($this->oneNameColumnCsv());

        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$batch->id}/mapping")->assertOk()->json('data');

        $this->assertContains('full_name', $data['identity_fields']);
        $this->assertContains('full_name', $data['unconfirmed_identity_fields']);
        $this->assertSame([], $batch->fresh()->column_map);
    }
}
