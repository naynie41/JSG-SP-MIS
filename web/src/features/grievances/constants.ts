import type { SelectOption } from '@/components/Field/SelectField'
import type { GrievanceCategory, GrievanceChannel, GrievanceStatus } from './types'

export const GRIEVANCE_CATEGORY_LABELS: Record<GrievanceCategory, string> = {
  payment: 'Payment',
  eligibility: 'Eligibility',
  data_correction: 'Data correction',
  service_quality: 'Service quality',
  complaint: 'Complaint',
  other: 'Other',
}

export const GRIEVANCE_CHANNEL_LABELS: Record<GrievanceChannel, string> = {
  walk_in: 'Walk-in',
  phone: 'Phone',
  email: 'Email',
  sms: 'SMS',
  online: 'Online',
  other: 'Other',
}

export const GRIEVANCE_STATUS_LABELS: Record<GrievanceStatus, string> = {
  open: 'Open',
  assigned: 'Assigned',
  in_progress: 'In progress',
  resolved: 'Resolved',
  closed: 'Closed',
}

const toOptions = <T extends string>(labels: Record<T, string>): SelectOption[] =>
  (Object.entries(labels) as [T, string][]).map(([value, label]) => ({ value, label }))

export const GRIEVANCE_CATEGORY_OPTIONS = toOptions(GRIEVANCE_CATEGORY_LABELS)
export const GRIEVANCE_CHANNEL_OPTIONS = toOptions(GRIEVANCE_CHANNEL_LABELS)

/** Filters (empty = all). */
export const GRIEVANCE_STATUS_FILTER: SelectOption[] = [{ value: '', label: 'All statuses' }, ...toOptions(GRIEVANCE_STATUS_LABELS)]
export const GRIEVANCE_CATEGORY_FILTER: SelectOption[] = [{ value: '', label: 'All categories' }, ...GRIEVANCE_CATEGORY_OPTIONS]
