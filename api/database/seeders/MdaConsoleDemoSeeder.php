<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Registry\Models\ServiceRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * MDA-console demo data — enough for all SIX modules plus the header to render something
 * real, for BOTH an MDA Officer and an MDA Admin.
 *
 * Like {@see AdminConsoleDemoSeeder} it seeds only what no existing seeder covers and
 * reuses the rest: MDAs from {@see SampleMdaSeeder}, the Officer/Admin accounts from
 * {@see SampleMdaUserSeeder}, the registry from {@see RegistrySampleSeeder}, programmes,
 * activities and delivered benefits from {@see ProgrammeSampleSeeder}, and referrals in
 * both directions from {@see ReferralSampleSeeder}.
 *
 * What it adds is the three gaps those leave:
 *
 *  1. **A non-beneficiary activity.** Every factory-made activity has
 *     `involves_beneficiaries = true`, so the Import Center's activity picker had nothing
 *     to filter OUT and the conditional wizard branch was invisible.
 *  2. **Request-to-serve in both directions.** Nothing seeded these at all, which left
 *     Service Delivery's approval queue and the Overview's "pending request-to-serve
 *     approvals" counter empty.
 *  3. **A duplicate case on an MDA-owned batch**, with real `MatchBand` /
 *     `ImportRowResolution` values and a candidate pointing at an actual beneficiary, so
 *     Duplicate Resolution renders exact and probable rows with working evidence.
 *
 * Synthetic only. Names come from the existing factories; identifiers are never real,
 * and nothing here is derived from a live record (SECURITY.md — no real PII outside
 * production).
 *
 * Never runs in production. Idempotent: safe to re-run.
 */
class MdaConsoleDemoSeeder extends Seeder
{
    /** The MDA whose console the demo is built around. */
    private const HOME_MDA = 'Ministry of Health';

    /** The counterparty for two-sided flows (referrals, request-to-serve). */
    private const OTHER_MDA = 'Ministry of Women Affairs & Social Development';

    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $this->call([
            RolesAndPermissionsSeeder::class,
            MatchingConfigSeeder::class,
            SampleMdaSeeder::class,
            SampleMdaUserSeeder::class,
            RegistrySampleSeeder::class,
            ProgrammeSampleSeeder::class,
            ReferralSampleSeeder::class,
        ]);

        $home = $this->mda(self::HOME_MDA);
        $other = $this->mda(self::OTHER_MDA);
        if ($home === null || $other === null) {
            return;
        }

        $this->seedNonBeneficiaryActivity($home);
        $this->seedServiceRequests($home, $other);
        $this->seedDuplicateCase($home);
        $this->seedInboundReferral($home, $other);
    }

    private function mda(string $name): ?Mda
    {
        return Mda::query()->withoutGlobalScopes()->where('name', $name)->first();
    }

    private function officerIn(Mda $mda): ?User
    {
        return User::query()->withoutGlobalScope(MdaScope::class)
            ->where('mda_id', $mda->id)
            ->orderBy('email')
            ->first();
    }

    /**
     * An activity that does NOT register beneficiaries — staff training.
     *
     * The conditional wizard (FR-REG-11) branches on this flag, and the Import Center
     * only offers activities that accept beneficiaries. With every seeded activity set to
     * `true`, neither behaviour was observable in a demo stack.
     */
    private function seedNonBeneficiaryActivity(Mda $home): void
    {
        $programme = Programme::query()->withoutGlobalScopes()->orderBy('name')->first();
        if ($programme === null) {
            return;
        }

        Activity::query()->withoutGlobalScope(MdaScope::class)->firstOrCreate(
            ['name' => 'Caseworker Training — Cohort 2', 'owner_mda_id' => $home->id],
            [
                'programme_id' => $programme->id,
                'involves_beneficiaries' => false,
                'status' => 'active',
                'description' => 'Two-day refresher for caseworkers. Registers no beneficiaries.',
                'lga' => 'dutse',
                'budget_amount' => 2_500_000,
                'funding_source' => 'State budget',
                'starts_on' => Carbon::now()->subDays(20)->toDateString(),
                'ends_on' => Carbon::now()->subDays(18)->toDateString(),
            ],
        );
    }

    /**
     * Request-to-serve in BOTH directions.
     *
     * Incoming + pending is the only combination that is work awaiting this MDA, and it
     * is what the Overview's approvals counter counts — so the demo needs one of those
     * plus a decided one (history) and one we raised ourselves (the requester's view).
     */
    private function seedServiceRequests(Mda $home, Mda $other): void
    {
        $ourBeneficiaries = $this->beneficiariesOf($home, 2);
        $theirBeneficiary = $this->beneficiariesOf($other, 1)->first();
        $requester = $this->officerIn($other);
        $ourOfficer = $this->officerIn($home);

        if ($ourBeneficiaries->isEmpty() || $theirBeneficiary === null) {
            return;
        }

        // INCOMING, pending — awaiting an MDA Admin's decision here.
        $this->serviceRequest([
            'beneficiary_id' => $ourBeneficiaries->first()->id,
            'from_mda_id' => $other->id,
            'to_mda_id' => $home->id,
            'status' => ServiceRequestStatus::Pending->value,
            'reason' => 'Enrolling her in our school-feeding activity; she is already on your register.',
            'requested_by' => $requester?->id,
        ]);

        // INCOMING, already declined — so the Declined / History views are not empty.
        if ($ourBeneficiaries->count() > 1) {
            $this->serviceRequest([
                'beneficiary_id' => $ourBeneficiaries->get(1)->id,
                'from_mda_id' => $other->id,
                'to_mda_id' => $home->id,
                'status' => ServiceRequestStatus::Declined->value,
                'reason' => 'Requested for a livelihood grant.',
                'requested_by' => $requester?->id,
                'decision_reason' => 'Already receiving a comparable benefit from us this quarter.',
                'decided_at' => Carbon::now()->subDays(9),
            ]);
        }

        // OUTGOING — one we raised on another MDA's beneficiary, accepted.
        $this->serviceRequest([
            'beneficiary_id' => $theirBeneficiary->id,
            'from_mda_id' => $home->id,
            'to_mda_id' => $other->id,
            'status' => ServiceRequestStatus::Accepted->value,
            'reason' => 'Adding him to our dry-season food distribution.',
            'requested_by' => $ourOfficer?->id,
            'decided_at' => Carbon::now()->subDays(4),
        ]);
    }

    /** @param array<string, mixed> $attributes */
    private function serviceRequest(array $attributes): void
    {
        ServiceRequest::query()->firstOrCreate(
            [
                'beneficiary_id' => $attributes['beneficiary_id'],
                'from_mda_id' => $attributes['from_mda_id'],
                'to_mda_id' => $attributes['to_mda_id'],
            ],
            $attributes,
        );
    }

    /**
     * An import batch owned by this MDA whose screening surfaced duplicates.
     *
     * Bands and resolutions use the real enums — `exact`/`probable` and
     * `new`/`link`/`skip`. A candidate references an ACTUAL beneficiary so the
     * adjudication screen can resolve a reveal and render the comparison; without that
     * the evidence panel has nothing to show.
     */
    private function seedDuplicateCase(Mda $home): void
    {
        $beneficiaries = $this->beneficiariesOf($home, 2);
        if ($beneficiaries->isEmpty()) {
            return;
        }

        $activity = Activity::query()->withoutGlobalScope(MdaScope::class)
            ->where('owner_mda_id', $home->id)
            ->where('involves_beneficiaries', true)
            ->first();

        /** @var ImportBatch $batch */
        $batch = ImportBatch::withoutEvents(fn (): ImportBatch => ImportBatch::updateOrCreate(
            ['original_filename' => 'dutse-cash-round-q2.csv'],
            [
                'owner_mda_id' => $home->id,
                'uploaded_by' => $this->officerIn($home)?->id,
                'activity_id' => $activity?->id,
                'stored_path' => 'imports/demo/dutse-cash-round-q2.csv',
                'source' => 'csv',
                // Preview-ready is the only state in which a decision can be taken, so
                // the module's Pending Reviews queue has something actionable.
                'status' => ImportStatus::PreviewReady->value,
                'total_rows' => 6,
                'valid_rows' => 6,
                'invalid_rows' => 0,
                'committed_rows' => 0,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ));

        if (ImportRow::query()->where('import_batch_id', $batch->id)->exists()) {
            return; // idempotent
        }

        $existing = $beneficiaries->first();
        $second = $beneficiaries->count() > 1 ? $beneficiaries->get(1) : $existing;

        $rows = [
            // An EXACT match, undecided: definitive duplicate, discard-or-serve only.
            $this->duplicateRow(1, $existing, MatchBand::Exact, 1.0, ['nin'], 'deterministic', null),
            // A PROBABLE match, undecided: the same-person judgement is offered here.
            $this->duplicateRow(2, $second, MatchBand::Probable, 0.86, ['last_name', 'date_of_birth'], 'fuzzy', null),
            // A PROBABLE match already adjudicated as a distinct person, with a reason.
            $this->duplicateRow(3, $second, MatchBand::Probable, 0.79, ['last_name'], 'fuzzy', ImportRowResolution::New),
            // An EXACT match resolved by serving the existing record instead.
            $this->duplicateRow(4, $existing, MatchBand::Exact, 1.0, ['bvn'], 'deterministic', ImportRowResolution::Link),
            // Two clean rows, so the module's "flagged only" filtering is visible.
            $this->cleanRow(5),
            $this->cleanRow(6),
        ];

        foreach ($rows as $row) {
            ImportRow::withoutEvents(fn () => ImportRow::create($row + ['import_batch_id' => $batch->id]));
        }
    }

    /**
     * A flagged staged row. The payload is synthetic and carries no identifier — the
     * match candidate points at the existing record by id, which is what the reveal is
     * resolved from.
     *
     * @param  list<string>  $matchedFields
     * @return array<string, mixed>
     */
    private function duplicateRow(
        int $rowNumber,
        Beneficiary $candidate,
        MatchBand $band,
        float $score,
        array $matchedFields,
        string $stage,
        ?ImportRowResolution $resolution,
    ): array {
        return [
            'row_number' => $rowNumber,
            'original_record_id' => sprintf('CSV-%04d', $rowNumber),
            'payload' => [
                'first_name' => $candidate->first_name,
                'last_name' => $candidate->last_name,
                'lga' => $candidate->lga,
            ],
            'is_valid' => true,
            'match_band' => $band->value,
            'match_candidates' => [[
                'type' => 'registry',
                'reference' => $candidate->id,
                'band' => $band->value,
                'score' => $score,
                'matched_fields' => $matchedFields,
                'stage' => $stage,
                'comparison' => array_map(
                    static fn (string $field): array => ['field' => $field, 'verdict' => 'match'],
                    $matchedFields,
                ),
            ]],
            'resolution' => $resolution?->value,
            'resolution_note' => $resolution === ImportRowResolution::New
                ? 'Reviewed against the existing record — different date of birth and ward. A distinct person.'
                : null,
            'resolved_beneficiary_id' => $resolution === ImportRowResolution::Link ? $candidate->id : null,
            'resolved_at' => $resolution !== null ? Carbon::now()->subDays(2) : null,
        ];
    }

    /** @return array<string, mixed> */
    private function cleanRow(int $rowNumber): array
    {
        return [
            'row_number' => $rowNumber,
            'original_record_id' => sprintf('CSV-%04d', $rowNumber),
            'payload' => ['first_name' => 'Zainab', 'last_name' => 'Garba', 'lga' => 'dutse'],
            'is_valid' => true,
            'match_band' => MatchBand::None->value,
            'match_candidates' => [],
            'resolution' => null,
        ];
    }

    /**
     * An inbound referral still awaiting a response, so the Overview's "referrals
     * awaiting your response" counter is non-zero. ReferralSampleSeeder creates referrals
     * between the two MDAs but not necessarily one left open against this MDA.
     */
    private function seedInboundReferral(Mda $home, Mda $other): void
    {
        $beneficiary = $this->beneficiariesOf($other, 1)->first();
        if ($beneficiary === null) {
            return;
        }

        Referral::query()->withoutGlobalScopes()->firstOrCreate(
            [
                'beneficiary_id' => $beneficiary->id,
                'from_mda_id' => $other->id,
                'to_mda_id' => $home->id,
                'need' => 'Health service',
            ],
            [
                'status' => ReferralStatus::Created->value,
                'notes' => 'Referred for antenatal care; she is on our livelihood register.',
                'escalation_level' => 0,
                'created_by' => $this->officerIn($other)?->id,
            ],
        );
    }

    /**
     * @return Collection<int, Beneficiary>
     */
    private function beneficiariesOf(Mda $mda, int $take)
    {
        return Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('owner_mda_id', $mda->id)
            ->orderBy('id')
            ->take($take)
            ->get();
    }
}
