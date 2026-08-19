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
  activity_id: null,
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
  activity_id: null,
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

    // Scoped to the outbox table: "Declined" is also a status-view option in the
    // filter now, so a bare text query would match the <option> as readily as the chip.
    const table = await screen.findByRole('table', { name: 'Service requests my MDA raised' })
    expect(within(table).getByText('Declined')).toBeInTheDocument()
    expect(within(table).getByText('Not eligible')).toBeInTheDocument()
  })

  describe('deciding many at once', () => {
    const pending = (id: string, mdaName = 'Ministry of Women Affairs'): ServiceRequest => ({
      ...incoming,
      id,
      beneficiary_id: `ben-${id}`,
      beneficiary_name: `Beneficiary ${id}`,
      from_mda: { id: 'm-2', name: mdaName },
    })

    /** Select every pending row via the table's select-all checkbox. */
    async function selectAll(user: ReturnType<typeof userEvent.setup>) {
      const table = await screen.findByRole('table', { name: 'Incoming service requests' })
      const [selectAllBox] = within(table).getAllByRole('checkbox')
      await user.click(selectAllBox!)
    }

    it('accepts every selected request in one action', async () => {
      inbox.mockResolvedValue([pending('sr-1'), pending('sr-2'), pending('sr-3')])
      outbox.mockResolvedValue([])
      accept.mockImplementation((id: string) => Promise.resolve({ ...pending(id), status: 'accepted' }))
      const user = userEvent.setup()
      renderPage(<ServiceRequestsPage />)

      await selectAll(user)
      expect(await screen.findByText('3 selected')).toBeInTheDocument()

      await user.click(screen.getByRole('button', { name: /accept selected/i }))

      // The confirmation states what is being granted, to whom, and how many —
      // this is the largest single PII authorisation this screen can perform.
      const dialog = await screen.findByRole('dialog')
      expect(dialog).toHaveTextContent(/Ministry of Women Affairs will gain READ access/i)
      expect(dialog).toHaveTextContent(/3 beneficiaries/)
      expect(dialog).toHaveTextContent(/Ownership is unchanged/i)

      await user.click(within(dialog).getByRole('button', { name: /accept all/i }))

      await waitFor(() => expect(accept).toHaveBeenCalledTimes(3))
      expect(accept.mock.calls.map((c) => c[0])).toEqual(['sr-1', 'sr-2', 'sr-3'])
    })

    it('counts the requesting MDAs when a selection spans several', async () => {
      inbox.mockResolvedValue([pending('sr-1', 'Women Affairs'), pending('sr-2', 'Education')])
      outbox.mockResolvedValue([])
      const user = userEvent.setup()
      renderPage(<ServiceRequestsPage />)

      await selectAll(user)
      await user.click(screen.getByRole('button', { name: /accept selected/i }))

      // Naming both would bury the fact that matters: how many agencies gain access.
      expect(await screen.findByRole('dialog')).toHaveTextContent(/2 MDAs will gain READ access/i)
    })

    it('requires one reason to decline them all', async () => {
      inbox.mockResolvedValue([pending('sr-1'), pending('sr-2')])
      outbox.mockResolvedValue([])
      decline.mockImplementation((id: string) => Promise.resolve({ ...pending(id), status: 'declined' }))
      const user = userEvent.setup()
      renderPage(<ServiceRequestsPage />)

      await selectAll(user)
      await user.click(screen.getByRole('button', { name: /decline selected/i }))

      const dialog = await screen.findByRole('dialog')
      await user.click(within(dialog).getByRole('button', { name: /decline all/i }))

      // Same rule as a single decline — a refusal the requester cannot understand
      // is not a decision, it is a dead end.
      expect(await within(dialog).findByText(/a reason is required/i)).toBeInTheDocument()
      expect(decline).not.toHaveBeenCalled()

      await user.type(within(dialog).getByLabelText(/reason applied to all/i), 'Out of scope')
      await user.click(within(dialog).getByRole('button', { name: /decline all/i }))

      await waitFor(() => expect(decline).toHaveBeenCalledTimes(2))
      expect(decline).toHaveBeenCalledWith('sr-1', 'Out of scope')
    })

    it('keeps the rows it could not save selected', async () => {
      inbox.mockResolvedValue([pending('sr-1'), pending('sr-2')])
      outbox.mockResolvedValue([])
      accept.mockImplementation((id: string) =>
        id === 'sr-2' ? Promise.reject(new Error('boom')) : Promise.resolve({ ...pending(id), status: 'accepted' }),
      )
      const user = userEvent.setup()
      renderPage(<ServiceRequestsPage />)

      await selectAll(user)
      await user.click(screen.getByRole('button', { name: /accept selected/i }))
      await user.click(within(await screen.findByRole('dialog')).getByRole('button', { name: /accept all/i }))

      // A partial failure must not vanish: the one that failed stays selected so it
      // can be retried, and the count says so.
      await waitFor(() => expect(screen.getByText('1 selected')).toBeInTheDocument())
    })

    it('offers no bulk bar until something is selected', async () => {
      inbox.mockResolvedValue([pending('sr-1')])
      outbox.mockResolvedValue([])
      renderPage(<ServiceRequestsPage />)

      await screen.findByRole('table', { name: 'Incoming service requests' })
      expect(screen.queryByRole('button', { name: /accept selected/i })).not.toBeInTheDocument()
    })

    it('never bulk-decides an already-decided request', async () => {
      // Selecting across the History view could otherwise re-apply a decision to a row
      // that was settled days ago.
      inbox.mockResolvedValue([pending('sr-1'), { ...pending('sr-2'), status: 'accepted' as const }])
      outbox.mockResolvedValue([])
      accept.mockImplementation((id: string) => Promise.resolve({ ...pending(id), status: 'accepted' }))
      const user = userEvent.setup()
      renderPage(<ServiceRequestsPage />)

      await user.selectOptions(screen.getAllByLabelText('View')[0]!, '')
      await selectAll(user)
      await user.click(screen.getByRole('button', { name: /accept selected/i }))
      await user.click(within(await screen.findByRole('dialog')).getByRole('button', { name: /accept all/i }))

      await waitFor(() => expect(accept).toHaveBeenCalledTimes(1))
      expect(accept).toHaveBeenCalledWith('sr-1', undefined)
    })
  })
})
