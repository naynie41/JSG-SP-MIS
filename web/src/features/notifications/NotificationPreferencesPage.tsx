import { Card } from '@/components/Card/Card'
import { Toggle } from '@/components/Field/Toggle'
import { useNotificationPreferences, useUpdateNotificationPreferences } from './hooks'
import layout from '@/features/shared/formLayout.module.css'
import styles from './notifications.module.css'

/**
 * Notification preferences at a real URL (FR-NOT-02).
 *
 * The same toggle lives in the notification bell, which is fine while you are already
 * in the app. It is not enough for someone arriving from an EMAIL: every notification
 * message has to say how to stop receiving them, and "open the app and find the bell"
 * is not an instruction that survives an inbox. Preferences are this system's
 * unsubscribe mechanism, so they need an address — `notifications.preferences_path` on
 * the API points here.
 *
 * In-app notifications are deliberately absent from this screen. The bell is the inbox
 * and the system of record for what a user was told; turning it off would let an
 * approver make a request-to-serve invisible to themselves and leave a beneficiary
 * unserved with no trace of why.
 */
export function NotificationPreferencesPage() {
  const prefs = useNotificationPreferences()
  const update = useUpdateNotificationPreferences()

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">Account</span>
          <h1 className="t-h1">Notification preferences</h1>
          <p className={styles.note}>
            Choose how SP-MIS reaches you. These settings apply to your account only.
          </p>
        </div>
      </div>

      <Card title="Email" titleAs="h2">
        <div className={styles.prefRow}>
          <div>
            <p className={styles.prefLabel}>Email notifications</p>
            <p className={styles.note}>
              Sent when something needs your decision — a request to serve one of your
              beneficiaries, or a decision on a request you raised. Emails never contain
              beneficiary details; they link you here to review the record.
            </p>
          </div>
          <Toggle
            label="Email notifications"
            hideLabel
            checked={prefs.data?.email_enabled ?? true}
            disabled={prefs.isLoading || update.isPending}
            onChange={(event) => update.mutate(event.target.checked)}
          />
        </div>
      </Card>

      <Card title="In-app" titleAs="h2">
        <p className={styles.note}>
          Notifications always appear in the bell in the top bar. This is your record of
          what you were told and when, so it cannot be switched off.
        </p>
      </Card>
    </div>
  )
}
