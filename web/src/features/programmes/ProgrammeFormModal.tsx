import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { Plus, Trash2 } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { Toggle } from '@/components/Field/Toggle'
import { Icon } from '@/components/Icon/Icon'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { ELIGIBILITY_ATTRIBUTE_OPTIONS, PROGRAMME_STATUS_OPTIONS, PROGRAMME_TYPE_OPTIONS } from './constants'
import { programmeSchema } from './schema'
import type { ProgrammeFormValues } from './schema'
import { useSaveProgramme } from './hooks'
import type { EligibilityCriterion, Programme } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

interface ProgrammeFormModalProps {
  open: boolean
  onClose: () => void
  programme?: Programme | null
}

const KNOWN = ['name', 'objective', 'type', 'benefit_category', 'status'] as const

/**
 * Create or configure a GLOBAL catalog programme (§10) — type-level attributes only.
 * Catalog-admin only (server-enforced); budget/funding/period live on activities.
 */
export function ProgrammeFormModal({ open, onClose, programme }: ProgrammeFormModalProps) {
  const save = useSaveProgramme()
  const [formError, setFormError] = useState<string | null>(null)
  const [criteria, setCriteria] = useState<EligibilityCriterion[]>(programme?.eligibility ?? [])

  const {
    register,
    handleSubmit,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<ProgrammeFormValues>({
    resolver: zodResolver(programmeSchema),
    defaultValues: {
      name: programme?.name ?? '',
      objective: programme?.objective ?? '',
      type: programme?.type ?? 'individual',
      benefit_category: programme?.benefit_category ?? '',
      status: (programme?.status as ProgrammeFormValues['status']) ?? 'draft',
      enforce_eligibility: programme?.enforce_eligibility ?? false,
    },
  })

  const onSubmit = handleSubmit(async (values) => {
    setFormError(null)
    const cleanCriteria = criteria.filter((c) => c.attribute && String(c.value ?? '') !== '')
    try {
      await save.mutateAsync({
        id: programme?.id,
        input: {
          name: values.name,
          objective: values.objective || null,
          type: values.type,
          benefit_category: values.benefit_category || null,
          status: values.status,
          enforce_eligibility: values.enforce_eligibility ?? false,
          eligibility: cleanCriteria,
        },
      })
      onClose()
    } catch (error) {
      setFormError(applyApiErrors(error, setError, KNOWN))
    }
  })

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={programme ? 'Configure programme' : 'Create programme'}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose} disabled={isSubmitting}>
            Cancel
          </Button>
          <Button type="submit" form="programme-form" loading={isSubmitting}>
            {programme ? 'Save changes' : 'Create programme'}
          </Button>
        </>
      }
    >
      <form id="programme-form" onSubmit={onSubmit} noValidate className={formStyles.form}>
        {formError && (
          <p className={formStyles.alert} role="alert">
            {formError}
          </p>
        )}

        <TextField label="Name" required error={errors.name?.message} {...register('name')} />
        <TextareaField label="Objective" rows={2} error={errors.objective?.message} {...register('objective')} />

        <div className={formStyles.grid2}>
          <SelectField label="Type" required options={PROGRAMME_TYPE_OPTIONS} error={errors.type?.message} {...register('type')} />
          <SelectField label="Status" required options={PROGRAMME_STATUS_OPTIONS} error={errors.status?.message} {...register('status')} />
        </div>

        <TextField label="Benefit category" helper="Type-level (e.g. cash, in-kind, service). Budget & funding live on activities." error={errors.benefit_category?.message} {...register('benefit_category')} />

        <fieldset style={{ border: 'none', padding: 0, margin: 0 }}>
          <div className={styles.chipRow} style={{ justifyContent: 'space-between' }}>
            <span className="eyebrow">Eligibility criteria</span>
            <Button size="sm" variant="tertiary" leftIcon={Plus} onClick={() => setCriteria([...criteria, { attribute: 'lga', value: '' }])}>
              Add criterion
            </Button>
          </div>
          {criteria.length === 0 && <p className={styles.note}>No criteria — everyone is eligible. Add one to flag (or block) non-matching beneficiaries.</p>}
          {criteria.map((criterion, i) => (
            <div key={i} className={styles.criterionRow} style={{ marginTop: 'var(--space-2)' }}>
              <SelectField
                label="Attribute"
                hideLabel
                options={ELIGIBILITY_ATTRIBUTE_OPTIONS}
                value={criterion.attribute ?? ''}
                onChange={(e) => setCriteria(criteria.map((c, j) => (j === i ? { ...c, attribute: e.target.value } : c)))}
              />
              <TextField
                label="Value"
                hideLabel
                placeholder="Value"
                value={String(criterion.value ?? '')}
                onChange={(e) => setCriteria(criteria.map((c, j) => (j === i ? { ...c, value: e.target.value } : c)))}
              />
              <Button size="sm" variant="tertiary" aria-label={`Remove criterion ${i + 1}`} onClick={() => setCriteria(criteria.filter((_, j) => j !== i))}>
                <Icon icon={Trash2} size={16} />
              </Button>
            </div>
          ))}
        </fieldset>

        <div>
          <Toggle label="Enforce eligibility" {...register('enforce_eligibility')} />
          <p className={styles.note} style={{ marginTop: 'var(--space-1)' }}>
            When on, ineligible beneficiaries are blocked at enrollment; when off, they are only flagged.
          </p>
        </div>
      </form>
    </Modal>
  )
}
