import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { BenefitsPanel } from './BenefitsPanel'
import { benefitApi, flagApi } from './api'

vi.mock('./api', () => ({
  benefitApi: { ledger: vi.fn(), record: vi.fn(), verify: vi.fn(), list: vi.fn(), aggregate: vi.fn() },
  flagApi: { list: vi.fn(), review: vi.fn() },
  benefitImportApi: { upload: vi.fn(), get: vi.fn(), confirm: vi.fn() },
}))

vi.mock('@/lib/auth/AuthProvider', () => ({ useAuth: () => ({ hasPermission: () => true, user: { mda: { id: 'm-1' } } }) }))

const ledger = benefitApi.ledger as Mock
const flagList = flagApi.list as Mock
const review = flagApi.review as Mock

function renderPanel(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('BenefitsPanel (per-beneficiary ledger)', () => {
  beforeEach(() => vi.clearAllMocks())

  it('shows the ledger with status badges and surfaces double-dipping flags for review', async () => {
    ledger.mockResolvedValue({
      items: [{ id: 'bf-1', beneficiary_id: 'b-1', benefit_type: 'cash', delivery_date: '2026-07-01', mda_id: 'm-2', monetary_value: 500000, status: 'verified' }],
    })
    flagList.mockResolvedValue([
      { id: 'fl-1', beneficiary_id: 'b-1', benefit_type: 'cash', reason: 'Same type by another MDA within 30 days.', status: 'open' },
    ])
    review.mockResolvedValue({ id: 'fl-1', status: 'dismissed' })
    const user = userEvent.setup()

    renderPanel(<BenefitsPanel beneficiaryId="b-1" />)

    // Ledger row with a status badge + Naira value.
    expect(await screen.findByText('Cash')).toBeInTheDocument()
    expect(screen.getByText('Verified')).toBeInTheDocument()
    expect(screen.getByText('₦5,000.00')).toBeInTheDocument()

    // Double-dipping flag surfaced, and reviewable.
    expect(screen.getByText(/potential double-dipping/i)).toBeInTheDocument()
    await user.click(screen.getByRole('button', { name: /dismiss/i }))
    await waitFor(() => expect(review).toHaveBeenCalledWith('fl-1', { status: 'dismissed' }))
  })
})
