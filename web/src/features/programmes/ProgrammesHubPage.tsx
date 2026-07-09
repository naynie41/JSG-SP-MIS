import { useNavigate } from 'react-router-dom'
import { BarChart3, ClipboardList, Coins, Plus, Upload } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { SectionHub } from '@/components/SectionHub/SectionHub'
import type { SectionHubItem } from '@/components/SectionHub/SectionHub'
import { useAuth } from '@/lib/auth/AuthProvider'

interface GatedItem extends SectionHubItem {
  permission?: string
}

const ITEMS: GatedItem[] = [
  { label: 'Programmes', description: 'Browse programmes and manage their activities.', to: '/programmes/list', icon: ClipboardList, permission: 'programme.view' },
  { label: 'Record benefit', description: 'Log a single benefit delivery to a beneficiary.', to: '/benefits/record', icon: Coins, permission: 'benefit.create' },
  { label: 'Bulk delivery', description: 'Disburse benefits to many beneficiaries at once.', to: '/benefits/bulk', icon: Upload, permission: 'benefit.create' },
  { label: 'Benefit ledger', description: 'Review the full history of benefit payments.', to: '/benefits/ledger', icon: BarChart3, permission: 'benefit.view' },
]

export function ProgrammesHubPage() {
  const { hasPermission } = useAuth()
  const navigate = useNavigate()
  const items = ITEMS.filter((item) => !item.permission || hasPermission(item.permission))

  return (
    <SectionHub
      eyebrow="Programmes"
      title="Programmes & delivery"
      lead="Design programmes and their activities, then deliver and track benefits."
      items={items}
      actions={
        hasPermission('programme.create') ? (
          <Button leftIcon={Plus} onClick={() => navigate('/programmes/list?new=1')}>
            New programme
          </Button>
        ) : undefined
      }
    />
  )
}
