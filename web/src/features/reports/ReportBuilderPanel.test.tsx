import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { ReportBuilderPanel } from './ReportBuilderPanel'
import { reportsApi } from './api'
import type { AdHocDataset } from './types'

vi.mock('./api', () => ({
  reportsApi: {
    segmentDimensions: vi.fn(),
    segmentPreview: vi.fn(),
    exportSegment: vi.fn(),
    preview: vi.fn(),
    exportAdHoc: vi.fn(),
    runs: vi.fn().mockResolvedValue({ items: [] }),
  },
}))

const segmentDimensions = reportsApi.segmentDimensions as Mock
const segmentPreview = reportsApi.segmentPreview as Mock
const exportSegment = reportsApi.exportSegment as Mock

const LGAS = ['Auyo', 'Babura', 'Biriniwa', 'Birnin Kudu', 'Buji', 'Dutse', 'Gagarawa', 'Gumel', 'Ringim']

function catalogue(overrides: Record<string, unknown> = {}) {
  return {
    tier: 'rows',
    reveal_pii: false,
    cell_size_guard: false,
    minimum_cell_size: 5,
    dimensions: [
      { key: 'date_of_birth', label: 'Age', kind: 'age', canonical: true, unit: 'years' },
      {
        key: 'gender',
        label: 'Gender',
        kind: 'enum',
        canonical: true,
        options: [
          { value: 'female', label: 'Female' },
          { value: 'male', label: 'Male' },
        ],
      },
      {
        key: 'lga',
        label: 'LGA',
        kind: 'enum',
        canonical: true,
        options: LGAS.map((l) => ({ value: l.toLowerCase().replace(' ', '_'), label: l })),
      },
      { key: 'ward', label: 'Ward', kind: 'lookup', canonical: true, groupable: true },
      {
        key: 'household_role',
        label: 'Household role',
        kind: 'enum',
        canonical: true,
        groupable: false,
        options: [
          { value: 'head', label: 'Head' },
          { value: 'child', label: 'Child' },
        ],
      },
    ],
    ...overrides,
  }
}

function previewResult(overrides: Record<string, unknown> = {}) {
  return {
    total: 3,
    total_suppressed: false,
    tier: 'rows',
    reveal_pii: false,
    cell_size_guard: false,
    minimum_cell_size: 5,
    columns: [{ key: 'first_name', label: 'First name' }],
    rows: [{ first_name: 'Amina' }],
    page: 1,
    page_size: 50,
    breakdown: null,
    ...overrides,
  }
}

const datasets: AdHocDataset[] = [
  {
    key: 'benefits',
    label: 'Benefits (ledger)',
    dimensions: [{ key: 'programme', label: 'Programme' }],
    measures: [{ key: 'count', label: 'Deliveries' }],
    filters: [],
  } as unknown as AdHocDataset,
]

function renderPanel(canExport = true) {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <ToastProvider>
        <ReportBuilderPanel datasets={datasets} canExport={canExport} />
      </ToastProvider>
    </QueryClientProvider>,
  )
}

/**
 * The merged report builder (FR-RPT-03).
 *
 * The redesign's claim is that an officer arrives with a QUESTION, not with a choice of
 * engine — so the subject comes first and the builder follows. These tests pin that, and
 * pin the filter behaviour that made the old screen unreadable: nothing is shown until
 * it is asked for.
 */
describe('ReportBuilderPanel', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    segmentDimensions.mockResolvedValue(catalogue())
    segmentPreview.mockResolvedValue(previewResult())
    exportSegment.mockResolvedValue({ id: 'run-1' })
  })

  it('starts on people and offers the aggregate datasets as alternative subjects', async () => {
    renderPanel()

    const subject = await screen.findByLabelText(/what are you reporting on/i)
    expect(subject).toHaveValue('__people__')
    expect(within(subject as HTMLSelectElement).getByText('Benefits (ledger)')).toBeInTheDocument()
  })

  it('shows no filters until one is added', async () => {
    // The core of the redesign. The old panel rendered every dimension at once,
    // twenty-seven LGA checkboxes included, before the officer had asked anything.
    renderPanel()

    expect(await screen.findByText(/no filters/i)).toBeInTheDocument()
    expect(screen.queryByRole('group', { name: 'Gender' })).not.toBeInTheDocument()
    expect(screen.queryByRole('checkbox')).not.toBeInTheDocument()
  })

  it('adds only the chosen dimension and reports what it currently says', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: new RegExp('^Gender', 'i') }))

    // Added, and nothing else came with it.
    expect(screen.getByRole('checkbox', { name: 'Female' })).toBeInTheDocument()
    expect(screen.queryByRole('checkbox', { name: 'Dutse' })).not.toBeInTheDocument()

    // Reads as "Any" until something is picked, then states the selection.
    expect(screen.getByText('Any')).toBeInTheDocument()
    await userEvent.click(screen.getByRole('checkbox', { name: 'Female' }))
    expect(screen.queryByText('Any')).not.toBeInTheDocument()
    // Twice now: the checkbox that was ticked, and the filter summarising itself.
    expect(screen.getAllByText('Female')).toHaveLength(2)
  })

  it('searches a long option list instead of scrolling twenty-seven checkboxes', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: new RegExp('^LGA', 'i') }))

    const search = screen.getByPlaceholderText(/search lga/i)
    await userEvent.type(search, 'dut')

    expect(screen.getByRole('checkbox', { name: 'Dutse' })).toBeInTheDocument()
    expect(screen.queryByRole('checkbox', { name: 'Auyo' })).not.toBeInTheDocument()
  })

  it('removes a filter and stops sending it', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: new RegExp('^Gender', 'i') }))
    await userEvent.click(screen.getByRole('checkbox', { name: 'Female' }))
    await userEvent.click(screen.getByRole('button', { name: /remove the gender filter/i }))
    await userEvent.click(screen.getByRole('button', { name: /run report/i }))

    expect(segmentPreview).toHaveBeenCalledWith({ filters: {}, breakdown: null })
  })

  it('composes several dimensions into one query', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: new RegExp('^Gender', 'i') }))
    await userEvent.click(screen.getByRole('checkbox', { name: 'Female' }))

    await userEvent.click(screen.getByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: new RegExp('^Age', 'i') }))
    await userEvent.type(screen.getByLabelText('From age'), '20')
    await userEvent.type(screen.getByLabelText('To age'), '25')

    await userEvent.click(screen.getByRole('button', { name: /run report/i }))

    expect(segmentPreview).toHaveBeenCalledWith({
      filters: {
        gender: { op: 'in', values: ['female'] },
        date_of_birth: { op: 'between', values: ['20', '25'] },
      },
      breakdown: null,
    })
  })

  it('states what this role will get back before the query is built', async () => {
    segmentDimensions.mockResolvedValue(
      catalogue({ tier: 'aggregate', cell_size_guard: true, minimum_cell_size: 5 }),
    )
    renderPanel()

    expect(await screen.findByText(/counts only/i)).toBeInTheDocument()
    expect(screen.getByText(/groups under 5 withheld/i)).toBeInTheDocument()
  })

  it('closes to a pill that still says what it filters', async () => {
    // The space fix: a closed filter is one pill carrying its own selection, and its
    // options are not on the page at all until asked for.
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: /^Gender/i }))
    await userEvent.click(screen.getByRole('checkbox', { name: 'Female' }))

    const trigger = screen.getByRole('button', { name: /^Gender/i })
    expect(trigger).toHaveAttribute('aria-expanded', 'true')

    await userEvent.click(trigger)
    expect(trigger).toHaveAttribute('aria-expanded', 'false')

    // Closed: the options are gone from the page, the selection is not.
    expect(screen.queryByRole('checkbox', { name: 'Female' })).not.toBeInTheDocument()
    expect(within(trigger).getByText('Female')).toBeInTheDocument()
  })

  it('dismisses the menu on Escape', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: /^Gender/i }))
    expect(screen.getByRole('checkbox', { name: 'Female' })).toBeInTheDocument()

    await userEvent.keyboard('{Escape}')
    expect(screen.queryByRole('checkbox', { name: 'Female' })).not.toBeInTheDocument()
  })

  it('offers the schema field a relationship resolves, and keeps it out of the chart', async () => {
    // `household_role` is a DM.1 household field — not a beneficiaries column, resolved
    // through the open membership. It filters; it cannot be grouped by, because a join
    // to chart it would count memberships while the table counts people.
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    expect(screen.getByRole('menuitem', { name: new RegExp('^Household role', 'i') })).toBeInTheDocument()

    const chart = screen.getByLabelText(/chart breakdown/i)
    expect(within(chart).queryByRole('option', { name: 'Household role' })).not.toBeInTheDocument()
    expect(within(chart).getByRole('option', { name: 'Gender' })).toBeInTheDocument()
  })
  it('exports the query it previewed', async () => {
    renderPanel()

    await userEvent.click(await screen.findByRole('button', { name: /add filter/i }))
    await userEvent.click(screen.getByRole('menuitem', { name: new RegExp('^Gender', 'i') }))
    await userEvent.click(screen.getByRole('checkbox', { name: 'Male' }))
    await userEvent.click(screen.getByRole('button', { name: /^export$/i }))

    expect(exportSegment).toHaveBeenCalledWith(
      { filters: { gender: { op: 'in', values: ['male'] } }, breakdown: null },
      'csv',
    )
  })
})
