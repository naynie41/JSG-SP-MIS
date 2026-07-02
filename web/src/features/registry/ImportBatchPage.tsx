import { Link, useParams } from 'react-router-dom'
import { CheckCircle2 } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { IMPORT_STATUS_LABELS } from './constants'
import { useConfirmImport, useImportBatch } from './hooks'
import type { ImportRow } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

export function ImportBatchPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canConfirm = hasPermission('beneficiary.create')

  const { data: batch, isLoading } = useImportBatch(id, canView)
  const confirmImport = useConfirmImport()

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view imports.</p>
      </Card>
    )
  }

  if (isLoading || !batch) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading import" />
      </div>
    )
  }

  const rows = batch.rows ?? []
  const isPreviewReady = batch.status === 'preview_ready'
  const isProcessing = batch.status === 'pending' || batch.status === 'processing' || batch.status === 'committing'

  const columns: Column<ImportRow>[] = [
    { key: 'row', header: 'Row', align: 'right', render: (r) => <span className={styles.mono}>{r.row_number}</span> },
    {
      key: 'valid',
      header: 'Result',
      render: (r) => (
        <Badge variant={statusVariant(r.is_valid ? 'import.valid' : 'import.error')} dot>
          {r.is_valid ? 'Valid' : 'Error'}
        </Badge>
      ),
    },
    {
      key: 'name',
      header: 'Name',
      render: (r) => [r.preview.first_name, r.preview.last_name].filter(Boolean).join(' ') || '—',
    },
    { key: 'lga', header: 'LGA', render: (r) => r.preview.lga ?? '—' },
    {
      key: 'errors',
      header: 'Errors',
      render: (r) =>
        r.errors.length === 0 ? (
          <span className={styles.cellSub}>—</span>
        ) : (
          <ul className={styles.errorList}>
            {r.errors.map((e, i) => (
              <li key={`${r.row_number}-${i}`}>
                <strong>{e.field}</strong>: {e.message}
              </li>
            ))}
          </ul>
        ),
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">{batch.original_filename}</h1>
          <Link to="/imports" className={styles.note}>
            ← All imports
          </Link>
        </div>
        <div className={styles.rowActions}>
          <Badge variant={statusVariant(`batch.${batch.status}`)}>{IMPORT_STATUS_LABELS[batch.status] ?? batch.status}</Badge>
          {canConfirm && isPreviewReady && (
            <Button
              leftIcon={CheckCircle2}
              loading={confirmImport.isPending}
              onClick={() => id && confirmImport.mutate(id)}
            >
              Confirm &amp; commit {batch.summary.valid_rows} valid
            </Button>
          )}
        </div>
      </div>

      <Card className={styles.stack}>
        <div className={styles.summaryRow}>
          <div className={styles.summaryItem}>
            <span className={styles.summaryValue}>{batch.summary.total_rows}</span>
            <span className={styles.summaryLabel}>Total rows</span>
          </div>
          <div className={styles.summaryItem}>
            <span className={styles.summaryValue}>{batch.summary.valid_rows}</span>
            <span className={styles.summaryLabel}>Valid</span>
          </div>
          <div className={styles.summaryItem}>
            <span className={styles.summaryValue}>{batch.summary.invalid_rows}</span>
            <span className={styles.summaryLabel}>Invalid</span>
          </div>
          <div className={styles.summaryItem}>
            <span className={styles.summaryValue}>{batch.summary.committed_rows}</span>
            <span className={styles.summaryLabel}>Committed</span>
          </div>
        </div>
        {isProcessing && <p className={styles.note}>Processing… this view refreshes automatically.</p>}
        {batch.error && (
          <p className={layout.alert} role="alert">
            {batch.error}
          </p>
        )}
      </Card>

      <div style={{ marginTop: 'var(--space-5)' }}>
        <DataTable
          caption="Import rows"
          columns={columns}
          rows={rows}
          getRowId={(r) => String(r.row_number)}
          emptyTitle={isProcessing ? 'Parsing rows…' : 'No rows to preview'}
        />
      </div>
    </div>
  )
}
