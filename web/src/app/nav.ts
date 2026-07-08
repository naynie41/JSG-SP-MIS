import {
  BarChart3,
  Building2,
  ClipboardList,
  Coins,
  FileUp,
  GaugeCircle,
  Home,
  KeyRound,
  LayoutGrid,
  LifeBuoy,
  ScanSearch,
  Send,
  Share2,
  ShieldCheck,
  SlidersHorizontal,
  Split,
  Upload,
  UserSquare2,
  Users,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'

export interface NavConfigItem {
  label: string
  to: string
  icon: LucideIcon
  /** Permission required to see this item; omit for always-visible. */
  permission?: string
}

export interface NavConfigSection {
  label: string
  items: NavConfigItem[]
}

/**
 * Navigation model. Visibility is UX-only (the server is the security boundary):
 * items are filtered by the signed-in user's permissions.
 */
export const NAV_CONFIG: NavConfigSection[] = [
  {
    label: '01 · Overview',
    items: [{ label: 'Dashboard', to: '/', icon: GaugeCircle }],
  },
  {
    label: '02 · Administration',
    items: [
      { label: 'Users', to: '/users', icon: Users, permission: 'user.view' },
      { label: 'MDAs', to: '/mdas', icon: Building2, permission: 'mda.view' },
      { label: 'Roles', to: '/roles', icon: ShieldCheck, permission: 'role.view' },
      { label: 'Permissions', to: '/permissions', icon: KeyRound, permission: 'permission.view' },
      { label: 'Cross-MDA access', to: '/grants', icon: Share2, permission: 'mda-access.view' },
    ],
  },
  {
    label: '03 · Registry',
    items: [
      { label: 'Beneficiaries', to: '/beneficiaries', icon: UserSquare2, permission: 'beneficiary.view' },
      { label: 'Households', to: '/households', icon: Home, permission: 'household.view' },
      { label: 'Bulk import', to: '/imports', icon: FileUp, permission: 'beneficiary.create' },
      { label: 'Duplicate search', to: '/duplicate-search', icon: ScanSearch, permission: 'beneficiary-lookup.view' },
      { label: 'Service requests', to: '/service-requests', icon: Send, permission: 'beneficiary.view' },
      { label: 'Matching rules', to: '/matching', icon: SlidersHorizontal, permission: 'matching.view' },
    ],
  },
  {
    label: '04 · Programmes',
    items: [
      { label: 'Programmes', to: '/programmes', icon: ClipboardList, permission: 'programme.view' },
      { label: 'Record benefit', to: '/benefits/record', icon: Coins, permission: 'benefit.create' },
      { label: 'Bulk delivery', to: '/benefits/bulk', icon: Upload, permission: 'benefit.create' },
      { label: 'Benefit ledger', to: '/benefits/ledger', icon: BarChart3, permission: 'benefit.view' },
    ],
  },
  {
    label: '05 · Coordination',
    items: [
      { label: 'Referrals', to: '/referrals', icon: Split, permission: 'referral.view' },
      { label: 'Grievance desk', to: '/grievances', icon: LifeBuoy, permission: 'grievance.view' },
    ],
  },
  {
    label: '06 · System',
    items: [{ label: 'Style guide', to: '/styleguide', icon: LayoutGrid }],
  },
]
