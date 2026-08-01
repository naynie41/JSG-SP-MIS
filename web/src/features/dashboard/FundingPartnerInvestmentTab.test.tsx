import { beforeEach, describe, expect, it, vi } from 'vitest'
import type { Mock } from 'vitest'
import type { ReactNode } from 'react'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { FundingPartnerInvestmentTab } from './FundingPartnerInvestmentTab'
import { gisApi } from '@/features/gis/api'
import { EMPTY_FILTER } from './types'
import type { GisCoverageResponse } from '@/features/gis/types'

vi.mock('./BandChoroplethMap', () => ({ BandChoroplethMap: () => <div data-testid="band-map" /> }))
vi.mock('@/features/gis/api', () => ({ gisApi: { coverage: vi.fn() } }))
vi.mock('@/lib/auth/AuthProvider', () => ({ useAuth: () => ({ hasPermission: () => true }) }))

const coverage = gisApi.coverage as Mock

// One LGA per coverage-vs-funding quadrant (funding midpoint = median[50k,100k,4M,5M] = 2.05M).
const rows = [
  { key: 'dutse', name: 'Dutse', beneficiary_count: 1500, benefit_count: 40, benefit_value: 3_000_000, funding_allocated: 5_000_000, households: 400, served: 1200, active_programmes: 3, active_activities: 5, mdas: ['Ministry of Humanitarian Affairs'], band: 'green' as const },
  { key: 'hadejia', name: 'Hadejia', beneficiary_count: 200, benefit_count: 5, benefit_value: 2_500_000, funding_allocated: 4_000_000, households: 80, served: 100, active_programmes: 2, active_activities: 2, mdas: ['Ministry of Health'], band: 'red' as const },
  { key: 'gumel', name: 'Gumel', beneficiary_count: 600, benefit_count: 20, benefit_value: 120_000, funding_allocated: 100_000, households: 150, served: 500, active_programmes: 1, active_activities: 1, mdas: ['Ministry of Education'], band: 'green' as const },
  { key: 'kano', name: 'Kano', beneficiary_count: 20, benefit_count: 1, benefit_value: 10_000, funding_allocated: 50_000, households: 5, served: 10, active_programmes: 1, active_activities: 1, mdas: [], band: 'red' as const },
]
const bands = { green_min: 1000, yellow_min: 250 }

const choropleth: GisCoverageResponse = {
  level: 'lga', scope: { kind: 'partner', label: 'Funded programmes' }, mode: 'choropleth', bands, rows,
  feature_collection: { type: 'FeatureCollection', features: [] },
}
const tableOnly: GisCoverageResponse = { ...choropleth, mode: 'table', feature_collection: null }

function renderTab(ui: ReactNode) {
  const qc = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return render(<QueryClientProvider client={qc}>{ui}</QueryClientProvider>)
}

/** The quadrant cell containing a given label. */
function quadCell(label: string): HTMLElement {
  const cell = screen.getByText(label).closest('[data-tone]')
  if (!cell) throw new Error(`no quadrant cell for ${label}`)
  return cell as HTMLElement
}

describe('FundingPartnerInvestmentTab', () => {
  beforeEach(() => vi.clearAllMocks())

  it('offers the layers we HAVE and leaves poverty/vulnerability as inert slots', async () => {
    coverage.mockResolvedValue(choropleth)
    renderTab(<FundingPartnerInvestmentTab />)

    expect(await screen.findByTestId('band-map')).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Funding distribution' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Beneficiary concentration' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Programme coverage' })).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Funded programmes' })).toBeInTheDocument()

    // Omitted layers are present ONLY as inert slots — never selectable controls.
    expect(screen.getByText('Poverty rate')).toBeInTheDocument()
    expect(screen.getByText('Vulnerability')).toBeInTheDocument()
    expect(screen.queryByRole('button', { name: 'Poverty rate' })).toBeNull()
    expect(screen.queryByRole('checkbox', { name: 'Vulnerability' })).toBeNull()
  })

  it('classifies LGAs into coverage-vs-funding quadrants (absolute coverage)', async () => {
    coverage.mockResolvedValue(choropleth)
    renderTab(<FundingPartnerInvestmentTab />)
    await screen.findByTestId('band-map')

    expect(within(quadCell('High funding · High coverage')).getByRole('button', { name: 'Dutse' })).toBeInTheDocument()
    // The high-value signal: funded but few reached → possible implementation problem.
    expect(within(quadCell('High funding · Low coverage')).getByRole('button', { name: 'Hadejia' })).toBeInTheDocument()
    expect(within(quadCell('Low funding · High coverage')).getByRole('button', { name: 'Gumel' })).toBeInTheDocument()
    expect(within(quadCell('Low funding · Low coverage')).getByRole('button', { name: 'Kano' })).toBeInTheDocument()
  })

  it('drills down to an LGA with funding + delivery + absolute coverage (no population/poverty)', async () => {
    coverage.mockResolvedValue(choropleth)
    const user = userEvent.setup()
    renderTab(<FundingPartnerInvestmentTab />)
    await screen.findByTestId('band-map')

    await user.click(screen.getAllByRole('button', { name: 'Dutse' })[0]!)

    const detail = screen.getByRole('region', { name: 'Investment detail' })
    expect(within(detail).getByText('Registered households')).toBeInTheDocument()
    expect(within(detail).getByText('400')).toBeInTheDocument()
    expect(within(detail).getByText('Funding received')).toBeInTheDocument()
    expect(within(detail).getByText('₦50,000.00')).toBeInTheDocument() // 5,000,000 kobo budget
    expect(within(detail).getByText('Funds delivered')).toBeInTheDocument()
    expect(within(detail).getByText('₦30,000.00')).toBeInTheDocument() // 3,000,000 kobo delivered
    expect(within(detail).getByText('Coverage (served)')).toBeInTheDocument()
    expect(within(detail).getByText('1,200')).toBeInTheDocument()
    expect(within(detail).getByText('Ministry of Humanitarian Affairs')).toBeInTheDocument()

    // Omitted fields never appear in the drill-down (poverty/vulnerability exist only as
    // the inert layer slots at the top of the tab).
    expect(within(detail).queryByText(/population|povert|vulnerabilit|coverage %|% of population/i)).toBeNull()
  })

  it('degrades to a ranked table when boundaries are not loaded', async () => {
    coverage.mockResolvedValue(tableOnly)
    renderTab(<FundingPartnerInvestmentTab />)

    expect(await screen.findByText(/isn.t loaded yet/i)).toBeInTheDocument()
    expect(screen.queryByTestId('band-map')).toBeNull()
    expect(screen.getAllByRole('button', { name: 'Dutse' }).length).toBeGreaterThan(0)
  })

  it('passes the cross-cutting filter to the coverage query (funded scope)', async () => {
    coverage.mockResolvedValue(choropleth)
    renderTab(<FundingPartnerInvestmentTab filter={{ ...EMPTY_FILTER, lga: 'dutse' }} />)
    await screen.findByTestId('band-map')
    expect(coverage).toHaveBeenLastCalledWith('lga', { lga: 'dutse' })
  })

  it('drills from an LGA to a detail tab, scoped to that LGA', async () => {
    coverage.mockResolvedValue(choropleth)
    const onDrill = vi.fn()
    const user = userEvent.setup()
    renderTab(<FundingPartnerInvestmentTab onDrill={onDrill} />)
    await screen.findByTestId('band-map')

    await user.click(screen.getAllByRole('button', { name: 'Dutse' })[0]!)
    await user.click(screen.getByRole('button', { name: /View registry here/i }))
    expect(onDrill).toHaveBeenCalledWith('registry', { lga: 'dutse' })
  })

  it('recolours / re-ranks when a different layer is selected', async () => {
    coverage.mockResolvedValue(choropleth)
    const user = userEvent.setup()
    renderTab(<FundingPartnerInvestmentTab />)
    await screen.findByTestId('band-map')

    // Default layer is funding distribution.
    expect(screen.getByText('Funding distribution by LGA')).toBeInTheDocument()
    expect(screen.getByText('₦50,000.00')).toBeInTheDocument() // Dutse funding in the ranked list

    await user.click(screen.getByRole('button', { name: 'Beneficiary concentration' }))
    expect(screen.getByText('Beneficiary concentration by LGA')).toBeInTheDocument()
    expect(screen.getByText('1,500')).toBeInTheDocument() // Dutse beneficiary_count now shown
  })
})
