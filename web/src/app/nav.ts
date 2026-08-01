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
import type { NavSection } from '@/components/SideNav/SideNav'

export interface NavConfigItem {
  label: string
  to: string
  icon: LucideIcon
  /** Permission required to see this item; omit for always-visible. */
  permission?: string
  /** Match the route exactly (NavLink `end`) — for index links like `/partner`. */
  end?: boolean
}

export interface NavConfigSection {
  /** Mono eyebrow for the group; empty string renders no eyebrow. */
  label: string
  /** Role keys allowed to see this whole section; omit for everyone. */
  roles?: string[]
  /** Role keys that should NOT see this section (takes precedence over `roles`). */
  excludeRoles?: string[]
  items: NavConfigItem[]
}

/**
 * Navigation model. Visibility is UX-only (the server is the security boundary):
 * sections are gated by role, items by the signed-in user's permissions.
 *
 * Each functional area is a single top-level link that opens a "section screen"
 * (a launcher of permission-aware cards) rather than exploding its sub-tasks into
 * the rail — this keeps the navbar clean. The sub-tasks live on the hub page.
 *
 * A Development Partner is the exception: its rail IS the five pages of the funding-
 * partner suite (Overview → Investment Map), so the generic operator section is
 * hidden for that role and replaced by the partner section below.
 */
export const NAV_CONFIG: NavConfigSection[] = [
  {
    label: '',
    // Partners get their own rail (below) — hide the generic operator links from them.
    excludeRoles: ['development_partner'],
    items: [
      { label: 'Dashboard', to: '/', icon: GaugeCircle, end: true },
      { label: 'Programmes', to: '/programmes', icon: ClipboardList, permission: 'programme.view' },
      { label: 'Registry', to: '/registry', icon: UserSquare2, permission: 'beneficiary.view' },
      { label: 'Coordination', to: '/coordination', icon: Split, permission: 'referral.view' },
      { label: 'Coverage map', to: '/gis', icon: MapPinned, permission: 'dashboard.view' },
    ],
  },
  {
    // Development-Partner funding suite — one routed page per section (Phase 6P).
    label: '',
    roles: ['development_partner'],
    items: [
      { label: 'Overview', to: '/partner', icon: GaugeCircle, permission: 'dashboard.view', end: true },
      { label: 'Programmes & Results', to: '/partner/programmes', icon: ClipboardList, permission: 'dashboard.view' },
      { label: 'Registry', to: '/partner/registry', icon: UserSquare2, permission: 'dashboard.view' },
      { label: 'Coordination', to: '/partner/coordination', icon: Split, permission: 'dashboard.view' },
      { label: 'Investment Map', to: '/partner/investment', icon: MapPinned, permission: 'dashboard.view' },
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

/**
 * Resolve the nav rail for a caller: sections gated by role (+ `excludeRoles`),
 * items by permission, empty sections dropped. Pure — the AppLayout renders it and
 * it is unit-tested directly. Visibility is UX-only; the server enforces access.
 */
export function navSectionsFor(roleKey: string, hasPermission: (perm: string) => boolean): NavSection[] {
  return NAV_CONFIG.filter(
    (section) =>
      (!section.roles || section.roles.includes(roleKey)) &&
      (!section.excludeRoles || !section.excludeRoles.includes(roleKey)),
  )
    .map((section) => ({
      label: section.label,
      items: section.items
        .filter((item) => !item.permission || hasPermission(item.permission))
        .map(({ label, to, icon, end }) => ({ label, to, icon, end })),
    }))
    .filter((section) => section.items.length > 0)
}
