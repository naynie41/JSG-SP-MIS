import { describe, expect, it, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { ExecutiveOverviewTab } from './ExecutiveOverviewTab'
import { makeExecutivePayload } from './executiveTestData'

describe('ExecutiveOverviewTab', () => {
  it('carries four figures that qualify the headline, none repeating the hero', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    // Is the money moving, does it reach the state, is it still growing, who is
    // doing it. "Budget used" rather than "Value delivered" because the hero
    // already carries the naira figure — what it does not say is the share.
    expect(screen.getByText('Budget used')).toBeInTheDocument()
    expect(screen.getByText('LGAs covered')).toBeInTheDocument()
    expect(screen.getByText('New this period')).toBeInTheDocument()
    expect(screen.getByText('Agencies delivering')).toBeInTheDocument()
  })

  it('summarises each section without becoming that section', () => {
    // This test used to assert the opposite — that no other section's figures
    // appeared here at all. The owner reversed that: an executive has no time to
    // walk the tabs, so the overview now carries a headline from each. What must
    // NOT come with it is the detail, which is what made the old page a thing to
    // study rather than read.
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    // The summary figure is welcome…
    expect(screen.getByText('Active programmes')).toBeInTheDocument()

    // …the section's own detail is not. Each of these belongs to a tab.
    expect(screen.queryByText('Cost / beneficiary')).toBeNull()
    expect(screen.queryByText('Beneficiary share by programme')).toBeNull()
    expect(screen.queryByText('Gender split')).toBeNull()
    expect(screen.queryByText('Age groups')).toBeNull()
    expect(screen.queryByRole('heading', { name: 'Projections' })).toBeNull()
  })

  /* --------------------------------------------- packed, organised overview */

  it('leads with what needs attention, before the figures', () => {
    // The only part of this page an executive can act on. Numbers first is the
    // conventional order and it buries the problems under a reassuring band.
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    const attention = screen.getByRole('heading', { name: /needs attention/i })
    const firstFigure = screen.getByText('Budget used')

    // DOM order, not heading order: the figures band carries no heading of its own.
    expect(attention.compareDocumentPosition(firstFigure) & Node.DOCUMENT_POSITION_FOLLOWING).toBeTruthy()
  })

  it('surfaces problems that previously lived only on other tabs', () => {
    const data = makeExecutivePayload()
    data.metrics.referrals = { ...data.metrics.referrals!, total: 7, overdue: 5 }
    data.metrics.grievances = {
      by_status: {},
      avg_resolution_days: 5,
      ...data.metrics.grievances,
      total: 6,
      sla_breaches: 2,
    }
    data.metrics.coverage_bands = {
      ...data.metrics.coverage_bands!,
      summary: { green: 0, yellow: 0, red: 27 },
    }

    render(<ExecutiveOverviewTab data={data} />)

    // Each of these was invisible to an executive who did not open its tab.
    expect(screen.getByText(/5 referrals past the response time/i)).toBeInTheDocument()
    expect(screen.getByText(/2 grievances past the response time/i)).toBeInTheDocument()
    expect(screen.getByText(/every local government area is below the coverage threshold/i)).toBeInTheDocument()
  })

  it('gives every section a card that leads to the page explaining it', async () => {
    const user = userEvent.setup()
    const onDrill = vi.fn()
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} onDrill={onDrill} />)

    for (const name of [/view programmes/i, /view register/i, /view coverage/i, /view coordination/i]) {
      expect(screen.getByRole('button', { name })).toBeInTheDocument()
    }

    await user.click(screen.getByRole('button', { name: /view coordination/i }))
    expect(onDrill).toHaveBeenCalledWith('coordination', {})
  })

  it('caps every section card at four figures', () => {
    // The cap is the design: a card answers one question, and the fifth figure is
    // what the tab is for. Without it "packed" becomes the wall this page avoids.
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    for (const name of ['Delivery', 'The register', 'Reach', 'Coordination']) {
      const card = screen.getByText(name).closest('article')
      expect(card).not.toBeNull()
      // Each figure is one <dt>; the hints are extra <dd>s, so count the terms.
      const terms = card!.querySelectorAll('dt')
      expect(terms.length).toBeGreaterThan(0)
      expect(terms.length).toBeLessThanOrEqual(4)
    }
  })

  it('still says what the numbers mean, condensed', () => {
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    // Kept, but no longer a full panel competing with the sections.
    expect(screen.getByText(/people have been reached/i)).toBeInTheDocument()
    expect(screen.queryByRole('heading', { name: 'Executive insights' })).toBeNull()
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

  it('changes nothing — every control only navigates', () => {
    // This assertion used to be "no buttons at all", which was a proxy for the real
    // rule rather than the rule itself. The executive view is read-only OVERSIGHT:
    // it must never edit, approve, export or submit. Navigating to the section that
    // explains a figure breaks none of that, and the overview now needs it.
    render(<ExecutiveOverviewTab data={makeExecutivePayload()} />)

    // Nothing that takes input, and nothing that acts on a record.
    expect(screen.queryByRole('textbox')).toBeNull()
    expect(screen.queryByRole('checkbox')).toBeNull()
    expect(screen.queryByRole('combobox')).toBeNull()

    for (const button of screen.queryAllByRole('button')) {
      // The accessible name, which is what a screen-reader user actually hears.
      const label = button.getAttribute('aria-label') ?? button.textContent ?? ''
      expect(label).toMatch(/\b(view|open)\b/i)
      expect(label).not.toMatch(/save|edit|approve|delete|submit|export|download|run/i)
    }
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
