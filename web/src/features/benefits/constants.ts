import type { SelectOption } from '@/components/Field/SelectField'

export const BENEFIT_TYPE_OPTIONS: SelectOption[] = [
  { value: 'cash', label: 'Cash' },
  { value: 'food', label: 'Food' },
  { value: 'agricultural_input', label: 'Agricultural input' },
  { value: 'training', label: 'Training' },
  { value: 'health', label: 'Health' },
  { value: 'education', label: 'Education' },
  { value: 'cash_for_work', label: 'Cash for work' },
  { value: 'other', label: 'Other' },
]

export const BENEFIT_TYPE_LABELS: Record<string, string> = Object.fromEntries(BENEFIT_TYPE_OPTIONS.map((o) => [o.value, o.label]))

/** Verification methods (FR-BEN-04). OTP + biometric are stubbed as unavailable. */
export const VERIFICATION_METHOD_OPTIONS: SelectOption[] = [
  { value: 'none', label: 'None (record only)' },
  { value: 'field_confirmation', label: 'Field confirmation' },
  { value: 'signature', label: 'Signature' },
  { value: 'otp', label: 'OTP — unavailable', disabled: true },
  { value: 'biometric', label: 'Biometric — unavailable', disabled: true },
]

export const BENEFIT_STATUS_LABELS: Record<string, string> = {
  recorded: 'Recorded',
  verified: 'Verified',
  reversed: 'Reversed',
}

export const AGGREGATE_DIMENSION_OPTIONS: SelectOption[] = [
  { value: 'programme', label: 'Programme' },
  { value: 'mda', label: 'Delivering MDA' },
  { value: 'lga', label: 'LGA' },
  { value: 'ward', label: 'Ward' },
  { value: 'benefit_type', label: 'Benefit type' },
]
