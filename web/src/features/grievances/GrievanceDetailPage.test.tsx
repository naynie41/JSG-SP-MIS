import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { fireEvent, render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { GrievanceDetailPage } from './GrievanceDetailPage'
import { grievanceApi } from './api'
import type { Grievance } from './types'

vi.mock('./api', () => ({
  grievanceApi: { get: vi.fn(), assign: vi.fn(), act: vi.fn(), list: vi.fn(), create: vi.fn() },
}))
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { mda: { id: 'mda-1' } }, hasPermission: () => true }),
}))
vi.mock('@/features/users/hooks', () => ({
  useUsers: () => ({ data: [{ id: 'u-2', name: 'Bola Ade', mda: { id: 'mda-1' } }], isLoading: false }),
}))

const get = grievanceApi.get as Mock
const assign = grievanceApi.assign as Mock
const act = grievanceApi.act as Mock

const grievance: Grievance = {
  id: 'g-1', handling_mda_id: 'mda-1', beneficiary_id: 'ben-123', category: 'payment', channel: 'walk_in',
  description: 'No payment received', status: 'assigned', assignee_user_id: null, resolution_notes: null,
  submitted_by: 'u-1', escalation_level: 0, sla_breached_at: null,
  timeline: { created_at: '2026-07-01T00:00:00Z', assigned_at: '2026-07-02T00:00:00Z', started_at: null, resolved_at: null, closed_at: null },
}

function renderDetail(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={['/grievances/g-1']}>
          <Routes>
            <Route path="/grievances/:id" element={ui} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('GrievanceDetailPage (assign + resolve)', () => {
  beforeEach(() => vi.clearAllMocks())

  it('assigns the grievance to a team member in the handling MDA', async () => {
    get.mockResolvedValue(grievance)
    assign.mockResolvedValue({ ...grievance, assignee_user_id: 'u-2' })
    const user = userEvent.setup()

    renderDetail(<GrievanceDetailPage />)
    await screen.findByRole('heading', { name: 'Payment' })

    fireEvent.change(screen.getByLabelText('Assign to'), { target: { value: 'u-2' } })
    await user.click(screen.getByRole('button', { name: 'Assign' }))

    await waitFor(() => expect(assign).toHaveBeenCalledWith('g-1', 'u-2'))
  })

  it('requires notes to resolve and passes them through', async () => {
    get.mockResolvedValue(grievance)
    act.mockResolvedValue({ ...grievance, status: 'resolved', resolution_notes: 'Payment re-issued' })
    const user = userEvent.setup()

    renderDetail(<GrievanceDetailPage />)
    await screen.findByRole('heading', { name: 'Payment' })

    // Open the resolve dialog and submit empty → blocked.
    await user.click(screen.getByRole('button', { name: 'Resolve' }))
    let resolveButtons = screen.getAllByRole('button', { name: 'Resolve' })
    await user.click(resolveButtons[resolveButtons.length - 1]!)
    expect(await screen.findByText(/Resolution notes is required/i)).toBeInTheDocument()
    expect(act).not.toHaveBeenCalled()

    // Provide notes → resolve is sent with them.
    fireEvent.change(screen.getByLabelText(/Resolution notes/i), { target: { value: 'Payment re-issued' } })
    resolveButtons = screen.getAllByRole('button', { name: 'Resolve' })
    await user.click(resolveButtons[resolveButtons.length - 1]!)
    await waitFor(() => expect(act).toHaveBeenCalledWith('g-1', 'resolve', { resolution_notes: 'Payment re-issued' }))
  })
})
