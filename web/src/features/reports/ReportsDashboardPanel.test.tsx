import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ReportsDashboardPanel } from './ReportsDashboardPanel'
import { summariseReporting } from '@/features/dashboard/reportingSummary'
import { dashboardApi } from '@/features/dashboard/api'
import type { DashboardResponse } from '@/features/dashboard/types'

vi.mock('@/features/dashboard/api', () => ({
  dashboardApi: { get: vi.fn(), opsMetrics: vi.fn(), export: vi.fn() },
}))

const get = dashboardApi.get as Mock

function response(overrides: Partial<DashboardResponse> = {}): DashboardResponse {
  return {
    scope: { kind: 'mda', label: 'Ministry of Health', tier: 'operational' },
    computed_at: '2026-08-20T09:30:00+01:00',
    min_cell_size: null,
    metrics: {
      registry: {
        beneficiaries: {
          total: 15402,
          by_status: { active: 14000, suspended: 1402 },
          by_source: { excel: 9000, kobo: 6402 },
          by_lga: { dutse: 8000, gumel: 7402 },
        },
        households: { total: 4120, by_lga: {} },
      },
      programmes: { total: 12, active: 9, activities_total: 30, activities_active: 23 },
      duplicates: { matches_surfaced: 47, resolved_new: 0, resolved_served: 0, resolved_skipped: 0 },
      benefits: {
        disbursed: { benefit_count: 8800, total_value: 0, total_quantity: '0' },
        budget: { allocated: 0, utilized_value: 0 },
        by_type: [{ key: 'cash', benefit_count: 5000, total_value: 0, total_quantity: '0' }],
      },
      referrals: {},
    } as unknown as DashboardResponse['metrics'],
    ...overrides,
  }
}

function renderPanel() {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <ReportsDashboardPanel />
    </QueryClientProvider>,
  )
}

/**
 * The full reporting dashboard inside Reports (FR-DSH-01).
 *
 * This is the page the Overview's summary card expands into, so the binding property is
 * that the two show the same numbers. They share `summariseReporting()` over the same
 * query — these tests hold that shared derivation in place rather than duplicating
 * expected figures, which would drift the moment someone edited one fixture.
 */
describe('ReportsDashboardPanel', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    get.mockResolvedValue(response())
  })

  it('shows exactly the figures the Overview summary shows', async () => {
    const payload = response()
    get.mockResolvedValue(payload)
    renderPanel()

    await screen.findByText('Ministry of Health')

    for (const tile of summariseReporting(payload).tiles) {
      expect(screen.getByText(tile.label)).toBeInTheDocument()
      expect(screen.getAllByText((tile.value ?? 0).toLocaleString()).length).toBeGreaterThan(0)
    }
  })

  it('reads the same query the Overview card reads', async () => {
    renderPanel()
    await screen.findByText('Ministry of Health')

    expect(get).toHaveBeenCalledTimes(1)
    expect(get).toHaveBeenCalledWith(undefined)
  })

  it('states the scope and when the figures were computed', async () => {
    renderPanel()

    expect(await screen.findByText('Ministry of Health')).toBeInTheDocument()
    expect(screen.getByText(/Computed/)).toBeInTheDocument()
  })

  it('breaks the population down without introducing a new measure', async () => {
    renderPanel()

    const status = (await screen.findByText('Beneficiaries by status')).closest('section')!
    expect(within(status).getByText('Active')).toBeInTheDocument()
    expect(within(status).getByText('14,000')).toBeInTheDocument()

    expect(screen.getByText('Beneficiaries by LGA')).toBeInTheDocument()
    expect(screen.getByText('Benefits by type')).toBeInTheDocument()
  })

  it('says a breakdown is empty rather than drawing an empty chart', async () => {
    get.mockResolvedValue(
      response({
        metrics: {
          ...response().metrics,
          benefits: { ...response().metrics.benefits, by_type: [] },
        } as DashboardResponse['metrics'],
      }),
    )
    renderPanel()

    const benefits = (await screen.findByText('Benefits by type')).closest('section')!
    expect(within(benefits).getByText(/nothing recorded in this scope yet/i)).toBeInTheDocument()
  })

  it('withholds a small figure on an aggregate tier and explains why', async () => {
    get.mockResolvedValue(
      response({
        scope: { kind: 'state_wide', label: 'State-wide', tier: 'statewide' },
        min_cell_size: 5,
        metrics: {
          ...response().metrics,
          duplicates: { matches_surfaced: 2, resolved_new: 0, resolved_served: 0, resolved_skipped: 0 },
        } as DashboardResponse['metrics'],
      }),
    )
    renderPanel()

    expect(await screen.findByText('< 5')).toBeInTheDocument()
    expect(screen.getByText(/cannot be identified from a count/i)).toBeInTheDocument()
  })
})
