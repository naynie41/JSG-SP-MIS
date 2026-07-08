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
  programmes: DashboardCounts
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
}

export interface DashboardResponse {
  scope: { kind: string; label: string }
  computed_at: string
  metrics: DashboardMetrics
}
