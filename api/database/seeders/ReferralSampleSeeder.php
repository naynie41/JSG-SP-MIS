<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Synthetic referrals between the two sample MDAs in a spread of lifecycle states —
 * including a rejected referral and an overdue/escalated one (FR-REF-01/02/04/05).
 * LOCAL/STAGING ONLY — never real PII, never production. Idempotent (no-op once any
 * referral exists). Rows are created directly in their target state so the desk has
 * data to show; the live lifecycle + audit + notifications are exercised by tests.
 */
class ReferralSampleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production') || Referral::query()->withoutGlobalScopes()->exists()) {
            return;
        }

        $health = Mda::query()->where('name', 'Ministry of Health')->first();
        $women = Mda::query()->where('name', 'Ministry of Women Affairs & Social Development')->first();
        if ($health === null || $women === null) {
            return;
        }

        $healthBens = Beneficiary::query()->where('owner_mda_id', $health->id)->get();
        $womenBens = Beneficiary::query()->where('owner_mda_id', $women->id)->get();
        if ($healthBens->count() < 3 || $womenBens->count() < 3) {
            return;
        }

        $healthOfficer = User::query()->where('email', 'health.officer@spmis.local')->first();
        $womenOfficer = User::query()->where('email', 'women.officer@spmis.local')->first();
        $now = Carbon::now();

        // 1. Health → Women, accepted (authorizes Women to serve — no Service Request).
        $this->make([
            'beneficiary_id' => $healthBens[0]->id, 'from_mda_id' => $health->id, 'to_mda_id' => $women->id,
            'need' => 'Livelihood / skills', 'notes' => 'Widow seeking a skills grant.',
            'status' => ReferralStatus::Accepted, 'created_by' => $healthOfficer?->id,
            'accepted_at' => $now->copy()->subDays(2),
        ], $now->copy()->subDays(3));

        // 2. Women → Health, in progress.
        $this->make([
            'beneficiary_id' => $womenBens[0]->id, 'from_mda_id' => $women->id, 'to_mda_id' => $health->id,
            'need' => 'Health service', 'notes' => 'Antenatal care referral.',
            'status' => ReferralStatus::InProgress, 'created_by' => $womenOfficer?->id,
            'accepted_at' => $now->copy()->subDays(4), 'started_at' => $now->copy()->subDays(3),
        ], $now->copy()->subDays(5));

        // 3. Health → Women, completed with a recorded outcome (FR-REF-03).
        $this->make([
            'beneficiary_id' => $healthBens[1]->id, 'from_mda_id' => $health->id, 'to_mda_id' => $women->id,
            'need' => 'Nutrition support', 'status' => ReferralStatus::Completed, 'created_by' => $healthOfficer?->id,
            'outcome' => 'Enrolled in the supplementary feeding activity.',
            'accepted_at' => $now->copy()->subDays(9), 'started_at' => $now->copy()->subDays(8),
            'completed_at' => $now->copy()->subDays(6),
        ], $now->copy()->subDays(10));

        // 4. Women → Health, rejected with a required reason.
        $this->make([
            'beneficiary_id' => $womenBens[1]->id, 'from_mda_id' => $women->id, 'to_mda_id' => $health->id,
            'need' => 'Health service', 'status' => ReferralStatus::Rejected, 'created_by' => $womenOfficer?->id,
            'reason' => 'Beneficiary is outside this facility’s catchment area.',
            'rejected_at' => $now->copy()->subDays(1),
        ], $now->copy()->subDays(2));

        // 5. Health → Women, awaiting more information from the originator.
        $this->make([
            'beneficiary_id' => $healthBens[2]->id, 'from_mda_id' => $health->id, 'to_mda_id' => $women->id,
            'need' => 'Disability support', 'status' => ReferralStatus::MoreInfoRequested, 'created_by' => $healthOfficer?->id,
            'info_request' => 'Which disability register is the beneficiary on?',
            'info_requested_at' => $now->copy()->subHours(6),
        ], $now->copy()->subDays(1));

        // 6. Health → Women, OVERDUE — sat in "created" past its SLA, escalated (FR-REF-04).
        $this->make([
            'beneficiary_id' => $womenBens[2]->id, 'from_mda_id' => $health->id, 'to_mda_id' => $women->id,
            'need' => 'Protection / GBV', 'notes' => 'Urgent protection referral.',
            'status' => ReferralStatus::Created, 'created_by' => $healthOfficer?->id,
            'escalation_level' => 2, 'sla_breached_at' => $now->copy()->subDays(1),
        ], $now->copy()->subDays(5));

        // 7. Women → Health, freshly created (on track).
        $this->make([
            'beneficiary_id' => $womenBens[0]->id, 'from_mda_id' => $women->id, 'to_mda_id' => $health->id,
            'need' => 'Education support', 'status' => ReferralStatus::Created, 'created_by' => $womenOfficer?->id,
        ], $now->copy()->subHours(2));
    }

    /**
     * @param  array<string, mixed>  $attrs
     */
    private function make(array $attrs, Carbon $createdAt): Referral
    {
        $referral = new Referral;
        $referral->forceFill(array_merge(['escalation_level' => 0], $attrs));
        $referral->created_at = $createdAt;
        $referral->updated_at = $createdAt;
        $referral->save();

        return $referral;
    }
}
