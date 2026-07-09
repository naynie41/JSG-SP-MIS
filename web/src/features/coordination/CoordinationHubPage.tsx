import { LifeBuoy, Split } from 'lucide-react'
import { SectionHub } from '@/components/SectionHub/SectionHub'
import type { SectionHubItem } from '@/components/SectionHub/SectionHub'
import { useAuth } from '@/lib/auth/AuthProvider'

interface GatedItem extends SectionHubItem {
  permission?: string
}

const ITEMS: GatedItem[] = [
  { label: 'Referrals', description: 'Refer beneficiaries to other services and track them.', to: '/referrals', icon: Split, permission: 'referral.view' },
  { label: 'Grievance desk', description: 'Log, triage and resolve beneficiary complaints.', to: '/grievances', icon: LifeBuoy, permission: 'grievance.view' },
]

export function CoordinationHubPage() {
  const { hasPermission } = useAuth()
  const items = ITEMS.filter((item) => !item.permission || hasPermission(item.permission))

  return (
    <SectionHub
      eyebrow="Coordination"
      title="Coordination"
      lead="Connect beneficiaries to services and handle grievances across MDAs."
      items={items}
    />
  )
}
