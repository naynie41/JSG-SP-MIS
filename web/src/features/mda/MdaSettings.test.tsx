import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaSettingsPage } from './MdaSettingsPage'
import { authApi } from '@/lib/api/authApi'
import { notificationApi } from '@/features/notifications/api'
import { ApiError } from '@/types/api'

/* Every capability is mocked at its SOURCE — settings owns no endpoint of its own. */
vi.mock('@/lib/api/authApi', () => ({
  authApi: {
    login: vi.fn(), me: vi.fn(), logout: vi.fn(),
    mfaChallenge: vi.fn(), mfaEnroll: vi.fn(), mfaVerify: vi.fn(),
    changePassword: vi.fn(), mfaDisable: vi.fn(),
  },
}))
vi.mock('@/features/notifications/api', () => ({
  notificationApi: {
    list: vi.fn(), unreadCount: vi.fn(), markRead: vi.fn(), markAllRead: vi.fn(),
    preferences: vi.fn(), updatePreferences: vi.fn(),
  },
}))

const account = {
  name: 'Amina Bello',
  email: 'amina@health.jg.gov.ng',
  status: 'active',
  mfa_enabled: false,
  mfa_required: false,
  last_login_at: '2026-08-07T08:15:00+01:00',
  role: { key: 'mda_admin', name: 'MDA Admin' },
  mda: { id: 'm1', name: 'Ministry of Health' },
}
const logout = vi.fn()
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: account, logout, hasPermission: () => true }),
}))

const changePassword = authApi.changePassword as Mock
const mfaDisable = authApi.mfaDisable as Mock
const preferences = notificationApi.preferences as Mock
const updatePreferences = notificationApi.updatePreferences as Mock

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <MdaSettingsPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

async function openTab(user: ReturnType<typeof userEvent.setup>, name: string) {
  await user.click(screen.getByRole('tab', { name }))
  return screen.getByRole('tabpanel')
}

describe('MDA console — Settings', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    account.mfa_enabled = false
    account.mfa_required = false
    preferences.mockResolvedValue({ email_enabled: true })
    updatePreferences.mockResolvedValue({ email_enabled: false })
    changePassword.mockResolvedValue({ message: 'Password updated. Please sign in again.' })
    mfaDisable.mockResolvedValue({ message: 'ok' })
  })

  it('offers the four settings areas', async () => {
    renderPage()
    expect(await screen.findByRole('heading', { name: 'Settings' })).toBeInTheDocument()

    for (const name of ['Profile', 'Preferences', 'Security']) {
      expect(screen.getByRole('tab', { name })).toBeInTheDocument()
    }
  })

  /* ------------------------------------------------------------------ profile */

  it('shows the signed-in account from /auth/me', async () => {
    renderPage()
    const panel = await screen.findByRole('tabpanel')

    expect(within(panel).getByText('Amina Bello')).toBeInTheDocument()
    expect(within(panel).getByText('amina@health.jg.gov.ng')).toBeInTheDocument()
    expect(within(panel).getByText('MDA Admin')).toBeInTheDocument()
    expect(within(panel).getByText('Ministry of Health')).toBeInTheDocument()
  })

  it('does not offer a profile edit form — there is no self-service endpoint', async () => {
    renderPage()
    const panel = await screen.findByRole('tabpanel')

    // Absent, not disabled: PATCH /users/{user} is an administrator capability, so an
    // editable form here would fail for most users of this console.
    expect(within(panel).queryByRole('textbox')).not.toBeInTheDocument()
    expect(within(panel).getByText(/maintained by an administrator/i)).toBeInTheDocument()
  })

  /* -------------------------------------------------------------- preferences */

  it('reads and writes the existing notification preference', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    const panel = await openTab(user, 'Preferences')
    await waitFor(() => expect(preferences).toHaveBeenCalled())

    const toggle = within(panel).getByRole('switch', { name: /email notifications/i })
    expect(toggle).toBeChecked()

    await user.click(toggle)
    await waitFor(() => expect(updatePreferences).toHaveBeenCalledWith(false))
  })

  it('says turning email off does not silence the bell', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    const panel = await openTab(user, 'Preferences')
    expect(within(panel).getByText(/only the email copy stops/i)).toBeInTheDocument()
  })

  /* ----------------------------------------------------------------- security */

  it('changes the password through the existing endpoint and ends the session', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    const panel = await openTab(user, 'Security')
    await user.type(within(panel).getByLabelText('Current password'), 'OldPass123!x')
    await user.type(within(panel).getByLabelText('New password'), 'NewPass456!y')
    await user.type(within(panel).getByLabelText('Confirm new password'), 'NewPass456!y')
    await user.click(within(panel).getByRole('button', { name: 'Change password' }))

    await waitFor(() => expect(changePassword).toHaveBeenCalledWith('OldPass123!x', 'NewPass456!y'))
    // The server invalidates the session, so staying signed in would 401 on the next call.
    await waitFor(() => expect(logout).toHaveBeenCalled())
  })

  it('catches a mismatched confirmation before calling the server', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    const panel = await openTab(user, 'Security')
    await user.type(within(panel).getByLabelText('Current password'), 'OldPass123!x')
    await user.type(within(panel).getByLabelText('New password'), 'NewPass456!y')
    await user.type(within(panel).getByLabelText('Confirm new password'), 'Different1!z')
    await user.click(within(panel).getByRole('button', { name: 'Change password' }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/do not match/i)
    expect(changePassword).not.toHaveBeenCalled()
  })

  it('surfaces the server’s rejection rather than inventing its own', async () => {
    changePassword.mockRejectedValue(new ApiError(422, 'VALIDATION', 'The current password is incorrect.'))
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    const panel = await openTab(user, 'Security')
    await user.type(within(panel).getByLabelText('Current password'), 'wrong')
    await user.type(within(panel).getByLabelText('New password'), 'NewPass456!y')
    await user.type(within(panel).getByLabelText('Confirm new password'), 'NewPass456!y')
    await user.click(within(panel).getByRole('button', { name: 'Change password' }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/current password is incorrect/i)
    expect(logout).not.toHaveBeenCalled()
  })

  it('lets an optional MFA enrolment be turned off with a code', async () => {
    account.mfa_enabled = true
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    const panel = await openTab(user, 'Security')
    expect(within(panel).getByText('Enabled')).toBeInTheDocument()

    await user.type(within(panel).getByLabelText('Authentication code'), '123456')
    await user.click(within(panel).getByRole('button', { name: /turn off two-factor/i }))

    await waitFor(() => expect(mfaDisable).toHaveBeenCalledWith('123456'))
  })

  it('will not offer to turn off MFA when the role requires it', async () => {
    account.mfa_enabled = true
    account.mfa_required = true
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    const panel = await openTab(user, 'Security')
    expect(within(panel).getByText('Required for your role')).toBeInTheDocument()
    // The server refuses with MFA_REQUIRED; the UI does not offer a control that fails.
    expect(within(panel).queryByRole('button', { name: /turn off two-factor/i })).not.toBeInTheDocument()
    expect(within(panel).getByText(/cannot be turned off/i)).toBeInTheDocument()
  })

  it('says plainly that first-time enrolment happens at sign-in', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('tabpanel')

    // Enrolment runs behind a short-lived setup token on the login flow, so a signed-in
    // session cannot start it — better said than offered as a broken button.
    const panel = await openTab(user, 'Security')
    expect(within(panel).getByText(/set up when you sign in, not from here/i)).toBeInTheDocument()
  })

  it('writes no MDA or platform configuration', async () => {
    renderPage()
    await screen.findByRole('tabpanel')

    expect(screen.getByText(/Nothing here changes your MDA's data/i)).toBeInTheDocument()
  })
})
