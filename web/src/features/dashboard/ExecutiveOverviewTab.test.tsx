import { describe, expect, it } from 'vitest'
import { render, screen } from '@testing-library/react'
import { ExecutiveOverviewTab } from './ExecutiveOverviewTab'
import { makeExecutivePayload } from './executiveTestData'

describe('ExecutiveOverviewTab', () => {
  it('renders the KPI band led by the NET-UNIQUE figure', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Net-unique reached')).toBeInTheDocument()
    expect(screen.getAllByText('8,420').length).toBeGreaterThan(0) // net-unique headline KPI

    expect(screen.getByText('Active programmes')).toBeInTheDocument()
    expect(screen.getByText('LGAs covered')).toBeInTheDocument()
    expect(screen.getByText('96')).toBeInTheDocument() // wards covered
    expect(screen.getByText('51%')).toBeInTheDocument() // female share
  })

  it('renders rule-based insights and severity-ordered alerts', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByText(/net-unique beneficiaries have been reached/i)).toBeInTheDocument()
    expect(screen.getByText(/Cash Transfer reached 90% of its target/i)).toBeInTheDocument()

    expect(screen.getByText(/Low delivery — School Feeding/i)).toBeInTheDocument()
    expect(screen.getByText(/Budget nearly exhausted — Cash Transfer/i)).toBeInTheDocument()
    expect(screen.getByText(/records pending verification/i)).toBeInTheDocument()
  })

  it('renders labelled trend projections with stated assumptions', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByRole('heading', { name: 'Projections' })).toBeInTheDocument()
    // Every projection is explicitly labelled (not presented as certainty)…
    expect(screen.getAllByText(/Projection · based on current trend/i).length).toBeGreaterThan(0)
    expect(screen.getByText('Budget runway')).toBeInTheDocument()
    // …and states its assumption.
    expect(screen.getAllByText(/Assumes/i).length).toBeGreaterThan(0)
  })

  it('renders the programme share donut and demographics', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Beneficiary share by programme')).toBeInTheDocument()
    expect(screen.getAllByText('Cash Transfer').length).toBeGreaterThan(0)
    expect(screen.getByText('Female')).toBeInTheDocument()
    expect(screen.getByText('Gender split')).toBeInTheDocument()
    expect(screen.getByText('Age groups')).toBeInTheDocument()
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
    expect(screen.getAllByText('0').length).toBeGreaterThan(0) // headline falls back to zero
  })
})
