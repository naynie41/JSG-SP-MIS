import {
  CalendarPlus,
  ChartColumn,
  ClipboardCheck,
  Clock,
  CopyCheck,
  House,
  IdCard,
  Phone,
  ShieldCheck,
  UserCheck,
  Users,
  UsersRound,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import type { DashboardResponse } from './types'
import styles from './registry.module.css'

/* ------------------------------------------------------------------ helpers */

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const ratio = (n: number, d: number | undefined | null): number | null => (d && d > 0 ? n / d : null)

/* --------------------------------------------------------------- KPI cards */

function KpiPanel({ icon, label, value, hint, headline }: { icon: LucideIcon; label: string; value: string; hint?: string; headline?: boolean }) {
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

function Figure({ icon, label, value, hint }: { icon: LucideIcon; label: string; value: string; hint?: string }) {
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

/* ------------------------------------------------------------ quality meter */

/** A single-ratio meter. `good` rewards a high value (verification, completeness);
 * `risk` warns as the value climbs (duplicates, missing fields). */
function Meter({ icon, label, rate, tone, hint }: { icon: LucideIcon; label: string; rate: number | null; tone: 'good' | 'risk'; hint?: string }) {
  const value = rate == null ? null : Math.round(rate * 100)
  return (
    <div className={styles.meter}>
      <div className={styles.meterTop}>
        <span className={styles.meterLabel}>
          <Icon icon={icon} size={14} />
          {label}
        </span>
        <span className={styles.meterValue}>{value == null ? '—' : `${value}%`}</span>
      </div>
      <span className={styles.meterTrack}>
        <span className={styles.meterFill} data-tone={tone} style={{ width: `${Math.min(100, Math.max(0, value ?? 0))}%` }} />
      </span>
      {hint && <span className={styles.meterHint}>{hint}</span>}
    </div>
  )
}

/* ------------------------------------------------------------ breakdown bars */

/** Ordered magnitude bars (single forest hue), preserving the given category order. */
function Bars({ items }: { items: { name: string; value: number }[] }) {
  const max = Math.max(1, ...items.map((i) => i.value))
  if (items.every((i) => i.value === 0)) return <p className={styles.empty}>No data yet.</p>
  return (
    <div className={styles.bars}>
      {items.map((item) => (
        <div key={item.name} className={styles.barRow}>
          <span className={styles.barName}>{item.name}</span>
          <span className={styles.barTrack}>
            <span className={styles.barFill} style={{ width: `${Math.max(2, Math.round((item.value / max) * 100))}%` }} title={`${item.name}: ${num(item.value)}`} />
          </span>
          <span className={styles.barValue}>{num(item.value)}</span>
        </div>
      ))}
    </div>
  )
}

interface Segment {
  label: string
  value: number
  color: string
}

/** 100% stacked split bar for a small parts-of-whole (gender). */
function SplitBar({ segments }: { segments: Segment[] }) {
  const total = segments.reduce((s, x) => s + x.value, 0)
  if (total === 0) return <p className={styles.empty}>No data yet.</p>
  return (
    <div className={styles.split}>
      <div className={styles.splitTrack}>
        {segments
          .filter((s) => s.value > 0)
          .map((s) => (
            <span key={s.label} className={styles.splitFill} style={{ width: `${(s.value / total) * 100}%`, background: s.color }} title={`${s.label}: ${num(s.value)} (${Math.round((s.value / total) * 100)}%)`} />
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

/* ------------------------------------------------------------------ tab */

export interface RegistryTabProps {
  data: DashboardResponse
}

/**
 * Registry tab (Phase 6E, tab 3). Registry KPIs (households, individuals, verified vs
 * pending, duplicates, new registrations), a data-quality panel (verification, data
 * completeness, duplicate %, missing NIN/phone %), and household breakdowns by the
 * fields we HAVE — gender, age band, household size. Poverty/disability/PWD/IDP/
 * occupation are intentionally NOT rendered (no field is captured). Scoped +
 * de-identified from the reporting aggregation layer.
 */
export function RegistryTab({ data }: RegistryTabProps) {
  const m = data.metrics
  const pop = m.population
  const demo = m.demographics
  const rq = m.registry_quality
  const hh = m.household_size

  const gender = demo?.by_gender ?? {}
  const ageBands = demo?.age_bands ?? {}
  const sizeBands = hh?.bands ?? {}

  const verificationRate = rq ? ratio(rq.verified, rq.total) : null
  const duplicateRate = rq ? ratio(rq.duplicates_detected, rq.total) : null
  const missingNin = rq && rq.nin_completeness != null ? 1 - rq.nin_completeness : null
  const missingPhone = rq && rq.phone_completeness != null ? 1 - rq.phone_completeness : null

  return (
    <div className={styles.page}>
      {/* ---------- KPIs ---------- */}
      <section className={styles.reveal} aria-label="Registry indicators">
        <span className={styles.groupLabel}>Registry</span>
        <div className={styles.kpiBand}>
          <KpiPanel headline icon={Users} label="Total individuals" value={num(pop?.total_individuals)} hint="deduplicated registry" />
          <KpiPanel icon={House} label="Total households" value={num(pop?.total_households)} />
          <KpiPanel icon={UserCheck} label="Verified" value={num(rq?.verified)} hint="active records" />
          <KpiPanel icon={Clock} label="Pending" value={num(rq?.pending)} hint="flagged for review" />
        </div>
        <div className={styles.figureGrid}>
          <Figure icon={CopyCheck} label="Duplicates detected" value={num(rq?.duplicates_detected)} hint="surfaced at import" />
          <Figure icon={CalendarPlus} label="New this period" value={num(pop?.new_registrations_period)} hint={`last ${num(pop?.period_days)} days`} />
          <Figure icon={UsersRound} label="Suspended" value={num(rq?.suspended)} />
        </div>
      </section>

      {/* ---------- DATA QUALITY ---------- */}
      <section className={`${styles.reveal} ${styles.section}`} aria-label="Data quality">
        <div className={styles.sectionHead}>
          <Icon icon={ShieldCheck} size={16} />
          <h2 className={styles.sectionTitle}>Data quality</h2>
        </div>
        <div className={styles.qualityGrid}>
          <Meter icon={ShieldCheck} label="Verification rate" rate={verificationRate} tone="good" hint="records marked active" />
          <Meter icon={ClipboardCheck} label="Data completeness" rate={rq?.data_completeness ?? null} tone="good" hint="key fields present" />
          <Meter icon={CopyCheck} label="Duplicate rate" rate={duplicateRate} tone="risk" hint="of total records" />
          <Meter icon={IdCard} label="Missing NIN" rate={missingNin} tone="risk" hint="no NIN on file" />
          <Meter icon={Phone} label="Missing phone" rate={missingPhone} tone="risk" hint="no phone on file" />
        </div>
      </section>

      {/* ---------- BREAKDOWNS ---------- */}
      <section className={`${styles.reveal} ${styles.section}`} aria-label="Household breakdown">
        <div className={styles.sectionHead}>
          <Icon icon={ChartColumn} size={16} />
          <h2 className={styles.sectionTitle}>Breakdown</h2>
        </div>
        <div className={styles.breakGrid}>
          <div className={styles.panel}>
            <span className={styles.panelLabel}>Gender</span>
            <SplitBar
              segments={[
                { label: 'Female', value: gender.female ?? 0, color: '#2a78d6' },
                { label: 'Male', value: gender.male ?? 0, color: '#eda100' },
                { label: 'Unspecified', value: gender.unspecified ?? 0, color: '#9a9c93' },
              ]}
            />
          </div>

          <div className={styles.panel}>
            <span className={styles.panelLabel}>Age band</span>
            <Bars
              items={[
                { name: 'Children', value: ageBands.children ?? 0 },
                { name: 'Youth', value: ageBands.youth ?? 0 },
                { name: 'Adults', value: ageBands.adults ?? 0 },
                { name: 'Elderly', value: ageBands.elderly ?? 0 },
                { name: 'Unknown', value: ageBands.unknown ?? 0 },
              ]}
            />
          </div>

          <div className={styles.panel}>
            <div className={styles.panelLabelRow}>
              <span className={styles.panelLabel}>Household size</span>
              {hh?.average_size != null && <span className={styles.avgSize}>avg {hh.average_size}</span>}
            </div>
            <Bars
              items={[
                { name: '1', value: sizeBands['1'] ?? 0 },
                { name: '2–3', value: sizeBands['2-3'] ?? 0 },
                { name: '4–6', value: sizeBands['4-6'] ?? 0 },
                { name: '7+', value: sizeBands['7+'] ?? 0 },
              ]}
            />
          </div>
        </div>
      </section>
    </div>
  )
}
