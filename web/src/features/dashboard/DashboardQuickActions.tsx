import { useNavigate } from 'react-router-dom'
import { CalendarPlus, Coins, FileUp, LifeBuoy, Plus, Split } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Button } from '@/components/Button/Button'
import { useAuth } from '@/lib/auth/AuthProvider'
import styles from './dashboard.module.css'

interface QuickAction {
  label: string
  to: string
  icon: LucideIcon
  permission: string
  /** Needs an acting MDA (mirrors the server's MDA_REQUIRED / delivery rules). */
  requiresMda?: boolean
  primary?: boolean
}

// Ordered by prominence — creating a programme/activity leads, per the MDA workflow.
// Creating a programme is a central catalog action (no MDA); the rest operate
// within an MDA and are hidden from MDA-less accounts (e.g. oversight roles).
const ACTIONS: QuickAction[] = [
  { label: 'New programme', to: '/programmes/list?new=1', icon: Plus, permission: 'programme.create', primary: true },
  { label: 'New activity', to: '/activities', icon: CalendarPlus, permission: 'activity.create', requiresMda: true },
  { label: 'Record benefit', to: '/benefits/record', icon: Coins, permission: 'benefit.create', requiresMda: true },
  { label: 'Import beneficiaries', to: '/imports', icon: FileUp, permission: 'beneficiary.create', requiresMda: true },
  { label: 'Raise referral', to: '/referrals', icon: Split, permission: 'referral.create', requiresMda: true },
  { label: 'Log grievance', to: '/grievances', icon: LifeBuoy, permission: 'grievance.create', requiresMda: true },
]

/**
 * Permission-aware action bar for the MDA dashboard: surfaces the tasks this user can
 * actually perform (create a programme/activity, record a benefit, etc.) so the
 * dashboard is a launchpad, not just read-only KPIs. Renders nothing for view-only
 * scopes (executive/partner), keeping those dashboards read-only.
 */
export function DashboardQuickActions() {
  const { user, hasPermission } = useAuth()
  const navigate = useNavigate()
  const hasMda = Boolean(user?.mda)
  const actions = ACTIONS.filter((a) => hasPermission(a.permission) && (!a.requiresMda || hasMda))

  if (actions.length === 0) return null

  return (
    <Card eyebrow="Quick actions" title="What would you like to do?">
      <div className={styles.actions}>
        {actions.map((a) => (
          <Button
            key={a.label}
            variant={a.primary ? 'primary' : 'secondary'}
            leftIcon={a.icon}
            onClick={() => navigate(a.to)}
          >
            {a.label}
          </Button>
        ))}
      </div>
    </Card>
  )
}
