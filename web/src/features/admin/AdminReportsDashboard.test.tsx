import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { AdminReportsDashboard } from './AdminReportsDashboard'
import { dashboardApi } from '@/features/dashboard/api'
import type { DashboardResponse } from '@/features/dashboard/types'

vi.mock('@/features/dashboard/api', async () => {
  const actual = await vi.importActual<typeof import('@/features/dashboard/api')>('@/features/dashboard/api')
  return { ...actual, dashboardApi: { get: vi.fn(), export: vi.fn(), opsMetrics: vi.fn() } }
})
// The choropleth needs GIS coverage of its own; the board is what is under test.
vi.mock('@/features/gis/hooks', () => ({ useGisCoverage: () => ({ data: undefined, isLoading: false }) }))

const get = dashboardApi.get as Mock

const payload = (over: Partial<DashboardResponse['metrics']> = {}): DashboardResponse => ({
  scope: { kind: 'state_wide', label: 'State-wide', tier: 'statewide' },
  computed_at: '2026-09-18T09:00:00Z',
  live: false,
  min_cell_size: 5,
  filters: {
    year: null, quarter: null, month: null, programme_id: null, lga: null, ward: null, mda_id: null,
  },
  filter_options: {
    programmes: [{ id: 'p1', name: 'Cash Transfer' }],
    mdas: [{ id: 'm1', name: 'Ministry of Health' }, { id: 'm2', name: 'Ministry of Education' }],
    lgas: ['dutse'],
    wards: [],
    years: [2026],
  },
  metrics: {
    registry: { beneficiaries: { total: 8_420, by_status: { active: 8_000 }, by_source: { excel: 8_420 }, by_lga: { dutse: 8_420 } }, households: { total: 2_000, by_lga: {} } },
    programmes: { total: 6, active: 4 },
    duplicates: null,
    benefits: {
      disbursed: { benefit_count: 12, total_value: 50_000_000, total_quantity: '0' },
      budget: { allocated: 100_000_000, utilized_value: 50_000_000, utilized_quantity: '0', benefit_count: 12, remaining: 50_000_000, utilization_rate: 0.5 },
      by_type: [],
    },
    referrals: null,
    grievances: null,
    coverage: [],
    mda_delivery: [
      { mda_id: 'm1', mda: 'Ministry of Health', delivered_value: 40_000_000, deliveries: 9, reached: 900, allocated: 50_000_000, activities_total: 4, activities_active: 3 },
      { mda_id: 'm2', mda: 'Ministry of Education', delivered_value: 0, deliveries: 0, reached: 0, allocated: 20_000_000, activities_total: 2, activities_active: 1 },
    ],
    ...over,
  },
})

function renderBoard(canExport = true) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>
        <AdminReportsDashboard canExport={canExport} />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

/**
 * The administration console's reporting board.
 *
 * It composes the SAME cards the MDA board does, so those are covered once, over
 * there. What is tested here is what makes this board the admin's: state-wide
 * framing, the MDA filter, and the cross-agency comparison no other console gets.
 */
describe('AdminReportsDashboard', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    get.mockResolvedValue(payload())
  })

  it('frames the board as the whole state, not one agency', async () => {
    renderBoard()

    expect(await screen.findByText('Across the whole state')).toBeInTheDocument()
    expect(screen.getByText(/8,420 beneficiaries in view/)).toBeInTheDocument()
    expect(screen.getByText(/each person counted once however many MDAs or programmes/i)).toBeInTheDocument()
  })

  it('offers the MDA filter, which an MDA board has no use for', async () => {
    const user = userEvent.setup()
    renderBoard()
    await screen.findByText('Across the whole state')

    const mda = screen.getByLabelText('MDA')
    expect(within(mda).getByRole('option', { name: 'Ministry of Health' })).toBeInTheDocument()

    await user.selectOptions(mda, 'm1')
    await waitFor(() => expect(get).toHaveBeenCalledWith(expect.objectContaining({ mda_id: 'm1' })))
  })

  it('compares agencies, with the budget context that keeps a long bar honest', async () => {
    renderBoard()
    await screen.findByText('Across the whole state')

    const card = screen.getByRole('region', { name: 'Delivery by MDA' })
    expect(within(card).getByText('Ministry of Health')).toBeInTheDocument()
    expect(within(card).getByText('₦400,000.00')).toBeInTheDocument()
    expect(card).toHaveTextContent(/80% of its ₦500K budget/)

    // An agency that delivered nothing keeps its row — that is the finding.
    expect(within(card).getByText('Ministry of Education')).toBeInTheDocument()
    expect(card).toHaveTextContent(/0% of its ₦200K budget/)

    // Per-MDA reach double-counts a shared person, and the card says so.
    expect(card).toHaveTextContent(/add up to more than the state total/i)
  })

  it('offers the comparison as a table too', async () => {
    const user = userEvent.setup()
    renderBoard()
    await screen.findByText('Across the whole state')

    await user.click(screen.getByRole('button', { name: /show delivery by mda as a table/i }))

    const table = screen.getByRole('table', { name: /delivery by mda/i })
    expect(within(table).getByRole('columnheader', { name: 'Budget used' })).toBeInTheDocument()
    expect(within(table).getByText('3 of 4')).toBeInTheDocument()
  })

  it('hides the comparison when the payload carries none', async () => {
    get.mockResolvedValue(payload({ mda_delivery: null }))
    renderBoard()
    await screen.findByText('Across the whole state')

    expect(screen.queryByRole('region', { name: 'Delivery by MDA' })).toBeNull()
  })

  it('exports the board as a PDF, and only with the permission', async () => {
    const user = userEvent.setup()
    const { rerender } = renderBoard()
    await screen.findByText('Across the whole state')

    await user.click(screen.getByRole('button', { name: /export pdf/i }))
    // 'board' is what makes this the page-with-charts PDF and not the executive
    // suite's sectioned export off the same endpoint.
    expect(dashboardApi.export).toHaveBeenCalledWith('pdf', undefined, 'state-dashboard.pdf', 'board')

    const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
    rerender(
      <QueryClientProvider client={qc}>
        <MemoryRouter>
          <AdminReportsDashboard canExport={false} />
        </MemoryRouter>
      </QueryClientProvider>,
    )
    await waitFor(() => expect(screen.queryByRole('button', { name: /export pdf/i })).toBeNull())
  })
})
