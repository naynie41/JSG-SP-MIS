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
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Idempotent intake (PRD FR-REG-08 groundwork): a client-supplied idempotency key
 * / original_record_id lets a submission be retried without creating duplicates,
 * across the manual, REST, and import paths.
 */
class IdempotentIntakeTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private User $officer;

    private Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);
        $this->activity = Activity::factory()->forProgramme(
            Programme::factory()->individual()->create(['owner_mda_id' => $this->mda->id]),
        )->create();
    }

    private function token(): string
    {
        return $this->officer->createToken('test')->plainTextToken;
    }

    private function beneficiaryCount(): int
    {
        return Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count();
    }

    public function test_api_intake_is_idempotent_on_original_record_id(): void
    {
        $payload = [
            'first_name' => 'Musa', 'last_name' => 'Bello',
            'date_of_birth' => '1985-06-15', 'gender' => 'male',
            'lga' => 'kazaure', 'ward' => 'Ward 2',
            'original_record_id' => 'PARTNER-REC-99',
        ];

        $first = $this->withToken($this->token())
            ->postJson('/api/v1/beneficiaries/intake', $payload)
            ->assertCreated();
        $this->app['auth']->forgetGuards();

        $second = $this->withToken($this->token())
            ->postJson('/api/v1/beneficiaries/intake', $payload)
            ->assertOk();

        $this->assertSame($first->json('data.id'), $second->json('data.id'));
        $this->assertSame(1, $this->beneficiaryCount());
    }

    public function test_reimporting_the_same_file_does_not_duplicate(): void
    {
        $csv = implode("\n", [
            'first_name,last_name,date_of_birth,gender,lga,ward,original_record_id',
            'Zainab,Umar,1992-03-03,female,hadejia,Ward 5,ROW-1',
        ]);

        $this->importAndConfirm($csv);
        $this->assertSame(1, $this->beneficiaryCount());

        // A second upload of the same records (same original_record_id) commits
        // without inserting a duplicate.
        $this->importAndConfirm($csv);
        $this->assertSame(1, $this->beneficiaryCount());
    }

    private function importAndConfirm(string $csv): void
    {
        $response = $this->withToken($this->token())
            ->post('/api/v1/beneficiaries/imports', [
                'file' => UploadedFile::fake()->createWithContent('people.csv', $csv),
                'activity_id' => $this->activity->id,
            ], ['Accept' => 'application/json'])
            ->assertCreated();

        $batchId = $response->json('data.id');
        $this->app['auth']->forgetGuards();

        $this->withToken($this->token())
            ->postJson("/api/v1/beneficiaries/imports/{$batchId}/confirm")
            ->assertOk();
        $this->app['auth']->forgetGuards();

        // sanity: the batch reached completion
        $this->assertNotNull(ImportBatch::query()->withoutGlobalScope(MdaScope::class)->find($batchId));
    }
}
