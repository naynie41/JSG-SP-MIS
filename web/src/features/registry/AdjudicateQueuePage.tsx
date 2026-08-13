import { useCallback, useEffect, useMemo } from 'react'
import { Link, useNavigate, useParams, useSearchParams } from 'react-router-dom'
import { ChevronLeft, ChevronRight } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { MATCH_BAND_LABELS } from './constants'
import { useImportBatch } from './hooks'
import { MatchComparison } from './MatchComparison'
import { MatchRevealPanel } from './MatchRevealPanel'
import { MatchStrengthBand } from './MatchStrengthBand'
import { ResolveRowControls } from './ResolveRowControls'
import type { ImportRow } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const isFlagged = (row: ImportRow) => row.match.band !== 'none'
const rowName = (r: ImportRow) =>
  [r.preview.first_name, r.preview.last_name].filter(Boolean).join(' ') || `Row ${r.row_number}`

/**
 * The adjudication queue (FR-DUP-05/09).
 *
 * The import table is not the work — the flagged subset is, typically under a
 * tenth of the file. This route puts one flagged row on screen at a time with
 * the comparison front and centre, so the officer answering "is this the same
 * person?" is looking at evidence rather than hunting a table for warning
 * badges. The table remains the overview and the way back.
 *
 * Resolved rows stay reachable (the officer can revisit a decision) but the
 * queue opens on the first row still awaiting one.
 */
export function AdjudicateQueuePage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canResolve = hasPermission('beneficiary.create')
  const navigate = useNavigate()
  const [params, setParams] = useSearchParams()

  const { data: batch, isLoading } = useImportBatch(id, canView)

  const flagged = useMemo(() => (batch?.rows ?? []).filter(isFlagged), [batch?.rows])

  // Position is in the URL so a specific row can be linked, shared and
  // returned to — and so browser Back steps through the queue.
  const requested = Number(params.get('row'))
  const indexFromUrl = flagged.findIndex((r) => r.row_number === requested)
  const firstUnresolved = flagged.findIndex((r) => !r.resolution)
  const index = indexFromUrl >= 0 ? indexFromUrl : Math.max(0, firstUnresolved)
  const current = flagged[index]

  const go = useCallback(
    (nextIndex: number) => {
      const target = flagged[nextIndex]
      if (!target) return
      setParams({ row: String(target.row_number) }, { replace: true })
    },
    [flagged, setParams],
  )

  const goNextUnresolved = useCallback(() => {
    const after = flagged.findIndex((r, i) => i > index && !r.resolution)
    if (after >= 0) go(after)
    else if (index < flagged.length - 1) go(index + 1)
  }, [flagged, index, go])

  // Arrow keys move through the queue. Decisions are deliberately NOT bound to
  // bare letter keys: this writes an audited judgement about a citizen's
  // identity, and a stray keystroke should never be able to record one.
  useEffect(() => {
    function onKeyDown(event: KeyboardEvent) {
      const target = event.target as HTMLElement | null
      if (target && /^(INPUT|TEXTAREA|SELECT)$/.test(target.tagName)) return
      if (event.metaKey || event.ctrlKey || event.altKey) return
      if (event.key === 'ArrowLeft') {
        event.preventDefault()
        go(index - 1)
      } else if (event.key === 'ArrowRight') {
        event.preventDefault()
        go(index + 1)
      }
    }
    document.addEventListener('keydown', onKeyDown)
    return () => document.removeEventListener('keydown', onKeyDown)
  }, [go, index])

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view imports.</p>
      </Card>
    )
  }

  if (isLoading || !batch) {
    return (
      <div className={styles.pageLoading}>
        <Spinner size={24} label="Loading import" />
      </div>
    )
  }

  const resolvedCount = flagged.filter((r) => r.resolution).length

  if (flagged.length === 0) {
    return (
      <Card>
        <div className={styles.stack}>
          <h1 className="t-h2">Nothing to adjudicate</h1>
          <p className={styles.note}>
            No row in <strong>{batch.original_filename}</strong> matched an existing record, so there is
            no same-person decision to make.
          </p>
          <div>
            <Button variant="secondary" onClick={() => navigate(`/imports/${batch.id}`)}>
              Back to the import
            </Button>
          </div>
        </div>
      </Card>
    )
  }

  return (
    <div className={styles.stack}>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry · Adjudicate</span>
          <h1 className="t-h1">{current ? rowName(current) : 'Adjudicate'}</h1>
          <Link to={`/imports/${batch.id}`} className={styles.note}>
            ← {batch.original_filename}
          </Link>
        </div>
        <div className={styles.rowActions}>
          <span className={styles.queueProgress}>
            {index + 1} of {flagged.length} · {resolvedCount} decided
          </span>
        </div>
      </div>

      {current && (
        <>
          <Card>
            <div className={styles.stack}>
              <div className={styles.candidateMeta}>
                <Badge variant={statusVariant(`match.${current.match.band}`)} dot mono>
                  {MATCH_BAND_LABELS[current.match.band]}
                </Badge>
                <span className={styles.cellSub}>Row {current.row_number}</span>
                {current.resolution && (
                  <Badge variant={statusVariant(`resolution.${current.resolution}`)}>Decided</Badge>
                )}
              </div>

              {current.match.candidates
                .filter((c) => c.reveal)
                .map((candidate, i) => (
                  <div key={`cand-${i}`} className={styles.stack}>
                    <MatchStrengthBand
                      score={candidate.score}
                      thresholds={batch.matching_thresholds}
                      deterministic={candidate.stage === 'deterministic'}
                    />
                    <MatchComparison preview={current.preview} comparison={candidate.comparison ?? []} />
                    <MatchRevealPanel
                      reveal={candidate.reveal!}
                      eyebrow={candidate.type === 'registry' ? 'Existing record' : 'Earlier row in this file'}
                    />
                  </div>
                ))}
            </div>
          </Card>

          <Card>
            <ResolveRowControls
              batchId={batch.id}
              row={current}
              canResolve={canResolve}
              onResolved={goNextUnresolved}
            />
          </Card>
        </>
      )}

      <nav className={styles.queueNav} aria-label="Adjudication queue">
        <Button
          variant="tertiary"
          leftIcon={ChevronLeft}
          disabled={index <= 0}
          onClick={() => go(index - 1)}
        >
          Previous
        </Button>
        <Button
          variant="tertiary"
          rightIcon={ChevronRight}
          disabled={index >= flagged.length - 1}
          onClick={() => go(index + 1)}
        >
          Next
        </Button>
      </nav>
    </div>
  )
}
