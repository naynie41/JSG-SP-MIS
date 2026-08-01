import { useState } from 'react'
import {
  AlertTriangle,
  ArrowRight,
  Building2,
  CheckCircle2,
  ChevronDown,
  ChevronRight,
  CircleDashed,
  Clock,
  GitBranch,
  ListChecks,
  Lock,
  PackageCheck,
  TrendingUp,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import type {
  DashboardResponse,
  OutputIndicator,
  PartnerProgramme,
  PartnerProgrammeActivity,
  ProgrammeStatus,
  TrafficLight,
} from './types'
import shell from './fundingPartner.module.css'
import styles from './partnerProgrammes.module.css'

/* ------------------------------------------------------------------ helpers */

const pct = (rate: number | null | undefined): number => Math.round((rate ?? 0) * 100)
const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const naira = (n: number | null | undefined): string => (n === null || n === undefined ? '—' : formatNaira(n))

/** Humanise a benefit-type value ('agricultural_input' → 'Agricultural input'). */
function humanizeType(value: string): string {
  const spaced = value.replace(/_/g, ' ')
  return spaced.charAt(0).toUpperCase() + spaced.slice(1)
}

function fmtDate(iso: string | null): string {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime()) ? '—' : d.toLocaleDateString(undefined, { month: 'short', year: 'numeric' })
}

const STATUS: Record<ProgrammeStatus, { label: string; icon: LucideIcon }> = {
  completed: { label: 'Completed', icon: CheckCircle2 },
  on_track: { label: 'On Track', icon: TrendingUp },
  at_risk: { label: 'At Risk', icon: AlertTriangle },
  delayed: { label: 'Delayed', icon: Clock },
  unrated: { label: 'Unrated', icon: CircleDashed },
}

const STATUS_ORDER: ProgrammeStatus[] = ['completed', 'on_track', 'at_risk', 'delayed', 'unrated']

/* --------------------------------------------------------------- components */

/** Monthly delivery-value bars (burn / delivery-rate over time). Single-hue magnitude. */
function DeliveryChart({ series }: { series: PartnerProgramme['delivery_series'] }) {
  const max = Math.max(1, ...series.map((p) => p.value))
  const total = series.reduce((sum, p) => sum + p.value, 0)

  if (total === 0) {
    return <p className={styles.chartEmpty}>No deliveries recorded in the trend window yet.</p>
  }

  return (
    <div className={styles.chart} role="img" aria-label={`Monthly delivery value over ${series.length} months`}>
      {series.map((p) => (
        <div key={p.month} className={styles.chartCol} title={`${p.month}: ${formatNaira(p.value)}`}>
          <span className={styles.chartBar} style={{ height: `${Math.max(2, Math.round((p.value / max) * 100))}%` }} data-zero={p.value === 0} />
        </div>
      ))}
    </div>
  )
}

function OutputTable({ rows }: { rows: OutputIndicator[] }) {
  return (
    <div className={styles.tableWrap}>
      <table className={styles.table}>
        <thead>
          <tr>
            <th scope="col">Benefit type</th>
            <th scope="col" className={styles.numHead}>Interventions</th>
            <th scope="col" className={styles.numHead}>Beneficiaries</th>
            <th scope="col" className={styles.numHead}>Women</th>
            <th scope="col" className={styles.numHead}>Children</th>
          </tr>
        </thead>
        <tbody>
          {rows.map((r) => (
            <tr key={r.benefit_type}>
              <td>{humanizeType(r.benefit_type)}</td>
              <td className={styles.numCell}>{num(r.interventions)}</td>
              <td className={styles.numCell}>{num(r.beneficiaries)}</td>
              <td className={styles.numCell}>{num(r.women)}</td>
              <td className={styles.numCell}>{num(r.children)}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

function ActivityTable({ rows }: { rows: PartnerProgrammeActivity[] }) {
  const lightLabel: Record<TrafficLight, string> = { green: 'On target', yellow: 'Behind', red: 'Off target', unrated: 'Unrated' }
  return (
    <div className={styles.tableWrap}>
      <table className={styles.table}>
        <thead>
          <tr>
            <th scope="col">Activity</th>
            <th scope="col">MDA</th>
            <th scope="col">Score</th>
            <th scope="col" className={styles.numHead}>Delivered</th>
            <th scope="col" className={styles.numHead}>Reached</th>
            <th scope="col" className={styles.numHead}>Completion</th>
          </tr>
        </thead>
        <tbody>
          {rows.map((a) => (
            <tr key={a.activity_id}>
              <td>{a.name ?? '—'}</td>
              <td>{a.mda ?? '—'}</td>
              <td>
                <span className={styles.dot} data-light={a.traffic_light} />
                <span className={styles.srOnly}>{lightLabel[a.traffic_light]}</span>
                <span className={styles.dotLabel}>{a.status}</span>
              </td>
              <td className={styles.numCell}>{formatNaira(a.delivered_value)}</td>
              <td className={styles.numCell}>{num(a.reached)}</td>
              <td className={styles.numCell}>{a.completion_rate === null ? '—' : `${pct(a.completion_rate)}%`}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

function ProgrammeCard({ p, canDrill }: { p: PartnerProgramme; canDrill: boolean }) {
  const [open, setOpen] = useState(false)
  const status = STATUS[p.status_light]
  const completionPct = p.target > 0 ? Math.min(100, Math.round((p.reached / p.target) * 100)) : 0
  const deliveredPct = p.allocated > 0 ? Math.min(100, Math.round((p.delivered_value / p.allocated) * 100)) : 0
  const mdaNames = p.mdas.map((m) => m.name).filter((n): n is string => Boolean(n))

  return (
    <article className={styles.card}>
      <div className={styles.cardHead}>
        <div className={styles.cardTitleWrap}>
          <h3 className={styles.cardTitle}>{p.name ?? 'Untitled programme'}</h3>
          <div className={styles.cardMeta}>
            {p.type && <span className={styles.typeChip}>{humanizeType(p.type)}</span>}
            {mdaNames.length > 0 && (
              <span className={styles.metaItem}>
                <Icon icon={Building2} size={12} /> {mdaNames.join(', ')}
              </span>
            )}
            <span className={styles.metaItem}>
              {fmtDate(p.start_date)} – {fmtDate(p.end_date)}
            </span>
          </div>
        </div>
        <span className={styles.statusChip} data-status={p.status_light}>
          <Icon icon={status.icon} size={14} /> {status.label}
        </span>
      </div>

      <div className={styles.cardGrid}>
        {/* Financial performance — DELIVERY value, never spend. */}
        <div className={styles.block}>
          <span className={styles.blockLabel}>Financial performance</span>
          <div className={styles.flow}>
            <div className={styles.flowStage}>
              <span className={styles.flowLabel}>Budget</span>
              <strong className={styles.flowValue}>{formatNaira(p.allocated)}</strong>
            </div>
            <Icon icon={ArrowRight} size={16} className={styles.flowArrow} />
            <div className={styles.flowStage} data-emphasis="true">
              <span className={styles.flowLabel}>Delivered</span>
              <strong className={styles.flowValue}>{formatNaira(p.delivered_value)}</strong>
            </div>
            <Icon icon={ArrowRight} size={16} className={styles.flowArrow} />
            <div className={styles.flowStage}>
              <span className={styles.flowLabel}>Remaining</span>
              <strong className={styles.flowValue}>{formatNaira(p.remaining)}</strong>
            </div>
          </div>
          <div className={styles.bar}>
            <span className={styles.barFill} style={{ width: `${Math.max(2, deliveredPct)}%` }} />
          </div>
          <span className={styles.barCap}>
            <strong>Delivery value</strong>, not spend · {pct(p.utilization_rate)}% delivered · {naira(p.cost_per_beneficiary)}/beneficiary
          </span>
          <DeliveryChart series={p.delivery_series} />
          <span className={styles.chartCap}>Monthly delivery value</span>
        </div>

        {/* Results — target vs actual (absolute), interventions, averages. */}
        <div className={styles.block}>
          <span className={styles.blockLabel}>Results</span>
          <div className={styles.resultHead}>
            <span className={styles.resultBig}>
              {num(p.reached)} <span className={styles.resultOf}>of {num(p.target)}</span>
            </span>
            <span className={styles.resultCap}>reached vs target (absolute)</span>
          </div>
          <div className={styles.bar}>
            <span className={styles.barFillAlt} style={{ width: `${Math.max(2, completionPct)}%` }} />
          </div>
          <span className={styles.barCap}>
            {pct(p.completion_rate)}% completion · {num(p.coverage_absolute)} coverage
          </span>
          <dl className={styles.metrics}>
            <div>
              <dt>Interventions</dt>
              <dd>{num(p.interventions)}</dd>
            </div>
            <div>
              <dt>Avg benefit value</dt>
              <dd>{naira(p.avg_benefit_value)}</dd>
            </div>
            <div>
              <dt>Value / beneficiary</dt>
              <dd>{naira(p.cost_per_beneficiary)}</dd>
            </div>
          </dl>
        </div>
      </div>

      {p.output_indicators.length > 0 && (
        <div className={styles.outputs}>
          <span className={styles.blockLabel}>
            Output indicators <span className={styles.blockHint}>interventions delivered — outputs only</span>
          </span>
          <OutputTable rows={p.output_indicators} />
        </div>
      )}

      {canDrill && p.activities.length > 0 && (
        <div className={styles.drill}>
          <button type="button" className={styles.drillToggle} onClick={() => setOpen((v) => !v)} aria-expanded={open}>
            <Icon icon={open ? ChevronDown : ChevronRight} size={14} />
            {p.activities.length} funded {p.activities.length === 1 ? 'activity' : 'activities'}
          </button>
          {open && <ActivityTable rows={p.activities} />}
        </div>
      )}
    </article>
  )
}

export interface FundingPartnerProgrammesTabProps {
  data: DashboardResponse
  /** Show the activity-level drill-down (gated on `activity.view`). */
  canDrill: boolean
}

/**
 * Programmes & Results (Phase 6P, tab 2) — the funder's M&E view, ABSORBING the old
 * standalone outputs tab (output indicators ARE programme results). Per FUNDED programme:
 * budget → DELIVERED value → remaining (delivery value, never spend), a delivery-rate
 * chart, results (target vs actual, coverage, interventions, averages), a four-state
 * status, and OUTPUT indicators (interventions by type + captured demographic). A
 * Funding → Activities → Outputs framework is computed; Outcomes → Impact is an external,
 * greyed slot ("requires external evaluation data") — never fabricated. Read-only,
 * funded scope, de-identified. Activity drill-down only where permitted.
 */
export function FundingPartnerProgrammesTab({ data, canDrill }: FundingPartnerProgrammesTabProps) {
  const pf = data.metrics.partner_funding

  if (!pf || pf.programmes.length === 0) {
    return (
      <p className={shell.empty}>
        No funded programmes to report yet. Once benefits are delivered under your funded activities, each programme’s
        budget-vs-delivery, results and output indicators appear here.
      </p>
    )
  }

  const programmes = pf.programmes
  const totalInterventions = pf.output_indicators.reduce((sum, o) => sum + o.interventions, 0)
  const statusCounts = STATUS_ORDER.map((key) => ({
    key,
    label: STATUS[key].label,
    icon: STATUS[key].icon,
    count: programmes.filter((p) => p.status_light === key).length,
  })).filter((s) => s.count > 0)

  return (
    <div className={shell.tabBody}>
      {/* ---------- STATUS SUMMARY ---------- */}
      <section className={shell.section} aria-label="Delivery status">
        <div className={shell.sectionHead}>
          <Icon icon={ListChecks} size={16} />
          <h2 className={shell.sectionTitle}>Programmes &amp; results</h2>
          <span className={shell.sectionSub}>{programmes.length} funded · delivery status</span>
        </div>
        <div className={styles.legend}>
          {statusCounts.map((s) => (
            <span key={s.key} className={styles.legendChip} data-status={s.key}>
              <Icon icon={s.icon} size={14} />
              <strong>{s.count}</strong> {s.label}
            </span>
          ))}
        </div>
      </section>

      {/* ---------- PROGRAMME CARDS ---------- */}
      <section className={shell.section} aria-label="Funded programmes">
        <div className={styles.cards}>
          {programmes.map((p) => (
            <ProgrammeCard key={p.programme_id} p={p} canDrill={canDrill} />
          ))}
        </div>
      </section>

      {/* ---------- OUTPUT INDICATORS (ROLLED UP) ---------- */}
      <section className={shell.section} aria-label="Output indicators">
        <div className={shell.sectionHead}>
          <Icon icon={PackageCheck} size={16} />
          <h2 className={shell.sectionTitle}>Output indicators</h2>
          <span className={shell.sectionSub}>Outputs only · rolled up</span>
        </div>
        <div className={shell.panel}>
          {pf.output_indicators.length === 0 ? (
            <p className={styles.muted}>No interventions recorded yet.</p>
          ) : (
            <OutputTable rows={pf.output_indicators} />
          )}
          <p className={styles.outputsFoot}>
            Counts of interventions delivered (benefit records), by type and captured demographic (gender, age). These are
            <strong> outputs</strong> — outcomes (poverty reduction, income, school attendance, food security, employment)
            require external evaluation data and are not computed here.
          </p>
        </div>
      </section>

      {/* ---------- RESULTS FRAMEWORK ---------- */}
      <section className={shell.section} aria-label="Results framework">
        <div className={shell.sectionHead}>
          <Icon icon={GitBranch} size={16} />
          <h2 className={shell.sectionTitle}>Results framework</h2>
        </div>
        <div className={shell.framework}>
          <div className={shell.frameNode}>
            <span className={shell.frameLabel}>Funding</span>
            <span className={shell.frameValue}>{formatNaira(pf.allocated)}</span>
            <span className={shell.frameNote}>committed · {formatNaira(pf.delivered_value)} delivered</span>
          </div>
          <Icon icon={ArrowRight} size={18} className={shell.frameArrow} />
          <div className={shell.frameNode}>
            <span className={shell.frameLabel}>Activities</span>
            <span className={shell.frameValue}>{num(pf.funded_activities)}</span>
            <span className={shell.frameNote}>{num(pf.active_activities)} active · {num(programmes.length)} programmes</span>
          </div>
          <Icon icon={ArrowRight} size={18} className={shell.frameArrow} />
          <div className={shell.frameNode}>
            <span className={shell.frameLabel}>Outputs</span>
            <span className={shell.frameValue}>{num(totalInterventions)}</span>
            <span className={shell.frameNote}>interventions · {num(pf.net_unique_reached)} reached</span>
          </div>
          <Icon icon={ArrowRight} size={18} className={shell.frameArrow} />
          <div className={`${shell.frameNode} ${shell.frameExternal}`} aria-label="Outcomes and impact — requires external evaluation data">
            <span className={shell.frameLabel}>
              <Icon icon={Lock} size={12} /> Outcomes → Impact
            </span>
            <span className={shell.frameExternalNote}>Requires external evaluation data · not computed</span>
          </div>
        </div>
      </section>
    </div>
  )
}
