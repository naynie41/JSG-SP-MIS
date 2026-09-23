import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { ChangePasswordRequiredPage } from './ChangePasswordRequiredPage'
import { authApi } from '@/lib/api/authApi'

vi.mock('@/lib/api/authApi', () => ({
  authApi: { changePassword: vi.fn() },
}))

const logout = vi.fn()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { name: 'Amina Bello' }, logout }),
}))

const changePassword = authApi.changePassword as Mock

/**
 * The forced password change (FR-UAM-06).
 *
 * `ProtectedRoute` sends the user here and nowhere else while `must_change_password`
 * is set, and the API refuses every other route — which is right. What was wrong is
 * that it left no way out at all: someone who could not find the temporary password
 * had no back, no menu and no sign-out, and clearing site data was the only escape.
 */
describe('ChangePasswordRequiredPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('lets someone sign out instead of being stranded', async () => {
    const user = userEvent.setup()
    render(<ChangePasswordRequiredPage />)

    await user.click(screen.getByRole('button', { name: 'Sign out' }))

    expect(logout).toHaveBeenCalledTimes(1)
    // Signing out must not be mistaken for completing the change.
    expect(changePassword).not.toHaveBeenCalled()
  })

  it('says what signing out means, so it does not look like giving up the account', async () => {
    render(<ChangePasswordRequiredPage />)

    expect(screen.getByText(/do not have the temporary password/i)).toBeInTheDocument()
    expect(screen.getByText(/an administrator can issue\s+another temporary one/i)).toBeInTheDocument()
  })

  it('still changes the password and ends the session on success', async () => {
    changePassword.mockResolvedValue({})
    const user = userEvent.setup()
    render(<ChangePasswordRequiredPage />)

    await user.type(screen.getByLabelText('Temporary password'), 'Temp0rary!Pass')
    await user.type(screen.getByLabelText('New password'), 'My0wnStr0ng!Pass')
    await user.type(screen.getByLabelText('Confirm new password'), 'My0wnStr0ng!Pass')
    await user.click(screen.getByRole('button', { name: /set password and sign in again/i }))

    await waitFor(() => expect(changePassword).toHaveBeenCalledWith('Temp0rary!Pass', 'My0wnStr0ng!Pass'))
    // The server invalidates every token, so the client must not pretend otherwise.
    await waitFor(() => expect(logout).toHaveBeenCalled())
  })

  it('catches a mismatch before troubling the server', async () => {
    const user = userEvent.setup()
    render(<ChangePasswordRequiredPage />)

    await user.type(screen.getByLabelText('Temporary password'), 'Temp0rary!Pass')
    await user.type(screen.getByLabelText('New password'), 'My0wnStr0ng!Pass')
    await user.type(screen.getByLabelText('Confirm new password'), 'Different!Pass1')
    await user.click(screen.getByRole('button', { name: /set password and sign in again/i }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/do not match/i)
    expect(changePassword).not.toHaveBeenCalled()
  })
})
