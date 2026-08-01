import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { CoverageMapTab } from './CoverageMapTab'
import { gisApi } from '@/features/gis/api'
import { clearMapLayers, registerMapLayer } from '@/features/gis/mapLayers'
import { EMPTY_FILTER } from './types'
import type { GisCoverageResponse } from '@/features/gis/types'

// Leaflet needs a real DOM/canvas — stub the map so the tab logic is testable.
vi.mock('./BandChoroplethMap', () => ({ BandChoroplethMap: () => <div data-testid="band-map" /> }))
vi.mock('@/features/gis/api', () => ({ gisApi: { coverage: vi.fn() } }))
vi.mock('@/lib/auth/AuthProvider', () => ({ useAuth: () => ({ hasPermission: () => true }) }))

const coverage = gisApi.coverage as Mock

const rows = [
  { key: 'dutse', name: 'Dutse', beneficiary_count: 1500, benefit_count: 40, benefit_value: 3_000_000, funding_allocated: 5_000_000, households: 400, served: 1200, active_programmes: 3, active_activities: 5, mdas: ['Ministry of Humanitarian Affairs', 'Ministry of Health'], band: 'green' as const },
  { key: 'hadejia', name: 'Hadejia', beneficiary_count: 300, benefit_count: 10, benefit_value: 500_000, funding_allocated: 1_000_000, households: 90, served: 250, active_programmes: 2, active_activities: 2, mdas: ['Ministry of Education'], band: 'yellow' as const },
  { key: 'gumel', name: 'Gumel', beneficiary_count: 50, benefit_count: 2, benefit_value: 60_000, funding_allocated: 150_000, households: 12, served: 40, active_programmes: 1, active_activities: 1, mdas: ['Ministry of Youth'], band: 'red' as const },
  { key: 'kano', name: 'Kano', beneficiary_count: 0, benefit_count: 0, benefit_value: 0, funding_allocated: 0, households: 0, served: 0, active_programmes: 0, active_activities: 0, mdas: [], band: 'grey' as const },
]
const bands = { green_min: 1000, yellow_min: 250 }

const choropleth: GisCoverageResponse = {
  level: 'lga', scope: { kind: 'state_wide', label: 'State-wide' }, mode: 'choropleth', bands, rows,
  feature_collection: { type: 'FeatureCollection', features: [] },
}
const tableOnly: GisCoverageResponse = {
  level: 'lga', scope: { kind: 'state_wide', label: 'State-wide' }, mode: 'table', bands, rows, feature_collection: null,
}

function renderTab(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(<QueryClientProvider client={qc}>{ui}</QueryClientProvider>)
}

describe('CoverageMapTab', () => {
  beforeEach(() => vi.clearAllMocks())
  afterEach(() => clearMapLayers())

  it('renders the ABSOLUTE-count band legend with configured thresholds', async () => {
    coverage.mockResolvedValue(choropleth)
    const { container } = renderTab(<CoverageMapTab />)

    expect(await screen.findByTestId('band-map')).toBeInTheDocument()
    // Legend labels + thresholds (bands are absolute, never a population %).
    expect(screen.getByText('High')).toBeInTheDocument()
    expect(screen.getByText('Moderate')).toBeInTheDocument()
    expect(screen.getByText('Low')).toBeInTheDocument()
    expect(screen.getByText('No coverage')).toBeInTheDocument()
    expect(screen.getByText(/≥ 1,000 beneficiaries/)).toBeInTheDocument()

    // Each area is banded (green/yellow/red/grey) in the ranked list.
    expect(container.querySelector('[data-band="green"]')).toBeTruthy()
    expect(container.querySelector('[data-band="grey"]')).toBeTruthy()

    // No overlay layers are registered by default (framework present, none built).
    expect(screen.queryByText('Overlays')).toBeNull()
  })

  it('shows click-through detail with only the fields we hold', async () => {
    coverage.mockResolvedValue(choropleth)
    const user = userEvent.setup()
    renderTab(<CoverageMapTab />)

    await screen.findByTestId('band-map')
    expect(screen.getByText(/Select an LGA to see its detail/i)).toBeInTheDocument()

    await user.click(screen.getByRole('button', { name: /Dutse/i }))

    expect(screen.getByText('Registered households')).toBeInTheDocument()
    expect(screen.getByText('400')).toBeInTheDocument()
    expect(screen.getByText('Beneficiaries served')).toBeInTheDocument()
    expect(screen.getByText('1,200')).toBeInTheDocument()
    expect(screen.getByText('Active activities')).toBeInTheDocument()
    expect(screen.getByText('5')).toBeInTheDocument()
    expect(screen.getByText('Budget spent')).toBeInTheDocument()
    expect(screen.getByText('₦30,000.00')).toBeInTheDocument()
    expect(screen.getByText('Ministry of Humanitarian Affairs, Ministry of Health')).toBeInTheDocument()
  })

  it('shows NO population / poverty / vulnerability / coverage-% fields', async () => {
    coverage.mockResolvedValue(choropleth)
    const user = userEvent.setup()
    renderTab(<CoverageMapTab />)

    await screen.findByTestId('band-map')
    await user.click(screen.getByRole('button', { name: /Dutse/i }))

    expect(screen.queryByText(/population|povert|vulnerabilit|coverage %|% of population|deprivation|index/i)).toBeNull()
  })

  it('degrades to a ranked table when boundaries are not loaded', async () => {
    coverage.mockResolvedValue(tableOnly)
    renderTab(<CoverageMapTab />)

    expect(await screen.findByText(/isn.t loaded yet/i)).toBeInTheDocument() // fallback note
    expect(screen.queryByTestId('band-map')).toBeNull()
    // The ranked list still works as the selector.
    expect(screen.getByRole('button', { name: /Dutse/i })).toBeInTheDocument()
  })

  it('renders a toggle for each registered overlay layer (pluggable framework)', async () => {
    // An external layer registered via the framework surfaces as a map overlay toggle.
    registerMapLayer({ id: 'schools', label: 'Schools', load: async () => ({ type: 'FeatureCollection', features: [] }) })
    coverage.mockResolvedValue(choropleth)
    const user = userEvent.setup()
    renderTab(<CoverageMapTab />)

    await screen.findByTestId('band-map')
    expect(screen.getByText('Overlays')).toBeInTheDocument()
    const toggle = screen.getByRole('checkbox', { name: 'Schools' })
    expect(toggle).not.toBeChecked()
    await user.click(toggle)
    expect(toggle).toBeChecked()
  })

  it('is read-only — no mutating controls', async () => {
    coverage.mockResolvedValue(choropleth)
    renderTab(<CoverageMapTab />)
    await screen.findByTestId('band-map')

    expect(screen.queryByRole('textbox')).toBeNull()
    expect(screen.queryByRole('button', { name: /edit|create|save|delete|add|new|remove|update/i })).toBeNull()
  })

  it('passes the active cross-cutting filter to the coverage query', async () => {
    coverage.mockResolvedValue(choropleth)
    renderTab(<CoverageMapTab filter={{ ...EMPTY_FILTER, lga: 'dutse', year: 2026 }} />)

    await screen.findByTestId('band-map')
    expect(coverage).toHaveBeenLastCalledWith('lga', { lga: 'dutse', year: 2026 })
  })
})
