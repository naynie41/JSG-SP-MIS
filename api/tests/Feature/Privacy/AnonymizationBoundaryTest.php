<?php

declare(strict_types=1);

namespace Tests\Feature\Privacy;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Privacy\Services\AnonymizationService;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\BatchDuplicateScreener;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

/**
 * The boundaries of anonymization (NFR-PRV-01) — the invariants that decide whether
 * erasure is real, which {@see AnonymizationIntegrityTest} does not reach.
 *
 * Three questions, each of which can silently invert the whole exercise:
 *
 *  1. Does the audit entry ABOUT the erasure retain what was erased? An append-only
 *     trail that captures the name you just removed has not de-identified anybody — it
 *     has moved the PII somewhere immutable.
 *  2. Does `aggregate` mode keep enough to still be statistically useful? Its whole
 *     purpose is de-identified statistics; if the quasi fields go too it is just a
 *     slower `anonymize`.
 *  3. Can the duplicate matcher re-identify the record afterwards? Clearing the
 *     lookup hashes is necessary, but the fuzzy blocking key must go too.
 */
class AnonymizationBoundaryTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->users['owner'] = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function subject(array $overrides = []): Beneficiary
    {
        return Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'first_name' => 'Amina',
            'middle_name' => 'Ngozi',
            'last_name' => 'Bello',
            'nin' => '22200000031',
            'bvn' => '33300000032',
            'phone' => '08039999901',
            'address' => '14 Sokoto Road, Dutse',
            'date_of_birth' => '1988-03-04',
            'gender' => 'female',
            'lga' => 'dutse',
            'ward' => 'limawa',
            ...$overrides,
        ]);
    }

    private function anonymize(Beneficiary $beneficiary, bool $keepQuasi = false): void
    {
        app(AnonymizationService::class)->anonymize(
            $beneficiary,
            keepQuasi: $keepQuasi,
            policyKey: 'test-policy',
            actor: $this->users['owner'],
            reason: 'Retention period elapsed',
        );
    }

    private function send(string $method, string $url): TestResponse
    {
        $response = $this->withToken($this->users['owner']->createToken('t')->plainTextToken)->json($method, $url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------- 1. the trail must not retain the erasure */

    public function test_the_anonymization_audit_entry_carries_no_erased_identifier(): void
    {
        $subject = $this->subject();
        $erased = ['Amina', 'Ngozi', 'Bello', '22200000031', '33300000032', '08039999901', 'Sokoto Road'];

        $this->anonymize($subject);

        $entry = AuditLog::query()
            ->where('action', 'beneficiary.anonymized')
            ->where('entity_id', $subject->id)
            ->latest('id')
            ->firstOrFail();

        $payload = (string) json_encode([$entry->before, $entry->after]);

        foreach ($erased as $value) {
            $this->assertStringNotContainsString(
                $value,
                $payload,
                "the anonymization entry must not preserve '{$value}' — the audit log is append-only, so anything recorded here can never be removed",
            );
        }

        // It must still say what happened, or the erasure is unaccountable.
        $this->assertSame('anonymize', $entry->after['mode'] ?? null);
        $this->assertSame('test-policy', $entry->after['policy'] ?? null);
        $this->assertSame($this->users['owner']->id, $entry->actor_id);
    }

    public function test_no_audit_entry_anywhere_captures_the_erased_values_afterwards(): void
    {
        $subject = $this->subject();
        $this->anonymize($subject);

        // The quiet write matters for this reason: an ordinary `updated` diff would
        // record the old name and NIN as `before` values, immutably.
        $all = (string) json_encode(
            AuditLog::query()->where('entity_id', $subject->id)->get()->map(
                fn (AuditLog $row): array => [$row->action, $row->before, $row->after],
            )->all(),
        );

        // Identifiers are masked by the global `audit.mask` list; the NAME columns are
        // masked by Beneficiary::auditMask() instead, because the global list names
        // `full_name` while the table stores first/middle/last separately. Both layers
        // are asserted here so removing either is caught.
        foreach (['22200000031', '33300000032', '08039999901', 'Amina', 'Ngozi', 'Bello'] as $value) {
            $this->assertStringNotContainsString($value, $all, "no audit row may retain '{$value}'");
        }
    }

    /* --------------------------- 2. aggregate mode stays statistically useful */

    public function test_aggregate_mode_keeps_the_quasi_fields_that_statistics_need(): void
    {
        $subject = $this->subject();
        $this->anonymize($subject, keepQuasi: true);

        $fresh = $subject->fresh();

        // Direct identifiers gone…
        $this->assertNull($fresh->nin);
        $this->assertNull($fresh->phone);
        $this->assertNotSame('Amina', $fresh->first_name);

        // …but the de-identified dimensions remain, which is the entire point of
        // `aggregate` as distinct from `anonymize`.
        $this->assertSame('dutse', $fresh->lga);
        $this->assertSame('limawa', $fresh->ward);
        $this->assertSame('female', $fresh->gender?->value ?? $fresh->gender);
        $this->assertNotNull($fresh->date_of_birth);
    }

    public function test_a_full_anonymize_removes_the_quasi_fields_too(): void
    {
        $subject = $this->subject();
        $this->anonymize($subject, keepQuasi: false);

        $fresh = $subject->fresh();
        $this->assertNull($fresh->lga);
        $this->assertNull($fresh->ward);
        $this->assertNull($fresh->date_of_birth);
    }

    public function test_an_aggregated_record_still_counts_in_lga_statistics(): void
    {
        $subject = $this->subject();
        $programme = Programme::factory()->individual()->create();
        Benefit::factory()->create([
            'beneficiary_id' => $subject->id,
            'programme_id' => $programme->id,
            'mda_id' => $this->mda->id,
            'lga' => 'dutse',
            'monetary_value' => 250_000,
        ]);

        $this->anonymize($subject, keepQuasi: true);

        // The delivery stays attributable to an LGA on both sides of the join, so
        // de-identified coverage reporting still has a denominator to group by.
        $this->assertSame('dutse', $subject->fresh()->lga);
        $this->assertDatabaseHas('benefits', ['beneficiary_id' => $subject->id, 'lga' => 'dutse']);
        $this->assertSame(
            250_000,
            (int) Benefit::query()->where('lga', 'dutse')->sum('monetary_value'),
            'the anonymized subject’s delivery must still count toward its LGA',
        );
    }

    /* ----------------------------- 3. the matcher can never re-identify it */

    public function test_the_duplicate_matcher_cannot_match_an_anonymized_record(): void
    {
        $subject = $this->subject();
        $this->anonymize($subject);

        /** @var MatchingConfig $config */
        $config = app(MatchingConfigService::class)->activeOrNull();
        $this->assertNotNull($config, 'the matcher needs an active configuration for this to mean anything');

        // The SAME person arriving again must not resolve to the de-identified row —
        // clearing nin_hash/bvn_hash is not enough on its own, the fuzzy blocking key
        // (block_name_dob) has to go too or the name+DOB pair still finds it.
        $match = app(BatchDuplicateScreener::class)->screen([
            'first_name' => 'Amina',
            'middle_name' => 'Ngozi',
            'last_name' => 'Bello',
            'nin' => '22200000031',
            'bvn' => '33300000032',
            'phone' => '08039999901',
            'date_of_birth' => '1988-03-04',
            'lga' => 'dutse',
            'ward' => 'limawa',
        ], 1, $config);

        $references = collect($match['candidates'] ?? [])
            ->where('type', 'registry')
            ->pluck('reference')
            ->all();

        $this->assertNotContains($subject->id, $references, 'an anonymized record must never be re-identified by a later import');
    }

    public function test_the_blocking_key_is_cleared_so_a_rebuild_cannot_restore_it(): void
    {
        $subject = $this->subject();
        $this->anonymize($subject);

        $raw = DB::table('beneficiaries')->where('id', $subject->id)->first();

        // Derived from name + DOB; if it survived, the pair could be brute-forced back.
        $this->assertNull($raw->block_name_dob);
        $this->assertNull($raw->nin_hash);
        $this->assertNull($raw->bvn_hash);
    }

    /* ------------------------------------ the record stops being PII-bearing */

    public function test_an_access_request_on_an_anonymized_subject_returns_no_identifiers(): void
    {
        $subject = $this->subject();
        $this->anonymize($subject);

        $response = $this->send('GET', "/api/v1/beneficiaries/{$subject->id}/access-request");
        $status = $response->baseResponse->getStatusCode();

        if ($status === 200) {
            // The DSAR export streams, so read the streamed body rather than json().
            $body = $response->baseResponse instanceof StreamedResponse
                ? $response->streamedContent()
                : (string) $response->getContent();

            // Right-of-access must not resurrect what retention erased.
            foreach (['22200000031', '33300000032', '08039999901'] as $identifier) {
                $this->assertStringNotContainsString($identifier, $body);
            }
        } else {
            // A refusal is equally acceptable — there is no subject left to serve.
            $this->assertContains($status, [404, 409, 422]);
        }
    }

    public function test_anonymization_is_recorded_against_the_policy_that_caused_it(): void
    {
        $subject = $this->subject();
        $this->anonymize($subject);

        // Which policy erased this record is the question a DPO audit actually asks.
        $this->assertSame('test-policy', $subject->fresh()->retention_policy);
        $this->assertNotNull($subject->fresh()->anonymized_at);
    }
}
