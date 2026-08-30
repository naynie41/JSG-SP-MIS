<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Services\BeneficiaryRevealPresenter;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Http\Resources\BeneficiaryRevealResource;
use Illuminate\Support\Collection;

/**
 * Builds the `match_view` a flagged import row carries (FR-DUP-04).
 *
 * Extracted from the batch controller so the duplicate QUEUE and the batch PAGE render
 * a match identically. They read the same rows through different endpoints, and a second
 * implementation is how "owned by you" ends up true on one screen and absent on the
 * other — which decides whether the officer is offered a request-to-serve at all.
 *
 * Everything here is reveal-only: name, owner MDA, LGA, status. Never NIN, BVN or phone,
 * even for a match the caller's own MDA owns.
 */
class MatchRevealAssembler
{
    public function __construct(private readonly BeneficiaryRevealPresenter $reveals) {}

    /**
     * Attach `match_view` to every row, resolving matched records in ONE query for the
     * whole collection rather than per row.
     *
     * @param  Collection<int, ImportRow>  $rows
     * @param  Collection<string, ImportBatch>  $batches  keyed by id; each row's own batch
     */
    public function attach(Collection $rows, Collection $batches): void
    {
        $registryIds = $rows
            ->flatMap(fn (ImportRow $row) => collect($row->match_candidates ?? [])
                ->where('type', 'registry')->pluck('reference'))
            ->filter()->unique()->values()->all();

        // Reveal fields only — never the full profile — even cross-MDA (serve seam).
        $beneficiaries = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->with(['ownerMda' => fn ($query) => $query->withoutGlobalScope(MdaScope::class)->select('id', 'name')])
            ->whereIn('id', $registryIds)
            ->get()
            ->keyBy('id');

        // The reveal's programme/benefit sections cost two queries per subject. Loaded
        // here for the whole page instead: this assembler serves the duplicate QUEUE,
        // which is read when the backlog is large, so per-subject work is exactly the
        // cost that grows with the thing the screen exists to clear.
        $this->reveals->preload($beneficiaries);

        // Within-batch peers are resolved against rows of the SAME batch: a candidate
        // reference is a row number, which only means anything inside its own file.
        $peersByBatch = $rows->groupBy('import_batch_id')
            ->map(fn (Collection $batchRows) => $batchRows->keyBy('row_number'));

        foreach ($rows as $row) {
            $batch = $batches->get($row->import_batch_id);
            if ($batch === null) {
                continue;
            }

            $peers = $peersByBatch->get($row->import_batch_id) ?? collect();
            $candidates = [];

            foreach ($row->match_candidates ?? [] as $candidate) {
                $candidates[] = [
                    'type' => $candidate['type'],
                    'band' => $candidate['band'],
                    'score' => $candidate['score'],
                    'matched_fields' => $candidate['matched_fields'],
                    // Per-field verdicts + cascade stage drive the adjudication screen.
                    // Absent on batches screened before this shipped, so the client must
                    // treat them as optional.
                    'comparison' => $candidate['comparison'] ?? [],
                    'stage' => $candidate['stage'] ?? null,
                    // Whose record this is, stated by the server rather than left for the
                    // client to infer by comparing MDA ids. It decides which resolution
                    // the officer is even offered — "already in your registry" versus a
                    // cross-MDA request-to-serve — so it is policy, not presentation.
                    'owned_by_you' => $candidate['type'] === 'registry'
                        && $beneficiaries->get((string) $candidate['reference'])?->owner_mda_id === $batch->owner_mda_id,
                    'reveal' => $candidate['type'] === 'registry'
                        ? $this->registryReveal($beneficiaries, (string) $candidate['reference'])
                        : $this->peerReveal($peers, $batch, (int) $candidate['reference']),
                ];
            }

            $row->setAttribute('match_view', [
                'band' => $row->match_band ?? 'none',
                'candidates' => $candidates,
            ]);
        }
    }

    /**
     * @param  Collection<string, Beneficiary>  $beneficiaries
     * @return array<string, mixed>|null
     */
    private function registryReveal(Collection $beneficiaries, string $id): ?array
    {
        $beneficiary = $beneficiaries->get($id);

        return $beneficiary === null ? null : (new BeneficiaryRevealResource($beneficiary))->resolve();
    }

    /**
     * A within-batch peer is not persisted yet, so its reveal is drawn from the staged
     * row — same reveal-only shape, no NIN/BVN/phone.
     *
     * @param  Collection<int, ImportRow>  $peers
     * @return array<string, mixed>|null
     */
    private function peerReveal(Collection $peers, ImportBatch $batch, int $rowNumber): ?array
    {
        $row = $peers->get($rowNumber);
        if ($row === null) {
            return null;
        }

        $payload = $row->payload;

        return [
            'id' => null,
            'row_number' => $row->row_number,
            'full_name' => trim(($payload['first_name'] ?? '').' '.($payload['last_name'] ?? '')),
            'owner_mda' => ['id' => $batch->owner_mda_id, 'name' => null],
            'registration_source' => $batch->source->value,
            'registration_date' => null,
            'lga' => $payload['lga'] ?? null,
            'ward' => $payload['ward'] ?? null,
            'status' => 'pending',
            'programmes' => [],
            'benefits' => ['summary' => null, 'items' => []],
        ];
    }
}
