import { useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { ArrowLeft, Check, Play, UserPlus, X } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import type { ButtonVariant } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Modal } from '@/components/Modal/Modal'
import { Spinner } from '@/components/Spinner/Spinner'
import { SelectField } from '@/components/Field/SelectField'
import { TextareaField } from '@/components/Field/TextareaField'
import { Icon } from '@/components/Icon/Icon'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useUsers } from '@/features/users/hooks'
import { GrievanceStatusBadge, GrievanceSla } from './GrievanceDeskPage'
import { GRIEVANCE_CATEGORY_LABELS, GRIEVANCE_CHANNEL_LABELS, GRIEVANCE_STATUS_LABELS } from './constants'
import { useAssignGrievance, useGrievance, useGrievanceAction } from './hooks'
import type { Grievance, GrievanceAction } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './grievances.module.css'

interface ActionSpec {
  action: GrievanceAction
  label: string
  icon: LucideIcon
  variant: ButtonVariant
  input?: { label: string; required: boolean; helper?: string }
  title: string
}

/** Lifecycle actions available for the handling MDA given the current status. */
function actionsFor(grievance: Grievance): ActionSpec[] {
  const specs: ActionSpec[] = []
  if (grievance.status === 'assigned') {
    specs.push({ action: 'start', label: 'Mark in progress', icon: Play, variant: 'primary', title: 'Mark in progress' })
  }
  if (grievance.status === 'assigned' || grievance.status === 'in_progress') {
    specs.push({ action: 'resolve', label: 'Resolve', icon: Check, variant: 'primary', title: 'Resolve grievance', input: { label: 'Resolution notes', required: true, helper: 'How the grievance was resolved.' } })
  }
  // Close is valid from any non-terminal state (open/assigned/in_progress) and from resolved.
  if (grievance.status !== 'closed') {
    specs.push({ action: 'close', label: 'Close', icon: X, variant: 'tertiary', title: 'Close grievance', input: { label: 'Closing notes (optional)', required: false } })
  }
  return specs
}

const TIMELINE_STEPS: { key: keyof Grievance['timeline']; label: string }[] = [
  { key: 'created_at', label: 'Logged' },
  { key: 'assigned_at', label: 'Assigned' },
  { key: 'started_at', label: 'In progress' },
  { key: 'resolved_at', label: 'Resolved' },
  { key: 'closed_at', label: 'Closed' },
]

function formatTime(iso: string | null): string {
  if (!iso) return ''
  return new Date(iso).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' })
}

export function GrievanceDetailPage() {
  const { id } = useParams<{ id: string }>()
  const { user, hasPermission } = useAuth()
  const { data: grievance, isLoading } = useGrievance(id)
  const assign = useAssignGrievance(id ?? '')
  const act = useGrievanceAction(id ?? '')

  const [pending, setPending] = useState<ActionSpec | null>(null)
  const [notes, setNotes] = useState('')
  const [assignee, setAssignee] = useState('')
  const [error, setError] = useState<string | null>(null)

  const canEdit = hasPermission('grievance.edit')
  const isHandler = canEdit && user?.mda?.id === grievance?.handling_mda_id
  const users = useUsers(Boolean(isHandler))

  if (isLoading) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} />
      </div>
    )
  }
  if (!grievance) {
    return (
      <Card>
        <p className={layout.forbidden}>This grievance could not be found.</p>
      </Card>
    )
  }

  const actions = isHandler ? actionsFor(grievance) : []
  const canAssign = isHandler && grievance.status !== 'resolved' && grievance.status !== 'closed'
  const mdaUsers = (users.data ?? []).filter((u) => u.mda?.id === grievance.handling_mda_id)

  async function submitAssign() {
    if (!assignee) return setError('Select a team member.')
    setError(null)
    try {
      await assign.mutateAsync(assignee)
      setAssignee('')
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not assign the grievance.')
    }
  }

  function openAction(spec: ActionSpec) {
    setError(null)
    setNotes('')
    if (spec.input) {
      setPending(spec)
    } else {
      void run(spec)
    }
  }

  async function run(spec: ActionSpec) {
    setError(null)
    const value = notes.trim()
    if (spec.input?.required && value === '') {
      setError(`${spec.input.label} is required.`)
      return
    }
    try {
      await act.mutateAsync({ action: spec.action, input: value ? { resolution_notes: value } : {} })
      setPending(null)
      setNotes('')
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not complete the action.')
    }
  }

  return (
    <div>
      <Link to="/grievances" className={styles.backLink}>
        <Icon icon={ArrowLeft} size={14} /> Grievance desk
      </Link>

      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">05 · Coordination · Grievance</span>
          <h1 className="t-h1">{GRIEVANCE_CATEGORY_LABELS[grievance.category]}</h1>
          <div className={styles.rowActions}>
            <GrievanceStatusBadge status={grievance.status} />
            <GrievanceSla grievance={grievance} />
            <span className={styles.mono}>· #{grievance.id.slice(0, 8)}</span>
          </div>
        </div>
      </div>

      <div className={styles.detailGrid}>
        <div className={styles.stack}>
          <Card title="Details" eyebrow="Grievance">
            <dl className={styles.dl}>
              <dt>Category</dt>
              <dd>{GRIEVANCE_CATEGORY_LABELS[grievance.category]}</dd>
              <dt>Channel</dt>
              <dd>{GRIEVANCE_CHANNEL_LABELS[grievance.channel]}</dd>
              <dt>Beneficiary</dt>
              <dd>{grievance.beneficiary_id ? <span className={styles.mono}>#{grievance.beneficiary_id.slice(0, 8)}</span> : 'General (no link)'}</dd>
              <dt>Assignee</dt>
              <dd>{grievance.assignee_user_id ? <span className={styles.mono}>#{grievance.assignee_user_id.slice(0, 8)}</span> : 'Unassigned'}</dd>
              <dt>Description</dt>
              <dd>{grievance.description}</dd>
            </dl>

            {grievance.resolution_notes && (
              <p className={styles.resolution} style={{ marginTop: 'var(--space-4)' }}>
                <strong>Resolution:</strong> {grievance.resolution_notes}
              </p>
            )}
          </Card>

          <Card title="Timeline" eyebrow="Lifecycle">
            <ol className={styles.timeline}>
              {TIMELINE_STEPS.filter((step) => grievance.timeline[step.key]).map((step) => (
                <li key={step.key} className={styles.timelineItem}>
                  <span className={styles.timelineDot} />
                  <span>
                    <span className={styles.timelineLabel}>{step.label}</span>
                    <span className={styles.timelineTime}>{formatTime(grievance.timeline[step.key])}</span>
                  </span>
                </li>
              ))}
            </ol>
          </Card>
        </div>

        <div className={styles.stack}>
          {canAssign && (
            <Card title="Assignment" eyebrow="Handle">
              <div className={styles.stack} style={{ gap: 'var(--space-3)' }}>
                <SelectField
                  label="Assign to"
                  placeholder={users.isLoading ? 'Loading team…' : 'Select a team member'}
                  options={mdaUsers.map((u) => ({ value: u.id, label: u.name }))}
                  value={assignee}
                  onChange={(e) => setAssignee(e.target.value)}
                />
                <Button leftIcon={UserPlus} onClick={submitAssign} loading={assign.isPending} disabled={!assignee}>
                  {grievance.assignee_user_id ? 'Reassign' : 'Assign'}
                </Button>
              </div>
            </Card>
          )}

          <Card title="Actions" eyebrow={GRIEVANCE_STATUS_LABELS[grievance.status]}>
            {actions.length === 0 ? (
              <p className={styles.note}>
                {isHandler ? 'No actions are available in this state.' : 'Only the handling MDA can act on this grievance.'}
              </p>
            ) : (
              <div className={styles.actionBar}>
                {actions.map((spec) => (
                  <Button key={spec.action} variant={spec.variant} leftIcon={spec.icon} onClick={() => openAction(spec)} loading={act.isPending && pending === null}>
                    {spec.label}
                  </Button>
                ))}
              </div>
            )}
          </Card>
        </div>
      </div>

      {error && !pending && (
        <p className={layout.alert} role="alert" style={{ marginTop: 'var(--space-4)' }}>
          {error}
        </p>
      )}

      <Modal
        open={pending !== null}
        onClose={() => setPending(null)}
        title={pending?.title ?? ''}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setPending(null)} disabled={act.isPending}>
              Cancel
            </Button>
            <Button variant={pending?.variant === 'danger' ? 'danger' : 'primary'} onClick={() => pending && run(pending)} loading={act.isPending}>
              {pending?.label}
            </Button>
          </>
        }
      >
        <div className={styles.stack}>
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          {pending?.input && (
            <TextareaField
              label={pending.input.label}
              required={pending.input.required}
              helper={pending.input.helper}
              rows={3}
              value={notes}
              onChange={(e) => setNotes(e.target.value)}
            />
          )}
        </div>
      </Modal>
    </div>
  )
}
