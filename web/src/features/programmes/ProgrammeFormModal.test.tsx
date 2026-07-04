import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ProgrammeFormModal } from './ProgrammeFormModal'
import { programmeApi } from './api'

vi.mock('./api', () => ({
  programmeApi: { create: vi.fn(), update: vi.fn(), list: vi.fn(), get: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  activityApi: { listForProgramme: vi.fn() },
  enrollmentApi: { listForProgramme: vi.fn(), enroll: vi.fn(), bulk: vi.fn(), update: vi.fn() },
}))

const create = programmeApi.create as Mock

function renderModal(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ProgrammeFormModal', () => {
  beforeEach(() => vi.clearAllMocks())

  it('creates a programme with an eligibility criterion', async () => {
    create.mockResolvedValue({ id: 'p-1', name: 'Cash Transfer', type: 'individual' })
    const user = userEvent.setup()
    renderModal(<ProgrammeFormModal open onClose={() => {}} />)

    await user.type(screen.getByLabelText('Name'), 'Cash Transfer')
    await user.selectOptions(screen.getByLabelText('Status'), 'active')

    // Add an eligibility criterion.
    await user.click(screen.getByRole('button', { name: /add criterion/i }))
    await user.type(screen.getByPlaceholderText('Value'), 'dutse')

    await user.click(screen.getByRole('button', { name: /create programme/i }))

    await waitFor(() =>
      expect(create).toHaveBeenCalledWith(
        expect.objectContaining({
          name: 'Cash Transfer',
          type: 'individual',
          status: 'active',
          eligibility: [{ attribute: 'lga', value: 'dutse' }],
        }),
      ),
    )
  })

  it('validates that a name is required', async () => {
    const user = userEvent.setup()
    renderModal(<ProgrammeFormModal open onClose={() => {}} />)

    await user.click(screen.getByRole('button', { name: /create programme/i }))

    expect(await screen.findByText(/a name is required/i)).toBeInTheDocument()
    expect(create).not.toHaveBeenCalled()
  })
})
