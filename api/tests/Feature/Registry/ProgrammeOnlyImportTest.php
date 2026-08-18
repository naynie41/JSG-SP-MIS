<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
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
 * Programme-first import (revises the activity-first rule, CLAUDE.md §9).
 *
 * An upload names a catalog PROGRAMME; an activity is optional. Registering people under
 * a programme is a complete act — the enrollment records that they are on it, with a null
 * activity when none applies. Requiring an activity made officers invent placeholder
 * activities, and a placeholder is a worse record than an honest absence.
 */
class ProgrammeOnlyImportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

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

        $this->programme = Programme::factory()->individual()->create(['name' => 'Cash Transfer']);
    }

    private function csv(): string
    {
        return implode("\n", [
            'first_name,last_name,nin,date_of_birth,gender,lga,ward',
            'Ada,Okoye,22200000011,12/03/1995,female,dutse,Ward 1',
        ]);
    }

    /** @param array<string, mixed> $extra */
    private function upload(array $extra): TestResponse
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)
            ->post('/api/v1/beneficiaries/imports', [
                'file' => UploadedFile::fake()->createWithContent('cohort.csv', $this->csv()),
                ...$extra,
            ], ['Accept' => 'application/json']);

        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function send(string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** @return array<string, string|null> */
    private function goodMap(): array
    {
        return [
            'first_name' => 'first_name', 'last_name' => 'last_name', 'nin' => 'nin',
            'bvn' => null, 'phone' => null, 'full_name' => null,
            'date_of_birth' => 'date_of_birth', 'gender' => 'gender', 'lga' => 'lga', 'ward' => 'ward',
        ];
    }

    // ------------------------------------------------------------- the new door

    public function test_an_upload_with_a_programme_and_no_activity_is_accepted(): void
    {
        $response = $this->upload(['programme_id' => $this->programme->id])->assertCreated();

        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
        $this->assertNull($batch->activity_id);
        $this->assertSame($this->programme->id, $batch->programme_id);
        $this->assertSame($this->programme->id, $response->json('data.programme_id'));
    }

    public function test_an_upload_with_neither_a_programme_nor_an_activity_is_refused(): void
    {
        // Something must say what these people are being registered under.
        $this->upload([])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'programme_id']);
    }

    public function test_an_activity_alone_is_still_enough(): void
    {
        // Existing callers are unaffected: an activity names its programme, so asking for
        // both would be asking the caller to repeat a fact the server can read.
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)->create();

        $response = $this->upload(['activity_id' => $activity->id])->assertCreated();

        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
        $this->assertSame($activity->id, $batch->activity_id);
        $this->assertNull($batch->programme_id, 'the programme is read through the activity, not duplicated');
        $this->assertSame($this->programme->id, $response->json('data.programme_id'));
    }

    public function test_a_contradiction_between_programme_and_activity_is_refused(): void
    {
        // An activity belongs to exactly one programme. Accepting a mismatch would leave
        // the batch claiming one programme while its enrollments land in another.
        $other = Programme::factory()->individual()->create(['name' => 'Food Support']);
        $activity = Activity::factory()->forProgramme($other, $this->mda)->create();

        $this->upload(['programme_id' => $this->programme->id, 'activity_id' => $activity->id])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'activity_id']);
    }

    public function test_another_mdas_activity_is_still_refused(): void
    {
        $otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);
        $theirs = Activity::factory()->forProgramme($this->programme, $otherMda)->create();

        $this->upload(['programme_id' => $this->programme->id, 'activity_id' => $theirs->id])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'activity_id']);
    }

    // --------------------------------------------------------- commit behaviour

    public function test_a_programme_only_import_registers_people_and_enrolls_them_with_no_activity(): void
    {
        $response = $this->upload(['programme_id' => $this->programme->id])->assertCreated();
        $batchId = $response->json('data.id');

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batchId}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        ParseImportBatch::dispatchSync($batchId);
        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batchId);
        $this->assertSame(ImportStatus::PreviewReady, $batch->status);

        app(ImportCommitter::class)->commit($batch->fresh(), $this->officer);

        // The person is on the registry...
        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->firstOrFail();
        $this->assertSame($this->mda->id, $beneficiary->owner_mda_id);

        // ...and enrolled on the programme, with NO activity. That is the whole point:
        // "on this programme" is true and recorded; "under this activity" is not known.
        $enrollment = Enrollment::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame($this->programme->id, $enrollment->programme_id);
        $this->assertNull($enrollment->activity_id);
        $this->assertSame($beneficiary->id, $enrollment->beneficiary_id);
    }

    public function test_a_batch_naming_neither_cannot_be_committed(): void
    {
        // Defence in depth: the upload refuses this, so reaching commit means something
        // wrote the row directly. It must not silently produce registry entries with no
        // programme attached.
        $batch = ImportBatch::query()->create([
            'owner_mda_id' => $this->mda->id,
            'uploaded_by' => $this->officer->id,
            'original_filename' => 'x.csv',
            'stored_path' => 'imports/x.csv',
            'source' => 'excel',
            'status' => ImportStatus::PreviewReady,
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/neither a programme nor an activity/');

        app(ImportCommitter::class)->commit($batch, $this->officer);
    }

    public function test_an_activity_bound_import_still_enrolls_under_that_activity(): void
    {
        // The existing behaviour must be untouched by the new path.
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)->create();

        $response = $this->upload(['activity_id' => $activity->id])->assertCreated();
        $batchId = $response->json('data.id');

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batchId}/mapping", ['column_map' => $this->goodMap()]);
        ParseImportBatch::dispatchSync($batchId);

        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batchId);
        app(ImportCommitter::class)->commit($batch->fresh(), $this->officer);

        $enrollment = Enrollment::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame($activity->id, $enrollment->activity_id);
        $this->assertSame($this->programme->id, $enrollment->programme_id);
    }
}
