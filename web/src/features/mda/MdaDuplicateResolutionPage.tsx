import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { CheckCircle2, ChevronRight, History, Link2, Scale, Search, ShieldCheck, SkipForward } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Modal } from '@/components/Modal/Modal'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { useToast } from '@/components/Toast/ToastProvider'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useDuplicateQueue, useResolveMatch } from '@/features/registry/hooks'
import type { DuplicateQueueRow } from '@/features/registry/types'
import { MATCH_BAND_LABELS, RESOLUTION_LABELS } from '@/features/registry/constants'
import { DuplicateSearchPage } from '@/features/registry/DuplicateSearchPage'
import { MatchComparison } from '@/features/registry/MatchComparison'
import { MatchRevealPanel } from '@/features/registry/MatchRevealPanel'
import { MatchStrengthBand } from '@/features/registry/MatchStrengthBand'
import { ResolveRowControls } from '@/features/registry/ResolveRowControls'
import type { ImportBatch, ImportRow } from '@/features/registry/types'
import { when as formatWhen } from './format'
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


const personName = (row: ImportRow): string =>
  [row.preview.first_name, row.preview.last_name].filter(Boolean).join(' ') || `Row ${row.row_number}`

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

/* ------------------------------------------------------------ bulk decisions */

type BulkDecision = 'link' | 'skip'

/** Rows per page. The queue is worked through, not scanned, so a screenful is enough. */
const PAGE_SIZE = 25

/**
 * Selection + the run loop for deciding many matches at once.
 *
 * Rows here come from DIFFERENT import batches, so each decision carries its own batch
 * id — a hook bound to one batch cannot serve this page.
 */
function useBulkMatchDecision(items: MatchItem[], keyOf: (item: MatchItem) => string) {
  const resolve = useResolveMatch()
  const toast = useToast()
  const [selected, setSelected] = useState<Set<string>>(new Set())
  const [progress, setProgress] = useState<{ done: number; total: number } | null>(null)

  const toggle = (id: string) =>
    setSelected((current) => {
      const next = new Set(current)
      if (next.has(id)) next.delete(id)
      else next.add(id)
      return next
    })

  const toggleAll = (ids: string[], nextSelected: boolean) =>
    setSelected((current) => {
      const next = new Set(current)
      for (const id of ids) {
        if (nextSelected) next.add(id)
        else next.delete(id)
      }
      return next
    })

  async function run(decision: BulkDecision) {
    // Read from the live list, never from the selection alone: a row decided elsewhere
    // since selecting must not be decided again.
    const targets = items.filter((m) => selected.has(keyOf(m)) && !m.row.resolution)
    if (targets.length === 0) return

    const failed = new Set<string>()
    const ambiguous = new Set<string>()
    setProgress({ done: 0, total: targets.length })

    for (const [index, item] of targets.entries()) {
      try {
        // Each row acts on ITS OWN matched record — there is no one beneficiary across a
        // selection — and OWNERSHIP decides whether that act is a request-to-serve or an
        // intervention on a person this MDA already has.
        if (decision === 'link') {
          const target = existingTargetFor(item.row)

          if (target === undefined || target === 'ambiguous') {
            ;(target === 'ambiguous' ? ambiguous : failed).add(keyOf(item))
          } else {
            await resolve.mutateAsync({
              batchId: item.batch.id,
              rowNumber: item.row.row_number,
              input: { resolution: target.resolution, beneficiary_id: target.beneficiaryId },
            })
          }
        } else {
          await resolve.mutateAsync({
            batchId: item.batch.id,
            rowNumber: item.row.row_number,
            input: { resolution: decision },
          })
        }
      } catch {
        failed.add(keyOf(item))
      }
      setProgress({ done: index + 1, total: targets.length })
    }

    setProgress(null)

    // Anything undecided stays selected, so the officer can see exactly what is left.
    const undecided = new Set([...failed, ...ambiguous])
    setSelected(undecided)

    const saved = targets.length - undecided.size
    if (undecided.size === 0) {
      toast.success(
        `${saved} ${saved === 1 ? 'match' : 'matches'} decided`,
        `${RESOLUTION_LABELS[decision]} — each recorded in the audit log.`,
      )
    } else {
      // Naming the REASON: "could not be saved" gave the officer nothing to act on, and
      // needing an individual decision is not a failure.
      const reasons = [
        ambiguous.size > 0
          ? `${ambiguous.size} matched more than one record and need an individual decision`
          : '',
        failed.size > 0 ? `${failed.size} could not be saved` : '',
      ].filter(Boolean)

      toast.error(
        `${saved} of ${targets.length} decided`,
        `${reasons.join('; ')}. They are still selected.`,
      )
    }
  }

  return { selected, toggle, toggleAll, clear: () => setSelected(new Set()), run, progress, busy: resolve.isPending }
}

/** The existing record a row acts on, and which act OWNERSHIP permits. */
interface ExistingTarget {
  beneficiaryId: string
  resolution: 'link' | 'own'
}

/**
 * Resolve a row's single matched record, or say why it cannot be decided in bulk.
 *
 * Ownership decides the ACT, not the officer's choice of button. A match on a record
 * this MDA already owns is `own` — record an intervention on the person already there;
 * a match on another MDA's record is `link` — raise a request-to-serve. This used to
 * send `link` for both, so re-uploading your own list asked the server for permission to
 * serve your own beneficiary. The server refuses that outright, and the refusal surfaced
 * only as an anonymous "N could not be saved" — on the batch path most officers reach
 * first.
 *
 * More than one candidate is `'ambiguous'`: the single-row control makes the officer
 * pick which record, and picking the first arbitrarily in bulk would decide that for
 * them, silently.
 */
function existingTargetFor(row: ImportRow): ExistingTarget | 'ambiguous' | undefined {
  const candidates = row.match.candidates.filter((c) => c.type === 'registry' && c.reveal?.id)
  if (candidates.length === 0) return undefined
  if (candidates.length > 1) return 'ambiguous'

  const [candidate] = candidates
  return {
    beneficiaryId: candidate.reveal!.id!,
    resolution: candidate.owned_by_you ? 'own' : 'link',
  }
}

/**
 * What can be decided in bulk, and what deliberately cannot.
 *
 * **Exact** matches are settled by a unique identifier, so no same-person judgement
 * remains — only whether to serve against the existing record or discard. Both are safe
 * to apply to many rows at once.
 *
 * **Probable** matches are a judgement about whether two records are one human
 * (FR-DUP-09). Bulk "provide service" would assert sameness across many people without
 * anyone looking, which is the auto-merge CLAUDE.md §9 forbids — so only **discard** is
 * offered here, because discarding creates nothing and asserts nothing about identity.
 */
function BulkDecisionBar({
  band,
  count,
  progress,
  busy,
  onDecide,
  onConfirmDiscard,
  onClear,
}: {
  band?: 'exact' | 'probable'
  count: number
  progress: { done: number; total: number } | null
  busy: boolean
  onDecide: (decision: BulkDecision) => void
  onConfirmDiscard: () => void
  onClear: () => void
}) {
  const canBulkLink = band === 'exact'

  return (
    <div className={styles.bulkBar}>
      <span className={styles.bulkCount}>
        {progress ? `Saving ${progress.done} of ${progress.total}…` : `${count} selected`}
      </span>

      {!canBulkLink && (
        <span className={styles.bulkNote}>
          A possible match is a judgement about one person — decide those individually.
        </span>
      )}

      <span className={styles.spacer} />

      {canBulkLink && (
        <Button size="sm" leftIcon={Link2} onClick={() => onDecide('link')} loading={busy}>
          Provide service
        </Button>
      )}
      <Button size="sm" variant="danger" leftIcon={SkipForward} onClick={onConfirmDiscard} loading={busy}>
        Discard
      </Button>
      <Button size="sm" variant="tertiary" onClick={onClear}>
        Clear
      </Button>
    </div>
  )
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
  /** The band these rows belong to — decides which bulk decisions are offered at all. */
  band,
  /** Bulk is offered only over rows that are actually awaiting a decision. */
  allowBulk = false,
}: {
  items: MatchItem[]
  caption: string
  emptyTitle: string
  canResolve: boolean
  showDecision: boolean
  band?: 'exact' | 'probable'
  allowBulk?: boolean
}) {
  const navigate = useNavigate()
  const [open, setOpen] = useState<string | null>(null)
  const keyOf = (item: MatchItem) => `${item.batch.id}:${item.row.row_number}`

  const bulk = useBulkMatchDecision(items, keyOf)
  const bulkEnabled = allowBulk && canResolve && showDecision && items.length > 0

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
    { key: 'at', header: 'Decided', render: (m) => <span className={styles.mono}>{formatWhen(m.row.resolved_at, { year: true })}</span> },
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
  const [confirmDiscard, setConfirmDiscard] = useState(false)

  return (
    <div className={styles.section}>
      {bulkEnabled && bulk.selected.size > 0 && (
        <BulkDecisionBar
          band={band}
          count={bulk.selected.size}
          progress={bulk.progress}
          busy={bulk.busy}
          onDecide={bulk.run}
          onConfirmDiscard={() => setConfirmDiscard(true)}
          onClear={bulk.clear}
        />
      )}

      <Modal
        open={confirmDiscard}
        onClose={() => setConfirmDiscard(false)}
        title={`Discard ${bulk.selected.size} incoming ${bulk.selected.size === 1 ? 'row' : 'rows'}?`}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setConfirmDiscard(false)} disabled={bulk.busy}>
              Cancel
            </Button>
            <Button
              variant="danger"
              loading={bulk.busy}
              onClick={() => {
                setConfirmDiscard(false)
                void bulk.run('skip')
              }}
            >
              Discard {bulk.selected.size}
            </Button>
          </>
        }
      >
        <p className={styles.muted}>
          {bulk.selected.size === 1 ? 'This row' : `These ${bulk.selected.size} rows`} will not be
          registered and no intervention will be recorded against
          {bulk.selected.size === 1 ? ' it' : ' them'}. The existing records they matched are not
          affected. This cannot be undone.
        </p>
        {band === 'probable' && (
          <p className={styles.muted}>
            These are <strong>possible</strong> matches, not settled ones. Any that are not the
            same person are people who will now go unregistered.
          </p>
        )}
      </Modal>

      <DataTable
        caption={caption}
        columns={columns}
        rows={items}
        getRowId={keyOf}
        getRowLabel={(m) => personName(m.row)}
        emptyTitle={emptyTitle}
        selectedIds={bulkEnabled ? bulk.selected : undefined}
        onToggleRow={bulkEnabled ? bulk.toggle : undefined}
        onToggleAll={bulkEnabled ? bulk.toggleAll : undefined}
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


/**
 * Page through the queue.
 *
 * The module previously had none — it fetched the first page of import BATCHES and
 * showed whatever flagged rows those happened to contain, so an officer could not reach
 * the rest of their own backlog and nothing said it was there.
 */
function QueuePager({
  page,
  totalPages,
  total,
  onPage,
}: {
  page: number
  totalPages: number
  total: number
  onPage: (next: number) => void
}) {
  if (totalPages <= 1) return null

  return (
    <nav className={styles.pager} aria-label="Match pages">
      <Button size="sm" variant="tertiary" disabled={page <= 1} onClick={() => onPage(page - 1)}>
        Previous
      </Button>
      <span className={styles.pagerNote}>
        Page {page} of {totalPages} · {total.toLocaleString()} {total === 1 ? 'match' : 'matches'}
      </span>
      <Button size="sm" variant="tertiary" disabled={page >= totalPages} onClick={() => onPage(page + 1)}>
        Next
      </Button>
    </nav>
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

  // The band is a tab, so the tab drives the query. `search` is not a band and runs no
  // queue query at all.
  const [tab, setTab] = useState<'exact' | 'possible' | 'search'>('exact')
  const [page, setPage] = useState(1)
  const band = tab === 'possible' ? 'probable' : 'exact'

  /*
   * ONE paginated request for the flagged rows in scope.
   *
   * This used to fetch page one of the MDA's BATCHES and then fire a detail request per
   * batch, flattening the results in the browser. Three consequences, all silent: only
   * the first page of batches was reachable, so undecided matches in older imports could
   * not be seen from the module meant to clear them; the page blocked until the slowest
   * request landed; and a failed batch request was skipped, quietly shortening the list.
   *
   * `GET /beneficiaries/duplicates` paginates ROWS, which is the thing the officer is
   * actually working through.
   */
  const queue = useDuplicateQueue(
    { band, state, page, per_page: PAGE_SIZE },
    canView && tab !== 'search',
  )

  const counts = queue.data?.counts
  const pagination = queue.data?.pagination

  // The API hands back the row with just enough of its batch attached; the table and the
  // resolve controls both want them as a pair.
  const items: MatchItem[] = (queue.data?.items ?? []).map((row: DuplicateQueueRow) => ({
    batch: row.batch as unknown as MatchItem['batch'],
    row,
  }))
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
   *
   * Both are now query PARAMETERS rather than client-side filters over a fetched
   * corpus, so a page of results is a page of the thing being asked for.
   */

  // Counts come from the server across EVERY import, not from the page in hand — a tab
  // label counting only what had been fetched is how the module understated its backlog.
  const awaitingFor = (b: 'exact' | 'probable') => counts?.[b].awaiting ?? 0
  const totalFor = (b: 'exact' | 'probable') => counts?.[b].total ?? 0

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view duplicate resolution.</p>
      </Card>
    )
  }

  const loading = queue.isLoading

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
          activeId={tab}
          onChange={(id) => {
            setTab(id as 'exact' | 'possible' | 'search')
            // A new band is a new list; carrying page 5 into it would land on nothing.
            setPage(1)
          }}
          items={[
            {
              id: 'exact',
              // Counts the work outstanding, not the total. A tab reading "(45)" when all
              // 45 are already decided invites someone to go looking for nothing.
              label: `Exact matches${awaitingFor('exact') ? ` (${awaitingFor('exact')})` : ''}`,
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
                  <StateFilter
                    value={state}
                    onChange={(next) => {
                      setState(next)
                      setPage(1)
                    }}
                    awaiting={awaitingFor('exact')}
                    total={totalFor('exact')}
                  />
                  <MatchTable
                    items={items}
                    caption="Exact identifier matches"
                    emptyTitle={emptyFor(state, 'exact')}
                    canResolve={canResolve}
                    showDecision
                    band="exact"
                    // Only the awaiting view: bulk exists to clear outstanding work, and
                    // selecting across decided rows invites re-deciding settled ones.
                    allowBulk={state === 'awaiting'}
                  />
                  <QueuePager
                    page={pagination?.page ?? 1}
                    totalPages={pagination?.total_pages ?? 1}
                    total={pagination?.total ?? 0}
                    onPage={setPage}
                  />
                </div>
              ),
            },
            {
              id: 'possible',
              label: `Possible matches${awaitingFor('probable') ? ` (${awaitingFor('probable')})` : ''}`,
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
                  <StateFilter
                    value={state}
                    onChange={(next) => {
                      setState(next)
                      setPage(1)
                    }}
                    awaiting={awaitingFor('probable')}
                    total={totalFor('probable')}
                  />
                  <MatchTable
                    items={items}
                    caption="Probable matches awaiting judgement"
                    emptyTitle={emptyFor(state, 'probable')}
                    canResolve={canResolve}
                    showDecision
                    band="probable"
                    allowBulk={state === 'awaiting'}
                  />
                  <QueuePager
                    page={pagination?.page ?? 1}
                    totalPages={pagination?.total_pages ?? 1}
                    total={pagination?.total ?? 0}
                    onPage={setPage}
                  />
                </div>
              ),
            },
            // Pre-registration search: the same engine, run against details you type in
            // rather than a file. Raising a request-to-serve from a result is the same
            // coordination step "provide service" takes on a matched row.
            ...(canSearch
              ? [{ id: 'search', label: 'Search before serving', content: <DuplicateSearchPage embedded /> }]
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
