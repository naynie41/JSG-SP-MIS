import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { fireEvent } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ReferralDetailPage } from './ReferralDetailPage'
import { referralApi } from './api'
import type { Referral } from './types'

vi.mock('./api', () => ({
  referralApi: { get: vi.fn(), act: vi.fn(), list: vi.fn(), create: vi.fn() },
}))

// The signed-in user is in the RECEIVING MDA, so lifecycle actions are available.
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { mda: { id: 'to-mda' } }, hasPermission: () => true }),
}))

const get = referralApi.get as Mock
const act = referralApi.act as Mock

const referral: Referral = {
  id: 'r-1',
  beneficiary_id: 'ben-12345678',
  from_mda_id: 'from-mda',
  to_mda_id: 'to-mda',
  need: 'Cash transfer',
  notes: null,
  status: 'created',
  outcome: null,
  reason: null,
  info_request: null,
  info_response: null,
  escalation_level: 0,
  sla_breached_at: null,
  timeline: {
    created_at: '2026-07-07T09:00:00Z',
    accepted_at: null, rejected_at: null, info_requested_at: null,
    info_responded_at: null, started_at: null, completed_at: null, closed_at: null,
  },
}

function renderDetail(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter initialEntries={['/referrals/r-1']}>
          <Routes>
            <Route path="/referrals/:id" element={ui} />
          </Routes>
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ReferralDetailPage (receiving-MDA lifecycle)', () => {
  beforeEach(() => vi.clearAllMocks())

  it('accepts a referral the receiving MDA was sent', async () => {
    get.mockResolvedValue(referral)
    act.mockResolvedValue({ ...referral, status: 'accepted' })
    const user = userEvent.setup()

    renderDetail(<ReferralDetailPage />)

    // The need is the page title once loaded.
    expect(await screen.findByRole('heading', { name: 'Cash transfer' })).toBeInTheDocument()

    await user.click(screen.getByRole('button', { name: /^Accept$/ }))
    await waitFor(() => expect(act).toHaveBeenCalledWith('r-1', 'accept', {}))
  })

  it('requires a reason to reject and passes it through', async () => {
    get.mockResolvedValue(referral)
    act.mockResolvedValue({ ...referral, status: 'rejected', reason: 'Out of catchment' })
    const user = userEvent.setup()

    renderDetail(<ReferralDetailPage />)
    await screen.findByRole('heading', { name: 'Cash transfer' })

    // Open the reject dialog (action button) then submit with no reason → blocked.
    await user.click(screen.getByRole('button', { name: /^Reject$/ }))
    await screen.findByRole('dialog', { name: 'Reject referral' })
    let rejectButtons = screen.getAllByRole('button', { name: 'Reject' })
    await user.click(rejectButtons[rejectButtons.length - 1]!)
    expect(await screen.findByText(/Reason is required/i)).toBeInTheDocument()
    expect(act).not.toHaveBeenCalled()

    // Provide a reason → the transition is sent with it.
    fireEvent.change(screen.getByLabelText(/Reason/i), { target: { value: 'Out of catchment' } })
    rejectButtons = screen.getAllByRole('button', { name: 'Reject' })
    await user.click(rejectButtons[rejectButtons.length - 1]!)
    await waitFor(() => expect(act).toHaveBeenCalledWith('r-1', 'reject', { reason: 'Out of catchment' }))
  })
})
