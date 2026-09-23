import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { fireEvent, render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import type { ReactNode } from 'react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { UserListPage } from './UserListPage'
import { userApi, roleApi } from './api'
import { mdaApi } from '@/features/mdas/api'

vi.mock('./api', () => ({
  userApi: {
    list: vi.fn(),
    create: vi.fn(),
    update: vi.fn(),
    changeStatus: vi.fn(),
    forcePasswordReset: vi.fn(),
    resetMfa: vi.fn(),
    unlock: vi.fn(),
  },
  roleApi: { list: vi.fn() },
}))

vi.mock('@/features/mdas/api', () => ({
  mdaApi: { list: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    // cross-mda.view true → MDA not required on the form
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { permissions: ['user.view', 'user.create', 'user.edit', 'cross-mda.view', 'role.view', 'mda.view'] },
    status: 'authenticated',
  }),
}))

const listUsers = userApi.list as Mock
const createUser = userApi.create as Mock
const listRoles = roleApi.list as Mock
const listMdas = mdaApi.list as Mock

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('UserListPage — create flow', () => {
  beforeEach(() => vi.clearAllMocks())

  it('creates a user with an assigned role via the modal', async () => {
    listUsers.mockResolvedValue([])
    listRoles.mockResolvedValue([{ id: '11111111-1111-4111-8111-111111111111', key: 'mda_admin', name: 'MDA Admin', requires_mfa: false }])
    listMdas.mockResolvedValue([{ id: 'm-1', name: 'Women Affairs' }])
    createUser.mockResolvedValue({ id: 'u-1', name: 'Amina Bello' })

    const user = userEvent.setup()
    renderPage(<UserListPage />)

    expect(await screen.findByText('No users yet')).toBeInTheDocument()
    await user.click(screen.getAllByRole('button', { name: /create user/i })[0]!)

    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText('Full name'), 'Amina Bello')
    await user.type(within(dialog).getByLabelText('Email'), 'amina@example.test')
    await user.selectOptions(within(dialog).getByLabelText('Role'), '11111111-1111-4111-8111-111111111111')
    await user.type(within(dialog).getByLabelText('Temporary password'), 'Sup3rStr0ng!Pass')
    await user.type(within(dialog).getByLabelText('Confirm password'), 'Sup3rStr0ng!Pass')

    fireEvent.submit(document.getElementById('user-form')!)

    await waitFor(() =>
      expect(createUser).toHaveBeenCalledWith(
        expect.objectContaining({
          name: 'Amina Bello',
          email: 'amina@example.test',
          role_id: '11111111-1111-4111-8111-111111111111',
          password: 'Sup3rStr0ng!Pass',
          password_confirmation: 'Sup3rStr0ng!Pass',
        }),
      ),
    )
    await waitFor(() => expect(screen.queryByRole('dialog')).not.toBeInTheDocument())
  })

  it('surfaces a server field error (e.g. breached password) inline', async () => {
    const { ApiError } = await import('@/types/api')
    listUsers.mockResolvedValue([])
    listRoles.mockResolvedValue([{ id: '11111111-1111-4111-8111-111111111111', key: 'mda_admin', name: 'MDA Admin', requires_mfa: false }])
    listMdas.mockResolvedValue([])
    createUser.mockRejectedValue(
      new ApiError(422, 'VALIDATION_ERROR', 'The request is invalid.', [
        { field: 'password', message: 'This password has appeared in a data breach.' },
      ]),
    )

    const user = userEvent.setup()
    renderPage(<UserListPage />)
    await screen.findByText('No users yet')
    await user.click(screen.getAllByRole('button', { name: /create user/i })[0]!)

    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText('Full name'), 'Amina Bello')
    await user.type(within(dialog).getByLabelText('Email'), 'amina@example.test')
    await user.selectOptions(within(dialog).getByLabelText('Role'), '11111111-1111-4111-8111-111111111111')
    await user.type(within(dialog).getByLabelText('Temporary password'), 'Sup3rStr0ng!Pass')
    await user.type(within(dialog).getByLabelText('Confirm password'), 'Sup3rStr0ng!Pass')

    fireEvent.submit(document.getElementById('user-form')!)

    expect(await within(dialog).findByText('This password has appeared in a data breach.')).toBeInTheDocument()
  })
})

/**
 * A lockout is the one account state the list already flagged and gave nobody a way to
 * clear. The backoff is exponential and capped in hours, so a user who mistypes their
 * password five times could be shut out for the rest of the day with an administrator
 * watching a "locked" badge and holding no control that would help.
 */
describe('UserListPage — unlocking a locked account', () => {
  const unlock = userApi.unlock as Mock

  const locked = {
    id: 'u-9',
    name: 'Amina Bello',
    email: 'amina@example.test',
    status: 'active',
    is_locked: true,
    mfa_enabled: false,
    role: { id: 'r1', key: 'mda_admin', name: 'MDA Admin' },
    mda: { id: 'm1', name: 'Ministry of Health' },
  }

  beforeEach(() => {
    vi.clearAllMocks()
    listRoles.mockResolvedValue([])
    listMdas.mockResolvedValue([])
    unlock.mockResolvedValue({ message: 'The account is unlocked.', user: { ...locked, is_locked: false } })
  })

  it('offers Unlock on a locked row and clears the lock', async () => {
    listUsers.mockResolvedValue([locked])
    const user = userEvent.setup()
    renderPage(<UserListPage />)

    await screen.findByText('Amina Bello')
    await user.click(screen.getByRole('button', { name: /actions for/i }))
    await user.click(await screen.findByText('Unlock account'))

    const dialog = await screen.findByRole('dialog')
    // The dialog has to distinguish this from a password reset, or an admin will reach
    // for the wrong one: unlocking does not change the password.
    expect(within(dialog).getByText(/password is unchanged/i)).toBeInTheDocument()
    await user.click(within(dialog).getByRole('button', { name: 'Unlock' }))

    await waitFor(() => expect(unlock).toHaveBeenCalledWith('u-9'))
  })

  it('does not offer Unlock on an account that is not locked', async () => {
    listUsers.mockResolvedValue([{ ...locked, is_locked: false }])
    const user = userEvent.setup()
    renderPage(<UserListPage />)

    await screen.findByText('Amina Bello')
    await user.click(screen.getByRole('button', { name: /actions for/i }))

    expect(await screen.findByText('Force password reset')).toBeInTheDocument()
    expect(screen.queryByText('Unlock account')).not.toBeInTheDocument()
  })

  /**
   * A locked account is usually still `active`, so "Activate" would appear to do
   * something while the real block remained. The two are separate controls.
   */
  it('keeps unlock separate from the status actions', async () => {
    listUsers.mockResolvedValue([locked])
    const user = userEvent.setup()
    renderPage(<UserListPage />)

    await screen.findByText('Amina Bello')
    await user.click(screen.getByRole('button', { name: /actions for/i }))

    expect(await screen.findByText('Unlock account')).toBeInTheDocument()
    expect(screen.queryByText('Activate')).not.toBeInTheDocument()
  })
})
