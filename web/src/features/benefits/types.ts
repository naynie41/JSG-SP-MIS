export type BenefitStatus = 'recorded' | 'verified' | 'reversed'
export type VerificationMethod = 'none' | 'field_confirmation' | 'signature' | 'otp' | 'biometric'
export type BenefitFlagStatus = 'open' | 'confirmed' | 'dismissed'

export interface Benefit {
  id: string
  beneficiary_id: string
  programme_id: string
  activity_id: string | null
  enrollment_id: string | null
  mda_id: string
  benefit_type: string
  quantity: string | null
  unit: string | null
  monetary_value: number | null // minor units (kobo); data only, no money moved
  funding_source: string | null
  delivery_date: string
  lga: string | null
  ward: string | null
  status: BenefitStatus
  verification_method: VerificationMethod
  verification_reference: string | null
  verified_by: string | null
  verified_at: string | null
  recorded_by: string | null
  notes: string | null
  created_at: string | null
}

export interface BenefitInput {
  beneficiary_id: string
  programme_id: string
  activity_id?: string
  benefit_type: string
  quantity?: number
  unit?: string
  monetary_value?: number // kobo
  funding_source?: string
  delivery_date: string
  verification_method?: VerificationMethod
  verification_reference?: string
  notes?: string
}

export interface AggregateGroup {
  key: string | null
  benefit_count: number
  total_value: number
  total_quantity: string
}

export interface Aggregate {
  group_by: string
  groups: AggregateGroup[]
  totals: { benefit_count: number; total_value: number; total_quantity: string }
}

export type ImportStatus = 'pending' | 'processing' | 'preview_ready' | 'committing' | 'completed' | 'failed'

export interface BenefitImportRow {
  row_number: number
  is_valid: boolean
  errors: { field: string; message: string }[]
  eligibility_flagged: boolean
  resolved_beneficiary_id: string | null
  benefit_id: string | null
  delivery: Record<string, unknown>
}

export interface BenefitImportBatch {
  id: string
  mda_id: string
  activity_id: string
  programme_id: string
  original_filename: string
  source: string
  status: ImportStatus
  summary: { total_rows: number; valid_rows: number; invalid_rows: number; committed_rows: number }
  error: string | null
  rows?: BenefitImportRow[]
  created_at: string | null
  updated_at: string | null
}

export interface BenefitFlag {
  id: string
  beneficiary_id: string
  benefit_id: string
  related_benefit_id: string
  benefit_type: string
  from_mda_id: string
  other_mda_id: string
  status: BenefitFlagStatus
  reason: string
  reviewed_by: string | null
  reviewed_at: string | null
  review_note: string | null
  created_at: string | null
}
