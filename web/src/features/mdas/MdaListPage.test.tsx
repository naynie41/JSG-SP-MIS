import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { fireEvent, render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import type { ReactNode } from 'react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaListPage } from './MdaListPage'
import { mdaApi } from './api'
import type { Mda } from './types'

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
    expect(await screen.findByText('No agencies yet')).toBeInTheDocument()

    // Open the create modal from the header action.
    await user.click(screen.getAllByRole('button', { name: /add agency/i })[0]!)

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
    await screen.findByText('No agencies yet')
    await user.click(screen.getAllByRole('button', { name: /add agency/i })[0]!)

    const dialog = await screen.findByRole('dialog')
    await user.type(within(dialog).getByLabelText('Name'), 'Duplicate MDA')
    fireEvent.submit(document.getElementById('mda-form')!)

    expect(await within(dialog).findByText('The name has already been taken.')).toBeInTheDocument()
    // Still open (submission failed).
    expect(screen.getByRole('dialog')).toBeInTheDocument()
  })
})

/**
 * A list that holds ministries and implementing partners together (§11, revised).
 *
 * The whole point of tagging is that "MDA" is no longer true of every row, so the
 * list must SAY which rows are government and which are not — the reader cannot be
 * expected to infer it from the organisation's name.
 */
describe('MdaListPage — government and partner together', () => {
  beforeEach(() => vi.clearAllMocks())

  const agency = (over: Partial<Mda> & { id: string; name: string; type: Mda['type'] }): Mda => ({
    status: 'active',
    contact_person: null,
    contact_email: null,
    contact_phone: null,
    address: null,
    created_at: null,
    updated_at: null,
    ...over,
  })

  it('tags a partner organisation and leaves government rows plain', async () => {
    list.mockResolvedValue([
      agency({ id: 'm1', name: 'Ministry of Health', type: 'ministry' }),
      agency({ id: 'm2', name: 'Save the Children', type: 'partner', funder_user_id: 'u9' }),
    ])

    renderPage(<MdaListPage />)
    await screen.findByText('Save the Children')

    const partnerRow = screen.getByRole('row', { name: /Save the Children/ })
    expect(within(partnerRow).getByText('Development partner')).toBeInTheDocument()

    // Government is the norm here; badging it too would make the exception invisible.
    const ministryRow = screen.getByRole('row', { name: /Ministry of Health/ })
    expect(within(ministryRow).getByText('Ministry')).toBeInTheDocument()
  })
})
