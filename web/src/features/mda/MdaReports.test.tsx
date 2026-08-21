import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { MdaReportsPage } from './MdaReportsPage'
import { reportsApi } from '@/features/reports/api'
import { exportListFile } from '@/lib/api/exportList'
import type { AdHocDataset } from '@/features/reports/types'

/*
 * Mocked at the SOURCE: the Phase 6 reports api and the shared list-export transport.
 * The module owns neither, so if it ever grew its own the assertions below would stop
 * seeing these calls.
 */
vi.mock('@/features/reports/api', () => ({
  reportsApi: {
    datasets: vi.fn(),
    catalogue: vi.fn(),
    preview: vi.fn(),
    exportAdHoc: vi.fn(),
    generate: vi.fn(),
    runs: vi.fn(),
    run: vi.fn(),
    download: vi.fn(),
    saveDefinition: vi.fn(),
    schedules: vi.fn(),
    createSchedule: vi.fn(),
    updateSchedule: vi.fn(),
    deleteSchedule: vi.fn(),
  },
}))
vi.mock('@/lib/api/exportList', () => ({ exportListFile: vi.fn() }))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { name: 'Amina', role: { key: 'mda_admin', name: 'MDA Admin' }, mda: { id: 'm1', name: 'Ministry of Health' } },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const datasets = reportsApi.datasets as Mock
const preview = reportsApi.preview as Mock
const exportAdHoc = reportsApi.exportAdHoc as Mock
const runs = reportsApi.runs as Mock
const schedules = reportsApi.schedules as Mock
const listExport = exportListFile as Mock

const dataset = (key: string, label: string, admin = false): AdHocDataset => ({
  key,
  label,
  admin,
  dimensions: [
    { key: 'programme', label: 'Programme' },
    { key: 'lga', label: 'LGA' },
  ],
  measures: [{ key: 'count', label: 'Count' }],
  filters: ['mda_id', 'lga'],
})

/**
 * Exactly what the server releases to an MDA scope: the delivery datasets plus
 * `duplicates` (the `mda_scopable` exception), and NOT users/audit/organizations.
 */
const MDA_DATASETS: AdHocDataset[] = [
  dataset('benefits', 'Benefits (ledger)'),
  dataset('beneficiaries', 'Beneficiaries (registry)'),
  dataset('activities', 'Activities (delivery)'),
  dataset('referrals', 'Referrals'),
  dataset('duplicates', 'Duplicate review', true),
]

/**
 * The gate here is a PERMISSION, not a role. Since the Officer/Admin merge (FR-UAM-01)
 * the seeded MDA role holds `beneficiary.export`, but a System Administrator can withhold
 * it through the role-permission editor — so both states below are reachable for the one
 * MDA role, and the page must render each correctly.
 */
const AGGREGATE_ONLY = ['reporting.view', 'reporting.export']
/** The seeded default: aggregate reporting plus the matrix-governed beneficiary export. */
const WITH_PII_EXPORT = [...AGGREGATE_ONLY, 'beneficiary.export']

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <MdaReportsPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/** The page renders its header before the datasets land; anchor on a tab instead. */
const ready = () => screen.findByRole('tab', { name: 'Report types' })

async function openTab(user: ReturnType<typeof userEvent.setup>, name: string) {
  await user.click(await screen.findByRole('tab', { name }))
  return screen.getByRole('tabpanel')
}

describe('MDA console — Reports', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = AGGREGATE_ONLY
    datasets.mockResolvedValue(MDA_DATASETS)
    runs.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 20, total: 0, total_pages: 1 } })
    schedules.mockResolvedValue([])
    listExport.mockResolvedValue({ queued: false })
  })

  /* ------------------------------------------------------------ the six types */

  /**
   * The report types come from the SERVER, not a list in this file.
   *
   * There used to be a hardcoded "Report types" tab beside the dataset catalogue — two
   * card grids whose only action was to seed the builder. It had drifted: two cards
   * ("Programme", "Benefit") pointed at the same `benefits` dataset, and it omitted
   * grievances entirely, so a dataset the server grants MDAs could not be reached from
   * it. Rendering what `/reports/adhoc/datasets` returns cannot drift that way.
   */
  it('offers exactly the report types the server released', async () => {
    renderPage()
    const panel = await screen.findByRole('tabpanel')
    await ready()

    for (const label of ['Benefits (ledger)', 'Beneficiaries (registry)', 'Activities (delivery)', 'Referrals', 'Duplicate review']) {
      expect(within(panel).getByRole('heading', { name: label })).toBeInTheDocument()
    }
  })

  it('offers one card per dataset, never two for the same one', async () => {
    // "Programme" and "Benefit" were two doors onto `benefits`, producing an identical
    // builder state from either — a choice that was not a choice.
    renderPage()
    const panel = await screen.findByRole('tabpanel')
    await ready()

    expect(within(panel).getAllByRole('button', { name: /build report/i })).toHaveLength(MDA_DATASETS.length)
  })

  it('lists a dataset the old hardcoded set left out', async () => {
    // Grievances are `coordination` data, which every non-Partner scope includes — so an
    // MDA may report on them, and the catalogue says so.
    datasets.mockResolvedValue([...MDA_DATASETS, dataset('grievances', 'Grievances')])
    renderPage()
    const panel = await screen.findByRole('tabpanel')
    await ready()

    expect(within(panel).getByRole('heading', { name: 'Grievances' })).toBeInTheDocument()
  })

  it('simply omits a dataset the server did not release', async () => {
    // No dead card claiming "not available to your account" — if the scope does not
    // admit it, it is not a report type this MDA has.
    datasets.mockResolvedValue(MDA_DATASETS.filter((d) => d.key !== 'duplicates'))
    renderPage()
    const panel = await screen.findByRole('tabpanel')
    await ready()

    expect(within(panel).queryByRole('heading', { name: 'Duplicate review' })).not.toBeInTheDocument()
    expect(within(panel).getByRole('heading', { name: 'Benefits (ledger)' })).toBeInTheDocument()
  })

  it('names the dimensions a dataset can be grouped by', async () => {
    // The operative information for building a report — the hardcoded cards showed prose
    // hints instead, which could not tell you what the builder would actually offer.
    renderPage()
    const panel = await screen.findByRole('tabpanel')
    await ready()

    const benefits = within(panel).getByRole('heading', { name: 'Benefits (ledger)' }).closest('section')!
    expect(within(benefits as HTMLElement).getByText(/^Group by/)).toBeInTheDocument()
  })

  /* --------------------------------------------------------------- engine reuse */

  it('reads its datasets from the Phase 6 endpoint, not a new one', async () => {
    renderPage()
    await ready()
    await waitFor(() => expect(datasets).toHaveBeenCalled())
  })

  it('previews and exports through the shared engine', async () => {
    preview.mockResolvedValue({
      title: 'Benefits (ledger) by programme',
      columns: [{ label: 'Programme', numeric: false }, { label: 'Count', numeric: true }],
      rows: [['Cash Transfer', '2']],
      row_count: 1,
      truncated: false,
      scope: { kind: 'mda', label: 'Ministry of Health' },
    })
    exportAdHoc.mockResolvedValue({ id: 'run-1', status: 'queued' })
    const user = userEvent.setup()
    renderPage()
    await ready()

    // Launching from a report type seeds the builder with that dataset. Scoped to the
    // card, since every card now offers the same "Build report" action.
    const activities = screen.getByRole('heading', { name: 'Activities (delivery)' }).closest('section')!
    await user.click(within(activities as HTMLElement).getByRole('button', { name: /build report/i }))
    const builder = screen.getByRole('tabpanel')
    expect(within(builder).getByLabelText('Dataset')).toHaveValue('activities')

    await user.click(within(builder).getByRole('checkbox', { name: 'Count' }))
    await user.click(within(builder).getByRole('button', { name: 'Preview' }))
    await waitFor(() => expect(preview).toHaveBeenCalledWith(expect.objectContaining({ dataset: 'activities' })))

    await user.click(screen.getByRole('button', { name: 'Export' }))
    await waitFor(() => expect(exportAdHoc).toHaveBeenCalled())
  })

  it('shows the scope the engine reported on the preview', async () => {
    preview.mockResolvedValue({
      title: 'Benefits (ledger) by programme',
      columns: [{ label: 'Programme', numeric: false }],
      rows: [['Cash Transfer']],
      row_count: 1,
      truncated: false,
      scope: { kind: 'mda', label: 'Ministry of Health' },
    })
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openTab(user, 'Build & export')
    await user.click(within(builder).getByRole('checkbox', { name: 'Count' }))
    await user.click(within(builder).getByRole('button', { name: 'Preview' }))

    // The server's own scope label — the page never asserts a scope of its own.
    expect(await screen.findByText(/Ministry of Health/)).toBeInTheDocument()
  })

  it('reuses the engine’s schedules and run history', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    await openTab(user, 'Scheduled reports')
    await waitFor(() => expect(schedules).toHaveBeenCalled())

    await openTab(user, 'Recent exports')
    await waitFor(() => expect(runs).toHaveBeenCalled())
  })

  it('offers CSV, Excel and PDF from the shared exporter registry', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openTab(user, 'Build & export')
    const format = within(builder).getByLabelText('Format')
    for (const label of ['CSV', 'Excel', 'PDF']) {
      expect(within(format).getByRole('option', { name: label })).toBeInTheDocument()
    }
  })

  /* ------------------------------------------------------ the export matrix */

  it('denies the beneficiary export without the permission and says how it is granted', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const panel = await openTab(user, 'Beneficiary export')
    expect(within(panel).getByText('Not permitted')).toBeInTheDocument()
    expect(within(panel).getByText(/MDA Administrator permission/i)).toBeInTheDocument()
    // The export control itself is absent, not merely disabled.
    expect(within(panel).queryByRole('button', { name: /export/i })).not.toBeInTheDocument()
  })

  it('allows the beneficiary export for an Admin, through the existing endpoint', async () => {
    perms.value = WITH_PII_EXPORT
    const user = userEvent.setup()
    renderPage()
    await ready()

    const panel = await openTab(user, 'Beneficiary export')
    expect(within(panel).getByText('You may export')).toBeInTheDocument()

    await user.click(within(panel).getByRole('button', { name: /export/i }))
    await user.click(await screen.findByRole('menuitem', { name: /csv/i }))

    await waitFor(() => expect(listExport).toHaveBeenCalledWith('/beneficiaries/export', {}, 'csv'))
  })

  it('states that identifiers are masked without the reveal permission', async () => {
    perms.value = WITH_PII_EXPORT
    const user = userEvent.setup()
    renderPage()
    await ready()

    const panel = await openTab(user, 'Beneficiary export')
    expect(within(panel).getByText('NIN/BVN masked')).toBeInTheDocument()
    expect(within(panel).getByText(/Identifiers are masked in the file/i)).toBeInTheDocument()
    expect(within(panel).queryByText('NIN/BVN revealed')).not.toBeInTheDocument()
  })

  it('says so when the caller does hold the reveal permission', async () => {
    perms.value = [...WITH_PII_EXPORT, 'export.reveal_pii']
    const user = userEvent.setup()
    renderPage()
    await ready()

    const panel = await openTab(user, 'Beneficiary export')
    expect(within(panel).getByText('NIN/BVN revealed')).toBeInTheDocument()
    expect(within(panel).getByText(/audited distinctly/i)).toBeInTheDocument()
  })

  it('keeps aggregate export separate from the PII export gate', async () => {
    // Holding reporting.export but not beneficiary.export: the user may build and
    // export an aggregate report while the registry export stays closed. Conflating the
    // two would either block legitimate reporting or open a PII path.
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openTab(user, 'Build & export')
    expect(within(builder).getByRole('button', { name: 'Export' })).toBeInTheDocument()

    const registry = await openTab(user, 'Beneficiary export')
    expect(within(registry).getByText('Not permitted')).toBeInTheDocument()
  })

  it('hides the builder without reporting.export', async () => {
    perms.value = ['reporting.view']
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openTab(user, 'Build & export')
    expect(within(builder).getByText(/needs the reporting export permission/i)).toBeInTheDocument()
    expect(within(builder).queryByRole('button', { name: 'Preview' })).not.toBeInTheDocument()
  })

  it('refuses the module without reporting.view', async () => {
    perms.value = []
    renderPage()

    expect(await screen.findByText(/do not have permission to view reports/i)).toBeInTheDocument()
    expect(datasets).not.toHaveBeenCalled()
  })

  /* -------------------------------------------------------------------- audit */

  it('tells the user every export is audited', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    // Tabs render only the active panel, so this lives where the export does.
    const panel = await openTab(user, 'Beneficiary export')
    expect(
      within(panel).getByText(/recorded in the audit log with who ran it, the scope and filters applied/i),
    ).toBeInTheDocument()
  })

  it('states the scope invariant plainly', async () => {
    renderPage()
    await ready()

    expect(screen.getByText(/only ever export what you could already see/i)).toBeInTheDocument()
  })
})
