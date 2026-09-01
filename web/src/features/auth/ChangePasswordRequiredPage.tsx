import { useState } from 'react'
import type { FormEvent } from 'react'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { TextField } from '@/components/Field/TextField'
import { authApi } from '@/lib/api/authApi'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ApiError } from '@/types/api'
import layout from '@/features/shared/formLayout.module.css'
import styles from './ChangePasswordRequired.module.css'

/**
 * Shown when an administrator has issued a temporary password (FR-UAM-06).
 *
 * ProtectedRoute redirects here and nowhere else while `must_change_password` is
 * set, and the API independently refuses every other route with
 * PASSWORD_CHANGE_REQUIRED — so this page is the only way forward, by design.
 *
 * Separate from the settings page's change-password card because the situation is
 * different: the user did not choose to be here, the "current password" is one
 * somebody handed them, and there is nowhere else to navigate.
 */
export function ChangePasswordRequiredPage() {
  const { user, logout } = useAuth()

  const [current, setCurrent] = useState('')
  const [next, setNext] = useState('')
  const [confirm, setConfirm] = useState('')
  const [error, setError] = useState<string | null>(null)
  const [saving, setSaving] = useState(false)

  async function submit(event: FormEvent) {
    event.preventDefault()
    setError(null)

    if (next !== confirm) {
      setError('The new password and its confirmation do not match.')
      return
    }

    setSaving(true)
    try {
      await authApi.changePassword(current, next)
      // The server invalidates every token on change, so end the session here and
      // let them sign in with the password only they now know.
      await logout()
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not change your password.')
    } finally {
      setSaving(false)
    }
  }

  return (
    <div className={styles.page}>
      <div className={styles.card}>
        <Card titleAs="h2" title="Choose a new password" eyebrow="Security">
          <p className={styles.intro}>
            {user?.name ? `${user.name}, an` : 'An'} administrator gave this account a
            temporary password. Choose one only you know before continuing.
          </p>

          <form className={layout.form} onSubmit={submit}>
            {error && (
              <p className={layout.alert} role="alert">
                {error}
              </p>
            )}

            <TextField
              label="Temporary password"
              type="password"
              autoComplete="current-password"
              required
              value={current}
              onChange={(event) => setCurrent(event.target.value)}
            />
            <TextField
              label="New password"
              type="password"
              autoComplete="new-password"
              required
              helper="At least 12 characters, and not a password known to have been breached."
              value={next}
              onChange={(event) => setNext(event.target.value)}
            />
            <TextField
              label="Confirm new password"
              type="password"
              autoComplete="new-password"
              required
              value={confirm}
              onChange={(event) => setConfirm(event.target.value)}
            />

            <Button type="submit" disabled={saving}>
              {saving ? 'Saving…' : 'Set password and sign in again'}
            </Button>
          </form>
        </Card>
      </div>
    </div>
  )
}

export default ChangePasswordRequiredPage
