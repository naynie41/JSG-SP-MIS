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
    listRoles.mockResolvedValue([{ id: '11111111-1111-4111-8111-111111111111', key: 'mda_officer', name: 'MDA Officer', requires_mfa: false }])
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
    listRoles.mockResolvedValue([{ id: '11111111-1111-4111-8111-111111111111', key: 'mda_officer', name: 'MDA Officer', requires_mfa: false }])
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
