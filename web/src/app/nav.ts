import {
  Building2,
  FileUp,
  GaugeCircle,
  Home,
  KeyRound,
  LayoutGrid,
  ScanSearch,
  Send,
  Share2,
  ShieldCheck,
  SlidersHorizontal,
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
      { label: 'Serve requests', to: '/serve-requests', icon: Send, permission: 'beneficiary.view' },
      { label: 'Matching rules', to: '/matching', icon: SlidersHorizontal, permission: 'matching.view' },
    ],
  },
  {
    label: '04 · System',
    items: [{ label: 'Style guide', to: '/styleguide', icon: LayoutGrid }],
  },
]
