/** Dashboard aggregation payload (mirrors the 6.1 reporting layer). */

export interface DashboardCounts {
  total: number
  active: number
}

export interface BenefitTotals {
  benefit_count: number
  total_value: number // kobo
  total_quantity: string
}

export interface BudgetSummary {
  allocated: number // kobo
  utilized_value: number
  utilized_quantity: string
  benefit_count: number
  remaining: number
  utilization_rate: number | null // 0..1
}

export interface BenefitTypeGroup {
  key: string | null
  benefit_count: number
  total_value: number
  total_quantity: string
}

export interface CoverageRow {
  lga: string
  beneficiary_count: number
  benefit_count: number
  benefit_value: number
}

/** Programme/activity counts in scope (headline "active programmes/activities").
 * Activity counts are optional so the MDA/partner dashboard fixtures (which only
 * read programme counts) stay valid; the backend always emits them. */
export interface ProgrammeCounts extends DashboardCounts {
  activities_total?: number
  activities_active?: number
}

/* ------------------------------------------------------------------------------
   Phase 6E executive metrics (FR-RPT-01/02) — scoped, de-identified aggregates.
   The backend always emits these for the executive/oversight payload; they are
   optional here so the MDA/partner dashboards (which don't render them) keep a
   valid shape, and so an older cached snapshot degrades gracefully.
   ------------------------------------------------------------------------------ */

export interface PopulationMetrics {
  total_households: number
  total_individuals: number // deduplicated (net-unique) registry
  net_unique_served: number // THE headline: distinct persons served
  new_registrations_period: number
  lgas_covered: number
  wards_covered: number
  period_days: number
}

export interface DemographicsMetrics {
  total: number
  by_gender: Record<string, number>
  gender_known: number
  female_pct: number | null // 0..1, over KNOWN genders
  age_bands: Record<string, number> // children/youth/adults/elderly/unknown
  household_vs_individual: { in_household: number; individual: number }
}

export type TrafficLight = 'green' | 'yellow' | 'red' | 'unrated'

export interface ImplementingMda {
  id: string
  name: string | null
}

/** Activity-level drill-down under a programme (scoped; shown where permitted). */
export interface ActivityPerformance {
  activity_id: string
  name: string | null
  mda: string | null
  status: string
  target: number
  reached: number // net-unique
  completion_rate: number | null // 0..1
  coverage_absolute: number
  budget: { allocated: number; spent: number; remaining: number }
  cost_per_beneficiary: number | null
  traffic_light: TrafficLight
}

export interface ProgrammePerformance {
  programme_id: string
  name: string | null
  status: string | null // draft | active | closed | archived
  mdas: ImplementingMda[]
  start_date: string | null // earliest scoped activity start
  end_date: string | null // latest scoped activity end
  target: number
  reached: number // net-unique
  completion_rate: number | null // 0..1
  coverage_absolute: number
  budget: {
    allocated: number // kobo
    spent: number
    remaining: number
    utilization_rate: number | null
  }
  cost_per_beneficiary: number | null // kobo per net-unique person
  traffic_light: TrafficLight
  activities: ActivityPerformance[]
}

/** Configured programme traffic-light thresholds (for the scoring legend). */
export interface ProgrammeScoring {
  green_min: number // completion ≥ → green
  yellow_min: number // completion ≥ → yellow (else red)
}

/** Household-size distribution — a field we HAVE (active memberships per household).
 * Banded 1 / 2-3 / 4-6 / 7+. Empty for partners (they own no households). */
export interface HouseholdSize {
  total_households: number
  households_with_members: number
  average_size: number | null
  bands: Record<string, number> // '1' | '2-3' | '4-6' | '7+'
}

export interface RegistryQuality {
  total: number
  verified: number // status: active
  pending: number // status: flagged (pending review)
  suspended: number
  duplicates_detected: number
  nin_completeness: number | null // 0..1 (hash presence only, never a value)
  phone_completeness: number | null
  data_completeness: number | null
}

export type CoverageBand = 'green' | 'yellow' | 'red'

export interface CoverageBands {
  basis: 'absolute' // banded by ABSOLUTE beneficiaries/area, never a % of population
  thresholds: { green_min: number; yellow_min: number }
  summary: { green: number; yellow: number; red: number }
  areas: Array<{ lga: string; beneficiary_count: number; band: CoverageBand }>
}

export interface TrendPoint {
  month: string // 'YYYY-MM'
  value: number
}

export interface TrendMetrics {
  months: string[]
  registrations: TrendPoint[]
  beneficiaries_cumulative: TrendPoint[]
  disbursement: TrendPoint[] // kobo/month
  programme_growth: TrendPoint[]
}

/** One development partner's contribution, scoped to the funded programmes in view. */
export interface PartnerContribution {
  partner_id: string
  name: string
  funded_programmes: number
  beneficiaries_served: number // net-unique through funded programmes
  funding_allocated: number // kobo
}

export interface CoordinationMetrics {
  active_mdas: number
  joint_programmes: number // run by ≥2 MDAs
  cross_mda_beneficiaries: number
  referral_throughput: { total: number; completed: number; completion_rate: number | null }
  request_to_serve: {
    raised: number
    accepted: number
    declined: number
    pending: number
    approval_rate: number | null // accepted ÷ decided
    avg_turnaround_hours: number | null
  }
  partners: {
    count: number
    funded_programmes: number
    beneficiaries_served: number
    funding_allocated: number
    list: PartnerContribution[]
  }
  sync_health: {
    total_runs: number
    succeeded: number
    failed: number
    last_run_at: string | null
    api_registrations: number
    connectors: number
    sources: string[]
  }
}

/** Four-state funded-programme delivery status (from completion + timeline). Never a
 * fabricated %; derived from reached ÷ target and the delivery end date. */
export type ProgrammeStatus = 'completed' | 'on_track' | 'at_risk' | 'delayed' | 'unrated'

/** OUTPUT INDICATOR (Phase 6P) — a count of INTERVENTIONS (benefit records) delivered,
 * by benefit type, with captured demographics. OUTPUTS ONLY — never an outcome index. */
export interface OutputIndicator {
  benefit_type: string
  interventions: number // benefit-record count
  beneficiaries: number // distinct persons
  women: number // recorded female
  children: number // 0–17 by DOB
}

/** Activity-level drill-down under a FUNDED programme (activity-precise; delivery value). */
export interface PartnerProgrammeActivity {
  activity_id: string
  name: string | null
  mda: string | null
  status: string
  target: number
  reached: number
  completion_rate: number | null
  coverage_absolute: number
  allocated: number
  delivered_value: number
  remaining: number
  cost_per_beneficiary: number | null
  traffic_light: TrafficLight
}

/** One FUNDED programme's results (Phase 6P "Programmes & Results"), ACTIVITY-PRECISE:
 * only the partner's funded activities count toward its budget/delivery/reach. */
export interface PartnerProgramme {
  programme_id: string
  name: string | null
  type: string | null // catalog programme type
  status: string | null // draft | active | closed | archived
  mdas: ImplementingMda[]
  start_date: string | null
  end_date: string | null
  allocated: number // committed funding (kobo)
  delivered_value: number // delivery value (kobo) — NOT expenditure
  remaining: number
  utilization_rate: number | null
  target: number
  reached: number // net-unique
  coverage_absolute: number
  completion_rate: number | null
  interventions: number // benefit-record count
  avg_benefit_value: number | null // delivered ÷ interventions
  cost_per_beneficiary: number | null // delivered ÷ net-unique
  delivery_series: TrendPoint[] // monthly delivery value (burn/delivery-rate chart)
  status_light: ProgrammeStatus
  output_indicators: OutputIndicator[]
  activities: PartnerProgrammeActivity[]
}

/** Phase 6P tab 3 "Registry" — the aggregate registry for a partner's FUNDED cohort
 * (beneficiaries enrolled in ∪ served by the funded activities). De-identified counts
 * only — never the raw registry. Demographics use CAPTURED fields only. */
export interface PartnerRegistry {
  total_individuals: number
  total_households: number
  verified: number // status: active
  pending: number // status: flagged (pending review)
  suspended: number
  duplicate_records: number // import match bands surfaced for the cohort
  new_registrations: number // registered within period_days
  updated_records: number // updated (post-creation) within period_days
  period_days: number
  demographics: {
    by_gender: Record<string, number>
    gender_known: number
    female_pct: number | null
    age_bands: Record<string, number> // children/youth/adults/elderly/unknown
    by_lga: Record<string, number>
    household_size: HouseholdSize
  }
  /** Reduced targeting funnel — the stages we HAVE. The eligible→selected steps are
   * omitted (no eligibility denominator / selection model) and rendered as an inert slot. */
  funnel: { registered: number; enrolled: number; receiving: number }
  quality: {
    verification_rate: number | null // verified ÷ total
    duplicate_rate: number | null // duplicate records ÷ total
    data_completeness: number | null // 0..1 across captured fields
    nin_linkage: number | null // % with a NIN hash
    missing: { nin: number; phone: number; date_of_birth: number; gender: number; lga: number }
  }
}

/** Phase 6P tab 4 "Coordination" — one funder in the partner's landscape. Money fields
 * are populated for the CALLER only (`is_self`); a partner never sees another funder's
 * amounts, so those are null (only the count of shared programmes is shown). */
export interface PartnerCoordinationFunder {
  partner_id: string
  name: string
  is_self: boolean
  allocated: number | null
  delivered_value: number | null
  net_unique_reached: number | null
  funded_programmes: number | null
  shared_programmes: number
}

/** A government agency (MDA) implementing activities in the funded programmes — counts
 * only, never money (an MDA's funding is not the partner's to see). */
export interface PartnerCoordinationAgency {
  id: string
  name: string | null
  activities: number
  programmes: number
}

export interface PartnerCoordination {
  landscape: { funders: number; government_agencies: number; implementing_agencies: number }
  funding_by_partner: PartnerCoordinationFunder[]
  agencies: PartnerCoordinationAgency[]
  data_sharing: {
    agencies_integrated: number
    connectors: number
    sources: string[]
    total_runs: number
    succeeded: number
    failed: number
    last_run_at: string | null
    api_registrations: number
  }
}

/** Phase 6P — activity-precise funding aggregates for a Development Partner. Money is
 * DELIVERED VALUE (recorded value of benefits delivered under funded activities), NOT
 * treasury expenditure. Present only on a partner-scoped payload. */
export interface PartnerFunding {
  allocated: number // committed funding (kobo)
  delivered_value: number // value delivered to beneficiaries (kobo) — not expenditure
  remaining: number
  utilization_rate: number | null
  funded_programmes: number
  funded_activities: number
  active_activities: number
  implementing_mdas: number
  lgas_covered: number
  wards_covered: number
  net_unique_reached: number
  target: number
  reach_vs_target: number | null
  cost_per_beneficiary: number | null // kobo per net-unique beneficiary
  reach: { households_reached: number; women_reached: number; children_reached: number }
  coverage_bands: CoverageBands
  funding_by_partner: {
    partner_id: string
    name: string
    allocated: number
    delivered_value: number
    net_unique_reached: number
    funded_programmes: number
  }[]
  programme_overlap: {
    count: number
    cells: { programme_id: string; programme: string | null; lga: string; other_funders: number; other_mdas: number }[]
  }
  // ---- Phase 6P tab 2 "Programmes & Results" (absorbs M&E/outputs) ----
  programmes: PartnerProgramme[] // per funded programme, activity-precise
  output_indicators: OutputIndicator[] // OUTPUTS ONLY, rolled up across funded programmes
  // ---- Phase 6P tab 3 "Registry" (funded-programme beneficiaries) ----
  registry: PartnerRegistry
  // ---- Phase 6P tab 4 "Coordination" (landscape, funding-by-partner, data sharing) ----
  coordination: PartnerCoordination
}

export interface DashboardMetrics {
  registry: {
    beneficiaries: {
      total: number
      by_status: Record<string, number>
      by_source: Record<string, number>
      by_lga: Record<string, number>
    }
    households: { total: number; by_lga: Record<string, number> } | null
  }
  programmes: ProgrammeCounts
  duplicates: {
    matches_surfaced: number
    resolved_new: number
    resolved_served: number
    resolved_skipped: number
  } | null
  benefits: {
    disbursed: BenefitTotals
    budget: BudgetSummary
    by_type: BenefitTypeGroup[]
  }
  referrals: {
    total: number
    by_status: Record<string, number>
    completed: number
    completion_rate: number | null
    overdue: number
    avg_completion_days: number | null
  } | null
  grievances: {
    total: number
    by_status: Record<string, number>
    sla_breaches: number
    avg_resolution_days: number | null
  } | null
  coverage: CoverageRow[]

  // ---- Phase 6E executive metrics (optional; see note above) ----
  population?: PopulationMetrics
  demographics?: DemographicsMetrics
  household_size?: HouseholdSize
  programme_performance?: ProgrammePerformance[]
  programme_scoring?: ProgrammeScoring
  registry_quality?: RegistryQuality
  coordination?: CoordinationMetrics | null
  partner_funding?: PartnerFunding | null
  coverage_bands?: CoverageBands
  trends?: TrendMetrics
  deferred?: Record<string, null>
}

/** The cross-cutting filter — sent as query params and echoed back. All fields
 * optional/nullable; an empty filter serves the fast (unfiltered) snapshot. */
export interface DashboardFilterValue {
  year: number | null
  quarter: number | null
  month: number | null
  programme_id: string | null
  lga: string | null
  ward: string | null
  mda_id: string | null
}

export const EMPTY_FILTER: DashboardFilterValue = {
  year: null,
  quarter: null,
  month: null,
  programme_id: null,
  lga: null,
  ward: null,
  mda_id: null,
}

/** The in-scope universe of filter options (so the UI only offers what the caller sees). */
export interface FilterOptions {
  programmes: { id: string; name: string }[]
  mdas: { id: string; name: string }[]
  lgas: string[]
  wards: string[]
  years: number[]
}

/** Oversight tier — statewide (Governor/Executive), operational (MDA), or partner. */
export type ScopeTier = 'statewide' | 'operational' | 'partner'

/** Drill-down: jump to a tab, optionally applying a scoped filter patch. The patch
 * flows through the same filter machinery, so a drill can only ever narrow in scope. */
export type DrillFn = (tab: string, patch?: Partial<DashboardFilterValue>) => void

export interface DashboardResponse {
  scope: { kind: string; label: string; tier?: ScopeTier }
  /** True when the metrics were recomputed live for an active filter. */
  live?: boolean
  filters?: DashboardFilterValue
  filter_options?: FilterOptions
  computed_at: string
  /**
   * Small-group suppression threshold for AGGREGATE tiers, published by the server so
   * the client never carries its own copy of a DPO-owned number. `null` on the
   * operational tier, where an MDA already holds the records it is counting.
   */
  min_cell_size?: number | null
  metrics: DashboardMetrics
}

/** Operational health snapshot for the admin dashboard (GET /health/metrics). */
export interface OpsMetricsResponse {
  time: string
  backups: {
    last_success_at: string | null
    age_hours: number | null
    rpo_hours: number
  }
  dashboard_snapshots: {
    last_computed_at: string | null
    stale_minutes: number | null
  }
  volumes: {
    beneficiaries: number
    benefits: number
    audit_entries: number
  }
}
