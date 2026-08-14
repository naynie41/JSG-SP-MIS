import { useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { ArrowRight, CheckCircle2, SkipForward, UserPlus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { TextareaField } from '@/components/Field/TextareaField'
import { Spinner } from '@/components/Spinner/Spinner'
import { useToast } from '@/components/Toast/ToastProvider'
import { useAuth } from '@/lib/auth/AuthProvider'
import { IMPORT_STATUS_LABELS, MATCH_BAND_LABELS, RESOLUTION_LABELS } from './constants'
import { useConfirmActivityImport, useConfirmImport, useImportBatch, useResolveRow } from './hooks'
import { ImportMappingPanel } from './ImportMappingPanel'
import { MatchComparison } from './MatchComparison'
import { MatchRevealPanel } from './MatchRevealPanel'
import { MatchStrengthBand } from './MatchStrengthBand'
import { ResolveRowControls } from './ResolveRowControls'
import type { ImportRow } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const isFlagged = (row: ImportRow) => row.match.band !== 'none'

/**
 * Row filters. An import's whole purpose is the flagged subset — typically ~8%
 * of the file — so the page defaults to "Needs review" rather than making the
 * officer scan thousands of rows for warning badges by eye.
 */
type RowFilter = 'review' | 'errors' | 'resolved' | 'all'

const ROW_FILTERS: { value: RowFilter; label: string; match: (r: ImportRow) => boolean }[] = [
  { value: 'review', label: 'Needs review', match: (r) => isFlagged(r) && !r.resolution },
  { value: 'errors', label: 'Errors', match: (r) => !r.is_valid },
  { value: 'resolved', label: 'Resolved', match: (r) => Boolean(r.resolution) },
  { value: 'all', label: 'All rows', match: () => true },
]

const rowName = (r: ImportRow) =>
  [r.preview.first_name, r.preview.last_name].filter(Boolean).join(' ') || `row ${r.row_number}`

export function ImportBatchPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canResolve = hasPermission('beneficiary.create')

  const navigate = useNavigate()
  const { data: batch, isLoading } = useImportBatch(id, canView)
  const confirmImport = useConfirmImport()
  const confirmActivityImport = useConfirmActivityImport()
  // Bulk runs report once at the end; a toast per row would bury the summary.
  const bulkResolve = useResolveRow(id ?? '', { silent: true })
  const toast = useToast()

  const [expanded, setExpanded] = useState<Set<string>>(new Set())
  const [selected, setSelected] = useState<Set<string>>(new Set())
  const [bulkNewOpen, setBulkNewOpen] = useState(false)
  const [bulkNote, setBulkNote] = useState('')
  const [filter, setFilter] = useState<RowFilter>('review')
  const [bulkProgress, setBulkProgress] = useState<{ done: number; total: number } | null>(null)
  const [confirmOpen, setConfirmOpen] = useState(false)

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
  // Captured after the loading guard: renderExpanded is a hoisted function, so
  // TS cannot carry the narrowing on `batch` into it.
  const thresholds = batch.matching_thresholds ?? null
  const rows = batch.rows ?? []
  const isPreviewReady = batch.status === 'preview_ready'
  // Activity-wizard preview (§10): unbound batch → confirm creates the activity first.
  const isWizard = batch.activity_id === null && batch.draft_activity_name !== null
  const confirmingActivity = confirmImport.isPending || confirmActivityImport.isPending

  function onConfirm() {
    setConfirmOpen(false)
    if (!id) return
    if (isWizard) {
      // Land on the new activity's detail page (the "View Activity" post-commit view).
      confirmActivityImport.mutate(id, { onSuccess: (result) => navigate(`/activities/${result.activity.id}`) })
    } else {
      confirmImport.mutate(id)
    }
  }
  const isProcessing = batch.status === 'pending' || batch.status === 'processing' || batch.status === 'committing'
  const unresolvedFlagged = rows.filter((r) => isFlagged(r) && !r.resolution).length
  const activeFilter = ROW_FILTERS.find((f) => f.value === filter) ?? ROW_FILTERS[3]!
  const visibleRows = rows.filter(activeFilter.match)

  function toggle(set: Set<string>, key: string): Set<string> {
    const next = new Set(set)
    if (next.has(key)) next.delete(key)
    else next.add(key)
    return next
  }

  /**
   * Apply one decision to every selected row.
   *
   * Sequential by necessity — there is no batch endpoint — but a failure part
   * way through must not vanish. Previously an unhandled rejection left the
   * selection stuck and told the officer nothing; now the loop records how far
   * it got, keeps the rows it could not save selected so they can be retried,
   * and always reports.
   */
  async function runBulk(resolution: 'skip' | 'new', note?: string) {
    const targets = rows.filter((r) => selected.has(String(r.row_number)))
    if (targets.length === 0) return

    const failed = new Set<string>()
    setBulkProgress({ done: 0, total: targets.length })

    for (const [index, row] of targets.entries()) {
      try {
        await bulkResolve.mutateAsync({ rowNumber: row.row_number, input: { resolution, note } })
      } catch {
        failed.add(String(row.row_number))
      }
      setBulkProgress({ done: index + 1, total: targets.length })
    }

    const saved = targets.length - failed.size
    setBulkProgress(null)
    setSelected(failed)
    setBulkNewOpen(false)
    setBulkNote('')

    if (failed.size === 0) {
      toast.success(
        `${saved} ${saved === 1 ? 'row' : 'rows'} decided`,
        `${RESOLUTION_LABELS[resolution]} — recorded in the audit log.`,
      )
    } else {
      toast.error(
        `${saved} of ${targets.length} saved`,
        `${failed.size} could not be saved and ${failed.size === 1 ? 'is' : 'are'} still selected. Try again.`,
      )
    }
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
              </div>
              {/* Strength as a position between the configured thresholds, not a
                  bare decimal that invites deference to the number. */}
              <MatchStrengthBand
                score={candidate.score}
                thresholds={thresholds}
                deterministic={candidate.stage === 'deterministic'}
              />
              {/* The comparison itself: this row in full, beside per-field
                  verdicts. Replaces a chip list of field names that said which
                  fields matched but never how. */}
              <MatchComparison preview={row.preview} comparison={candidate.comparison ?? []} />
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

  /*
   * Mapping comes BEFORE validation and dedup (CLAUDE.md §11). While a batch sits in
   * `mapping_required` there is nothing to preview — no rows are staged and nothing has
   * been screened — so the mapping step replaces the preview rather than sitting above
   * an empty one.
   */
  if (batch.status === 'mapping_required') {
    return (
      <div>
        <div className={layout.pageHead}>
          <div className={layout.pageTitle}>
            <span className="eyebrow">{isWizard ? 'New activity · Upload' : '03 · Registry'}</span>
            <h1 className="t-h1">{batch.original_filename}</h1>
            <Link to="/imports" className={styles.note}>
              ← All imports
            </Link>
          </div>
          <Badge variant={statusVariant(`batch.${batch.status}`)}>
            {IMPORT_STATUS_LABELS[batch.status] ?? batch.status}
          </Badge>
        </div>

        {id && <ImportMappingPanel batchId={id} />}
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
            <Button leftIcon={CheckCircle2} loading={confirmingActivity} onClick={() => setConfirmOpen(true)}>
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
        {/* The mapping this file was read with, kept visible after the fact — "which
            column did we believe held the NIN, and who said so" is the question when a
            record turns out wrong (CLAUDE.md §11). */}
        {batch.mapping?.confirmed_at && (
          <details className={styles.note}>
            <summary>
              Read with a confirmed column mapping
              {batch.mapping.confirmed_by ? ` by ${batch.mapping.confirmed_by}` : ''}
              {batch.mapping.template ? ` · template “${batch.mapping.template.name}”` : ''}
            </summary>
            <dl className={styles.dl}>
              {Object.entries(batch.mapping.column_map ?? {})
                .filter(([, header]) => header !== null)
                .map(([field, header]) => (
                  <div key={field}>
                    <dt>{field}</dt>
                    <dd className={styles.mono}>{header}</dd>
                  </div>
                ))}
            </dl>
          </details>
        )}

        {isPreviewReady && unresolvedFlagged > 0 && (
          // The flagged subset is the work. Rather than leaving the officer to
          // find it in the table, offer the queue that exists to process it.
          <div className={styles.adjudicateCta}>
            <p className={styles.note}>
              {unresolvedFlagged} flagged {unresolvedFlagged === 1 ? 'row needs' : 'rows need'} a
              decision. Unresolved flagged rows create nothing on confirm.
            </p>
            {canResolve && (
              <Button
                variant="secondary"
                rightIcon={ArrowRight}
                onClick={() => navigate(`/imports/${batchId}/adjudicate`)}
              >
                Adjudicate {unresolvedFlagged} {unresolvedFlagged === 1 ? 'row' : 'rows'}
              </Button>
            )}
          </div>
        )}
        {isWizard && batch.target_mismatch && (
          <p className={layout.alert} role="alert" data-variant="warning">
            Heads up: this file has {batch.summary.total_rows}{' '}
            {batch.summary.total_rows === 1 ? 'row' : 'rows'}, but the activity target is{' '}
            {batch.draft_target_beneficiaries}. You can still continue — this is only a warning.
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
          <span className={styles.bulkCount}>
            {bulkProgress
              ? `Saving ${bulkProgress.done} of ${bulkProgress.total}…`
              : `${selected.size} selected`}
          </span>
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
        <div className={styles.rowFilters} role="group" aria-label="Filter rows">
          {ROW_FILTERS.map((f) => {
            const count = rows.filter(f.match).length
            return (
              <button
                key={f.value}
                type="button"
                className={styles.rowFilter}
                aria-pressed={filter === f.value}
                onClick={() => setFilter(f.value)}
              >
                {f.label}
                <span className={styles.rowFilterCount}>{count}</span>
              </button>
            )
          })}
        </div>
        <DataTable
          caption="Import rows"
          columns={columns}
          rows={visibleRows}
          getRowId={(r) => String(r.row_number)}
          getRowLabel={rowName}
          renderExpanded={renderExpanded}
          expandedIds={expanded}
          onToggleExpand={(key) => setExpanded((s) => toggle(s, key))}
          selectedIds={isPreviewReady && canResolve ? selected : undefined}
          onToggleRow={isPreviewReady && canResolve ? (key) => setSelected((s) => toggle(s, key)) : undefined}
          onToggleAll={
            isPreviewReady && canResolve
              ? (ids, nextSelected) =>
                  setSelected((s) => {
                    const next = new Set(s)
                    for (const rowId of ids) {
                      if (nextSelected) next.add(rowId)
                      else next.delete(rowId)
                    }
                    return next
                  })
              : undefined
          }
          emptyTitle={
            isProcessing
              ? 'Parsing rows…'
              : filter === 'review'
                ? 'No rows need review'
                : 'No rows match this filter'
          }
        />
      </div>

      {/* Commit writes real beneficiary records and cannot be undone, and it
          silently discards any flagged row still without a decision. A single
          grey paragraph was the only guard; ConfirmDialog already gates deleting
          one beneficiary, so it certainly should gate this. */}
      <ConfirmDialog
        open={confirmOpen}
        title={isWizard ? 'Create activity and commit?' : 'Commit this import?'}
        confirmLabel={isWizard ? 'Create activity & commit' : 'Confirm & commit'}
        loading={confirmingActivity}
        onConfirm={onConfirm}
        onCancel={() => setConfirmOpen(false)}
      >
        <div className={styles.stack}>
          <p>
            {batch.summary.valid_rows} valid{' '}
            {batch.summary.valid_rows === 1 ? 'row' : 'rows'} will be written to the registry
            {isWizard && batch.draft_activity_name ? ` under "${batch.draft_activity_name}"` : ''}. Rows
            resolved as “provide service” raise Service Requests for the owning MDA to approve.
          </p>
          {unresolvedFlagged > 0 && (
            <p className={layout.alert} role="alert" data-variant="warning">
              {unresolvedFlagged} flagged {unresolvedFlagged === 1 ? 'row has' : 'rows have'} no decision
              and will be discarded — nothing is created for {unresolvedFlagged === 1 ? 'it' : 'them'}.
              This cannot be undone.
            </p>
          )}
        </div>
      </ConfirmDialog>

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
