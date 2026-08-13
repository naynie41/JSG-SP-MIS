import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import { render, screen, waitFor, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ExecutiveLayout } from './ExecutiveLayout'
import {
  ExecutiveCoordinationPage,
  ExecutiveCoveragePage,
  ExecutiveOverviewPage,
  ExecutiveProgrammesPage,
  ExecutiveRegistryPage,
} from './executivePages'
import { dashboardApi } from './api'
import { makeExecutivePayload } from './executiveTestData'

vi.mock('./api', () => ({
  dashboardApi: { get: vi.fn(), export: vi.fn() },
  filterParams: (f?: Record<string, unknown>) => {
    const out: Record<string, unknown> = {}
    if (f) for (const [k, v] of Object.entries(f)) if (v !== null && v !== undefined && v !== '') out[k] = v
    return out
  },
}))

const authState = { roleKey: 'executive', canView: true }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ user: { role: { key: authState.roleKey }, mda: null }, hasPermission: () => authState.canView }),
}))

// Leaflet needs a real DOM/canvas — stub the map so the Coverage page is testable.
vi.mock('./BandChoroplethMap', () => ({ BandChoroplethMap: () => <div data-testid="band-map" /> }))
vi.mock('@/features/gis/api', () => ({
  gisApi: {
    coverage: vi.fn().mockResolvedValue({
      level: 'lga', scope: { kind: 'state_wide', label: 'State-wide' }, mode: 'table',
      bands: { green_min: 1000, yellow_min: 250 }, rows: [], feature_collection: null,
    }),
  },
}))

const get = dashboardApi.get as Mock

function renderAt(path: string) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter initialEntries={[path]}>
        <Routes>
          <Route path="/executive" element={<ExecutiveLayout />}>
            <Route index element={<ExecutiveOverviewPage />} />
            <Route path="programmes" element={<ExecutiveProgrammesPage />} />
            <Route path="registry" element={<ExecutiveRegistryPage />} />
            <Route path="coordination" element={<ExecutiveCoordinationPage />} />
            <Route path="coverage" element={<ExecutiveCoveragePage />} />
          </Route>
        </Routes>
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('ExecutiveLayout (briefing suite shell + routed pages)', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    authState.roleKey = 'executive'
    authState.canView = true
  })

  it('renders the shared hero + net-unique headline and the Overview page via the outlet', async () => {
    get.mockResolvedValue(makeExecutivePayload())
    renderAt('/executive')

    expect(await screen.findByRole('heading', { name: /state of social protection/i })).toBeInTheDocument()
    expect(screen.getByText('Net-unique beneficiaries reached')).toBeInTheDocument()
    // Net-unique (8,420) is the headline — deliberately below the gross delivery count.
    expect(screen.getAllByText('8,420').length).toBeGreaterThan(0)
    expect(screen.getByRole('button', { name: /export/i })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: /refresh/i })).toBeInTheDocument()
    expect(screen.getByLabelText('Year')).toBeInTheDocument() // shared filter bar
  })

  it('renders each inner page from its own route WITHOUT the hero, keeping the shared filter', async () => {
    get.mockResolvedValue(makeExecutivePayload())

    const { unmount } = renderAt('/executive/registry')
    expect(await screen.findByRole('heading', { name: 'Data quality' })).toBeInTheDocument()
    expect(screen.getByText('Verification rate')).toBeInTheDocument()
    expect(screen.queryByRole('heading', { name: /state of social protection/i })).toBeNull()
    expect(screen.getByLabelText('Year')).toBeInTheDocument()
    unmount()

    const { unmount: unmount2 } = renderAt('/executive/coordination')
    expect(await screen.findByRole('heading', { name: 'Agencies' })).toBeInTheDocument()
    expect(screen.getByRole('heading', { name: 'Partner contributions' })).toBeInTheDocument()
    expect(screen.queryByRole('heading', { name: /state of social protection/i })).toBeNull()
    unmount2()

    renderAt('/executive/programmes')
    expect(await screen.findByRole('heading', { name: 'Financials' })).toBeInTheDocument()
    expect(screen.getByRole('heading', { name: 'Comparison' })).toBeInTheDocument()
    expect(screen.queryByRole('heading', { name: /state of social protection/i })).toBeNull()
  })

  it('renders the Coverage Map page on its route', async () => {
    get.mockResolvedValue(makeExecutivePayload())
    renderAt('/executive/coverage')

    // Table-mode fallback (no boundaries in the stub) still renders the map page.
    expect(await screen.findByText(/isn.t loaded yet/i)).toBeInTheDocument()
  })

  it('applies the cross-cutting filter (refetches with params) — shared across pages', async () => {
    get.mockResolvedValue(makeExecutivePayload())
    const user = userEvent.setup()
    renderAt('/executive')
    await screen.findByRole('heading', { name: /state of social protection/i })

    await user.click(screen.getByRole('button', { name: /more filters/i }))
    await user.selectOptions(screen.getByLabelText('LGA'), 'dutse')
    expect(get).toHaveBeenLastCalledWith(expect.objectContaining({ lga: 'dutse' }))
  })

  it('drills into a single programme from the share donut on the Programmes page', async () => {
    get.mockResolvedValue(makeExecutivePayload())
    const user = userEvent.setup()
    // The donut moved off the Overview with the rest of the delivery detail; it
    // now sits on the page that owns programmes, where the drill scopes the view.
    renderAt('/executive/programmes')
    const donut = await screen.findByRole('region', { name: 'Beneficiary share by programme' })

    // The donut legend exposes each programme as a drill affordance. Scoped to
    // the donut: the comparison table below offers the same programme names.
    await user.click(within(donut).getByRole('button', { name: 'Cash Transfer' }))

    // Refetched scoped to that programme — and the filter is now in the URL.
    await waitFor(() => expect(get).toHaveBeenLastCalledWith(expect.objectContaining({ programme_id: 'p-a' })))
  })

  it('is strictly read-only — no mutating controls', async () => {
    get.mockResolvedValue(makeExecutivePayload())
    renderAt('/executive')
    await screen.findByRole('heading', { name: /state of social protection/i })

    expect(screen.queryByRole('textbox')).toBeNull()
    expect(screen.queryByRole('button', { name: /edit|create|save|delete|add|new|remove|update|submit/i })).toBeNull()
  })

  it('blocks non-executive roles and never fetches', () => {
    authState.roleKey = 'mda_officer'
    renderAt('/executive')

    expect(screen.getByText(/available to Executive users only/i)).toBeInTheDocument()
    expect(get).not.toHaveBeenCalled()
  })
})
