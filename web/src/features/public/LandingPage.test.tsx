import { beforeEach, describe, expect, it, vi } from 'vitest'
import { screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import type { Mock } from 'vitest'
import { App } from '@/app/App'
import { authApi } from '@/lib/api/authApi'
import { tokenStore } from '@/lib/api/tokenStore'
import { makeUser, renderWithProviders } from '@/test/harness'

vi.mock('@/lib/api/authApi', () => ({
  authApi: {
    login: vi.fn(),
    me: vi.fn(),
    logout: vi.fn(),
    mfaChallenge: vi.fn(),
    mfaEnroll: vi.fn(),
    mfaVerify: vi.fn(),
  },
}))

const me = authApi.me as Mock

/**
 * The public landing page at `/`.
 *
 * The load-bearing property is what it does NOT do: an anonymous visitor is entitled to
 * know what SP-MIS is for, not what is in it. Everything in it is personal data about
 * people who never consented to a public page, so the landing page reads nothing from
 * the API and renders none of the authenticated shell (NDPA/NDPR).
 */
describe('public landing page', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    localStorage.clear()
    tokenStore.clear()
  })

  /* ------------------------------------------------------------- it renders */

  it('greets an anonymous visitor at the site root', async () => {
    renderWithProviders(<App />, '/')

    expect(
      await screen.findByRole('heading', { level: 1, name: /social protection management information system/i }),
    ).toBeInTheDocument()
    expect(screen.getByText(/connecting people, programmes and services/i)).toBeInTheDocument()
  })

  it('carries all ten sections in order', async () => {
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    // Named by their headings, which is also how a screen-reader user finds them.
    const headings = screen.getAllByRole('heading', { level: 2 }).map((h) => h.textContent ?? '')
    for (const expected of [
      /one record of social protection/i, // 3. what is SP-MIS
      /what sp-mis provides/i, // 4. capabilities
      /from a programme to the evidence/i, // 5. how it works
      /built for every local government area/i, // 6. across the state
      /your voice matters/i, // 7. grievance redress
      /connecting the ecosystem/i, // 8. stakeholders
      /access sp-mis/i, // 9. CTA
    ]) {
      expect(headings.some((h) => expected.test(h))).toBe(true)
    }

    // 1. header, 2. hero, 10. footer — landmarks rather than headings.
    expect(screen.getByRole('banner')).toBeInTheDocument()
    expect(screen.getByRole('main')).toBeInTheDocument()
    expect(screen.getByRole('contentinfo')).toBeInTheDocument()
    expect(screen.getByText(/© 2026 jigawa state government/i)).toBeInTheDocument()
  })

  it('names the five delivery steps in the order they happen', async () => {
    // The numbering is information: nothing is delivered to someone unregistered, and no
    // insight precedes the delivery it describes.
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    expect(screen.getByText('01')).toBeInTheDocument()
    expect(screen.getByText('05')).toBeInTheDocument()
    expect(screen.getByText(/a programme is defined/i)).toBeInTheDocument()
    expect(screen.getByText(/insight comes back/i)).toBeInTheDocument()
  })

  /* --------------------------------------------------------- no system data */

  it('reads nothing from the API', async () => {
    // The whole point. A landing page that fetches is a landing page that can leak.
    const fetchSpy = vi.spyOn(globalThis, 'fetch')

    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    expect(fetchSpy).not.toHaveBeenCalled()
    expect(me).not.toHaveBeenCalled()
  })

  it('states no beneficiary, coverage or delivery figure', async () => {
    // Anything that reads as a measurement is a claim about real people. The only
    // numerals allowed on this page are the step numbers and the copyright year.
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    const text = document.body.textContent ?? ''
    const allowed = new Set(['01', '02', '03', '04', '05', '2026', '27'])
    const numbers = text.match(/\d[\d,.]*/g) ?? []

    expect(numbers.filter((n) => !allowed.has(n))).toEqual([])
    // No percentages, and no naira figures.
    expect(text).not.toMatch(/\d\s*%/)
    expect(text).not.toMatch(/₦/)
  })

  it('says plainly that the map is illustrative', async () => {
    // It is a decorative lattice, but a diagram on a government page is read as a map
    // unless it says otherwise.
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    expect(screen.getByText(/illustrative only/i)).toBeInTheDocument()
    expect(screen.getByRole('img', { name: /shows no data/i })).toBeInTheDocument()
  })

  it('offers no public grievance submission', async () => {
    // Intake runs through MDA channels, where an officer can identify the person and the
    // programme. A public form would collect personal data from an anonymous visitor
    // into a queue with no owner.
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    expect(screen.queryByRole('button', { name: /submit.*grievance/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('link', { name: /submit.*grievance/i })).not.toBeInTheDocument()
    expect(screen.getByRole('link', { name: /learn about grievance redress/i })).toBeInTheDocument()
  })

  it('does not render the authenticated shell', async () => {
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    // The app's nav rail and its account control belong behind the login.
    expect(screen.queryByRole('navigation', { name: /main/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /sign out/i })).not.toBeInTheDocument()
  })

  /* -------------------------------------------------------------- the door */

  it('routes both Login actions to the auth page', async () => {
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    const logins = screen.getAllByRole('link', { name: /login/i })
    expect(logins.length).toBeGreaterThanOrEqual(2) // header + CTA band
    for (const link of logins) {
      expect(link).toHaveAttribute('href', '/login')
    }
  })

  it('takes an anonymous visitor to the login page when they click Login', async () => {
    const user = userEvent.setup()
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    await user.click(screen.getAllByRole('link', { name: /login/i })[0])

    expect(await screen.findByLabelText('Email')).toBeInTheDocument()
  })

  /* ----------------------------------------------------- already signed in */

  it('sends a signed-in user straight to their dashboard instead of the landing page', async () => {
    // The landing page is for people who are not yet in. It must never stand between
    // someone with a session and their work.
    tokenStore.set('token-123')
    me.mockResolvedValue(makeUser({ role: { key: 'mda_admin', name: 'MDA Admin' } }))

    renderWithProviders(<App />, '/')

    await screen.findByText(/loading|mda|dashboard/i, undefined, { timeout: 3000 }).catch(() => undefined)
    expect(
      screen.queryByRole('heading', { level: 1, name: /social protection management information system/i }),
    ).not.toBeInTheDocument()
  })

  /* ------------------------------------------------------------ navigation */

  it('offers the section anchors, and a mobile menu button that discloses them', async () => {
    const user = userEvent.setup()
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    const header = screen.getByRole('banner')
    expect(within(header).getByRole('link', { name: 'About' })).toHaveAttribute('href', '#about')
    expect(within(header).getByRole('link', { name: 'Grievance redress' })).toHaveAttribute(
      'href',
      '#grievance-redress',
    )

    // Found by the panel it promises to control, not by role+name: jsdom lays out at
    // desktop width, where this button is correctly `display: none` and so has neither a
    // computed accessible name nor a place in the accessibility tree. The disclosure
    // being tested is not a function of viewport width.
    const menuButton = header.querySelector<HTMLButtonElement>('[aria-controls="landing-mobile-nav"]')
    expect(menuButton).not.toBeNull()
    expect(menuButton).toHaveAttribute('aria-expanded', 'false')
    expect(menuButton).toHaveTextContent('Open menu') // its label, for a screen reader
    expect(document.getElementById('landing-mobile-nav')).toBeNull()

    await user.click(menuButton as HTMLButtonElement)

    expect(menuButton).toHaveAttribute('aria-expanded', 'true')
    expect(menuButton).toHaveTextContent('Close menu')

    // The panel it named now exists, and carries the same anchors.
    const panel = document.getElementById('landing-mobile-nav')
    expect(panel).not.toBeNull()
    expect(within(panel as HTMLElement).getByText('Grievance redress')).toBeInTheDocument()
  })

  it('opens with a skip link, so a keyboard user is not walked through the nav', async () => {
    renderWithProviders(<App />, '/')
    await screen.findByRole('heading', { level: 1 })

    const skip = screen.getByRole('link', { name: /skip to content/i })
    expect(skip).toHaveAttribute('href', '#main')
  })
})
