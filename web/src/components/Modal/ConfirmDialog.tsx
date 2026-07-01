import type { ReactNode } from 'react'
import { Button } from '@/components/Button/Button'
import { Modal } from './Modal'

export interface ConfirmDialogProps {
  open: boolean
  title: string
  /** Describe the exact consequence (DESIGN-SYSTEM.md §5.7). */
  children: ReactNode
  confirmLabel?: string
  cancelLabel?: string
  danger?: boolean
  loading?: boolean
  onConfirm: () => void
  onCancel: () => void
}

/** Confirmation dialog. Destructive actions use the danger button + explicit consequence. */
export function ConfirmDialog({
  open,
  title,
  children,
  confirmLabel = 'Confirm',
  cancelLabel = 'Cancel',
  danger = false,
  loading = false,
  onConfirm,
  onCancel,
}: ConfirmDialogProps) {
  return (
    <Modal
      open={open}
      onClose={onCancel}
      title={title}
      footer={
        <>
          <Button variant="tertiary" onClick={onCancel} disabled={loading}>
            {cancelLabel}
          </Button>
          <Button variant={danger ? 'danger' : 'primary'} onClick={onConfirm} loading={loading}>
            {confirmLabel}
          </Button>
        </>
      }
    >
      {children}
    </Modal>
  )
}
