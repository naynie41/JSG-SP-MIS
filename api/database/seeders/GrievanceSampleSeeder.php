<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Grievance\Enums\GrievanceCategory;
use App\Domain\Grievance\Enums\GrievanceChannel;
use App\Domain\Grievance\Enums\GrievanceStatus;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Synthetic grievances the sample MDAs handle, across categories + channels and a
 * spread of lifecycle states — including an escalated/overdue one (FR-GRM-01/02/03).
 * Staff-logged on behalf of beneficiaries (some general, no link). LOCAL/STAGING ONLY
 * — never real PII, never production. Idempotent (no-op once any grievance exists).
 */
class GrievanceSampleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production') || Grievance::query()->withoutGlobalScopes()->exists()) {
            return;
        }

        $health = Mda::query()->where('name', 'Ministry of Health')->first();
        $women = Mda::query()->where('name', 'Ministry of Women Affairs & Social Development')->first();
        if ($health === null || $women === null) {
            return;
        }

        $healthBens = Beneficiary::query()->where('owner_mda_id', $health->id)->get();
        $womenBens = Beneficiary::query()->where('owner_mda_id', $women->id)->get();
        $healthOfficer = User::query()->where('email', 'health.officer@spmis.local')->first();
        $healthAdmin = User::query()->where('email', 'health.admin@spmis.local')->first();
        $womenOfficer = User::query()->where('email', 'women.officer@spmis.local')->first();
        $now = Carbon::now();

        // --- Health MDA ---------------------------------------------------------
        // Open, freshly logged, linked to a beneficiary.
        $this->make([
            'handling_mda_id' => $health->id, 'beneficiary_id' => $healthBens[0]->id ?? null,
            'category' => GrievanceCategory::Payment, 'channel' => GrievanceChannel::WalkIn,
            'description' => 'Reported not receiving the last cash transfer.',
            'status' => GrievanceStatus::Open, 'submitted_by' => $healthOfficer?->id,
        ], $now->copy()->subHours(4));

        // Assigned and in progress.
        $this->make([
            'handling_mda_id' => $health->id, 'beneficiary_id' => $healthBens[1]->id ?? null,
            'category' => GrievanceCategory::DataCorrection, 'channel' => GrievanceChannel::Phone,
            'description' => 'Beneficiary’s ward is recorded incorrectly.',
            'status' => GrievanceStatus::InProgress, 'submitted_by' => $healthOfficer?->id,
            'assignee_user_id' => $healthOfficer?->id,
            'assigned_at' => $now->copy()->subDays(1), 'started_at' => $now->copy()->subHours(20),
        ], $now->copy()->subDays(1)->subHours(2));

        // Resolved with notes (general grievance — no beneficiary link).
        $this->make([
            'handling_mda_id' => $health->id, 'beneficiary_id' => null,
            'category' => GrievanceCategory::ServiceQuality, 'channel' => GrievanceChannel::Email,
            'description' => 'Long queues at the distribution point.',
            'status' => GrievanceStatus::Resolved, 'submitted_by' => $healthOfficer?->id,
            'assignee_user_id' => $healthAdmin?->id, 'resolution_notes' => 'Added a second distribution window.',
            'assigned_at' => $now->copy()->subDays(4), 'started_at' => $now->copy()->subDays(3),
            'resolved_at' => $now->copy()->subDays(2),
        ], $now->copy()->subDays(5));

        // ESCALATED / overdue — open past its category SLA (FR-GRM-03).
        $this->make([
            'handling_mda_id' => $health->id, 'beneficiary_id' => $healthBens[2]->id ?? null,
            'category' => GrievanceCategory::Eligibility, 'channel' => GrievanceChannel::Sms,
            'description' => 'Disputes exclusion from the programme.',
            'status' => GrievanceStatus::Assigned, 'submitted_by' => $healthOfficer?->id,
            'assignee_user_id' => $healthOfficer?->id, 'assigned_at' => $now->copy()->subDays(5),
            'escalation_level' => 1, 'sla_breached_at' => $now->copy()->subDays(1),
        ], $now->copy()->subDays(6));

        // --- Women Affairs MDA --------------------------------------------------
        // Assigned, awaiting work.
        $this->make([
            'handling_mda_id' => $women->id, 'beneficiary_id' => $womenBens[0]->id ?? null,
            'category' => GrievanceCategory::Complaint, 'channel' => GrievanceChannel::Online,
            'description' => 'Complaint about staff conduct at intake.',
            'status' => GrievanceStatus::Assigned, 'submitted_by' => $womenOfficer?->id,
            'assignee_user_id' => $womenOfficer?->id, 'assigned_at' => $now->copy()->subHours(8),
        ], $now->copy()->subHours(10));

        // Closed.
        $this->make([
            'handling_mda_id' => $women->id, 'beneficiary_id' => null,
            'category' => GrievanceCategory::Other, 'channel' => GrievanceChannel::Other,
            'description' => 'General enquiry about the grant schedule.',
            'status' => GrievanceStatus::Closed, 'submitted_by' => $womenOfficer?->id,
            'assignee_user_id' => $womenOfficer?->id, 'resolution_notes' => 'Answered; schedule shared.',
            'assigned_at' => $now->copy()->subDays(3), 'resolved_at' => $now->copy()->subDays(2),
            'closed_at' => $now->copy()->subDays(2),
        ], $now->copy()->subDays(4));
    }

    /**
     * @param  array<string, mixed>  $attrs
     */
    private function make(array $attrs, Carbon $createdAt): Grievance
    {
        $grievance = new Grievance;
        $grievance->forceFill(array_merge(['escalation_level' => 0], $attrs));
        $grievance->created_at = $createdAt;
        $grievance->updated_at = $createdAt;
        $grievance->save();

        return $grievance;
    }
}
