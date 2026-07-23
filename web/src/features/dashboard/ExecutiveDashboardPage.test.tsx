import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ExecutiveDashboardPage } from './ExecutiveDashboardPage'
import { dashboardApi } from './api'
import type { DashboardResponse } from './types'

vi.mock('./api', () => ({ dashboardApi: { get: vi.fn() } }))

const mockRole = { key: 'executive' }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { role: mockRole }, hasPermission: () => true }),
}))

const get = dashboardApi.get as Mock

const payload: DashboardResponse = {
  scope: { kind: 'state_wide', label: 'State-wide' },
  computed_at: new Date().toISOString(),
  metrics: {
    registry: {
      beneficiaries: { total: 1234, by_status: { active: 1200, suspended: 34 }, by_source: {}, by_lga: { dutse: 800, hadejia: 434 } },
      households: { total: 300, by_lga: {} },
    },
    programmes: { total: 15, active: 12 },
    duplicates: { matches_surfaced: 50, resolved_new: 30, resolved_served: 15, resolved_skipped: 5 },
    benefits: {
      disbursed: { benefit_count: 320, total_value: 45_000_000, total_quantity: '0' },
      budget: { allocated: 100_000_000, utilized_value: 45_000_000, utilized_quantity: '0', benefit_count: 320, remaining: 55_000_000, utilization_rate: 0.45 },
      by_type: [
        { key: 'cash', benefit_count: 200, total_value: 30_000_000, total_quantity: '0' },
        { key: 'food', benefit_count: 120, total_value: 15_000_000, total_quantity: '0' },
      ],
    },
    referrals: { total: 40, by_status: { created: 10, completed: 20 }, completed: 20, completion_rate: 0.5, overdue: 3, avg_completion_days: 6 },
    grievances: { total: 10, by_status: { open: 4 }, sla_breaches: 2, avg_resolution_days: 4 },
    coverage: [
      { lga: 'dutse', beneficiary_count: 800, benefit_count: 200, benefit_value: 20_000_000 },
      { lga: 'hadejia', beneficiary_count: 434, benefit_count: 120, benefit_value: 15_000_000 },
    ],
  },
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>{ui}</MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('ExecutiveDashboardPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    mockRole.key = 'executive'
  })

  it('renders the state-wide briefing from the aggregation layer', async () => {
    get.mockResolvedValue(payload)

    renderPage(<ExecutiveDashboardPage />)

    // Editorial hero + marquee headline figure (beneficiaries appears in the hero
    // AND the key-figures band).
    expect(await screen.findByRole('heading', { name: /state of social protection in Jigawa/i })).toBeInTheDocument()
    expect(screen.getByText('State-wide briefing')).toBeInTheDocument()
    expect(screen.getAllByText('1,234').length).toBeGreaterThan(0)          // beneficiaries reached
    expect(screen.getAllByText('₦450,000.00').length).toBeGreaterThan(0)    // benefits disbursed

    // Key figures + budget.
    expect(screen.getByText('12')).toBeInTheDocument()                      // active programmes
    expect(screen.getByText('45%')).toBeInTheDocument()                     // budget utilisation

    // Ranked coverage + benefit-type bars.
    expect(screen.getByText('Dutse')).toBeInTheDocument()
    expect(screen.getByText('Hadejia')).toBeInTheDocument()
    expect(screen.getByText('Cash')).toBeInTheDocument()

    // Coordination.
    expect(screen.getByText('Referrals')).toBeInTheDocument()
    expect(screen.getByText('Grievances')).toBeInTheDocument()
    expect(screen.getByText('50%')).toBeInTheDocument()                     // referral completion donut
  })

  it('is strictly read-only — no edit controls', async () => {
    get.mockResolvedValue(payload)

    renderPage(<ExecutiveDashboardPage />)
    await screen.findAllByText('1,234')

    // The only control is Refresh (a read); nothing mutating.
    expect(screen.getByRole('button', { name: /refresh/i })).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /edit|create|save|delete|add|new|remove|update/i })).toBeNull()
    expect(screen.queryByRole('textbox')).toBeNull()
  })

  it('blocks non-executive roles from the executive view', () => {
    mockRole.key = 'mda_officer'

    renderPage(<ExecutiveDashboardPage />)

    expect(screen.getByText(/available to Executive users only/i)).toBeInTheDocument()
    expect(screen.queryByText('1,234')).toBeNull()
    expect(get).not.toHaveBeenCalled() // data is never fetched for a non-executive
  })
})
