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

  it('hides nav items the user lacks permission for', async () => {
    tokenStore.set('tok-abc')
    // A user who can manage users sees the Users admin page…
    me.mockResolvedValue(makeUser({ permissions: ['user.create'] }))

    renderWithProviders(<App />, '/')

    await screen.findByText('Your access')
    expect(screen.getByRole('link', { name: 'Users' })).toBeInTheDocument()
    // …while pages requiring other permissions are not.
    expect(screen.queryByRole('link', { name: 'Roles' })).not.toBeInTheDocument()
    expect(screen.queryByRole('link', { name: 'MDAs' })).not.toBeInTheDocument()
  })

  it('hides the Administration section from a view-only MDA officer', async () => {
    tokenStore.set('tok-abc')
    // An MDA Officer holds view permissions (for dropdowns) but no manage rights.
    me.mockResolvedValue(makeUser({ permissions: ['user.view', 'mda.view', 'beneficiary.view'] }))

    renderWithProviders(<App />, '/')

    await screen.findByText('Your access')
    // The admin pages are gated on manage permissions, so they're hidden…
    expect(screen.queryByRole('link', { name: 'Users' })).not.toBeInTheDocument()
    expect(screen.queryByRole('link', { name: 'MDAs' })).not.toBeInTheDocument()
    // …but their registry work is still available.
    expect(screen.getByRole('link', { name: 'Beneficiaries' })).toBeInTheDocument()
  })
})
