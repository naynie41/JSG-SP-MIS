import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { fireEvent, render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MdaReportsDashboard } from './MdaReportsDashboard'
import { dashboardApi } from '@/features/dashboard/api'
import { summariseReporting } from '@/features/dashboard/reportingSummary'
import type { DashboardResponse } from '@/features/dashboard/types'
import { gisApi } from '@/features/gis/api'

vi.mock('@/features/dashboard/api', async (importOriginal) => ({
  ...(await importOriginal<typeof import('@/features/dashboard/api')>()),
  dashboardApi: { get: vi.fn(), opsMetrics: vi.fn(), export: vi.fn() },
}))
vi.mock('@/features/gis/api', () => ({ gisApi: { coverage: vi.fn() } }))
vi.mock('@/features/dashboard/BandChoroplethMap', () => ({ BandChoroplethMap: () => <div data-testid="band-map" /> }))

const get = dashboardApi.get as Mock
const coverage = gisApi.coverage as Mock

function response(overrides: Partial<DashboardResponse> = {}, metrics: Record<string, unknown> = {}): DashboardResponse {
  return {
    scope: { kind: 'mda', label: 'Ministry of Health', tier: 'operational' },
    computed_at: '2026-09-14T09:30:00+01:00',
    min_cell_size: null,
    filter_options: { programmes: [], mdas: [], lgas: ['dutse', 'gumel'], wards: [], years: [2026, 2025] },
    metrics: {
      registry: {
        beneficiaries: {
          total: 50,
          by_status: { active: 45, flagged: 5 },
          by_source: { excel: 30, kobo: 20 },
          by_lga: { dutse: 30, gumel: 20 },
        },
        households: { total: 12, by_lga: {} },
      },
      programmes: { total: 4, active: 3, activities_total: 9, activities_active: 6 },
      duplicates: { matches_surfaced: 7, resolved_new: 0, resolved_served: 0, resolved_skipped: 0 },
      benefits: {
        disbursed: { benefit_count: 80, total_value: 12_000_000, total_quantity: '0' },
        budget: { allocated: 0, utilized_value: 0 },
        by_type: [{ key: 'cash', benefit_count: 60, total_value: 9_000_000, total_quantity: '0' }],
      },
      population: { total_households: 12, total_individuals: 50, net_unique_served: 40, new_registrations_period: 15, lgas_covered: 2, wards_covered: 3, period_days: 30 },
      demographics: {
        total: 50,
        by_gender: { female: 30, male: 18, unspecified: 2 },
        gender_known: 48,
        female_pct: 0.625,
        age_bands: { children: 10, youth: 20, adults: 15, elderly: 5, unknown: 0 },
        household_vs_individual: { in_household: 40, individual: 10 },
      },
      household_size: { total_households: 12, households_with_members: 12, average_size: 3.33, bands: { '1': 2, '2-3': 5, '4-6': 3, '7+': 2 } },
      registry_quality: {
        total: 50,
        verified: 45,
        pending: 5,
        suspended: 0,
        duplicates_detected: 7,
        nin_completeness: 0.8,
        phone_completeness: 0.4,
        data_completeness: 0.7,
      },
      programme_performance: [
        {
          programme_id: 'p1',
          name: 'cash transfer',
          status: 'active',
          mdas: [],
          start_date: null,
          end_date: null,
          target: 50,
          reached: 45,
          completion_rate: 0.9,
          coverage_absolute: 45,
          budget: { allocated: 1_000_000, spent: 600_000, remaining: 400_000, utilization_rate: 0.6 },
          cost_per_beneficiary: null,
          traffic_light: 'green',
          activities: [],
        },
      ],
      trends: {
        months: ['2026-07', '2026-08', '2026-09'],
        registrations: [
          { month: '2026-07', value: 10 },
          { month: '2026-08', value: 25 },
          { month: '2026-09', value: 15 },
        ],
        beneficiaries_cumulative: [
          { month: '2026-07', value: 10 },
          { month: '2026-08', value: 35 },
          { month: '2026-09', value: 50 },
        ],
        disbursement: [
          { month: '2026-07', value: 2_000_000 },
          { month: '2026-08', value: 4_000_000 },
          { month: '2026-09', value: 6_000_000 },
        ],
        programme_growth: [],
      },
      ...metrics,
    } as unknown as DashboardResponse['metrics'],
    ...overrides,
  }
}

const COVERAGE = {
  level: 'lga',
  scope: { kind: 'mda', label: 'Ministry of Health' },
  mode: 'choropleth',
  bands: { green_min: 1000, yellow_min: 250 },
  rows: [
    { key: 'dutse', name: 'Dutse', beneficiary_count: 30, benefit_count: 10, benefit_value: 500_000, funding_allocated: 0, households: 8, served: 25, active_programmes: 1, active_activities: 2, mdas: [], band: 'red' },
    { key: 'gumel', name: 'Gumel', beneficiary_count: 20, benefit_count: 5, benefit_value: 200_000, funding_allocated: 0, households: 4, served: 15, active_programmes: 1, active_activities: 1, mdas: [], band: 'red' },
  ],
  feature_collection: { type: 'FeatureCollection', features: [] },
}

function renderDashboard(canExport = true) {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <MdaReportsDashboard canExport={canExport} />
    </QueryClientProvider>,
  )
}

const card = (name: string | RegExp) => screen.findByRole('region', { name })

/**
 * The MDA Reports dashboard (FR-DSH-01): headline tiles, activity over time, record
 * quality, who is registered, where they are, delivery and programmes — every chart with
 * its values printed, hover/keyboard tooltips and a table view.
 */
describe('MdaReportsDashboard', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    get.mockResolvedValue(response())
    coverage.mockResolvedValue(COVERAGE)
  })

  it('leads with the same headline figures the Overview card shows', async () => {
    const payload = response()
    get.mockResolvedValue(payload)
    renderDashboard()

    const tiles = await card('Headline figures')
    for (const tile of summariseReporting(payload).tiles) {
      expect(within(tiles).getByText(tile.label)).toBeInTheDocument()
      expect(within(tiles).getAllByText((tile.value ?? 0).toLocaleString()).length).toBeGreaterThan(0)
    }
    expect(within(tiles).getByText('15 new in the last 30 days')).toBeInTheDocument()
  })

  it('reads the unfiltered snapshot first, once', async () => {
    renderDashboard()
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    expect(get).toHaveBeenCalledTimes(1)
    expect(get).toHaveBeenCalledWith(undefined)
  })

  it('plots one series at a time over the months, and switches between them', async () => {
    const user = userEvent.setup()
    renderDashboard()

    const trend = await card('Activity over time')
    expect(within(trend).getByRole('img', { name: /^New registrations over the last 3 months/ })).toBeInTheDocument()

    await user.click(within(trend).getByRole('button', { name: 'Value delivered' }))

    expect(within(trend).getByRole('button', { name: 'Value delivered' })).toHaveAttribute('aria-pressed', 'true')
    expect(within(trend).getByRole('img', { name: /^Value delivered over the last 3 months/ })).toBeInTheDocument()
  })

  it('walks the trend month by month from the keyboard', async () => {
    renderDashboard()

    const trend = await card('Activity over time')
    const chart = within(trend).getByRole('img', { name: /^New registrations/ })

    fireEvent.focus(chart)
    expect(within(trend).getByText('September 2026')).toBeInTheDocument()

    fireEvent.keyDown(chart, { key: 'ArrowLeft' })
    expect(within(trend).getByText('August 2026')).toBeInTheDocument()
    expect(within(trend).getAllByText('25').length).toBeGreaterThan(0)
  })

  it('gives every chart a table view', async () => {
    const user = userEvent.setup()
    renderDashboard()

    const trend = await card('Activity over time')
    await user.click(within(trend).getByRole('button', { name: 'Show Activity over time as a table' }))

    const table = within(trend).getByRole('table', { name: 'New registrations by month' })
    expect(within(table).getAllByRole('row')).toHaveLength(4)
    expect(within(table).getByText('August 2026')).toBeInTheDocument()
  })

  it('measures the quality of the records as rings and names the weakest detail', async () => {
    renderDashboard()

    const quality = await card('Quality of your records')
    expect(within(quality).getByRole('img', { name: 'NIN recorded: 80%' })).toBeInTheDocument()
    expect(within(quality).getByRole('img', { name: 'Phone recorded: 40%, the weakest detail' })).toBeInTheDocument()
    expect(within(quality).getByText('Phone recorded is the weakest detail at 40%.')).toBeInTheDocument()
  })

  it('shows who is registered: gender, age and household size', async () => {
    renderDashboard()

    const gender = await card('Women and men')
    expect(within(gender).getByText('63%')).toBeInTheDocument()
    expect(within(gender).getByText('Women').parentElement).toHaveTextContent('30')

    const age = screen.getByRole('region', { name: 'Age groups' })
    expect(within(age).getByRole('group', { name: 'People by age group' })).toBeInTheDocument()
    expect(within(age).getByText('Youth')).toBeInTheDocument()

    const households = screen.getByRole('region', { name: 'Household size' })
    expect(within(households).getByText('7 or more')).toBeInTheDocument()
    expect(within(households).getByText('Registered as individuals')).toBeInTheDocument()
  })

  it('maps coverage by LGA with a key, and lists it in the table view', async () => {
    const user = userEvent.setup()
    renderDashboard()

    const map = await card('Coverage across your LGAs')
    expect(await within(map).findByTestId('band-map')).toBeInTheDocument()
    expect(within(map).getByRole('list', { name: 'Map key' })).toHaveTextContent('1,000 or more')

    await user.click(within(map).getByRole('button', { name: 'Show Coverage across your LGAs as a table' }))
    expect(within(map).getByRole('table', { name: 'Coverage by LGA' })).toHaveTextContent('Dutse')
  })

  it('says so when boundaries are not loaded, rather than drawing an empty map', async () => {
    coverage.mockResolvedValue({ ...COVERAGE, mode: 'table', feature_collection: null })
    renderDashboard()

    const map = await card('Coverage across your LGAs')
    expect(await within(map).findByText(/boundary map is not available here yet/i)).toBeInTheDocument()
  })

  it('breaks records down by source and by status, with labels', async () => {
    renderDashboard()

    const records = await card('Records')
    expect(within(records).getByText('Excel upload')).toBeInTheDocument()
    expect(within(records).getByText('Flagged for review')).toBeInTheDocument()
  })

  it('shows how each programme is doing against target and budget', async () => {
    renderDashboard()

    const programmes = await card('How your programmes are doing')
    expect(within(programmes).getByText(/^cash transfer$/i)).toBeInTheDocument()
    expect(within(programmes).getByText('On target')).toBeInTheDocument()
    expect(within(programmes).getByText('45 of 50 · 90%')).toBeInTheDocument()
  })

  it('narrows the dashboard and the map through the same filter', async () => {
    const user = userEvent.setup()
    renderDashboard()
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    await user.selectOptions(screen.getByLabelText('LGA'), 'dutse')

    await waitFor(() => expect(get).toHaveBeenCalledWith(expect.objectContaining({ lga: 'dutse' })))
    await waitFor(() => expect(coverage).toHaveBeenCalledWith('lga', expect.objectContaining({ lga: 'dutse' })))
  })

  it('exports the dashboard as a PDF only, with the filter in force', async () => {
    const user = userEvent.setup()
    ;(dashboardApi.export as Mock).mockResolvedValue(undefined)
    renderDashboard()
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    // No format choice: the export is this dashboard on paper.
    expect(screen.queryByLabelText('Export as')).not.toBeInTheDocument()

    await user.selectOptions(screen.getByLabelText('LGA'), 'gumel')
    await user.click(screen.getByRole('button', { name: 'Export PDF' }))

    await waitFor(() =>
      expect(dashboardApi.export).toHaveBeenCalledWith('pdf', expect.objectContaining({ lga: 'gumel' }), 'mda-dashboard.pdf'),
    )
  })

  it('offers the export only to a caller who may export', async () => {
    renderDashboard(false)
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    expect(screen.queryByRole('button', { name: /export pdf/i })).not.toBeInTheDocument()
  })

  it('withholds small groups on a tier where the server publishes a minimum', async () => {
    get.mockResolvedValue(
      response({ min_cell_size: 5 }, {
        registry: {
          beneficiaries: { total: 50, by_status: { active: 47, suspended: 3 }, by_source: {}, by_lga: {} },
          households: { total: 12, by_lga: {} },
        },
      }),
    )
    renderDashboard()

    const records = await card('Records')
    expect(within(records).getByText('< 5')).toBeInTheDocument()
  })

  it('says a breakdown is empty rather than drawing an empty chart', async () => {
    get.mockResolvedValue(
      response({}, {
        benefits: {
          disbursed: { benefit_count: 0, total_value: 0, total_quantity: '0' },
          budget: { allocated: 0, utilized_value: 0 },
          by_type: [],
        },
      }),
    )
    renderDashboard()

    const benefits = await card('Benefits delivered')
    expect(within(benefits).getByText('No benefits delivered in this view yet.')).toBeInTheDocument()
  })
})
