import { describe, expect, it } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { FundingPartnerProgrammesTab } from './FundingPartnerProgrammesTab'
import type { DashboardResponse, OutputIndicator, PartnerFunding, PartnerProgramme } from './types'

function makeProgramme(over: Partial<PartnerProgramme> & { programme_id: string }): PartnerProgramme {
  return {
    name: 'Programme',
    type: 'cash_transfer',
    status: 'active',
    mdas: [{ id: 'm1', name: 'Ministry of Humanitarian Affairs' }],
    start_date: '2025-01-01',
    end_date: '2026-12-31',
    allocated: 100_000_000,
    delivered_value: 40_000_000,
    remaining: 60_000_000,
    utilization_rate: 0.4,
    target: 1000,
    reached: 850,
    coverage_absolute: 850,
    completion_rate: 0.85,
    interventions: 1200,
    avg_benefit_value: 33_333,
    cost_per_beneficiary: 47_058,
    delivery_series: [
      { month: '2026-01', value: 10_000_000 },
      { month: '2026-02', value: 30_000_000 },
    ],
    status_light: 'on_track',
    output_indicators: [],
    activities: [],
    ...over,
  }
}

const rolledUp: OutputIndicator[] = [
  { benefit_type: 'cash', interventions: 1200, beneficiaries: 950, women: 590, children: 260 },
  { benefit_type: 'food', interventions: 200, beneficiaries: 150, women: 90, children: 60 },
]

const p1 = makeProgramme({
  programme_id: 'p1',
  name: 'Cash Transfer Programme',
  status_light: 'on_track',
  output_indicators: [
    { benefit_type: 'cash', interventions: 1000, beneficiaries: 800, women: 500, children: 200 },
    { benefit_type: 'food', interventions: 200, beneficiaries: 150, women: 90, children: 60 },
  ],
  activities: [
    {
      activity_id: 'a1',
      name: 'Q1 disbursement',
      mda: 'Ministry of Humanitarian Affairs',
      status: 'active',
      target: 500,
      reached: 450,
      completion_rate: 0.9,
      coverage_absolute: 450,
      allocated: 50_000_000,
      delivered_value: 20_000_000,
      remaining: 30_000_000,
      cost_per_beneficiary: 44_444,
      traffic_light: 'green',
    },
  ],
})
const p2 = makeProgramme({ programme_id: 'p2', name: 'School Feeding', type: 'school_meals', status_light: 'delayed', delivery_series: [] })
const p3 = makeProgramme({ programme_id: 'p3', name: 'Livelihood Support', status_light: 'at_risk' })
const p4 = makeProgramme({ programme_id: 'p4', name: 'Health Insurance', status_light: 'completed', end_date: '2025-06-30' })

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
    coverage_bands: { basis: 'absolute', thresholds: { green_min: 1000, yellow_min: 250 }, summary: { green: 2, yellow: 1, red: 1 }, areas: [] },
    funding_by_partner: [],
    programme_overlap: { count: 0, cells: [] },
    programmes: [p1, p2, p3, p4],
    output_indicators: rolledUp,
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
      landscape: { funders: 0, government_agencies: 0, implementing_agencies: 0 },
      funding_by_partner: [],
      agencies: [],
      data_sharing: { agencies_integrated: 0, connectors: 0, sources: [], total_runs: 0, succeeded: 0, failed: 0, last_run_at: null, api_registrations: 0 },
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
      programmes: { total: pf?.programmes.length ?? 0, active: pf?.programmes.length ?? 0 },
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

describe('FundingPartnerProgrammesTab', () => {
  it('renders a card per funded programme with type, implementing MDA and financials', () => {
    render(<FundingPartnerProgrammesTab data={buildPayload(buildPf())} canDrill={false} />)

    expect(screen.getByText('Cash Transfer Programme')).toBeInTheDocument()
    expect(screen.getByText('School Feeding')).toBeInTheDocument()
    expect(screen.getAllByText('Cash transfer').length).toBeGreaterThan(0) // humanised catalog type
    expect(screen.getAllByText('Ministry of Humanitarian Affairs').length).toBeGreaterThan(0)

    // Budget → Delivered → Remaining (financials-as-delivery).
    expect(screen.getAllByText('Budget').length).toBeGreaterThan(0)
    expect(screen.getAllByText('Delivered').length).toBeGreaterThan(0)
    expect(screen.getAllByText('Remaining').length).toBeGreaterThan(0)
    expect(screen.getAllByText('₦400,000.00').length).toBeGreaterThan(0) // 40,000,000 kobo delivered

    // Results: target vs actual (absolute), interventions.
    expect(screen.getAllByText(/reached vs target \(absolute\)/i).length).toBeGreaterThan(0)
    expect(screen.getAllByText('Interventions').length).toBeGreaterThan(0)
  })

  it('labels money as DELIVERY VALUE, never treasury spend/expenditure', () => {
    render(<FundingPartnerProgrammesTab data={buildPayload(buildPf())} canDrill={false} />)

    expect(screen.getAllByText(/Delivery value/).length).toBeGreaterThan(0)
    expect(screen.getAllByText(/not spend/i).length).toBeGreaterThan(0)
    expect(screen.queryByText('Spent')).toBeNull()
    expect(screen.queryByText('Disbursed')).toBeNull()
    expect(screen.queryByText(/expenditure/i)).toBeNull()
  })

  it('shows the four-state delivery status (traffic light) per programme + a legend', () => {
    render(<FundingPartnerProgrammesTab data={buildPayload(buildPf())} canDrill={false} />)

    for (const label of ['On Track', 'At Risk', 'Delayed', 'Completed']) {
      expect(screen.getAllByText(label).length).toBeGreaterThan(0)
    }
  })

  it('renders OUTPUT indicators (outputs only) by benefit type and captured demographic', () => {
    render(<FundingPartnerProgrammesTab data={buildPayload(buildPf())} canDrill={false} />)

    const outputs = screen.getByRole('region', { name: 'Output indicators' })
    expect(within(outputs).getByText('Cash')).toBeInTheDocument()
    expect(within(outputs).getByText('Food')).toBeInTheDocument()
    expect(within(outputs).getAllByText('Women').length).toBeGreaterThan(0)
    expect(within(outputs).getAllByText('Children').length).toBeGreaterThan(0)
    expect(within(outputs).getByText('1,200')).toBeInTheDocument() // cash interventions (rolled up)

    // Outcomes are explicitly NOT computed — the footnote names them as external only.
    expect(within(outputs).getByText(/require external evaluation data and are not computed here/i)).toBeInTheDocument()
  })

  it('computes the Funding→Activities→Outputs framework with an external, non-computed Outcomes→Impact slot', () => {
    render(<FundingPartnerProgrammesTab data={buildPayload(buildPf())} canDrill={false} />)

    const framework = screen.getByRole('region', { name: 'Results framework' })
    expect(within(framework).getByText('Funding')).toBeInTheDocument()
    expect(within(framework).getByText('Activities')).toBeInTheDocument()
    expect(within(framework).getByText('Outputs')).toBeInTheDocument()
    expect(within(framework).getByText(/Outcomes → Impact/i)).toBeInTheDocument()
    expect(within(framework).getByText(/Requires external evaluation data · not computed/i)).toBeInTheDocument()
  })

  it('shows the activity drill-down only where permitted', async () => {
    const user = userEvent.setup()
    const { rerender } = render(<FundingPartnerProgrammesTab data={buildPayload(buildPf())} canDrill={false} />)

    // Not permitted → no drill-down control.
    expect(screen.queryByRole('button', { name: /funded activit/i })).toBeNull()

    // Permitted → toggle reveals the activity table.
    rerender(<FundingPartnerProgrammesTab data={buildPayload(buildPf())} canDrill={true} />)
    const toggle = screen.getByRole('button', { name: /funded activit/i })
    expect(screen.queryByText('Q1 disbursement')).toBeNull()
    await user.click(toggle)
    expect(screen.getByText('Q1 disbursement')).toBeInTheDocument()
  })

  it('shows an empty state when no funded programmes are attributed', () => {
    render(<FundingPartnerProgrammesTab data={buildPayload(buildPf({ programmes: [] }))} canDrill={false} />)

    expect(screen.getByText(/No funded programmes to report yet/i)).toBeInTheDocument()
  })
})
