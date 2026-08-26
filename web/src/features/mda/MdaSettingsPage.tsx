import { useState } from 'react'
import { KeyRound, Mail, ShieldCheck, UserCircle } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Tabs } from '@/components/Tabs/Tabs'
import { TextField } from '@/components/Field/TextField'
import { Toggle } from '@/components/Field/Toggle'
import { useToast } from '@/components/Toast/ToastProvider'
import { useAuth } from '@/lib/auth/AuthProvider'
import { authApi } from '@/lib/api/authApi'
import { ApiError } from '@/types/api'
import {
  useNotificationPreferences,
  useUpdateNotificationPreferences,
} from '@/features/notifications/hooks'
import layout from '@/features/shared/formLayout.module.css'
import { titleCase, when as formatWhen } from './format'
import styles from './mda.module.css'

/* -------------------------------------------------------------------- profile */

/**
 * Profile is READ-ONLY, deliberately.
 *
 * There is no self-service profile endpoint: `/auth/me` is a GET, and the only write
 * path for a user record is `PATCH /users/{user}` behind `user.edit` — a System
 * Administrator capability that no MDA role holds, since FR-UAM-01 centralised account
 * administration. Rendering an editable form here would either fabricate an endpoint or
 * quietly fail for every user of this console, so it shows what the account is and says
 * who can change it.
 */
function ProfilePanel() {
  const { user } = useAuth()

  return (
    <div className={styles.section}>
      <Card titleAs="h3" title="Your account" eyebrow="Profile">
        <dl className={styles.defList}>
          <dt>Name</dt>
          <dd>{user?.name ?? '—'}</dd>
          <dt>Email</dt>
          <dd>{user?.email ?? '—'}</dd>
          <dt>Role</dt>
          <dd>{user?.role?.name ?? '—'}</dd>
          <dt>MDA</dt>
          <dd>{user?.mda?.name ?? '—'}</dd>
          <dt>Account status</dt>
          <dd>
            <Badge variant={user?.status === 'active' ? 'success' : 'warning'} dot>
              {titleCase(user?.status)}
            </Badge>
          </dd>
          <dt>Last sign-in</dt>
          <dd className={styles.mono}>{formatWhen(user?.last_login_at, { year: true, absent: 'never' })}</dd>
        </dl>
        <p className={styles.footnote}>
          Your name, email, role and MDA are maintained by an administrator — ask them to correct anything here. Your
          role determines what you can do; your MDA determines what you can see.
        </p>
      </Card>
    </div>
  )
}

/* ---------------------------------------------------------------- preferences */

/** Notification preferences — the existing `/notifications/preferences` capability. */
function PreferencesPanel() {
  const prefs = useNotificationPreferences()
  const update = useUpdateNotificationPreferences()

  return (
    <div className={styles.section}>
      <Card titleAs="h3" title="Notification preferences" eyebrow="Preferences">
        <div className={styles.choiceRow}>
          <Toggle
            label="Email notifications"
            checked={prefs.data?.email_enabled ?? true}
            disabled={prefs.isLoading || update.isPending}
            onChange={(event) => update.mutate(event.target.checked)}
          />
        </div>
        <p className={styles.queueNote}>
          <Icon icon={Mail} size={14} /> When this is off you still receive everything in the bell — only the email
          copy stops. Notifications about work waiting on you are never suppressed entirely.
        </p>
        <p className={styles.footnote}>
          This is the same preference the bell&apos;s toggle sets — one setting, two places to reach it
        </p>
      </Card>
    </div>
  )
}

/* -------------------------------------------------------------------- security */

/**
 * Password change and MFA state. Both are the existing auth endpoints:
 * `POST /auth/password` (verifies the current password, applies the policy, then
 * invalidates the session) and `POST /auth/mfa/disable` (refused for a role whose MFA is
 * mandatory — the server decides, and this page reports what it decided).
 *
 * First-time MFA *enrolment* is not reachable from here: it runs on the login flow behind
 * a short-lived setup token, so a signed-in session has no way to start it. Said plainly
 * rather than offered as a control that could not work.
 */
function SecurityPanel() {
  const { user, logout } = useAuth()
  const toast = useToast()

  const [current, setCurrent] = useState('')
  const [next, setNext] = useState('')
  const [confirm, setConfirm] = useState('')
  const [error, setError] = useState<string | null>(null)
  const [saving, setSaving] = useState(false)

  const [mfaCode, setMfaCode] = useState('')
  const [mfaError, setMfaError] = useState<string | null>(null)
  const [disabling, setDisabling] = useState(false)

  const mfaEnabled = user?.mfa_enabled ?? false
  const mfaRequired = user?.mfa_required ?? false

  async function submitPassword() {
    setError(null)
    if (next !== confirm) {
      setError('The new password and its confirmation do not match.')
      return
    }
    setSaving(true)
    try {
      await authApi.changePassword(current, next)
      toast.success('Password updated', 'Please sign in again with your new password.')
      // The server invalidates the session on change, so end it here rather than
      // leaving the user on a page whose next request will 401.
      await logout()
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not change your password.')
    } finally {
      setSaving(false)
    }
  }

  async function submitDisableMfa() {
    setMfaError(null)
    setDisabling(true)
    try {
      await authApi.mfaDisable(mfaCode)
      toast.success('Two-factor authentication turned off')
      setMfaCode('')
    } catch (err) {
      setMfaError(err instanceof ApiError ? err.message : 'Could not turn off two-factor authentication.')
    } finally {
      setDisabling(false)
    }
  }

  return (
    <div className={styles.section}>
      <Card titleAs="h3" title="Change your password" eyebrow="Security">
        <div className={layout.form}>
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          <TextField
            label="Current password"
            type="password"
            autoComplete="current-password"
            value={current}
            onChange={(event) => setCurrent(event.target.value)}
          />
          <div className={layout.grid2}>
            <TextField
              label="New password"
              type="password"
              autoComplete="new-password"
              value={next}
              onChange={(event) => setNext(event.target.value)}
              helper="At least 12 characters, with upper and lower case, a number and a symbol."
            />
            <TextField
              label="Confirm new password"
              type="password"
              autoComplete="new-password"
              value={confirm}
              onChange={(event) => setConfirm(event.target.value)}
            />
          </div>
          <div>
            <Button
              leftIcon={KeyRound}
              loading={saving}
              disabled={current === '' || next === '' || confirm === ''}
              onClick={submitPassword}
            >
              Change password
            </Button>
          </div>
          <p className={styles.queueNote}>
            Changing your password signs you out everywhere. You will be asked to sign in again straight away.
          </p>
        </div>
      </Card>

      <Card titleAs="h3" title="Two-factor authentication" eyebrow="Security">
        <div className={styles.choiceRow}>
          <Badge variant={mfaEnabled ? 'success' : 'warning'} dot>
            {mfaEnabled ? 'Enabled' : 'Not enabled'}
          </Badge>
          {mfaRequired && <Badge variant="neutral" dot>Required for your role</Badge>}
        </div>

        {mfaRequired ? (
          <p className={styles.queueNote}>
            <Icon icon={ShieldCheck} size={14} /> Your role requires two-factor authentication, so it cannot be turned
            off. If you need to move it to a new device, an administrator can reset your enrolment.
          </p>
        ) : mfaEnabled ? (
          <div className={layout.form}>
            {mfaError && (
              <p className={layout.alert} role="alert">
                {mfaError}
              </p>
            )}
            <TextField
              label="Authentication code"
              inputMode="numeric"
              autoComplete="one-time-code"
              value={mfaCode}
              onChange={(event) => setMfaCode(event.target.value)}
              helper="Confirm with a current code from your authenticator app."
            />
            <div>
              <Button variant="danger" loading={disabling} disabled={mfaCode.trim() === ''} onClick={submitDisableMfa}>
                Turn off two-factor authentication
              </Button>
            </div>
          </div>
        ) : (
          <p className={styles.queueNote}>
            <Icon icon={ShieldCheck} size={14} /> Two-factor authentication is set up when you sign in, not from here.
            An administrator can require it for your account.
          </p>
        )}

        <p className={styles.footnote}>
          Whether two-factor authentication may be turned off is decided by your role on the server — this page reports
          that decision rather than making it
        </p>
      </Card>
    </div>
  )
}

/* ---------------------------------------------------------------------- page */

/**
 * Settings — reached from the gear in the header, deliberately NOT one of the six
 * navigation modules. The rail is for the MDA's work; this is the signed-in user's own
 * account, which is a different kind of thing and does not belong beside Programmes and
 * Beneficiaries (DESIGN.md §5.12).
 *
 * Every panel composes an EXISTING capability — `/auth/me` for the profile,
 * `/auth/password` and `/auth/mfa/disable` for security, `/notifications/preferences`
 * for the email toggle. There is no settings store of its own, and nothing here writes
 * MDA or platform configuration: an MDA user configures their own account, not the
 * system.
 */
export function MdaSettingsPage() {
  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Settings</h1>
        <p className={styles.lead}>
          Your own account and how you are notified. Nothing here changes your MDA&apos;s data or what your colleagues
          can do — that is an administrator&apos;s job.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'profile', label: 'Profile', content: <ProfilePanel /> },
          { id: 'preferences', label: 'Preferences', content: <PreferencesPanel /> },
          { id: 'security', label: 'Security', content: <SecurityPanel /> },
        ]}
      />

      <section className={styles.section} aria-label="About your account">
        <div className={styles.sectionHead}>
          <Icon icon={UserCircle} size={16} />
          <h2 className={styles.sectionTitle}>What you can change here</h2>
        </div>
        <Card>
          <p className={styles.muted}>
            You control your password, your two-factor authentication where your role allows it, and whether
            notifications reach you by email. Your name, role and MDA are set by an administrator, because they
            determine what you can see and do.
          </p>
        </Card>
      </section>
    </div>
  )
}
