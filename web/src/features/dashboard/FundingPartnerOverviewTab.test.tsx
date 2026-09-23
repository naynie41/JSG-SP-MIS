import { describe, expect, it, vi } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { FundingPartnerOverviewTab } from './FundingPartnerOverviewTab'
import type { DashboardResponse, PartnerFunding } from './types'

function buildPf(over: Partial<PartnerFunding> = {}): PartnerFunding {
  return {
    allocated: 300_000_000,
    delivered_value: 120_000_000,
    remaining: 180_000_000,
    utilization_rate: 0.4,
    funded_programmes: 4,
    funded_activities: 6,
    active_activities: 5,
    implementing_mdas: 2,
    lgas_covered: 5,
    wards_covered: 12,
    net_unique_reached: 1500,
    target: 2000,
    reach_vs_target: 0.75,
    cost_per_beneficiary: 80_000,
    reach: { households_reached: 600, women_reached: 900, children_reached: 400 },
    coverage_bands: { basis: 'absolute', thresholds: { green_min: 1000, yellow_min: 250 }, summary: { green: 2, yellow: 1, red: 0 }, areas: [] },
    funding_by_partner: [],
    programme_overlap: { count: 0, cells: [] },
    programmes: [],
    output_indicators: [],
    registry: {
      total_individuals: 0,
      total_households: 0,
      verified: 0,
      pending: 0,
      suspended: 0,
      duplicate_records: 0,
      new_registrations: 0,
      updated_records: 0,
      period_days: 30,
      demographics: {
        by_gender: {},
        gender_known: 0,
        female_pct: null,
        age_bands: {},
        by_lga: {},
        household_size: { total_households: 0, households_with_members: 0, average_size: null, bands: { '1': 0, '2-3': 0, '4-6': 0, '7+': 0 } },
      },
      funnel: { registered: 0, enrolled: 0, receiving: 0 },
      quality: { verification_rate: null, duplicate_rate: null, data_completeness: null, nin_linkage: null, missing: { nin: 0, phone: 0, date_of_birth: 0, gender: 0, lga: 0 } },
    },
    coordination: {
      landscape: { funders: 0, implementing_agencies: 0, delivering_agencies: 0 },
      funding_by_partner: [],
      agencies: [],
    },
    ...over,
  }
}

function buildPayload(pf: PartnerFunding | null): DashboardResponse {
  return {
    scope: { kind: 'partner', label: 'Funded programmes', tier: 'partner' },
    computed_at: new Date().toISOString(),
    metrics: {
      registry: { beneficiaries: { total: 0, by_status: {}, by_source: {}, by_lga: {} }, households: null },
      programmes: { total: 0, active: 0 },
      duplicates: null,
      benefits: {
        disbursed: { benefit_count: 0, total_value: 0, total_quantity: '0' },
        budget: { allocated: 0, utilized_value: 0, utilized_quantity: '0', benefit_count: 0, remaining: 0, utilization_rate: 0 },
        by_type: [],
      },
      referrals: null,
      grievances: null,
      coverage: [],
      partner_funding: pf,
    },
  }
}

/**
 * The band under the hero (Phase 6P, tab 1).
 *
 * What it must NOT do is as load-bearing as what it shows: Allocated, Remaining and
 * Target used to appear here as standalone tiles AND again in the sections below, so
 * these tests pin that each is now stated once, as the denominator of a lead figure.
 */
describe('FundingPartnerOverviewTab — the band', () => {
  it('leads on people reached, against the target and what each person cost', () => {
    render(<FundingPartnerOverviewTab data={buildPayload(buildPf())} />)

    const band = screen.getByRole('region', { name: 'Funded-scope indicators' })
    expect(within(band).getByText('People reached')).toBeInTheDocument()
    expect(within(band).getByText('1,500')).toBeInTheDocument()
    // The target is context for the lead figure, not a tile of its own, and cost per
    // person is the one money figure nothing else on the page states.
    expect(band).toHaveTextContent(/75% of the 2,000 you targeted/)
    expect(band).toHaveTextContent(/₦800\.00 delivered per person/)
    expect(band).toHaveTextContent(/counted once, however many funded programmes/i)
  })

  it('carries no money headline — the hero above already states it', () => {
    render(<FundingPartnerOverviewTab data={buildPayload(buildPf())} />)

    const band = screen.getByRole('region', { name: 'Funded-scope indicators' })
    // Value delivered and the share of the commitment belong to the hero and to the
    // lifecycle strip; stating them a third time between the two is what this
    // redesign removed.
    expect(within(band).queryByText('₦1,200,000.00')).toBeNull()
    expect(within(band).queryByText('Value delivered')).toBeNull()
  })

  it('shows the portfolio as what delivery runs through, with activities as a ratio', () => {
    render(<FundingPartnerOverviewTab data={buildPayload(buildPf())} />)

    const band = screen.getByRole('region', { name: 'Funded-scope indicators' })
    expect(within(band).getByText('Funded programmes')).toBeInTheDocument()
    expect(within(band).getByText('Implementing agencies')).toBeInTheDocument()
    // "5 of 6" rather than a bare 5 beside a separate "6 funded" elsewhere.
    expect(within(band).getByText('5 of 6')).toBeInTheDocument()
    expect(within(band).getByText('LGAs covered')).toBeInTheDocument()
  })

  it('never repeats a figure the sections below already explain', () => {
    render(<FundingPartnerOverviewTab data={buildPayload(buildPf())} />)

    const band = screen.getByRole('region', { name: 'Funded-scope indicators' })
    // Allocated and Remaining belong to the Funding lifecycle; Target to Results.
    expect(within(band).queryByText('Allocated')).toBeNull()
    expect(within(band).queryByText('Remaining')).toBeNull()
    expect(within(band).queryByText('Target beneficiaries')).toBeNull()
    // …and they are still on the page, once each.
    expect(screen.getByRole('region', { name: 'Funding lifecycle' })).toBeInTheDocument()
    expect(screen.getByRole('region', { name: 'Results and reach' })).toBeInTheDocument()
  })

  it('drills into the funded detail from the band', async () => {
    const user = userEvent.setup()
    const onDrill = vi.fn()
    render(<FundingPartnerOverviewTab data={buildPayload(buildPf())} onDrill={onDrill} />)

    // A drillable figure is a real button, so it is reachable by keyboard.
    await user.click(screen.getByRole('button', { name: '1,500' }))
    expect(onDrill).toHaveBeenCalledWith('registry')

    await user.click(screen.getByRole('button', { name: /funded programmes/i }))
    expect(onDrill).toHaveBeenCalledWith('programmes')
  })

  it('says so plainly when nothing is attributed yet', () => {
    render(<FundingPartnerOverviewTab data={buildPayload(null)} />)

    expect(screen.getByText(/No funded activities are attributed to you yet/i)).toBeInTheDocument()
  })
})
