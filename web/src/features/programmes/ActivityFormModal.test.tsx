import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ActivityFormModal } from './ActivityFormModal'
import { activityApi, programmeApi } from './api'

vi.mock('./api', () => ({
  programmeApi: { catalog: vi.fn(), create: vi.fn(), update: vi.fn(), list: vi.fn(), get: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  activityApi: { list: vi.fn(), listForProgramme: vi.fn(), create: vi.fn(), update: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  enrollmentApi: {},
}))

const catalog = programmeApi.catalog as Mock
const createActivity = activityApi.create as Mock

const CATALOG = {
  items: [
    { id: 'p-1', name: 'Cash Transfer', type: 'individual', status: 'active' },
    { id: 'p-2', name: 'Food Support', type: 'household', status: 'active' },
  ],
  pagination: { page: 1, per_page: 100, total: 2, total_pages: 1 },
}

function renderModal(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>{ui}</ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ActivityFormModal', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    catalog.mockResolvedValue(CATALOG)
  })

  it('makes the catalog programme dropdown the first field and requires it', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    // The programme dropdown is present and sourced from the catalog.
    const programme = await screen.findByLabelText('Programme')
    expect(programme.tagName).toBe('SELECT')
    await waitFor(() => expect(within(programme).getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    expect(within(programme).getByRole('option', { name: 'Food Support' })).toBeInTheDocument()

    // It is the first field: it appears before the activity Name field.
    const fields = screen.getAllByRole('combobox').concat(screen.getAllByRole('textbox'))
    expect(fields[0]).toBe(programme)

    // Submitting without choosing a programme is blocked.
    await user.type(screen.getByLabelText('Name'), 'Q1 Round')
    await user.click(screen.getByRole('button', { name: /add activity/i }))
    expect(await screen.findByText(/select a programme/i)).toBeInTheDocument()
    expect(createActivity).not.toHaveBeenCalled()
  })

  it('creates an activity against the chosen catalog programme', async () => {
    createActivity.mockResolvedValue({ id: 'a-1' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await screen.findByLabelText('Programme')
    await waitFor(() => expect(screen.getByRole('option', { name: 'Food Support' })).toBeInTheDocument())
    await user.selectOptions(screen.getByLabelText('Programme'), 'p-2')
    await user.type(screen.getByLabelText('Name'), 'Dry-season Round')
    await user.click(screen.getByRole('button', { name: /add activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(expect.objectContaining({ programme_id: 'p-2', name: 'Dry-season Round' })),
    )
  })

  it('locks the programme when a page fixes it', async () => {
    renderModal(<ActivityFormModal open onClose={() => {}} programmeId="p-1" />)

    const programme = await screen.findByLabelText('Programme')
    expect(programme).toBeDisabled()
  })
})
