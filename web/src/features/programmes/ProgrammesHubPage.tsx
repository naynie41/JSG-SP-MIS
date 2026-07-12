import { BarChart3, CalendarPlus, ClipboardList, Coins, PackageCheck, Upload, Wallet } from 'lucide-react'
import { SectionHub } from '@/components/SectionHub/SectionHub'
import type { SectionHubItem } from '@/components/SectionHub/SectionHub'
import { MetricBand } from '@/components/MetricBand/MetricBand'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { useDashboard } from '@/features/dashboard/hooks'

interface GatedItem extends SectionHubItem {
  permission?: string
}

// Programmes are a global catalog (§10) — this area is operational: browse the
// catalog, run it through MDA activities, and deliver/track benefits. Catalog
// creation/editing lives in Administration, not here.
const ITEMS: GatedItem[] = [
  { label: 'Programme catalog', description: 'Browse the shared catalog of programme types.', to: '/programmes/list', icon: ClipboardList, tone: 'forest', permission: 'programme.view' },
  { label: 'Activities', description: "Create and manage your MDA's activities against the catalog.", to: '/activities', icon: CalendarPlus, tone: 'info', permission: 'activity.view' },
  { label: 'Record benefit', description: 'Log a single benefit delivery to a beneficiary.', to: '/benefits/record', icon: Coins, tone: 'mint', permission: 'benefit.create' },
  { label: 'Bulk delivery', description: 'Disburse benefits to many beneficiaries at once.', to: '/benefits/bulk', icon: Upload, tone: 'mint', permission: 'benefit.create' },
  { label: 'Benefit ledger', description: 'Review the full history of benefit payments.', to: '/benefits/ledger', icon: BarChart3, tone: 'success', permission: 'benefit.view' },
]

/** Scoped programme metrics — only mounted when the user has dashboard.view. */
function ProgrammesMetrics() {
  const { data } = useDashboard(true)
  if (!data) return null
  const m = data.metrics
  return (
    <MetricBand
      items={[
        { icon: ClipboardList, label: 'Active programmes', value: m.programmes.active.toLocaleString(), tone: 'info' },
        { icon: PackageCheck, label: 'Deliveries', value: m.benefits.disbursed.benefit_count.toLocaleString(), tone: 'success' },
        { icon: Coins, label: 'Benefits disbursed', value: formatNaira(m.benefits.disbursed.total_value), tone: 'mint' },
        { icon: Wallet, label: 'Budget used', value: `${Math.round((m.benefits.budget.utilization_rate ?? 0) * 100)}%`, tone: 'forest' },
      ]}
    />
  )
}

export function ProgrammesHubPage() {
  const { hasPermission } = useAuth()
  const items = ITEMS.filter((item) => !item.permission || hasPermission(item.permission))

  return (
    <SectionHub
      eyebrow="Programmes"
      title="Programmes & delivery"
      lead="Run catalog programmes through your MDA's activities, then deliver and track benefits."
      metrics={hasPermission('dashboard.view') ? <ProgrammesMetrics /> : undefined}
      items={items}
    />
  )
}
