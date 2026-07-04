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
import { koboToNaira, nairaToKobo } from '@/lib/utils/money'
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

const KNOWN = ['name', 'objective', 'type', 'funding_source', 'starts_on', 'ends_on', 'status'] as const

/** Create or configure a programme (PRD FR-PRG-01). Owner-MDA only (server-enforced). */
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
      funding_source: programme?.funding_source ?? '',
      budget_naira: koboToNaira(programme?.budget_amount),
      starts_on: programme?.starts_on ?? '',
      ends_on: programme?.ends_on ?? '',
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
          funding_source: values.funding_source || null,
          budget_amount: nairaToKobo(values.budget_naira) ?? null,
          starts_on: values.starts_on || null,
          ends_on: values.ends_on || null,
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

        <div className={formStyles.grid2}>
          <TextField label="Funding source" error={errors.funding_source?.message} {...register('funding_source')} />
          <TextField label="Budget (₦)" type="number" min={0} step="0.01" helper="Total allocated; recorded as data, not disbursed." error={errors.budget_naira?.message} {...register('budget_naira')} />
        </div>

        <div className={formStyles.grid2}>
          <TextField label="Start date" type="date" error={errors.starts_on?.message} {...register('starts_on')} />
          <TextField label="End date" type="date" error={errors.ends_on?.message} {...register('ends_on')} />
        </div>

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
