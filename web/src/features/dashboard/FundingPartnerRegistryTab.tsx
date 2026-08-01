import {
  BadgeCheck,
  CalendarPlus,
  CopyCheck,
  Fingerprint,
  Hourglass,
  House,
  Lock,
  PenLine,
  ShieldCheck,
  Users,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import type { DashboardResponse, PartnerRegistry } from './types'
import shell from './fundingPartner.module.css'
import styles from './partnerRegistry.module.css'

/* ------------------------------------------------------------------ helpers */

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const pct = (rate: number | null | undefined): string => (rate === null || rate === undefined ? '—' : `${Math.round(rate * 100)}%`)
const cap = (s: string): string => (s === 'unspecified' ? 'Unspecified' : s.charAt(0).toUpperCase() + s.slice(1))

interface Row {
  key: string
  value: number
}

function toRows(map: Record<string, number>): Row[] {
  return Object.entries(map).map(([key, value]) => ({ key, value }))
}

/* --------------------------------------------------------------- components */

function Kpi({ icon, label, value, hint, headline }: { icon: LucideIcon; label: string; value: string; hint?: string; headline?: boolean }) {
  return (
    <div className={headline ? `${shell.kpi} ${shell.kpiHeadline}` : shell.kpi}>
      <span className={shell.kpiLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={shell.kpiValue}>{value}</span>
      {hint && <span className={shell.kpiHint}>{hint}</span>}
    </div>
  )
}

function Figure({ icon, label, value, hint }: { icon: LucideIcon; label: string; value: string; hint?: string }) {
  return (
    <div className={shell.figure}>
      <span className={shell.figureLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={shell.figureValue}>{value}</span>
      {hint && <span className={shell.figureHint}>{hint}</span>}
    </div>
  )
}

/** Labelled magnitude bars (single-hue) for a small categorical breakdown. */
function BarList({ rows, order }: { rows: Row[]; order?: string[] }) {
  const sorted = order
    ? [...rows].sort((a, b) => order.indexOf(a.key) - order.indexOf(b.key))
    : [...rows].sort((a, b) => b.value - a.value)
  const max = Math.max(1, ...sorted.map((r) => r.value))

  if (sorted.length === 0) {
    return <p className={styles.muted}>No data captured yet.</p>
  }

  return (
    <div className={styles.bars}>
      {sorted.map((r) => (
        <div key={r.key} className={styles.barRow}>
          <span className={styles.barLabel}>{cap(r.key)}</span>
          <span className={styles.barTrack}>
            <span className={styles.barFill} style={{ width: `${Math.max(2, Math.round((r.value / max) * 100))}%` }} />
          </span>
          <span className={styles.barVal}>{num(r.value)}</span>
        </div>
      ))}
    </div>
  )
}

/** A 0..1 quality meter. */
function Meter({ label, rate, hint }: { label: string; rate: number | null; hint?: string }) {
  const width = rate === null ? 0 : Math.max(2, Math.round(rate * 100))
  return (
    <div className={styles.meter}>
      <div className={styles.meterTop}>
        <span className={styles.meterLabel}>{label}</span>
        <span className={styles.meterVal}>{pct(rate)}</span>
      </div>
      <span className={styles.meterTrack}>
        <span className={styles.meterFill} style={{ width: `${width}%` }} />
      </span>
      {hint && <span className={styles.meterHint}>{hint}</span>}
    </div>
  )
}

export interface FundingPartnerRegistryTabProps {
  data: DashboardResponse
}

/**
 * Registry (Phase 6P, tab 3) — the aggregate registry for the partner's FUNDED cohort
 * (beneficiaries enrolled in ∪ served by funded activities). De-identified counts only:
 * KPIs, CAPTURED-field demographics (gender, age, location, household size — poverty /
 * disability / rural-urban / vulnerability are NOT captured and omitted), a REDUCED
 * targeting funnel (Registered → Enrolled → Receiving; the eligible→selected steps are an
 * inert slot needing an eligible-population denominator + a selection model), and data
 * quality (verification, duplicate rate, missing data, completeness, NIN linkage — no
 * bank/mobile-money verification, since SP-MIS is not a payment engine). Read-only.
 */
export function FundingPartnerRegistryTab({ data }: FundingPartnerRegistryTabProps) {
  const pf = data.metrics.partner_funding
  const reg: PartnerRegistry | undefined = pf?.registry

  if (!reg || reg.total_individuals === 0) {
    return (
      <p className={shell.empty}>
        No beneficiaries are on record for your funded activities yet. Once beneficiaries are enrolled in or served by
        them, the registry KPIs, demographics and data quality appear here.
      </p>
    )
  }

  const funnelMax = Math.max(1, reg.funnel.registered)
  const funnelStages = [
    { key: 'registered', label: 'Registered', value: reg.funnel.registered, hint: 'on record for funded activities' },
    { key: 'enrolled', label: 'Enrolled', value: reg.funnel.enrolled, hint: 'enrolled into a funded programme' },
    { key: 'receiving', label: 'Receiving benefits', value: reg.funnel.receiving, hint: 'net-unique served' },
  ]
  const hh = reg.demographics.household_size

  return (
    <div className={shell.tabBody}>
      {/* ---------- KPI BAND ---------- */}
      <section className={shell.reveal} aria-label="Registry indicators">
        <span className={shell.groupLabel}>Funded-programme registry</span>
        <div className={shell.kpiBand}>
          <Kpi headline icon={Users} label="Individuals" value={num(reg.total_individuals)} hint="distinct beneficiaries" />
          <Kpi icon={House} label="Households" value={num(reg.total_households)} />
          <Kpi icon={BadgeCheck} label="Verified" value={num(reg.verified)} hint="registry status: active" />
          <Kpi icon={Hourglass} label="Pending review" value={num(reg.pending)} />
        </div>
        <div className={shell.figureGrid}>
          <Figure icon={CopyCheck} label="Duplicate records" value={num(reg.duplicate_records)} hint="potential matches surfaced" />
          <Figure icon={CalendarPlus} label="New registrations" value={num(reg.new_registrations)} hint={`last ${num(reg.period_days)} days`} />
          <Figure icon={PenLine} label="Updated records" value={num(reg.updated_records)} hint={`last ${num(reg.period_days)} days`} />
          <Figure icon={ShieldCheck} label="Suspended" value={num(reg.suspended)} />
        </div>
      </section>

      {/* ---------- REDUCED TARGETING FUNNEL ---------- */}
      <section className={`${shell.section} ${shell.reveal}`} aria-label="Targeting funnel">
        <div className={shell.sectionHead}>
          <Icon icon={Users} size={16} />
          <h2 className={shell.sectionTitle}>Targeting funnel</h2>
          <span className={shell.sectionSub}>the stages we track</span>
        </div>
        <div className={shell.panel}>
          <div className={styles.funnel}>
            {/* Omitted upstream stages — inert, never fabricated. */}
            <div className={`${styles.funnelStage} ${styles.funnelExternal}`}>
              <span className={styles.funnelLabel}>
                <Icon icon={Lock} size={12} /> Eligible → Selected
              </span>
              <span className={styles.funnelExternalNote}>
                Not tracked — needs an eligible-population denominator and a selection model
              </span>
            </div>
            {funnelStages.map((s) => (
              <div key={s.key} className={styles.funnelStage}>
                <span className={styles.funnelLabel}>{s.label}</span>
                <span className={styles.funnelTrack}>
                  <span className={styles.funnelFill} style={{ width: `${Math.max(3, Math.round((s.value / funnelMax) * 100))}%` }} />
                  <span className={styles.funnelValue}>{num(s.value)}</span>
                </span>
                <span className={styles.funnelHint}>{s.hint}</span>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ---------- DEMOGRAPHICS ---------- */}
      <section className={`${shell.section} ${shell.reveal}`} aria-label="Demographics">
        <div className={shell.sectionHead}>
          <Icon icon={Users} size={16} />
          <h2 className={shell.sectionTitle}>Demographics</h2>
          <span className={shell.sectionSub}>captured fields only</span>
        </div>
        <div className={styles.demoGrid}>
          <div className={shell.panel}>
            <span className={styles.blockLabel}>Gender {reg.demographics.female_pct !== null && <span className={styles.blockHint}>· {pct(reg.demographics.female_pct)} female (of known)</span>}</span>
            <BarList rows={toRows(reg.demographics.by_gender)} order={['female', 'male', 'unspecified']} />
          </div>
          <div className={shell.panel}>
            <span className={styles.blockLabel}>Age band</span>
            <BarList rows={toRows(reg.demographics.age_bands)} order={['children', 'youth', 'adults', 'elderly', 'unknown']} />
          </div>
          <div className={shell.panel}>
            <span className={styles.blockLabel}>Household size <span className={styles.blockHint}>· avg {hh.average_size ?? '—'}</span></span>
            <BarList rows={toRows(hh.bands)} order={['1', '2-3', '4-6', '7+']} />
          </div>
          <div className={shell.panel}>
            <span className={styles.blockLabel}>Location <span className={styles.blockHint}>· top LGAs</span></span>
            <BarList rows={toRows(reg.demographics.by_lga).slice(0, 6)} />
          </div>
        </div>
      </section>

      {/* ---------- DATA QUALITY ---------- */}
      <section className={`${shell.section} ${shell.reveal}`} aria-label="Data quality">
        <div className={shell.sectionHead}>
          <Icon icon={Fingerprint} size={16} />
          <h2 className={shell.sectionTitle}>Data quality</h2>
        </div>
        <div className={shell.panel}>
          <div className={styles.meters}>
            <Meter label="Verification rate" rate={reg.quality.verification_rate} hint="active ÷ total" />
            <Meter label="Linked to NIN" rate={reg.quality.nin_linkage} hint="identity linkage" />
            <Meter label="Data completeness" rate={reg.quality.data_completeness} hint="across captured fields" />
            <Meter label="Duplicate rate" rate={reg.quality.duplicate_rate} hint="matches ÷ total (lower is better)" />
          </div>

          <span className={styles.blockLabel}>Missing data</span>
          <div className={styles.missingGrid}>
            <div className={styles.missing}>
              <span className={styles.missingVal}>{num(reg.quality.missing.nin)}</span>
              <span className={styles.missingLabel}>NIN</span>
            </div>
            <div className={styles.missing}>
              <span className={styles.missingVal}>{num(reg.quality.missing.phone)}</span>
              <span className={styles.missingLabel}>Phone</span>
            </div>
            <div className={styles.missing}>
              <span className={styles.missingVal}>{num(reg.quality.missing.date_of_birth)}</span>
              <span className={styles.missingLabel}>Date of birth</span>
            </div>
            <div className={styles.missing}>
              <span className={styles.missingVal}>{num(reg.quality.missing.gender)}</span>
              <span className={styles.missingLabel}>Gender</span>
            </div>
            <div className={styles.missing}>
              <span className={styles.missingVal}>{num(reg.quality.missing.lga)}</span>
              <span className={styles.missingLabel}>LGA</span>
            </div>
          </div>

          <p className={styles.qualityFoot}>
            Bank / mobile-money verification is not shown — SP-MIS records value as data and is not a payment engine, so
            no such field is captured.
          </p>
        </div>
      </section>
    </div>
  )
}
