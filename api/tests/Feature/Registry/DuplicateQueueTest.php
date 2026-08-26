<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The duplicate queue: every flagged row across an MDA's imports (FR-DUP-01/05).
 *
 * The console used to build this in the browser — page one of BATCHES, one detail
 * request per batch, flatten. So the module for clearing a backlog could only ever see
 * the first page of it, and said nothing about the rest. These tests pin the property
 * that replaces that: the queue is paginated over ROWS, so every flagged row is
 * reachable however many files it took to produce them.
 */
class DuplicateQueueTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mine;

    private Mda $theirs;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mine = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->theirs = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['mine'] = $this->user($this->mine);
        $this->users['theirs'] = $this->user($this->theirs);
    }

    private function user(Mda $mda): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $query = ''): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)
            ->getJson('/api/v1/beneficiaries/duplicates'.$query);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** A batch of flagged rows owned by one MDA. */
    private function batchWith(Mda $owner, int $rows, string $band = 'exact', ?string $resolution = null): ImportBatch
    {
        $batch = ImportBatch::create([
            'owner_mda_id' => $owner->id,
            'original_filename' => 'flagged-'.$owner->id.'-'.$rows.'.csv',
            'stored_path' => 'imports/flagged.csv',
            'source' => 'csv',
            'status' => 'preview_ready',
        ]);

        for ($i = 1; $i <= $rows; $i++) {
            ImportRow::query()->create([
                'import_batch_id' => $batch->id,
                'row_number' => $i,
                'payload' => ['first_name' => 'Aisha', 'last_name' => 'Bello'.$i],
                'is_valid' => true,
                'match_band' => $band,
                'match_candidates' => [],
                'resolution' => $resolution,
            ]);
        }

        return $batch;
    }

    /* -------------------------------------------------------------- the backlog */

    public function test_it_reaches_flagged_rows_beyond_the_first_batch(): void
    {
        // The whole point. Three files, each with flagged rows: the browser-side version
        // fetched page one of batches and stopped, so anything in an older import was
        // unreachable from the module meant to clear it.
        $this->batchWith($this->mine, 2);
        $this->batchWith($this->mine, 2);
        $this->batchWith($this->mine, 2);

        $this->send('mine')->assertOk()->assertJsonPath('data.pagination.total', 6);
    }

    public function test_it_paginates_rows_not_batches(): void
    {
        $this->batchWith($this->mine, 30);

        $first = $this->send('mine', '?per_page=10')->assertOk();
        $first->assertJsonPath('data.pagination.total', 30)
            ->assertJsonPath('data.pagination.total_pages', 3);
        $this->assertCount(10, $first->json('data.items'));

        $second = $this->send('mine', '?per_page=10&page=2')->assertOk();
        $this->assertCount(10, $second->json('data.items'));

        // Different page, different rows — not the same ten re-served.
        $this->assertNotEquals(
            collect($first->json('data.items'))->pluck('row_number')->all(),
            collect($second->json('data.items'))->pluck('row_number')->all(),
        );
    }

    /* ------------------------------------------------------------------- filters */

    public function test_awaiting_is_the_working_queue_and_decided_is_the_record(): void
    {
        $this->batchWith($this->mine, 3);                        // undecided
        $this->batchWith($this->mine, 2, 'exact', 'skip');       // already decided

        $this->send('mine')->assertOk()->assertJsonPath('data.pagination.total', 3);
        $this->send('mine', '?state=decided')->assertOk()->assertJsonPath('data.pagination.total', 2);
    }

    public function test_a_committed_batch_is_history_not_outstanding_work(): void
    {
        // Once a batch is committed the server refuses a resolution on its rows, so an
        // undecided row there is not work waiting — counting it would send an officer
        // looking for a decision that would be rejected.
        $this->batchWith($this->mine, 2);                       // preview_ready
        $committed = $this->batchWith($this->mine, 4);
        $committed->forceFill(['status' => 'completed'])->save();

        $this->send('mine')->assertOk()
            ->assertJsonPath('data.pagination.total', 2)
            ->assertJsonPath('data.counts.exact.awaiting', 2)
            // Still visible under `all` — hidden from the queue, not from the record.
            ->assertJsonPath('data.counts.exact.total', 6);

        $this->send('mine', '?state=all')->assertOk()->assertJsonPath('data.pagination.total', 6);
    }

    public function test_it_filters_by_band(): void
    {
        $this->batchWith($this->mine, 2, 'exact');
        $this->batchWith($this->mine, 3, 'probable');

        $this->send('mine', '?band=exact')->assertOk()->assertJsonPath('data.pagination.total', 2);
        $this->send('mine', '?band=probable')->assertOk()->assertJsonPath('data.pagination.total', 3);
        // No band is both, never everything: a clean row is not a duplicate.
        $this->send('mine')->assertOk()->assertJsonPath('data.pagination.total', 5);
    }

    public function test_a_row_that_was_never_flagged_is_not_in_the_queue(): void
    {
        $batch = ImportBatch::create([
            'owner_mda_id' => $this->mine->id,
            'original_filename' => 'clean.csv',
            'stored_path' => 'imports/clean.csv',
            'source' => 'csv',
            'status' => 'preview_ready',
        ]);
        ImportRow::query()->create([
            'import_batch_id' => $batch->id,
            'row_number' => 1,
            'payload' => ['first_name' => 'Halima', 'last_name' => 'Yusuf'],
            'is_valid' => true,
            'match_band' => null,
            'match_candidates' => [],
        ]);

        $this->send('mine')->assertOk()->assertJsonPath('data.pagination.total', 0);
    }

    public function test_it_reports_outstanding_counts_across_every_import(): void
    {
        // The tab label needs a number, not a list. Counting client-side is what tied
        // the label to whatever subset had been fetched — so it could read "(4)" while
        // forty sat unreachable in older batches.
        $this->batchWith($this->mine, 3, 'exact');
        $this->batchWith($this->mine, 2, 'exact', 'skip');
        $this->batchWith($this->mine, 4, 'probable');

        $counts = $this->send('mine', '?band=exact&per_page=1')->assertOk()->json('data.counts');

        // Scope-wide, and unaffected by the band filter or the page size.
        $this->assertSame(['awaiting' => 3, 'total' => 5], $counts['exact']);
        $this->assertSame(['awaiting' => 4, 'total' => 4], $counts['probable']);
    }

    public function test_counts_stop_at_the_mda_boundary(): void
    {
        $this->batchWith($this->mine, 2, 'exact');
        $this->batchWith($this->theirs, 9, 'exact');

        $this->send('mine')->assertOk()->assertJsonPath('data.counts.exact.awaiting', 2);
        $this->send('theirs')->assertOk()->assertJsonPath('data.counts.exact.awaiting', 9);
    }
    /* --------------------------------------------------------------------- scope */

    public function test_it_never_shows_another_mdas_flagged_rows(): void
    {
        $this->batchWith($this->mine, 2);
        $this->batchWith($this->theirs, 5);

        $this->send('mine')->assertOk()->assertJsonPath('data.pagination.total', 2);
        $this->send('theirs')->assertOk()->assertJsonPath('data.pagination.total', 5);
    }

    /* ------------------------------------------------------------------- payload */

    public function test_each_row_names_the_file_it_came_from(): void
    {
        // The queue spans files, so context the batch page gets for free has to travel
        // with the row.
        $batch = $this->batchWith($this->mine, 1);

        $item = $this->send('mine')->assertOk()->json('data.items.0');

        $this->assertSame($batch->id, $item['batch']['id']);
        $this->assertSame($batch->original_filename, $item['batch']['original_filename']);
    }

    public function test_it_carries_the_same_match_view_the_batch_page_renders(): void
    {
        // Both surfaces run one assembler, so a match cannot read differently depending
        // on which screen reached it — `owned_by_you` in particular decides whether a
        // request-to-serve is offered at all.
        $existing = Beneficiary::factory()->create(['owner_mda_id' => $this->mine->id]);
        $batch = ImportBatch::create([
            'owner_mda_id' => $this->mine->id,
            'original_filename' => 'clean.csv',
            'stored_path' => 'imports/clean.csv',
            'source' => 'csv',
            'status' => 'preview_ready',
        ]);
        ImportRow::query()->create([
            'import_batch_id' => $batch->id,
            'row_number' => 1,
            'payload' => ['first_name' => 'Aisha', 'last_name' => 'Bello'],
            'is_valid' => true,
            'match_band' => 'exact',
            'match_candidates' => [[
                'type' => 'registry',
                'reference' => $existing->id,
                'band' => 'exact',
                'score' => 1.0,
                'matched_fields' => ['nin'],
                'comparison' => [],
                'stage' => 'deterministic',
            ]],
        ]);

        $candidate = $this->send('mine')->assertOk()->json('data.items.0.match.candidates.0');

        $this->assertTrue($candidate['owned_by_you']);
        $this->assertSame($existing->id, $candidate['reveal']['id']);
        // Reveal only — never an identifier, even for a record this MDA owns.
        $this->assertArrayNotHasKey('nin', $candidate['reveal']);
        $this->assertArrayNotHasKey('bvn', $candidate['reveal']);
        $this->assertArrayNotHasKey('phone', $candidate['reveal']);
    }

    public function test_it_refuses_a_caller_without_beneficiary_view(): void
    {
        $stripped = $this->users['mine'];
        $stripped->role->permissions()->detach(
            Permission::where('key', 'beneficiary.view')->firstOrFail()->id
        );

        $this->send('mine')->assertStatus(403);
    }
}
