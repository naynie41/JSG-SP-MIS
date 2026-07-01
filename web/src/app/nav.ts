import { Building2, GaugeCircle, KeyRound, LayoutGrid, Share2, ShieldCheck, Users } from 'lucide-react'
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
    label: '03 · System',
    items: [{ label: 'Style guide', to: '/styleguide', icon: LayoutGrid }],
  },
]
