import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MdaReportsDashboard } from './MdaReportsDashboard'
import { dashboardApi } from '@/features/dashboard/api'
import { summariseReporting } from '@/features/dashboard/reportingSummary'
import type { DashboardResponse } from '@/features/dashboard/types'

vi.mock('@/features/dashboard/api', () => ({
  dashboardApi: { get: vi.fn(), opsMetrics: vi.fn(), export: vi.fn() },
}))

const get = dashboardApi.get as Mock

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
        disbursed: { benefit_count: 80, total_value: 0, total_quantity: '0' },
        budget: { allocated: 0, utilized_value: 0 },
        by_type: [{ key: 'cash', benefit_count: 60, total_value: 0, total_quantity: '0' }],
      },
      demographics: {
        total: 50,
        by_gender: { female: 30, male: 18, unspecified: 2 },
        gender_known: 48,
        female_pct: 0.625,
        age_bands: { children: 10, youth: 20, adults: 15, elderly: 5, unknown: 0 },
        household_vs_individual: { in_household: 40, individual: 10 },
      },
      household_size: { total_households: 12, households_with_members: 12, average_size: 3.33, bands: { '7+': 2 } },
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
      trends: {
        months: ['2026-07', '2026-08', '2026-09'],
        registrations: [
          { month: '2026-07', value: 10 },
          { month: '2026-08', value: 25 },
          { month: '2026-09', value: 15 },
        ],
        beneficiaries_cumulative: [],
        disbursement: [],
        programme_growth: [],
      },
      ...metrics,
    } as unknown as DashboardResponse['metrics'],
    ...overrides,
  }
}

function renderDashboard(canExport = true) {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <MdaReportsDashboard canExport={canExport} />
    </QueryClientProvider>,
  )
}

/**
 * The MDA Reports dashboard (FR-DSH-01): headline figures beside the quality of the
 * records, who is registered, then where, when and how — all from the one scoped query.
 */
describe('MdaReportsDashboard', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    get.mockResolvedValue(response())
  })

  it('leads with the same headline figures the Overview card shows', async () => {
    const payload = response()
    get.mockResolvedValue(payload)
    renderDashboard()

    await screen.findByRole('heading', { name: 'Ministry of Health' })
    const band = screen.getByRole('region', { name: /headline figures/i })

    for (const tile of summariseReporting(payload).tiles) {
      expect(within(band).getByText(tile.label)).toBeInTheDocument()
      expect(within(band).getAllByText((tile.value ?? 0).toLocaleString()).length).toBeGreaterThan(0)
    }
  })

  it('reads the unfiltered snapshot first, once', async () => {
    renderDashboard()
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    expect(get).toHaveBeenCalledTimes(1)
    expect(get).toHaveBeenCalledWith(undefined)
  })

  it('measures the quality of the records and names the weakest detail', async () => {
    renderDashboard()

    const band = await screen.findByRole('region', { name: /record quality/i })
    expect(within(band).getByText('NIN recorded')).toBeInTheDocument()
    expect(within(band).getByText('80%')).toBeInTheDocument()
    expect(within(band).getByText('Phone recorded is the weakest detail at 40%.')).toBeInTheDocument()
  })

  it('says who is registered: the share of women and the household picture', async () => {
    renderDashboard()

    const section = (await screen.findByRole('heading', { name: 'Who is registered' })).closest('section')!
    expect(within(section).getByText('63%')).toBeInTheDocument()
    expect(within(section).getByText('Women').nextElementSibling).toHaveTextContent('30')
    expect(within(section).getByText('3.3')).toBeInTheDocument()
    expect(within(section).getByText('Households of 7 or more').nextElementSibling).toHaveTextContent('2')
  })

  it('breaks the population down by place, age, source, benefit and status', async () => {
    renderDashboard()

    const lga = (await screen.findByRole('heading', { name: 'Where they live' })).closest('section')!
    expect(within(lga).getByText('Dutse')).toBeInTheDocument()

    const source = screen.getByRole('heading', { name: 'How people were registered' }).closest('section')!
    expect(within(source).getByText('Excel upload')).toBeInTheDocument()

    const status = screen.getByRole('heading', { name: 'Status of records' }).closest('section')!
    expect(within(status).getByText('Flagged for review')).toBeInTheDocument()

    expect(screen.getByRole('heading', { name: 'Age groups' })).toBeInTheDocument()
    expect(screen.getByRole('heading', { name: 'Benefits delivered' })).toBeInTheDocument()
  })

  it('gives the monthly chart a table screen readers can read', async () => {
    renderDashboard()

    const table = await screen.findByRole('table', { name: 'Registrations by month' })
    expect(within(table).getAllByRole('row')).toHaveLength(4)
    expect(within(table).getByText('25')).toBeInTheDocument()
  })

  it('narrows through the same dashboard query when a filter is chosen', async () => {
    const user = userEvent.setup()
    renderDashboard()
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    await user.selectOptions(screen.getByLabelText('LGA'), 'dutse')

    await waitFor(() => expect(get).toHaveBeenCalledWith(expect.objectContaining({ lga: 'dutse' })))
  })

  it('exports the dashboard in the chosen format, with the filter in force', async () => {
    const user = userEvent.setup()
    ;(dashboardApi.export as Mock).mockResolvedValue(undefined)
    renderDashboard()
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    await user.selectOptions(screen.getByLabelText('LGA'), 'gumel')
    await user.selectOptions(screen.getByLabelText('Export as'), 'xlsx')
    await user.click(screen.getByRole('button', { name: 'Export' }))

    await waitFor(() =>
      expect(dashboardApi.export).toHaveBeenCalledWith('xlsx', expect.objectContaining({ lga: 'gumel' })),
    )
  })

  it('offers the export only to a caller who may export', async () => {
    renderDashboard(false)
    await screen.findByRole('heading', { name: 'Ministry of Health' })

    expect(screen.queryByRole('button', { name: /export/i })).not.toBeInTheDocument()
  })

  it('withholds small groups on a tier where the server publishes a minimum', async () => {
    get.mockResolvedValue(
      response({ min_cell_size: 5, scope: { kind: 'mda', label: 'Ministry of Health', tier: 'operational' } }, {
        registry: {
          beneficiaries: { total: 50, by_status: { active: 47, suspended: 3 }, by_source: {}, by_lga: {} },
          households: { total: 12, by_lga: {} },
        },
      }),
    )
    renderDashboard()

    const status = (await screen.findByRole('heading', { name: 'Status of records' })).closest('section')!
    expect(within(status).getByText('< 5')).toBeInTheDocument()
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

    const benefits = (await screen.findByRole('heading', { name: 'Benefits delivered' })).closest('section')!
    expect(within(benefits).getByText('No benefits delivered in this view yet.')).toBeInTheDocument()
  })
})
