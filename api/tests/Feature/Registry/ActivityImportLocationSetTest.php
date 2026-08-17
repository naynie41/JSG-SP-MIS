<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The activity WIZARD's upload path carries the location set through a multipart request.
 *
 * That is a different transport from the JSON create path, and it is where a nested array
 * can silently arrive as a string — so it gets its own tests.
 */
class ActivityImportLocationSetTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    private Programme $programme;

    private Lga $dutse;

    private Ward $limawa;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);

        $mda = Mda::factory()->create();
        $this->officer = User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->programme = Programme::factory()->create();
        $this->dutse = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        $this->limawa = Ward::factory()->create(['lga_id' => $this->dutse->id, 'code' => 'limawa', 'name' => 'Limawa']);
    }

    private function file(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'people.csv',
            "first_name,last_name,lga\nAmina,Sule,dutse\n",
        );
    }

    /**
     * The browser sends multipart, so the nested set arrives as bracketed keys. Laravel
     * parses those back into an array — this is the shape the wizard must produce.
     *
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'programme_id' => $this->programme->id,
            'name' => 'Wizard activity',
            'target_beneficiaries' => 10,
            'locations' => [
                ['lga_id' => $this->dutse->id, 'ward_ids' => [$this->limawa->id]],
            ],
            'file' => $this->file(),
        ], $overrides);
    }

    public function test_the_wizard_upload_accepts_a_location_set(): void
    {
        $this->actingAs($this->officer)
            ->post('/api/v1/activity-imports', $this->payload())
            ->assertCreated();

        // Stashed on the draft, not yet an activity.
        $batch = ImportBatch::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame(0, Activity::query()->withoutGlobalScopes()->count());
        $this->assertSame(
            [['lga_id' => $this->dutse->id, 'ward_ids' => [$this->limawa->id]]],
            $batch->draft_activity['locations'] ?? null,
        );
    }

    /**
     * The regression this file exists for.
     *
     * A FormData body that stringifies the set sends the literal "[object Object]".
     * The server correctly rejects it, but the wizard had no way to show WHY — the user
     * saw only "The request is invalid." on the upload step.
     */
    public function test_a_stringified_location_set_is_rejected_with_a_field_error(): void
    {
        $this->actingAs($this->officer)
            ->post('/api/v1/activity-imports', $this->payload(['locations' => '[object Object]']))
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonFragment(['field' => 'locations']);
    }

    public function test_a_ward_from_another_lga_is_rejected_at_preview_not_at_confirm(): void
    {
        $kiyawa = Lga::factory()->forEnum(LgaEnum::Kiyawa)->create();
        $kwanda = Ward::factory()->create(['lga_id' => $kiyawa->id, 'code' => 'kwanda', 'name' => 'Kwanda']);

        // Reported now, before the file is parsed and previewed — failing at confirm would
        // be the worst possible moment to tell the user.
        $this->actingAs($this->officer)
            ->post('/api/v1/activity-imports', $this->payload([
                'locations' => [['lga_id' => $this->dutse->id, 'ward_ids' => [$kwanda->id]]],
            ]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'locations.0.ward_ids.0']);

        $this->assertSame(0, ImportBatch::query()->withoutGlobalScopes()->count());
    }

    public function test_the_wizard_upload_works_without_any_locations(): void
    {
        $payload = $this->payload();
        unset($payload['locations']);

        $this->actingAs($this->officer)
            ->post('/api/v1/activity-imports', $payload)
            ->assertCreated();
    }

    public function test_whole_lga_survives_the_multipart_boolean_round_trip(): void
    {
        // Multipart has no booleans — everything is a string. Laravel's `boolean` rule
        // accepts "1"/"0" but NOT "true"/"false", so the client must send "1"; sending
        // "true" was the second half of the wizard's 422. The server stays strict here to
        // match every other boolean in this API rather than special-casing one field.
        $this->actingAs($this->officer)
            ->post('/api/v1/activity-imports', $this->payload([
                'locations' => [['lga_id' => $this->dutse->id, 'whole_lga' => '1']],
            ]))
            ->assertCreated();

        $batch = ImportBatch::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame($this->dutse->id, $batch->draft_activity['locations'][0]['lga_id']);

        // And the string "true" is rejected — with a field error the form can point at,
        // which is the part that was missing.
        $this->actingAs($this->officer)
            ->post('/api/v1/activity-imports', $this->payload([
                'locations' => [['lga_id' => $this->dutse->id, 'whole_lga' => 'true']],
            ]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'locations.0.whole_lga']);
    }
}
