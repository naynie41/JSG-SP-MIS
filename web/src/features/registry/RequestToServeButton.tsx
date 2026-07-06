import { useState } from 'react'
import { Send } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Modal } from '@/components/Modal/Modal'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { useRaiseServiceRequest } from './hooks'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

interface RequestToServeButtonProps {
  beneficiaryId: string
  /** Disabled with a reason (e.g. the caller's MDA already owns this record). */
  disabledReason?: string
  size?: 'sm' | 'md'
}

/**
 * Raise a request-to-serve on an existing beneficiary owned by another MDA
 * (PRD FR-DUP-05, FR-OWN-03). Opens a small dialog for an optional reason; the
 * request is routed to the owner MDA for approval and never transfers ownership.
 */
export function RequestToServeButton({ beneficiaryId, disabledReason, size = 'sm' }: RequestToServeButtonProps) {
  const raise = useRaiseServiceRequest()
  const [open, setOpen] = useState(false)
  const [reason, setReason] = useState('')
  const [error, setError] = useState<string | null>(null)

  async function submit() {
    setError(null)
    try {
      await raise.mutateAsync({ beneficiary_id: beneficiaryId, reason: reason.trim() || undefined })
      setOpen(false)
      setReason('')
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not send the request.')
    }
  }

  if (disabledReason) {
    return (
      <Button size={size} variant="tertiary" disabled>
        {disabledReason}
      </Button>
    )
  }

  return (
    <>
      <Button size={size} variant="secondary" leftIcon={Send} onClick={() => setOpen(true)}>
        Request to serve
      </Button>
      <Modal
        open={open}
        onClose={() => setOpen(false)}
        title="Request to serve"
        footer={
          <>
            <Button variant="tertiary" onClick={() => setOpen(false)} disabled={raise.isPending}>
              Cancel
            </Button>
            <Button onClick={submit} loading={raise.isPending}>
              Send request
            </Button>
          </>
        }
      >
        <div className={styles.stack}>
          <p className={styles.note}>
            The owning MDA is asked to grant your MDA serve access to this beneficiary. Ownership does not change.
          </p>
          {error && (
            <p className={formStyles.alert} role="alert">
              {error}
            </p>
          )}
          <TextareaField
            label="Reason (optional)"
            rows={3}
            value={reason}
            onChange={(event) => setReason(event.target.value)}
            helper="Shared with the owning MDA and recorded in the audit log."
          />
        </div>
      </Modal>
    </>
  )
}
