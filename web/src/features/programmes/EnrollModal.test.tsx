import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { EnrollModal } from './EnrollModal'
import { enrollmentApi } from './api'
import type { Programme } from './types'

vi.mock('./api', () => ({
  programmeApi: {},
  activityApi: { listForProgramme: vi.fn().mockResolvedValue({ items: [] }) },
  enrollmentApi: { listForProgramme: vi.fn().mockResolvedValue({ items: [] }), enroll: vi.fn(), bulk: vi.fn() },
}))

vi.mock('@/features/registry/hooks', () => ({
  useBeneficiaries: (_params: unknown, enabled: boolean) => ({
    data: { items: enabled ? [{ id: 'b-1', full_name: 'Amina Bello', lga: 'dutse', status: 'active' }] : [] },
    isLoading: false,
  }),
  useHouseholds: () => ({ data: { items: [] }, isLoading: false }),
}))

const enroll = enrollmentApi.enroll as Mock

const programme = { id: 'p-1', name: 'Cash Transfer', type: 'individual' } as Programme

function renderModal(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('EnrollModal', () => {
  beforeEach(() => vi.clearAllMocks())

  it('enrolls a selected beneficiary into an individual programme', async () => {
    enroll.mockResolvedValue({ id: 'e-1', beneficiary_id: 'b-1', eligibility_flagged: false, eligibility_notes: null })
    const user = userEvent.setup()
    renderModal(<EnrollModal open onClose={() => {}} programme={programme} />)

    await user.type(screen.getByLabelText('Search beneficiaries'), 'Amina')
    expect(await screen.findByText('Amina Bello')).toBeInTheDocument()

    await user.click(screen.getByLabelText('Select row 1'))
    await user.click(screen.getByRole('button', { name: /^enroll 1$/i }))

    await waitFor(() => expect(enroll).toHaveBeenCalledWith('p-1', { beneficiary_id: 'b-1', activity_id: undefined }))
    expect(await screen.findByText(/eligible/i)).toBeInTheDocument()
  })
})
