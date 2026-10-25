import { describe, expect, it } from 'vitest'
import { render, screen } from '@testing-library/react'
import { ExecutiveOverviewTab } from './ExecutiveOverviewTab'
import { makeExecutivePayload } from './executiveTestData'

describe('ExecutiveOverviewTab', () => {
  it('carries only the three figures that qualify the headline', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    // Is the money moving, does it reach the state, is it still growing.
    expect(screen.getByText('Value delivered')).toBeInTheDocument()
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

  /* ------------------------------------------------------- ministry brief */

  it('briefs on the leading ministries and what each is running', () => {
    const data = makeExecutivePayload()
    data.metrics.ministry_brief = [
      {
        mda_id: 'm1',
        mda: 'Ministry of Health',
        reached: 1200,
        programmes: [
          { programme_id: 'p1', name: 'Cash Transfer', reached: 800, allocated: 1_250_000_000 },
          { programme_id: 'p2', name: 'Maternal Vouchers', reached: 400, allocated: 300_000_000 },
        ],
      },
      { mda_id: 'm2', mda: 'Ministry of Education', reached: 900, programmes: [] },
    ]

    render(<ExecutiveOverviewTab data={data} />)

    expect(screen.getByRole('heading', { name: 'Leading ministries' })).toBeInTheDocument()
    expect(screen.getByText('Ministry of Health')).toBeInTheDocument()
    expect(screen.getByText('Cash Transfer')).toBeInTheDocument()
    expect(screen.getByText(/800 reached/)).toBeInTheDocument()
    expect(screen.getByText(/₦12,500,000/)).toBeInTheDocument()

    // A ministry with nothing running says so rather than showing an empty card.
    expect(screen.getByText(/no active programme has reached anyone yet/i)).toBeInTheDocument()
  })

  it('does not present a ministry total that invites adding the rows up', () => {
    // Reach is net-unique WITHIN a ministry and within the state. A person served by
    // Health and Education is one to each and one to the state, so these rows must
    // never be totalled — and the page must not look like it has totalled them.
    const data = makeExecutivePayload()
    data.metrics.ministry_brief = [
      { mda_id: 'm1', mda: 'Ministry of Health', reached: 1200, programmes: [] },
      { mda_id: 'm2', mda: 'Ministry of Education', reached: 900, programmes: [] },
    ]

    render(<ExecutiveOverviewTab data={data} />)

    expect(screen.queryByText('2,100')).toBeNull()
    expect(screen.queryByText(/total across ministries/i)).toBeNull()
  })

  it('shows nothing where the scope has no brief', () => {
    // MDA and partner scopes get null — the comparison is meaningless inside one.
    const data = makeExecutivePayload()
    data.metrics.ministry_brief = null

    render(<ExecutiveOverviewTab data={data} />)

    expect(screen.queryByRole('heading', { name: 'Leading ministries' })).toBeNull()
  })

  it('renders rule-based insights and severity-ordered alerts', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByText(/people have been reached/i)).toBeInTheDocument()
    expect(screen.getByText(/Cash Transfer reached 90% of its target/i)).toBeInTheDocument()

    expect(screen.getByText(/Low delivery: School Feeding/i)).toBeInTheDocument()
    expect(screen.getByText(/Budget nearly exhausted: Cash Transfer/i)).toBeInTheDocument()
    expect(screen.getByText(/records pending verification/i)).toBeInTheDocument()
  })

  it('shows one trend — the headline trajectory, not four competing series', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    expect(screen.getByText('Beneficiaries reached (cumulative)')).toBeInTheDocument()
    expect(screen.queryByText('Value delivered each month')).toBeNull()
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
