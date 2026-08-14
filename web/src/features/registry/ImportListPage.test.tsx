import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { fireEvent, render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ImportListPage } from './ImportListPage'
import { importApi } from './api'
import { activityApi } from '@/features/programmes/api'
import type { ImportBatch } from './types'

/**
 * The Import Center's front door: upload → (preview) → batch history.
 *
 * This is the ONLY way beneficiaries enter from a file, so its guards matter more than
 * a normal form's. Since manual registration was removed there is no fallback path — if
 * the upload panel is wrongly hidden, or the activity binding is wrongly skipped, an MDA
 * simply cannot register anyone.
 *
 * The preview/confirm half of the flow lives in `ImportBatchPage.test`; this covers the
 * list page that launches it and records what happened.
 */

vi.mock('./api', () => ({
  importApi: { list: vi.fn(), get: vi.fn(), upload: vi.fn(), confirm: vi.fn(), resolveRow: vi.fn() },
}))
vi.mock('@/features/programmes/api', () => ({
  programmeApi: { list: vi.fn(), get: vi.fn(), catalog: vi.fn() },
  activityApi: { list: vi.fn(), listForProgramme: vi.fn(), get: vi.fn() },
  enrollmentApi: { list: vi.fn(), create: vi.fn(), update: vi.fn() },
}))

let permissions = ['beneficiary.view', 'beneficiary.create']
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: (p: string) => permissions.includes(p),
    hasAnyPermission: () => true,
    user: { mda: { id: 'm-1' } },
    status: 'authenticated',
  }),
}))

const navigate = vi.fn()
vi.mock('react-router-dom', async () => ({
  ...(await vi.importActual<typeof import('react-router-dom')>('react-router-dom')),
  useNavigate: () => navigate,
}))

const listImports = importApi.list as Mock
const upload = importApi.upload as Mock
const listActivities = activityApi.list as Mock

function makeBatch(overrides: Partial<ImportBatch> = {}): ImportBatch {
  return {
    id: 'ib-1',
    original_filename: 'kano-cohort.xlsx',
    source: 'excel',
    status: 'completed',
    activity_id: 'a-1',
    summary: { total_rows: 120, valid_rows: 118, rejected_rows: 2, dropped_field_rows: 0, committed_rows: 118 },
    error: null,
    created_at: '2026-08-01T09:00:00Z',
    ...overrides,
  } as ImportBatch
}

const page = <T,>(items: T[]) => ({ items, pagination: { page: 1, per_page: 25, total: items.length, total_pages: 1 } })

/** An activity that accepts beneficiaries — the precondition for any upload (§9). */
const ACTIVITY = { id: 'a-1', name: 'Cash transfer — Q3', involves_beneficiaries: true, status: 'active' }

/**
 * The activity list and the batch list are separate queries, so the batch table can be
 * on screen while the activity dropdown is still empty. Anchor on the option itself
 * before selecting it, or the test races the query it depends on.
 */
async function chooseActivity(user: ReturnType<typeof userEvent.setup>, name = ACTIVITY.name) {
  await screen.findByRole('option', { name })
  await user.selectOptions(screen.getByLabelText(/activity/i), ACTIVITY.id)
}

/**
 * FileField keeps the real `<input type="file">` visually hidden behind a dropzone, so
 * `user.upload` (which checks pointer visibility) will not drive it. Firing the change
 * directly is the honest equivalent of the browser handing back a chosen file.
 */
function attachFile(name = 'cohort.csv') {
  const input = screen.getByLabelText(/^file$/i) as HTMLInputElement
  fireEvent.change(input, { target: { files: [new File(['first_name\nAda'], name, { type: 'text/csv' })] } })
}

function renderPage(props: { readOnly?: boolean } = {}) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <ImportListPage {...props} />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('ImportListPage — the source-ingestion front door', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    permissions = ['beneficiary.view', 'beneficiary.create']
    listImports.mockResolvedValue(page([makeBatch()]))
    listActivities.mockResolvedValue(page([ACTIVITY]))
  })

  /* ------------------------------------------------------------ batch history */

  it('lists past batches with their outcome and links each to its detail', async () => {
    listImports.mockResolvedValue(
      page([
        makeBatch(),
        makeBatch({ id: 'ib-2', original_filename: 'jigawa-kobo.csv', source: 'kobo', status: 'preview_ready' }),
      ]),
    )

    renderPage()

    // Anchored on a row, not on the table: the table renders immediately with skeleton
    // rows while the query is in flight, so awaiting the table alone proves nothing.
    const first = await screen.findByRole('link', { name: 'kano-cohort.xlsx' })
    const table = screen.getByRole('table', { name: /import batches/i })

    // The history is the audit trail of everything that entered the registry.
    expect(first).toHaveAttribute('href', '/imports/ib-1')
    expect(within(table).getByRole('link', { name: 'jigawa-kobo.csv' })).toHaveAttribute('href', '/imports/ib-2')
    expect(within(table).getAllByText('118').length).toBeGreaterThan(0) // committed rows
  })

  it('says so plainly when nothing has been imported yet', async () => {
    listImports.mockResolvedValue(page([]))

    renderPage()

    expect(await screen.findByText(/no imports yet/i)).toBeInTheDocument()
  })

  /* ------------------------------------------------- activity-first upload (§9) */

  it('refuses to upload without an activity, before touching the network', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('table', { name: /import batches/i })

    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/choose the activity/i)
    // Activity-first is a hard precondition: nothing is sent until it is satisfied.
    expect(upload).not.toHaveBeenCalled()
  })

  it('refuses to upload without a file once an activity is chosen', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('table', { name: /import batches/i })

    await chooseActivity(user)
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/choose a file/i)
    expect(upload).not.toHaveBeenCalled()
  })

  it('uploads bound to the chosen activity and goes to the preview', async () => {
    upload.mockResolvedValue({ id: 'ib-9' })
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('table', { name: /import batches/i })

    await chooseActivity(user)
    await user.selectOptions(screen.getByLabelText(/source/i), 'kobo')
    attachFile()

    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    // (file, activityId, source) — the activity binding is the middle argument, and it
    // is what makes every imported row traceable to a delivery (§9).
    await waitFor(() => expect(upload).toHaveBeenCalledWith(expect.any(File), 'a-1', 'kobo'))
    // Nothing is committed here — the user lands on the preview to review first.
    expect(navigate).toHaveBeenCalledWith('/imports/ib-9')
  })

  it('surfaces a rejected upload instead of navigating away', async () => {
    upload.mockRejectedValue(new Error('boom'))
    const user = userEvent.setup()
    renderPage()
    await screen.findByRole('table', { name: /import batches/i })

    await chooseActivity(user)
    attachFile()
    await user.click(screen.getByRole('button', { name: /upload & preview/i }))

    expect(await screen.findByRole('alert')).toHaveTextContent(/upload failed/i)
    expect(navigate).not.toHaveBeenCalled()
  })

  it('blocks the upload and explains when the MDA has no activity that accepts beneficiaries', async () => {
    // An activity that declared it involves NO beneficiaries must not be an upload
    // target — that would contradict what was recorded when it was created (§10).
    listActivities.mockResolvedValue(page([{ ...ACTIVITY, involves_beneficiaries: false }]))

    renderPage()
    await screen.findByRole('table', { name: /import batches/i })

    expect(await screen.findByRole('status')).toHaveTextContent(/no activity that accepts beneficiaries/i)
    expect(screen.getByRole('button', { name: /upload & preview/i })).toBeDisabled()
  })

  /* ----------------------------------------------------------- who may upload */

  it('shows history but no upload panel to a viewer who cannot create', async () => {
    permissions = ['beneficiary.view']

    renderPage()

    // Browsing what was imported is not the same as being able to import.
    expect(await screen.findByRole('table', { name: /import batches/i })).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /upload & preview/i })).not.toBeInTheDocument()
  })

  it('hides the upload panel in read-only mode even for a user who could upload', async () => {
    // The admin console embeds this as oversight: an administrator reviews imports,
    // but bulk ingestion belongs to an acting MDA (CLAUDE.md §10).
    renderPage({ readOnly: true })

    expect(await screen.findByRole('table', { name: /import batches/i })).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /upload & preview/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('heading', { name: /bulk import/i })).not.toBeInTheDocument()
  })

  it('refuses the page to a user without view permission', async () => {
    permissions = []

    renderPage()

    expect(await screen.findByText(/do not have permission to view imports/i)).toBeInTheDocument()
    expect(listImports).not.toHaveBeenCalled()
  })
})
