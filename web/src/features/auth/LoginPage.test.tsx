import { beforeEach, describe, expect, it, vi } from 'vitest'
import { screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import type { Mock } from 'vitest'
import { App } from '@/app/App'
import { authApi } from '@/lib/api/authApi'
import { tokenStore } from '@/lib/api/tokenStore'
import { ApiError } from '@/types/api'
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

const login = authApi.login as Mock
const mfaChallenge = authApi.mfaChallenge as Mock

describe('LoginPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    localStorage.clear()
  })

  async function fillCredentials() {
    const user = userEvent.setup()
    await user.type(screen.getByLabelText('Email'), 'amina@example.test')
    await user.type(screen.getByLabelText('Password'), 'Sup3rStr0ng!Pass')
    await user.click(screen.getByRole('button', { name: /sign in/i }))
    return user
  }

  it('logs in with valid credentials (no MFA) and lands on the dashboard', async () => {
    login.mockResolvedValue({ token: 'tok-123', token_type: 'Bearer', user: makeUser() })

    renderWithProviders(<App />, '/login')
    await fillCredentials()

    // Dashboard content appears (unique to the authenticated shell).
    expect(await screen.findByText('Your access')).toBeInTheDocument()
    expect(tokenStore.get()).toBe('tok-123')
  })

  it('shows a generic error on invalid credentials', async () => {
    login.mockRejectedValue(new ApiError(401, 'INVALID_CREDENTIALS', 'Invalid credentials.'))

    renderWithProviders(<App />, '/login')
    await fillCredentials()

    expect(await screen.findByRole('alert')).toHaveTextContent('Invalid credentials.')
    expect(tokenStore.get()).toBeNull()
  })

  it('completes the MFA challenge step to sign in', async () => {
    login.mockResolvedValue({ mfa_required: true, token_type: 'Bearer', mfa_token: 'chal-1' })
    mfaChallenge.mockResolvedValue({ token: 'tok-final', token_type: 'Bearer', user: makeUser() })

    renderWithProviders(<App />, '/login')
    const user = await fillCredentials()

    // Challenge step is shown.
    expect(await screen.findByText('Two-factor check')).toBeInTheDocument()

    await user.type(screen.getByLabelText('Authentication code'), '123456')
    await user.click(screen.getByRole('button', { name: /verify/i }))

    expect(await screen.findByText('Your access')).toBeInTheDocument()
    expect(mfaChallenge).toHaveBeenCalledWith('chal-1', '123456')
    expect(tokenStore.get()).toBe('tok-final')
  })

  it('offers recovery-code entry during the MFA challenge', async () => {
    login.mockResolvedValue({ mfa_required: true, token_type: 'Bearer', mfa_token: 'chal-1' })

    renderWithProviders(<App />, '/login')
    const user = await fillCredentials()

    await screen.findByText('Two-factor check')
    expect(screen.getByLabelText('Authentication code')).toBeInTheDocument()

    await user.click(screen.getByRole('button', { name: /use a recovery code/i }))
    expect(screen.getByLabelText('Recovery code')).toBeInTheDocument()
  })
})
