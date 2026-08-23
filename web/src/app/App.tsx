import { lazy } from 'react'
import type { ReactNode } from 'react'
import { Navigate, Route, Routes } from 'react-router-dom'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { isMdaRole } from '@/features/mda/roles'
import { LoginPage } from '@/features/auth/LoginPage'
import { NotFoundPage } from '@/features/misc/NotFoundPage'
import { AppLayout } from './AppLayout'
import { ProtectedRoute } from './ProtectedRoute'

/*
 * Route-level code splitting.
 *
 * Every page used to be a static import, so one chunk carried the whole product:
 * opening Settings also downloaded the GIS map, both dashboard suites and every
 * registry screen. The shell (login, layout, guard, 404) stays eager because it is
 * needed for first paint; everything reachable by navigation is split.
 *
 * These modules use NAMED exports, so each loader maps the name onto `default`.
 * The suspense boundary lives in AppLayout, around the <Outlet/>, so the navigation
 * rail and top bar stay on screen while the next page arrives.
 */
const DashboardPage = lazy(() => import('@/features/dashboard/DashboardPage').then((m) => ({ default: m.DashboardPage })))
const MdaDashboardPage = lazy(() => import('@/features/dashboard/MdaDashboardPage').then((m) => ({ default: m.MdaDashboardPage })))

const AdminLayout = lazy(() => import('@/features/admin/AdminLayout').then((m) => ({ default: m.AdminLayout })))
const AdminOverviewPage = lazy(() => import('@/features/admin/AdminOverviewPage').then((m) => ({ default: m.AdminOverviewPage })))
const AdminAccessPage = lazy(() => import('@/features/admin/AdminAccessPage').then((m) => ({ default: m.AdminAccessPage })))
const AdminOrganizationPage = lazy(() => import('@/features/admin/AdminOrganizationPage').then((m) => ({ default: m.AdminOrganizationPage })))
const AdminCatalogPage = lazy(() => import('@/features/admin/AdminCatalogPage').then((m) => ({ default: m.AdminCatalogPage })))
const AdminRegistryPage = lazy(() => import('@/features/admin/AdminRegistryPage').then((m) => ({ default: m.AdminRegistryPage })))
const AdminMatchingPage = lazy(() => import('@/features/admin/AdminMatchingPage').then((m) => ({ default: m.AdminMatchingPage })))
const AdminAuditPage = lazy(() => import('@/features/admin/AdminAuditPage').then((m) => ({ default: m.AdminAuditPage })))
const AdminIntegrationsPage = lazy(() => import('@/features/admin/AdminIntegrationsPage').then((m) => ({ default: m.AdminIntegrationsPage })))
const AdminReportsPage = lazy(() => import('@/features/admin/AdminReportsPage').then((m) => ({ default: m.AdminReportsPage })))
const AdminSettingsPage = lazy(() => import('@/features/admin/AdminSettingsPage').then((m) => ({ default: m.AdminSettingsPage })))

const MdaLayout = lazy(() => import('@/features/mda/MdaLayout').then((m) => ({ default: m.MdaLayout })))
const MdaOverviewPage = lazy(() => import('@/features/mda/MdaOverviewPage').then((m) => ({ default: m.MdaOverviewPage })))
const MdaProgrammesPage = lazy(() => import('@/features/mda/MdaProgrammesPage').then((m) => ({ default: m.MdaProgrammesPage })))
const MdaProgrammeDetailPage = lazy(() => import('@/features/mda/MdaProgrammeDetailPage').then((m) => ({ default: m.MdaProgrammeDetailPage })))
const MdaBeneficiariesPage = lazy(() => import('@/features/mda/MdaBeneficiariesPage').then((m) => ({ default: m.MdaBeneficiariesPage })))
const MdaServiceDeliveryPage = lazy(() => import('@/features/mda/MdaServiceDeliveryPage').then((m) => ({ default: m.MdaServiceDeliveryPage })))
const MdaDuplicateResolutionPage = lazy(() => import('@/features/mda/MdaDuplicateResolutionPage').then((m) => ({ default: m.MdaDuplicateResolutionPage })))
const MdaReportsPage = lazy(() => import('@/features/mda/MdaReportsPage').then((m) => ({ default: m.MdaReportsPage })))
const MdaSettingsPage = lazy(() => import('@/features/mda/MdaSettingsPage').then((m) => ({ default: m.MdaSettingsPage })))

const ExecutiveLayout = lazy(() => import('@/features/dashboard/ExecutiveLayout').then((m) => ({ default: m.ExecutiveLayout })))
const ExecutiveOverviewPage = lazy(() => import('@/features/dashboard/executivePages').then((m) => ({ default: m.ExecutiveOverviewPage })))
const ExecutiveProgrammesPage = lazy(() => import('@/features/dashboard/executivePages').then((m) => ({ default: m.ExecutiveProgrammesPage })))
const ExecutiveRegistryPage = lazy(() => import('@/features/dashboard/executivePages').then((m) => ({ default: m.ExecutiveRegistryPage })))
const ExecutiveCoordinationPage = lazy(() => import('@/features/dashboard/executivePages').then((m) => ({ default: m.ExecutiveCoordinationPage })))
const ExecutiveCoveragePage = lazy(() => import('@/features/dashboard/executivePages').then((m) => ({ default: m.ExecutiveCoveragePage })))

const PartnerLayout = lazy(() => import('@/features/dashboard/PartnerLayout').then((m) => ({ default: m.PartnerLayout })))
const PartnerOverviewPage = lazy(() => import('@/features/dashboard/partnerPages').then((m) => ({ default: m.PartnerOverviewPage })))
const PartnerProgrammesPage = lazy(() => import('@/features/dashboard/partnerPages').then((m) => ({ default: m.PartnerProgrammesPage })))
const PartnerRegistryPage = lazy(() => import('@/features/dashboard/partnerPages').then((m) => ({ default: m.PartnerRegistryPage })))
const PartnerCoordinationPage = lazy(() => import('@/features/dashboard/partnerPages').then((m) => ({ default: m.PartnerCoordinationPage })))
const PartnerInvestmentPage = lazy(() => import('@/features/dashboard/partnerPages').then((m) => ({ default: m.PartnerInvestmentPage })))

const StyleguidePage = lazy(() => import('@/features/styleguide/StyleguidePage').then((m) => ({ default: m.StyleguidePage })))
const MdaListPage = lazy(() => import('@/features/mdas/MdaListPage').then((m) => ({ default: m.MdaListPage })))
const UserListPage = lazy(() => import('@/features/users/UserListPage').then((m) => ({ default: m.UserListPage })))
const RolesPage = lazy(() => import('@/features/access/RolesPage').then((m) => ({ default: m.RolesPage })))
const PermissionsPage = lazy(() => import('@/features/access/PermissionsPage').then((m) => ({ default: m.PermissionsPage })))
const GrantsPage = lazy(() => import('@/features/access/GrantsPage').then((m) => ({ default: m.GrantsPage })))

const BeneficiaryListPage = lazy(() => import('@/features/registry/BeneficiaryListPage').then((m) => ({ default: m.BeneficiaryListPage })))
const BeneficiaryDetailPage = lazy(() => import('@/features/registry/BeneficiaryDetailPage').then((m) => ({ default: m.BeneficiaryDetailPage })))
const HouseholdListPage = lazy(() => import('@/features/registry/HouseholdListPage').then((m) => ({ default: m.HouseholdListPage })))
const HouseholdDetailPage = lazy(() => import('@/features/registry/HouseholdDetailPage').then((m) => ({ default: m.HouseholdDetailPage })))
const ImportListPage = lazy(() => import('@/features/registry/ImportListPage').then((m) => ({ default: m.ImportListPage })))
const ImportBatchPage = lazy(() => import('@/features/registry/ImportBatchPage').then((m) => ({ default: m.ImportBatchPage })))
const AdjudicateQueuePage = lazy(() => import('@/features/registry/AdjudicateQueuePage').then((m) => ({ default: m.AdjudicateQueuePage })))
const DuplicateSearchPage = lazy(() => import('@/features/registry/DuplicateSearchPage').then((m) => ({ default: m.DuplicateSearchPage })))
const ServiceRequestsPage = lazy(() => import('@/features/registry/ServiceRequestsPage').then((m) => ({ default: m.ServiceRequestsPage })))
const NotificationPreferencesPage = lazy(() => import('@/features/notifications/NotificationPreferencesPage').then((m) => ({ default: m.NotificationPreferencesPage })))
const MatchingConfigPage = lazy(() => import('@/features/registry/MatchingConfigPage').then((m) => ({ default: m.MatchingConfigPage })))
const RegistryHubPage = lazy(() => import('@/features/registry/RegistryHubPage').then((m) => ({ default: m.RegistryHubPage })))

const CoordinationHubPage = lazy(() => import('@/features/coordination/CoordinationHubPage').then((m) => ({ default: m.CoordinationHubPage })))
const ProgrammesHubPage = lazy(() => import('@/features/programmes/ProgrammesHubPage').then((m) => ({ default: m.ProgrammesHubPage })))
const ProgrammeListPage = lazy(() => import('@/features/programmes/ProgrammeListPage').then((m) => ({ default: m.ProgrammeListPage })))
const ProgrammeDetailPage = lazy(() => import('@/features/programmes/ProgrammeDetailPage').then((m) => ({ default: m.ProgrammeDetailPage })))
const ActivitiesPage = lazy(() => import('@/features/programmes/ActivitiesPage').then((m) => ({ default: m.ActivitiesPage })))
const ActivityDetailPage = lazy(() => import('@/features/programmes/ActivityDetailPage').then((m) => ({ default: m.ActivityDetailPage })))

const RecordBenefitPage = lazy(() => import('@/features/benefits/RecordBenefitPage').then((m) => ({ default: m.RecordBenefitPage })))
const BulkDeliveryPage = lazy(() => import('@/features/benefits/BulkDeliveryPage').then((m) => ({ default: m.BulkDeliveryPage })))
const BenefitLedgerPage = lazy(() => import('@/features/benefits/BenefitLedgerPage').then((m) => ({ default: m.BenefitLedgerPage })))
const ReferralsPage = lazy(() => import('@/features/referrals/ReferralsPage').then((m) => ({ default: m.ReferralsPage })))
const ReferralDetailPage = lazy(() => import('@/features/referrals/ReferralDetailPage').then((m) => ({ default: m.ReferralDetailPage })))
const GrievanceDeskPage = lazy(() => import('@/features/grievances/GrievanceDeskPage').then((m) => ({ default: m.GrievanceDeskPage })))
const GrievanceDetailPage = lazy(() => import('@/features/grievances/GrievanceDetailPage').then((m) => ({ default: m.GrievanceDetailPage })))
const GisDashboardPage = lazy(() => import('@/features/gis/GisDashboardPage').then((m) => ({ default: m.GisDashboardPage })))

/**
 * Home landing, by role/scope: System Administrators get the administration
 * dashboard (state-wide + platform health, no MDA-operator actions), Executives the
 * state-wide dashboard, Development Partners the funded-programmes dashboard, other
 * dashboard-permitted users the MDA-scoped dashboard, and everyone else the account
 * view. The server resolves and enforces the actual data scope regardless of which
 * page renders.
 */
function HomeDashboard() {
  const { user, hasPermission } = useAuth()
  const roleKey = user?.role?.key
  if (roleKey === 'system_administrator') return <Navigate to="/admin" replace />
  if (roleKey === 'executive') return <Navigate to="/executive" replace />
  if (roleKey === 'development_partner') return <Navigate to="/partner" replace />
  if (isMdaRole(roleKey)) return <Navigate to="/mda" replace />
  if (hasPermission('dashboard.view')) return <MdaDashboardPage />
  return <DashboardPage />
}

/** Keeps authenticated users away from /login. */
function PublicOnlyRoute({ children }: { children: ReactNode }) {
  const { status } = useAuth()
  if (status === 'loading') {
    return (
      <div style={{ display: 'grid', placeItems: 'center', minHeight: '100vh' }}>
        <Spinner size={28} />
      </div>
    )
  }
  if (status === 'authenticated') {
    return <Navigate to="/" replace />
  }
  return <>{children}</>
}

export function App() {
  return (
    <Routes>
      <Route
        path="/login"
        element={
          <PublicOnlyRoute>
            <LoginPage />
          </PublicOnlyRoute>
        }
      />

      <Route
        element={
          <ProtectedRoute>
            <AppLayout />
          </ProtectedRoute>
        }
      >
        <Route index element={<HomeDashboard />} />
        <Route path="/admin" element={<AdminLayout />}>
          <Route index element={<AdminOverviewPage />} />
          <Route path="access" element={<AdminAccessPage />} />
          <Route path="organization" element={<AdminOrganizationPage />} />
          <Route path="catalog" element={<AdminCatalogPage />} />
          <Route path="registry" element={<AdminRegistryPage />} />
          <Route path="integrations" element={<AdminIntegrationsPage />} />
          <Route path="matching" element={<AdminMatchingPage />} />
          <Route path="audit" element={<AdminAuditPage />} />
          <Route path="reports" element={<AdminReportsPage />} />
          <Route path="settings" element={<AdminSettingsPage />} />
        </Route>
        <Route path="/mda" element={<MdaLayout />}>
          <Route index element={<MdaOverviewPage />} />
          <Route path="programmes" element={<MdaProgrammesPage />} />
          <Route path="programmes/:id" element={<MdaProgrammeDetailPage />} />
          <Route path="beneficiaries" element={<MdaBeneficiariesPage />} />
          <Route path="service-delivery" element={<MdaServiceDeliveryPage />} />
          <Route path="duplicate-resolution" element={<MdaDuplicateResolutionPage />} />
          <Route path="reports" element={<MdaReportsPage />} />
          <Route path="settings" element={<MdaSettingsPage />} />
        </Route>
        <Route path="/executive" element={<ExecutiveLayout />}>
          <Route index element={<ExecutiveOverviewPage />} />
          <Route path="programmes" element={<ExecutiveProgrammesPage />} />
          <Route path="registry" element={<ExecutiveRegistryPage />} />
          <Route path="coordination" element={<ExecutiveCoordinationPage />} />
          <Route path="coverage" element={<ExecutiveCoveragePage />} />
        </Route>
        <Route path="/partner" element={<PartnerLayout />}>
          <Route index element={<PartnerOverviewPage />} />
          <Route path="programmes" element={<PartnerProgrammesPage />} />
          <Route path="registry" element={<PartnerRegistryPage />} />
          <Route path="coordination" element={<PartnerCoordinationPage />} />
          <Route path="investment" element={<PartnerInvestmentPage />} />
        </Route>
        <Route path="/beneficiaries" element={<BeneficiaryListPage />} />
        <Route path="/beneficiaries/:id" element={<BeneficiaryDetailPage />} />
        <Route path="/households" element={<HouseholdListPage />} />
        <Route path="/households/:id" element={<HouseholdDetailPage />} />
        <Route path="/imports" element={<ImportListPage />} />
        <Route path="/imports/:id" element={<ImportBatchPage />} />
        {/* One flagged row per screen — the adjudication queue (FR-DUP-09). */}
        <Route path="/imports/:id/adjudicate" element={<AdjudicateQueuePage />} />
        <Route path="/duplicate-search" element={<DuplicateSearchPage />} />
        <Route path="/service-requests" element={<ServiceRequestsPage />} />
        {/* Addressable so notification emails can point somewhere real (FR-NOT-02). */}
        <Route path="/settings/notifications" element={<NotificationPreferencesPage />} />
        <Route path="/matching" element={<MatchingConfigPage />} />
        <Route path="/registry" element={<RegistryHubPage />} />
        <Route path="/coordination" element={<CoordinationHubPage />} />
        <Route path="/programmes" element={<ProgrammesHubPage />} />
        <Route path="/programmes/list" element={<ProgrammeListPage />} />
        <Route path="/programmes/:id" element={<ProgrammeDetailPage />} />
        <Route path="/activities" element={<ActivitiesPage />} />
        <Route path="/activities/:id" element={<ActivityDetailPage />} />
        <Route path="/benefits/record" element={<RecordBenefitPage />} />
        <Route path="/benefits/bulk" element={<BulkDeliveryPage />} />
        <Route path="/benefits/ledger" element={<BenefitLedgerPage />} />
        <Route path="/referrals" element={<ReferralsPage />} />
        <Route path="/referrals/:id" element={<ReferralDetailPage />} />
        <Route path="/grievances" element={<GrievanceDeskPage />} />
        <Route path="/grievances/:id" element={<GrievanceDetailPage />} />
        <Route path="/gis" element={<GisDashboardPage />} />
        <Route path="/users" element={<UserListPage />} />
        <Route path="/mdas" element={<MdaListPage />} />
        <Route path="/roles" element={<RolesPage />} />
        <Route path="/permissions" element={<PermissionsPage />} />
        <Route path="/grants" element={<GrantsPage />} />
        <Route path="/styleguide" element={<StyleguidePage />} />
      </Route>

      <Route path="*" element={<NotFoundPage />} />
    </Routes>
  )
}
