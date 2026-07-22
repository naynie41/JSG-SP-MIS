import { useState } from 'react'
import { GraduationCap, Pencil, Plus } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { GraduationCriteriaModal } from './GraduationCriteriaModal'
import { useGraduationCriteria } from './hooks'
import type { GraduationCriteria, GraduationRule } from './types'
import styles from './graduation.module.css'

function ruleText(rule: GraduationRule): string {
  if (rule.type === 'manual') return `${rule.label ?? 'Manual readiness'} — officer confirms`
  if (rule.type === 'total_benefit_value') return `${rule.label}: ${formatNaira(rule.threshold)}`
  const unit = rule.type === 'months_enrolled' ? 'months' : ''
  return `${rule.label}: ${rule.threshold}${unit ? ` ${unit}` : ''}`.trim()
}

/**
 * Per-programme graduation criteria (FR-GRD-01): shows the MDA's active criteria set and
 * lets an admin configure it. Criteria are admin-editable config, never hard-coded.
 */
export function GraduationCriteriaCard({ programmeId }: { programmeId: string }) {
  const { hasPermission } = useAuth()
  const canView = hasPermission('graduation.view')
  const canEdit = hasPermission('graduation.edit')
  const { data, isLoading } = useGraduationCriteria(programmeId, canView)
  const [editing, setEditing] = useState<{ open: boolean; criteria: GraduationCriteria | null }>({ open: false, criteria: null })

  if (!canView) {
    return (
      <Card title="Graduation criteria" eyebrow="Graduation">
        <p className={styles.note}>You do not have permission to view graduation criteria.</p>
      </Card>
    )
  }

  if (isLoading) {
    return (
      <Card title="Graduation criteria" eyebrow="Graduation">
        <Spinner size={20} label="Loading criteria" />
      </Card>
    )
  }

  const sets = data?.criteria ?? []
  const active = sets.find((s) => s.is_active) ?? null

  return (
    <>
      <Card
        title="Graduation criteria"
        eyebrow="Graduation"
        variant="mint"
        actions={
          canEdit ? (
            active ? (
              <Button size="sm" variant="tertiary" leftIcon={Pencil} onClick={() => setEditing({ open: true, criteria: active })}>
                Configure
              </Button>
            ) : (
              <Button size="sm" leftIcon={Plus} onClick={() => setEditing({ open: true, criteria: null })}>
                Define criteria
              </Button>
            )
          ) : undefined
        }
      >
        {!active ? (
          <p className={styles.note}>
            No graduation criteria defined for this programme yet. {canEdit ? 'Define criteria so progress can be tracked.' : ''}
          </p>
        ) : (
          <div>
            <div className={styles.summaryHead}>
              <Badge variant="neutral" dot>
                <GraduationCap size={13} style={{ marginRight: 4, verticalAlign: '-2px' }} />
                {active.name}
              </Badge>
              <span className={styles.note}>
                A beneficiary graduates when {active.logic === 'all' ? 'ALL' : 'ANY'} of these are met:
              </span>
            </div>
            <div className={styles.chipRow}>
              {active.rules.map((rule, i) => (
                <Badge key={i} variant={rule.automatic ? 'info' : 'warning'} mono>
                  {ruleText(rule)}
                </Badge>
              ))}
            </div>
          </div>
        )}
      </Card>

      {canEdit && (
        <GraduationCriteriaModal
          open={editing.open}
          onClose={() => setEditing({ open: false, criteria: null })}
          programmeId={programmeId}
          criteria={editing.criteria}
        />
      )}
    </>
  )
}
