import { useState } from 'react'
import { Share2 } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { useUsers } from '@/features/users/hooks'
import { useMdas } from '@/features/mdas/hooks'
import { useCreateGrant } from './hooks'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './access.module.css'

interface Props {
  open: boolean
  onClose: () => void
}

function errorMessage(err: unknown): string {
  if (err instanceof ApiError) {
    return err.details?.[0]?.message ?? err.message
  }
  return 'Could not grant access. Please try again.'
}

/**
 * Grant a user cross-MDA read access to another MDA's scoped data (FR-UAM-03,
 * FR-DSH-01). Every grant is audited (who, why, until when) server-side.
 */
export function GrantFormModal({ open, onClose }: Props) {
  const users = useUsers(open)
  const mdas = useMdas(open)
  const create = useCreateGrant()

  const [userId, setUserId] = useState('')
  const [mdaId, setMdaId] = useState('')
  const [reason, setReason] = useState('')
  const [expiresAt, setExpiresAt] = useState('')
  const [error, setError] = useState<string | null>(null)

  function reset() {
    setUserId('')
    setMdaId('')
    setReason('')
    setExpiresAt('')
    setError(null)
  }

  async function submit() {
    setError(null)
    try {
      await create.mutateAsync({
        user_id: userId,
        mda_id: mdaId,
        reason: reason.trim() || undefined,
        expires_at: expiresAt || undefined,
      })
      reset()
      onClose()
    } catch (err) {
      setError(errorMessage(err))
    }
  }

  return (
    <Modal
      open={open}
      onClose={onClose}
      title="Grant cross-MDA access"
      footer={
        <>
          <Button variant="tertiary" onClick={onClose}>Cancel</Button>
          <Button leftIcon={Share2} onClick={submit} loading={create.isPending} disabled={!userId || !mdaId}>
            Grant access
          </Button>
        </>
      }
    >
      <div className={styles.stack}>
        {error && <p className={formStyles.alert} role="alert">{error}</p>}

        <SelectField
          label="User"
          placeholder="Select a user"
          options={(users.data ?? []).map((u) => ({ value: u.id, label: `${u.name} — ${u.email}` }))}
          value={userId}
          onChange={(e) => setUserId(e.target.value)}
          required
        />
        <SelectField
          label="MDA to grant access to"
          placeholder="Select an MDA"
          options={(mdas.data ?? []).map((m) => ({ value: m.id, label: m.name }))}
          value={mdaId}
          onChange={(e) => setMdaId(e.target.value)}
          required
        />
        <TextareaField
          label="Reason"
          placeholder="Why does this user need access to another MDA's data?"
          rows={2}
          value={reason}
          onChange={(e) => setReason(e.target.value)}
        />
        <TextField
          label="Expires (optional)"
          type="date"
          helper="Leave blank for a standing grant. An expired grant no longer confers access."
          value={expiresAt}
          onChange={(e) => setExpiresAt(e.target.value)}
        />
      </div>
    </Modal>
  )
}
