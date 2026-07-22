<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Support\IdentifierHasher;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Testing\TestResponse;
use RuntimeException;
use Tests\TestCase;

/**
 * Phase 7 security hardening controls (SECURITY.md, NFR-SEC-01/02, NFR-AUD-01):
 * encryption of national identifiers at rest, the tamper-evident audit hash chain,
 * rate limits on the riskiest endpoint classes, MDA-scoping bypass attempts, and
 * deny-by-default RBAC.
 */
class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['adminA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['roleless'] = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* -------------------------------------------- A02: identifiers encrypted at rest */

    public function test_nin_and_bvn_are_encrypted_at_rest_and_matched_by_keyed_hash(): void
    {
        $beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'nin' => '31100000012',
            'bvn' => '41100000013',
        ]);

        // The raw stored column values are ciphertext, never the plaintext digits.
        $raw = DB::table('beneficiaries')->where('id', $beneficiary->id)->first();
        $this->assertNotSame('31100000012', $raw->nin);
        $this->assertStringNotContainsString('31100000012', (string) $raw->nin);
        $this->assertNotSame('41100000013', $raw->bvn);

        // The deterministic keyed hashes support exact matching + uniqueness.
        $this->assertSame(IdentifierHasher::hash('31100000012'), $raw->nin_hash);
        $this->assertSame(IdentifierHasher::hash('41100000013'), $raw->bvn_hash);

        // And the model decrypts transparently for permitted readers.
        $this->assertSame('31100000012', $beneficiary->fresh()->nin);

        // The exact-match lookup/serve seam still finds the record (FR-OWN-03).
        $this->send('officerB', 'GET', '/api/v1/beneficiaries/lookup?nin=31100000012')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    /* ------------------------------------------------- NFR-AUD-01: audit hash chain */

    public function test_audit_entries_are_hash_chained_and_verifiable(): void
    {
        // Three real audited actions → three chained entries.
        Beneficiary::factory()->count(3)->create(['owner_mda_id' => $this->mdaA->id]);

        $entries = AuditLog::query()->whereNotNull('chain_position')->orderBy('chain_position')->get();
        $this->assertGreaterThanOrEqual(3, $entries->count());

        // Positions are 1..n with genesis-linked start and intact links.
        $this->assertSame(1, (int) $entries->first()->chain_position);
        $this->assertSame(AuditLog::GENESIS_HASH, $entries->first()->prev_hash);
        for ($i = 1; $i < $entries->count(); $i++) {
            $this->assertSame($entries[$i - 1]->entry_hash, $entries[$i]->prev_hash);
            $this->assertSame((int) $entries[$i - 1]->chain_position + 1, (int) $entries[$i]->chain_position);
        }
        foreach ($entries as $entry) {
            $this->assertSame($entry->computeEntryHash(), $entry->entry_hash);
        }

        // The verifier agrees.
        $this->assertSame(0, Artisan::call('audit:verify-chain'));
        $this->assertStringContainsString('intact', Artisan::output());
    }

    public function test_tampering_with_an_audit_row_is_detected_by_the_verifier(): void
    {
        Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id]);

        // Forge a row the way an attacker with raw DB access would (the Eloquent
        // guard + pgsql triggers block ordinary paths; sqlite tests bypass via DB).
        $victim = AuditLog::query()->whereNotNull('chain_position')->orderBy('chain_position')->first();
        DB::table('audit_log')->where('id', $victim->id)->update(['action' => 'forged.action']);

        $this->assertSame(1, Artisan::call('audit:verify-chain'));
        $this->assertStringContainsString('TAMPER EVIDENT', Artisan::output());
    }

    public function test_audit_rows_refuse_update_and_delete_in_application_code(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $entry = AuditLog::query()->firstOrFail();

        try {
            $entry->update(['action' => 'rewritten']);
            $this->fail('audit_log update should have thrown');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('append-only', $e->getMessage());
        }

        try {
            $entry->delete();
            $this->fail('audit_log delete should have thrown');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('append-only', $e->getMessage());
        }
    }

    /* ----------------------------------------------- A01: MDA scoping bypass attempts */

    public function test_direct_id_access_to_another_mdas_beneficiary_is_denied_and_logged(): void
    {
        $foreign = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // MDA B has no grant → 404 (not 403 — existence is never leaked) + audited.
        $this->send('officerB', 'GET', "/api/v1/beneficiaries/{$foreign->id}")->assertStatus(404);
        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.access_denied',
            'entity_id' => $foreign->id,
            'actor_id' => $this->users['officerB']->id,
        ]);

        // Scoped-list bypass attempt: filtering by the foreign owner MDA returns nothing.
        $this->send('officerB', 'GET', '/api/v1/beneficiaries?filter[owner_mda_id]='.$this->mdaA->id)
            ->assertOk()
            ->assertJsonCount(0, 'data');

        // Export inherits the same scope — MDA B's export never contains A's record.
        $csv = $this->withToken($this->users['officerB']->createToken('t')->plainTextToken)
            ->get('/api/v1/beneficiaries/export?format=csv');
        $this->app['auth']->forgetGuards();
        if ($csv->getStatusCode() === 200) {
            $this->assertStringNotContainsString($foreign->id, $csv->streamedContent());
        } else {
            $csv->assertStatus(403); // officer without export grant is denied outright
        }
    }

    /* --------------------------------------------------- FR-UAM-05: deny by default */

    public function test_a_user_with_no_role_is_denied_everywhere(): void
    {
        foreach ([
            ['GET', '/api/v1/beneficiaries'],
            ['GET', '/api/v1/programmes'],
            ['GET', '/api/v1/reports'],
            ['GET', '/api/v1/sync/connectors'],
            ['GET', '/api/v1/graduation-events'],
            ['GET', '/api/v1/beneficiaries/export?format=csv'],
        ] as [$method, $url]) {
            $this->send('roleless', $method, $url)->assertStatus(403);
        }
    }

    /* ------------------------------------------------------- A04/A07: rate limiting */

    public function test_export_endpoint_is_rate_limited_per_user(): void
    {
        RateLimiter::clear('exports|'.$this->users['adminA']->id);
        $limit = (int) config('security.rate_limits.exports_per_minute');

        $token = $this->users['adminA']->createToken('t')->plainTextToken;
        for ($i = 0; $i < $limit; $i++) {
            $this->withToken($token)->get('/api/v1/beneficiaries/export?format=csv');
            $this->app['auth']->forgetGuards();
        }

        $this->withToken($token)->get('/api/v1/beneficiaries/export?format=csv')
            ->assertStatus(429)
            ->assertJsonPath('error.code', 'TOO_MANY_REQUESTS');
        $this->app['auth']->forgetGuards();
        RateLimiter::clear('exports|'.$this->users['adminA']->id);
    }

    public function test_import_upload_endpoint_is_rate_limited_per_user(): void
    {
        RateLimiter::clear('imports|'.$this->users['adminA']->id);
        $limit = (int) config('security.rate_limits.imports_per_minute');

        $token = $this->users['adminA']->createToken('t')->plainTextToken;
        for ($i = 0; $i < $limit; $i++) {
            // Empty POST fails validation (422) — but the throttle counts the hit.
            $this->withToken($token)->postJson('/api/v1/beneficiaries/imports', []);
            $this->app['auth']->forgetGuards();
        }

        $this->withToken($token)->postJson('/api/v1/beneficiaries/imports', [])
            ->assertStatus(429)
            ->assertJsonPath('error.code', 'TOO_MANY_REQUESTS');
        $this->app['auth']->forgetGuards();
        RateLimiter::clear('imports|'.$this->users['adminA']->id);
    }
}
