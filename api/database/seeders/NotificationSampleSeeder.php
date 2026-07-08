<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\User;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Notification\Models\Notification;
use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Models\Referral;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Synthetic in-app notifications for the sample MDA users, referencing the seeded
 * referrals + grievances (FR-NOT-01/02). Deep-linkable via `related_type` (the morph
 * alias: referral / grievance / service_request) + `related_id`. Bodies carry NO
 * beneficiary PII. LOCAL/STAGING ONLY. Idempotent (no-op once any notification exists).
 */
class NotificationSampleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production') || Notification::query()->withoutGlobalScopes()->exists()) {
            return;
        }

        $womenOfficer = User::query()->where('email', 'women.officer@spmis.local')->first();
        $healthOfficer = User::query()->where('email', 'health.officer@spmis.local')->first();
        $healthAdmin = User::query()->where('email', 'health.admin@spmis.local')->first();
        if ($womenOfficer === null || $healthOfficer === null || $healthAdmin === null) {
            return;
        }

        $accepted = Referral::query()->withoutGlobalScopes()->where('status', ReferralStatus::Accepted->value)->first();
        $overdueReferral = Referral::query()->withoutGlobalScopes()->whereNotNull('sla_breached_at')->first();
        $assignedGrievance = Grievance::query()->withoutGlobalScopes()->where('status', 'in_progress')->first();
        $escalatedGrievance = Grievance::query()->withoutGlobalScopes()->whereNotNull('sla_breached_at')->first();
        $now = Carbon::now();

        // Referral: the receiving MDA (Women) was notified their referral was accepted.
        if ($accepted !== null) {
            $this->make($womenOfficer, 'referral.accepted', 'Referral accepted',
                'A referral involving your MDA is now accepted.', 'referral', $accepted->id, $now->copy()->subDays(2));
        }

        // Referral SLA breach: the originating MDA (Health) alerted about an overdue referral.
        if ($overdueReferral !== null) {
            $this->make($healthOfficer, 'referral.sla_breached', 'Referral overdue — escalation level 2',
                'A referral your MDA raised has breached its SLA and was escalated.', 'referral', $overdueReferral->id, $now->copy()->subDays(1));
        }

        // Grievance assignment: the assignee was notified.
        if ($assignedGrievance !== null) {
            $this->make($healthOfficer, 'grievance.assigned', 'A grievance was assigned to you',
                'You have been assigned a grievance to handle.', 'grievance', $assignedGrievance->id, $now->copy()->subHours(20));
        }

        // Grievance SLA breach: the handling MDA's admin alerted about an escalation.
        if ($escalatedGrievance !== null) {
            $this->make($healthAdmin, 'grievance.sla_breached', 'Grievance overdue — escalation level 1',
                'A grievance in your MDA has breached its SLA and was escalated.', 'grievance', $escalatedGrievance->id, $now->copy()->subDays(1));
        }

        // A Service Request approval (already-read) — shows the read state + a distinct deep link.
        $this->make($healthAdmin, 'service_request.created', 'New service request to review',
            'Another MDA has requested to serve a beneficiary your MDA owns.', 'service_request', (string) Str::uuid(),
            $now->copy()->subDays(3), read: true);
    }

    private function make(User $user, string $type, string $subject, string $body, ?string $relatedType, ?string $relatedId, Carbon $createdAt, bool $read = false): void
    {
        $notification = new Notification;
        $notification->forceFill([
            'recipient_user_id' => $user->id,
            'recipient_mda_id' => $user->mda_id,
            'type' => $type,
            'subject' => $subject,
            'body' => $body,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'read_at' => $read ? $createdAt->copy()->addHour() : null,
        ]);
        $notification->created_at = $createdAt;
        $notification->updated_at = $createdAt;
        $notification->save();
    }
}
