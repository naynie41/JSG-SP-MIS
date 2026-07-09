import {
  Building2,
  ClipboardList,
  GaugeCircle,
  KeyRound,
  LibraryBig,
  MapPinned,
  Share2,
  ShieldCheck,
  Split,
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
  /** Mono eyebrow for the group; empty string renders no eyebrow. */
  label: string
  /** Role keys allowed to see this whole section; omit for everyone. */
  roles?: string[]
  items: NavConfigItem[]
}

/**
 * Navigation model. Visibility is UX-only (the server is the security boundary):
 * sections are gated by role, items by the signed-in user's permissions.
 *
 * Each functional area is a single top-level link that opens a "section screen"
 * (a launcher of permission-aware cards) rather than exploding its sub-tasks into
 * the rail — this keeps the navbar clean. The sub-tasks live on the hub page.
 */
export const NAV_CONFIG: NavConfigSection[] = [
  {
    label: '',
    items: [
      { label: 'Dashboard', to: '/', icon: GaugeCircle },
      { label: 'Programmes', to: '/programmes', icon: ClipboardList, permission: 'programme.view' },
      { label: 'Registry', to: '/registry', icon: UserSquare2, permission: 'beneficiary.view' },
      { label: 'Coordination', to: '/coordination', icon: Split, permission: 'referral.view' },
      { label: 'Coverage map', to: '/gis', icon: MapPinned, permission: 'dashboard.view' },
    ],
  },
  {
    // System administration is not relevant to MDA staff — it's scoped to the
    // System Administrator role, who provisions users, MDAs and access.
    label: 'Administration',
    roles: ['system_administrator'],
    items: [
      { label: 'Programme catalog', to: '/programmes/list', icon: LibraryBig, permission: 'programme.create' },
      { label: 'Users', to: '/users', icon: Users, permission: 'user.view' },
      { label: 'MDAs', to: '/mdas', icon: Building2, permission: 'mda.view' },
      { label: 'Roles', to: '/roles', icon: ShieldCheck, permission: 'role.view' },
      { label: 'Permissions', to: '/permissions', icon: KeyRound, permission: 'permission.view' },
      { label: 'Cross-MDA access', to: '/grants', icon: Share2, permission: 'mda-access.view' },
    ],
  },
]
