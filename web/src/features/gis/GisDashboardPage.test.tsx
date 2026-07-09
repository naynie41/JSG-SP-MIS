import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import { GisDashboardPage } from './GisDashboardPage'
import { gisApi } from './api'
import type { GisCoverageResponse } from './types'

// Leaflet needs a real DOM/canvas — stub the map so the page logic is testable.
vi.mock('./CoverageMap', () => ({
  CoverageMap: () => <div data-testid="coverage-map" />,
}))
vi.mock('./api', () => ({ gisApi: { coverage: vi.fn() } }))

const authState = { canView: true }
vi.mock('@/lib/auth/AuthProvider', () => ({
  useAuth: () => ({ hasPermission: () => authState.canView }),
}))

const coverage = gisApi.coverage as Mock

const rows = [
  { key: 'dutse', name: 'Dutse', beneficiary_count: 5, benefit_count: 2, benefit_value: 100_000 },
  { key: 'hadejia', name: 'Hadejia', beneficiary_count: 1, benefit_count: 0, benefit_value: 0 },
]

const choropleth: GisCoverageResponse = {
  level: 'lga', scope: { kind: 'mda', label: 'MDA A' }, mode: 'choropleth', rows,
  feature_collection: { type: 'FeatureCollection', features: [] },
}

const tableOnly: GisCoverageResponse = {
  level: 'lga', scope: { kind: 'mda', label: 'MDA A' }, mode: 'table', rows, feature_collection: null,
}

function renderPage(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(
    <QueryClientProvider client={qc}>
      <MemoryRouter>{ui}</MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('GisDashboardPage', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    authState.canView = true
  })

  it('renders the choropleth map + ranked coverage when boundaries are loaded', async () => {
    coverage.mockResolvedValue(choropleth)

    renderPage(<GisDashboardPage />)

    expect(await screen.findByTestId('coverage-map')).toBeInTheDocument()
    expect(screen.getByText('Dutse')).toBeInTheDocument()
    expect(screen.getByText('5')).toBeInTheDocument()
    expect(screen.queryByText(/isn.t loaded yet/i)).toBeNull()
  })

  it('degrades to the ranked table when no boundaries are loaded', async () => {
    coverage.mockResolvedValue(tableOnly)

    renderPage(<GisDashboardPage />)

    expect(await screen.findByText(/isn.t loaded yet/i)).toBeInTheDocument() // fallback note
    expect(screen.getByText('Dutse')).toBeInTheDocument()
    expect(screen.queryByTestId('coverage-map')).toBeNull()
  })

  it('switches metric and area level', async () => {
    coverage.mockResolvedValue(choropleth)
    const user = userEvent.setup()

    renderPage(<GisDashboardPage />)
    await screen.findByTestId('coverage-map')
    expect(coverage).toHaveBeenCalledWith('lga')

    // Metric → Benefit value shows Naira.
    await user.click(screen.getByRole('button', { name: 'Benefit value' }))
    expect(await screen.findByText('₦1,000.00')).toBeInTheDocument()

    // Level → Ward refetches for the ward level.
    await user.click(screen.getByRole('button', { name: 'Ward' }))
    await waitFor(() => expect(coverage).toHaveBeenCalledWith('ward'))
  })

  it('blocks users without dashboard permission', () => {
    authState.canView = false

    renderPage(<GisDashboardPage />)

    expect(screen.getByText(/do not have permission to view coverage maps/i)).toBeInTheDocument()
    expect(coverage).not.toHaveBeenCalled()
  })
})
