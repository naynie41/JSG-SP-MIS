import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { GrantsPage } from './GrantsPage'
import { accessApi } from './api'
import type { AccessGrant } from './types'

vi.mock('./api', () => ({
  accessApi: { grants: vi.fn(), createGrant: vi.fn(), revokeGrant: vi.fn() },
}))

// The grant form pulls users + MDAs for its pickers.
vi.mock('@/features/users/hooks', () => ({
  useUsers: () => ({ data: [{ id: 'u-1', name: 'Amina Bello', email: 'amina@x.test' }] }),
}))
vi.mock('@/features/mdas/hooks', () => ({
  useMdas: () => ({ data: [{ id: 'm-2', name: 'MDA B' }] }),
}))

let perms = new Set<string>(['mda-access.view', 'mda-access.create', 'mda-access.edit'])
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: (p: string) => perms.has(p) }),
}))

const grantsFn = accessApi.grants as Mock
const createFn = accessApi.createGrant as Mock
const revokeFn = accessApi.revokeGrant as Mock

const grants: AccessGrant[] = [
  {
    id: 'g-1',
    user: { id: 'u-1', name: 'Amina Bello', email: 'amina@x.test' },
    mda: { id: 'm-2', name: 'MDA B' },
    granted_by: 'Admin',
    reason: 'Joint programme',
    expires_at: null,
    active: true,
    created_at: new Date().toISOString(),
  },
]

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('GrantsPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms = new Set(['mda-access.view', 'mda-access.create', 'mda-access.edit'])
  })

  it('lists grants and creates a new one', async () => {
    grantsFn.mockResolvedValue(grants)
    createFn.mockResolvedValue({ ...grants[0], id: 'g-2' })
    const user = userEvent.setup()

    renderPage(<GrantsPage />)

    // Existing grant is shown with user + MDA + active status.
    expect(await screen.findByText('Amina Bello')).toBeInTheDocument()
    expect(screen.getByText('MDA B')).toBeInTheDocument()
    expect(screen.getByText('Active')).toBeInTheDocument()

    // Open the grant modal and submit (scope to the dialog — the header button
    // shares the "Grant access" label).
    await user.click(screen.getByRole('button', { name: 'Grant access' }))
    const dialog = screen.getByRole('dialog')
    await user.selectOptions(within(dialog).getByLabelText('User'), 'u-1')
    await user.selectOptions(within(dialog).getByLabelText('MDA to grant access to'), 'm-2')
    await user.click(within(dialog).getByRole('button', { name: /^grant access$/i }))

    await waitFor(() =>
      expect(createFn).toHaveBeenCalledWith(expect.objectContaining({ user_id: 'u-1', mda_id: 'm-2' })),
    )
  })

  it('revokes a grant after confirmation', async () => {
    grantsFn.mockResolvedValue(grants)
    revokeFn.mockResolvedValue({ message: 'ok' })
    const user = userEvent.setup()

    renderPage(<GrantsPage />)

    await screen.findByText('Amina Bello')
    await user.click(screen.getByRole('button', { name: 'Revoke' }))
    // Confirm dialog → confirm (scope to the dialog; the row button also says "Revoke").
    await user.click(within(screen.getByRole('dialog')).getByRole('button', { name: /^revoke$/i }))

    await waitFor(() => expect(revokeFn).toHaveBeenCalledWith('g-1'))
  })

  it('hides grant/revoke controls without the write permissions', async () => {
    perms = new Set(['mda-access.view'])
    grantsFn.mockResolvedValue(grants)

    renderPage(<GrantsPage />)

    await screen.findByText('Amina Bello')
    expect(screen.queryByRole('button', { name: 'Grant access' })).toBeNull()
    expect(screen.queryByRole('button', { name: 'Revoke' })).toBeNull()
  })

  it('blocks users without mda-access.view', () => {
    perms = new Set()
    renderPage(<GrantsPage />)
    expect(screen.getByText(/do not have permission to view cross-MDA access/i)).toBeInTheDocument()
    expect(grantsFn).not.toHaveBeenCalled()
  })
})
