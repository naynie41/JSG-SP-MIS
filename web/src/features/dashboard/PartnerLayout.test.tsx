import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { PartnerLayout } from './PartnerLayout'
import {
  PartnerCoordinationPage,
  PartnerInvestmentPage,
  PartnerOverviewPage,
  PartnerProgrammesPage,
  PartnerRegistryPage,
} from './partnerPages'
import { dashboardApi } from './api'
import { EMPTY_FILTER } from './types'
import type { DashboardResponse, PartnerFunding } from './types'

vi.mock('./api', () => ({
  dashboardApi: { get: vi.fn(), export: vi.fn() },
  filterParams: (f?: Record<string, unknown>) => {
    const out: Record<string, unknown> = {}
    if (f) for (const [k, v] of Object.entries(f)) if (v !== null && v !== undefined && v !== '') out[k] = v
    return out
  },
}))

const authState = { roleKey: 'development_partner', canView: true }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { role: { key: authState.roleKey }, mda: null }, hasPermission: () => authState.canView }),
}))

// The Investment page pulls the GIS coverage endpoint — stub it (table mode, no Leaflet).
vi.mock('@/features/gis/api', () => ({
  gisApi: {
    coverage: vi.fn().mockResolvedValue({
      level: 'lga', scope: { kind: 'partner', label: 'Funded programmes' }, mode: 'table',
      bands: { green_min: 1000, yellow_min: 250 }, rows: [], feature_collection: null,
    }),
  },
}))

const get = dashboardApi.get as Mock

const partnerFunding: PartnerFunding = {
  allocated: 200_000_000,
  delivered_value: 60_000_000,
  remaining: 140_000_000,
  utilization_rate: 0.3,
  funded_programmes: 2,
  funded_activities: 5,
  active_activities: 4,
  implementing_mdas: 3,
  lgas_covered: 4,
  wards_covered: 9,
  net_unique_reached: 800,
  target: 2000,
  reach_vs_target: 0.4,
  cost_per_beneficiary: 75_000,
  reach: { households_reached: 300, women_reached: 420, children_reached: 260 },
  coverage_bands: { basis: 'absolute', thresholds: { green_min: 1000, yellow_min: 250 }, summary: { green: 1, yellow: 1, red: 2 }, areas: [] },
  funding_by_partner: [],
  programme_overlap: { count: 0, cells: [] },
  programmes: [],
  output_indicators: [],
  registry: {
    total_individuals: 800, total_households: 300, verified: 780, pending: 20, suspended: 0,
    duplicate_records: 5, new_registrations: 40, updated_records: 12, period_days: 30,
    demographics: {
      by_gender: { female: 420, male: 380 }, gender_known: 800, female_pct: 0.525,
      age_bands: { children: 200, youth: 300, adults: 250, elderly: 50, unknown: 0 }, by_lga: { dutse: 500, hadejia: 300 },
      household_size: { total_households: 300, households_with_members: 300, average_size: 4.2, bands: { '1': 50, '2-3': 100, '4-6': 120, '7+': 30 } },
    },
    funnel: { registered: 800, enrolled: 700, receiving: 600 },
    quality: { verification_rate: 0.975, duplicate_rate: 0.006, data_completeness: 0.88, nin_linkage: 0.9, missing: { nin: 80, phone: 40, date_of_birth: 10, gender: 0, lga: 0 } },
  },
  coordination: {
    landscape: { funders: 2, government_agencies: 3, implementing_agencies: 2 },
    funding_by_partner: [{ partner_id: 'u-1', name: 'World Bank', is_self: true, allocated: 200_000_000, delivered_value: 60_000_000, net_unique_reached: 800, funded_programmes: 2, shared_programmes: 2 }],
    agencies: [],
    data_sharing: { agencies_integrated: 1, connectors: 2, sources: ['api'], total_runs: 5, succeeded: 4, failed: 1, last_run_at: null, api_registrations: 30 },
  },
}

const partnerPayload: DashboardResponse = {
  scope: { kind: 'partner', label: 'Funded programmes', tier: 'partner' },
  computed_at: new Date().toISOString(),
  live: false,
  filters: { ...EMPTY_FILTER },
  filter_options: { programmes: [{ id: 'p-a', name: 'cash transfer' }], mdas: [{ id: 'm-1', name: 'Ministry X' }], lgas: ['dutse', 'hadejia'], wards: [], years: [2026] },
  metrics: {
    registry: { beneficiaries: { total: 800, by_status: {}, by_source: {}, by_lga: {} }, households: null },
    programmes: { total: 2, active: 2 },
    duplicates: null,
    benefits: {
      disbursed: { benefit_count: 60, total_value: 60_000_000, total_quantity: '0' },
      budget: { allocated: 200_000_000, utilized_value: 60_000_000, utilized_quantity: '0', benefit_count: 60, remaining: 140_000_000, utilization_rate: 0.3 },
      by_type: [],
    },
    referrals: null,
    grievances: null,
    coverage: [],
    partner_funding: partnerFunding,
  },
}

function renderAt(path: string) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter initialEntries={[path]}>
        <Routes>
          <Route path="/partner" element={<PartnerLayout />}>
            <Route index element={<PartnerOverviewPage />} />
            <Route path="programmes" element={<PartnerProgrammesPage />} />
            <Route path="registry" element={<PartnerRegistryPage />} />
            <Route path="coordination" element={<PartnerCoordinationPage />} />
            <Route path="investment" element={<PartnerInvestmentPage />} />
          </Route>
        </Routes>
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('PartnerLayout (funding-partner suite shell + routed pages)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    authState.roleKey = 'development_partner'
    authState.canView = true
  })

  it('renders the shared hero, filter, export/refresh and the Overview page via the outlet', async () => {
    get.mockResolvedValue(partnerPayload)
    renderAt('/partner')

    expect(await screen.findByRole('heading', { name: /at work.*in Jigawa/i })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /export/i })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /refresh/i })).toBeInTheDocument()
    expect(screen.getByLabelText('Year')).toBeInTheDocument() // the shared filter bar
    // Overview page body (from the outlet).
    expect(screen.getByText('Value delivered')).toBeInTheDocument()
    expect(screen.getByText('Net-unique reached')).toBeInTheDocument()
  })

  it('renders a non-index child page WITHOUT the hero, but keeps the shared filter (Registry)', async () => {
    get.mockResolvedValue(partnerPayload)
    renderAt('/partner/registry')

    // Registry page body renders...
    expect(await screen.findByText('Individuals')).toBeInTheDocument()
    expect(screen.getByText(/Targeting funnel/i)).toBeInTheDocument()
    // ...but the money-first hero card is Overview-only.
    expect(screen.queryByRole('heading', { name: /at work.*in Jigawa/i })).toBeNull()
    // The cross-cutting filter bar is still shared across inner pages.
    expect(screen.getByLabelText('Year')).toBeInTheDocument()
  })

  it('applies the cross-cutting filter (refetches with params) — shared across pages', async () => {
    get.mockResolvedValue(partnerPayload)
    const user = userEvent.setup()
    renderAt('/partner')
    await screen.findByRole('heading', { name: /at work.*in Jigawa/i })

    await user.click(screen.getByRole('button', { name: /more filters/i }))
    await user.selectOptions(screen.getByLabelText('LGA'), 'dutse')
    expect(get).toHaveBeenLastCalledWith(expect.objectContaining({ lga: 'dutse' }))
  })

  it('drills from a KPI to the detail page (route navigation)', async () => {
    get.mockResolvedValue(partnerPayload)
    const user = userEvent.setup()
    renderAt('/partner')
    await screen.findByRole('heading', { name: /at work.*in Jigawa/i })

    await user.click(screen.getByRole('button', { name: /Funded programmes/i }))
    // Now on /partner/programmes — the Programmes page (empty in this fixture).
    expect(await screen.findByText(/No funded programmes to report yet/i)).toBeInTheDocument()
  })

  it('blocks non-partner roles and never fetches', () => {
    authState.roleKey = 'mda_admin'
    renderAt('/partner')

    expect(screen.getByText(/available to Development Partner users only/i)).toBeInTheDocument()
    expect(get).not.toHaveBeenCalled()
  })
})
