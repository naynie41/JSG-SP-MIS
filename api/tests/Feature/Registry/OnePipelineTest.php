<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Registry\Services\ImportCommitter;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * ONE flow, multiple entry points (CLAUDE.md §9, FR-REG-11).
 *
 * A beneficiary file can arrive two ways — inline in the activity-creation wizard
 * (`POST /activity-imports`, the activity does not exist yet) or through the Import
 * Center against an activity that already exists (`POST /beneficiaries/imports`). Two
 * endpoints, because the two moments genuinely differ; but they must be thin doors onto
 * ONE pipeline, or validation, duplicate screening and provenance would drift between
 * the primary and secondary path.
 *
 * This asserts the pipeline is shared by OBSERVING it: the same file uploaded through
 * each door must be parsed by the same rules, screened by the same matcher into the same
 * bands, and committed by the same {@see ImportCommitter}
 * into records carrying the same provenance.
 */
class OnePipelineTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    /**
     * Row 1 carries a NIN so a seeded record with the same NIN produces a DETERMINISTIC
     * exact match. Fuzzy scoring would depend on the configured weights (name + phone +
     * LGA sum to 0.65, below the 0.75 review threshold), which would make the screening
     * assertion a test of the thresholds rather than of the pipeline being shared.
     */
    private const FILE = "first_name,last_name,nin,phone,lga\nAisha,Bello,12345678901,08030000001,dutse\nMusa,Sani,,08030000002,dutse\n";

    private Mda $mda;

    private Programme $programme;

    private Activity $activity;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->programme = Programme::factory()->individual()->create(['status' => 'active']);
        $this->activity = Activity::factory()->forProgramme($this->programme, $this->mda)
            ->create(['name' => 'Existing activity', 'involves_beneficiaries' => true]);
    }

    /** @param array<string, mixed> $body */
    private function send(string $url, array $body): TestResponse
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)
            ->post($url, $body, ['Accept' => 'application/json']);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function file(string $name = 'rows.csv', ?string $content = null): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, $content ?? self::FILE);
    }

    /**
     * Two people nobody has seen before. Used where the point is the COMMIT: sending the
     * same names through both doors would make the second upload a duplicate of whatever
     * the first one just created — correct pipeline behaviour, but it would measure the
     * dedup cascade instead of the shared committer.
     */
    private function distinctFile(string $tag): string
    {
        return "first_name,last_name,phone,lga\n{$tag}One,Danjuma,0803000{$tag}1,dutse\n{$tag}Two,Garba,0803000{$tag}2,dutse\n";
    }

    /** Entry point A — the activity-creation wizard's inline upload (primary path). */
    private function uploadViaWizard(string $name = 'Wizard activity', ?string $content = null): ImportBatch
    {
        $id = $this->send('/api/v1/activity-imports', [
            'programme_id' => $this->programme->id,
            'name' => $name,
            'target_beneficiaries' => 2,
            'lga' => 'dutse',
            'file' => $this->file('rows.csv', $content),
        ])->assertSuccessful()->json('data.id');

        return $this->confirmImportMapping(
            ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($id)
        )->load('rows');
    }

    /** Entry point B — the Import Center, bound to an activity that already exists. */
    private function uploadViaImportCenter(?string $content = null): ImportBatch
    {
        $id = $this->send('/api/v1/beneficiaries/imports', [
            'activity_id' => $this->activity->id,
            'source' => 'csv',
            'file' => $this->file('rows.csv', $content),
        ])->assertSuccessful()->json('data.id');

        return $this->confirmImportMapping(
            ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($id)
        )->load('rows');
    }

    /* --------------------------------------------- the same mapping stage (§11) */

    public function test_both_doors_stop_for_the_same_mapping_confirmation(): void
    {
        // Uploaded through each door, WITHOUT confirming a mapping.
        $wizardId = $this->send('/api/v1/activity-imports', [
            'programme_id' => $this->programme->id,
            'name' => 'Wizard activity',
            'target_beneficiaries' => 2,
            'lga' => 'dutse',
            'file' => $this->file('rows.csv'),
        ])->assertSuccessful()->json('data.id');

        $centreId = $this->send('/api/v1/beneficiaries/imports', [
            'activity_id' => $this->activity->id,
            'source' => 'csv',
            'file' => $this->file('rows.csv'),
        ])->assertSuccessful()->json('data.id');

        foreach ([$wizardId, $centreId] as $id) {
            $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($id);

            // Both doors profile the columns and stop. Neither parses, screens or stages
            // a single row until a human has said which column holds the NIN.
            $this->assertSame(ImportStatus::MappingRequired, $batch->status);
            $this->assertNull($batch->mapping_confirmed_at);
            $this->assertNotEmpty($batch->detected_headers);
            $this->assertSame(0, $batch->rows()->count());
        }
    }

    public function test_the_gate_holds_even_when_the_parse_job_is_dispatched_directly(): void
    {
        $id = $this->send('/api/v1/beneficiaries/imports', [
            'activity_id' => $this->activity->id,
            'source' => 'csv',
            'file' => $this->file('rows.csv'),
        ])->assertSuccessful()->json('data.id');

        // The guard lives in the JOB, not in either controller — which is what makes it
        // impossible for a future third entry point to bypass it by construction.
        ParseImportBatch::dispatchSync($id);

        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($id);
        $this->assertSame(ImportStatus::MappingRequired, $batch->status);
        $this->assertSame(0, $batch->rows()->count());
    }

    public function test_both_doors_record_the_mapping_they_were_read_with(): void
    {
        $wizard = $this->uploadViaWizard();
        $centre = $this->uploadViaImportCenter();

        foreach ([$wizard, $centre] as $batch) {
            $this->assertNotNull($batch->mapping_confirmed_at);
            $this->assertNotNull($batch->mapping_confirmed_by);
            $this->assertNotEmpty($batch->column_map);
            $this->assertNotNull($batch->source_signature);
        }

        // Same file shape through either door ⇒ same signature, so one saved template
        // serves both. The doors differ in WHEN the activity binds, nothing else.
        $this->assertSame($wizard->source_signature, $centre->source_signature);
    }

    /* ------------------------------------------------- the same parse + validation */

    public function test_both_entry_points_stage_the_same_rows(): void
    {
        $wizard = $this->uploadViaWizard();
        $centre = $this->uploadViaImportCenter();

        // Same file, same row count, same validity verdicts — one parser.
        $this->assertSame(2, $wizard->rows->count());
        $this->assertSame(2, $centre->rows->count());
        $this->assertSame(
            $wizard->rows->pluck('is_valid')->all(),
            $centre->rows->pluck('is_valid')->all(),
        );
        $this->assertSame(
            $wizard->rows->map(fn (ImportRow $r): mixed => $r->payload['last_name'] ?? null)->all(),
            $centre->rows->map(fn (ImportRow $r): mixed => $r->payload['last_name'] ?? null)->all(),
        );
    }

    public function test_both_entry_points_reach_the_same_preview_state(): void
    {
        // Whichever door was used, the batch lands awaiting confirmation — the state in
        // which rows can be adjudicated.
        $this->assertSame('preview_ready', $this->uploadViaWizard()->status->value);
        $this->assertSame('preview_ready', $this->uploadViaImportCenter()->status->value);
    }

    /* --------------------------------------------------- the same duplicate screening */

    public function test_both_entry_points_screen_against_the_same_registry(): void
    {
        // An existing record sharing row 1's NIN — a definitive identifier hit.
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'first_name' => 'Aisha',
            'last_name' => 'Bello',
            'nin' => '12345678901',
            'phone' => '08030000001',
            'lga' => 'dutse',
        ]);

        $wizardBands = $this->uploadViaWizard()->rows->pluck('match_band')->all();
        $centreBands = $this->uploadViaImportCenter()->rows->pluck('match_band')->all();

        // The same matcher, the same thresholds, therefore the same verdicts. If the
        // secondary path had its own screening these would diverge.
        $this->assertSame($wizardBands, $centreBands);
        // …and the verdict is a real flag, not two matching absences of one.
        $this->assertContains(
            MatchBand::Exact->value,
            $wizardBands,
            'the seeded duplicate must actually be flagged, or this test proves nothing',
        );
    }

    /* ----------------------------------------------------- the same commit + provenance */

    public function test_both_entry_points_commit_through_the_same_committer(): void
    {
        $wizard = $this->uploadViaWizard('Committed via wizard', $this->distinctFile('44'));
        $centre = $this->uploadViaImportCenter($this->distinctFile('55'));

        $this->send("/api/v1/activity-imports/{$wizard->id}/confirm", [])->assertSuccessful();
        $this->send("/api/v1/beneficiaries/imports/{$centre->id}/confirm", [])->assertSuccessful();

        foreach ([$wizard, $centre] as $batch) {
            $fresh = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batch->id);
            $this->assertSame('completed', $fresh->status->value, 'both doors must reach the same terminal state');
            $this->assertSame(2, $fresh->committed_rows);
        }
    }

    public function test_a_record_carries_the_same_provenance_whichever_door_it_came_through(): void
    {
        $wizard = $this->uploadViaWizard('Committed via wizard', $this->distinctFile('66'));
        $this->send("/api/v1/activity-imports/{$wizard->id}/confirm", [])->assertSuccessful();

        $centre = $this->uploadViaImportCenter($this->distinctFile('77'));
        $this->send("/api/v1/beneficiaries/imports/{$centre->id}/confirm", [])->assertSuccessful();

        $created = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->get();
        $this->assertCount(4, $created, 'two rows through each door');

        foreach ($created as $beneficiary) {
            // Provenance is what makes bulk-only intake defensible (§9) — every record
            // knows the batch it came from and the MDA that owns it, regardless of door.
            $this->assertNotNull($beneficiary->import_batch_id);
            $this->assertSame($this->mda->id, $beneficiary->owner_mda_id);
        }

        $this->assertEqualsCanonicalizing(
            [$wizard->id, $wizard->id, $centre->id, $centre->id],
            $created->pluck('import_batch_id')->all(),
        );
    }

    public function test_the_wizard_creates_its_activity_on_confirm_and_binds_the_rows(): void
    {
        $wizard = $this->uploadViaWizard('Created on confirm');
        $this->assertNull($wizard->activity_id, 'the wizard batch is unbound until confirm');

        $this->send("/api/v1/activity-imports/{$wizard->id}/confirm", [])->assertSuccessful();

        // The one behaviour the two doors do NOT share — and the only reason there are
        // two endpoints rather than one.
        $activity = Activity::query()->withoutGlobalScope(MdaScope::class)
            ->where('name', 'Created on confirm')->first();

        $this->assertNotNull($activity, 'confirm must atomically create the drafted activity');
        $this->assertSame($this->mda->id, $activity->owner_mda_id);
        $this->assertTrue($activity->involves_beneficiaries);
    }

    /* ------------------------------------------------------------ same scope rules */

    public function test_neither_door_accepts_another_mdas_activity(): void
    {
        $otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);
        $theirActivity = Activity::factory()->forProgramme($this->programme, $otherMda)
            ->create(['involves_beneficiaries' => true]);

        $this->send('/api/v1/beneficiaries/imports', [
            'activity_id' => $theirActivity->id,
            'file' => $this->file(),
        ])->assertStatus(422);
    }

    public function test_the_import_center_requires_a_programme_the_wizard_supplies_itself(): void
    {
        // The asymmetry is deliberate and unchanged in shape, only in which field: the
        // wizard IS creating the activity (and so knows its programme), while the Import
        // Center must be told the programme. Binding an activity there is now optional.
        $response = $this->send('/api/v1/beneficiaries/imports', ['file' => $this->file()])
            ->assertStatus(422);

        $fields = array_column($response->json('error.details'), 'field');
        $this->assertContains('programme_id', $fields);
        $this->assertNotContains('activity_id', $fields);
    }
}
