import { describe, expect, it } from 'vitest'

/**
 * Ingestion is source-only (CLAUDE.md §8) — checked structurally across the whole app.
 *
 * `BeneficiaryListPage.test` and `HouseholdListPage.test` already assert that neither
 * LIST page offers a create action. This is the complementary check, and the one those
 * cannot make: a registration form reappearing somewhere else — a new route, a modal on
 * the detail page, a quick action in the MDA console. The server is the real boundary
 * (`NoManualCreateRouteTest` pins it there), but a form that renders and then 405s is a
 * broken promise to the user, so the client must not offer one either.
 *
 * Sources are read through Vite's `?raw` glob, matching `MdaComposition.test.ts`.
 */

const SOURCES = import.meta.glob('../../**/*.{ts,tsx}', {
  query: '?raw',
  import: 'default',
  eager: true,
}) as Record<string, string>

/** Application sources only — tests describe the ban, so they may name the forbidden thing. */
const appSources = Object.entries(SOURCES).filter(([path]) => !path.includes('.test.'))

describe('no manual registration anywhere in the client', () => {
  it('reads a non-trivial slice of the app', () => {
    // Guards the guard: a glob that silently matched nothing would make every
    // assertion below vacuously true.
    expect(appSources.length).toBeGreaterThan(50)
  })

  /* ------------------------------------------------------------------ the API layer */

  it('exposes no client function that POSTs a new beneficiary or household', () => {
    for (const [path, source] of appSources) {
      // A create would have to POST to the bare collection; every legitimate POST in
      // the registry is to a sub-resource (/imports, /intake, /members, /documents…).
      for (const forbidden of [
        `url: '/beneficiaries'`,
        `url: "/beneficiaries"`,
        `url: '/households'`,
        `url: "/households"`,
      ]) {
        if (!source.includes(forbidden)) continue

        // The list endpoints use the same URL with GET, so only a POST is a failure.
        const postsToCollection = new RegExp(
          `method:\\s*'POST'[^}]*${forbidden.replace(/[/'"]/g, '\\$&')}|${forbidden.replace(/[/'"]/g, '\\$&')}[^}]*method:\\s*'POST'`,
        )
        expect(postsToCollection.test(source), `${path} POSTs to a registry collection`).toBe(false)
      }
    }
  })

  /* -------------------------------------------------------------------- the routes */

  it('routes no create/registration screen for either resource', () => {
    const app = appSources.find(([path]) => path.endsWith('/app/App.tsx'))?.[1]
    expect(app, 'App.tsx not found').toBeDefined()

    for (const forbidden of [
      'beneficiaries/new',
      'beneficiaries/create',
      'beneficiaries/register',
      'households/new',
      'households/create',
    ]) {
      expect(app).not.toContain(forbidden)
    }
  })

  /* ---------------------------------------------------------------- the affordances */

  it('renders no "add/register/create" affordance for a beneficiary or household', () => {
    /*
     * Matched as an INTERACTIVE ELEMENT'S LABEL, not as English anywhere in the file.
     *
     * Searching the raw text for "new beneficiary" finds the import preview's "this row
     * will be created as a new beneficiary", and searching for "add beneficiary" finds
     * the MDA console's notice explaining that no such form exists — both of which are
     * copy we want to keep. What is actually banned is a control a user can press, so
     * the check looks for the phrase in the two places a control gets its name: as the
     * children of a button/link, or as a label-ish prop.
     */
    const VERB = String.raw`(?:Add|New|Create|Register|Enrol|Enroll)`
    const NOUN = String.raw`(?:beneficiary|household|individual)`

    const asChildOfControl = new RegExp(
      String.raw`<(?:Button|Link|NavLink|MenuItem|a)\b[^>]*>\s*\{?\s*['"\`]?\s*${VERB}\s+(?:an?\s+|the\s+)?${NOUN}`,
      'i',
    )
    const asLabelProp = new RegExp(
      String.raw`(?:label|title|aria-label|actionLabel|emptyActionLabel)\s*=\s*['"\`]\s*${VERB}\s+(?:an?\s+|the\s+)?${NOUN}`,
      'i',
    )

    for (const [path, source] of appSources) {
      // Comments still stripped: a `// no "Add beneficiary" button here` note is
      // exactly what we want people writing.
      const code = source.replace(/\/\*[\s\S]*?\*\//g, '').replace(/(^|[^:])\/\/.*$/gm, '$1')

      expect(asChildOfControl.test(code), `${path} renders a manual-registration control`).toBe(false)
      expect(asLabelProp.test(code), `${path} labels a control for manual registration`).toBe(false)
    }
  })
})
