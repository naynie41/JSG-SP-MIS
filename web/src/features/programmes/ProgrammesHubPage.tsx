import { BarChart3, CalendarPlus, ClipboardList, Coins, Upload } from 'lucide-react'
import { SectionHub } from '@/components/SectionHub/SectionHub'
import type { SectionHubItem } from '@/components/SectionHub/SectionHub'
import { useAuth } from '@/lib/auth/AuthProvider'

interface GatedItem extends SectionHubItem {
  permission?: string
}

// Programmes are a global catalog (§10) — this area is operational: browse the
// catalog, run it through MDA activities, and deliver/track benefits. Catalog
// creation/editing lives in Administration, not here.
const ITEMS: GatedItem[] = [
  { label: 'Programme catalog', description: 'Browse the shared catalog of programme types.', to: '/programmes/list', icon: ClipboardList, permission: 'programme.view' },
  { label: 'Activities', description: "Create and manage your MDA's activities against the catalog.", to: '/activities', icon: CalendarPlus, permission: 'activity.view' },
  { label: 'Record benefit', description: 'Log a single benefit delivery to a beneficiary.', to: '/benefits/record', icon: Coins, permission: 'benefit.create' },
  { label: 'Bulk delivery', description: 'Disburse benefits to many beneficiaries at once.', to: '/benefits/bulk', icon: Upload, permission: 'benefit.create' },
  { label: 'Benefit ledger', description: 'Review the full history of benefit payments.', to: '/benefits/ledger', icon: BarChart3, permission: 'benefit.view' },
]

export function ProgrammesHubPage() {
  const { hasPermission } = useAuth()
  const items = ITEMS.filter((item) => !item.permission || hasPermission(item.permission))

  return (
    <SectionHub
      eyebrow="Programmes"
      title="Programmes & delivery"
      lead="Run catalog programmes through your MDA's activities, then deliver and track benefits."
      items={items}
    />
  )
}
