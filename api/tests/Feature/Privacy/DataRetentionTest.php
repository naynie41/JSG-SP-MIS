<?php

declare(strict_types=1);

namespace Tests\Feature\Privacy;

use App\Domain\Benefit\Models\Benefit;
use App\Domain\Privacy\Services\RetentionService;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Data-retention engine (NFR-PRV-01): each configured policy selects an aged cohort
 * (status + age) and applies its action — flag / aggregate / anonymize / delete —
 * auditing the run. Legal periods/actions are configuration; a `delete` never
 * destroys operational history; a dry run reports without mutating; disabled = no-op.
 */
class DataRetentionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Baseline: retention on, no policies (each test supplies its own).
        config(['privacy.retention.enabled' => true, 'privacy.retention.policies' => []]);
    }

    private function policy(string $action, array $overrides = []): array
    {
        return array_merge([
            'key' => 'test_'.$action,
            'enabled' => true,
            'status' => ['suspended'],
            'age_field' => 'registration_date',
            'age_days' => 30,
            'action' => $action,
        ], $overrides);
    }

    private function agedSuspended(array $overrides = []): Beneficiary
    {
        // NIN is left to the factory so each record gets a unique (encrypted) value;
        // the tests only assert it is present before and cleared after anonymization.
        return Beneficiary::factory()->create(array_merge([
            'status' => BeneficiaryStatus::Suspended,
            'registration_date' => now()->subDays(60)->toDateString(),
            'first_name' => 'Amina',
            'last_name' => 'Bello',
            'lga' => 'dutse',
        ], $overrides));
    }

    private function enforce(): void
    {
        app(RetentionService::class)->run();
    }

    public function test_flag_marks_the_cohort_for_review_without_changing_data(): void
    {
        config(['privacy.retention.policies' => [$this->policy('flag')]]);
        $target = $this->agedSuspended();
        $young = Beneficiary::factory()->create(['status' => BeneficiaryStatus::Suspended, 'registration_date' => now()->toDateString()]);
        $active = $this->agedSuspended(['status' => BeneficiaryStatus::Active]);

        $this->enforce();

        // Flagged, but no PII changed.
        $target->refresh();
        $this->assertNotNull($target->retention_flagged_at);
        $this->assertSame('Amina', $target->first_name);
        $this->assertNull($target->anonymized_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.retention_flagged', 'entity_id' => $target->id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'retention.run']);

        // Out-of-cohort records untouched (too young / wrong status).
        $this->assertNull($young->refresh()->retention_flagged_at);
        $this->assertNull($active->refresh()->retention_flagged_at);
    }

    public function test_aggregate_removes_direct_identifiers_but_keeps_quasi_for_statistics(): void
    {
        config(['privacy.retention.policies' => [$this->policy('aggregate')]]);
        $target = $this->agedSuspended();

        $this->enforce();
        $target->refresh();

        // Direct identifiers gone…
        $this->assertNull($target->nin);
        $this->assertNull($target->nin_hash);
        $this->assertNotSame('Amina', $target->first_name);
        // …but quasi fields retained so aggregation by LGA still works.
        $this->assertSame('dutse', $target->lga);
        $this->assertNotNull($target->anonymized_at);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.anonymized', 'entity_id' => $target->id]);
    }

    public function test_anonymize_removes_direct_and_quasi_identifiers(): void
    {
        config(['privacy.retention.policies' => [$this->policy('anonymize')]]);
        $target = $this->agedSuspended();

        $this->enforce();
        $target->refresh();

        $this->assertNull($target->nin);
        $this->assertNull($target->lga);          // quasi removed on full anonymize
        $this->assertNotNull($target->anonymized_at);
    }

    public function test_delete_soft_deletes_a_history_free_record(): void
    {
        config(['privacy.retention.policies' => [$this->policy('delete')]]);
        $target = $this->agedSuspended();

        $this->enforce();

        $this->assertSoftDeleted('beneficiaries', ['id' => $target->id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.retention_deleted', 'entity_id' => $target->id]);
    }

    public function test_delete_anonymizes_instead_when_operational_history_exists(): void
    {
        config(['privacy.retention.policies' => [$this->policy('delete')]]);
        $target = $this->agedSuspended();
        $programme = Programme::factory()->individual()->create();
        Enrollment::factory()->create(['programme_id' => $programme->id, 'mda_id' => $target->owner_mda_id, 'beneficiary_id' => $target->id]);
        $benefit = Benefit::factory()->create(['beneficiary_id' => $target->id, 'programme_id' => $programme->id, 'mda_id' => $target->owner_mda_id]);

        $this->enforce();

        // NOT deleted — history must survive — but de-identified instead.
        $this->assertNotSoftDeleted('beneficiaries', ['id' => $target->id]);
        $target->refresh();
        $this->assertNotNull($target->anonymized_at);
        $this->assertNull($target->nin);
        // The benefit ledger row (an aggregate/audit input) is preserved.
        $this->assertDatabaseHas('benefits', ['id' => $benefit->id, 'beneficiary_id' => $target->id]);
    }

    public function test_dry_run_reports_the_cohort_without_mutating(): void
    {
        config(['privacy.retention.policies' => [$this->policy('anonymize')]]);
        $target = $this->agedSuspended();

        $summary = app(RetentionService::class)->run(dryRun: true);

        $this->assertSame(1, $summary->anonymized);
        $this->assertTrue($summary->dryRun);
        $target->refresh();
        $this->assertNull($target->anonymized_at);   // untouched
        $this->assertSame('Amina', $target->first_name);
        $this->assertDatabaseHas('audit_log', ['action' => 'retention.run']);
    }

    public function test_disabled_retention_is_a_no_op(): void
    {
        config(['privacy.retention.enabled' => false, 'privacy.retention.policies' => [$this->policy('anonymize')]]);
        $target = $this->agedSuspended();

        $summary = app(RetentionService::class)->run();

        $this->assertFalse($summary->ranPolicies);
        $this->assertNull($target->refresh()->anonymized_at);
    }
}
