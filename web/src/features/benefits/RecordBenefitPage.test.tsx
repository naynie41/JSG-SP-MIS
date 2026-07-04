import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { RecordBenefitPage } from './RecordBenefitPage'
import { benefitApi } from './api'

vi.mock('./api', () => ({
  benefitApi: { record: vi.fn(), verify: vi.fn(), list: vi.fn(), ledger: vi.fn(), aggregate: vi.fn() },
  flagApi: { list: vi.fn(), review: vi.fn() },
  benefitImportApi: { upload: vi.fn(), get: vi.fn(), confirm: vi.fn() },
}))

vi.mock('@/features/programmes/hooks', () => ({
  useProgrammes: () => ({ data: { items: [{ id: 'p-1', name: 'Cash Transfer' }] } }),
  useActivities: () => ({ data: { items: [] } }),
}))

vi.mock('@/features/registry/hooks', () => ({
  useBeneficiaries: (_params: unknown, enabled: boolean) => ({
    data: { items: enabled ? [{ id: 'b-1', full_name: 'Amina Bello', lga: 'dutse', status: 'active' }] : [] },
    isLoading: false,
  }),
}))

vi.mock('@/lib/auth/AuthProvider', () => ({ useAuth: () => ({ hasPermission: () => true, user: { mda: { id: 'm-1' } } }) }))

const record = benefitApi.record as Mock

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

describe('RecordBenefitPage', () => {
  beforeEach(() => vi.clearAllMocks())

  it('records a verified delivery for an enrolled beneficiary', async () => {
    record.mockResolvedValue({ id: 'bf-1', status: 'verified', beneficiary_id: 'b-1' })
    const user = userEvent.setup()
    renderPage(<RecordBenefitPage />)

    // The "records delivery, not payment" affordance is present.
    expect(screen.getByText(/does not move money/i)).toBeInTheDocument()

    // Step 1 — pick a beneficiary.
    await user.type(screen.getByLabelText('Search'), 'Amina')
    await user.click(await screen.findByRole('button', { name: /select/i }))

    // Step 2 — programme.
    await user.selectOptions(screen.getByLabelText('Programme'), 'p-1')

    // Step 4 — verify with field confirmation.
    await user.selectOptions(screen.getByLabelText('Method'), 'field_confirmation')

    await user.click(screen.getByRole('button', { name: /record delivery/i }))

    await waitFor(() =>
      expect(record).toHaveBeenCalledWith(
        expect.objectContaining({
          beneficiary_id: 'b-1',
          programme_id: 'p-1',
          benefit_type: 'cash',
          verification_method: 'field_confirmation',
        }),
      ),
    )
    expect(await screen.findByText(/delivery recorded for amina bello/i)).toBeInTheDocument()
  })
})
