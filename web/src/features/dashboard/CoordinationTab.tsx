import {
  ArrowLeftRight,
  Building2,
  Cable,
  CalendarClock,
  CheckCircle2,
  Coins,
  Database,
  Handshake,
  Layers,
  Network,
  RefreshCw,
  Users,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import type { ReactNode } from 'react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import type { CoordinationMetrics, DashboardResponse } from './types'
import styles from './coordination.module.css'

/* ------------------------------------------------------------------ helpers */

const pct = (rate: number | null | undefined): number => Math.round((rate ?? 0) * 100)
const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()

function relative(iso: string | null): string {
  if (!iso) return 'never'
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000)
  if (mins < 1) return 'moments ago'
  if (mins < 60) return `${mins} min ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  return `${Math.round(hrs / 24)}d ago`
}

function turnaround(hours: number | null): string {
  if (hours == null) return '—'
  return hours < 48 ? `${Math.round(hours)}h` : `${Math.round(hours / 24)}d`
}

const SOURCE_LABEL: Record<string, string> = {
  socu: 'SOCU',
  government_system: 'Government system',
  kobo: 'KoboToolbox',
  odk: 'ODK',
  api: 'API',
  csv: 'CSV',
  excel: 'Excel',
  manual: 'Manual',
}
const sourceLabel = (s: string): string => SOURCE_LABEL[s] ?? titleCase(s)

type SyncTone = 'good' | 'warn' | 'bad' | 'idle'
function syncStatus(sh: CoordinationMetrics['sync_health']): { label: string; tone: SyncTone } {
  if (sh.total_runs === 0) return { label: 'Not configured', tone: 'idle' }
  if (sh.succeeded === 0 && sh.failed > 0) return { label: 'Failing', tone: 'bad' }
  if (sh.failed > 0) return { label: 'Degraded', tone: 'warn' }
  return { label: 'Healthy', tone: 'good' }
}

/* ------------------------------------------------------------------ pieces */

function SectionHead({ icon, title }: { icon: LucideIcon; title: string }) {
  return (
    <div className={styles.sectionHead}>
      <Icon icon={icon} size={16} />
      <h2 className={styles.sectionTitle}>{title}</h2>
    </div>
  )
}

function Kpi({ icon, label, value, hint }: { icon: LucideIcon; label: string; value: string; hint?: string }) {
  return (
    <div className={styles.kpi}>
      <span className={styles.kpiLabel}>
        <Icon icon={icon} size={14} />
        {label}
      </span>
      <span className={styles.kpiValue}>{value}</span>
      {hint && <span className={styles.kpiHint}>{hint}</span>}
    </div>
  )
}

/** A single-ratio ring (referral completion, approval rate). */
function Ring({ value, tone }: { value: number; tone: 'info' | 'good' }) {
  const size = 104
  const r = size / 2 - 8
  const circ = 2 * Math.PI * r
  const dash = circ * Math.min(1, Math.max(0, value / 100))
  return (
    <svg className={styles.ring} width={size} height={size} viewBox={`0 0 ${size} ${size}`} role="img" aria-label={`${value}%`}>
      <circle cx={size / 2} cy={size / 2} r={r} fill="none" strokeWidth="9" className={styles.ringTrack} />
      <circle
        cx={size / 2}
        cy={size / 2}
        r={r}
        fill="none"
        strokeWidth="9"
        className={styles.ringValue}
        data-tone={tone}
        strokeDasharray={`${dash} ${circ}`}
        transform={`rotate(-90 ${size / 2} ${size / 2})`}
      />
      <text x="50%" y="50%" textAnchor="middle" dominantBaseline="central" className={styles.ringText}>
        {value}%
      </text>
    </svg>
  )
}

function RingBlock({ value, tone, title, note, children }: { value: number; tone: 'info' | 'good'; title: string; note: string; children?: ReactNode }) {
  return (
    <div className={styles.ringBlock}>
      <Ring value={value} tone={tone} />
      <div className={styles.ringMeta}>
        <span className={styles.ringTitle}>{title}</span>
        <span className={styles.ringNote}>{note}</span>
        {children}
      </div>
    </div>
  )
}

/* ------------------------------------------------------------------ tab */

export interface CoordinationTabProps {
  data: DashboardResponse
}

/**
 * Coordination tab (Phase 6E, tab 4). Agencies (active MDAs, activities, joint
 * programmes), per-partner contributions scoped to funded programmes, cross-agency
 * collaboration (joint beneficiaries, referral throughput, request-to-serve approval
 * + turnaround), and data sharing (agencies integrated, API/sync health, registry
 * sync status). The meetings/attendance/action-items module is intentionally NOT
 * built — it is not part of SP-MIS (noted as a future/external slot). Scoped +
 * de-identified from the reporting aggregation layer.
 */
export function CoordinationTab({ data }: CoordinationTabProps) {
  const c = data.metrics.coordination
  const programmes = data.metrics.programmes

  if (!c) {
    return (
      <div className={styles.page}>
        <p className={styles.empty}>Coordination metrics are unavailable for this view.</p>
      </div>
    )
  }

  const rts = c.request_to_serve
  const sync = c.sync_health
  const status = syncStatus(sync)
  const partners = c.partners

  return (
    <div className={styles.page}>
      {/* Page identity — inner executive pages previously had no <h1>. */}
      <h1 className="t-h1">Coordination</h1>

      {/* ---------- AGENCIES ---------- */}
      <section className={styles.section}>
        <SectionHead icon={Building2} title="Agencies" />
        <div className={styles.kpiBand}>
          <Kpi icon={Building2} label="Active agencies" value={num(c.active_mdas)} hint="delivering benefits" />
          <Kpi icon={Layers} label="Active activities" value={num(programmes.activities_active)} hint={`of ${num(programmes.activities_total)} projects`} />
          <Kpi icon={Network} label="Joint programmes" value={num(c.joint_programmes)} hint="run by ≥2 agencies" />
        </div>
      </section>

      {/* ---------- CROSS-AGENCY COLLABORATION ---------- */}
      <section className={styles.section}>
        <SectionHead icon={ArrowLeftRight} title="Cross-agency collaboration" />
        <div className={styles.collabGrid}>
          <div className={styles.panel}>
            <div className={styles.bigStat}>
              <Icon icon={Users} size={20} />
              <span className={styles.bigValue}>{num(c.cross_mda_beneficiaries)}</span>
              <span className={styles.bigLabel}>Joint beneficiaries</span>
              <span className={styles.bigNote}>served across agency lines (net-unique)</span>
            </div>
          </div>

          <div className={styles.panel}>
            <RingBlock
              value={pct(c.referral_throughput.completion_rate)}
              tone="info"
              title="Referral completion"
              note={`${num(c.referral_throughput.completed)} of ${num(c.referral_throughput.total)} completed`}
            />
          </div>

          <div className={styles.panel}>
            <RingBlock value={pct(rts.approval_rate)} tone="good" title="Request-to-serve approval" note={`avg turnaround ${turnaround(rts.avg_turnaround_hours)}`}>
              <ul className={styles.pills}>
                <li className={styles.pill} data-tone="good">
                  <Icon icon={CheckCircle2} size={12} /> {num(rts.accepted)} accepted
                </li>
                <li className={styles.pill} data-tone="bad">
                  {num(rts.declined)} declined
                </li>
                <li className={styles.pill} data-tone="muted">
                  {num(rts.pending)} pending
                </li>
              </ul>
            </RingBlock>
          </div>
        </div>
      </section>

      {/* ---------- PARTNER CONTRIBUTIONS ---------- */}
      <section className={styles.section}>
        <SectionHead icon={Handshake} title="Partner contributions" />
        <div className={styles.panel}>
          <div className={styles.partnerSummary}>
            <span>
              <strong>{num(partners.count)}</strong> partners
            </span>
            <span>
              <strong>{num(partners.funded_programmes)}</strong> funded programmes
            </span>
            <span>
              <Icon icon={Coins} size={14} /> <strong>{formatNaira(partners.funding_allocated)}</strong> committed
            </span>
            <span>
              <strong>{num(partners.beneficiaries_served)}</strong> beneficiaries served
            </span>
          </div>

          {partners.list.length === 0 ? (
            <p className={styles.empty}>No partner-funded programmes in scope.</p>
          ) : (
            <table className={styles.table}>
              <caption className="sr-only">Funding and beneficiaries by partner, scoped to funded programmes</caption>
              <thead>
                <tr>
                  <th scope="col">Partner</th>
                  <th scope="col">Funded programmes</th>
                  <th scope="col">Beneficiaries served</th>
                  <th scope="col">Funding committed</th>
                </tr>
              </thead>
              <tbody>
                {partners.list.map((p) => (
                  <tr key={p.partner_id}>
                    <th scope="row" className={styles.partnerName}>
                      {p.name}
                    </th>
                    <td>{num(p.funded_programmes)}</td>
                    <td>{num(p.beneficiaries_served)}</td>
                    <td>{formatNaira(p.funding_allocated)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      </section>

      {/* ---------- DATA SHARING ---------- */}
      <section className={styles.section}>
        <SectionHead icon={Database} title="Data sharing" />
        <div className={styles.panel}>
          <div className={styles.syncTop}>
            <span className={styles.syncStatus} data-tone={status.tone}>
              <span className={styles.statusDot} data-tone={status.tone} aria-hidden="true" />
              Registry sync: {status.label}
            </span>
            <span className={styles.syncLast}>
              <Icon icon={RefreshCw} size={13} /> last run {relative(sync.last_run_at)}
            </span>
          </div>

          <div className={styles.syncGrid}>
            <div className={styles.syncStat}>
              <span className={styles.syncStatLabel}>
                <Icon icon={Cable} size={13} /> Agencies integrated
              </span>
              <span className={styles.syncStatValue}>{num(sync.connectors)}</span>
            </div>
            <div className={styles.syncStat}>
              <span className={styles.syncStatLabel}>Sync runs</span>
              <span className={styles.syncStatValue}>{num(sync.total_runs)}</span>
            </div>
            <div className={styles.syncStat}>
              <span className={styles.syncStatLabel}>Succeeded</span>
              <span className={styles.syncStatValue} data-tone="good">
                {num(sync.succeeded)}
              </span>
            </div>
            <div className={styles.syncStat}>
              <span className={styles.syncStatLabel}>Failed</span>
              <span className={styles.syncStatValue} data-tone={sync.failed > 0 ? 'bad' : undefined}>
                {num(sync.failed)}
              </span>
            </div>
            <div className={styles.syncStat}>
              <span className={styles.syncStatLabel}>API registrations</span>
              <span className={styles.syncStatValue}>{num(sync.api_registrations)}</span>
            </div>
          </div>

          {sync.sources.length > 0 && (
            <div className={styles.sources}>
              <span className={styles.sourcesLabel}>Integrated sources</span>
              <ul className={styles.sourceChips}>
                {sync.sources.map((s) => (
                  <li key={s} className={styles.sourceChip}>
                    {sourceLabel(s)}
                  </li>
                ))}
              </ul>
            </div>
          )}
        </div>
      </section>

      {/* ---------- OMITTED MODULE (future/external slot) ---------- */}
      <p className={styles.slotNote}>
        <Icon icon={CalendarClock} size={14} />
        Meetings, attendance and action items are not part of SP-MIS. Coordinate those in your external meeting tool. Reserved as a future or external slot.
      </p>
    </div>
  )
}
