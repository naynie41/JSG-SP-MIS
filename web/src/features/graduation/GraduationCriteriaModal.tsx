import { useState } from 'react'
import { Plus, Save, Trash2 } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { Icon } from '@/components/Icon/Icon'
import { ApiError } from '@/types/api'
import { koboToNaira, nairaToKobo } from '@/lib/utils/money'
import { useSaveGraduationCriteria } from './hooks'
import type { CriteriaLogic, GraduationCriteria, GraduationCriterionType } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './graduation.module.css'

interface Props {
  open: boolean
  onClose: () => void
  programmeId: string
  criteria: GraduationCriteria | null
}

interface RuleDraft {
  type: GraduationCriterionType
  threshold: string
}

const TYPE_OPTIONS: { value: GraduationCriterionType; label: string }[] = [
  { value: 'benefits_received', label: 'Benefits received (count)' },
  { value: 'months_enrolled', label: 'Months enrolled' },
  { value: 'total_benefit_value', label: 'Total benefit value (₦)' },
  { value: 'manual', label: 'Manual readiness (officer confirms)' },
]

function thresholdLabel(type: GraduationCriterionType): string {
  if (type === 'total_benefit_value') return 'Threshold (₦)'
  if (type === 'months_enrolled') return 'Months'
  return 'Count'
}

/** Seed the editor from an existing set (converting stored kobo back to Naira for display). */
function draftFrom(criteria: GraduationCriteria | null): RuleDraft[] {
  if (!criteria || criteria.rules.length === 0) {
    return [{ type: 'benefits_received', threshold: '' }]
  }
  return criteria.rules.map((rule) => {
    const type = (rule.type ?? 'benefits_received') as GraduationCriterionType
    const threshold =
      rule.threshold == null
        ? ''
        : type === 'total_benefit_value'
          ? koboToNaira(rule.threshold)
          : String(rule.threshold)
    return { type, threshold }
  })
}

/**
 * Define/edit an MDA's graduation criteria for a programme (FR-GRD-01). Rules are
 * configuration — a list of {dimension, threshold} — never hard-coded. Saving an active
 * set replaces the previous active one for this programme.
 */
export function GraduationCriteriaModal({ open, onClose, programmeId, criteria }: Props) {
  const [name, setName] = useState(criteria?.name ?? 'Standard graduation')
  const [logic, setLogic] = useState<CriteriaLogic>(criteria?.logic ?? 'all')
  const [rules, setRules] = useState<RuleDraft[]>(() => draftFrom(criteria))
  const [error, setError] = useState<string | null>(null)
  const save = useSaveGraduationCriteria(programmeId)

  function updateRule(index: number, patch: Partial<RuleDraft>) {
    setRules((current) => current.map((rule, i) => (i === index ? { ...rule, ...patch } : rule)))
  }

  function addRule() {
    setRules((current) => [...current, { type: 'months_enrolled', threshold: '' }])
  }

  function removeRule(index: number) {
    setRules((current) => (current.length === 1 ? current : current.filter((_, i) => i !== index)))
  }

  async function submit() {
    setError(null)
    const payloadRules = rules.map((rule) => {
      if (rule.type === 'manual') return { type: rule.type }
      const threshold = rule.type === 'total_benefit_value' ? nairaToKobo(rule.threshold) : Number(rule.threshold)
      return { type: rule.type, threshold: threshold ?? 0 }
    })

    try {
      await save.mutateAsync({ id: criteria?.id, input: { name, logic, is_active: true, rules: payloadRules } })
      onClose()
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not save criteria. Please check the thresholds and try again.')
    }
  }

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={criteria ? 'Edit graduation criteria' : 'Define graduation criteria'}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose}>
            Cancel
          </Button>
          <Button leftIcon={Save} onClick={submit} loading={save.isPending} disabled={!name.trim()}>
            Save criteria
          </Button>
        </>
      }
    >
      <div className={styles.stack}>
        {error && (
          <p className={formStyles.alert} role="alert">
            {error}
          </p>
        )}

        <div className={formStyles.grid2}>
          <TextField label="Criteria name" value={name} onChange={(e) => setName(e.target.value)} required />
          <SelectField
            label="A beneficiary graduates when"
            options={[
              { value: 'all', label: 'ALL criteria are met' },
              { value: 'any', label: 'ANY criterion is met' },
            ]}
            value={logic}
            onChange={(e) => setLogic(e.target.value as CriteriaLogic)}
          />
        </div>

        <div>
          <p className={styles.note} style={{ marginBottom: 'var(--space-2)' }}>
            Criteria are evaluated against real data — benefits delivered, months enrolled, total value. “Manual readiness” is confirmed by an officer.
          </p>
          <div className={styles.rules}>
            {rules.map((rule, index) => (
              <div key={index} className={styles.ruleRow} data-manual={rule.type === 'manual'}>
                <SelectField
                  label="Criterion"
                  hideLabel
                  options={TYPE_OPTIONS}
                  value={rule.type}
                  onChange={(e) => updateRule(index, { type: e.target.value as GraduationCriterionType })}
                />
                {rule.type !== 'manual' && (
                  <TextField
                    label={thresholdLabel(rule.type)}
                    hideLabel
                    type="number"
                    min="0"
                    placeholder={thresholdLabel(rule.type)}
                    value={rule.threshold}
                    onChange={(e) => updateRule(index, { threshold: e.target.value })}
                  />
                )}
                <button
                  type="button"
                  className={styles.removeBtn}
                  onClick={() => removeRule(index)}
                  disabled={rules.length === 1}
                  aria-label="Remove criterion"
                >
                  <Icon icon={Trash2} size={16} />
                </button>
              </div>
            ))}
          </div>
          <div style={{ marginTop: 'var(--space-2)' }}>
            <Button size="sm" variant="tertiary" leftIcon={Plus} onClick={addRule}>
              Add criterion
            </Button>
          </div>
        </div>
      </div>
    </Modal>
  )
}
