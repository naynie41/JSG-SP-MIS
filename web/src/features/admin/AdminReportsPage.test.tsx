import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminReportsPage } from './AdminReportsPage'
import { reportsApi } from '@/features/reports/api'
import type { AdHocDataset, ReportRun, ReportSchedule } from '@/features/reports/types'

vi.mock('@/features/reports/api', () => ({
  reportsApi: {
    datasets: vi.fn(),
    preview: vi.fn(),
    exportAdHoc: vi.fn(),
    generate: vi.fn(),
    runs: vi.fn(),
    download: vi.fn(),
    saveDefinition: vi.fn(),
    schedules: vi.fn(),
    updateSchedule: vi.fn(),
  },
}))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const datasets = reportsApi.datasets as Mock
const preview = reportsApi.preview as Mock
const exportAdHoc = reportsApi.exportAdHoc as Mock
const runs = reportsApi.runs as Mock
const download = reportsApi.download as Mock
const schedules = reportsApi.schedules as Mock
const updateSchedule = reportsApi.updateSchedule as Mock

/** What the server returns to a System Administrator: admin datasets + delivery ones. */
const ADMIN_DATASETS: AdHocDataset[] = [
  {
    key: 'users',
    label: 'Users & access',
    admin: true,
    dimensions: [
      { key: 'role', label: 'Role' },
      { key: 'status', label: 'Account status' },
    ],
    measures: [{ key: 'count', label: 'Users' }],
    filters: ['mda_id', 'status'],
  },
  {
    key: 'audit',
    label: 'Audit events',
    admin: true,
    dimensions: [{ key: 'action', label: 'Action' }],
    measures: [{ key: 'count', label: 'Events' }],
    filters: ['action'],
  },
  {
    key: 'imports',
    label: 'Import batches',
    admin: true,
    dimensions: [{ key: 'status', label: 'Status' }],
    measures: [
      { key: 'count', label: 'Batches' },
      { key: 'total_rows', label: 'Rows' },
    ],
    filters: ['status'],
  },
  // A non-admin dataset the server also returns — the catalogue must not list it.
  {
    key: 'benefits',
    label: 'Benefits (ledger)',
    admin: false,
    dimensions: [{ key: 'lga', label: 'LGA' }],
    measures: [{ key: 'count', label: 'Deliveries' }],
    filters: ['lga'],
  },
]

const RUNS: ReportRun[] = [
  {
    id: 'r1', report_key: 'adhoc', report_label: 'Users & access', format: 'xlsx', status: 'ready',
    scope: { kind: 'state_wide', label: 'State-wide' }, row_count: 7, file_name: 'users.xlsx',
    error: null, download_ready: true, created_at: new Date().toISOString(), completed_at: new Date().toISOString(),
  },
  {
    id: 'r2', report_key: 'adhoc', report_label: 'Audit events', format: 'csv', status: 'failed',
    scope: { kind: 'state_wide', label: 'State-wide' }, row_count: null, file_name: null,
    error: 'Generation timed out', download_ready: false, created_at: new Date().toISOString(), completed_at: null,
  },
]

const SCHEDULES: ReportSchedule[] = [
  {
    id: 's1', name: 'Weekly access review', report_key: null, report_definition_id: 'd1',
    format: 'xlsx', frequency: 'weekly', delivery: 'link', status: 'active',
    scope: { kind: 'state_wide', label: 'State-wide' }, recipient_user_ids: ['u1', 'u2'],
    last_run_on: '2026-08-01', created_at: null, updated_at: null,
  },
]

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminReportsPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — Reports', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['reporting.view', 'reporting.export']
    datasets.mockResolvedValue(ADMIN_DATASETS)
    runs.mockResolvedValue({ items: RUNS, pagination: { page: 1, per_page: 20, total: 2, total_pages: 1 } })
    schedules.mockResolvedValue(SCHEDULES)
  })

  /* --------------------------------------------------------------- catalogue */

  it('lists the administrative report families from the server catalogue', async () => {
    renderPage()

    expect(await screen.findByText('Users & access')).toBeInTheDocument()
    expect(screen.getByText('Audit events')).toBeInTheDocument()
    expect(screen.getByText('Import batches')).toBeInTheDocument()
    expect(datasets).toHaveBeenCalled()
  })

  it('does not present delivery datasets as administrative reports', async () => {
    renderPage()
    await screen.findByText('Users & access')

    // `benefits` came back from the server but is not an admin dataset.
    expect(screen.queryByText('Benefits (ledger)')).not.toBeInTheDocument()
  })

  /* ----------------------------------------------------------------- builder */

  it('builds a preview through the engine and renders the returned rows', async () => {
    preview.mockResolvedValue({
      title: 'Users & access by Role',
      scope: { kind: 'state_wide', label: 'State-wide' },
      columns: [{ label: 'Role', numeric: false }, { label: 'Users', numeric: true }],
      rows: [['System Administrator', '2'], ['MDA Officer', '5']],
      row_count: 2,
      truncated: false,
    })

    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')

    await user.click(screen.getByRole('tab', { name: /build & export/i }))
    await user.click(await screen.findByRole('checkbox', { name: 'Role' }))
    await user.click(screen.getByRole('checkbox', { name: 'Users' }))
    await user.click(screen.getByRole('button', { name: /preview/i }))

    await waitFor(() =>
      expect(preview).toHaveBeenCalledWith({
        dataset: 'users', group_by: ['role'], measures: ['count'], name: 'Users & access by role',
      }),
    )
    expect(await screen.findByText('System Administrator')).toBeInTheDocument()
    expect(screen.getByText('MDA Officer')).toBeInTheDocument()
  })

  it('offers only the dimensions and measures the server returned', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')
    await user.click(screen.getByRole('tab', { name: /build & export/i }))

    // The users dataset exposes role + status only. Nothing identifying is offered,
    // because the UI never invents a column — it renders the whitelist.
    expect(await screen.findByRole('checkbox', { name: 'Role' })).toBeInTheDocument()
    expect(screen.getByRole('checkbox', { name: 'Account status' })).toBeInTheDocument()
    expect(screen.queryByRole('checkbox', { name: /name|email|nin|phone/i })).not.toBeInTheDocument()
  })

  it('exports through the engine in the chosen format', async () => {
    exportAdHoc.mockResolvedValue(RUNS[0])
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')

    await user.click(screen.getByRole('tab', { name: /build & export/i }))
    await user.click(await screen.findByRole('checkbox', { name: 'Role' }))
    await user.click(screen.getByRole('checkbox', { name: 'Users' }))
    await user.selectOptions(screen.getByLabelText('Format'), 'csv')
    await user.click(screen.getByRole('button', { name: /^export$/i }))

    await waitFor(() =>
      expect(exportAdHoc).toHaveBeenCalledWith(
        // The run carries a readable label, so the export list is not a wall of
        // identical "Ad-hoc report" rows.
        { dataset: 'users', group_by: ['role'], measures: ['count'], name: 'Users & access by role' },
        'csv',
      ),
    )
  })

  it('will not run a report with no measure selected', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')
    await user.click(screen.getByRole('tab', { name: /build & export/i }))

    expect(await screen.findByRole('button', { name: /preview/i })).toBeDisabled()
    expect(screen.getByRole('button', { name: /^export$/i })).toBeDisabled()
    expect(preview).not.toHaveBeenCalled()
  })

  /* ------------------------------------------------------------ export gating */

  it('withholds the builder without the reporting export permission', async () => {
    perms.value = ['reporting.view'] // view only
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')

    await user.click(screen.getByRole('tab', { name: /build & export/i }))

    expect(await screen.findByText(/needs the reporting export permission/i)).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /^export$/i })).not.toBeInTheDocument()
  })

  it('withholds schedule controls without the reporting export permission', async () => {
    perms.value = ['reporting.view']
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')

    await user.click(screen.getByRole('tab', { name: /scheduled reports/i }))

    expect(await screen.findByText('Weekly access review')).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /pause|resume/i })).not.toBeInTheDocument()
  })

  /* --------------------------------------------------------------- schedules */

  it('lists schedules and pauses one through the existing endpoint', async () => {
    updateSchedule.mockResolvedValue({ ...SCHEDULES[0]!, status: 'paused' })
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')

    await user.click(screen.getByRole('tab', { name: /scheduled reports/i }))
    expect(await screen.findByText('Weekly access review')).toBeInTheDocument()
    expect(screen.getByText('Weekly')).toBeInTheDocument()

    await user.click(screen.getByRole('button', { name: /pause/i }))
    await waitFor(() => expect(updateSchedule).toHaveBeenCalledWith('s1', { status: 'paused' }))
  })

  /* -------------------------------------------------------------- recent runs */

  it('lists recent exports and downloads a ready one', async () => {
    download.mockResolvedValue(undefined)
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Users & access')

    await user.click(screen.getByRole('tab', { name: /recent exports/i }))

    const table = await screen.findByRole('table', { name: /recent exports/i })
    expect(within(table).getByText('ready')).toBeInTheDocument()
    expect(within(table).getByText('failed')).toBeInTheDocument()
    expect(within(table).getByText('Generation timed out')).toBeInTheDocument()

    // Only the ready run is downloadable.
    const buttons = screen.getAllByRole('button', { name: /download/i })
    expect(buttons).toHaveLength(1)

    await user.click(buttons[0]!)
    await waitFor(() => expect(download).toHaveBeenCalledWith(RUNS[0]))
  })
})
