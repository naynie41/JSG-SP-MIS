export type Gender = 'male' | 'female' | 'other'
export type BeneficiaryStatus = 'active' | 'suspended' | 'flagged'
export type HouseholdRole = 'head' | 'spouse' | 'child' | 'dependent' | 'other'
export type ImportStatus = 'pending' | 'processing' | 'preview_ready' | 'committing' | 'completed' | 'failed'

export interface Beneficiary {
  id: string
  owner_mda_id: string
  first_name: string
  middle_name: string | null
  last_name: string
  full_name: string
  nin: string | null
  bvn: string | null
  phone: string | null
  date_of_birth: string | null
  gender: Gender | null
  address: string | null
  lga: string | null
  ward: string | null
  registration_source: string
  registration_date: string
  import_batch_id: string | null
  original_record_id: string | null
  status: BeneficiaryStatus
  current_household?: { household_id: string; role_in_household: HouseholdRole; joined_at: string } | null
  created_at: string | null
  updated_at: string | null
}

export interface BeneficiaryInput {
  first_name: string
  middle_name?: string
  last_name: string
  nin?: string
  bvn?: string
  phone?: string
  date_of_birth: string
  gender: Gender
  address?: string
  lga: string
  ward: string
}

/** Reveal-only projection returned by the cross-MDA lookup/serve path (FR-OWN-03). */
export interface RevealMatch {
  id: string
  full_name: string
  owner_mda: { id: string; name: string } | null
  registration_source: string
  registration_date: string
  lga: string | null
  ward: string | null
  status: BeneficiaryStatus
}

export interface HouseholdMembership {
  id: string
  household_id: string
  beneficiary_id: string
  beneficiary_name?: string
  role_in_household: HouseholdRole
  joined_at: string
  left_at: string | null
  is_open: boolean
}

export interface Household {
  id: string
  owner_mda_id: string
  head_beneficiary_id: string | null
  registration_source: string
  registration_date: string
  address: string | null
  lga: string | null
  ward: string | null
  members: HouseholdMembership[]
  history?: HouseholdMembership[]
  created_at: string | null
  updated_at: string | null
}

export interface HouseholdInput {
  lga: string
  ward: string
  address?: string
  members?: { beneficiary_id: string; role_in_household: HouseholdRole }[]
  head_beneficiary_id?: string
}

export interface ImportRow {
  row_number: number
  original_record_id: string | null
  is_valid: boolean
  errors: { field: string; message: string }[]
  beneficiary_id: string | null
  preview: {
    first_name: string | null
    last_name: string | null
    nin: string | null
    bvn: string | null
    phone: string | null
    date_of_birth: string | null
    gender: string | null
    lga: string | null
    ward: string | null
  }
}

export interface ImportBatch {
  id: string
  owner_mda_id: string
  uploaded_by: string | null
  original_filename: string
  source: string
  status: ImportStatus
  summary: {
    total_rows: number
    valid_rows: number
    invalid_rows: number
    committed_rows: number
  }
  error: string | null
  rows?: ImportRow[]
  created_at: string | null
  updated_at: string | null
}

export interface BeneficiaryDocument {
  id: string
  beneficiary_id: string
  document_type: string
  original_filename: string
  mime_type: string
  size_bytes: number
  checksum_sha256: string
  uploaded_by: string | null
  created_at: string | null
}
