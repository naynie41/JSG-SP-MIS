import { Card } from '@/components/Card/Card'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { DashboardView } from './DashboardView'
import { useDashboard } from './hooks'
import styles from './dashboard.module.css'

/**
 * Executive dashboard (PRD FR-RPT-01). Read-only, state-wide KPIs from the reporting
 * aggregation layer. Executive role only; there are no edit controls.
 */
export function ExecutiveDashboardPage() {
  const { user, hasPermission } = useAuth()
  const isExecutive = user?.role?.key === 'executive'
  const { data, isLoading, isFetching, refetch } = useDashboard(isExecutive && hasPermission('dashboard.view'))

  if (!isExecutive) {
    return (
      <Card>
        <p className={styles.forbidden}>The executive dashboard is available to Executive users only.</p>
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
      title="Executive dashboard"
      lead="State-wide social protection at a glance — beneficiaries, programmes, benefits delivered, budget utilisation and coverage across all MDAs. Read-only."
      data={data}
      isFetching={isFetching}
      onRefresh={() => refetch()}
    />
  )
}
