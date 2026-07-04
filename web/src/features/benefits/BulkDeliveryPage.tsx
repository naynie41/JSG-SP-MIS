import { useState } from 'react'
import { CheckCircle2, Upload } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { SelectField } from '@/components/Field/SelectField'
import { FileField } from '@/components/Field/FileField'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { IMPORT_STATUS_LABELS } from '@/features/registry/constants'
import { useActivities, useProgrammes } from '@/features/programmes/hooks'
import { useBenefitImport, useConfirmBenefitImport, useUploadBenefitImport } from './hooks'
import type { BenefitImportRow } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from '@/features/programmes/programmes.module.css'

export function BulkDeliveryPage() {
  const { hasPermission } = useAuth()
  const canRecord = hasPermission('benefit.create')

  const [programmeId, setProgrammeId] = useState('')
  const [activityId, setActivityId] = useState('')
  const [file, setFile] = useState<File | null>(null)
  const [batchId, setBatchId] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  const programmes = useProgrammes({ status: 'active' }, canRecord)
  const activities = useActivities(programmeId || undefined, Boolean(programmeId))
  const upload = useUploadBenefitImport()
  const confirm = useConfirmBenefitImport()
  const { data: batch } = useBenefitImport(batchId ?? undefined, Boolean(batchId))

  if (!canRecord) {
    return <Card><p className={layout.forbidden}>You do not have permission to record benefits.</p></Card>
  }

  async function startUpload() {
    setError(null)
    if (!activityId || !file) {
      setError('Select an activity and choose a file.')
      return
    }
    try {
      const created = await upload.mutateAsync({ activityId, file })
      setBatchId(created.id)
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Upload failed.')
    }
  }

  const isPreviewReady = batch?.status === 'preview_ready'
  const rows = batch?.rows ?? []

  const columns: Column<BenefitImportRow>[] = [
    { key: 'row', header: 'Row', align: 'right', render: (r) => <span className={styles.mono}>{r.row_number}</span> },
    { key: 'valid', header: 'Result', render: (r) => <Badge variant={statusVariant(r.is_valid ? 'import.valid' : 'import.error')} dot>{r.is_valid ? 'Valid' : 'Error'}</Badge> },
    { key: 'ben', header: 'Beneficiary', render: (r) => (r.resolved_beneficiary_id ? <span className={styles.mono}>#{r.resolved_beneficiary_id.slice(0, 8)}</span> : '—') },
    { key: 'elig', header: 'Eligibility', render: (r) => (r.eligibility_flagged ? <Badge variant="warning" dot>Flagged</Badge> : <span className={styles.note}>OK</span>) },
    {
      key: 'errors',
      header: 'Errors',
      render: (r) => (r.errors.length === 0 ? <span className={styles.note}>—</span> : <span className={styles.note} style={{ color: 'var(--danger-ink)' }}>{r.errors.map((e) => e.message).join('; ')}</span>),
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">04 · Benefits</span>
          <h1 className="t-h1">Bulk delivery</h1>
          <p className={styles.note}>Upload a delivery list keyed to an activity. Rows reference existing beneficiaries — this records benefits, not beneficiaries.</p>
        </div>
      </div>

      <div className={styles.stack}>
        {error && <p className={layout.alert} role="alert">{error}</p>}

        <Card title="Delivery list" eyebrow="Upload">
          <div className={layout.form}>
            <div className={layout.grid2}>
              <SelectField label="Programme" required placeholder="Select a programme" options={(programmes.data?.items ?? []).map((p) => ({ value: p.id, label: p.name }))} value={programmeId} onChange={(e) => { setProgrammeId(e.target.value); setActivityId('') }} />
              <SelectField label="Activity" required placeholder="Select an activity" options={(activities.data?.items ?? []).map((a) => ({ value: a.id, label: a.name }))} value={activityId} onChange={(e) => setActivityId(e.target.value)} disabled={!programmeId} />
            </div>
            <FileField label="File" accept=".csv,.xlsx,.xls" helper="CSV or Excel · columns: beneficiary_id/nin/bvn, benefit_type, quantity, monetary_value (kobo), delivery_date, verification_method…" onFilesSelected={(files) => setFile(files[0] ?? null)} />
            <div>
              <Button leftIcon={Upload} onClick={startUpload} loading={upload.isPending} disabled={!activityId || !file}>Upload &amp; preview</Button>
            </div>
          </div>
        </Card>

        {batch && (
          <Card
            title="Preview"
            eyebrow={IMPORT_STATUS_LABELS[batch.status] ?? batch.status}
            actions={
              isPreviewReady ? (
                <Button leftIcon={CheckCircle2} loading={confirm.isPending} onClick={() => batchId && confirm.mutate(batchId)}>
                  Confirm &amp; commit {batch.summary.valid_rows} valid
                </Button>
              ) : undefined
            }
          >
            <div className={styles.bulkResult} style={{ marginBottom: 'var(--space-4)' }}>
              <Badge variant="neutral">{batch.summary.total_rows} rows</Badge>
              <Badge variant="success">{batch.summary.valid_rows} valid</Badge>
              <Badge variant="danger">{batch.summary.invalid_rows} invalid</Badge>
              <Badge variant="info">{batch.summary.committed_rows} committed</Badge>
            </div>
            {batch.error && <p className={layout.alert} role="alert">{batch.error}</p>}
            <DataTable caption="Delivery rows" columns={columns} rows={rows} getRowId={(r) => String(r.row_number)} emptyTitle="Validating rows…" />
          </Card>
        )}
      </div>
    </div>
  )
}
