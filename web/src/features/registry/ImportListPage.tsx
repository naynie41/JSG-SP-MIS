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
import { useAllActivities, useProgrammeCatalog } from '@/features/programmes/hooks'
import type { ImportBatch } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

/**
 * Where this file's data came from — the batch's provenance (PRD §6.1).
 *
 * The distinction that matters is SELF-SOURCED (the MDA collected it) versus MINED from
 * an external register. A SOCU upload additionally has to carry each row's SOCU id, so
 * the record can be traced back — the mapping step enforces that.
 *
 * Source is not ownership: a SOCU-mined record is still owned by the first MDA to import
 * it (FR-OWN-01).
 */
const SOURCE_OPTIONS = [
  { value: '', label: 'Our own data: Excel or CSV (auto-detect)' },
  { value: 'kobo', label: 'Our own data: Kobo Collect export' },
  { value: 'odk', label: 'Our own data: ODK export' },
  { value: 'socu', label: 'Mined from SOCU' },
  { value: 'government_system', label: 'Mined from another government system' },
]

export interface ImportListPageProps {
  /**
   * No upload panel: imports are reviewed, not performed.
   *
   * The administration console's oversight sections use this — an administrator reads
   * import history but bulk ingestion belongs to an acting MDA (CLAUDE.md §10).
   */
  readOnly?: boolean
  /**
   * No page header: the host page already owns the heading.
   *
   * SEPARATE from `readOnly` on purpose. They were one flag, so the MDA console could
   * not suppress a duplicate `<h1>` without also losing the upload panel it needs —
   * one prop answering two unrelated questions, and neither answer usable alone.
   */
  embedded?: boolean
}

export function ImportListPage({ readOnly = false, embedded = false }: ImportListPageProps = {}) {
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canImport = !readOnly && hasPermission('beneficiary.create')
  const navigate = useNavigate()

  const [page, setPage] = useState(1)
  const [source, setSource] = useState('')
  const [programmeId, setProgrammeId] = useState('')
  const [activityId, setActivityId] = useState('')
  const [file, setFile] = useState<File | null>(null)
  const [uploadError, setUploadError] = useState<string | null>(null)

  const { data, isLoading } = useImports(page, canView)
  const uploadImport = useUploadImport()

  // Programme-first (§9): the upload names the catalog PROGRAMME the rows are registered
  // under. An activity is optional — it says which MDA-run instance delivered to them,
  // which an intake often does not know yet.
  const { data: catalog } = useProgrammeCatalog(canImport)
  const programmeOptions = (catalog?.items ?? []).map((p) => ({ value: p.id, label: p.name }))

  // Only this MDA's own activities, only those that declared they involve beneficiaries
  // (uploading into one that declared it has none would contradict its own record, §10),
  // and only those running the SELECTED programme — an activity belongs to exactly one,
  // so offering the rest would let the two contradict each other.
  const { data: activityPage } = useAllActivities(canImport)
  const activityOptions = (activityPage?.items ?? [])
    .filter((a) => a.involves_beneficiaries && a.status !== 'archived' && a.programme_id === programmeId)
    .map((a) => ({ value: a.id, label: a.name }))

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view imports.</p>
      </Card>
    )
  }

  async function submitUpload() {
    if (!programmeId) {
      setUploadError('Choose the programme these beneficiaries are being registered under.')
      return
    }
    if (!file) {
      setUploadError('Choose a file to upload.')
      return
    }
    setUploadError(null)
    try {
      const batch = await uploadImport.mutateAsync({
        file,
        programmeId,
        // Omitted rather than sent empty — "no activity" is one value, not two.
        activityId: activityId || undefined,
        source: source || undefined,
      })
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
      {!embedded && (
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
              label="Programme"
              required
              placeholder="Select the programme"
              options={programmeOptions}
              value={programmeId}
              onChange={(e) => {
                // Changing programme invalidates the chosen activity — an activity
                // belongs to one programme, so keeping it would create a contradiction
                // the server would (correctly) reject at upload.
                setProgrammeId(e.target.value)
                setActivityId('')
              }}
              helper="Every uploaded row is registered under this catalog programme."
            />
            <SelectField
              label="Activity (optional)"
              placeholder={
                !programmeId
                  ? 'Select a programme first'
                  : activityOptions.length === 0
                    ? 'No matching activity. Leave blank'
                    : 'No specific activity'
              }
              options={activityOptions}
              disabled={!programmeId || activityOptions.length === 0}
              value={activityId}
              onChange={(e) => setActivityId(e.target.value)}
              helper="Only if you know which of your activities delivered to these people."
            />
          </div>
          <SelectField
            label="Data source"
            options={SOURCE_OPTIONS}
            value={source}
            onChange={(e) => setSource(e.target.value)}
            helper={
              source === 'socu'
                ? 'You will be asked to map each row’s SOCU record ID at the mapping step. Records stay owned by your MDA.'
                : 'Where this data came from. It is recorded on every record, and does not affect who owns them.'
            }
          />
          {programmeOptions.length === 0 && (
            <p className={layout.alert} role="status">
              No catalog programme is available yet. Programmes are created centrally. Ask a System
              Administrator to add one before importing.
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
              // Gated on the PROGRAMME catalog now, not on the MDA owning an activity —
              // having no activity is no longer a reason it cannot register anyone.
              disabled={programmeOptions.length === 0}
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
