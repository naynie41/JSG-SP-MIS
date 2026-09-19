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
    segmentDimensions: vi.fn(),
    segmentPreview: vi.fn(),
    exportSegment: vi.fn(),
    duplicateReview: vi.fn(),
    exportDuplicateReview: vi.fn(),
  },
}))
vi.mock('@/lib/api/exportList', () => ({ exportListFile: vi.fn() }))
// The Dashboard tab has its own tests; here it only needs to stay quiet.
vi.mock('@/features/dashboard/api', () => ({
  dashboardApi: { get: vi.fn(() => new Promise(() => {})), opsMetrics: vi.fn(), export: vi.fn() },
  filterParams: () => ({}),
}))
vi.mock('@/features/dashboard/BandChoroplethMap', () => ({ BandChoroplethMap: () => null }))

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
const segmentDimensions = reportsApi.segmentDimensions as Mock
const exportSegment = reportsApi.exportSegment as Mock
const duplicateReview = reportsApi.duplicateReview as Mock
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
  dataset('benefits', 'Benefits delivered'),
  dataset('beneficiaries', 'Beneficiaries'),
  dataset('activities', 'Activities'),
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
const ready = () => screen.findByRole('tab', { name: 'Dashboard' })

async function openTab(user: ReturnType<typeof userEvent.setup>, name: string) {
  await user.click(await screen.findByRole('tab', { name }))
  return screen.getByRole('tabpanel')
}

/**
 * The aggregate builder now sits behind the subject picker on one merged tab: an
 * officer names WHAT they are reporting on, and the matching builder follows.
 */
async function openDatasetBuilder(user: ReturnType<typeof userEvent.setup>, dataset: string) {
  const panel = await openTab(user, 'Build a report')
  await user.selectOptions(within(panel).getByLabelText(/what are you reporting on/i), dataset)
  return panel
}

describe('MDA console — Reports', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = AGGREGATE_ONLY
    datasets.mockResolvedValue(MDA_DATASETS)
    runs.mockResolvedValue({ items: [], pagination: { page: 1, per_page: 20, total: 0, total_pages: 1 } })
    schedules.mockResolvedValue([])
    listExport.mockResolvedValue({ queued: false })
    segmentDimensions.mockResolvedValue({
      dimensions: [],
      tier: 'rows',
      reveal_pii: false,
      cell_size_guard: false,
      minimum_cell_size: 5,
    })
    exportSegment.mockResolvedValue({ id: 'run-1', status: 'pending' })
    duplicateReview.mockResolvedValue({
      scope: { kind: 'mda', label: 'Ministry of Health' },
      filters: {},
      totals: { surfaced: 3, exact: 1, probable: 2, decided: 1, awaiting: 2, closed_undecided: 0 },
      decisions: { new: 0, link: 1, own: 0, skip: 0 },
      waiting: [{ key: 'recent', label: 'Under 7 days', count: 2 }],
      median_hours_to_decide: 5,
      batches: [],
      batches_total: 0,
      computed_at: '2026-09-14T09:00:00+01:00',
    })
  })

  /* ------------------------------------------------------------ the six types */

  /**
   * The report subjects come from the SERVER, not a list in this file.
   *
   * They used to be a grid of cards on their own "Report types" tab, which had drifted:
   * two cards ("Programme", "Benefit") pointed at the same `benefits` dataset, and it
   * omitted grievances entirely. That tab is gone and the subjects are now the options
   * of the builder's own subject picker — one list, rendered from what
   * `/reports/adhoc/datasets` returns, so it cannot drift from what the engine offers.
   */
  async function subjectPicker(user: ReturnType<typeof userEvent.setup>) {
    const panel = await openTab(user, 'Build a report')
    return within(panel).getByLabelText(/what are you reporting on/i) as HTMLSelectElement
  }

  it('offers the report subjects the server released', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const picker = await subjectPicker(user)
    for (const label of ['Benefits delivered', 'Activities', 'Referrals', 'Duplicate review']) {
      expect(within(picker).getByRole('option', { name: label })).toBeInTheDocument()
    }
  })

  it('does not offer the registry dataset that "People in the registry" already answers', async () => {
    // `beneficiaries` grouped the registry by LGA, ward, status and source. The People
    // builder filters and breaks down by all of those and more, and its export carries
    // the counts as a summary — a second door onto the same people is not a choice.
    const user = userEvent.setup()
    renderPage()
    await ready()

    const picker = await subjectPicker(user)
    expect(within(picker).queryByRole('option', { name: 'Beneficiaries' })).not.toBeInTheDocument()
    expect(within(picker).getByRole('option', { name: 'People in the registry' })).toBeInTheDocument()
  })

  it('offers one subject per dataset, never two for the same one', async () => {
    // "Programme" and "Benefit" were two doors onto `benefits` — a choice that was not
    // a choice. People replaces the one dataset it covers, so the count is unchanged.
    const user = userEvent.setup()
    renderPage()
    await ready()

    const picker = await subjectPicker(user)
    expect(within(picker).getAllByRole('option')).toHaveLength(MDA_DATASETS.length)
  })

  it('reports on duplicate review with a purpose-built report, not a group-by builder', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const panel = await openDatasetBuilder(user, 'duplicates')

    expect(await within(panel).findByRole('heading', { name: 'Duplicate review' })).toBeInTheDocument()
    await waitFor(() => expect(duplicateReview).toHaveBeenCalled())
    expect(within(panel).queryByRole('checkbox')).not.toBeInTheDocument()
    expect(within(panel).queryByText(/group by/i)).not.toBeInTheDocument()
    expect(within(panel).queryByLabelText('Dataset')).not.toBeInTheDocument()
  })

  it('exports people with a summary under the crest', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const panel = await openTab(user, 'Build a report')
    await user.selectOptions(await within(panel).findByLabelText('Export as'), 'pdf')
    expect(within(panel).getByText(/opens with the state crest and a summary/i)).toBeInTheDocument()

    await user.click(within(panel).getByRole('button', { name: 'Export' }))

    await waitFor(() =>
      expect(exportSegment).toHaveBeenCalledWith({ filters: {}, breakdown: null }, 'pdf', { summary: true }),
    )
  })

  it('lists a dataset the old hardcoded set left out', async () => {
    // Grievances are `coordination` data, which every non-Partner scope includes — so an
    // MDA may report on them, and the picker says so.
    datasets.mockResolvedValue([...MDA_DATASETS, dataset('grievances', 'Grievances')])
    const user = userEvent.setup()
    renderPage()
    await ready()

    const picker = await subjectPicker(user)
    expect(within(picker).getByRole('option', { name: 'Grievances' })).toBeInTheDocument()
  })

  it('simply omits a dataset the server did not release', async () => {
    // No dead entry claiming "not available to your account" — if the scope does not
    // admit it, it is not a subject this MDA has.
    datasets.mockResolvedValue(MDA_DATASETS.filter((d) => d.key !== 'duplicates'))
    const user = userEvent.setup()
    renderPage()
    await ready()

    const picker = await subjectPicker(user)
    expect(within(picker).queryByRole('option', { name: 'Duplicate review' })).not.toBeInTheDocument()
    expect(within(picker).getByRole('option', { name: 'Benefits delivered' })).toBeInTheDocument()
  })

  it('names the dimensions a dataset can be grouped by', async () => {
    // The operative information for building a report, shown once a subject is chosen.
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openDatasetBuilder(user, 'benefits')
    expect(within(builder).getByRole('checkbox', { name: 'Programme' })).toBeInTheDocument()
  })
  /* --------------------------------------------------------------- engine reuse */

  it('reads its datasets from the Phase 6 endpoint, not a new one', async () => {
    renderPage()
    await ready()
    await waitFor(() => expect(datasets).toHaveBeenCalled())
  })

  it('previews and exports through the shared engine', async () => {
    preview.mockResolvedValue({
      title: 'Benefits delivered by programme',
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

    // Choosing the subject seeds the builder with that dataset.
    const builder = await openDatasetBuilder(user, 'activities')
    expect(within(builder).getByLabelText('Subject')).toHaveValue('activities')

    await user.click(within(builder).getByRole('checkbox', { name: 'Count' }))
    await user.click(within(builder).getByRole('button', { name: 'Preview' }))
    await waitFor(() => expect(preview).toHaveBeenCalledWith(expect.objectContaining({ dataset: 'activities' })))

    await user.click(screen.getByRole('button', { name: 'Export' }))
    await waitFor(() => expect(exportAdHoc).toHaveBeenCalled())
  })

  it('shows the scope the engine reported on the preview', async () => {
    preview.mockResolvedValue({
      title: 'Benefits delivered by programme',
      columns: [{ label: 'Programme', numeric: false }],
      rows: [['Cash Transfer']],
      row_count: 1,
      truncated: false,
      scope: { kind: 'mda', label: 'Ministry of Health' },
    })
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openDatasetBuilder(user, 'benefits')
    await user.click(within(builder).getByRole('checkbox', { name: 'Count' }))
    await user.click(within(builder).getByRole('button', { name: 'Preview' }))

    // The server's own scope label — the page never asserts a scope of its own.
    expect(await screen.findByText(/Ministry of Health/)).toBeInTheDocument()
  })

  it('reuses the engine’s schedules and run history', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    await openTab(user, 'History')
    await waitFor(() => expect(schedules).toHaveBeenCalled())

    await openTab(user, 'History')
    await waitFor(() => expect(runs).toHaveBeenCalled())
  })

  it('offers CSV, Excel and PDF from the shared exporter registry', async () => {
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openDatasetBuilder(user, 'benefits')
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

    const panel = await openTab(user, 'History')
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

    const panel = await openTab(user, 'History')
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

    const panel = await openTab(user, 'History')
    expect(within(panel).getByText('NIN/BVN masked')).toBeInTheDocument()
    expect(within(panel).getByText(/Identifiers are masked in the file/i)).toBeInTheDocument()
    expect(within(panel).queryByText('NIN/BVN revealed')).not.toBeInTheDocument()
  })

  it('says so when the caller does hold the reveal permission', async () => {
    perms.value = [...WITH_PII_EXPORT, 'export.reveal_pii']
    const user = userEvent.setup()
    renderPage()
    await ready()

    const panel = await openTab(user, 'History')
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

    const builder = await openDatasetBuilder(user, 'benefits')
    expect(within(builder).getByRole('button', { name: 'Export' })).toBeInTheDocument()

    const registry = await openTab(user, 'History')
    expect(within(registry).getByText('Not permitted')).toBeInTheDocument()
  })

  it('hides the builder without reporting.export', async () => {
    perms.value = ['reporting.view']
    const user = userEvent.setup()
    renderPage()
    await ready()

    const builder = await openDatasetBuilder(user, 'benefits')
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
    const panel = await openTab(user, 'History')
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
