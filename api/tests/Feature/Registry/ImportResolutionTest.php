<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ServeRequest;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Duplicate decision + request-to-serve (PRD FR-DUP-05/06, FR-OWN-03): each
 * flagged row is resolved new/link/skip; link raises a serve request without
 * changing ownership; every decision is audited; commit creates only "new" rows.
 */
class ImportResolutionTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA; // importer

    private Mda $mdaB; // owner of the existing records

    /** @var array<string, User> */
    private array $users = [];

    private Beneficiary $existing1; // matched exactly by NIN (row 1)

    private Beneficiary $existing2; // matched fuzzily (row 2)

    private Beneficiary $existing3; // matched exactly by NIN (row 3)

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->users['officer'] = $this->user($this->mdaA, RoleKey::MdaOfficer);   // importer
        $this->users['ownerAdmin'] = $this->user($this->mdaB, RoleKey::MdaAdmin);  // can accept serve

        $this->existing1 = Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaB->id, 'nin' => '22200000011']);
        $this->existing2 = Beneficiary::factory()->withoutNin()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaB->id, 'first_name' => 'Musa', 'last_name' => 'Bello',
            'date_of_birth' => '1985-06-15', 'phone' => '08052222222', 'lga' => 'gumel', 'ward' => 'Ward 2',
        ]);
        $this->existing3 = Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaB->id, 'nin' => '44400000033']);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function token(string $key): string
    {
        return $this->users[$key]->createToken('test')->plainTextToken;
    }

    private function upload(): string
    {
        $csv = implode("\n", [
            'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward',
            'Zed,Person,22200000011,,08000000000,1999-01-01,male,dutse,Ward 1',    // row1 exact vs existing1 (NIN)
            'Musa,Bello,,,08052222222,1985-06-15,male,gumel,Ward 9',               // row2 probable vs existing2 (fuzzy)
            'Grace,Okoro,44400000033,,08000000001,1990-03-03,female,ringim,Ward 1', // row3 exact vs existing3 (NIN)
            'Amina,Yusuf,55500000044,,08000000002,1992-05-05,female,kazaure,Ward 2', // row4 unique
        ]);

        $upload = $this->withToken($this->token('officer'))
            ->post('/api/v1/beneficiaries/imports', ['file' => UploadedFile::fake()->createWithContent('people.csv', $csv)], ['Accept' => 'application/json'])
            ->assertCreated();
        $this->app['auth']->forgetGuards();

        return $upload->json('data.id');
    }

    private function resolve(string $batchId, int $row, array $body): TestResponse
    {
        $response = $this->withToken($this->token('officer'))
            ->postJson("/api/v1/beneficiaries/imports/{$batchId}/rows/{$row}/resolve", $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function confirm(string $batchId): void
    {
        $this->withToken($this->token('officer'))
            ->postJson("/api/v1/beneficiaries/imports/{$batchId}/confirm")
            ->assertOk();
        $this->app['auth']->forgetGuards();
    }

    public function test_new_resolution_requires_a_justification(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 2, ['resolution' => 'new'])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonFragment(['field' => 'note']);

        $this->resolve($batchId, 2, ['resolution' => 'new', 'note' => 'Confirmed a distinct person after review'])
            ->assertOk()
            ->assertJsonPath('data.resolution', 'new')
            ->assertJsonPath('data.resolution_note', 'Confirmed a distinct person after review');
    }

    public function test_link_creates_a_serve_request_without_changing_ownership(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 1, ['resolution' => 'link', 'beneficiary_id' => $this->existing1->id])
            ->assertOk()
            ->assertJsonPath('data.resolution', 'link');

        $this->confirm($batchId);

        // A serve request routed to the owner MDA, ownership unchanged, no new record.
        $serve = ServeRequest::query()->firstOrFail();
        $this->assertSame($this->existing1->id, $serve->beneficiary_id);
        $this->assertSame($this->mdaA->id, $serve->from_mda_id);
        $this->assertSame($this->mdaB->id, $serve->to_mda_id);
        $this->assertSame('pending', $serve->status->value);

        $this->assertSame($this->mdaB->id, $this->existing1->fresh()->owner_mda_id, 'Ownership must not change');
    }

    public function test_link_rejects_a_beneficiary_that_is_not_a_match_candidate(): void
    {
        $batchId = $this->upload();
        $stranger = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);

        $this->resolve($batchId, 1, ['resolution' => 'link', 'beneficiary_id' => $stranger->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'INVALID_MATCH');
    }

    public function test_confirm_commits_only_new_rows_and_records_the_tallies(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 1, ['resolution' => 'link', 'beneficiary_id' => $this->existing1->id])->assertOk();
        $this->resolve($batchId, 2, ['resolution' => 'new', 'note' => 'Distinct person'])->assertOk();
        $this->resolve($batchId, 3, ['resolution' => 'skip'])->assertOk();
        // row 4 is not flagged → defaults to new.

        $this->confirm($batchId);

        // 3 pre-existing + row2 (new) + row4 (new) = 5; linked/skipped created nothing.
        $this->assertSame(5, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());
        $this->assertSame(1, ServeRequest::query()->count());

        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batchId);
        $this->assertSame(2, $batch->committed_rows);
        $this->assertSame(1, $batch->served_rows);
        $this->assertSame(1, $batch->skipped_rows);
    }

    public function test_every_decision_is_audited_with_actor_choice_and_justification(): void
    {
        $batchId = $this->upload();

        $this->resolve($batchId, 2, ['resolution' => 'new', 'note' => 'Reviewed and distinct'])->assertOk();

        $this->assertDatabaseHas('audit_log', [
            'action' => 'import.row_resolved',
            'actor_id' => $this->users['officer']->id,
        ]);

        $entry = AuditLog::query()->where('action', 'import.row_resolved')->latest('created_at')->firstOrFail();
        $this->assertSame('new', $entry->after['resolution']);
        $this->assertSame('Reviewed and distinct', $entry->after['justification']);
    }

    public function test_owner_accepts_a_serve_request_and_ownership_stays_put(): void
    {
        $batchId = $this->upload();
        $this->resolve($batchId, 1, ['resolution' => 'link', 'beneficiary_id' => $this->existing1->id])->assertOk();
        $this->confirm($batchId);
        $serve = ServeRequest::query()->firstOrFail();

        // The requester (MDA A officer) may not accept its own request.
        $this->withToken($this->token('officer'))
            ->postJson("/api/v1/serve-requests/{$serve->id}/accept")
            ->assertStatus(403);
        $this->app['auth']->forgetGuards();

        // The owner MDA accepts → serve access granted, ownership unchanged, audited.
        $this->withToken($this->token('ownerAdmin'))
            ->postJson("/api/v1/serve-requests/{$serve->id}/accept", ['reason' => 'Approved for programme X'])
            ->assertOk()
            ->assertJsonPath('data.status', 'accepted');

        $this->assertSame($this->mdaB->id, $this->existing1->fresh()->owner_mda_id);
        $this->assertDatabaseHas('audit_log', ['action' => 'serve_request.accepted', 'entity_id' => $serve->id]);
    }

    public function test_deterministic_auto_link_config_pre_resolves_exact_rows(): void
    {
        MatchingConfig::query()->update(['exact_match_behaviour' => 'auto_link']);

        $batchId = $this->upload();

        // Row 1 (exact NIN match) is auto-resolved to link — no manual step.
        $this->withToken($this->token('officer'))
            ->getJson("/api/v1/beneficiaries/imports/{$batchId}")
            ->assertOk()
            ->assertJsonPath('data.rows.0.match.band', 'exact')
            ->assertJsonPath('data.rows.0.resolution', 'link');
        $this->app['auth']->forgetGuards();

        $this->confirm($batchId);

        $this->assertSame(1, ServeRequest::query()->where('beneficiary_id', $this->existing1->id)->count());
    }
}
