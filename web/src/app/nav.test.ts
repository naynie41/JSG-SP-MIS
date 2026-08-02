import { describe, expect, it } from 'vitest'
import { navSectionsFor } from './nav'

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
    const tos = navSectionsFor('mda_officer', all).flatMap((s) => s.items.map((i) => i.to))
    expect(tos).toContain('/') // Dashboard
    expect(tos).toContain('/programmes')
    expect(tos).not.toContain('/partner')
    expect(tos).not.toContain('/partner/investment')
    expect(tos).not.toContain('/executive')
    expect(tos).not.toContain('/executive/coverage')
  })

  it('adds the Administration section only for the System Administrator', () => {
    expect(navSectionsFor('system_administrator', all).flatMap((s) => s.items.map((i) => i.label))).toContain('Users')
    expect(navSectionsFor('mda_officer', all).flatMap((s) => s.items.map((i) => i.label))).not.toContain('Users')
  })

  it('drops items the caller lacks permission for, and empty sections', () => {
    // A partner with no permissions sees no rail (every partner item needs dashboard.view).
    expect(navSectionsFor('development_partner', () => false)).toEqual([])
  })
})
