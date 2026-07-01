import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { fireEvent, render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import type { ReactNode } from 'react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaListPage } from './MdaListPage'
import { mdaApi } from './api'

vi.mock('./api', () => ({
  mdaApi: { list: vi.fn(), create: vi.fn(), update: vi.fn(), deactivate: vi.fn(), activate: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { permissions: ['mda.view', 'mda.create', 'mda.edit'] },
    status: 'authenticated',
  }),
}))

const list = mdaApi.list as Mock
const create = mdaApi.create as Mock

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('MdaListPage — create flow', () => {
  beforeEach(() => vi.clearAllMocks())

  it('creates an MDA via the modal form', async () => {
    list.mockResolvedValue([])
    create.mockResolvedValue({
      id: 'm-1',
      name: 'Ministry of Health',
      type: 'ministry',
      status: 'active',
      contact_person: null,
      contact_email: null,
      contact_phone: null,
      address: null,
      created_at: null,
      updated_at: null,
    })

    const user = userEvent.setup()
    renderPage(<MdaListPage />)

    // Empty state shown once the (empty) list loads.
    expect(await screen.findByText('No MDAs yet')).toBeInTheDocument()

    // Open the create modal from the header action.
    await user.click(screen.getAllByRole('button', { name: /create mda/i })[0]!)

    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText('Name'), 'Ministry of Health')

    // Submit the form (button is associated via the form attribute).
    fireEvent.submit(document.getElementById('mda-form')!)

    await waitFor(() =>
      expect(create).toHaveBeenCalledWith(expect.objectContaining({ name: 'Ministry of Health', type: 'ministry' })),
    )
    // Modal closes on success.
    await waitFor(() => expect(screen.queryByRole('dialog')).not.toBeInTheDocument())
  })

  it('shows an inline validation error from the API envelope', async () => {
    const { ApiError } = await import('@/types/api')
    list.mockResolvedValue([])
    create.mockRejectedValue(new ApiError(422, 'VALIDATION_ERROR', 'The request is invalid.', [
      { field: 'name', message: 'The name has already been taken.' },
    ]))

    const user = userEvent.setup()
    renderPage(<MdaListPage />)
    await screen.findByText('No MDAs yet')
    await user.click(screen.getAllByRole('button', { name: /create mda/i })[0]!)

    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText('Name'), 'Duplicate MDA')
    fireEvent.submit(document.getElementById('mda-form')!)

    expect(await within(dialog).findByText('The name has already been taken.')).toBeInTheDocument()
    // Still open (submission failed).
    expect(screen.getByRole('dialog')).toBeInTheDocument()
  })
})
