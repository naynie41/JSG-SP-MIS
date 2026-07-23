import type { ReactNode } from 'react'
import { Navigate, Route, Routes } from 'react-router-dom'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { LoginPage } from '@/features/auth/LoginPage'
import { DashboardPage } from '@/features/dashboard/DashboardPage'
import { AdminDashboardPage } from '@/features/dashboard/AdminDashboardPage'
import { ExecutiveDashboardPage } from '@/features/dashboard/ExecutiveDashboardPage'
import { MdaDashboardPage } from '@/features/dashboard/MdaDashboardPage'
import { PartnerDashboardPage } from '@/features/dashboard/PartnerDashboardPage'
import { NotFoundPage } from '@/features/misc/NotFoundPage'
import { StyleguidePage } from '@/features/styleguide/StyleguidePage'
import { MdaListPage } from '@/features/mdas/MdaListPage'
import { UserListPage } from '@/features/users/UserListPage'
import { RolesPage } from '@/features/access/RolesPage'
import { PermissionsPage } from '@/features/access/PermissionsPage'
import { GrantsPage } from '@/features/access/GrantsPage'
import { BeneficiaryListPage } from '@/features/registry/BeneficiaryListPage'
import { BeneficiaryDetailPage } from '@/features/registry/BeneficiaryDetailPage'
import { HouseholdListPage } from '@/features/registry/HouseholdListPage'
import { HouseholdDetailPage } from '@/features/registry/HouseholdDetailPage'
import { ImportListPage } from '@/features/registry/ImportListPage'
import { ImportBatchPage } from '@/features/registry/ImportBatchPage'
import { DuplicateSearchPage } from '@/features/registry/DuplicateSearchPage'
import { ServiceRequestsPage } from '@/features/registry/ServiceRequestsPage'
import { MatchingConfigPage } from '@/features/registry/MatchingConfigPage'
import { RegistryHubPage } from '@/features/registry/RegistryHubPage'
import { CoordinationHubPage } from '@/features/coordination/CoordinationHubPage'
import { ProgrammesHubPage } from '@/features/programmes/ProgrammesHubPage'
import { ProgrammeListPage } from '@/features/programmes/ProgrammeListPage'
import { ProgrammeDetailPage } from '@/features/programmes/ProgrammeDetailPage'
import { ActivitiesPage } from '@/features/programmes/ActivitiesPage'
import { ActivityDetailPage } from '@/features/programmes/ActivityDetailPage'
import { RecordBenefitPage } from '@/features/benefits/RecordBenefitPage'
import { BulkDeliveryPage } from '@/features/benefits/BulkDeliveryPage'
import { BenefitLedgerPage } from '@/features/benefits/BenefitLedgerPage'
import { ReferralsPage } from '@/features/referrals/ReferralsPage'
import { ReferralDetailPage } from '@/features/referrals/ReferralDetailPage'
import { GrievanceDeskPage } from '@/features/grievances/GrievanceDeskPage'
import { GrievanceDetailPage } from '@/features/grievances/GrievanceDetailPage'
import { GisDashboardPage } from '@/features/gis/GisDashboardPage'
import { AppLayout } from './AppLayout'
import { ProtectedRoute } from './ProtectedRoute'

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
  if (roleKey === 'system_administrator') return <AdminDashboardPage />
  if (roleKey === 'executive') return <ExecutiveDashboardPage />
  if (roleKey === 'development_partner') return <PartnerDashboardPage />
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
        <Route path="/beneficiaries" element={<BeneficiaryListPage />} />
        <Route path="/beneficiaries/:id" element={<BeneficiaryDetailPage />} />
        <Route path="/households" element={<HouseholdListPage />} />
        <Route path="/households/:id" element={<HouseholdDetailPage />} />
        <Route path="/imports" element={<ImportListPage />} />
        <Route path="/imports/:id" element={<ImportBatchPage />} />
        <Route path="/duplicate-search" element={<DuplicateSearchPage />} />
        <Route path="/service-requests" element={<ServiceRequestsPage />} />
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
