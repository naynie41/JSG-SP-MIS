import {
  Building2,
  ClipboardList,
  DatabaseZap,
  FileBarChart,
  GaugeCircle,
  HandHeart,
  LibraryBig,
  MapPinned,
  Plug,
  ShieldCheck,
  SlidersHorizontal,
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
 * The console roles are the exception: an Executive's rail IS the five pages of the
 * executive briefing, a Development Partner's IS the five pages of the funding-partner
 * suite, and a System Administrator's IS the nine pages of the administration console —
 * so the generic operator section is hidden for those roles and replaced by the
 * role-specific sections below.
 */
export const NAV_CONFIG: NavConfigSection[] = [
  {
    // MDA delivery workspace — six task-based modules (Phase MDA). ONE nav for both
    // MDA Officer and MDA Admin: the Officer's permission set is a strict subset of
    // the Admin's, so items gate on permission rather than the rail branching by role.
    // Settings is NOT a nav link; it opens from the gear affordance in the top bar.
    label: '',
    roles: ['mda_officer', 'mda_admin'],
    items: [
      { label: 'Overview', to: '/mda', icon: GaugeCircle, permission: 'dashboard.view', end: true },
      { label: 'Programmes', to: '/mda/programmes', icon: ClipboardList, permission: 'programme.view' },
      { label: 'Beneficiaries', to: '/mda/beneficiaries', icon: UserSquare2, permission: 'beneficiary.view' },
      { label: 'Service Delivery', to: '/mda/service-delivery', icon: HandHeart, permission: 'benefit.view' },
      { label: 'Duplicate Resolution', to: '/mda/duplicate-resolution', icon: Split, permission: 'beneficiary-lookup.view' },
      { label: 'Reports', to: '/mda/reports', icon: FileBarChart, permission: 'reporting.view' },
    ],
  },
  {
    label: '',
    // Every other operator role (SP Coordination, M&E) keeps the generic hub rail.
    // Console roles have their own rails below.
    excludeRoles: ['development_partner', 'executive', 'system_administrator', 'mda_officer', 'mda_admin'],
    items: [
      { label: 'Dashboard', to: '/', icon: GaugeCircle, end: true },
      { label: 'Programmes', to: '/programmes', icon: ClipboardList, permission: 'programme.view' },
      { label: 'Registry', to: '/registry', icon: UserSquare2, permission: 'beneficiary.view' },
      { label: 'Coordination', to: '/coordination', icon: Split, permission: 'referral.view' },
      { label: 'Coverage map', to: '/gis', icon: MapPinned, permission: 'dashboard.view' },
    ],
  },
  {
    // Executive briefing suite — one routed page per section (Phase 6E).
    label: '',
    roles: ['executive'],
    items: [
      { label: 'Overview', to: '/executive', icon: GaugeCircle, permission: 'dashboard.view', end: true },
      { label: 'Programmes', to: '/executive/programmes', icon: ClipboardList, permission: 'dashboard.view' },
      { label: 'Registry', to: '/executive/registry', icon: UserSquare2, permission: 'dashboard.view' },
      { label: 'Coordination', to: '/executive/coordination', icon: Split, permission: 'dashboard.view' },
      { label: 'Coverage Map', to: '/executive/coverage', icon: MapPinned, permission: 'dashboard.view' },
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
    // System Administrator console — governance/administration, one routed page per
    // section (peer links, no sub-grouping). Item permissions are omitted on purpose:
    // the section is ROLE-scoped and a System Administrator implicitly holds every
    // permission — the server gates the console (role:system_administrator).
    // Settings is NOT a nav link; it opens from the gear/account affordance.
    label: '',
    roles: ['system_administrator'],
    items: [
      { label: 'Overview', to: '/admin', icon: GaugeCircle, end: true },
      { label: 'User & Access', to: '/admin/access', icon: Users },
      { label: 'Organization', to: '/admin/organization', icon: Building2 },
      { label: 'Programme Catalog', to: '/admin/catalog', icon: LibraryBig },
      { label: 'Registry & Data Quality', to: '/admin/registry', icon: DatabaseZap },
      { label: 'Integrations', to: '/admin/integrations', icon: Plug },
      { label: 'Matching Rules & Registry Config', to: '/admin/matching', icon: SlidersHorizontal },
      { label: 'Audit & Security', to: '/admin/audit', icon: ShieldCheck },
      { label: 'Reports', to: '/admin/reports', icon: FileBarChart },
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
