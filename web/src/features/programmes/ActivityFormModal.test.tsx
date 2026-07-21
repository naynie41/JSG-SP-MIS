import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ActivityFormModal } from './ActivityFormModal'
import { activityApi, programmeApi } from './api'

vi.mock('./api', () => ({
  programmeApi: { catalog: vi.fn(), create: vi.fn(), update: vi.fn(), list: vi.fn(), get: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  activityApi: { list: vi.fn(), listForProgramme: vi.fn(), create: vi.fn(), update: vi.fn(), archive: vi.fn(), budget: vi.fn() },
  enrollmentApi: {},
}))

const navigate = vi.fn()
vi.mock('react-router-dom', async (importOriginal) => ({
  ...(await importOriginal<typeof import('react-router-dom')>()),
  useNavigate: () => navigate,
}))

const previewImport = vi.fn()
vi.mock('@/features/registry/hooks', () => ({
  usePreviewActivityImport: () => ({ mutateAsync: previewImport, isPending: false }),
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
      <ToastProvider>
        <MemoryRouter>{ui}</MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ActivityFormModal', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    catalog.mockResolvedValue(CATALOG)
  })

  async function selectProgramme(user: ReturnType<typeof userEvent.setup>, programme = 'p-1') {
    await screen.findByLabelText('Programme')
    await waitFor(() => expect(screen.getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    await user.selectOptions(screen.getByLabelText('Programme'), programme)
  }

  it('makes the catalog programme dropdown the first field and requires it', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    const programme = await screen.findByLabelText('Programme')
    expect(programme.tagName).toBe('SELECT')
    await waitFor(() => expect(within(programme).getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    // It is the first field.
    const fields = screen.getAllByRole('combobox').concat(screen.getAllByRole('textbox'))
    expect(fields[0]).toBe(programme)

    // Default is "No" → the action creates the activity alone, but still needs a programme.
    await user.type(screen.getByLabelText('Name'), 'Q1 Round')
    await user.click(screen.getByRole('button', { name: /create activity/i }))
    expect(await screen.findByText(/select a programme/i)).toBeInTheDocument()
  })

  it('saves a no-beneficiary activity alone — no target field, no upload step', async () => {
    createActivity.mockResolvedValue({ id: 'a-1' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-2')
    await user.type(screen.getByLabelText('Name'), 'Dry-season Round')

    // "No" (default): no target, no upload affordance.
    expect(screen.queryByLabelText('Target beneficiaries')).toBeNull()
    expect(screen.queryByRole('button', { name: /next: upload/i })).toBeNull()

    await user.click(screen.getByRole('button', { name: /create activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(
        expect.objectContaining({ programme_id: 'p-2', name: 'Dry-season Round', involves_beneficiaries: false, target_beneficiaries: null }),
      ),
    )
    expect(previewImport).not.toHaveBeenCalled()
    expect(navigate).not.toHaveBeenCalled()
  })

  it('shows a post-save confirmation with a View activity action (no-beneficiary path)', async () => {
    createActivity.mockResolvedValue({ id: 'a-9', name: 'Dry-season Round', involves_beneficiaries: false })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-2')
    await user.type(screen.getByLabelText('Name'), 'Dry-season Round')
    await user.click(screen.getByRole('button', { name: /create activity/i }))

    // Post-save confirmation → View activity opens the detail page.
    await user.click(await screen.findByRole('button', { name: /view activity/i }))
    expect(navigate).toHaveBeenCalledWith('/activities/a-9')
  })

  it('requires a target before advancing when it involves beneficiaries', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.selectOptions(screen.getByLabelText(/involve beneficiaries/i), 'yes')
    await user.type(screen.getByLabelText('Name'), 'Q1 Round')
    await user.click(screen.getByRole('button', { name: /next: upload/i }))

    expect(await screen.findByText(/a target is required/i)).toBeInTheDocument()
  })

  it('requires a target and a mandatory upload when it involves beneficiaries', async () => {
    previewImport.mockResolvedValue({ id: 'batch-9' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await selectProgramme(user, 'p-1')
    await user.selectOptions(screen.getByLabelText(/involve beneficiaries/i), 'yes')
    await user.type(screen.getByLabelText('Name'), 'Q1 Round')
    await user.type(screen.getByLabelText('Target beneficiaries'), '250')
    await user.click(screen.getByRole('button', { name: /next: upload/i }))

    // Step 2 is mandatory — there is no "skip & create" escape hatch.
    expect(await screen.findByLabelText(/choose a beneficiary file/i)).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /skip/i })).toBeNull()

    await user.upload(screen.getByLabelText(/choose a beneficiary file/i), new File(['a,b'], 'people.csv', { type: 'text/csv' }))
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    await waitFor(() =>
      expect(previewImport).toHaveBeenCalledWith(
        expect.objectContaining({ draft: expect.objectContaining({ programme_id: 'p-1', name: 'Q1 Round', involves_beneficiaries: true, target_beneficiaries: 250 }) }),
      ),
    )
    // The activity is NOT created directly — dedup runs in preview first.
    expect(createActivity).not.toHaveBeenCalled()
    expect(navigate).toHaveBeenCalledWith('/imports/batch-9')
  })

  it('locks the programme when a page fixes it', async () => {
    renderModal(<ActivityFormModal open onClose={() => {}} programmeId="p-1" />)

    const programme = await screen.findByLabelText('Programme')
    expect(programme).toBeDisabled()
  })
})
