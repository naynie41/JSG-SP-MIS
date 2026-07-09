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
    me.mockResolvedValue(makeUser())

    renderWithProviders(<App />, '/')

    expect(await screen.findByText('Your access')).toBeInTheDocument()
    expect(me).toHaveBeenCalled()
  })

  it('shows one clean link per functional area, gated by permission', async () => {
    tokenStore.set('tok-abc')
    // An MDA Officer works across registry, programmes and coordination.
    me.mockResolvedValue(
      makeUser({ permissions: ['beneficiary.view', 'programme.view', 'referral.view'] }),
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

  it('shows Administration only to the System Administrator', async () => {
    tokenStore.set('tok-abc')
    me.mockResolvedValue(
      makeUser({
        role: { key: 'system_administrator', name: 'System Administrator' },
        permissions: ['user.view', 'mda.view', 'role.view'],
      }),
    )

    renderWithProviders(<App />, '/')

    await screen.findByText('Your access')
    expect(screen.getByRole('link', { name: 'Users' })).toBeInTheDocument()
    expect(screen.getByRole('link', { name: 'MDAs' })).toBeInTheDocument()
  })
})
