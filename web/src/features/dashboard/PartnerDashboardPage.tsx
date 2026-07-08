import { Card } from '@/components/Card/Card'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { DashboardView } from './DashboardView'
import { useDashboard } from './hooks'
import styles from './dashboard.module.css'

/**
 * Development-Partner dashboard (PRD FR-RPT-02). Scoped by the aggregation layer to
 * the partner's FUNDED programmes only (the 6.0 funding-scope rule): coverage, budget
 * utilisation and delivery performance for those programmes — and nothing outside
 * them. Coordination metrics (referrals/grievances) don't apply and are omitted.
 * Read-only; the server enforces the scope.
 */
export function PartnerDashboardPage() {
  const { user, hasPermission } = useAuth()
  const isPartner = user?.role?.key === 'development_partner'
  const { data, isLoading, isFetching, refetch } = useDashboard(isPartner && hasPermission('dashboard.view'))

  if (!isPartner) {
    return (
      <Card>
        <p className={styles.forbidden}>The partner dashboard is available to Development Partner users only.</p>
      </Card>
    )
  }

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} label="Loading dashboard" />
      </div>
    )
  }

  return (
    <DashboardView
      eyebrow="06 · Reporting"
      title="Partner dashboard"
      lead="Coverage, budget utilisation and delivery performance for the programmes you fund. Scoped to your funded programmes only."
      beneficiariesLabel="Beneficiaries served"
      data={data}
      isFetching={isFetching}
      onRefresh={() => refetch()}
    />
  )
}
