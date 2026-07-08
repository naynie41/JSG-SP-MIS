import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { fireEvent, render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { GrievanceDeskPage } from './GrievanceDeskPage'
import { grievanceApi } from './api'

vi.mock('./api', () => ({
  grievanceApi: { list: vi.fn(), get: vi.fn(), create: vi.fn(), assign: vi.fn(), act: vi.fn() },
}))
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { mda: { id: 'mda-1' } }, hasPermission: () => true }),
}))

const list = grievanceApi.list as Mock
const create = grievanceApi.create as Mock

function renderDesk(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>{ui}</MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('GrievanceDeskPage (queue + logging)', () => {
  beforeEach(() => vi.clearAllMocks())

  it('shows the queue with a status badge and flags overdue grievances', async () => {
    list.mockResolvedValue({
      items: [
        {
          id: 'g-1', handling_mda_id: 'mda-1', beneficiary_id: 'ben-123', category: 'payment', channel: 'walk_in',
          description: 'No payment', status: 'open', assignee_user_id: null, resolution_notes: null, submitted_by: 'u-1',
          escalation_level: 2, sla_breached_at: '2026-07-06T00:00:00Z',
          timeline: { created_at: '2026-07-01T00:00:00Z', assigned_at: null, started_at: null, resolved_at: null, closed_at: null },
        },
      ],
    })

    renderDesk(<GrievanceDeskPage />)

    // Wait for the row itself (its reference is unique — "Payment"/"Open" also
    // appear in the filter dropdowns).
    const row = (await screen.findByText('#g-1')).closest('tr')!
    // Overdue/escalation indicator (FR-GRM-03) — flagged at escalation level 2.
    expect(row.textContent).toMatch(/Overdue/)
    expect(row.textContent).toMatch(/L2/)
  })

  it('logs a new grievance on behalf of a beneficiary', async () => {
    list.mockResolvedValue({ items: [] })
    create.mockResolvedValue({ id: 'g-2', status: 'open' })
    const user = userEvent.setup()

    renderDesk(<GrievanceDeskPage />)
    await screen.findByRole('heading', { name: 'Grievance desk' })

    // Open the log dialog (header action).
    await user.click(screen.getByRole('button', { name: 'Log grievance' }))
    expect(await screen.findByRole('dialog', { name: 'Log a grievance' })).toBeInTheDocument()

    fireEvent.change(screen.getByLabelText('Description'), { target: { value: 'Did not receive cash transfer' } })

    // Submit (the footer button, second in DOM order after the header one).
    const submits = screen.getAllByRole('button', { name: 'Log grievance' })
    await user.click(submits[submits.length - 1]!)

    await waitFor(() =>
      expect(create).toHaveBeenCalledWith({
        category: 'payment',
        channel: 'walk_in',
        beneficiary_id: undefined,
        description: 'Did not receive cash transfer',
      }),
    )
  })
})
