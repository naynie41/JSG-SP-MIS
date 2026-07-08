export interface NotificationRelated {
  type: string // backend morph alias, e.g. referral, grievance, service_request
  id: string
}

export interface AppNotification {
  id: string
  type: string // e.g. referral.accepted, grievance.assigned, service_request.created
  subject: string
  body: string
  payload: Record<string, unknown> | null
  related: NotificationRelated | null
  read_at: string | null
  created_at: string | null
}
