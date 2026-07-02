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
import type { ImportBatch } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const SOURCE_OPTIONS = [
  { value: '', label: 'Excel / CSV (auto-detect)' },
  { value: 'kobo', label: 'Kobo Collect export' },
  { value: 'odk', label: 'ODK export' },
]

export function ImportListPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canImport = hasPermission('beneficiary.create')
  const navigate = useNavigate()

  const [page, setPage] = useState(1)
  const [source, setSource] = useState('')
  const [file, setFile] = useState<File | null>(null)
  const [uploadError, setUploadError] = useState<string | null>(null)

  const { data, isLoading } = useImports(page, canView)
  const uploadImport = useUploadImport()

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view imports.</p>
      </Card>
    )
  }

  async function submitUpload() {
    if (!file) {
      setUploadError('Choose a file to upload.')
      return
    }
    setUploadError(null)
    try {
      const batch = await uploadImport.mutateAsync({ file, source: source || undefined })
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
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Bulk import</h1>
        </div>
      </div>

      {canImport && (
        <Card title="Upload a file" eyebrow="Excel · CSV · Kobo · ODK" className={styles.stack}>
          {uploadError && (
            <p className={layout.alert} role="alert">
              {uploadError}
            </p>
          )}
          <div className={layout.grid2}>
            <SelectField label="Source" options={SOURCE_OPTIONS} value={source} onChange={(e) => setSource(e.target.value)} />
          </div>
          <FileField
            label="File"
            accept=".csv,.xlsx,.xls"
            helper="Excel (.xlsx/.xls) or CSV · max 10 MB. Rows are validated before anything is saved."
            onFilesSelected={(files) => setFile(files[0] ?? null)}
          />
          <div>
            <Button leftIcon={Upload} onClick={submitUpload} loading={uploadImport.isPending}>
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
