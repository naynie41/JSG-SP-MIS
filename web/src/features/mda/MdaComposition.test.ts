import { describe, expect, it } from 'vitest'

/**
 * Compose, never rebuild — checked structurally.
 *
 * Each of the six modules is meant to be an arrangement of screens that already exist in
 * their source feature. Behavioural tests prove reuse one module at a time by mocking the
 * SOURCE api and watching it get called; this is the complementary check, and the one
 * that catches the regression those cannot: a module that quietly grows its own endpoint,
 * its own axios call, or its own copy of a table.
 *
 * Sources are read through Vite's `?raw` glob rather than `node:fs`, so the test needs no
 * Node types and runs the same way the app is built.
 */

const MODULE_SOURCES = import.meta.glob('./*.tsx', { query: '?raw', import: 'default', eager: true }) as Record<string, string>
const OWN_API = import.meta.glob('./api.ts', { query: '?raw', import: 'default', eager: true }) as Record<string, string>
const APP_SOURCE = import.meta.glob('../../app/App.tsx', { query: '?raw', import: 'default', eager: true }) as Record<string, string>

/** Page sources only — the tests beside them are not part of the surface under check. */
const pages = Object.entries(MODULE_SOURCES).filter(([path]) => !path.includes('.test.'))

const sourceOf = (file: string): string => {
  const entry = pages.find(([path]) => path.endsWith(`/${file}`))
  if (!entry) throw new Error(`${file} not found in the MDA module directory`)
  return entry[1]
}

/** Module → the feature(s) whose screens it must be composing. */
const COMPOSITION: { module: string; composes: string[] }[] = [
  { module: 'MdaOverviewPage.tsx', composes: ['@/features/dashboard/hooks', '@/features/notifications/hooks'] },
  { module: 'MdaProgrammesPage.tsx', composes: ['@/features/programmes/hooks'] },
  { module: 'MdaProgrammeDetailPage.tsx', composes: ['@/features/programmes/'] },
  { module: 'MdaBeneficiariesPage.tsx', composes: ['@/features/registry/BeneficiaryListPage', '@/features/registry/ImportListPage'] },
  { module: 'MdaServiceDeliveryPage.tsx', composes: ['@/features/benefits/', '@/features/referrals/', '@/features/registry/ServiceRequestsPage'] },
  { module: 'MdaDuplicateResolutionPage.tsx', composes: ['@/features/registry/ResolveRowControls', '@/features/registry/DuplicateSearchPage'] },
  { module: 'MdaReportsPage.tsx', composes: ['@/features/reports/ReportPanels', '@/features/reports/hooks'] },
  { module: 'MdaSettingsPage.tsx', composes: ['@/lib/api/authApi', '@/features/notifications/hooks'] },
]

describe('MDA console — composition', () => {
  it.each(COMPOSITION)('$module composes its source feature', ({ module, composes }) => {
    const code = sourceOf(module)
    for (const dependency of composes) {
      expect(code, `${module} must import from ${dependency}`).toContain(dependency)
    }
  })

  it('no module talks to the HTTP layer directly', () => {
    // Every request must go through a feature's api module, which is where scoping,
    // error mapping and the auth header live. A raw client call here would be a second
    // data path around all of that.
    for (const [path, code] of pages) {
      expect(code, `${path} must not import the axios client`).not.toContain('@/lib/api/client')
      expect(code, `${path} must not call apiRequest`).not.toMatch(/\bapiRequest\b/)
      expect(code, `${path} must not use axios`).not.toMatch(/from ['"]axios['"]/)
      expect(code, `${path} must not fetch()`).not.toMatch(/\bfetch\(/)
    }
  })

  it('the console owns exactly one endpoint — the live action-required counters', () => {
    // No other feature provides them: the dashboard snapshot is 15 minutes stale and is
    // not directional. Anything else appearing here would mean a module started serving
    // itself instead of composing.
    const api = Object.values(OWN_API)[0] ?? ''

    expect(api).toContain('/mda/action-required')
    const endpoints = api.match(/url: *['"`][^'"`]+/g) ?? []
    expect(endpoints).toHaveLength(1)
  })

  it('every module is routed and none is still a scaffold', () => {
    const app = Object.values(APP_SOURCE)[0] ?? ''

    expect(app).not.toContain('mdaScaffolds')
    for (const { module } of COMPOSITION) {
      expect(app, `${module} must be routed`).toContain(`@/features/mda/${module.replace('.tsx', '')}`)
    }
  })
})
