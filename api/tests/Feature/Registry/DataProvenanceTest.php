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
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Services\ImportCommitter;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Data provenance: where a record came from, and who owns it.
 *
 * These are two different facts and the system must never conflate them. A record can be
 * MINED FROM SOCU and OWNED BY the first MDA that imported it — SOCU is an origin, not an
 * owner, and no amount of provenance moves ownership (FR-OWN-01).
 *
 * The per-record source id is `original_record_id` — the source's own key for that row,
 * which for a SOCU batch is the SOCU ID. It is one field, not two: it doubles as the
 * idempotency key, so a second column meaning the same thing would let display and
 * de-duplication disagree about which id a record actually has.
 */
class DataProvenanceTest extends TestCase
{
    use RefreshDatabase;

    private Mda $health;

    private Mda $women;

    private Activity $activity;

    private User $healthOfficer;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->health = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->women = Mda::factory()->create(['name' => 'Ministry of Women Affairs']);

        $this->healthOfficer = $this->officerFor($this->health);

        $programme = Programme::factory()->individual()->create();
        $this->activity = Activity::factory()->forProgramme($programme, $this->health)->create();
    }

    private function officerFor(Mda $mda): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    /** A SOCU extract: the SOCU register's own id per row. */
    private function socuCsv(string $socuId = 'SOCU-000123'): string
    {
        return implode("\n", [
            'socu_id,full_name,nin,date_of_birth,gender,lga,ward',
            "{$socuId},Ladidi Ciroma,22200000011,12/03/1995,female,dutse,Limawa",
        ]);
    }

    /** A file the MDA collected itself — no external register, so no external id. */
    private function selfSourcedCsv(): string
    {
        return implode("\n", [
            'full_name,nin,date_of_birth,gender,lga,ward',
            'Amina Yusuf,22200000022,05/06/1990,female,dutse,Limawa',
        ]);
    }

    private function upload(User $as, string $csv, string $filename, ?string $source, ?Activity $activity = null): ImportBatch
    {
        $payload = [
            'file' => UploadedFile::fake()->createWithContent($filename, $csv),
            'activity_id' => ($activity ?? $this->activity)->id,
        ];
        if ($source !== null) {
            $payload['source'] = $source;
        }

        // BEFORE the request, not after: work between requests (committing a batch
        // creates Auditable models) resolves Auth::user() and caches the previous
        // officer, who would then be the one this upload authenticates as.
        $this->app['auth']->forgetGuards();

        $response = $this->withToken($as->createToken('t')->plainTextToken)
            ->post('/api/v1/beneficiaries/imports', $payload, ['Accept' => 'application/json'])
            ->assertCreated();

        return ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
    }

    private function send(User $as, string $method, string $url, array $body = []): TestResponse
    {
        $this->app['auth']->forgetGuards();

        return $this->withToken($as->createToken('t')->plainTextToken)->json($method, $url, $body);
    }

    /** @return array<string, string|null> */
    private function map(bool $withSourceId): array
    {
        return [
            'full_name' => 'full_name', 'first_name' => null, 'last_name' => null,
            'nin' => 'nin', 'bvn' => null, 'phone' => null,
            'date_of_birth' => 'date_of_birth', 'gender' => 'gender',
            'lga' => 'lga', 'ward' => 'ward',
            'original_record_id' => $withSourceId ? 'socu_id' : null,
        ];
    }

    private function commit(ImportBatch $batch, User $actor): void
    {
        ParseImportBatch::dispatchSync($batch->id);
        app(ImportCommitter::class)->commit($batch->fresh(), $actor);
    }

    // ------------------------------------------------------- the batch's source

    public function test_an_upload_can_be_tagged_as_socu_mined(): void
    {
        $batch = $this->upload($this->healthOfficer, $this->socuCsv(), 'socu-extract.csv', 'socu');

        $this->assertSame(RegistrationSource::Socu, $batch->source);
    }

    public function test_an_upload_defaults_to_the_mdas_own_file_when_no_source_is_given(): void
    {
        // Self-sourced is the ordinary case, inferred from the file itself. Nothing is
        // ever tagged SOCU by accident — claiming a national register as the origin has
        // to be a deliberate act.
        $batch = $this->upload($this->healthOfficer, $this->selfSourcedCsv(), 'our-own.csv', null);

        $this->assertNotSame(RegistrationSource::Socu, $batch->source);
        $this->assertSame(RegistrationSource::Csv, $batch->source);
    }

    // -------------------------------------------------- the per-RECORD source id

    public function test_a_socu_import_stores_the_socu_id_on_each_record(): void
    {
        $batch = $this->upload($this->healthOfficer, $this->socuCsv('SOCU-ABC-77'), 'socu.csv', 'socu');

        $this->send($this->healthOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->map(withSourceId: true),
        ])->assertOk();

        $this->commit($batch, $this->healthOfficer);

        $beneficiary = Beneficiary::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame(RegistrationSource::Socu, $beneficiary->registration_source);
        // Per RECORD, not per batch: the id identifies THIS person in SOCU.
        $this->assertSame('SOCU-ABC-77', $beneficiary->original_record_id);
    }

    public function test_each_row_keeps_its_own_socu_id(): void
    {
        // The batch flag is one value; the ids are many. Forcing one id per batch would
        // make every row claim to be the same SOCU record.
        $csv = implode("\n", [
            'socu_id,full_name,nin,date_of_birth,gender,lga,ward',
            'SOCU-1,Ladidi Ciroma,22200000011,12/03/1995,female,dutse,Limawa',
            'SOCU-2,Amina Yusuf,22200000022,05/06/1990,female,dutse,Limawa',
        ]);

        $batch = $this->upload($this->healthOfficer, $csv, 'socu-many.csv', 'socu');
        $this->send($this->healthOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->map(withSourceId: true),
        ])->assertOk();

        $this->commit($batch, $this->healthOfficer);

        $ids = Beneficiary::query()->withoutGlobalScopes()->orderBy('original_record_id')->pluck('original_record_id');
        $this->assertSame(['SOCU-1', 'SOCU-2'], $ids->all());
    }

    public function test_a_socu_import_cannot_be_confirmed_without_the_socu_id_mapped(): void
    {
        // Otherwise the batch claims a SOCU origin no row can be traced back to.
        $batch = $this->upload($this->healthOfficer, $this->socuCsv(), 'socu.csv', 'socu');

        $this->send($this->healthOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->map(withSourceId: false),
        ])
            ->assertStatus(422)
            ->assertJsonPath('error.message', fn (string $m): bool => str_contains($m, 'SOCU record id'));

        $this->assertNull($batch->fresh()->mapping_confirmed_at);
    }

    public function test_a_self_sourced_import_needs_no_external_id(): void
    {
        // An MDA's own field collection has no external register to point at. Demanding
        // one would make officers invent identifiers.
        $batch = $this->upload($this->healthOfficer, $this->selfSourcedCsv(), 'own.csv', null);

        $this->send($this->healthOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->map(withSourceId: false),
        ])->assertOk();

        $this->commit($batch, $this->healthOfficer);

        $beneficiary = Beneficiary::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertNull($beneficiary->original_record_id);
        $this->assertSame(RegistrationSource::Csv, $beneficiary->registration_source);
    }

    public function test_the_mapping_screen_is_told_when_a_source_record_id_is_required(): void
    {
        $socu = $this->upload($this->healthOfficer, $this->socuCsv(), 'socu.csv', 'socu');
        $this->send($this->healthOfficer, 'GET', "/api/v1/beneficiaries/imports/{$socu->id}/mapping")
            ->assertOk()
            ->assertJsonPath('data.source', 'socu')
            ->assertJsonPath('data.requires_source_record_id', true);

        $own = $this->upload($this->healthOfficer, $this->selfSourcedCsv(), 'own.csv', null);
        $this->send($this->healthOfficer, 'GET', "/api/v1/beneficiaries/imports/{$own->id}/mapping")
            ->assertOk()
            ->assertJsonPath('data.requires_source_record_id', false);
    }

    // ------------------------------------------- source and owner are SEPARATE

    public function test_a_socu_mined_record_is_owned_by_the_mda_that_imported_it(): void
    {
        // The heart of it: SOCU is where the data came from, not who owns it.
        $batch = $this->upload($this->healthOfficer, $this->socuCsv(), 'socu.csv', 'socu');
        $this->send($this->healthOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->map(withSourceId: true),
        ]);
        $this->commit($batch, $this->healthOfficer);

        $beneficiary = Beneficiary::query()->withoutGlobalScopes()->firstOrFail();

        $this->assertSame(RegistrationSource::Socu, $beneficiary->registration_source);
        $this->assertSame($this->health->id, $beneficiary->owner_mda_id, 'the first IMPORTER owns it');
        $this->assertSame('SOCU-000123', $beneficiary->original_record_id);
    }

    public function test_a_second_mda_mining_the_same_socu_record_does_not_become_the_owner(): void
    {
        /*
         * The rule this protects: two MDAs mine the same SOCU register. The first import
         * creates the record and owns it (FR-OWN-01). The second finds an exact NIN
         * duplicate — it must NOT create a second record, and must NOT take ownership.
         * A shared origin is not a shared claim.
         */
        // Built BEFORE any request: creating an MdaScoped model between requests stamps
        // it from the previously authenticated user, which would give Women's activity
        // to Health and make the second upload fail for the wrong reason.
        $womenOfficer = $this->officerFor($this->women);
        $womenProgramme = Programme::factory()->individual()->create();
        $womenActivity = Activity::factory()->forProgramme($womenProgramme, $this->women)->create();
        $this->assertSame($this->women->id, $womenActivity->owner_mda_id, 'fixture: activity must belong to Women Affairs');
        $this->app['auth']->forgetGuards();

        // First import — Health.
        $first = $this->upload($this->healthOfficer, $this->socuCsv(), 'socu-health.csv', 'socu');
        $this->send($this->healthOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", [
            'column_map' => $this->map(withSourceId: true),
        ]);
        $this->commit($first, $this->healthOfficer);

        $original = Beneficiary::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame($this->health->id, $original->owner_mda_id);

        // Second import — Women Affairs, same SOCU row, through the real upload path.
        $second = $this->upload($womenOfficer, $this->socuCsv(), 'socu-women.csv', 'socu', $womenActivity);
        $this->send($womenOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$second->id}/mapping", [
            'column_map' => $this->map(withSourceId: true),
        ])->assertOk();
        $this->commit($second, $womenOfficer);

        // Still one person, still owned by Health.
        $this->assertSame(1, Beneficiary::query()->withoutGlobalScopes()->count());
        $this->assertSame($this->health->id, $original->fresh()->owner_mda_id, 'SOCU provenance never moves ownership');
    }

    public function test_provenance_is_exposed_on_the_beneficiary_record(): void
    {
        $batch = $this->upload($this->healthOfficer, $this->socuCsv(), 'socu.csv', 'socu');
        $this->send($this->healthOfficer, 'PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->map(withSourceId: true),
        ]);
        $this->commit($batch, $this->healthOfficer);

        $beneficiary = Beneficiary::query()->withoutGlobalScopes()->firstOrFail();

        $this->send($this->healthOfficer, 'GET', "/api/v1/beneficiaries/{$beneficiary->id}")
            ->assertOk()
            ->assertJsonPath('data.registration_source', 'socu')
            ->assertJsonPath('data.original_record_id', 'SOCU-000123')
            // Owner is reported separately from origin, never derived from it.
            ->assertJsonPath('data.owner_mda_id', $this->health->id);
    }

    public function test_import_history_reports_the_batch_source(): void
    {
        $this->upload($this->healthOfficer, $this->socuCsv(), 'socu.csv', 'socu');

        $this->send($this->healthOfficer, 'GET', '/api/v1/beneficiaries/imports')
            ->assertOk()
            ->assertJsonPath('data.0.source', 'socu');
    }
}
