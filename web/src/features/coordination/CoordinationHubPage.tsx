import { AlertTriangle, Clock, LifeBuoy, Split } from 'lucide-react'
import { SectionHub } from '@/components/SectionHub/SectionHub'
import type { SectionHubItem } from '@/components/SectionHub/SectionHub'
import { MetricBand } from '@/components/MetricBand/MetricBand'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useDashboard } from '@/features/dashboard/hooks'

interface GatedItem extends SectionHubItem {
  permission?: string
}

const ITEMS: GatedItem[] = [
  { label: 'Referrals', description: 'Refer beneficiaries to other services and track them.', to: '/referrals', icon: Split, tone: 'info', permission: 'referral.view' },
  { label: 'Grievance desk', description: 'Log, triage and resolve beneficiary complaints.', to: '/grievances', icon: LifeBuoy, tone: 'mint', permission: 'grievance.view' },
]

/** Scoped coordination metrics — only mounted when the user has dashboard.view. */
function CoordinationMetrics() {
  const { data } = useDashboard(true)
  if (!data) return null
  const { referrals, grievances } = data.metrics
  if (referrals === null && grievances === null) return null
  return (
    <MetricBand
      items={[
        { icon: Split, label: 'Referrals', value: (referrals?.total ?? 0).toLocaleString(), tone: 'info' },
        { icon: Clock, label: 'Overdue referrals', value: (referrals?.overdue ?? 0).toLocaleString(), tone: 'warning' },
        { icon: LifeBuoy, label: 'Grievances', value: (grievances?.total ?? 0).toLocaleString(), tone: 'info' },
        { icon: AlertTriangle, label: 'SLA breaches', value: (grievances?.sla_breaches ?? 0).toLocaleString(), tone: 'danger' },
      ]}
    />
  )
}

export function CoordinationHubPage() {
  const { hasPermission } = useAuth()
  const items = ITEMS.filter((item) => !item.permission || hasPermission(item.permission))

  return (
    <SectionHub
      eyebrow="Coordination"
      title="Coordination"
      lead="Connect beneficiaries to services and handle grievances across MDAs."
      metrics={hasPermission('dashboard.view') ? <CoordinationMetrics /> : undefined}
      items={items}
    />
  )
}
