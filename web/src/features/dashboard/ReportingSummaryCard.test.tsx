import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ReportingSummaryCard } from './ReportingSummaryCard'
import { summariseReporting } from './reportingSummary'
import { dashboardApi } from './api'
import type { DashboardResponse } from './types'

vi.mock('./api', async (importOriginal) => ({
  ...(await importOriginal<typeof import('./api')>()),
  dashboardApi: { get: vi.fn() },
}))

const roleKey = vi.hoisted(() => ({ current: 'mda_admin' }))

vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    hasPermission: () => true,
    user: { role: { key: roleKey.current } },
  }),
}))

const get = dashboardApi.get as Mock

function response(overrides: Partial<DashboardResponse> = {}): DashboardResponse {
  return {
    scope: { kind: 'mda', label: 'Ministry of Health', tier: 'operational' },
    computed_at: '2026-08-20T09:30:00+01:00',
    min_cell_size: null,
    metrics: {
      registry: {
        beneficiaries: { total: 15402, by_status: {}, by_source: {}, by_lga: {} },
        households: { total: 4120, by_lga: {} },
      },
      programmes: { total: 12, active: 9, activities_total: 30, activities_active: 23 },
      duplicates: { matches_surfaced: 47, resolved_new: 10, resolved_served: 30, resolved_skipped: 7 },
      benefits: {
        disbursed: { benefit_count: 8800, total_value: 0, total_quantity: '0' },
        budget: { allocated: 0, utilized_value: 0 },
        by_type: [],
      },
      referrals: {},
    } as unknown as DashboardResponse['metrics'],
    ...overrides,
  }
}

function renderCard() {
  const client = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={client}>
      <MemoryRouter>
        <ReportingSummaryCard />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

/**
 * The Overview's reporting summary (FR-DSH-01, FR-RPT-03).
 *
 * The property that matters is that the summary and the full dashboard cannot disagree.
 * These tests pin the mechanism that guarantees it — one source, one derivation — rather
 * than comparing two rendered pages, which would pass for as long as someone remembered
 * to update both fixtures.
 */
describe('ReportingSummaryCard', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    roleKey.current = 'mda_admin'
    get.mockResolvedValue(response())
  })

  it('shows the headline figures from the dashboard aggregation', async () => {
    renderCard()

    expect(await screen.findByText('15,402')).toBeInTheDocument()
    expect(screen.getByText('4,120')).toBeInTheDocument()
    expect(screen.getByText('23')).toBeInTheDocument()
    expect(screen.getByText('8,800')).toBeInTheDocument()
    expect(screen.getByText('47')).toBeInTheDocument()
  })

  it('renders exactly what the shared derivation produces', async () => {
    // The anti-drift assertion. The card does not compute its own figures: whatever
    // `summariseReporting` returns for a payload is what appears, so the full dashboard
    // reading the same payload through the same helper cannot show a different number.
    const payload = response()
    get.mockResolvedValue(payload)
    renderCard()

    await screen.findByText('15,402')

    for (const tile of summariseReporting(payload).tiles) {
      expect(screen.getByText(tile.label)).toBeInTheDocument()
      expect(screen.getByText((tile.value ?? 0).toLocaleString())).toBeInTheDocument()
    }
  })

  it('reads from the same query the full dashboard uses', async () => {
    // One request, one cache entry: the summary and the detail page are literally the
    // same data, not two fetches that happen to agree today.
    renderCard()
    await screen.findByText('15,402')

    expect(get).toHaveBeenCalledTimes(1)
    expect(get).toHaveBeenCalledWith(undefined)
  })

  it('states when the figures were computed', async () => {
    get.mockResolvedValue(response({ computed_at: new Date().toISOString() }))
    renderCard()

    expect(await screen.findByText(/Last updated/)).toBeInTheDocument()
    expect(screen.queryByText(/out of date/i)).not.toBeInTheDocument()
  })

  it('warns rather than whispers when the snapshot has gone stale', async () => {
    // Snapshots recompute every fifteen minutes on the scheduler. Days old means the
    // scheduler is not running, and a weeks-old figure shown as current is the one way
    // this card can mislead on its own (PRODUCT.md principle 5).
    const old = new Date(Date.now() - 26 * 36e5).toISOString()
    get.mockResolvedValue(response({ computed_at: old }))
    renderCard()

    expect(await screen.findByText(/have not\s+refreshed since/i)).toBeInTheDocument()
    expect(screen.getByText(/Treat them as out of date/i)).toBeInTheDocument()
    expect(screen.queryByText(/^Last updated/)).not.toBeInTheDocument()
  })

  it('gives the headline figure more weight than the ones supporting it', async () => {
    // Net-unique beneficiaries is THE headline (CLAUDE.md §11). Six numbers at one size
    // is a list, and a list makes the reader do the ranking the page should have done.
    const payload = response()
    get.mockResolvedValue(payload)
    renderCard()

    await screen.findByText('15,402')
    const [headline] = summariseReporting(payload).tiles
    const figure = screen.getByText(headline.label).closest('div')!

    expect(figure.className).toMatch(/headline/)
    expect(screen.getByText('4,120').closest('div')!.className).not.toMatch(/headline/)
  })

  /* ------------------------------------------------------------- the deep link */

  it('expands to the MDA reports dashboard for an MDA user', async () => {
    renderCard()

    const link = await screen.findByRole('link', { name: /open full dashboard/i })
    expect(link).toHaveAttribute('href', '/mda/reports')
  })

  it('expands to the admin reports dashboard for a system administrator', async () => {
    roleKey.current = 'system_administrator'
    renderCard()

    const link = await screen.findByRole('link', { name: /open full dashboard/i })
    expect(link).toHaveAttribute('href', '/admin/reports')
  })

  it('offers no link where the console has no reports section', async () => {
    // Executive and Partner consoles are dashboard suites in their own right; a link to
    // a Reports route they do not have would be a 404 dressed as an affordance.
    roleKey.current = 'executive'
    get.mockResolvedValue(
      response({ scope: { kind: 'state_wide', label: 'State-wide', tier: 'statewide' } }),
    )
    renderCard()

    await screen.findByText('15,402')
    expect(screen.queryByRole('link', { name: /open full dashboard/i })).not.toBeInTheDocument()
  })

  /* --------------------------------------------------------- scope + cell guard */

  it('names the scope it is reporting on', async () => {
    renderCard()

    expect(await screen.findByText('Ministry of Health')).toBeInTheDocument()
  })

  it('withholds a small figure on an aggregate tier', async () => {
    roleKey.current = 'executive'
    get.mockResolvedValue(
      response({
        scope: { kind: 'state_wide', label: 'State-wide', tier: 'statewide' },
        min_cell_size: 5,
        metrics: {
          ...response().metrics,
          duplicates: { matches_surfaced: 2, resolved_new: 0, resolved_served: 0, resolved_skipped: 0 },
        },
      }),
    )
    renderCard()

    const tile = (await screen.findByText('Duplicates surfaced')).closest('div')!
    expect(within(tile).getByText('< 5')).toBeInTheDocument()
    expect(screen.getByText(/1 figure withheld/i)).toBeInTheDocument()
  })

  it('does not withhold anything for an MDA reading its own data', async () => {
    // The guard is off by construction here: the server sends a null threshold for the
    // operational tier, because an MDA already holds the records it is counting.
    get.mockResolvedValue(
      response({
        min_cell_size: null,
        metrics: {
          ...response().metrics,
          duplicates: { matches_surfaced: 2, resolved_new: 0, resolved_served: 0, resolved_skipped: 0 },
        },
      }),
    )
    renderCard()

    const tile = (await screen.findByText('Duplicates surfaced')).closest('div')!
    expect(within(tile).getByText('2')).toBeInTheDocument()
    expect(screen.queryByText(/withheld/i)).not.toBeInTheDocument()
  })

  it('never withholds a zero', async () => {
    // "Nobody" discloses nothing; hiding it would read as a withheld population rather
    // than an empty one.
    roleKey.current = 'executive'
    get.mockResolvedValue(
      response({
        scope: { kind: 'state_wide', label: 'State-wide', tier: 'statewide' },
        min_cell_size: 5,
        metrics: {
          ...response().metrics,
          duplicates: { matches_surfaced: 0, resolved_new: 0, resolved_served: 0, resolved_skipped: 0 },
        },
      }),
    )
    renderCard()

    const tile = (await screen.findByText('Duplicates surfaced')).closest('div')!
    expect(within(tile).getByText('0')).toBeInTheDocument()
  })
})
