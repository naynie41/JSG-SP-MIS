import { useState } from 'react'
import { Banknote, Building2, ChevronDown, Coins, HandCoins, Percent, Wallet } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import type { ActivityPerformance, DashboardResponse, DrillFn, ProgrammePerformance, ProgrammeScoring, TrafficLight } from './types'
import styles from './programmes.module.css'

/* ------------------------------------------------------------------ helpers */

const pct = (rate: number | null | undefined): number => Math.round((rate ?? 0) * 100)
const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const name = (n: string | null): string => (n ? titleCase(n) : 'Unnamed')

const SCORE_LABEL: Record<TrafficLight, string> = {
  green: 'On track',
  yellow: 'Lagging',
  red: 'Off track',
  unrated: 'Unrated',
}

/** Accessible traffic-light: a colored dot + a label that is always in the DOM for
 * screen readers (never color-alone), shown inline when `withLabel`. */
function ScoreDot({ light, withLabel }: { light: TrafficLight; withLabel?: boolean }) {
  return (
    <span className={styles.score}>
      <span className={styles.dot} data-score={light} aria-hidden="true" />
      <span className={withLabel ? styles.scoreLabel : 'sr-only'}>{SCORE_LABEL[light]}</span>
    </span>
  )
}

function StatusChip({ status }: { status: string | null }) {
  if (!status) return null
  return (
    <span className={styles.status} data-status={status}>
      {titleCase(status)}
    </span>
  )
}

/** Completion (reached ÷ target) with a score-tinted fill. */
function CompletionBar({ reached, target, light }: { reached: number; target: number; light: TrafficLight }) {
  const rate = target > 0 ? Math.min(1, reached / target) : 0
  return (
    <span className={styles.progressTrack} title={`${num(reached)} of ${num(target)}`}>
      <span className={styles.progressFill} data-score={light} style={{ width: `${Math.max(2, rate * 100)}%` }} />
    </span>
  )
}

/** Spent within allocated (budget utilisation). */
function BudgetBar({ allocated, spent }: { allocated: number; spent: number }) {
  const rate = allocated > 0 ? spent / allocated : 0
  return (
    <span className={styles.budgetTrack}>
      <span className={styles.budgetFill} data-over={spent > allocated} style={{ width: `${Math.min(100, Math.max(2, rate * 100))}%` }} />
    </span>
  )
}

/* --------------------------------------------------- financial dashboard --- */

function Figure({ icon, label, value }: { icon: typeof Wallet; label: string; value: string }) {
  return (
    <div className={styles.figure}>
      <span className={styles.figureLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={styles.figureValue}>{value}</span>
    </div>
  )
}

function FinancialDashboard({ data, programmes }: { data: DashboardResponse; programmes: ProgrammePerformance[] }) {
  const budget = data.metrics.benefits.budget
  const net = data.metrics.population?.net_unique_served ?? 0
  const overBudget = budget.remaining < 0
  const utilPct = pct(budget.utilization_rate)
  const costPerBeneficiary = net > 0 ? Math.round(budget.utilized_value / net) : null

  const ranked = programmes
    .filter((p) => p.budget.allocated > 0 || p.budget.spent > 0)
    .sort((a, b) => b.budget.allocated - a.budget.allocated)

  return (
    <section className={styles.section} aria-label="Financials">
      <div className={styles.sectionHead}>
        <Icon icon={Banknote} size={16} />
        <h2 className={styles.sectionTitle}>Financials</h2>
      </div>

      <div className={styles.figureGrid}>
        <Figure icon={Wallet} label="Allocated" value={formatNaira(budget.allocated)} />
        <Figure icon={Coins} label="Disbursed" value={formatNaira(budget.utilized_value)} />
        <Figure icon={HandCoins} label="Remaining" value={formatNaira(budget.remaining)} />
        <Figure icon={Percent} label="Cost / beneficiary" value={costPerBeneficiary === null ? '—' : formatNaira(costPerBeneficiary)} />
      </div>

      <div className={styles.panel}>
        <div className={styles.disbTop}>
          <span className={styles.disbPct} data-over={overBudget}>
            {utilPct}%
          </span>
          <span className={styles.disbCaption}>of budget {overBudget ? 'exceeded' : 'disbursed'}</span>
        </div>
        <span className={styles.ribbon}>
          <span className={styles.ribbonFill} data-over={overBudget} style={{ width: `${Math.min(100, utilPct)}%` }} />
        </span>

        <table className={styles.budgetTable}>
          <caption className="sr-only">Budget versus actual disbursement by programme</caption>
          <thead>
            <tr>
              <th scope="col">Programme</th>
              <th scope="col">Allocated</th>
              <th scope="col">Disbursed</th>
              <th scope="col" className={styles.barCol}>
                Utilisation
              </th>
            </tr>
          </thead>
          <tbody>
            {ranked.length === 0 ? (
              <tr>
                <td colSpan={4} className={styles.empty}>
                  No budgeted programmes in scope.
                </td>
              </tr>
            ) : (
              ranked.map((p) => (
                <tr key={p.programme_id}>
                  <th scope="row" className={styles.progCell}>
                    {name(p.name)}
                  </th>
                  <td>{formatNaira(p.budget.allocated)}</td>
                  <td>{formatNaira(p.budget.spent)}</td>
                  <td className={styles.barCol}>
                    <span className={styles.barCellInner}>
                      <BudgetBar allocated={p.budget.allocated} spent={p.budget.spent} />
                      <span className={styles.barPct}>{pct(p.budget.utilization_rate)}%</span>
                    </span>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </section>
  )
}

/* ------------------------------------------------------- comparison table --- */

function ComparisonTable({ programmes, onDrill }: { programmes: ProgrammePerformance[]; onDrill?: DrillFn }) {
  // Rated programmes first (by completion desc), unrated last.
  const rows = programmes.slice().sort((a, b) => (b.completion_rate ?? -1) - (a.completion_rate ?? -1))

  return (
    <section className={styles.section} aria-label="Programme comparison">
      <div className={styles.sectionHead}>
        <Icon icon={Building2} size={16} />
        <h2 className={styles.sectionTitle}>Comparison</h2>
      </div>
      <div className={styles.panel}>
        <table className={styles.compareTable}>
          <caption className="sr-only">Target, reached and completion by programme</caption>
          <thead>
            <tr>
              <th scope="col">Programme</th>
              <th scope="col">Target</th>
              <th scope="col">Reached</th>
              <th scope="col" className={styles.barCol}>
                Completion
              </th>
              <th scope="col">Score</th>
            </tr>
          </thead>
          <tbody>
            {rows.map((p) => (
              <tr key={p.programme_id}>
                <th scope="row" className={styles.progCell}>
                  {onDrill ? (
                    <button type="button" className={styles.progDrill} onClick={() => onDrill('programmes', { programme_id: p.programme_id })}>
                      {name(p.name)}
                    </button>
                  ) : (
                    name(p.name)
                  )}
                </th>
                <td>{num(p.target)}</td>
                <td>{num(p.reached)}</td>
                <td className={styles.barCol}>
                  <span className={styles.barCellInner}>
                    <CompletionBar reached={p.reached} target={p.target} light={p.traffic_light} />
                    <span className={styles.barPct}>{p.completion_rate === null ? '—' : `${pct(p.completion_rate)}%`}</span>
                  </span>
                </td>
                <td>
                  <ScoreDot light={p.traffic_light} withLabel />
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </section>
  )
}

/* ------------------------------------------------------- activity drill-down */

function ActivityTable({ activities }: { activities: ActivityPerformance[] }) {
  return (
    <div className={styles.drilldown}>
      <table className={styles.activityTable}>
        <caption className="sr-only">Activities</caption>
        <thead>
          <tr>
            <th scope="col">Activity</th>
            <th scope="col">MDA</th>
            <th scope="col">Status</th>
            <th scope="col">Target</th>
            <th scope="col">Reached</th>
            <th scope="col">Completion</th>
            <th scope="col">Disbursed</th>
            <th scope="col">Score</th>
          </tr>
        </thead>
        <tbody>
          {activities.map((a) => (
            <tr key={a.activity_id}>
              <th scope="row" className={styles.progCell}>
                {name(a.name)}
              </th>
              <td>{a.mda ?? '—'}</td>
              <td>{titleCase(a.status)}</td>
              <td>{num(a.target)}</td>
              <td>{num(a.reached)}</td>
              <td>{a.completion_rate === null ? '—' : `${pct(a.completion_rate)}%`}</td>
              <td>{formatNaira(a.budget.spent)}</td>
              <td>
                <ScoreDot light={a.traffic_light} />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

/* ----------------------------------------------------------- programme card */

function ProgrammeCard({ programme, canDrillDown }: { programme: ProgrammePerformance; canDrillDown: boolean }) {
  const [open, setOpen] = useState(false)
  const p = programme
  const mdaLabel = p.mdas.length === 0 ? '—' : p.mdas.map((m) => m.name ?? '—').join(', ')
  const dates =
    p.start_date || p.end_date ? `${p.start_date ?? '—'} → ${p.end_date ?? '—'}` : 'No scheduled dates'
  const hasActivities = p.activities.length > 0

  return (
    <article className={styles.card}>
      <header className={styles.cardHead}>
        <ScoreDot light={p.traffic_light} />
        <h3 className={styles.cardTitle}>{name(p.name)}</h3>
        <StatusChip status={p.status} />
      </header>

      <p className={styles.implementer}>
        <Icon icon={Building2} size={13} />
        {mdaLabel}
      </p>

      <div className={styles.cardMetric}>
        <div className={styles.cardMetricTop}>
          <span className={styles.metricLabel}>Reached / target</span>
          <span className={styles.metricValue}>
            {num(p.reached)} / {num(p.target)}
            <span className={styles.metricPct}> · {p.completion_rate === null ? 'no target' : `${pct(p.completion_rate)}%`}</span>
          </span>
        </div>
        <CompletionBar reached={p.reached} target={p.target} light={p.traffic_light} />
      </div>

      <div className={styles.cardMetric}>
        <div className={styles.cardMetricTop}>
          <span className={styles.metricLabel}>Budget disbursed</span>
          <span className={styles.metricValue}>
            {formatNaira(p.budget.spent)} / {formatNaira(p.budget.allocated)}
            <span className={styles.metricPct}> · {pct(p.budget.utilization_rate)}%</span>
          </span>
        </div>
        <BudgetBar allocated={p.budget.allocated} spent={p.budget.spent} />
      </div>

      <dl className={styles.cardStats}>
        <div>
          <dt>Coverage</dt>
          <dd>{num(p.coverage_absolute)}</dd>
        </div>
        <div>
          <dt>Remaining</dt>
          <dd>{formatNaira(p.budget.remaining)}</dd>
        </div>
        <div>
          <dt>Cost / beneficiary</dt>
          <dd>{p.cost_per_beneficiary === null ? '—' : formatNaira(p.cost_per_beneficiary)}</dd>
        </div>
        <div>
          <dt>Timeline</dt>
          <dd className={styles.dates}>{dates}</dd>
        </div>
      </dl>

      {canDrillDown && hasActivities && (
        <>
          <button
            type="button"
            className={styles.drillToggle}
            onClick={() => setOpen((v) => !v)}
            aria-expanded={open}
          >
            <Icon icon={ChevronDown} size={14} className={open ? styles.chevronOpen : undefined} />
            {open ? 'Hide' : 'View'} {p.activities.length} {p.activities.length === 1 ? 'activity' : 'activities'}
          </button>
          {open && <ActivityTable activities={p.activities} />}
        </>
      )}
    </article>
  )
}

/* ------------------------------------------------------------------ tab */

const SCORING_LEGEND: { light: TrafficLight; note: (s?: ProgrammeScoring) => string }[] = [
  { light: 'green', note: (s) => `On track ≥ ${pct(s?.green_min)}%` },
  { light: 'yellow', note: (s) => `Lagging ≥ ${pct(s?.yellow_min)}%` },
  { light: 'red', note: (s) => `Off track < ${pct(s?.yellow_min)}%` },
  { light: 'unrated', note: () => 'Unrated (no target set)' },
]

export interface ProgrammesTabProps {
  data: DashboardResponse
  /** Drill-down: filter the whole view to a programme (e.g. click a comparison row). */
  onDrill?: DrillFn
}

/**
 * Programmes tab (Phase 6E, tab 2). Per-programme performance cards (with an
 * activity-level drill-down shown where the viewer is permitted), a cross-programme
 * comparison table, a financial dashboard (budget vs actual, cost per beneficiary,
 * disbursement), and a configurable traffic-light score. All figures come scoped +
 * de-identified from the reporting aggregation layer.
 */
export function ProgrammesTab({ data, onDrill }: ProgrammesTabProps) {
  const { hasPermission } = useAuth()
  const canDrillDown = hasPermission('activity.view')
  const programmes = data.metrics.programme_performance ?? []
  const scoring = data.metrics.programme_scoring

  if (programmes.length === 0) {
    return (
      <div className={styles.page}>
        <p className={styles.empty}>No programmes in scope yet.</p>
      </div>
    )
  }

  return (
    <div className={styles.page}>
      <div className={styles.legend} role="note" aria-label="Traffic-light scoring">
        <span className={styles.legendTitle}>Performance score</span>
        {SCORING_LEGEND.map((item) => (
          <span key={item.light} className={styles.legendItem}>
            <span className={styles.dot} data-score={item.light} aria-hidden="true" />
            {item.note(scoring)}
          </span>
        ))}
      </div>

      <FinancialDashboard data={data} programmes={programmes} />

      <ComparisonTable programmes={programmes} onDrill={onDrill} />

      <section className={styles.section} aria-label="Programme performance">
        <div className={styles.sectionHead}>
          <Icon icon={Wallet} size={16} />
          <h2 className={styles.sectionTitle}>Programme performance</h2>
        </div>
        <div className={styles.cardGrid}>
          {programmes.map((p) => (
            <ProgrammeCard key={p.programme_id} programme={p} canDrillDown={canDrillDown} />
          ))}
        </div>
      </section>
    </div>
  )
}
