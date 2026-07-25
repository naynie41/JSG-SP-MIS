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

export interface CoordinationMetrics {
  active_mdas: number
  cross_mda_beneficiaries: number
  referral_throughput: { total: number; completed: number; completion_rate: number | null }
  request_to_serve: { raised: number; accepted: number; declined: number; pending: number }
  partners: { count: number; funded_programmes: number; beneficiaries_served: number; funding_allocated: number }
  sync_health: {
    total_runs: number
    succeeded: number
    failed: number
    last_run_at: string | null
    api_registrations: number
  }
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
  coverage_bands?: CoverageBands
  trends?: TrendMetrics
  deferred?: Record<string, null>
}

export interface DashboardResponse {
  scope: { kind: string; label: string }
  computed_at: string
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
