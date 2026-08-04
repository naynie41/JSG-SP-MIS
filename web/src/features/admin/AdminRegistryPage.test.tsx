import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { ToastProvider } from '@/components/Toast/ToastProvider'
import { AdminRegistryPage } from './AdminRegistryPage'
import { adminApi } from './api'
import { dashboardApi } from '@/features/dashboard/api'

// Compose, don't reimplement: mock the EXISTING data layers (reporting aggregates +
// Phase 2 imports) and assert the section drives them.
vi.mock('@/features/dashboard/api', () => ({
  dashboardApi: { get: vi.fn(), opsMetrics: vi.fn(), export: vi.fn() },
  filterParams: () => ({}),
}))
vi.mock('./api', () => ({ adminApi: { summary: vi.fn(), loginActivity: vi.fn(), organizations: vi.fn() } }))

const perms = { value: [] as string[] }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({
    user: { role: { key: 'system_administrator' }, name: 'Admin' },
    hasPermission: (p: string) => perms.value.includes(p),
  }),
}))

const getDashboard = dashboardApi.get as Mock
const getSummary = adminApi.summary as Mock

const dashboardPayload = {
  scope: { kind: 'state_wide', label: 'State-wide', tier: 'statewide' as const },
  computed_at: new Date().toISOString(),
  metrics: {
    registry: {
      beneficiaries: {
        total: 15_402,
        by_status: { active: 15_000, flagged: 380, suspended: 22 },
        by_source: { import: 12_000, api: 2_402, manual: 1_000 },
        by_lga: { dutse: 8_000, hadejia: 7_402 },
      },
      households: { total: 4_120, by_lga: { dutse: 2_500 } },
    },
    programmes: { total: 8, active: 8 },
    duplicates: { matches_surfaced: 240, resolved_new: 150, resolved_served: 70, resolved_skipped: 20 },
    benefits: {
      disbursed: { benefit_count: 0, total_value: 0, total_quantity: '0' },
      budget: { allocated: 0, utilized_value: 0, utilized_quantity: '0', benefit_count: 0, remaining: 0, utilization_rate: null },
      by_type: [],
    },
    referrals: null,
    grievances: null,
    coverage: [],
    population: {
      total_households: 4_120, total_individuals: 15_402, net_unique_served: 9_000,
      new_registrations_period: 320, lgas_covered: 12, wards_covered: 44, period_days: 30,
    },
    household_size: { total_households: 4_120, households_with_members: 4_000, average_size: 4.25, bands: {} },
    registry_quality: {
      total: 15_402, verified: 15_000, pending: 380, suspended: 22, duplicates_detected: 240,
      nin_completeness: 0.82, phone_completeness: 0.91, data_completeness: 0.88,
    },
  },
}

const summaryPayload = {
  generated_at: new Date().toISOString(),
  kpis: {},
  adoption_trend: [],
  registry: {
    imports_total: 30, imports_completed: 26, imports_failed: 3, imports_in_progress: 1,
    rows_total: 1_000, rows_valid: 940, rows_invalid: 60, validation_rate: 0.94,
    duplicates_surfaced: 240, duplicates_resolved: 225, duplicates_pending: 15,
  },
  alerts: [],
  recent_activity: [],
}

function renderPage() {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <ToastProvider>
        <MemoryRouter>
          <AdminRegistryPage />
        </MemoryRouter>
      </ToastProvider>
    </QueryClientProvider>,
  )
}

describe('Admin console — Registry & Data Quality (read-only oversight)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    perms.value = ['dashboard.view', 'beneficiary.view', 'beneficiary.create']
    getDashboard.mockResolvedValue(dashboardPayload)
    getSummary.mockResolvedValue(summaryPayload)
  })

  /* ------------------------------------------------------------- composition */

  it('reads the EXISTING reporting aggregates — no parallel registry query', async () => {
    renderPage()

    expect(await screen.findByText('Registered individuals')).toBeInTheDocument()
    expect(getDashboard).toHaveBeenCalled()
    expect(getSummary).toHaveBeenCalled()
  })

  /* ---------------------------------------------------------- registry stats */

  it('renders accurate registry statistics and data-source monitoring', async () => {
    renderPage()
    await screen.findByText('Registered individuals')

    expect(screen.getByText('15,402')).toBeInTheDocument()  // individuals
    expect(screen.getByText('4,120')).toBeInTheDocument()   // households
    expect(screen.getByText('4.25')).toBeInTheDocument()    // average household size
    expect(screen.getByText('12')).toBeInTheDocument()      // LGAs covered

    // Data-source monitoring — provenance breakdown from registration_source.
    expect(screen.getByText('By provenance')).toBeInTheDocument()
    expect(screen.getByText('Import')).toBeInTheDocument()
    expect(screen.getByText('Api')).toBeInTheDocument()
    expect(screen.getByText('12,000')).toBeInTheDocument()
  })

  /* ----------------------------------------------------------- data quality */

  it('renders quality indicators and duplicate-detection statistics', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Registered individuals')

    await user.click(screen.getByRole('tab', { name: /data quality/i }))

    // Completeness / coverage, rounded from the aggregate rates.
    expect(await screen.findByText('Data completeness')).toBeInTheDocument()
    expect(screen.getByText('88%')).toBeInTheDocument()
    expect(screen.getByText('NIN coverage')).toBeInTheDocument()
    expect(screen.getByText('82%')).toBeInTheDocument()
    expect(screen.getByText('91%')).toBeInTheDocument()   // phone
    expect(screen.getByText('94%')).toBeInTheDocument()   // row validation rate

    // Phase 3 duplicate-detection outcomes.
    expect(screen.getByText('Matches surfaced')).toBeInTheDocument()
    expect(screen.getByText('240')).toBeInTheDocument()
    expect(screen.getByText('Resolved as served')).toBeInTheDocument()
    expect(screen.getByText('70')).toBeInTheDocument()
    expect(screen.getByText('Duplicates pending')).toBeInTheDocument()
    expect(screen.getByText('15')).toBeInTheDocument()
  })

  /* ------------------------------------------------------- read-only + PII */

  it('is READ-ONLY — import history offers no upload and no mutating controls', async () => {
    const user = userEvent.setup()
    renderPage()
    await screen.findByText('Registered individuals')

    await user.click(screen.getByRole('tab', { name: /import history/i }))

    // The admin holds beneficiary.create, yet the upload panel is suppressed: bulk
    // ingestion belongs to an acting MDA, not the console.
    expect(screen.queryByText('Upload a file')).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /upload/i })).not.toBeInTheDocument()
    expect(screen.queryByRole('button', { name: /delete|edit|approve|resolve|merge/i })).not.toBeInTheDocument()
  })

  it('shows aggregates only — no beneficiary identifiers anywhere in the section', async () => {
    const user = userEvent.setup()
    const { container } = renderPage()
    await screen.findByText('Registered individuals')

    for (const tab of [/registry/i, /data quality/i]) {
      await user.click(screen.getAllByRole('tab', { name: tab })[0]!)
      // No NIN/BVN/phone/name columns — this section never reads a beneficiary record.
      expect(screen.queryByText(/\bNIN\b(?! coverage)|\bBVN\b|Phone number|Full name|Date of birth/i)).toBeNull()
      // No 11-digit identifier-looking strings rendered.
      expect(container.textContent ?? '').not.toMatch(/\b\d{11}\b/)
    }

    // Completeness is explicitly framed as field presence, not values.
    await user.click(screen.getAllByRole('tab', { name: /data quality/i })[0]!)
    const note = await screen.findByText(/field PRESENCE, never values/i)
    expect(within(note).getByText(/keyed hash/i)).toBeInTheDocument()
  })

  /* -------------------------------------------------------- permission gating */

  it('blocks the section without dashboard.view and never fetches', () => {
    perms.value = []
    renderPage()

    expect(screen.getByText(/do not have permission/i)).toBeInTheDocument()
    expect(getDashboard).not.toHaveBeenCalled()
    expect(getSummary).not.toHaveBeenCalled()
  })
})
