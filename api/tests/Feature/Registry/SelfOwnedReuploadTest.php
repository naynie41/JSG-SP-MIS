<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Enums\ExactMatchBehaviour;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * Re-uploading a beneficiary the uploading MDA ALREADY OWNS (FR-DUP-05, FR-OWN-06, §9).
 *
 * This is a dedup-RESOLUTION outcome, not new matching: the cascade already found the
 * person, and the only question is what to do about it. Ownership answers it. Against
 * another MDA's record the answer is a request-to-serve; against your own there is
 * nobody to ask — ServiceRequestService refuses a self-owned target outright, so the
 * old path did not merely produce an odd request, it threw mid-commit.
 *
 * What must happen instead: no second beneficiary, no service request, and the person
 * who is already there receives a NEW INTERVENTION under this batch's programme and
 * activity. The duplicate ROW is what gets blocked; the human being is not.
 */
class SelfOwnedReuploadTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    private Mda $mine;      // the uploading MDA

    private Mda $theirs;    // another MDA, for the cross-MDA control

    private User $officer;

    private Programme $programme;

    private Activity $activity;

    private Beneficiary $ownRecord;    // owned by $mine — the re-upload

    private Beneficiary $otherRecord;  // owned by $theirs — must still route to serve

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mine = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->theirs = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->officer = User::factory()->create([
            'mda_id' => $this->mine->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->programme = Programme::factory()->individual()->create();
        $this->activity = Activity::factory()->forProgramme($this->programme, $this->mine)->create();

        $this->ownRecord = Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->mine->id, 'nin' => '22200000011',
        ]);
        $this->otherRecord = Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->theirs->id, 'nin' => '44400000033',
        ]);
    }

    private function token(): string
    {
        return $this->officer->createToken('test')->plainTextToken;
    }

    /** Row 1 matches our OWN record by NIN; row 2 matches ANOTHER MDA's record by NIN. */
    private function upload(): string
    {
        $csv = implode("\n", [
            'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward',
            'Zainab,Aliyu,22200000011,,08030000001,1990-01-01,female,dutse,Ward 1',
            'Sadiq,Umar,44400000033,,08030000002,1991-02-02,male,ringim,Ward 1',
        ]);

        $upload = $this->withToken($this->token())
            ->post('/api/v1/beneficiaries/imports', [
                'file' => UploadedFile::fake()->createWithContent('reupload.csv', $csv),
                'activity_id' => $this->activity->id,
            ], ['Accept' => 'application/json'])
            ->assertCreated();
        $this->app['auth']->forgetGuards();

        $batchId = (string) $upload->json('data.id');
        $this->confirmImportMapping(
            ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batchId)
        );

        return $batchId;
    }

    private function resolve(string $batchId, int $row, array $body): TestResponse
    {
        $response = $this->withToken($this->token())
            ->postJson("/api/v1/beneficiaries/imports/{$batchId}/rows/{$row}/resolve", $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function confirm(string $batchId): void
    {
        $this->withToken($this->token())
            ->postJson("/api/v1/beneficiaries/imports/{$batchId}/confirm")
            ->assertOk();
        $this->app['auth']->forgetGuards();
    }

    private function batch(string $batchId): ImportBatch
    {
        return ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batchId);
    }

    /* ------------------------------------------------- the preview tells them apart */

    public function test_the_preview_says_which_matches_are_already_in_your_registry(): void
    {
        $batchId = $this->upload();

        $rows = $this->withToken($this->token())
            ->getJson("/api/v1/beneficiaries/imports/{$batchId}")
            ->assertOk()
            ->json('data.rows');
        $this->app['auth']->forgetGuards();

        $byNumber = collect($rows)->keyBy('row_number');

        // Same match band, opposite outcome — which is exactly why the officer cannot be
        // asked to work it out from the band alone.
        $this->assertTrue($byNumber[1]['match']['candidates'][0]['owned_by_you']);
        $this->assertFalse($byNumber[2]['match']['candidates'][0]['owned_by_you']);
    }

    /* --------------------------------------------------------- the self-owned outcome */

    public function test_re_uploading_a_record_you_own_creates_no_duplicate_and_no_service_request(): void
    {
        $batchId = $this->upload();
        $before = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count();

        $this->resolve($batchId, 1, ['resolution' => 'own', 'beneficiary_id' => $this->ownRecord->id])
            ->assertOk()
            ->assertJsonPath('data.resolution', 'own');

        $this->resolve($batchId, 2, ['resolution' => 'skip']);
        $this->confirm($batchId);

        $this->assertSame(
            $before,
            Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count(),
            'the person is already in the registry, so a second record is the bug this prevents',
        );
        $this->assertSame(
            0,
            ServiceRequest::query()->count(),
            'an MDA does not request permission to serve its own beneficiary',
        );
    }

    public function test_the_existing_person_receives_a_new_intervention_under_the_activity(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 1, ['resolution' => 'own', 'beneficiary_id' => $this->ownRecord->id])->assertOk();
        $this->resolve($batchId, 2, ['resolution' => 'skip']);
        $this->confirm($batchId);

        // The point of the outcome: blocking the duplicate ROW must not block the
        // DELIVERY. Re-uploading own data usually means delivering again.
        $enrollment = Enrollment::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('beneficiary_id', $this->ownRecord->id)
            ->firstOrFail();

        $this->assertSame($this->programme->id, $enrollment->programme_id);
        $this->assertSame($this->activity->id, $enrollment->activity_id);
        $this->assertSame($this->mine->id, $enrollment->mda_id);
    }

    public function test_an_own_match_is_counted_apart_from_discarded_rows(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 1, ['resolution' => 'own', 'beneficiary_id' => $this->ownRecord->id])->assertOk();
        $this->resolve($batchId, 2, ['resolution' => 'skip']);
        $this->confirm($batchId);

        $batch = $this->batch($batchId);

        // "Skipped" would tell the officer nothing happened, when in fact a delivery was
        // recorded. The completion notification is usually the only report they read.
        $this->assertSame(1, $batch->own_rows);
        $this->assertSame(0, $batch->committed_rows);
        $this->assertSame(0, $batch->served_rows);
        $this->assertSame(1, $batch->skipped_rows);
    }

    /* ------------------------------------------------- the two are not interchangeable */

    public function test_request_to_serve_is_refused_against_your_own_record(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 1, ['resolution' => 'link', 'beneficiary_id' => $this->ownRecord->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'ALREADY_OWNED');
    }

    public function test_already_owned_is_refused_against_another_mdas_record(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 2, ['resolution' => 'own', 'beneficiary_id' => $this->otherRecord->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'NOT_OWNED');
    }

    /* ------------------------------------------------------ cross-MDA is unaffected */

    public function test_a_cross_mda_match_still_routes_to_request_to_serve(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 1, ['resolution' => 'skip']);
        $this->resolve($batchId, 2, ['resolution' => 'link', 'beneficiary_id' => $this->otherRecord->id])->assertOk();
        $this->confirm($batchId);

        $serve = ServiceRequest::query()->firstOrFail();
        $this->assertSame($this->otherRecord->id, $serve->beneficiary_id);
        $this->assertSame($this->mine->id, $serve->from_mda_id);
        $this->assertSame($this->theirs->id, $serve->to_mda_id);
        $this->assertSame('pending', $serve->status->value);

        // Ownership never moves, and no intervention is recorded before approval.
        $this->assertSame($this->theirs->id, $this->otherRecord->fresh()->owner_mda_id);
        $this->assertSame(0, Enrollment::query()->withoutGlobalScope(MdaScope::class)
            ->where('beneficiary_id', $this->otherRecord->id)->count());
    }

    /* ------------------------------------------------------------ auto-link behaviour */

    public function test_auto_link_pre_resolves_a_self_owned_exact_match_as_already_owned(): void
    {
        MatchingConfig::query()->update(['exact_match_behaviour' => ExactMatchBehaviour::AutoLink->value]);

        $batchId = $this->upload();
        $rows = $this->batch($batchId)->rows()->orderBy('row_number')->get()->keyBy('row_number');

        // Auto-linking a self-owned match would queue a request-to-serve against
        // ourselves, which the service refuses outright: the batch would preview clean
        // and then throw at commit.
        $this->assertSame('own', $rows[1]->resolution);
        $this->assertSame($this->ownRecord->id, $rows[1]->resolved_beneficiary_id);

        $this->assertSame('link', $rows[2]->resolution);
        $this->assertSame($this->otherRecord->id, $rows[2]->resolved_beneficiary_id);
    }

    public function test_a_row_stored_as_link_against_your_own_record_commits_without_raising_one(): void
    {
        // The shape of the bug that existed before own-matches were modelled: auto-link
        // could persist LINK against a self-owned record. Committing threw a
        // DomainException mid-chunk and took the whole batch down with it, so the
        // committer decides by ownership rather than by the stored label.
        $batchId = $this->upload();
        $batch = $this->batch($batchId);
        $batch->rows()->where('row_number', 1)->update([
            'resolution' => 'link',
            'resolved_beneficiary_id' => $this->ownRecord->id,
        ]);
        $batch->rows()->where('row_number', 2)->update(['resolution' => 'skip']);

        $this->confirm($batchId);

        $this->assertSame('completed', $this->batch($batchId)->status->value);
        $this->assertSame(0, ServiceRequest::query()->count());
        $this->assertSame('own', (string) $batch->rows()->where('row_number', 1)->value('resolution'));
    }
}
