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

  // Fill step 1 (programme + name) and advance to the optional upload step.
  async function toUploadStep(user: ReturnType<typeof userEvent.setup>, programme = 'p-1', name = 'Q1 Round') {
    await screen.findByLabelText('Programme')
    await waitFor(() => expect(screen.getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    await user.selectOptions(screen.getByLabelText('Programme'), programme)
    await user.type(screen.getByLabelText('Name'), name)
    await user.click(screen.getByRole('button', { name: /next: upload/i }))
  }

  it('makes the catalog programme dropdown the first field and requires it before advancing', async () => {
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    const programme = await screen.findByLabelText('Programme')
    expect(programme.tagName).toBe('SELECT')
    await waitFor(() => expect(within(programme).getByRole('option', { name: 'Cash Transfer' })).toBeInTheDocument())
    // It is the first field: it appears before the activity Name field.
    const fields = screen.getAllByRole('combobox').concat(screen.getAllByRole('textbox'))
    expect(fields[0]).toBe(programme)

    // Advancing without a programme is blocked (stays on step 1).
    await user.type(screen.getByLabelText('Name'), 'Q1 Round')
    await user.click(screen.getByRole('button', { name: /next: upload/i }))
    expect(await screen.findByText(/select a programme/i)).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /skip & create activity/i })).toBeNull()
  })

  it('skips the upload and creates the activity alone', async () => {
    createActivity.mockResolvedValue({ id: 'a-1' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await toUploadStep(user, 'p-2', 'Dry-season Round')
    // Step 2 — skip.
    await user.click(await screen.findByRole('button', { name: /skip & create activity/i }))

    await waitFor(() =>
      expect(createActivity).toHaveBeenCalledWith(expect.objectContaining({ programme_id: 'p-2', name: 'Dry-season Round' })),
    )
    expect(previewImport).not.toHaveBeenCalled()
    expect(navigate).not.toHaveBeenCalled()
  })

  it('with an attached file, stages a preview and navigates to the import page (dedup before saving)', async () => {
    previewImport.mockResolvedValue({ id: 'batch-9' })
    const user = userEvent.setup()
    renderModal(<ActivityFormModal open onClose={() => {}} />)

    await toUploadStep(user, 'p-1', 'Q1 Round')
    await user.upload(await screen.findByLabelText(/choose a beneficiary file/i), new File(['a,b'], 'people.csv', { type: 'text/csv' }))
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    await waitFor(() =>
      expect(previewImport).toHaveBeenCalledWith(expect.objectContaining({ draft: expect.objectContaining({ programme_id: 'p-1', name: 'Q1 Round' }) })),
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
