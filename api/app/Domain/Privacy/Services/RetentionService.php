<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Privacy\Enums\RetentionAction;
use App\Domain\Privacy\Retention\RetentionPolicy;
use App\Domain\Privacy\Retention\RetentionPolicyRepository;
use App\Domain\Privacy\Retention\RetentionRunSummary;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Enforces the configured data-retention policies (NFR-PRV-01). For each enabled
 * policy it selects the aged cohort and applies the policy's action — flag,
 * aggregate, anonymize, or delete — auditing both each record and the run as a
 * whole. Nothing runs unless retention is enabled AND at least one policy is
 * configured; a `delete` never destroys operational history (it anonymizes
 * instead). Supports a dry run that reports the cohort sizes without mutating.
 */
class RetentionService
{
    /**
     * Columns a policy may measure age against — allow-listed so a config typo can
     * never turn into an unsafe/unexpected column reference.
     */
    private const AGE_FIELDS = ['created_at', 'updated_at', 'registration_date', 'sharing_consent_at'];

    public function __construct(
        private readonly RetentionPolicyRepository $policies,
        private readonly AnonymizationService $anonymizer,
        private readonly AuditLogger $audit,
    ) {}

    public function run(bool $dryRun = false, ?Carbon $asOf = null): RetentionRunSummary
    {
        $asOf ??= Carbon::now();

        if (! $this->policies->enabled()) {
            return new RetentionRunSummary(dryRun: $dryRun, ranPolicies: false);
        }

        $summary = new RetentionRunSummary(dryRun: $dryRun);
        $budget = $this->policies->batchLimit();

        foreach ($this->policies->enabledPolicies() as $policy) {
            if ($budget <= 0) {
                break;
            }
            if (! in_array($policy->ageField, self::AGE_FIELDS, true)) {
                continue; // unsafe/unknown age column — skip rather than guess
            }

            $cohort = $this->cohort($policy, $asOf)->limit($budget)->get();
            foreach ($cohort as $beneficiary) {
                $this->apply($policy, $beneficiary, $summary, $dryRun);
                $budget--;
            }
        }

        $this->audit->record('retention.run', null, after: [
            ...$summary->toArray(),
            'as_of' => $asOf->toIso8601String(),
        ]);

        return $summary;
    }

    /**
     * The aged cohort for a policy: matching status, older than the period, and not
     * already handled by this action. Runs system-wide (no MDA scope — retention is
     * a state-level obligation, not an MDA action).
     *
     * @return Builder<Beneficiary>
     */
    private function cohort(RetentionPolicy $policy, Carbon $asOf): Builder
    {
        $cutoff = $asOf->copy()->subDays($policy->ageDays);

        $query = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where($policy->ageField, '<=', $cutoff);

        if ($policy->statuses !== []) {
            $query->whereIn('status', $policy->statuses);
        }

        // Never re-process a record this action already de-identified/flagged.
        if ($policy->action === RetentionAction::Flag) {
            $query->whereNull('retention_flagged_at');
        } else {
            $query->whereNull('anonymized_at');
        }

        return $query;
    }

    private function apply(RetentionPolicy $policy, Beneficiary $beneficiary, RetentionRunSummary $summary, bool $dryRun): void
    {
        switch ($policy->action) {
            case RetentionAction::Flag:
                if (! $dryRun) {
                    $beneficiary->forceFill([
                        'retention_flagged_at' => Carbon::now(),
                        'retention_policy' => $policy->key,
                    ])->saveQuietly();
                    $this->audit->record('beneficiary.retention_flagged', $beneficiary, after: ['policy' => $policy->key]);
                }
                $summary->flagged++;
                break;

            case RetentionAction::Aggregate:
                if (! $dryRun) {
                    $this->anonymizer->anonymize($beneficiary, keepQuasi: true, policyKey: $policy->key);
                }
                $summary->aggregated++;
                break;

            case RetentionAction::Anonymize:
                if (! $dryRun) {
                    $this->anonymizer->anonymize($beneficiary, keepQuasi: false, policyKey: $policy->key);
                }
                $summary->anonymized++;
                break;

            case RetentionAction::Delete:
                $this->applyDelete($policy, $beneficiary, $summary, $dryRun);
                break;
        }
    }

    /**
     * `delete` preserves history: a record with any enrollment/benefit history is
     * anonymized instead of removed (so aggregates + audit survive). Only a
     * history-free record is actually deleted — soft by default, hard only when the
     * DPO has opted into `delete_hard`.
     */
    private function applyDelete(RetentionPolicy $policy, Beneficiary $beneficiary, RetentionRunSummary $summary, bool $dryRun): void
    {
        $hasHistory = $beneficiary->benefits()->exists() || $beneficiary->enrollments()->exists();

        if ($hasHistory) {
            if (! $dryRun) {
                $this->anonymizer->anonymize($beneficiary, keepQuasi: false, policyKey: $policy->key, reason: 'delete_policy_history_preserved');
            }
            $summary->anonymized++;

            return;
        }

        if (! $dryRun) {
            if ($this->policies->deleteHard()) {
                $beneficiary->forceDelete();
            } else {
                $beneficiary->forceFill(['retention_policy' => $policy->key])->saveQuietly();
                $beneficiary->delete();
            }
            $this->audit->record('beneficiary.retention_deleted', $beneficiary, after: [
                'policy' => $policy->key,
                'hard' => $this->policies->deleteHard(),
            ]);
        }
        $summary->deleted++;
    }
}
