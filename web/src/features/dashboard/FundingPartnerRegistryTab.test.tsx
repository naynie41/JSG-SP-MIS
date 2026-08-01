import { describe, expect, it } from 'vitest'
import { render, screen, within } from '@testing-library/react'
import { FundingPartnerRegistryTab } from './FundingPartnerRegistryTab'
import type { DashboardResponse, PartnerFunding, PartnerRegistry } from './types'

function makeRegistry(over: Partial<PartnerRegistry> = {}): PartnerRegistry {
  return {
    total_individuals: 800,
    total_households: 300,
    verified: 780,
    pending: 20,
    suspended: 3,
    duplicate_records: 5,
    new_registrations: 40,
    updated_records: 12,
    period_days: 30,
    demographics: {
      by_gender: { female: 420, male: 380 },
      gender_known: 800,
      female_pct: 0.525,
      age_bands: { children: 200, youth: 300, adults: 250, elderly: 50, unknown: 0 },
      by_lga: { dutse: 500, hadejia: 300 },
      household_size: { total_households: 300, households_with_members: 300, average_size: 4.2, bands: { '1': 50, '2-3': 100, '4-6': 120, '7+': 30 } },
    },
    funnel: { registered: 800, enrolled: 700, receiving: 600 },
    quality: { verification_rate: 0.975, duplicate_rate: 0.006, data_completeness: 0.88, nin_linkage: 0.9, missing: { nin: 80, phone: 40, date_of_birth: 10, gender: 0, lga: 0 } },
    ...over,
  }
}

function buildPf(registry: PartnerRegistry): PartnerFunding {
  return {
    allocated: 0,
    delivered_value: 0,
    remaining: 0,
    utilization_rate: null,
    funded_programmes: 0,
    funded_activities: 0,
    active_activities: 0,
    implementing_mdas: 0,
    lgas_covered: 0,
    wards_covered: 0,
    net_unique_reached: 0,
    target: 0,
    reach_vs_target: null,
    cost_per_beneficiary: null,
    reach: { households_reached: 0, women_reached: 0, children_reached: 0 },
    coverage_bands: { basis: 'absolute', thresholds: { green_min: 1000, yellow_min: 250 }, summary: { green: 0, yellow: 0, red: 0 }, areas: [] },
    funding_by_partner: [],
    programme_overlap: { count: 0, cells: [] },
    programmes: [],
    output_indicators: [],
    registry,
    coordination: {
      landscape: { funders: 0, government_agencies: 0, implementing_agencies: 0 },
      funding_by_partner: [],
      agencies: [],
      data_sharing: { agencies_integrated: 0, connectors: 0, sources: [], total_runs: 0, succeeded: 0, failed: 0, last_run_at: null, api_registrations: 0 },
    },
  }
}

function buildPayload(registry: PartnerRegistry): DashboardResponse {
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
      partner_funding: buildPf(registry),
    },
  }
}

describe('FundingPartnerRegistryTab', () => {
  it('renders funded-scope registry KPIs', () => {
    render(<FundingPartnerRegistryTab data={buildPayload(makeRegistry())} />)

    expect(screen.getByText('Individuals')).toBeInTheDocument()
    expect(screen.getByText('Households')).toBeInTheDocument()
    expect(screen.getByText('Verified')).toBeInTheDocument()
    expect(screen.getByText('Pending review')).toBeInTheDocument()
    expect(screen.getByText('Duplicate records')).toBeInTheDocument()
    expect(screen.getByText('New registrations')).toBeInTheDocument()
    expect(screen.getByText('Updated records')).toBeInTheDocument()
    expect(screen.getByText('Suspended')).toBeInTheDocument()
    expect(screen.getByText('780')).toBeInTheDocument() // verified count
  })

  it('renders the REDUCED targeting funnel with an inert Eligible→Selected slot', () => {
    render(<FundingPartnerRegistryTab data={buildPayload(makeRegistry())} />)

    const funnel = screen.getByRole('region', { name: 'Targeting funnel' })
    expect(within(funnel).getByText('Registered')).toBeInTheDocument()
    expect(within(funnel).getByText('Enrolled')).toBeInTheDocument()
    expect(within(funnel).getByText('Receiving benefits')).toBeInTheDocument()

    // Eligible → Selected is an inert slot, never a fabricated number.
    expect(within(funnel).getByText(/Eligible → Selected/i)).toBeInTheDocument()
    expect(within(funnel).getByText(/needs an eligible-population denominator and a selection model/i)).toBeInTheDocument()
  })

  it('shows captured-field demographics only (no poverty/disability/vulnerability)', () => {
    render(<FundingPartnerRegistryTab data={buildPayload(makeRegistry())} />)

    const demo = screen.getByRole('region', { name: 'Demographics' })
    expect(within(demo).getByText(/Gender/)).toBeInTheDocument()
    expect(within(demo).getByText(/Age band/)).toBeInTheDocument()
    expect(within(demo).getByText(/Household size/)).toBeInTheDocument()
    expect(within(demo).getByText(/Location/)).toBeInTheDocument()
    // Captured fields render as bar labels.
    expect(within(demo).getByText('Female')).toBeInTheDocument()
    expect(within(demo).getByText('Children')).toBeInTheDocument()

    // Omitted fields are absent entirely.
    expect(screen.queryByText(/poverty/i)).toBeNull()
    expect(screen.queryByText(/disabilit|\bPWD\b/i)).toBeNull()
    expect(screen.queryByText(/vulnerab/i)).toBeNull()
    expect(screen.queryByText(/rural|urban/i)).toBeNull()
  })

  it('renders data quality (verification, NIN linkage, completeness, duplicate rate, missing) and omits bank/mobile-money', () => {
    render(<FundingPartnerRegistryTab data={buildPayload(makeRegistry())} />)

    const quality = screen.getByRole('region', { name: 'Data quality' })
    expect(within(quality).getByText('Verification rate')).toBeInTheDocument()
    expect(within(quality).getByText('Linked to NIN')).toBeInTheDocument()
    expect(within(quality).getByText('Data completeness')).toBeInTheDocument()
    expect(within(quality).getByText('Duplicate rate')).toBeInTheDocument()
    expect(within(quality).getByText('Missing data')).toBeInTheDocument()
    expect(within(quality).getByText('98%')).toBeInTheDocument() // verification rate 0.975 → 98%

    // SP-MIS is not a payment engine — no bank / mobile-money verification METER
    // (the footnote names them only to explain their absence).
    expect(screen.queryByText('Bank verified')).toBeNull()
    expect(screen.queryByText('Mobile money verified')).toBeNull()
    expect(within(quality).getByText(/not a payment engine/i)).toBeInTheDocument()
  })

  it('shows an empty state when no beneficiaries are on record', () => {
    render(<FundingPartnerRegistryTab data={buildPayload(makeRegistry({ total_individuals: 0 }))} />)

    expect(screen.getByText(/No beneficiaries are on record for your funded activities yet/i)).toBeInTheDocument()
  })
})
