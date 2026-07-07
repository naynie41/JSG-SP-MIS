import type { SelectOption } from '@/components/Field/SelectField'
import type { ReferralStatus } from './types'

export const REFERRAL_STATUS_LABELS: Record<ReferralStatus, string> = {
  created: 'Created',
  accepted: 'Accepted',
  rejected: 'Rejected',
  more_info_requested: 'More info requested',
  in_progress: 'In progress',
  completed: 'Completed',
  closed: 'Closed',
}

/** Status filter for the referral tables (empty = all). */
export const REFERRAL_STATUS_FILTER: SelectOption[] = [
  { value: '', label: 'All statuses' },
  ...(Object.entries(REFERRAL_STATUS_LABELS) as [ReferralStatus, string][]).map(([value, label]) => ({ value, label })),
]

/** A common need taxonomy for the raise-referral form (free text also allowed). */
export const REFERRAL_NEED_OPTIONS: SelectOption[] = [
  { value: 'Cash transfer', label: 'Cash transfer' },
  { value: 'Health service', label: 'Health service' },
  { value: 'Education support', label: 'Education support' },
  { value: 'Nutrition support', label: 'Nutrition support' },
  { value: 'Livelihood / skills', label: 'Livelihood / skills' },
  { value: 'Protection / GBV', label: 'Protection / GBV' },
  { value: 'Disability support', label: 'Disability support' },
  { value: 'Other', label: 'Other' },
]
