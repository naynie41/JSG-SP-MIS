<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportMappingTemplate;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The Data Import & Mapping layer (CLAUDE.md §11, PRD v1.7).
 *
 * The rule this exists to enforce: a machine guess about which column holds the NIN must
 * never reach the duplicate cascade unreviewed. A wrong identity mapping does not fail
 * loudly — it produces a confident, wrong answer, declaring two different citizens to be
 * the same person. So suggestions are advisory, confirmation is mandatory on every
 * import, and a saved template pre-fills the proposal without ever satisfying the guard.
 */
class ImportMappingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Activity $activity;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->users['officer'] = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $programme = Programme::factory()->individual()->create();
        $this->activity = Activity::factory()->forProgramme($programme, $this->mda)->create();
    }

    /** @param array<string, mixed> $body */
    private function send(string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users['officer']->createToken('t')->plainTextToken)
            ->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** An MDA file whose headers are its OWN, not SP-MIS's canonical names. */
    private function mdaShapedCsv(): string
    {
        return implode("\n", [
            'Surname,Given Name,National ID,Mobile,Date of Birth,Sex,LGA,Ward,Ref',
            'Okoye,Ada,22200000011,0803 123 4567,12/03/1995,female,dutse,Ward 1,EXT-1',
        ]);
    }

    private function upload(?string $csv = null, string $filename = 'cohort.csv'): ImportBatch
    {
        $response = $this->withToken($this->users['officer']->createToken('t')->plainTextToken)
            ->post(
                '/api/v1/beneficiaries/imports',
                [
                    'file' => UploadedFile::fake()->createWithContent($filename, $csv ?? $this->mdaShapedCsv()),
                    'activity_id' => $this->activity->id,
                ],
                ['Accept' => 'application/json'],
            )->assertCreated();

        $this->app['auth']->forgetGuards();

        return ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
    }

    /** A complete, valid mapping for {@see mdaShapedCsv()}. */
    private function goodMap(): array
    {
        return [
            'first_name' => 'given_name',
            'last_name' => 'surname',
            // This file has separate name columns, so the single full-name column is
            // explicitly absent — an answer, not a blank (CLAUDE.md §11).
            'full_name' => null,
            'nin' => 'national_id',
            'bvn' => null,          // explicitly not present in this file
            'phone' => 'mobile',
            'date_of_birth' => 'date_of_birth',
            'gender' => 'sex',
            'lga' => 'lga',
            'ward' => 'ward',
            'original_record_id' => 'ref',
        ];
    }

    /* ------------------------------------------------- detection + suggestion */

    public function test_upload_detects_the_columns_and_waits_for_a_mapping(): void
    {
        $batch = $this->upload();

        // Nothing is parsed yet: an unconfirmed identity mapping must not reach dedup.
        $this->assertSame(ImportStatus::MappingRequired, $batch->status);
        $this->assertSame(0, $batch->rows()->count());
        $this->assertNull($batch->mapping_confirmed_at);

        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$batch->id}/mapping")->assertOk()->json('data');

        $this->assertContains('surname', $data['detected_headers']);
        $this->assertContains('national_id', $data['detected_headers']);
    }

    public function test_it_suggests_mappings_without_applying_them(): void
    {
        $batch = $this->upload();
        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$batch->id}/mapping")->assertOk()->json('data');

        // Suggested…
        $this->assertSame('surname', $data['suggestions']['last_name']['header']);
        $this->assertSame('mobile', $data['suggestions']['phone']['header']);

        // …but NOT applied: the confirmed map is still empty and every identity field
        // is still outstanding. A suggestion is advice, not a decision.
        $this->assertSame([], $data['column_map']);
        $this->assertEqualsCanonicalizing(
            // `full_name` is confirmation-required too: a single "Name" column IS the
            // identity, so it must never be applied without a person saying so.
            ['first_name', 'last_name', 'full_name', 'nin', 'bvn', 'phone'],
            $data['unconfirmed_identity_fields'],
        );
    }

    public function test_an_ambiguous_identity_header_is_suggested_with_low_confidence(): void
    {
        $batch = $this->upload();
        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$batch->id}/mapping")->assertOk()->json('data');

        // "National ID" is used for a NIN, a voter's card and a state ID. Suggesting it
        // confidently is how the wrong column becomes a definitive identity match.
        $this->assertSame('national_id', $data['suggestions']['nin']['header']);
        $this->assertSame('low', $data['suggestions']['nin']['confidence']);
    }

    public function test_it_returns_sample_values_so_the_confirmation_is_a_real_decision(): void
    {
        $batch = $this->upload();
        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$batch->id}/mapping")->assertOk()->json('data');

        // Whether `National ID` holds NINs is guesswork from the header and obvious from
        // the values. Without these the confirmation degrades into a click-through.
        $this->assertSame(['22200000011'], $data['samples']['national_id']);
        $this->assertSame(['0803 123 4567'], $data['samples']['mobile']);
    }

    public function test_it_previews_the_normalized_value_beside_the_original(): void
    {
        $batch = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$batch->id}/mapping")->assertOk()->json('data');
        $byField = collect($data['normalized_preview'])->keyBy('field');

        // A wrong mapping is visible HERE and invisible afterwards: a date read as the
        // wrong month, or a "NIN" that does not reduce to eleven digits.
        $this->assertSame('0803 123 4567', $byField['phone']['original']);
        $this->assertSame('08031234567', $byField['phone']['normalized']);
        $this->assertSame('12/03/1995', $byField['date_of_birth']['original']);
        $this->assertSame('1995-03-12', $byField['date_of_birth']['normalized']);
    }

    /* -------------------------------------------------- the identity guard */

    public function test_the_mapping_cannot_be_confirmed_while_an_identity_field_is_unanswered(): void
    {
        $batch = $this->upload();
        $map = $this->goodMap();
        unset($map['nin']); // never answered — not the same as "not present"

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $map])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MAPPING_INCOMPLETE');

        $this->assertNull($batch->fresh()->mapping_confirmed_at);
        $this->assertSame(0, $batch->rows()->count());
    }

    public function test_marking_an_identity_field_not_present_is_a_valid_answer(): void
    {
        $batch = $this->upload();

        // The file genuinely has no BVN column. Saying so explicitly is an answer;
        // silence is not.
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $this->assertNotNull($batch->fresh()->mapping_confirmed_at);
        $this->assertNull($batch->fresh()->column_map['bvn']);
    }

    public function test_the_preview_cannot_proceed_until_the_mapping_is_confirmed(): void
    {
        $batch = $this->upload();

        // Even dispatched directly, the job refuses — the gate is in the pipeline, not
        // in a controller, so no upload door can go around it.
        ParseImportBatch::dispatchSync($batch->id);

        $batch->refresh();
        $this->assertSame(ImportStatus::MappingRequired, $batch->status);
        $this->assertSame(0, $batch->rows()->count());
    }

    public function test_a_mapping_naming_a_column_the_file_lacks_is_refused(): void
    {
        $batch = $this->upload();
        $map = [...$this->goodMap(), 'ward' => 'village'];

        // Usually a stale template against a changed export. Mapping to a missing column
        // would look identical to a source that simply omitted the field.
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $map])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'MAPPING_INCOMPLETE');
    }

    public function test_a_field_outside_the_canonical_schema_is_rejected(): void
    {
        $batch = $this->upload();

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => [...$this->goodMap(), 'favourite_colour' => 'sex'],
        ])->assertStatus(422);
    }

    /* ------------------------------------------------ mapping + normalization */

    public function test_a_confirmed_mapping_transforms_and_normalizes_the_rows(): void
    {
        $batch = $this->upload();

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $batch->refresh();
        $this->assertSame(ImportStatus::PreviewReady, $batch->status);
        $this->assertSame(1, $batch->total_rows);

        $row = $batch->rows()->firstOrFail();
        // Mapped from the MDA's own header names onto the canonical schema…
        $this->assertSame('Ada', $row->payload['first_name']);
        $this->assertSame('Okoye', $row->payload['last_name']);
        $this->assertSame('22200000011', $row->payload['nin']);
        // …and DM.1 normalization applied: 12/03/1995 read day-first, not month-first.
        $this->assertSame('1995-03-12', substr((string) $row->payload['date_of_birth'], 0, 10));
        $this->assertTrue($row->is_valid);
    }

    public function test_the_raw_file_is_never_mutated(): void
    {
        $batch = $this->upload();
        $before = Storage::disk('local')->get($batch->stored_path);

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        // The canonical representation lives in import_rows; the upload is read-only.
        $this->assertSame($before, Storage::disk('local')->get($batch->fresh()->stored_path));
        $this->assertStringContainsString('National ID', (string) $before);
    }

    public function test_the_written_phone_survives_the_mapping(): void
    {
        $batch = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();
        $this->send('POST', "/api/v1/beneficiaries/imports/{$batch->id}/confirm")->assertSuccessful();

        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->firstOrFail();
        $this->assertSame('0803 123 4567', $beneficiary->phone, 'the source spelling is preserved');
        $this->assertSame('08031234567', $beneficiary->phone_normalized);
    }

    /* --------------------------------------------------------------- templates */

    public function test_a_confirmed_mapping_can_be_saved_as_a_template(): void
    {
        $batch = $this->upload();

        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->goodMap(),
            'save_template_as' => 'Health monthly returns',
        ])->assertOk();

        $template = ImportMappingTemplate::withoutGlobalScopes()->firstOrFail();
        $this->assertSame('Health monthly returns', $template->name);
        $this->assertSame($this->mda->id, $template->owner_mda_id);
        $this->assertSame($batch->fresh()->source_signature, $template->source_signature);
    }

    public function test_a_template_pre_fills_the_next_upload_but_never_pre_confirms_it(): void
    {
        $first = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", [
            'column_map' => $this->goodMap(),
            'save_template_as' => 'Health monthly returns',
        ])->assertOk();

        // A second file of the SAME shape.
        $second = $this->upload();
        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$second->id}/mapping")->assertOk()->json('data');

        // Pre-filled…
        $this->assertSame('national_id', $data['column_map']['nin']);
        $this->assertSame('Health monthly returns', $data['template']['name']);

        // …but NOT confirmed. This is the rule: a template saves typing, never judgement.
        $this->assertNull($data['mapping_confirmed_at']);
        $this->assertNull($second->fresh()->mapping_confirmed_at);
        $this->assertSame(ImportStatus::MappingRequired, $second->fresh()->status);
        $this->assertSame(0, $second->rows()->count());
    }

    public function test_a_pre_filled_batch_still_requires_an_explicit_confirmation_call(): void
    {
        $first = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", [
            'column_map' => $this->goodMap(), 'save_template_as' => 'T',
        ])->assertOk();

        $second = $this->upload();
        ParseImportBatch::dispatchSync($second->id);

        // Pre-filled but unconfirmed — the job still refuses.
        $this->assertSame(ImportStatus::MappingRequired, $second->fresh()->status);
    }

    public function test_a_changed_file_format_does_not_reuse_the_old_template(): void
    {
        $first = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", [
            'column_map' => $this->goodMap(), 'save_template_as' => 'Health monthly returns',
        ])->assertOk();

        // The MDA changes its export: NIN column renamed, a new column added.
        $changed = implode("\n", [
            'Surname,Given Name,NIN Number,Mobile,Date of Birth,Sex,LGA,Ward,Ref,Disability',
            'Okoye,Ada,22200000011,0803 123 4567,12/03/1995,female,dutse,Ward 1,EXT-1,none',
        ]);
        $second = $this->upload($changed, 'changed.csv');

        $this->assertNotSame($first->fresh()->source_signature, $second->source_signature);

        $data = $this->send('GET', "/api/v1/beneficiaries/imports/{$second->id}/mapping")->assertOk()->json('data');

        // No template offered, nothing pre-filled — the officer re-maps rather than
        // having a stale mapping applied to columns that moved.
        $this->assertNull($data['template']);
        $this->assertSame([], $data['column_map']);
        $this->assertContains('nin', $data['unconfirmed_identity_fields']);
    }

    public function test_re_confirming_the_same_shape_updates_the_template_in_place(): void
    {
        $first = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", [
            'column_map' => $this->goodMap(), 'save_template_as' => 'First name',
        ])->assertOk();

        $second = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$second->id}/mapping", [
            'column_map' => [...$this->goodMap(), 'address' => null],
            'save_template_as' => 'Renamed',
        ])->assertOk();

        $this->assertSame(1, ImportMappingTemplate::withoutGlobalScopes()->count(), 'one template per file shape');
        $this->assertSame('Renamed', ImportMappingTemplate::withoutGlobalScopes()->firstOrFail()->name);
    }

    /* ------------------------------------------------------------------ audit */

    public function test_the_confirmation_is_audited_with_the_identity_mapping(): void
    {
        $batch = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        $entry = AuditLog::query()->where('action', 'import.mapping_confirmed')->firstOrFail();

        $this->assertSame($this->users['officer']->id, $entry->actor_id);
        // Which column was declared to hold the NIN is the reviewable decision — this is
        // what makes a wrong identity mapping answerable after the fact.
        $this->assertSame('national_id', $entry->after['identity_fields']['nin'] ?? null);
        $this->assertSame('not present', $entry->after['identity_fields']['bvn'] ?? null);
    }

    public function test_saving_a_template_is_audited(): void
    {
        $batch = $this->upload();
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$batch->id}/mapping", [
            'column_map' => $this->goodMap(), 'save_template_as' => 'Health monthly returns',
        ])->assertOk();

        $this->assertSame(1, AuditLog::query()->where('action', 'import.mapping_template_saved')->count());
    }

    /* ------------------------------------------- recognising a familiar layout */

    /**
     * The ordinary case: the same MDA uploads another export of the same shape, having
     * never saved a named template. Recognising the layout must not depend on somebody
     * having thought to save one.
     */
    public function test_a_second_file_of_the_same_shape_is_pre_filled_from_the_last_confirmed_import(): void
    {
        $first = $this->upload(filename: 'january.csv');
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", ['column_map' => $this->goodMap()])
            ->assertOk();

        // No template was ever saved — this is what makes the fallback necessary.
        $this->assertSame(0, ImportMappingTemplate::query()->withoutGlobalScopes()->count());

        $second = $this->upload(filename: 'february.csv');

        $this->assertSame($first->source_signature, $second->source_signature, 'same layout, same signature');
        $this->assertSame($this->goodMap(), $second->column_map);
        $this->assertSame($first->id, $second->mapping_prefilled_from_id);
    }

    /** Pre-filled is not confirmed — the §11 guard survives the convenience. */
    public function test_a_pre_filled_mapping_still_has_to_be_confirmed(): void
    {
        $first = $this->upload(filename: 'january.csv');
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", ['column_map' => $this->goodMap()]);

        $second = $this->upload(filename: 'february.csv');

        $this->assertNull($second->mapping_confirmed_at);
        $this->assertSame(ImportStatus::MappingRequired, $second->status);

        // ...and the job refuses to parse it until a person confirms.
        ParseImportBatch::dispatchSync($second->id);
        $this->assertSame(ImportStatus::MappingRequired, $second->fresh()->status);
    }

    /** The review screen names where the pre-fill came from. */
    public function test_the_proposal_reports_the_earlier_import_it_recognised(): void
    {
        $first = $this->upload(filename: 'january.csv');
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", ['column_map' => $this->goodMap()]);

        $second = $this->upload(filename: 'february.csv');

        $this->send('GET', "/api/v1/beneficiaries/imports/{$second->id}/mapping")
            ->assertOk()
            ->assertJsonPath('data.prefilled_from.type', 'previous_import')
            ->assertJsonPath('data.prefilled_from.name', 'january.csv')
            ->assertJsonPath('data.prefilled_from.confirmed_by', $this->users['officer']->name)
            ->assertJsonPath('data.template', null);
    }

    /** A named template still wins — it is the deliberate artefact. */
    public function test_a_saved_template_takes_precedence_over_the_previous_import(): void
    {
        $first = $this->upload(filename: 'january.csv');
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", [
            'column_map' => $this->goodMap(),
            'save_template_as' => 'MoH monthly export',
        ])->assertOk();

        $second = $this->upload(filename: 'february.csv');

        $this->assertNotNull($second->mapping_template_id);
        $this->assertNull($second->mapping_prefilled_from_id);

        $this->send('GET', "/api/v1/beneficiaries/imports/{$second->id}/mapping")
            ->assertOk()
            ->assertJsonPath('data.prefilled_from.type', 'template')
            ->assertJsonPath('data.prefilled_from.name', 'MoH monthly export');
    }

    /** An abandoned mapping is not a decision anyone made. */
    public function test_an_unconfirmed_earlier_batch_is_not_used_as_a_source(): void
    {
        $this->upload(filename: 'abandoned.csv'); // never confirmed

        $second = $this->upload(filename: 'february.csv');

        $this->assertSame([], $second->column_map);
        $this->assertNull($second->mapping_prefilled_from_id);
    }

    /** A different file shape is not "familiar" — the signature has to match exactly. */
    public function test_a_different_layout_is_not_pre_filled(): void
    {
        $first = $this->upload(filename: 'january.csv');
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", ['column_map' => $this->goodMap()]);

        $other = $this->upload(
            "Full Name,NIN,Phone\nAda Okoye,22200000011,08031234567",
            'different-shape.csv',
        );

        $this->assertNotSame($first->source_signature, $other->source_signature);
        $this->assertSame([], $other->column_map);
        $this->assertNull($other->mapping_prefilled_from_id);
    }

    /**
     * Another MDA's mapping decision is not evidence about this MDA's file.
     *
     * Two agencies can export identical column headers and mean different things by
     * `national_id`; borrowing across the boundary would also leak one MDA's working
     * practice into another's screen.
     */
    public function test_another_mdas_confirmed_mapping_is_never_borrowed(): void
    {
        // Built BEFORE any request: creating an Auditable model between requests
        // resolves Auth::user() against the previous one and caches it, which would
        // authenticate this upload as the wrong MDA.
        $otherMda = Mda::factory()->create(['name' => 'Ministry of Education']);
        $otherUser = User::factory()->create([
            'mda_id' => $otherMda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
        $otherProgramme = Programme::factory()->individual()->create();
        $otherActivity = Activity::factory()->forProgramme($otherProgramme, $otherMda)->create();
        $otherToken = $otherUser->createToken('t')->plainTextToken;

        $first = $this->upload(filename: 'january.csv');
        $this->send('PUT', "/api/v1/beneficiaries/imports/{$first->id}/mapping", ['column_map' => $this->goodMap()]);

        $this->app['auth']->forgetGuards();
        $response = $this->withToken($otherToken)
            ->post('/api/v1/beneficiaries/imports', [
                'file' => UploadedFile::fake()->createWithContent('theirs.csv', $this->mdaShapedCsv()),
                'activity_id' => $otherActivity->id,
            ], ['Accept' => 'application/json'])->assertCreated();
        $this->app['auth']->forgetGuards();

        $theirs = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));

        $this->assertSame($first->source_signature, $theirs->source_signature, 'identical headers');
        $this->assertSame([], $theirs->column_map, 'but no mapping borrowed across MDAs');
        $this->assertNull($theirs->mapping_prefilled_from_id);
    }
}
