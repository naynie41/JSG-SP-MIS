import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { FileUp, UploadCloud, X } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { TextField } from '@/components/Field/TextField'
import { TextareaField } from '@/components/Field/TextareaField'
import { SelectField } from '@/components/Field/SelectField'
import { Icon } from '@/components/Icon/Icon'
import { applyApiErrors } from '@/lib/forms/applyApiErrors'
import { koboToNaira, nairaToKobo } from '@/lib/utils/money'
import { LGA_OPTIONS } from '@/features/registry/constants'
import { usePreviewActivityImport } from '@/features/registry/hooks'
import { ACTIVITY_STATUS_OPTIONS } from './constants'
import { activitySchema } from './schema'
import type { ActivityFormValues } from './schema'
import { useProgrammeCatalog, useSaveActivity } from './hooks'
import type { Activity, ActivityInput } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

interface ActivityFormModalProps {
  open: boolean
  onClose: () => void
  /** Fix the activity to one programme (from a programme page); omit for the
   *  standalone flow where the user picks the catalog programme first. */
  programmeId?: string
  activity?: Activity | null
}

const KNOWN = ['programme_id', 'involves_beneficiaries', 'name', 'description', 'target_beneficiaries', 'lga', 'ward', 'location_description', 'budget_naira', 'funding_source', 'starts_on', 'ends_on', 'status'] as const

/**
 * Create/edit an MDA-owned activity that runs a GLOBAL catalog programme (§10). Creation
 * branches on "does this activity involve beneficiaries?" (DESIGN-SYSTEM §5.10):
 *  - No  → a single step; the activity is saved alone (no target, no upload).
 *  - Yes → a target is required, then a MANDATORY step 2 "Upload beneficiary data".
 *          Attaching a file stages a preview (dedup before saving) and continues on the
 *          import preview page to resolve duplicates and confirm.
 */
export function ActivityFormModal({ open, onClose, programmeId, activity }: ActivityFormModalProps) {
  const isCreate = !activity
  const save = useSaveActivity(programmeId)
  const previewImport = usePreviewActivityImport()
  const navigate = useNavigate()
  const catalog = useProgrammeCatalog(open)
  const lockProgramme = Boolean(activity) || Boolean(programmeId) // fixed when editing / page-scoped
  const programmeOptions = (catalog.data?.items ?? []).map((p) => ({ value: p.id, label: p.name }))

  const [step, setStep] = useState<1 | 2>(1)
  const [file, setFile] = useState<File | null>(null)
  const [formError, setFormError] = useState<string | null>(null)

  useEffect(() => {
    if (open) {
      setStep(1)
      setFile(null)
      setFormError(null)
    }
  }, [open])

  const {
    register,
    handleSubmit,
    trigger,
    watch,
    setError,
    formState: { errors, isSubmitting },
  } = useForm<ActivityFormValues>({
    resolver: zodResolver(activitySchema),
    defaultValues: {
      programme_id: activity?.programme_id ?? programmeId ?? '',
      involves_beneficiaries: activity ? (activity.involves_beneficiaries ? 'yes' : 'no') : 'no',
      name: activity?.name ?? '',
      description: activity?.description ?? '',
      target_beneficiaries: activity?.target_beneficiaries != null ? String(activity.target_beneficiaries) : '',
      lga: activity?.lga ?? '',
      ward: activity?.ward ?? '',
      location_description: activity?.location_description ?? '',
      budget_naira: koboToNaira(activity?.budget_amount),
      funding_source: activity?.funding_source ?? '',
      starts_on: activity?.starts_on ?? '',
      ends_on: activity?.ends_on ?? '',
      status: (activity?.status as ActivityFormValues['status']) ?? 'draft',
    },
  })

  const involves = watch('involves_beneficiaries') === 'yes'

  function buildInput(values: ActivityFormValues): ActivityInput {
    const involvesBeneficiaries = values.involves_beneficiaries === 'yes'
    return {
      programme_id: values.programme_id,
      involves_beneficiaries: involvesBeneficiaries,
      name: values.name,
      description: values.description || null,
      // No beneficiaries → no target (the API prohibits it on the no-file path).
      target_beneficiaries: involvesBeneficiaries && values.target_beneficiaries ? Number(values.target_beneficiaries) : null,
      lga: values.lga || null,
      ward: values.ward || null,
      location_description: values.location_description || null,
      budget_amount: nairaToKobo(values.budget_naira) ?? null,
      funding_source: values.funding_source || null,
      starts_on: values.starts_on || null,
      ends_on: values.ends_on || null,
      status: values.status,
    }
  }

  async function goToUploadStep() {
    setFormError(null)
    if (await trigger()) setStep(2)
  }

  // No-beneficiary create / edit: save the activity alone.
  const saveActivity = handleSubmit(async (values) => {
    setFormError(null)
    try {
      await save.mutateAsync({ id: activity?.id, input: buildInput(values) })
      onClose()
    } catch (error) {
      setFormError(applyApiErrors(error, setError, KNOWN))
    }
  })

  // Attach a file: stage the preview (dedup before saving), then continue on the
  // import preview page to resolve duplicates and confirm.
  const uploadAndPreview = handleSubmit(async (values) => {
    setFormError(null)
    if (!file) {
      setFormError('Attach a beneficiary file to continue — the upload is required for this activity.')
      return
    }
    try {
      const batch = await previewImport.mutateAsync({ draft: buildInput(values) as unknown as Record<string, string | number | null | undefined>, file })
      onClose()
      navigate(`/imports/${batch.id}`)
    } catch (error) {
      setFormError(applyApiErrors(error, setError, KNOWN))
    }
  })

  const busy = isSubmitting || save.isPending || previewImport.isPending

  const footer = !isCreate ? (
    <>
      <Button variant="tertiary" onClick={onClose} disabled={busy}>Cancel</Button>
      <Button onClick={saveActivity} loading={busy}>Save changes</Button>
    </>
  ) : !involves ? (
    // No beneficiaries → single step; create the activity on its own.
    <>
      <Button variant="tertiary" onClick={onClose} disabled={busy}>Cancel</Button>
      <Button onClick={saveActivity} loading={busy}>Create activity</Button>
    </>
  ) : step === 1 ? (
    <>
      <Button variant="tertiary" onClick={onClose} disabled={busy}>Cancel</Button>
      <Button rightIcon={FileUp} onClick={goToUploadStep}>Next: upload</Button>
    </>
  ) : (
    // Yes → the upload is mandatory (no skip).
    <>
      <Button variant="tertiary" onClick={() => setStep(1)} disabled={busy}>Back</Button>
      <Button leftIcon={UploadCloud} onClick={uploadAndPreview} loading={previewImport.isPending} disabled={!file}>Upload &amp; preview</Button>
    </>
  )

  return (
    <Modal open={open} onClose={onClose} title={isCreate ? 'New activity' : 'Edit activity'} footer={footer}>
      <div className={formStyles.form}>
        {isCreate && involves && (
          <ol className={styles.wizardSteps} aria-label="Steps">
            <li className={step === 1 ? styles.wizardStepActive : styles.wizardStep} aria-current={step === 1 ? 'step' : undefined}>
              <span className={styles.wizardStepNo}>1</span> Activity details
            </li>
            <li className={step === 2 ? styles.wizardStepActive : styles.wizardStep} aria-current={step === 2 ? 'step' : undefined}>
              <span className={styles.wizardStepNo}>2</span> Upload beneficiary data
            </li>
          </ol>
        )}

        {formError && (
          <p className={formStyles.alert} role="alert">{formError}</p>
        )}

        {/* Step 1 — activity details (always shown when editing). */}
        <div hidden={isCreate && step !== 1}>
          <div className={formStyles.form}>
            <SelectField
              label="Programme"
              required
              placeholder="Select a catalog programme"
              options={programmeOptions}
              disabled={lockProgramme}
              helper="The catalog programme this activity delivers."
              error={errors.programme_id?.message}
              {...register('programme_id')}
            />
            <SelectField
              label="Does this activity involve beneficiaries?"
              required
              options={[
                { value: 'no', label: 'No — no beneficiaries (save the activity alone)' },
                { value: 'yes', label: 'Yes — onboard or serve beneficiaries' },
              ]}
              disabled={!isCreate}
              helper="Yes requires a target and a beneficiary upload; No saves the activity on its own."
              error={errors.involves_beneficiaries?.message}
              {...register('involves_beneficiaries')}
            />
            <TextField label="Name" required error={errors.name?.message} {...register('name')} />
            <TextareaField label="Description" rows={2} error={errors.description?.message} {...register('description')} />
            {involves ? (
              <div className={formStyles.grid2}>
                <TextField label="Target beneficiaries" required type="number" min={1} error={errors.target_beneficiaries?.message} {...register('target_beneficiaries')} />
                <TextField label="Budget (₦)" type="number" min={0} step="0.01" error={errors.budget_naira?.message} {...register('budget_naira')} />
              </div>
            ) : (
              <TextField label="Budget (₦)" type="number" min={0} step="0.01" error={errors.budget_naira?.message} {...register('budget_naira')} />
            )}
            <div className={formStyles.grid2}>
              <SelectField label="LGA" placeholder="Select LGA" options={LGA_OPTIONS} error={errors.lga?.message} {...register('lga')} />
              <TextField label="Ward" error={errors.ward?.message} {...register('ward')} />
            </div>
            <TextField label="Location" error={errors.location_description?.message} {...register('location_description')} />
            <TextField label="Funding source" error={errors.funding_source?.message} {...register('funding_source')} />
            <div className={formStyles.grid2}>
              <TextField label="Start date" type="date" error={errors.starts_on?.message} {...register('starts_on')} />
              <TextField label="End date" type="date" error={errors.ends_on?.message} {...register('ends_on')} />
            </div>
            <SelectField label="Status" required options={ACTIVITY_STATUS_OPTIONS} error={errors.status?.message} {...register('status')} />
          </div>
        </div>

        {/* Step 2 — mandatory beneficiary upload (Yes flow). */}
        {isCreate && involves && step === 2 && (
          <div>
            <p className={styles.note} style={{ marginBottom: 'var(--space-3)' }}>
              Attach the beneficiary file for this activity. It’s validated and screened for duplicates in a
              preview before anything is saved — you’ll resolve any matches and confirm on the next screen.
            </p>

            {file ? (
              <div className={styles.dropzoneFilled}>
                <span className={styles.dropzoneChip} aria-hidden="true"><Icon icon={FileUp} size={18} /></span>
                <span className={styles.dropzoneName}>{file.name}</span>
                <Button size="sm" variant="tertiary" leftIcon={X} onClick={() => setFile(null)}>Remove</Button>
              </div>
            ) : (
              <label htmlFor="activity-beneficiary-file" className={styles.dropzone}>
                <Icon icon={UploadCloud} size={26} className={styles.dropzoneIcon} />
                <span className={styles.dropzoneTitle}>Choose a beneficiary file</span>
                <span className={styles.dropzoneHint}>CSV or Excel · Kobo/ODK exports supported</span>
              </label>
            )}
            <input
              id="activity-beneficiary-file"
              type="file"
              accept=".csv,.txt,.xlsx,.xls"
              onChange={(e) => setFile(e.target.files?.[0] ?? null)}
              className={styles.visuallyHidden}
            />
          </div>
        )}
      </div>
    </Modal>
  )
}
