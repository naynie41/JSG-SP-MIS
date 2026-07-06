import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { fireEvent, render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ServiceRequestsPage } from './ServiceRequestsPage'
import { serviceRequestApi } from './api'
import type { ServiceRequest } from './types'

vi.mock('./api', () => ({
  serviceRequestApi: { inbox: vi.fn(), outbox: vi.fn(), accept: vi.fn(), decline: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const inbox = serviceRequestApi.inbox as Mock
const outbox = serviceRequestApi.outbox as Mock
const accept = serviceRequestApi.accept as Mock
const decline = serviceRequestApi.decline as Mock

const incoming: ServiceRequest = {
  id: 'sr-1',
  beneficiary_id: 'ben-1234abcd',
  from_mda_id: 'm-2',
  to_mda_id: 'm-1', // routed to the current user's MDA → decidable
  status: 'pending',
  reason: 'Enrolling into cash transfer',
  decided_at: null,
  decision_reason: null,
  created_at: null,
}

const mine: ServiceRequest = {
  id: 'sr-9',
  beneficiary_id: 'ben-99887766',
  from_mda_id: 'm-1', // raised by me
  to_mda_id: 'm-3',
  status: 'declined',
  reason: 'Need to serve',
  decided_at: '2026-07-01',
  decision_reason: 'Not eligible',
  created_at: null,
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>{ui}</MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ServiceRequestsPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('lets the owner MDA accept an incoming pending request from the inbox', async () => {
    inbox.mockResolvedValue([incoming])
    outbox.mockResolvedValue([])
    accept.mockResolvedValue({ ...incoming, status: 'accepted' })
    const user = userEvent.setup()
    renderPage(<ServiceRequestsPage />)

    expect(await screen.findByText('Approval inbox')).toBeInTheDocument()
    await user.click(await screen.findByRole('button', { name: /accept/i }))
    // Dialog confirms; read-access + ownership-unchanged copy is shown.
    expect(await screen.findByText(/gain READ access to the full record/i)).toBeInTheDocument()
    const dialog = screen.getByRole('dialog')
    await user.click(within(dialog).getByRole('button', { name: 'Accept' }))

    await waitFor(() => expect(accept).toHaveBeenCalledWith('sr-1', undefined))
  })

  it('blocks a decline with no reason', async () => {
    inbox.mockResolvedValue([incoming])
    outbox.mockResolvedValue([])
    const user = userEvent.setup()
    renderPage(<ServiceRequestsPage />)

    await user.click(await screen.findByRole('button', { name: /decline/i }))
    await user.click(within(screen.getByRole('dialog')).getByRole('button', { name: 'Decline' }))

    expect(await screen.findByText(/reason is required to decline/i)).toBeInTheDocument()
    expect(decline).not.toHaveBeenCalled()
  })

  it('declines with a reason and calls the API', async () => {
    inbox.mockResolvedValue([incoming])
    outbox.mockResolvedValue([])
    decline.mockResolvedValue({ ...incoming, status: 'declined', decision_reason: 'Not eligible' })
    const user = userEvent.setup()
    renderPage(<ServiceRequestsPage />)

    await user.click(await screen.findByRole('button', { name: /decline/i }))
    const dialog = screen.getByRole('dialog')
    fireEvent.change(within(dialog).getByRole('textbox'), { target: { value: 'Not eligible' } })
    await user.click(within(dialog).getByRole('button', { name: 'Decline' }))

    await waitFor(() => expect(decline).toHaveBeenCalledWith('sr-1', 'Not eligible'))
  })

  it('shows the requester a status chip and decline reason in the outbox', async () => {
    inbox.mockResolvedValue([])
    outbox.mockResolvedValue([mine])
    renderPage(<ServiceRequestsPage />)

    expect(await screen.findByText('My requests')).toBeInTheDocument()
    expect(await screen.findByText('Declined')).toBeInTheDocument()
    expect(screen.getByText('Not eligible')).toBeInTheDocument()
  })
})
