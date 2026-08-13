import { beforeAll, beforeEach, describe, expect, it, vi } from 'vitest'
import { screen, waitFor } from '@testing-library/react'
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

describe('protected routing', () => {
  /**
   * Warm the lazily-imported landing routes before asserting on any of them.
   *
   * `App` code-splits every route, so a landing redirect suspends on a module load the
   * first time each route is reached. Measured cold at the tail of a full single-threaded
   * run, that import dominated these tests' wall time; importing the modules here puts
   * them in the registry so `React.lazy` resolves from cache and the assertions are
   * bounded by React rather than by disk and transform time.
   *
   * This is a speed measure, not the correctness fix — see `expectRail`, which is what
   * actually makes the rail assertions sound.
   */
  beforeAll(async () => {
    await Promise.all([
      import('@/features/admin/AdminLayout'),
      import('@/features/admin/AdminOverviewPage'),
      import('@/features/mda/MdaLayout'),
      import('@/features/mda/MdaOverviewPage'),
      import('@/features/dashboard/DashboardPage'),
      import('@/features/dashboard/MdaDashboardPage'),
    ])
  })

  beforeEach(() => {
    vi.clearAllMocks()
    localStorage.clear()
  })

  /**
   * Assert a rail's contents as ONE retried snapshot.
   *
   * Landing on `/` is a redirect, i.e. a router transition, so the tree passes through
   * intermediate states before it settles. Awaiting one anchor and then querying
   * synchronously samples whatever happens to be mounted at that instant — a run caught
   * the body already emptied and failed in 94ms, nowhere near a timeout.
   *
   * Worse, that pattern makes the negative assertions worthless: `queryByRole(...)`
   * `.not.toBeInTheDocument()` passes trivially against an empty body, so an
   * unsettled tree reads as a pass. Grouping them here means the `present` links must
   * be found in the SAME snapshot the `absent` ones are missing from — the positives
   * are what prove the rail actually rendered before the absences count for anything.
   */
  async function expectRail(present: string[], absent: string[]) {
    await waitFor(() => {
      for (const name of present) expect(screen.getByRole('link', { name })).toBeInTheDocument()
      for (const name of absent) expect(screen.queryByRole('link', { name })).not.toBeInTheDocument()
    })
  }

  it('redirects unauthenticated users from a protected route to login', async () => {
    renderWithProviders(<App />, '/users')

    // Login screen is shown instead of the protected content.
    expect(await screen.findByText('Welcome back')).toBeInTheDocument()
    expect(screen.getByLabelText('Email')).toBeInTheDocument()
  })

  it('restores a session from a stored token and shows the protected page', async () => {
    tokenStore.set('tok-abc')
    // SP Coordination lands on the generic account/dashboard view. (MDA roles now
    // redirect to their own workspace — covered separately below.)
    me.mockResolvedValue(makeUser({ role: { key: 'sp_coordination', name: 'SP Coordination' } }))

    renderWithProviders(<App />, '/')

    expect(await screen.findByText('Your access')).toBeInTheDocument()
    expect(me).toHaveBeenCalled()
  })

  it('sends an MDA role to its own six-module workspace, not the generic rail', async () => {
    tokenStore.set('tok-abc')
    me.mockResolvedValue(
      makeUser({
        role: { key: 'mda_officer', name: 'MDA Officer' },
        permissions: ['dashboard.view', 'programme.view', 'beneficiary.view'],
      }),
    )

    renderWithProviders(<App />, '/')

    // The rail is the MDA workspace, and the generic hub links are gone.
    await expectRail(['Beneficiaries', 'Overview'], ['Registry', 'Coordination'])
  })

  it('shows one clean link per functional area, gated by permission', async () => {
    tokenStore.set('tok-abc')
    // SP Coordination works across registry, programmes and coordination via the
    // generic hub rail.
    me.mockResolvedValue(
      makeUser({
        role: { key: 'sp_coordination', name: 'SP Coordination' },
        permissions: ['beneficiary.view', 'programme.view', 'referral.view'],
      }),
    )

    renderWithProviders(<App />, '/')

    // Each area collapses to a single top-level link (children live on the hub page),
    // the former child links are no longer in the rail, and Administration — not
    // relevant to MDA staff — is absent.
    await expectRail(
      ['Programmes', 'Registry', 'Coordination'],
      ['Beneficiaries', 'Record benefit', 'Users'],
    )
  })

  it('shows the administration console only to the System Administrator', async () => {
    tokenStore.set('tok-abc')
    me.mockResolvedValue(
      makeUser({
        role: { key: 'system_administrator', name: 'System Administrator' },
        permissions: ['user.view', 'mda.view', 'role.view'],
      }),
    )

    renderWithProviders(<App />, '/')

    // The System Administrator lands on the console (not the MDA operator view), the
    // rail IS the console's sections, and the generic operator rail is replaced rather
    // than merged.
    await expectRail(
      ['User & Access', 'Organization', 'Audit & Security'],
      ['Dashboard', 'Coverage map'],
    )
  })
})
