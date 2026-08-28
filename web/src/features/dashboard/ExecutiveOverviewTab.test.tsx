import { describe, expect, it } from 'vitest'
import { render, screen } from '@testing-library/react'
import { ExecutiveOverviewTab } from './ExecutiveOverviewTab'
import { makeExecutivePayload } from './executiveTestData'

describe('ExecutiveOverviewTab', () => {
  it('carries only the three figures that qualify the headline', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    // Is the money moving, does it reach the state, is it still growing.
    expect(screen.getByText('Disbursed')).toBeInTheDocument()
    expect(screen.getByText('LGAs covered')).toBeInTheDocument()
    expect(screen.getByText('New this period')).toBeInTheDocument()
  })

  it('does not repeat the figures that belong to another section', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    // Delivery detail lives on Programmes, composition on Registry. Fifty
    // co-equal figures made this page something to study rather than read.
    expect(screen.queryByText('Active programmes')).toBeNull()
    expect(screen.queryByText('Budget allocated')).toBeNull()
    expect(screen.queryByText('Cost / beneficiary')).toBeNull()
    expect(screen.queryByText('Beneficiary share by programme')).toBeNull()
    expect(screen.queryByText('Gender split')).toBeNull()
    expect(screen.queryByText('Age groups')).toBeNull()
    expect(screen.queryByRole('heading', { name: 'Projections' })).toBeNull()
  })

  it('renders rule-based insights and severity-ordered alerts', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByText(/net-unique beneficiaries have been reached/i)).toBeInTheDocument()
    expect(screen.getByText(/Cash Transfer reached 90% of its target/i)).toBeInTheDocument()

    expect(screen.getByText(/Low delivery: School Feeding/i)).toBeInTheDocument()
    expect(screen.getByText(/Budget nearly exhausted: Cash Transfer/i)).toBeInTheDocument()
    expect(screen.getByText(/records pending verification/i)).toBeInTheDocument()
  })

  it('shows one trend — the headline trajectory, not four competing series', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Beneficiaries reached (cumulative)')).toBeInTheDocument()
    expect(screen.queryByText('Monthly disbursement')).toBeNull()
    expect(screen.queryByText('Programme growth')).toBeNull()
  })

  it('is a pure read-only display — no interactive controls', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.queryAllByRole('button')).toHaveLength(0)
    expect(screen.queryByRole('textbox')).toBeNull()
  })

  it('degrades gracefully when executive metrics are absent (older snapshot)', () => {
    const payload = makeExecutivePayload()
    const m = payload.metrics
    m.population = undefined
    m.demographics = undefined
    m.programme_performance = undefined
    m.trends = undefined
    m.coverage_bands = undefined
    m.registry_quality = undefined

    expect(() => render(<ExecutiveOverviewTab data={payload} />)).not.toThrow()
    expect(screen.getAllByText('0').length).toBeGreaterThan(0)
  })
})
