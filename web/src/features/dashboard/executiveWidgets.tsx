/**
 * Shared presentation widgets for the Executive briefing suite.
 *
 * These were defined inside ExecutiveOverviewTab while every figure lived on the
 * Overview page. Distilling that page moved most figures to the section that
 * actually owns the question they answer (delivery to Programmes, demographics
 * to Registry, geography to Coverage), so the widgets had to become shared
 * rather than duplicated.
 */
import { CalendarPlus, TrendingUp, UsersRound, Wallet } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { titleCase } from '@/features/registry/constants'
import { PROJECTION_LABEL } from './forecast'
import type { ExecutiveForecast } from './forecast'
import type { ProgrammePerformance, TrendPoint } from './types'
import { OTHER_COLOR, PROGRAMME_COLORS, monthLong, num } from './executiveFormat'
import styles from './executiveOverview.module.css'
/* ------------------------------------------------------------------ stats */

export interface StatProps {
  icon: LucideIcon
  label: string
  value: string
  hint?: string
}

/** A forest KPI panel (dark) — used for the headline "reach" band. */
export function KpiPanel({ icon, label, value, hint, headline }: StatProps & { headline?: boolean }) {
  return (
    <div className={headline ? `${styles.kpi} ${styles.kpiHeadline}` : styles.kpi}>
      <span className={styles.kpiLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={styles.kpiValue}>{value}</span>
      {hint && <span className={styles.kpiHint}>{hint}</span>}
    </div>
  )
}

/** A light figure card — used for the secondary KPI groups. */
export function Figure({ icon, label, value, hint }: StatProps) {
  return (
    <div className={styles.figure}>
      <span className={styles.figureLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={styles.figureValue}>{value}</span>
      {hint && <span className={styles.figureHint}>{hint}</span>}
    </div>
  )
}

/* ------------------------------------------------------------------ charts */

/** Single-hue area sparkline (change-over-time): forest line, faint fill, an
 * emphasised endpoint, recessive baseline, and native per-point hover tooltips. */
export function Sparkline({ points, format }: { points: TrendPoint[]; format: (n: number) => string }) {
  const W = 320
  const H = 76
  const P = 8
  const n = points.length
  if (n === 0) return <p className={styles.empty}>No data yet.</p>

  const values = points.map((p) => p.value)
  const max = Math.max(1, ...values)
  const min = Math.min(0, ...values)
  const span = max - min || 1
  const x = (i: number): number => (n <= 1 ? W / 2 : P + (i / (n - 1)) * (W - 2 * P))
  const y = (v: number): number => H - P - ((v - min) / span) * (H - 2 * P)

  const line = points.map((p, i) => `${i === 0 ? 'M' : 'L'}${x(i).toFixed(1)} ${y(p.value).toFixed(1)}`).join(' ')
  const area = `${line} L ${x(n - 1).toFixed(1)} ${H - P} L ${x(0).toFixed(1)} ${H - P} Z`
  const last = points[n - 1]

  return (
    <svg className={styles.spark} viewBox={`0 0 ${W} ${H}`} preserveAspectRatio="none" role="img" aria-label={`Trend, latest ${format(last.value)}`}>
      <line x1={P} y1={H - P} x2={W - P} y2={H - P} className={styles.sparkBase} />
      <path d={area} className={styles.sparkArea} />
      <path d={line} className={styles.sparkLine} />
      <circle cx={x(n - 1)} cy={y(last.value)} r={3.5} className={styles.sparkDot} />
      {points.map((p, i) => (
        <circle key={p.month} cx={x(i)} cy={y(p.value)} r={9} className={styles.sparkHit}>
          <title>{`${monthLong(p.month)}: ${format(p.value)}`}</title>
        </circle>
      ))}
    </svg>
  )
}

export function TrendCard({
  title,
  points,
  format,
  delta,
}: {
  title: string
  points: TrendPoint[]
  format: (n: number) => string
  delta?: string
}) {
  const last = points.length > 0 ? points[points.length - 1] : undefined
  return (
    <div className={styles.trendCard}>
      <div className={styles.trendHead}>
        <span className={styles.trendTitle}>{title}</span>
        <span className={styles.trendNow}>{last ? format(last.value) : '—'}</span>
      </div>
      <Sparkline points={points} format={format} />
      {delta && <span className={styles.trendDelta}>{delta}</span>}
    </div>
  )
}

/* -- programme distribution donut (the explicitly-requested pie, as a donut) -- */

function polar(cx: number, cy: number, r: number, angleDeg: number): [number, number] {
  const a = ((angleDeg - 90) * Math.PI) / 180
  return [cx + r * Math.cos(a), cy + r * Math.sin(a)]
}
function arc(cx: number, cy: number, ro: number, ri: number, start: number, end: number): string {
  const [x1, y1] = polar(cx, cy, ro, end)
  const [x2, y2] = polar(cx, cy, ro, start)
  const [x3, y3] = polar(cx, cy, ri, start)
  const [x4, y4] = polar(cx, cy, ri, end)
  const large = end - start > 180 ? 1 : 0
  return `M ${x1} ${y1} A ${ro} ${ro} 0 ${large} 0 ${x2} ${y2} L ${x3} ${y3} A ${ri} ${ri} 0 ${large} 1 ${x4} ${y4} Z`
}

export interface Slice {
  label: string
  value: number
  color: string
  programmeId?: string
}

export function ProgrammeDonut({ programmes, onSelect }: { programmes: ProgrammePerformance[]; onSelect?: (programmeId: string) => void }) {
  const ranked = programmes
    .filter((p) => p.reached > 0)
    .sort((a, b) => b.reached - a.reached)
    .map((p) => ({ programmeId: p.programme_id, label: p.name ? titleCase(p.name) : 'Unnamed', value: p.reached }))

  if (ranked.length === 0) return <p className={styles.empty}>No beneficiaries reached yet.</p>

  const slices: Slice[] = ranked.slice(0, 5).map((r, i) => ({ ...r, color: PROGRAMME_COLORS[i] }))
  const rest = ranked.slice(5)
  if (rest.length > 0) {
    slices.push({ label: `${rest.length} others`, value: rest.reduce((s, r) => s + r.value, 0), color: OTHER_COLOR })
  }
  const total = slices.reduce((s, r) => s + r.value, 0)

  const size = 176
  const cx = size / 2
  const cy = size / 2
  const ro = size / 2 - 4
  const ri = ro - 30
  const GAP = 1.4
  let cursor = 0

  return (
    <div className={styles.donutWrap}>
      <svg width={size} height={size} viewBox={`0 0 ${size} ${size}`} role="img" aria-label="Beneficiary share by programme">
        {slices.map((s) => {
          const sweep = (s.value / total) * 360
          const start = cursor + GAP
          const end = cursor + sweep - GAP
          cursor += sweep
          const mid = (start + end) / 2
          const share = Math.round((s.value / total) * 100)
          const [lx, ly] = polar(cx, cy, (ro + ri) / 2, mid)
          return (
            <g key={s.label}>
              <path
                d={arc(cx, cy, ro, ri, start, Math.max(start, end))}
                fill={s.color}
                className={styles.slice}
                data-drill={onSelect && s.programmeId ? true : undefined}
                onClick={onSelect && s.programmeId ? () => onSelect(s.programmeId!) : undefined}
              >
                <title>{`${s.label}: ${num(s.value)} (${share}%)`}</title>
              </path>
              {share >= 8 && (
                <text x={lx} y={ly} className={styles.sliceLabel} textAnchor="middle" dominantBaseline="central">
                  {share}%
                </text>
              )}
            </g>
          )
        })}
        <text x={cx} y={cy - 6} textAnchor="middle" className={styles.donutCoreValue}>
          {num(total)}
        </text>
        <text x={cx} y={cy + 12} textAnchor="middle" className={styles.donutCoreLabel}>
          reached
        </text>
      </svg>
      <ul className={styles.legend}>
        {slices.map((s) => (
          <li key={s.label} className={styles.legendRow}>
            <span className={styles.legendSwatch} style={{ background: s.color }} aria-hidden="true" />
            {onSelect && s.programmeId ? (
              <button type="button" className={styles.legendDrill} onClick={() => onSelect(s.programmeId!)} title={`View ${s.label} programme`}>
                {s.label}
              </button>
            ) : (
              <span className={styles.legendName} title={s.label}>
                {s.label}
              </span>
            )}
            <span className={styles.legendValue}>{num(s.value)}</span>
          </li>
        ))}
      </ul>
    </div>
  )
}

/* -- ordered/magnitude bars (single forest hue) -- */

export function Bars({ items, format }: { items: { name: string; value: number }[]; format: (n: number) => string }) {
  const max = Math.max(1, ...items.map((i) => i.value))
  if (items.every((i) => i.value === 0)) return <p className={styles.empty}>No data yet.</p>
  return (
    <div className={styles.bars}>
      {items.map((item) => (
        <div key={item.name} className={styles.barRow}>
          <span className={styles.barName}>{item.name}</span>
          <span className={styles.barTrack}>
            <span
              className={styles.barFill}
              style={{ width: `${Math.max(2, Math.round((item.value / max) * 100))}%` }}
              title={`${item.name}: ${format(item.value)}`}
            />
          </span>
          <span className={styles.barValue}>{format(item.value)}</span>
        </div>
      ))}
    </div>
  )
}

/* -- 100% stacked split bar (parts of a whole, few categories) -- */

export function SplitBar({ segments }: { segments: Slice[] }) {
  const total = segments.reduce((s, x) => s + x.value, 0)
  if (total === 0) return <p className={styles.empty}>No data yet.</p>
  return (
    <div className={styles.split}>
      <div className={styles.splitTrack}>
        {segments
          .filter((s) => s.value > 0)
          .map((s) => (
            <span
              key={s.label}
              className={styles.splitFill}
              style={{ width: `${(s.value / total) * 100}%`, background: s.color }}
              title={`${s.label}: ${num(s.value)} (${Math.round((s.value / total) * 100)}%)`}
            />
          ))}
      </div>
      <ul className={styles.splitLegend}>
        {segments.map((s) => (
          <li key={s.label} className={styles.legendRow}>
            <span className={styles.legendSwatch} style={{ background: s.color }} aria-hidden="true" />
            <span className={styles.legendName}>{s.label}</span>
            <span className={styles.legendValue}>
              {num(s.value)} · {Math.round((s.value / total) * 100)}%
            </span>
          </li>
        ))}
      </ul>
    </div>
  )
}

/* --------------------------------------------------------------- forecasting */

function ForecastCard({ icon, title, value, note, assumption, tone }: { icon: LucideIcon; title: string; value: string; note?: string; assumption: string; tone?: 'warn' | 'bad' }) {
  return (
    <div className={styles.forecastCard} data-tone={tone}>
      <span className={styles.forecastTag}>{PROJECTION_LABEL}</span>
      <span className={styles.forecastTitle}>
        <Icon icon={icon} size={14} />
        {title}
      </span>
      <span className={styles.forecastValue}>{value}</span>
      {note && <span className={styles.forecastNote}>{note}</span>}
      <span className={styles.forecastAssume}>{assumption}</span>
    </div>
  )
}

/** Labelled straight-line projections (NOT ML) — budget runway + reach/registration trend. */
export function ForecastCards({ forecast }: { forecast: ExecutiveForecast }) {
  const { budget, beneficiaries, registrations } = forecast
  if (!budget && !beneficiaries && !registrations) return null

  return (
    <section className={styles.reveal} style={{ animationDelay: '280ms' }} aria-label="Projections">
      <div className={styles.sectionHead}>
        <Icon icon={TrendingUp} size={16} />
        <h2 className={styles.sectionTitle}>Projections</h2>
      </div>
      <div className={styles.forecastGrid}>
        {budget && (
          <ForecastCard
            icon={Wallet}
            title="Budget runway"
            tone={budget.status === 'over' ? 'bad' : budget.status === 'exhausting' ? 'warn' : undefined}
            value={
              budget.status === 'over'
                ? 'Fully committed'
                : budget.status === 'idle'
                  ? 'No active burn'
                  : `Runs out ~${budget.exhaustionMonth ? monthLong(budget.exhaustionMonth) : '—'}`
            }
            note={budget.monthsRemaining !== null && budget.status !== 'over' ? `${budget.monthsRemaining} months at current burn` : undefined}
            assumption={budget.assumption}
          />
        )}
        {beneficiaries && (
          <ForecastCard
            icon={UsersRound}
            title="Beneficiaries reached"
            value={`~${num(beneficiaries.endValue)} by ${beneficiaries.projected.length ? monthLong(beneficiaries.projected[beneficiaries.projected.length - 1].month) : '—'}`}
            note={`${beneficiaries.monthlyRate >= 0 ? '+' : ''}${num(beneficiaries.monthlyRate)}/month`}
            assumption={beneficiaries.assumption}
          />
        )}
        {registrations && (
          <ForecastCard
            icon={CalendarPlus}
            title="New registrations"
            value={`~${num(registrations.endValue)}/month projected`}
            note={`${registrations.monthlyRate >= 0 ? '+' : ''}${num(registrations.monthlyRate)}/month trend`}
            assumption={registrations.assumption}
          />
        )}
      </div>
    </section>
  )
}
