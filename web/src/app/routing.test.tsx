import { beforeEach, describe, expect, it, vi } from 'vitest'
import { screen } from '@testing-library/react'
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
  beforeEach(() => {
    vi.clearAllMocks()
    localStorage.clear()
  })

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

    // The rail is the MDA workspace…
    expect(await screen.findByRole('link', { name: 'Beneficiaries' }, { timeout: 5000 })).toBeInTheDocument()
    expect(screen.getByRole('link', { name: 'Overview' })).toBeInTheDocument()
    // …and the generic hub links are gone.
    expect(screen.queryByRole('link', { name: 'Registry' })).not.toBeInTheDocument()
    expect(screen.queryByRole('link', { name: 'Coordination' })).not.toBeInTheDocument()
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

    await screen.findByText('Your access')
    // Each area collapses to a single top-level link (children live on the hub page).
    expect(screen.getByRole('link', { name: 'Programmes' })).toBeInTheDocument()
    expect(screen.getByRole('link', { name: 'Registry' })).toBeInTheDocument()
    expect(screen.getByRole('link', { name: 'Coordination' })).toBeInTheDocument()
    // The former child links are no longer in the rail.
    expect(screen.queryByRole('link', { name: 'Beneficiaries' })).not.toBeInTheDocument()
    expect(screen.queryByRole('link', { name: 'Record benefit' })).not.toBeInTheDocument()
    // Administration is not relevant to MDA staff — it's absent.
    expect(screen.queryByRole('link', { name: 'Users' })).not.toBeInTheDocument()
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

    // The System Administrator lands on the console (not the MDA operator view), and
    // the rail IS the console's nine sections.
    expect(await screen.findByRole('link', { name: 'User & Access' }, { timeout: 5000 })).toBeInTheDocument()
    expect(screen.getByRole('link', { name: 'Organization' })).toBeInTheDocument()
    expect(screen.getByRole('link', { name: 'Audit & Security' })).toBeInTheDocument()

    // The generic operator rail is replaced, not merged.
    expect(screen.queryByRole('link', { name: 'Dashboard' })).not.toBeInTheDocument()
    expect(screen.queryByRole('link', { name: 'Coverage map' })).not.toBeInTheDocument()
  })
})
