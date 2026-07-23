import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { MdaDashboardPage } from './MdaDashboardPage'
import { dashboardApi } from './api'
import type { DashboardResponse } from './types'

vi.mock('./api', () => ({ dashboardApi: { get: vi.fn() } }))

const authState = {
  roleKey: 'mda_officer',
  mda: { name: 'MDA A' } as { name: string } | null,
  canView: true,
  can: (_p: string) => true as boolean,
}
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: authState.roleKey }, mda: authState.mda },
    hasPermission: (p: string) => (p === 'dashboard.view' ? authState.canView : authState.can(p)),
  }),
}))

const get = dashboardApi.get as Mock

// An MDA-scoped payload (coordination metrics present).
const mdaPayload: DashboardResponse = {
  scope: { kind: 'mda', label: 'MDA A' },
  computed_at: new Date().toISOString(),
  metrics: {
    registry: { beneficiaries: { total: 300, by_status: { active: 300 }, by_source: {}, by_lga: { dutse: 300 } }, households: { total: 40, by_lga: {} } },
    programmes: { total: 3, active: 2 },
    duplicates: { matches_surfaced: 10, resolved_new: 6, resolved_served: 3, resolved_skipped: 1 },
    benefits: {
      disbursed: { benefit_count: 120, total_value: 12_000_000, total_quantity: '0' },
      budget: { allocated: 30_000_000, utilized_value: 12_000_000, utilized_quantity: '0', benefit_count: 120, remaining: 18_000_000, utilization_rate: 0.4 },
      by_type: [{ key: 'cash', benefit_count: 120, total_value: 12_000_000, total_quantity: '0' }],
    },
    referrals: { total: 8, by_status: {}, completed: 5, completion_rate: 0.625, overdue: 1, avg_completion_days: 4 },
    grievances: { total: 3, by_status: {}, sla_breaches: 1, avg_resolution_days: 2 },
    coverage: [{ lga: 'dutse', beneficiary_count: 280, benefit_count: 120, benefit_value: 12_000_000 }],
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

describe('MdaDashboardPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    authState.roleKey = 'mda_officer'
    authState.mda = { name: 'MDA A' }
    authState.canView = true
    authState.can = () => true
  })

  it('renders the MDA-scoped metrics with the MDA name in the title', async () => {
    get.mockResolvedValue(mdaPayload)

    renderPage(<MdaDashboardPage />)

    expect(await screen.findByRole('heading', { name: 'MDA A dashboard' })).toBeInTheDocument()
    expect(screen.getByText('300')).toBeInTheDocument()   // beneficiaries in this MDA
    expect(screen.getByText('40%')).toBeInTheDocument()   // budget utilisation
    // Coordination families apply to an MDA scope.
    expect(screen.getByText('Referrals')).toBeInTheDocument()
    expect(screen.getByText('Grievances')).toBeInTheDocument()
    // Permission-aware quick actions turn the dashboard into a launchpad — the
    // create-programme/activity actions are clearly surfaced.
    expect(screen.getByRole('button', { name: 'New programme' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'New activity' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Record benefit' })).toBeInTheDocument()
  })

  it('hides the quick actions a user has no permission for', async () => {
    get.mockResolvedValue(mdaPayload)
    // Can view the dashboard, but holds no create permissions.
    authState.can = () => false

    renderPage(<MdaDashboardPage />)

    await screen.findByRole('heading', { name: 'MDA A dashboard' })
    expect(screen.queryByRole('button', { name: 'New programme' })).toBeNull()
  })

  it('blocks users without dashboard permission', () => {
    authState.canView = false

    renderPage(<MdaDashboardPage />)

    expect(screen.getByText(/do not have permission to view a dashboard/i)).toBeInTheDocument()
    expect(get).not.toHaveBeenCalled()
  })

  it('hides MDA-operator actions for an MDA-less oversight user (keeps central catalog action)', async () => {
    get.mockResolvedValue(mdaPayload)
    authState.mda = null // e.g. SP Coordination — holds permissions but no acting MDA

    renderPage(<MdaDashboardPage />)

    await screen.findByRole('heading', { name: 'MDA dashboard' })
    // Central catalog action stays (creating a programme needs no MDA)…
    expect(screen.getByRole('button', { name: 'New programme' })).toBeInTheDocument()
    // …but actions that require an acting MDA are hidden (mirrors the server).
    expect(screen.queryByRole('button', { name: 'New activity' })).toBeNull()
    expect(screen.queryByRole('button', { name: 'Record benefit' })).toBeNull()
    expect(screen.queryByRole('button', { name: 'Import beneficiaries' })).toBeNull()
  })
})
