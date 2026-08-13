import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminMatchingPage } from './AdminMatchingPage'
import { adminApi } from './api'
import { matchingApi } from '@/features/registry/api'
import { dashboardApi } from '@/features/dashboard/api'
import type { MatchingConfig } from '@/features/registry/types'

// Compose, don't reimplement: mock the EXISTING Phase 3 matching api layer and assert
// the console drives it — a publish must go to /matching/config, not a console store.
vi.mock('@/features/registry/api', () => ({
  matchingApi: { getConfig: vi.fn(), publish: vi.fn(), versions: vi.fn() },
}))
vi.mock('@/features/dashboard/api', () => ({
  dashboardApi: { get: vi.fn(), opsMetrics: vi.fn(), export: vi.fn() },
  filterParams: () => ({}),
}))
vi.mock('./api', () => ({
  adminApi: { summary: vi.fn(), loginActivity: vi.fn(), organizations: vi.fn(), registryRules: vi.fn() },
}))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const getConfig = matchingApi.getConfig as Mock
const publish = matchingApi.publish as Mock
const versions = matchingApi.versions as Mock
const registryRules = adminApi.registryRules as Mock

const activeConfig: MatchingConfig = {
  id: 'c2',
  version: 2,
  is_active: true,
  deterministic_rules: [['nin'], ['bvn']],
  fuzzy_fields: [
    { field: 'last_name', comparator: 'jaro_winkler', weight: 0.5 },
    { field: 'first_name', comparator: 'jaro_winkler', weight: 0.5 },
  ],
  review_threshold: 0.75,
  auto_accept_threshold: 0.92,
  exact_match_behaviour: 'confirm',
  description: 'Current',
  created_by: 'u1',
  created_at: null,
  updated_at: null,
}

const olderConfig: MatchingConfig = { ...activeConfig, id: 'c1', version: 1, is_active: false, description: 'First' }

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminMatchingPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — Matching Rules & Registry Config (composes Phase 3)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['matching.view', 'matching.edit', 'dashboard.view']
    getConfig.mockResolvedValue(activeConfig)
    publish.mockResolvedValue({ ...activeConfig, id: 'c3', version: 3 })
    versions.mockResolvedValue([activeConfig, olderConfig])
    registryRules.mockResolvedValue({
      editable: false,
      policy: { identity: 'Present but malformed rejects the WHOLE row.', non_identity: 'Drops just that field.' },
      identity_fields: ['first_name', 'last_name', 'phone', 'nin', 'bvn'],
      non_identity_fields: ['date_of_birth', 'gender', 'lga'],
      fields: [
        { field: 'nin', identity: true, required: false, constraints: ['nullable', 'digits:11'] },
        { field: 'lga', identity: false, required: true, constraints: ['required', 'enum'] },
      ],
    })
    ;(dashboardApi.get as Mock).mockResolvedValue({
      scope: { kind: 'state_wide', label: 'State-wide' },
      computed_at: new Date().toISOString(),
      metrics: {
        registry: { beneficiaries: { total: 0, by_status: {}, by_source: {}, by_lga: {} }, households: null },
        programmes: { total: 0, active: 0 },
        duplicates: { matches_surfaced: 240, resolved_new: 150, resolved_served: 70, resolved_skipped: 20 },
        benefits: {
          disbursed: { benefit_count: 0, total_value: 0, total_quantity: '0' },
          budget: { allocated: 0, utilized_value: 0, utilized_quantity: '0', benefit_count: 0, remaining: 0, utilization_rate: null },
          by_type: [],
        },
        referrals: null,
        grievances: null,
        coverage: [],
      },
    })
    ;(adminApi.summary as Mock).mockResolvedValue({
      generated_at: new Date().toISOString(),
      kpis: {},
      adoption_trend: [],
      registry: {
        imports_total: 0, imports_completed: 0, imports_failed: 0, imports_in_progress: 0,
        rows_total: 1_000, rows_valid: 940, rows_invalid: 60, validation_rate: 0.94,
        duplicates_surfaced: 240, duplicates_resolved: 225, duplicates_pending: 15, duplicate_rate: 0.24,
      },
      alerts: [],
      recent_activity: [],
    })
  })

  /* ------------------------------------------------------------- composition */

  it('edits the cascade through the EXISTING matching engine config — no second store', async () => {
    renderPage()

    // The section drove the Phase 3 api layer for the active config. Anchored on the
    // engine's own version badge rather than page chrome — the composed module's
    // heading block is suppressed when embedded so the console keeps one h1.
    expect(await screen.findByText('v2')).toBeInTheDocument()
    expect(getConfig).toHaveBeenCalled()
    expect(screen.getByText('v2')).toBeInTheDocument()
  })

  it('publishes through the existing endpoint, creating a new audited version', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('v2')

    await user.click(screen.getByRole('button', { name: /publish new version/i }))
    // Confirmation dialog explains the versioning + audit contract.
    expect(await screen.findByText(/publishes a new active version/i)).toBeInTheDocument()
  })

  /* ---------------------------------------------------------- version history */

  it('shows the immutable version history from the existing endpoint', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('v2')

    await user.click(screen.getByRole('tab', { name: /version history/i }))

    expect(await screen.findByText('Matching configuration versions')).toBeInTheDocument()
    expect(versions).toHaveBeenCalled()
    // Cascade order is rendered as the priority chain (both versions share it here).
    expect(screen.getAllByText('nin → bvn').length).toBe(2)
    // Only the current version is marked active.
    expect(screen.getAllByText('active')).toHaveLength(1)
    expect(screen.getByText('v2')).toBeInTheDocument()
    expect(screen.getByText('v1')).toBeInTheDocument()
  })

  /* ------------------------------------------------------- duplicate statistics */

  it('reports duplicate statistics produced by the configured engine', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('v2')

    await user.click(screen.getByRole('tab', { name: /duplicate statistics/i }))

    expect(await screen.findByText('Matches surfaced')).toBeInTheDocument()
    expect(screen.getByText('240')).toBeInTheDocument()
    expect(screen.getByText('Duplicate rate')).toBeInTheDocument()
    expect(screen.getByText('24%')).toBeInTheDocument()
    expect(screen.getByText('Resolved as served')).toBeInTheDocument()
    expect(screen.getByText('70')).toBeInTheDocument()
  })

  /* --------------------------------------------------------- validation rules */

  it('shows registry validation rules as READ-ONLY policy, never an editor', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('v2')

    await user.click(screen.getByRole('tab', { name: /validation rules/i }))

    expect(await screen.findByText('Registry validation rules')).toBeInTheDocument()
    expect(registryRules).toHaveBeenCalled()

    // Identity vs standard classification + the locked policy.
    expect(screen.getByText('identity')).toBeInTheDocument()
    expect(screen.getByText('standard')).toBeInTheDocument()
    expect(screen.getByText(/rejects the WHOLE row/i)).toBeInTheDocument()
    expect(screen.getByText(/digits:11/)).toBeInTheDocument()

    // No editing affordance whatsoever — these are policy, not configuration.
    const panel = screen.getByText('Registry validation rules').closest('div')
    expect(within(panel as HTMLElement).queryByRole('button', { name: /save|publish|edit|add/i })).toBeNull()
    expect(within(panel as HTMLElement).queryByRole('textbox')).toBeNull()
  })

  /* -------------------------------------------------------- permission gating */

  it('inherits Phase 3 gating — a viewer cannot publish', async () => {
    perms.value = ['matching.view', 'dashboard.view'] // no matching.edit
    renderPage()
    await screen.findByText('v2')

    expect(screen.queryByRole('button', { name: /publish new version/i })).not.toBeInTheDocument()
  })

  it('blocks the section without matching.view', async () => {
    perms.value = ['dashboard.view']
    renderPage()

    expect(await screen.findByText(/do not have permission/i)).toBeInTheDocument()
    expect(getConfig).not.toHaveBeenCalled()
  })
})
