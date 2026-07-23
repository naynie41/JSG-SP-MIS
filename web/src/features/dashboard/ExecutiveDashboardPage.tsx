import { Card } from '@/components/Card/Card'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ExecutiveDashboardView } from './ExecutiveDashboardView'
import { useDashboard } from './hooks'
import styles from './executive.module.css'

/**
 * Executive dashboard (PRD FR-RPT-01). Read-only, state-wide briefing from the
 * reporting aggregation layer, presented as an editorial "state-of-the-state" report
 * for the governor and commissioners ({@see ExecutiveDashboardView}) — visually
 * distinct from the Admin/MDA dashboards. Executive role only; no edit controls.
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
        <Spinner size={28} label="Loading briefing" />
      </div>
    )
  }

  return <ExecutiveDashboardView data={data} isFetching={isFetching} onRefresh={() => refetch()} />
}
