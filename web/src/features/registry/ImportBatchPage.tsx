import { useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { CheckCircle2, SkipForward, UserPlus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { TextareaField } from '@/components/Field/TextareaField'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { IMPORT_STATUS_LABELS, MATCH_BAND_LABELS, RESOLUTION_LABELS } from './constants'
import { useConfirmActivityImport, useConfirmImport, useImportBatch, useResolveRow } from './hooks'
import { MatchRevealPanel } from './MatchRevealPanel'
import { ResolveRowControls } from './ResolveRowControls'
import type { ImportRow } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const isFlagged = (row: ImportRow) => row.match.band !== 'none'

export function ImportBatchPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canResolve = hasPermission('beneficiary.create')

  const navigate = useNavigate()
  const { data: batch, isLoading } = useImportBatch(id, canView)
  const confirmImport = useConfirmImport()
  const confirmActivityImport = useConfirmActivityImport()
  const bulkResolve = useResolveRow(id ?? '')

  const [expanded, setExpanded] = useState<Set<string>>(new Set())
  const [selected, setSelected] = useState<Set<string>>(new Set())
  const [bulkNewOpen, setBulkNewOpen] = useState(false)
  const [bulkNote, setBulkNote] = useState('')

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

  const batchId = batch.id
  const rows = batch.rows ?? []
  const isPreviewReady = batch.status === 'preview_ready'
  // Activity-wizard preview (§10): unbound batch → confirm creates the activity first.
  const isWizard = batch.activity_id === null && batch.draft_activity_name !== null
  const confirmingActivity = confirmImport.isPending || confirmActivityImport.isPending

  function onConfirm() {
    if (!id) return
    if (isWizard) {
      confirmActivityImport.mutate(id, { onSuccess: () => navigate('/activities') })
    } else {
      confirmImport.mutate(id)
    }
  }
  const isProcessing = batch.status === 'pending' || batch.status === 'processing' || batch.status === 'committing'
  const unresolvedFlagged = rows.filter((r) => isFlagged(r) && !r.resolution).length

  function toggle(set: Set<string>, key: string): Set<string> {
    const next = new Set(set)
    if (next.has(key)) next.delete(key)
    else next.add(key)
    return next
  }

  async function runBulk(resolution: 'skip' | 'new', note?: string) {
    const targets = rows.filter((r) => selected.has(String(r.row_number)))
    for (const row of targets) {
      await bulkResolve.mutateAsync({ rowNumber: row.row_number, input: { resolution, note } })
    }
    setSelected(new Set())
    setBulkNewOpen(false)
    setBulkNote('')
  }

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
      key: 'match',
      header: 'Match',
      render: (r) =>
        isFlagged(r) ? (
          <Badge variant={statusVariant(`match.${r.match.band}`)} dot mono>
            {MATCH_BAND_LABELS[r.match.band]}
          </Badge>
        ) : (
          <span className={styles.cellSub}>No match</span>
        ),
    },
    {
      key: 'name',
      header: 'Name',
      render: (r) => [r.preview.first_name, r.preview.last_name].filter(Boolean).join(' ') || '—',
    },
    { key: 'lga', header: 'LGA', render: (r) => r.preview.lga ?? '—' },
    {
      key: 'decision',
      header: 'Decision',
      render: (r) =>
        r.resolution ? (
          <Badge variant={statusVariant(`resolution.${r.resolution}`)}>{RESOLUTION_LABELS[r.resolution]}</Badge>
        ) : isFlagged(r) ? (
          <Badge variant="warning">Needs review</Badge>
        ) : (
          <span className={styles.cellSub}>New</span>
        ),
    },
  ]

  function renderExpanded(row: ImportRow) {
    const candidates = row.match.candidates.filter((c) => c.reveal)
    return (
      <div className={styles.reviewPanel}>
        <div className={styles.stack}>
          {row.errors.length > 0 && (
            <ul className={styles.errorList}>
              {row.errors.map((e, i) => (
                <li key={`${row.row_number}-${i}`}>
                  <strong>{e.field}</strong>: {e.message}
                </li>
              ))}
            </ul>
          )}
          {candidates.length === 0 && (
            <p className={styles.note}>
              No duplicate match. This row will be created as a new beneficiary owned by your MDA on confirm.
            </p>
          )}
          {candidates.map((candidate, i) => (
            <div key={`${row.row_number}-cand-${i}`} className={styles.candidate}>
              <div className={styles.candidateMeta}>
                <Badge variant={statusVariant(`match.${candidate.band}`)} dot mono>
                  {MATCH_BAND_LABELS[candidate.band]}
                </Badge>
                <span className={styles.cellSub}>score {candidate.score.toFixed(2)}</span>
                <div className={styles.matchedFields}>
                  {candidate.matched_fields.map((f) => (
                    <Badge key={f} variant="outline" mono>
                      {f}
                    </Badge>
                  ))}
                </div>
              </div>
              <MatchRevealPanel
                reveal={candidate.reveal!}
                eyebrow={candidate.type === 'registry' ? 'Existing record' : 'Earlier row in this file'}
              />
            </div>
          ))}
        </div>
        <div>
          {isFlagged(row) ? (
            <ResolveRowControls batchId={batchId} row={row} canResolve={canResolve} />
          ) : (
            <div className={styles.resolveBox}>
              <span className="eyebrow">Resolve row {row.row_number}</span>
              <p className={styles.note}>Nothing to resolve — no match was found.</p>
            </div>
          )}
        </div>
      </div>
    )
  }

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">{isWizard ? 'New activity · Upload' : '03 · Registry'}</span>
          <h1 className="t-h1">{batch.original_filename}</h1>
          {isWizard ? (
            <p className={styles.note}>
              Preview for new activity <strong>{batch.draft_activity_name}</strong>. On confirm, the activity is
              created and these beneficiaries are saved under it; served duplicates raise pending Service Requests.
            </p>
          ) : (
            <Link to="/imports" className={styles.note}>
              ← All imports
            </Link>
          )}
        </div>
        <div className={styles.rowActions}>
          <Badge variant={statusVariant(`batch.${batch.status}`)}>{IMPORT_STATUS_LABELS[batch.status] ?? batch.status}</Badge>
          {canResolve && isPreviewReady && (
            <Button leftIcon={CheckCircle2} loading={confirmingActivity} onClick={onConfirm}>
              {isWizard ? 'Create activity & commit' : 'Confirm & commit'}
            </Button>
          )}
        </div>
      </div>

      <Card className={styles.stack}>
        <div className={styles.summaryRow}>
          {[
            ['Total rows', batch.summary.total_rows],
            ['Valid', batch.summary.valid_rows],
            ['Invalid', batch.summary.invalid_rows],
            ['Committed', batch.summary.committed_rows],
            ['Served', batch.summary.served_rows],
            ['Skipped', batch.summary.skipped_rows],
          ].map(([label, value]) => (
            <div key={label} className={styles.summaryItem}>
              <span className={styles.summaryValue}>{value}</span>
              <span className={styles.summaryLabel}>{label}</span>
            </div>
          ))}
        </div>
        {isPreviewReady && unresolvedFlagged > 0 && (
          <p className={styles.note}>
            {unresolvedFlagged} flagged {unresolvedFlagged === 1 ? 'row needs' : 'rows need'} a decision. Unresolved
            flagged rows create nothing on confirm.
          </p>
        )}
        {isProcessing && <p className={styles.note}>Processing… this view refreshes automatically.</p>}
        {batch.error && (
          <p className={layout.alert} role="alert">
            {batch.error}
          </p>
        )}
      </Card>

      {isPreviewReady && canResolve && selected.size > 0 && (
        <div className={styles.bulkBar}>
          <span className={styles.bulkCount}>{selected.size} selected</span>
          <span className={styles.spacer} />
          <Button size="sm" variant="tertiary" leftIcon={UserPlus} onClick={() => setBulkNewOpen(true)} loading={bulkResolve.isPending}>
            Create as new…
          </Button>
          <Button size="sm" variant="tertiary" leftIcon={SkipForward} onClick={() => runBulk('skip')} loading={bulkResolve.isPending}>
            Skip selected
          </Button>
          <Button size="sm" variant="tertiary" onClick={() => setSelected(new Set())}>
            Clear
          </Button>
        </div>
      )}

      <div style={{ marginTop: 'var(--space-5)' }}>
        <DataTable
          caption="Import rows"
          columns={columns}
          rows={rows}
          getRowId={(r) => String(r.row_number)}
          renderExpanded={renderExpanded}
          expandedIds={expanded}
          onToggleExpand={(key) => setExpanded((s) => toggle(s, key))}
          selectedIds={isPreviewReady && canResolve ? selected : undefined}
          onToggleRow={isPreviewReady && canResolve ? (key) => setSelected((s) => toggle(s, key)) : undefined}
          emptyTitle={isProcessing ? 'Parsing rows…' : 'No rows to preview'}
        />
      </div>

      <Modal
        open={bulkNewOpen}
        onClose={() => setBulkNewOpen(false)}
        title={`Create ${selected.size} row(s) as new`}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setBulkNewOpen(false)} disabled={bulkResolve.isPending}>
              Cancel
            </Button>
            <Button
              onClick={() => runBulk('new', bulkNote.trim())}
              loading={bulkResolve.isPending}
              disabled={bulkNote.trim() === ''}
            >
              Apply to selected
            </Button>
          </>
        }
      >
        <div className={styles.stack}>
          <p className={styles.note}>
            The same justification is recorded against each selected row and written to the audit log.
          </p>
          <TextareaField
            label="Justification"
            required
            rows={3}
            value={bulkNote}
            onChange={(event) => setBulkNote(event.target.value)}
          />
        </div>
      </Modal>
    </div>
  )
}
