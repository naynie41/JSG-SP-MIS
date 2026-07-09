import { FileUp, Home, ScanSearch, Send, SlidersHorizontal, UserSquare2 } from 'lucide-react'
import { SectionHub } from '@/components/SectionHub/SectionHub'
import type { SectionHubItem } from '@/components/SectionHub/SectionHub'
import { useAuth } from '@/lib/auth/AuthProvider'

interface GatedItem extends SectionHubItem {
  permission?: string
}

const ITEMS: GatedItem[] = [
  { label: 'Beneficiaries', description: 'Search and manage the single registry of people.', to: '/beneficiaries', icon: UserSquare2, permission: 'beneficiary.view' },
  { label: 'Households', description: 'Household records and their members.', to: '/households', icon: Home, permission: 'household.view' },
  { label: 'Bulk import', description: 'Onboard beneficiaries from a spreadsheet.', to: '/imports', icon: FileUp, permission: 'beneficiary.create' },
  { label: 'Duplicate search', description: 'Check for existing records before enrolling.', to: '/duplicate-search', icon: ScanSearch, permission: 'beneficiary-lookup.view' },
  { label: 'Service requests', description: 'Requests raised on behalf of beneficiaries.', to: '/service-requests', icon: Send, permission: 'beneficiary.view' },
  { label: 'Matching rules', description: 'Tune the deduplication matching thresholds.', to: '/matching', icon: SlidersHorizontal, permission: 'matching.view' },
]

export function RegistryHubPage() {
  const { hasPermission } = useAuth()
  const items = ITEMS.filter((item) => !item.permission || hasPermission(item.permission))

  return (
    <SectionHub
      eyebrow="Registry"
      title="Beneficiary registry"
      lead="The single source of truth for people and households. Pick a task to get started."
      items={items}
    />
  )
}
