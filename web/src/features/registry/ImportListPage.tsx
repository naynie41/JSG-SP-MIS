import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { Upload } from 'lucide-react'
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
import { IMPORT_STATUS_LABELS } from './constants'
import { useImports, useUploadImport } from './hooks'
import { useAllActivities } from '@/features/programmes/hooks'
import type { ImportBatch } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const SOURCE_OPTIONS = [
  { value: '', label: 'Excel / CSV (auto-detect)' },
  { value: 'kobo', label: 'Kobo Collect export' },
  { value: 'odk', label: 'ODK export' },
]

export interface ImportListPageProps {
  /** Render as embedded read-only history: no page header (the host page owns the
   *  heading) and no upload panel. Used by the administration console's oversight
   *  sections — an administrator reviews imports but does not perform them; bulk
   *  ingestion belongs to an acting MDA (CLAUDE.md §10). */
  readOnly?: boolean
}

export function ImportListPage({ readOnly = false }: ImportListPageProps = {}) {
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canImport = !readOnly && hasPermission('beneficiary.create')
  const navigate = useNavigate()

  const [page, setPage] = useState(1)
  const [source, setSource] = useState('')
  const [activityId, setActivityId] = useState('')
  const [file, setFile] = useState<File | null>(null)
  const [uploadError, setUploadError] = useState<string | null>(null)

  const { data, isLoading } = useImports(page, canView)
  const uploadImport = useUploadImport()

  // Activity-first (§9): the upload must name the activity the rows are delivered
  // under. Only this MDA's own activities are offered, and only those that declared
  // they involve beneficiaries — uploading into an activity that declared it has none
  // would contradict what was recorded when it was created (§10).
  const { data: activityPage } = useAllActivities(canImport)
  const activityOptions = (activityPage?.items ?? [])
    .filter((a) => a.involves_beneficiaries && a.status !== 'archived')
    .map((a) => ({ value: a.id, label: a.name }))

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view imports.</p>
      </Card>
    )
  }

  async function submitUpload() {
    if (!activityId) {
      setUploadError('Choose the activity these beneficiaries are being delivered under.')
      return
    }
    if (!file) {
      setUploadError('Choose a file to upload.')
      return
    }
    setUploadError(null)
    try {
      const batch = await uploadImport.mutateAsync({ file, activityId, source: source || undefined })
      navigate(`/imports/${batch.id}`)
    } catch (error) {
      setUploadError(error instanceof ApiError ? error.message : 'Upload failed. Please try again.')
    }
  }

  const columns: Column<ImportBatch>[] = [
    {
      key: 'file',
      header: 'File',
      render: (b) => (
        <div className={styles.cellStack}>
          <Link to={`/imports/${b.id}`}>{b.original_filename}</Link>
          <span className={styles.cellSub} style={{ textTransform: 'capitalize' }}>
            {b.source}
          </span>
        </div>
      ),
    },
    {
      key: 'status',
      header: 'Status',
      render: (b) => <Badge variant={statusVariant(`batch.${b.status}`)}>{IMPORT_STATUS_LABELS[b.status] ?? b.status}</Badge>,
    },
    { key: 'rows', header: 'Rows', align: 'right', render: (b) => b.summary.total_rows },
    { key: 'valid', header: 'Valid', align: 'right', render: (b) => b.summary.valid_rows },
    { key: 'committed', header: 'Committed', align: 'right', render: (b) => b.summary.committed_rows },
  ]

  return (
    <div>
      {!readOnly && (
        <div className={layout.pageHead}>
          <div className={layout.pageTitle}>
            <span className="eyebrow">03 · Registry</span>
            <h1 className="t-h1">Bulk import</h1>
          </div>
        </div>
      )}

      {canImport && (
        <Card title="Upload a file" eyebrow="Excel · CSV · Kobo · ODK" className={styles.stack}>
          {uploadError && (
            <p className={layout.alert} role="alert">
              {uploadError}
            </p>
          )}
          <div className={layout.grid2}>
            <SelectField
              label="Activity"
              required
              placeholder="Select the activity"
              options={activityOptions}
              value={activityId}
              onChange={(e) => setActivityId(e.target.value)}
              helper="Every uploaded row is recorded as an intervention under this activity."
            />
            <SelectField label="Source" options={SOURCE_OPTIONS} value={source} onChange={(e) => setSource(e.target.value)} />
          </div>
          {activityOptions.length === 0 && (
            <p className={layout.alert} role="status">
              Your MDA has no activity that accepts beneficiaries yet. Create one first — an upload is always
              recorded under an activity.
            </p>
          )}
          <FileField
            label="File"
            accept=".csv,.xlsx,.xls"
            helper="Excel (.xlsx/.xls) or CSV · max 10 MB. Rows are validated before anything is saved."
            onFilesSelected={(files) => setFile(files[0] ?? null)}
          />
          <div>
            <Button
              leftIcon={Upload}
              onClick={submitUpload}
              loading={uploadImport.isPending}
              disabled={activityOptions.length === 0}
            >
              Upload &amp; preview
            </Button>
          </div>
        </Card>
      )}

      <div style={{ marginTop: 'var(--space-5)' }}>
        <DataTable
          caption="Import batches"
          columns={columns}
          rows={data?.items ?? []}
          getRowId={(b) => b.id}
          loading={isLoading}
          emptyTitle="No imports yet"
          pagination={{ page, pageCount: data?.pagination?.total_pages ?? 1, onPageChange: setPage }}
        />
      </div>
    </div>
  )
}
