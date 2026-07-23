import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { AdminDashboardPage } from './AdminDashboardPage'
import { dashboardApi } from './api'
import type { DashboardResponse, OpsMetricsResponse } from './types'

vi.mock('./api', () => ({ dashboardApi: { get: vi.fn(), opsMetrics: vi.fn() } }))

const authState = {
  roleKey: 'system_administrator',
  perms: new Set<string>(['dashboard.view', 'user.view', 'mda.view']),
}
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: authState.roleKey }, mda: null }, // admin is not MDA-bound
    hasPermission: (p: string) => authState.perms.has(p),
  }),
}))

const get = dashboardApi.get as Mock
const opsMetrics = dashboardApi.opsMetrics as Mock

const statePayload: DashboardResponse = {
  scope: { kind: 'state', label: 'State-wide' },
  computed_at: new Date().toISOString(),
  metrics: {
    registry: { beneficiaries: { total: 5000, by_status: {}, by_source: {}, by_lga: {} }, households: { total: 900, by_lga: {} } },
    programmes: { total: 12, active: 9 },
    duplicates: { matches_surfaced: 0, resolved_new: 0, resolved_served: 0, resolved_skipped: 0 },
    benefits: {
      disbursed: { benefit_count: 4000, total_value: 900_000_000, total_quantity: '0' },
      budget: { allocated: 1_000_000_000, utilized_value: 900_000_000, utilized_quantity: '0', benefit_count: 4000, remaining: 100_000_000, utilization_rate: 0.9 },
      by_type: [],
    },
    referrals: null,
    grievances: null,
    coverage: [],
  },
}

const opsPayload: OpsMetricsResponse = {
  time: new Date().toISOString(),
  backups: { last_success_at: new Date().toISOString(), age_hours: 3, rpo_hours: 24 },
  dashboard_snapshots: { last_computed_at: new Date().toISOString(), stale_minutes: 5 },
  volumes: { beneficiaries: 5000, benefits: 4000, audit_entries: 12000 },
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>{ui}</MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('AdminDashboardPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    authState.roleKey = 'system_administrator'
    authState.perms = new Set(['dashboard.view', 'user.view', 'mda.view'])
  })

  it('shows administration framing + admin actions + the ops strip, not MDA operator actions', async () => {
    get.mockResolvedValue(statePayload)
    opsMetrics.mockResolvedValue(opsPayload)

    renderPage(<AdminDashboardPage />)

    expect(await screen.findByRole('heading', { name: 'System administration' })).toBeInTheDocument()
    expect(screen.getByText('5,000')).toBeInTheDocument() // state-wide beneficiaries

    // Admin provisioning actions (permission-gated) are present…
    expect(screen.getByRole('button', { name: 'Manage users' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'MDAs' })).toBeInTheDocument()

    // …but the MDA-operator quick actions never appear on the admin dashboard.
    expect(screen.queryByRole('button', { name: 'Record benefit' })).toBeNull()
    expect(screen.queryByRole('button', { name: 'New activity' })).toBeNull()
    expect(screen.queryByRole('button', { name: 'Import beneficiaries' })).toBeNull()

    // Ops health strip surfaces platform state (the admin's actual job).
    expect(await screen.findByText('Last backup')).toBeInTheDocument()
    expect(screen.getByText('3h ago')).toBeInTheDocument()
  })

  it('hides admin actions the user lacks permission for', async () => {
    get.mockResolvedValue(statePayload)
    opsMetrics.mockResolvedValue(opsPayload)
    authState.perms = new Set(['dashboard.view']) // no user.view / mda.view

    renderPage(<AdminDashboardPage />)

    await screen.findByRole('heading', { name: 'System administration' })
    expect(screen.queryByRole('button', { name: 'Manage users' })).toBeNull()
  })

  it('is available to System Administrators only', () => {
    authState.roleKey = 'mda_admin'

    renderPage(<AdminDashboardPage />)

    expect(screen.getByText(/available to System Administrators only/i)).toBeInTheDocument()
    expect(get).not.toHaveBeenCalled()
  })
})
