import type { SelectOption } from '@/components/Field/SelectField'

export const PROGRAMME_TYPE_OPTIONS: SelectOption[] = [
  { value: 'individual', label: 'Individual' },
  { value: 'household', label: 'Household' },
]

export const PROGRAMME_STATUS_OPTIONS: SelectOption[] = [
  { value: 'draft', label: 'Draft' },
  { value: 'active', label: 'Active' },
  { value: 'closed', label: 'Closed' },
]

export const ACTIVITY_STATUS_OPTIONS: SelectOption[] = [
  { value: 'draft', label: 'Draft' },
  { value: 'active', label: 'Active' },
  { value: 'completed', label: 'Completed' },
]

export const ENROLLMENT_STATUS_OPTIONS: SelectOption[] = [
  { value: 'enrolled', label: 'Enrolled' },
  { value: 'suspended', label: 'Suspended' },
  { value: 'exited', label: 'Exited' },
  { value: 'graduated', label: 'Graduated' },
]

/** Structured eligibility attributes the backend evaluator understands (FR-PRG-03). */
export const ELIGIBILITY_ATTRIBUTE_OPTIONS: SelectOption[] = [
  { value: 'lga', label: 'LGA' },
  { value: 'ward', label: 'Ward' },
  { value: 'gender', label: 'Gender' },
  { value: 'status', label: 'Beneficiary status' },
  { value: 'age_min', label: 'Minimum age' },
  { value: 'age_max', label: 'Maximum age' },
]
