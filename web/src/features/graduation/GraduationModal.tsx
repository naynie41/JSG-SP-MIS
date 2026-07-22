import { useState } from 'react'
import { CheckCircle2, Circle, GraduationCap } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Gauge } from '@/components/Gauge/Gauge'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { TextareaField } from '@/components/Field/TextareaField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ApiError } from '@/types/api'
import { formatNaira } from '@/lib/utils/money'
import { useGraduate, useGraduationProgress } from './hooks'
import type { GraduationProgressRule } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './graduation.module.css'

interface Props {
  open: boolean
  onClose: () => void
  enrollmentId: string
  label: string
}

function ruleValue(rule: GraduationProgressRule): string {
  switch (rule.type) {
    case 'total_benefit_value':
      return `${formatNaira(rule.current)} / ${formatNaira(rule.threshold)}`
    case 'months_enrolled':
      return `${rule.current} / ${rule.threshold} months`
    case 'manual':
      return 'Officer decision'
    default:
      return `${rule.current} / ${rule.threshold}`
  }
}

/**
 * A beneficiary/household's progress toward graduation and the record action (FR-GRD-02).
 * Recording flips the enrolment status but NEVER deletes the beneficiary or their ledger.
 */
export function GraduationModal({ open, onClose, enrollmentId, label }: Props) {
  const { hasPermission } = useAuth()
  const canEdit = hasPermission('graduation.edit')
  const { data, isLoading } = useGraduationProgress(enrollmentId, open)
  const graduate = useGraduate()
  const [reason, setReason] = useState('')
  const [error, setError] = useState<string | null>(null)

  const progress = data?.progress
  const alreadyGraduated = progress?.already_graduated ?? data?.status === 'graduated'
  const pct = Math.round((progress?.progress ?? 0) * 100)

  async function submit() {
    setError(null)
    try {
      await graduate.mutateAsync({ enrollmentId, reason: reason.trim() || undefined })
      setReason('')
      onClose()
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not record the graduation. Please try again.')
    }
  }

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={`Graduation · ${label}`}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose}>
            Close
          </Button>
          {canEdit && (
            <Button leftIcon={GraduationCap} onClick={submit} loading={graduate.isPending} disabled={alreadyGraduated}>
              Record graduation
            </Button>
          )}
        </>
      }
    >
      <div className={styles.stack}>
        {error && (
          <p className={formStyles.alert} role="alert">
            {error}
          </p>
        )}

        {isLoading || !progress ? (
          <Spinner size={22} label="Loading progress" />
        ) : (
          <>
            <div className={styles.overall}>
              <Gauge label="Progress" value={pct} tone={alreadyGraduated ? 'success' : progress.eligible ? 'success' : 'forest'} />
              <div className={styles.overallMeta}>
                {alreadyGraduated ? (
                  <Badge variant="success" dot>
                    Already graduated
                  </Badge>
                ) : progress.eligible ? (
                  <Badge variant="success" dot>
                    Meets the graduation criteria
                  </Badge>
                ) : (
                  <Badge variant="neutral" dot>
                    In progress
                  </Badge>
                )}
                {progress.message ? (
                  <span className={styles.note}>{progress.message}</span>
                ) : (
                  <span className={styles.note}>
                    Criteria: {progress.logic === 'all' ? 'all must be met' : 'any may be met'}
                  </span>
                )}
              </div>
            </div>

            {progress.rules.length > 0 && (
              <div>
                {progress.rules.map((rule, i) => (
                  <div key={i} className={styles.progressRule}>
                    <div className={styles.progressTop}>
                      <span className={styles.ruleLabel}>
                        <Icon icon={rule.met ? CheckCircle2 : Circle} size={16} className={rule.met ? styles.metIcon : styles.unmetIcon} />
                        {rule.label}
                      </span>
                      <span className={styles.ruleValue}>{ruleValue(rule)}</span>
                    </div>
                    {rule.type !== 'manual' && (
                      <div className={styles.bar} aria-hidden="true">
                        <div className={styles.barFill} data-met={rule.met} style={{ width: `${Math.round(rule.progress * 100)}%` }} />
                      </div>
                    )}
                  </div>
                ))}
              </div>
            )}

            {canEdit && !alreadyGraduated && (
              <TextareaField
                label="Reason (optional)"
                placeholder="e.g. Household income sustained above the threshold."
                rows={2}
                value={reason}
                onChange={(e) => setReason(e.target.value)}
              />
            )}

            {data && data.history.length > 0 && (
              <div>
                <p className={styles.note} style={{ marginBottom: 'var(--space-2)' }}>
                  Graduation record
                </p>
                <div className={styles.history}>
                  {data.history.map((event) => (
                    <div key={event.id} className={styles.historyItem}>
                      <span className={styles.historyDate}>{event.graduated_at?.slice(0, 10) ?? '—'}</span>
                      <span className={styles.note}>{event.reason ?? 'No reason recorded.'}</span>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </>
        )}
      </div>
    </Modal>
  )
}
