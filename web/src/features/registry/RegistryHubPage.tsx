import { CheckCircle2, FileUp, Home, ScanSearch, Send, SlidersHorizontal, UserSquare2 } from 'lucide-react'
import { SectionHub } from '@/components/SectionHub/SectionHub'
import type { SectionHubItem } from '@/components/SectionHub/SectionHub'
import { MetricBand } from '@/components/MetricBand/MetricBand'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useDashboard } from '@/features/dashboard/hooks'

interface GatedItem extends SectionHubItem {
  permission?: string
  /** Needs an acting MDA (mirrors the server); hidden from MDA-less accounts. */
  requiresMda?: boolean
}

const ITEMS: GatedItem[] = [
  { label: 'Beneficiaries', description: 'Search and manage the single registry of people.', to: '/beneficiaries', icon: UserSquare2, tone: 'forest', permission: 'beneficiary.view' },
  { label: 'Households', description: 'Household records and their members.', to: '/households', icon: Home, tone: 'info', permission: 'household.view' },
  { label: 'Bulk import', description: 'Onboard beneficiaries from a spreadsheet.', to: '/imports', icon: FileUp, tone: 'mint', permission: 'beneficiary.create', requiresMda: true },
  { label: 'Duplicate search', description: 'Check for existing records before enrolling.', to: '/duplicate-search', icon: ScanSearch, tone: 'warning', permission: 'beneficiary-lookup.view' },
  { label: 'Service requests', description: 'Requests raised on behalf of beneficiaries.', to: '/service-requests', icon: Send, tone: 'info', permission: 'beneficiary.view' },
  { label: 'Matching rules', description: 'Tune the deduplication matching thresholds.', to: '/matching', icon: SlidersHorizontal, tone: 'success', permission: 'matching.view' },
]

/** Scoped registry metrics — only mounted when the user has dashboard.view. */
function RegistryMetrics() {
  const { data } = useDashboard(true)
  if (!data) return null
  const m = data.metrics
  return (
    <MetricBand
      items={[
        { icon: UserSquare2, label: 'Beneficiaries', value: m.registry.beneficiaries.total.toLocaleString(), tone: 'forest' },
        { icon: Home, label: 'Households', value: (m.registry.households?.total ?? 0).toLocaleString(), tone: 'info' },
        { icon: ScanSearch, label: 'Duplicates surfaced', value: (m.duplicates?.matches_surfaced ?? 0).toLocaleString(), tone: 'warning' },
        { icon: CheckCircle2, label: 'Served', value: (m.duplicates?.resolved_served ?? 0).toLocaleString(), tone: 'success' },
      ]}
    />
  )
}

export function RegistryHubPage() {
  const { user, hasPermission } = useAuth()
  const hasMda = Boolean(user?.mda)
  const items = ITEMS.filter(
    (item) => (!item.permission || hasPermission(item.permission)) && (!item.requiresMda || hasMda),
  )

  return (
    <SectionHub
      eyebrow="Registry"
      title="Beneficiary registry"
      lead="The single source of truth for people and households. Pick a task to get started."
      metrics={hasPermission('dashboard.view') ? <RegistryMetrics /> : undefined}
      items={items}
    />
  )
}
