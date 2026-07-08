import { Card } from '@/components/Card/Card'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { DashboardView } from './DashboardView'
import { useDashboard } from './hooks'
import styles from './dashboard.module.css'

/**
 * MDA dashboard (PRD FR-RPT-02). The same metric families as the executive view but
 * scoped by the aggregation layer to the caller's MDA — its programmes, beneficiaries
 * (owned + served), benefits, budget, referrals (in/out) and grievances. Read-only;
 * the server enforces the scope, so a user never sees another MDA's data.
 */
export function MdaDashboardPage() {
  const { user, hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const { data, isLoading, isFetching, refetch } = useDashboard(canView)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view a dashboard.</p>
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
      title={user?.mda?.name ? `${user.mda.name} dashboard` : 'MDA dashboard'}
      lead="Your MDA's programmes, beneficiaries, benefits delivered, budget utilisation, referrals and grievances. Scoped to your MDA."
      data={data}
      isFetching={isFetching}
      onRefresh={() => refetch()}
    />
  )
}
