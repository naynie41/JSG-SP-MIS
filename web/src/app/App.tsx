import type { ReactNode } from 'react'
import { Navigate, Route, Routes } from 'react-router-dom'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { LoginPage } from '@/features/auth/LoginPage'
import { DashboardPage } from '@/features/dashboard/DashboardPage'
import { PlaceholderPage } from '@/features/misc/PlaceholderPage'
import { NotFoundPage } from '@/features/misc/NotFoundPage'
import { StyleguidePage } from '@/features/styleguide/StyleguidePage'
import { MdaListPage } from '@/features/mdas/MdaListPage'
import { UserListPage } from '@/features/users/UserListPage'
import { BeneficiaryListPage } from '@/features/registry/BeneficiaryListPage'
import { BeneficiaryDetailPage } from '@/features/registry/BeneficiaryDetailPage'
import { HouseholdListPage } from '@/features/registry/HouseholdListPage'
import { HouseholdDetailPage } from '@/features/registry/HouseholdDetailPage'
import { ImportListPage } from '@/features/registry/ImportListPage'
import { ImportBatchPage } from '@/features/registry/ImportBatchPage'
import { DuplicateSearchPage } from '@/features/registry/DuplicateSearchPage'
import { ServiceRequestsPage } from '@/features/registry/ServiceRequestsPage'
import { MatchingConfigPage } from '@/features/registry/MatchingConfigPage'
import { ProgrammeListPage } from '@/features/programmes/ProgrammeListPage'
import { ProgrammeDetailPage } from '@/features/programmes/ProgrammeDetailPage'
import { RecordBenefitPage } from '@/features/benefits/RecordBenefitPage'
import { BulkDeliveryPage } from '@/features/benefits/BulkDeliveryPage'
import { BenefitLedgerPage } from '@/features/benefits/BenefitLedgerPage'
import { AppLayout } from './AppLayout'
import { ProtectedRoute } from './ProtectedRoute'

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
        <Route index element={<DashboardPage />} />
        <Route path="/beneficiaries" element={<BeneficiaryListPage />} />
        <Route path="/beneficiaries/:id" element={<BeneficiaryDetailPage />} />
        <Route path="/households" element={<HouseholdListPage />} />
        <Route path="/households/:id" element={<HouseholdDetailPage />} />
        <Route path="/imports" element={<ImportListPage />} />
        <Route path="/imports/:id" element={<ImportBatchPage />} />
        <Route path="/duplicate-search" element={<DuplicateSearchPage />} />
        <Route path="/service-requests" element={<ServiceRequestsPage />} />
        <Route path="/matching" element={<MatchingConfigPage />} />
        <Route path="/programmes" element={<ProgrammeListPage />} />
        <Route path="/programmes/:id" element={<ProgrammeDetailPage />} />
        <Route path="/benefits/record" element={<RecordBenefitPage />} />
        <Route path="/benefits/bulk" element={<BulkDeliveryPage />} />
        <Route path="/benefits/ledger" element={<BenefitLedgerPage />} />
        <Route path="/users" element={<UserListPage />} />
        <Route path="/mdas" element={<MdaListPage />} />
        <Route path="/roles" element={<PlaceholderPage eyebrow="02 · Administration" title="Roles" />} />
        <Route path="/permissions" element={<PlaceholderPage eyebrow="02 · Administration" title="Permissions" />} />
        <Route path="/grants" element={<PlaceholderPage eyebrow="02 · Administration" title="Cross-MDA access" />} />
        <Route path="/styleguide" element={<StyleguidePage />} />
      </Route>

      <Route path="*" element={<NotFoundPage />} />
    </Routes>
  )
}
