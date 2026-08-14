<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * The MDA-scope bypass surface, pinned (NFR-SEC-02, SECURITY.md §3).
 *
 * `withoutGlobalScope(MdaScope::class)` is a legitimate and necessary tool — system
 * jobs run unauthenticated, the programme catalogue is global by design, and the
 * governed cross-MDA seams have to reach across MDAs to do their job. It is also the
 * single easiest way to leak another MDA's data by accident.
 *
 * Reading all ~185 call sites once, as pass 1 did, verifies the code on that day and
 * nothing afterwards. This test makes the surface a tracked quantity instead: the set of
 * FILES permitted to bypass is an allow-list, so introducing a bypass in a new file
 * fails here and has to be argued for rather than merged quietly.
 *
 * It deliberately checks files, not counts — adding a bypass inside a file that already
 * governs its access is ordinary work; opening a bypass somewhere new is a decision.
 */
class ScopeBypassSurfaceTest extends TestCase
{
    /**
     * Files permitted to bypass the global MDA scope, grouped by why.
     *
     * @var array<string, list<string>>
     */
    private const ALLOWED = [
        // Unauthenticated execution context: a queue worker or scheduler has no user, so
        // the scope cannot resolve one. Each carries its own explicit constraint.
        'system jobs, schedulers and console commands' => [
            'Console/Commands/PerfBenchmark.php',
            'Domain/Benefit/Jobs/CommitBenefitImport.php',
            'Domain/Benefit/Jobs/ParseBenefitImport.php',
            'Domain/Grievance/Jobs/EscalateOverdueGrievances.php',
            'Domain/Referral/Jobs/EscalateOverdueReferrals.php',
            'Domain/Registry/Jobs/CommitImportBatch.php',
            'Domain/Registry/Jobs/ParseImportBatch.php',
            'Domain/Reporting/Jobs/GenerateReport.php',
            'Domain/Reporting/Listeners/DeliverScheduledReport.php',
            'Domain/Sync/Jobs/RunSyncConnector.php',
            'Domain/Sync/Services/SyncEngine.php',
            'Domain/Notification/Listeners/NotificationSubscriber.php',
            'Domain/Notification/Services/BroadcastService.php',
            'Domain/Privacy/Services/RetentionService.php',
            'Http/Controllers/Api/V1/HealthController.php',
        ],
        // The duplicate/serve seam: matching MUST see across MDAs or the registry would
        // duplicate people. These return reveal-only data, never a full profile.
        'duplicate matching + reveal-only serve seam' => [
            'Domain/Benefit/Imports/BenefitDeliveryRowValidator.php',
            'Domain/Benefit/Services/BeneficiaryRevealPresenter.php',
            'Domain/Registry/Services/BeneficiaryLookupService.php',
            'Domain/Registry/Services/CandidateGatherer.php',
            'Domain/Registry/Services/DeterministicDuplicateFinder.php',
            'Domain/Registry/Support/UniqueIdentifier.php',
            'Domain/Registry/Services/BeneficiaryRegistrar.php',
            'Domain/Registry/Services/HouseholdIngestionService.php',
            'Domain/Registry/Services/ImportCommitter.php',
            'Http/Controllers/Api/V1/Registry/ActivityImportController.php',
            'Http/Controllers/Api/V1/Registry/ImportBatchController.php',
            'Http/Controllers/Api/V1/Registry/BeneficiaryRoutingController.php',
            'Http/Requests/Registry/UploadImportRequest.php',
            // Mapping templates are resolved for the BATCH's owning MDA, which is not
            // necessarily the acting user's scope (the parse job runs on the queue with
            // no authenticated user at all). Every query is filtered on
            // `owner_mda_id = $batch->owner_mda_id`, so the scope is re-applied
            // explicitly rather than widened.
            'Domain/Registry/Services/ImportMappingService.php',
        ],
        // Governed cross-MDA sharing: every one of these resolves through
        // DataSharingGuard or an owner-approval flow before releasing anything.
        'governed cross-MDA sharing' => [
            'Domain/Registry/Services/ServiceRequestService.php',
            'Domain/Registry/Services/OwnershipTransferService.php',
            'Domain/Referral/Services/ReferralService.php',
            'Domain/Referral/Models/Referral.php',
            'Domain/Benefit/Services/BenefitRecorder.php',
            'Domain/Benefit/Policies/BenefitPolicy.php',
            // Revoking a read grant: the grant is ScopedToMda on the GRANTED mda_id, so
            // the scope hides it from the owner MDA entitled to revoke it. The policy
            // reads the beneficiary's owner unscoped precisely to compare against it —
            // it widens nothing; it is the check that decides who may act.
            'Domain/Registry/Policies/OwnerMdaPolicy.php',
            'Http/Controllers/Api/V1/Registry/BeneficiaryController.php',
            'Http/Controllers/Api/V1/Registry/ServiceRequestController.php',
            'Http/Controllers/Api/V1/Registry/OwnershipTransferController.php',
            'Http/Controllers/Api/V1/Sharing/DataSharingController.php',
            'Domain/Privacy/Services/SubjectAccessAssembler.php',
        ],
        // Oversight/aggregate reporting: scope is re-applied as an explicit
        // DashboardScope constraint instead of the implicit global scope.
        'scope-resolved reporting + aggregates' => [
            'Domain/Reporting/Gis/GisCoverageService.php',
            'Domain/Reporting/Reports/AdHoc/AdHocReportBuilder.php',
            'Domain/Reporting/Reports/ReportBuilder.php',
            'Domain/Reporting/Services/AdminOrganizationService.php',
            'Domain/Reporting/Services/AdminSummaryService.php',
            'Domain/Reporting/Services/DashboardMetricsService.php',
            'Domain/Reporting/Services/DashboardScopeResolver.php',
            'Domain/Reporting/Services/DashboardSnapshotService.php',
            'Domain/Reporting/Services/MdaActionRequiredService.php',
            'Domain/Reporting/Services/ReportScheduleService.php',
            'Domain/Benefit/Services/LedgerAggregator.php',
            'Domain/Benefit/Services/DoubleDippingDetector.php',
            'Domain/Graduation/Services/GraduationProgressService.php',
            'Domain/Registry/Export/BeneficiaryListExport.php',
        ],
        // Global-by-design records: the programme catalogue is unowned (§10), and these
        // resolve catalogue/partner/household references that are not MDA-scoped.
        'global catalogue + unowned references' => [
            'Domain/Programme/Rules/IsFundingPartner.php',
            'Domain/Programme/Services/EnrollmentService.php',
            'Domain/Programme/Services/ProgrammeMatcher.php',
            'Domain/Programme/Services/ProgrammeMatchingRouter.php',
            'Domain/Registry/Models/Beneficiary.php',
            'Domain/Registry/Services/HouseholdMembershipService.php',
            'Http/Controllers/Api/V1/Benefit/BenefitController.php',
            'Http/Controllers/Api/V1/Benefit/BenefitImportController.php',
            'Http/Controllers/Api/V1/Programme/ActivityController.php',
            'Http/Controllers/Api/V1/Programme/EnrollmentController.php',
            'Http/Controllers/Api/V1/Registry/BeneficiaryDocumentController.php',
            'Http/Controllers/Api/V1/Registry/HouseholdController.php',
            'Http/Controllers/Api/V1/Registry/HouseholdMemberController.php',
            'Http/Resources/ActivityDetailResource.php',
        ],
    ];

    /** @return list<string> */
    private function allowed(): array
    {
        return array_merge(...array_values(self::ALLOWED));
    }

    /** Every app file that bypasses the global MDA scope, as repo-relative paths. */
    /** @return list<string> */
    private function bypassingFiles(): array
    {
        $found = [];

        foreach (File::allFiles(app_path()) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            if (! str_contains((string) File::get($file->getRealPath()), 'withoutGlobalScope')) {
                continue;
            }
            $found[] = str_replace('\\', '/', substr($file->getRealPath(), strlen(app_path()) + 1));
        }

        sort($found);

        return $found;
    }

    public function test_no_new_file_bypasses_mda_scoping_without_review(): void
    {
        $unreviewed = array_values(array_diff($this->bypassingFiles(), $this->allowed()));

        $this->assertSame([], $unreviewed, implode("\n", [
            'A file outside the reviewed set bypasses the global MDA scope:',
            '  '.implode("\n  ", $unreviewed),
            '',
            'This is not automatically wrong — but it releases data the scope would',
            'otherwise bound, so it needs a reason. Add it to the group in',
            'ScopeBypassSurfaceTest::ALLOWED that matches WHY, or remove the bypass.',
        ]));
    }

    public function test_the_allow_list_has_no_stale_entries(): void
    {
        // A file that stopped bypassing should leave the list, so the list keeps
        // describing the real surface rather than accumulating history.
        $stale = array_values(array_diff($this->allowed(), $this->bypassingFiles()));

        $this->assertSame([], $stale, 'these files no longer bypass the scope: '.implode(', ', $stale));
    }

    public function test_the_allow_list_is_free_of_duplicates(): void
    {
        // The same file listed under two justifications means one of them is wrong.
        $allowed = $this->allowed();

        $this->assertSame(count($allowed), count(array_unique($allowed)));
    }
}
