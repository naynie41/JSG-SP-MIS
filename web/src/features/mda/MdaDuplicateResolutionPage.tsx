import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useQueries } from '@tanstack/react-query'
import { CheckCircle2, ChevronRight, History, Scale, Search, ShieldCheck } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import { importApi } from '@/features/registry/api'
import { useImports } from '@/features/registry/hooks'
import { MATCH_BAND_LABELS, RESOLUTION_LABELS } from '@/features/registry/constants'
import { DuplicateSearchPage } from '@/features/registry/DuplicateSearchPage'
import { MatchComparison } from '@/features/registry/MatchComparison'
import { MatchRevealPanel } from '@/features/registry/MatchRevealPanel'
import { MatchStrengthBand } from '@/features/registry/MatchStrengthBand'
import { ResolveRowControls } from '@/features/registry/ResolveRowControls'
import type { ImportBatch, ImportRow } from '@/features/registry/types'
import styles from './mda.module.css'

/**
 * One surfaced match: a flagged import row, plus the batch it belongs to.
 *
 * Rows only exist inside a batch, so every view here is a projection of the same
 * flattened list rather than a separate query per view.
 */
interface MatchItem {
  batch: ImportBatch
  row: ImportRow
}

const flagged = (row: ImportRow): boolean => row.match.band !== 'none'

const personName = (row: ImportRow): string =>
  [row.preview.first_name, row.preview.last_name].filter(Boolean).join(' ') || `Row ${row.row_number}`

function when(iso: string | null): string {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

/* ------------------------------------------------------- decision-state filter */

type DecisionState = 'awaiting' | 'decided' | 'all'

/**
 * Decision state, within a band tab.
 *
 * This is the axis that used to be three extra tabs. As a filter it composes with the
 * band instead of competing with it, so "the exact matches I still have to decide" is
 * one place rather than an intersection the officer has to work out.
 *
 * The awaiting count is on the control because it is the only number that represents
 * WORK — a decided match needs nothing from anyone.
 */
function StateFilter({
  value,
  onChange,
  awaiting,
  total,
}: {
  value: DecisionState
  onChange: (next: DecisionState) => void
  awaiting: number
  total: number
}) {
  const options: { id: DecisionState; label: string }[] = [
    { id: 'awaiting', label: awaiting > 0 ? `Awaiting decision (${awaiting})` : 'Awaiting decision' },
    { id: 'decided', label: 'Decided' },
    { id: 'all', label: total > 0 ? `All (${total})` : 'All' },
  ]

  return (
    <div className={styles.choiceRow} role="group" aria-label="Filter by decision state">
      {options.map((option) => (
        <button
          key={option.id}
          type="button"
          className={option.id === value ? styles.stateChipActive : styles.stateChip}
          aria-pressed={option.id === value}
          onClick={() => onChange(option.id)}
        >
          {option.label}
        </button>
      ))}
    </div>
  )
}

/** Empty copy that says which filter is hiding the rest, not just that nothing is here. */
function emptyFor(state: DecisionState, band: 'exact' | 'probable'): string {
  const noun = band === 'exact' ? 'exact matches' : 'probable matches'

  if (state === 'awaiting') return `No ${noun} awaiting a decision`
  if (state === 'decided') return `No ${noun} decided yet`

  return `No ${noun} surfaced`
}

/* --------------------------------------------------------------- match table */

/**
 * The shared table for every view. What differs between views is which items are
 * passed in and whether a decision can still be taken — never the columns, so an
 * exact match reads the same wherever it appears.
 */
function MatchTable({
  items,
  caption,
  emptyTitle,
  canResolve,
  /** Decisions are only accepted while the batch awaits confirmation (server-enforced). */
  showDecision,
}: {
  items: MatchItem[]
  caption: string
  emptyTitle: string
  canResolve: boolean
  showDecision: boolean
}) {
  const navigate = useNavigate()
  const [open, setOpen] = useState<string | null>(null)
  const keyOf = (item: MatchItem) => `${item.batch.id}:${item.row.row_number}`

  const columns: Column<MatchItem>[] = [
    { key: 'person', header: 'Incoming row', render: (m) => personName(m.row) },
    {
      key: 'band',
      header: 'Match',
      render: (m) => (
        <Badge variant={statusVariant(`match.${m.row.match.band}`)} dot mono>
          {MATCH_BAND_LABELS[m.row.match.band]}
        </Badge>
      ),
    },
    {
      key: 'file',
      header: 'From file',
      render: (m) => (
        <Link to={`/imports/${m.batch.id}`} className={styles.queueNote}>
          {m.batch.original_filename}
        </Link>
      ),
    },
    {
      key: 'outcome',
      header: 'Outcome',
      render: (m) =>
        m.row.resolution ? (
          <Badge variant={statusVariant(`resolution.${m.row.resolution}`)}>{RESOLUTION_LABELS[m.row.resolution]}</Badge>
        ) : (
          <span className={styles.queueNote}>Awaiting a decision</span>
        ),
    },
    { key: 'at', header: 'Decided', render: (m) => <span className={styles.mono}>{when(m.row.resolved_at)}</span> },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (m) => (
        <div className={styles.rowActions}>
          {showDecision && (
            <Button
              size="sm"
              variant="tertiary"
              onClick={() => setOpen((current) => (current === keyOf(m) ? null : keyOf(m)))}
              aria-expanded={open === keyOf(m)}
            >
              {open === keyOf(m) ? 'Close' : m.row.resolution ? 'Revisit decision' : 'Decide'}
            </Button>
          )}
          {/* The full evidence view — the existing adjudication queue, positioned on
              this row. Kept as a link rather than duplicated here. */}
          <Button
            size="sm"
            variant="tertiary"
            rightIcon={ChevronRight}
            onClick={() => navigate(`/imports/${m.batch.id}/adjudicate?row=${m.row.row_number}`)}
          >
            Evidence
          </Button>
        </div>
      ),
    },
  ]

  const expanded = items.find((m) => keyOf(m) === open)

  return (
    <div className={styles.section}>
      <DataTable
        caption={caption}
        columns={columns}
        rows={items}
        getRowId={keyOf}
        getRowLabel={(m) => personName(m.row)}
        emptyTitle={emptyTitle}
      />

      {expanded && (
        <Card
          eyebrow={`${MATCH_BAND_LABELS[expanded.row.match.band]} match · row ${expanded.row.row_number}`}
          title={personName(expanded.row)}
        >
          <div className={styles.section}>
            {expanded.row.match.candidates
              .filter((c) => c.reveal)
              .map((candidate, i) => (
                <div key={`cand-${i}`} className={styles.section}>
                  <MatchStrengthBand
                    score={candidate.score}
                    thresholds={expanded.batch.matching_thresholds}
                    deterministic={candidate.stage === 'deterministic'}
                  />
                  <MatchComparison preview={expanded.row.preview} comparison={candidate.comparison ?? []} />
                  <MatchRevealPanel
                    reveal={candidate.reveal!}
                    eyebrow={candidate.type === 'registry' ? 'Existing record' : 'Earlier row in this file'}
                  />
                </div>
              ))}

            {/*
              The SAME control the adjudication queue uses. It is what enforces
              FR-DUP-09: "not the same person" is offered only for a probable match,
              while discard / provide-service are offered at every band. Gating it here
              too would be a second copy of the rule, free to drift from this one.
            */}
            <ResolveRowControls batchId={expanded.batch.id} row={expanded.row} canResolve={canResolve} />
          </div>
        </Card>
      )}
    </div>
  )
}

/* ---------------------------------------------------------------------- page */

/**
 * Duplicate Resolution — the MDA's view of every match the engine surfaced, and what
 * was decided about it.
 *
 * **Five projections of one list.** Rows live inside an import batch, so the module
 * reads the MDA's batches (`GET /beneficiaries/imports`) and each batch's rows
 * (`GET /beneficiaries/imports/{batch}`) and flattens the flagged ones. Exact /
 * Possible / Pending / Decisions / History are filters over that list, not five
 * different queries.
 *
 * **The band decides which actions exist (FR-DUP-09).**
 *  - An **exact** match is a definitive duplicate. It is shown as established and
 *    offers discard or provide-service only. It is NEVER presented for same-person
 *    adjudication — and the server refuses `new` on an exact row with
 *    `ADJUDICATION_NOT_ALLOWED` even if a client tried.
 *  - A **probable** (fuzzy) match is genuinely uncertain, so it additionally offers the
 *    same-person judgement.
 *
 * That asymmetry is not implemented here. It lives in {@link ResolveRowControls}, which
 * this module mounts unchanged — the one place the rule is written on the client.
 *
 * **Provide-service raises a request-to-serve**, opening read access without moving
 * ownership; every decision is audited by `resolveRow` (`import.row_resolved`) with the
 * actor, the choice, the justification and the matched record.
 *
 * Terminology: the scope calls the fuzzy band "possible"; the engine and the API call it
 * `probable`. The tab is labelled "Possible matches" for the officer, and the value
 * stays `probable` on the wire — this module does not introduce a third name for it.
 */
export function MdaDuplicateResolutionPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const canResolve = hasPermission('beneficiary.create')
  const canSearch = hasPermission('beneficiary-lookup.view')

  // Defaults to the work: the page exists to get undecided matches decided, and opening
  // it on a mixed list makes the officer filter before they can start.
  const [state, setState] = useState<DecisionState>('awaiting')

  const { data: batchPage, isLoading: batchesLoading } = useImports(1, canView)
  const batches = batchPage?.items ?? []

  /*
   * One detail request per batch, in parallel.
   *
   * The row-level views cannot be built from the batch list alone — it carries counts,
   * not bands — and there is no cross-batch rows endpoint. Fetching the current page of
   * batches is the compositional way to get there, and each query is keyed exactly as
   * `useImportBatch` keys it (`['import', id]`), so a decision taken through
   * `useResolveRow` invalidates the right entry and these views refresh themselves.
   *
   * If an MDA ever accumulates enough batches for this to hurt, the fix is a
   * `GET /beneficiaries/duplicates` projection on the server — not a cache here.
   */
  const details = useQueries({
    queries: batches.map((b) => ({
      queryKey: ['import', b.id],
      queryFn: () => importApi.get(b.id),
      enabled: canView,
    })),
  })

  const detailsLoading = details.some((q) => q.isLoading)

  // Not memoised: `useQueries` returns a fresh array every render, so a memo keyed on it
  // would recompute anyway — and the flattened list is a page of batches, not a corpus.
  const items: MatchItem[] = []
  for (const query of details) {
    const batch = query.data
    if (!batch) continue
    for (const row of batch.rows ?? []) {
      if (flagged(row)) items.push({ batch, row })
    }
  }
  // Undecided first — that is the work. Then newest decision first.
  items.sort((a, b) => {
    if (!a.row.resolved_at && b.row.resolved_at) return -1
    if (a.row.resolved_at && !b.row.resolved_at) return 1
    return (b.row.resolved_at ?? '').localeCompare(a.row.resolved_at ?? '')
  })

  /*
   * Two INDEPENDENT axes, and only one of them is a tab.
   *
   * The band — exact vs probable — decides what the officer is being asked (§9: an exact
   * identifier match is settled; only a probable one asks "same person?"). That is a
   * different job, so it is a different tab.
   *
   * Decision state — awaiting vs decided — is the same job at a different stage. It used
   * to be three more tabs ("Pending reviews", "Duplicate decisions", "Match history"),
   * which flattened two axes into one row: "Match history" was exactly Exact + Possible
   * combined, and a row could be counted in three tabs at once. It is a filter now.
   */
  const exact = items.filter((m) => m.row.match.band === 'exact')
  const possible = items.filter((m) => m.row.match.band === 'probable')

  // Only a batch still awaiting confirmation can take a decision (server-enforced), so
  // "awaiting" means work that can actually be done, not merely work left undone.
  const byState = (list: MatchItem[]): MatchItem[] => {
    if (state === 'awaiting') return list.filter((m) => !m.row.resolution && m.batch.status === 'preview_ready')
    if (state === 'decided') return list.filter((m) => m.row.resolution !== null)
    return list
  }

  const awaitingCount = (list: MatchItem[]) =>
    list.filter((m) => !m.row.resolution && m.batch.status === 'preview_ready').length

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view duplicate resolution.</p>
      </Card>
    )
  }

  const loading = batchesLoading || detailsLoading

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Duplicate Resolution</h1>
        <p className={styles.lead}>
          Every match the screening engine surfaced against your imports, and what was decided about it. An exact
          identifier match is a settled duplicate; a probable match is a judgement call, and only it asks you whether
          this is the same person.
        </p>
      </header>

      {loading ? (
        <div className={styles.pageLoading}>
          <Spinner size={26} label="Loading surfaced matches" />
        </div>
      ) : (
        <Tabs
          items={[
            {
              id: 'exact',
              // Counts the work outstanding, not the total. A tab reading "(45)" when all
              // 45 are already decided invites someone to go looking for nothing.
              label: `Exact matches${awaitingCount(exact) ? ` (${awaitingCount(exact)})` : ''}`,
              content: (
                <div className={styles.section}>
                  <div className={styles.queue}>
                    <div className={styles.queueHead}>
                      <Icon icon={ShieldCheck} size={16} />
                      <h3 className={styles.queueTitle}>Definitive duplicates</h3>
                    </div>
                    <p className={styles.queueNote}>
                      These rows matched an existing record on a unique identifier, so they are the same person as a
                      matter of fact, not of opinion. There is no same-person question to answer — decide only whether
                      to provide service against the existing record or discard the row.
                    </p>
                  </div>
                  <StateFilter value={state} onChange={setState} awaiting={awaitingCount(exact)} total={exact.length} />
                  <MatchTable
                    items={byState(exact)}
                    caption="Exact identifier matches"
                    emptyTitle={emptyFor(state, 'exact')}
                    canResolve={canResolve}
                    showDecision
                  />
                </div>
              ),
            },
            {
              id: 'possible',
              label: `Possible matches${awaitingCount(possible) ? ` (${awaitingCount(possible)})` : ''}`,
              content: (
                <div className={styles.section}>
                  <div className={styles.queue}>
                    <div className={styles.queueHead}>
                      <Icon icon={Scale} size={16} />
                      <h3 className={styles.queueTitle}>Needs a judgement</h3>
                    </div>
                    <p className={styles.queueNote}>
                      A fuzzy match on name, date of birth or locality — close enough to raise, not close enough to
                      settle. Answer whether it is the same person; if it is not, a justification is required and
                      recorded.
                    </p>
                  </div>
                  <StateFilter value={state} onChange={setState} awaiting={awaitingCount(possible)} total={possible.length} />
                  <MatchTable
                    items={byState(possible)}
                    caption="Probable matches awaiting judgement"
                    emptyTitle={emptyFor(state, 'probable')}
                    canResolve={canResolve}
                    showDecision
                  />
                </div>
              ),
            },
            // Pre-registration search: the same engine, run against details you type in
            // rather than a file. Raising a request-to-serve from a result is the same
            // coordination step "provide service" takes on a matched row.
            ...(canSearch
              ? [{ id: 'search', label: 'Search before serving', content: <DuplicateSearchPage /> }]
              : []),
          ]}
        />
      )}

      <section className={styles.section} aria-label="How duplicates are resolved">
        <div className={styles.sectionHead}>
          <Icon icon={History} size={16} />
          <h2 className={styles.sectionTitle}>How a duplicate is settled</h2>
        </div>
        <Card>
          <p className={styles.muted}>
            <Icon icon={CheckCircle2} size={14} /> Providing service links the incoming row to the existing record and
            raises a request-to-serve with its owner — read access, never a transfer of ownership. Discarding drops the
            row. Creating a new record is available only where the match is genuinely uncertain, and always with a
            justification.
          </p>
          <p className={styles.footnote}>
            Screening runs on the configured thresholds; changing them never rewrites a decision already recorded
          </p>
        </Card>
      </section>

      {canSearch && (
        <p className={styles.footnote}>
          <Icon icon={Search} size={13} /> Searching before you register is the cheapest way to avoid a duplicate
          entirely
        </p>
      )}
    </div>
  )
}
