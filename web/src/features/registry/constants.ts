import type { SelectOption } from '@/components/Field/SelectField'

/** Canonical option lists mirroring the backend enums (App\Domain\Registry\Enums). */

const LGA_VALUES = [
  'auyo', 'babura', 'biriniwa', 'birnin_kudu', 'buji', 'dutse', 'gagarawa', 'garki',
  'gumel', 'guri', 'gwaram', 'gwiwa', 'hadejia', 'jahun', 'kafin_hausa', 'kaugama',
  'kazaure', 'kiri_kasama', 'kiyawa', 'maigatari', 'malam_madori', 'miga', 'ringim',
  'roni', 'sule_tankarkar', 'taura', 'yankwashi',
] as const

export function titleCase(value: string): string {
  return value
    .split('_')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(' ')
}

export const LGA_OPTIONS: SelectOption[] = LGA_VALUES.map((value) => ({ value, label: titleCase(value) }))

export const GENDER_OPTIONS: SelectOption[] = [
  { value: 'male', label: 'Male' },
  { value: 'female', label: 'Female' },
  { value: 'other', label: 'Other' },
]

export const STATUS_OPTIONS: SelectOption[] = [
  { value: 'active', label: 'Active' },
  { value: 'suspended', label: 'Suspended' },
  { value: 'flagged', label: 'Flagged' },
]

export const HOUSEHOLD_ROLE_OPTIONS: SelectOption[] = [
  { value: 'head', label: 'Head' },
  { value: 'spouse', label: 'Spouse' },
  { value: 'child', label: 'Child' },
  { value: 'dependent', label: 'Dependent' },
  { value: 'other', label: 'Other' },
]

export const DOCUMENT_TYPE_OPTIONS: SelectOption[] = [
  { value: 'national_id', label: 'National ID' },
  { value: 'birth_certificate', label: 'Birth certificate' },
  { value: 'proof_of_residence', label: 'Proof of residence' },
  { value: 'passport_photo', label: 'Passport photo' },
  { value: 'attestation', label: 'Attestation' },
  { value: 'other', label: 'Other' },
]

export const REGISTRATION_SOURCE_LABELS: Record<string, string> = {
  manual: 'Manual entry',
  excel: 'Excel upload',
  csv: 'CSV upload',
  kobo: 'Kobo Collect',
  odk: 'ODK',
  api: 'REST API',
  socu: 'SOCU',
  government_system: 'Government system',
}

export const IMPORT_STATUS_LABELS: Record<string, string> = {
  pending: 'Pending',
  processing: 'Processing',
  preview_ready: 'Preview ready',
  committing: 'Committing',
  completed: 'Completed',
  failed: 'Failed',
}
