import { describe, expect, it } from 'vitest'
import { navSectionsFor } from './nav'
import { MDA_ROLES } from '@/features/mda/roles'

const all = () => true

describe('navSectionsFor', () => {
  it('gives a Development Partner the five funding-suite pages, not the generic rail', () => {
    const items = navSectionsFor('development_partner', all).flatMap((s) => s.items)
    expect(items.map((i) => i.label)).toEqual(['Overview', 'Programmes & Results', 'Registry', 'Coordination', 'Investment Map'])
    expect(items.map((i) => i.to)).toEqual(['/partner', '/partner/programmes', '/partner/registry', '/partner/coordination', '/partner/investment'])

    // The generic operator links + Administration are hidden for a partner.
    expect(items.map((i) => i.label)).not.toContain('Coverage map')
    expect(items.map((i) => i.label)).not.toContain('Users')

    // Overview is an exact-match (index) link so it doesn't stay active on child routes.
    expect(items.find((i) => i.label === 'Overview')?.end).toBe(true)
  })

  it('gives an Executive the five briefing pages, not the generic rail', () => {
    const items = navSectionsFor('executive', all).flatMap((s) => s.items)
    expect(items.map((i) => i.label)).toEqual(['Overview', 'Programmes', 'Registry', 'Coordination', 'Coverage Map'])
    expect(items.map((i) => i.to)).toEqual(['/executive', '/executive/programmes', '/executive/registry', '/executive/coordination', '/executive/coverage'])

    // The generic operator links are hidden, and the partner suite is never offered.
    expect(items.map((i) => i.to)).not.toContain('/gis')
    expect(items.map((i) => i.to)).not.toContain('/partner')

    // Overview is an exact-match (index) link so it doesn't stay active on child routes.
    expect(items.find((i) => i.label === 'Overview')?.end).toBe(true)
  })

  it('gives an operator role the generic rail, never a suite rail', () => {
    // SP Coordination / M&E keep the generic hub rail. The MDA roles moved to their
    // own six-module workspace (below), so they are no longer the example here.
    const tos = navSectionsFor('sp_coordination', all).flatMap((s) => s.items.map((i) => i.to))
    expect(tos).toContain('/') // Dashboard
    expect(tos).toContain('/programmes')
    expect(tos).not.toContain('/mda')
    expect(tos).not.toContain('/partner')
    expect(tos).not.toContain('/partner/investment')
    expect(tos).not.toContain('/executive')
    expect(tos).not.toContain('/executive/coverage')
  })

  it('gives every MDA role the six-module workspace, not the generic rail', () => {
    // MDA_ROLES is the single source of truth and now holds exactly one entry
    // (FR-UAM-01). Iterating it rather than a literal keeps this honest if it ever grows.
    expect(MDA_ROLES).toEqual(['mda_admin'])

    for (const role of MDA_ROLES) {
      const tos = navSectionsFor(role, all).flatMap((s) => s.items.map((i) => i.to))
      expect(tos).toEqual([
        '/mda',
        '/mda/programmes',
        '/mda/beneficiaries',
        '/mda/service-delivery',
        '/mda/duplicate-resolution',
        '/mda/reports',
      ])
      // The generic hubs are replaced, not duplicated alongside.
      expect(tos).not.toContain('/')
      expect(tos).not.toContain('/registry')
      // Settings opens from the gear, never the rail.
      expect(tos).not.toContain('/mda/settings')
    }
  })

  it('gives a System Administrator the nine console pages, not the generic rail', () => {
    const items = navSectionsFor('system_administrator', all).flatMap((s) => s.items)

    expect(items.map((i) => i.label)).toEqual([
      'Overview',
      'User & Access',
      'Organization',
      'Programme Catalog',
      'Registry & Data Quality',
      'Integrations',
      'Matching Rules & Registry Config',
      'Audit & Security',
      'Reports',
    ])
    expect(items).toHaveLength(9)
    expect(items.every((i) => i.to.startsWith('/admin'))).toBe(true)

    // Settings is NOT a nav link — it opens from the gear/account affordance.
    expect(items.map((i) => i.label)).not.toContain('Settings')
    expect(items.map((i) => i.to)).not.toContain('/admin/settings')

    // Overview is an exact-match (index) link, and the generic operator rail is hidden.
    expect(items.find((i) => i.label === 'Overview')?.end).toBe(true)
    expect(items.map((i) => i.to)).not.toContain('/')
  })

  it('never offers the console rails to an operator role', () => {
    const tos = navSectionsFor('mda_admin', all).flatMap((s) => s.items.map((i) => i.to))
    expect(tos.some((to) => to.startsWith('/admin'))).toBe(false)
  })

  it('drops items the caller lacks permission for, and empty sections', () => {
    // A partner with no permissions sees no rail (every partner item needs dashboard.view).
    expect(navSectionsFor('development_partner', () => false)).toEqual([])
  })

  /* ------------------------------------------------------------ grievances */

  it('puts the grievance desk on the operator rail', () => {
    // It was a rail item, was folded into the Coordination hub, and is promoted back:
    // a grievance is inbound work with an SLA already running, so it is the one
    // sub-task that earns a place people cannot miss.
    const tos = navSectionsFor('sp_coordination', all).flatMap((s) => s.items.map((i) => i.to))

    expect(tos).toContain('/grievances')
    // ...without displacing the hub, which still carries the coordination metrics.
    expect(tos).toContain('/coordination')
  })

  it('hides the grievance desk from a caller without grievance.view', () => {
    // Visibility is UX-only, but the rail must still not advertise what the server
    // would refuse.
    const tos = navSectionsFor('sp_coordination', (p) => p !== 'grievance.view').flatMap((s) =>
      s.items.map((i) => i.to),
    )

    expect(tos).not.toContain('/grievances')
    expect(tos).toContain('/coordination')
  })

  it('keeps the grievance desk off the console rails', () => {
    // An Executive's rail IS the briefing suite and a Partner's IS the funding suite —
    // neither handles grievances, and a Partner never sees operational queues at all.
    for (const role of ['executive', 'development_partner', 'system_administrator']) {
      const tos = navSectionsFor(role, all).flatMap((s) => s.items.map((i) => i.to))
      expect(tos).not.toContain('/grievances')
    }
  })

  it('leaves the MDA workspace at six modules', () => {
    // MDAs reach grievances through Service Delivery, so the six-module rail is unchanged.
    const mdaSections = navSectionsFor('mda_admin', all)
    const tos = mdaSections.flatMap((s) => s.items.map((i) => i.to))

    expect(tos).not.toContain('/grievances')
    expect(tos).toHaveLength(6)
  })
})
