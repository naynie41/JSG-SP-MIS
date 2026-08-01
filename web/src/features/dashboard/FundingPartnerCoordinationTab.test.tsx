import { describe, expect, it } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import { FundingPartnerCoordinationTab } from './FundingPartnerCoordinationTab'
import type { DashboardResponse, PartnerCoordination, PartnerFunding } from './types'

function makeCoordination(over: Partial<PartnerCoordination> = {}): PartnerCoordination {
  return {
    landscape: { funders: 3, government_agencies: 2, implementing_agencies: 1 },
    funding_by_partner: [
      { partner_id: 'u-self', name: 'World Bank', is_self: true, allocated: 200_000_000, delivered_value: 60_000_000, net_unique_reached: 800, funded_programmes: 2, shared_programmes: 2 },
      { partner_id: 'u-2', name: 'UNICEF', is_self: false, allocated: null, delivered_value: null, net_unique_reached: null, funded_programmes: null, shared_programmes: 1 },
    ],
    agencies: [{ id: 'm1', name: 'Ministry of Humanitarian Affairs', activities: 3, programmes: 2 }],
    data_sharing: { agencies_integrated: 1, connectors: 2, sources: ['api', 'csv'], total_runs: 10, succeeded: 8, failed: 2, last_run_at: new Date().toISOString(), api_registrations: 120 },
    ...over,
  }
}

function buildPf(coordination: PartnerCoordination, overlap?: PartnerFunding['programme_overlap']): PartnerFunding {
  return {
    allocated: 200_000_000,
    delivered_value: 60_000_000,
    remaining: 140_000_000,
    utilization_rate: 0.3,
    funded_programmes: 2,
    funded_activities: 5,
    active_activities: 4,
    implementing_mdas: 2,
    lgas_covered: 4,
    wards_covered: 9,
    net_unique_reached: 800,
    target: 2000,
    reach_vs_target: 0.4,
    cost_per_beneficiary: 75_000,
    reach: { households_reached: 300, women_reached: 420, children_reached: 260 },
    coverage_bands: { basis: 'absolute', thresholds: { green_min: 1000, yellow_min: 250 }, summary: { green: 1, yellow: 1, red: 2 }, areas: [] },
    funding_by_partner: [],
    programme_overlap: overlap ?? {
      count: 1,
      cells: [{ programme_id: 'p1', programme: 'Cash Transfer Programme', lga: 'dutse', other_funders: 1, other_mdas: 1 }],
    },
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
      demographics: { by_gender: {}, gender_known: 0, female_pct: null, age_bands: {}, by_lga: {}, household_size: { total_households: 0, households_with_members: 0, average_size: null, bands: { '1': 0, '2-3': 0, '4-6': 0, '7+': 0 } } },
      funnel: { registered: 0, enrolled: 0, receiving: 0 },
      quality: { verification_rate: null, duplicate_rate: null, data_completeness: null, nin_linkage: null, missing: { nin: 0, phone: 0, date_of_birth: 0, gender: 0, lga: 0 } },
    },
    coordination,
  }
}

function buildPayload(pf: PartnerFunding): DashboardResponse {
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

describe('FundingPartnerCoordinationTab', () => {
  it('renders the partner landscape (funders, government agencies, implementing agencies)', () => {
    render(<FundingPartnerCoordinationTab data={buildPayload(buildPf(makeCoordination()))} />)

    const landscape = screen.getByRole('region', { name: 'Partner landscape' })
    expect(within(landscape).getByText('Funding organisations')).toBeInTheDocument()
    expect(within(landscape).getByText('Government agencies (MDAs)')).toBeInTheDocument()
    expect(within(landscape).getByText('Implementing agencies')).toBeInTheDocument()
    expect(within(landscape).getByText('3')).toBeInTheDocument() // funders
  })

  it('surfaces PROGRAMME OVERLAP as a table + LGA map indicator', () => {
    render(<FundingPartnerCoordinationTab data={buildPayload(buildPf(makeCoordination()))} />)

    const overlap = screen.getByRole('region', { name: 'Programme overlap' })
    expect(within(overlap).getByText('Cash Transfer Programme')).toBeInTheDocument()
    expect(within(overlap).getAllByText('Dutse').length).toBeGreaterThan(0) // table cell + map chip
    expect(within(overlap).getByText('Other funders')).toBeInTheDocument()
    expect(within(overlap).getByText('Other MDAs')).toBeInTheDocument()
    // The LGA map indicator (a labelled set of overlapped-LGA chips).
    const mapIndicator = within(overlap).getByRole('group', { name: /Overlap by LGA/i })
    expect(within(mapIndicator).getByText('Dutse')).toBeInTheDocument()
  })

  it('shows an all-clear when no overlap is detected', () => {
    render(<FundingPartnerCoordinationTab data={buildPayload(buildPf(makeCoordination(), { count: 0, cells: [] }))} />)

    const overlap = screen.getByRole('region', { name: 'Programme overlap' })
    expect(within(overlap).getByText(/No overlap detected/i)).toBeInTheDocument()
  })

  it('shows funding-by-partner with amounts for SELF only (co-funders carry no money)', () => {
    render(<FundingPartnerCoordinationTab data={buildPayload(buildPf(makeCoordination()))} />)

    const funding = screen.getByRole('region', { name: 'Funding by partner' })
    // Self row — full figures + a "You" badge.
    expect(within(funding).getByText('World Bank')).toBeInTheDocument()
    expect(within(funding).getByText('You')).toBeInTheDocument()
    expect(within(funding).getByText('₦2,000,000.00')).toBeInTheDocument() // self allocated

    // Co-funder present, but no money leaked (rendered as em dashes).
    expect(within(funding).getByText('UNICEF')).toBeInTheDocument()
    expect(within(funding).getAllByText('—').length).toBeGreaterThan(0)
    expect(within(funding).getByText(/your own funding only/i)).toBeInTheDocument()
  })

  it('renders data sharing / sync health', () => {
    render(<FundingPartnerCoordinationTab data={buildPayload(buildPf(makeCoordination()))} />)

    const ds = screen.getByRole('region', { name: 'Data sharing' })
    expect(within(ds).getByText('Agencies integrated')).toBeInTheDocument()
    expect(within(ds).getByText('Connectors')).toBeInTheDocument()
    expect(within(ds).getByText('Runs failed')).toBeInTheDocument()
    expect(within(ds).getByText('API registrations')).toBeInTheDocument()
    expect(within(ds).getByText('Api')).toBeInTheDocument() // a source chip (humanised)
  })

  it('omits meetings + reporting-compliance modules (inert slots only)', () => {
    render(<FundingPartnerCoordinationTab data={buildPayload(buildPf(makeCoordination()))} />)

    const inert = screen.getByRole('region', { name: 'Not tracked here' })
    expect(within(inert).getByText(/Coordination meetings & action items/i)).toBeInTheDocument()
    expect(within(inert).getByText(/Reporting compliance/i)).toBeInTheDocument()
    expect(within(inert).getByText(/No meetings module/i)).toBeInTheDocument()
    expect(within(inert).getByText(/No reporting workflow/i)).toBeInTheDocument()
  })
})
