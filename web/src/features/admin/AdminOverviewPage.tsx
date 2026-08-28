import { useNavigate } from 'react-router-dom'
import {
  AlertTriangle,
  Bell,
  Building2,
  Database,
  FileWarning,
  HandCoins,
  Info,
  Layers,
  LibraryBig,
  RefreshCw,
  ScrollText,
  ShieldCheck,
  SlidersHorizontal,
  UserPlus,
  Users,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { ReportingSummaryCard } from '@/features/dashboard/ReportingSummaryCard'
import { useAdminSummary } from './hooks'
import type { AdminSummary } from './types'
import styles from './admin.module.css'

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const pct = (rate: number | null | undefined): string =>
  rate === null || rate === undefined ? '—' : `${Math.round(rate * 100)}%`

function when(iso: string | null): string {
  if (!iso) return '—'
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000)
  if (mins < 1) return 'just now'
  if (mins < 60) return `${mins} min ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  return new Date(iso).toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' })
}

/** 'user.created' → 'User created' — the audit action, humanised. */
function humanizeAction(action: string): string {
  const text = action.replace(/[._]/g, ' ')
  return text.charAt(0).toUpperCase() + text.slice(1)
}

/* --------------------------------------------------------------- quick actions */

interface QuickAction {
  id: string
  label: string
  icon: LucideIcon
  /** Existing flow this launches. Null when the flow does not exist yet. */
  to: string | null
  /** Why the action is unavailable (renders disabled with this as the tooltip). */
  pending?: string
}

/**
 * Launchers into EXISTING flows — this console never introduces a parallel form.
 * Every action now has a real destination: manual synchronization opens the
 * Integrations section (which composes the Phase 7 sync engine, or shows its pending
 * state where that engine is not enabled), and broadcast opens the Settings
 * notification panel, which sends through the Phase 5 notifier.
 */
const QUICK_ACTIONS: QuickAction[] = [
  { id: 'create-user', label: 'Create User', icon: UserPlus, to: '/users' },
  { id: 'register-mda', label: 'Register MDA', icon: Building2, to: '/mdas' },
  { id: 'register-partner', label: 'Register Development Partner', icon: HandCoins, to: '/users' },
  { id: 'create-programme', label: 'Create Programme', icon: LibraryBig, to: '/programmes/list' },
  { id: 'configure-matching', label: 'Configure Matching Rules', icon: SlidersHorizontal, to: '/matching' },
  { id: 'trigger-sync', label: 'Trigger Manual Synchronization', icon: RefreshCw, to: '/admin/integrations' },
  { id: 'reprocess-imports', label: 'Reprocess Failed Imports', icon: FileWarning, to: '/imports' },
  { id: 'view-audit', label: 'View Audit Logs', icon: ScrollText, to: '/admin/audit' },
  { id: 'broadcast', label: 'Broadcast System Notification', icon: Bell, to: '/admin/settings' },
]

function QuickActions() {
  const navigate = useNavigate()
  return (
    <section className={styles.section} aria-label="Quick actions">
      <div className={styles.sectionHead}>
        <Icon icon={Layers} size={16} />
        <h2 className={styles.sectionTitle}>Quick actions</h2>
        <span className={styles.sectionSub}>launch an existing flow</span>
      </div>
      <div className={styles.actionBar}>
        {QUICK_ACTIONS.map((a) => (
          <button
            key={a.id}
            type="button"
            className={styles.action}
            disabled={a.to === null}
            title={a.pending}
            onClick={a.to ? () => navigate(a.to as string) : undefined}
          >
            <Icon icon={a.icon} size={15} />
            {a.label}
            {a.to === null && <span className={styles.actionPending}>Pending</span>}
          </button>
        ))}
      </div>
    </section>
  )
}

/* ------------------------------------------------------------------ components */

function Kpi({ icon, label, value, hint, headline }: { icon: LucideIcon; label: string; value: string; hint?: string; headline?: boolean }) {
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

/** User adoption: monthly new accounts as bars, with the running total called out. */
function AdoptionTrend({ trend }: { trend: AdminSummary['adoption_trend'] }) {
  const max = Math.max(1, ...trend.map((p) => p.new_users))
  const latest = trend[trend.length - 1]
  return (
    <section className={styles.section} aria-label="User adoption">
      <div className={styles.sectionHead}>
        <Icon icon={Users} size={16} />
        <h2 className={styles.sectionTitle}>User adoption</h2>
        <span className={styles.sectionSub}>accounts provisioned · 12 months</span>
      </div>
      <div className={styles.panel}>
        <div className={styles.trendTop}>
          <span className={styles.trendValue}>{num(latest?.total_users)}</span>
          <span className={styles.trendLabel}>accounts on the platform</span>
        </div>
        <div className={styles.chart} role="img" aria-label={`New accounts per month over ${trend.length} months`}>
          {trend.map((p) => (
            <div key={p.month} className={styles.chartCol} title={`${p.month}: ${p.new_users} new`}>
              <span
                className={styles.chartBar}
                style={{ height: `${Math.max(2, Math.round((p.new_users / max) * 100))}%` }}
                data-zero={p.new_users === 0}
              />
            </div>
          ))}
        </div>
        <span className={styles.chartCap}>New accounts per month</span>
      </div>
    </section>
  )
}

function RegistrySnapshot({ registry }: { registry: AdminSummary['registry'] }) {
  const figures = [
    { label: 'Imports', value: num(registry.imports_total), hint: `${num(registry.imports_completed)} completed` },
    { label: 'Failed imports', value: num(registry.imports_failed), hint: 'need reprocessing' },
    { label: 'In progress', value: num(registry.imports_in_progress) },
    { label: 'Rows processed', value: num(registry.rows_total) },
    { label: 'Validation rate', value: pct(registry.validation_rate), hint: `${num(registry.rows_invalid)} rejected` },
    { label: 'Duplicates surfaced', value: num(registry.duplicates_surfaced), hint: `${num(registry.duplicates_pending)} pending` },
  ]
  return (
    <section className={styles.section} aria-label="Registry snapshot">
      <div className={styles.sectionHead}>
        <Icon icon={Database} size={16} />
        <h2 className={styles.sectionTitle}>Registry snapshot</h2>
        <span className={styles.sectionSub}>imports · validation · duplicates</span>
      </div>
      <div className={styles.figureGrid}>
        {figures.map((f) => (
          <div key={f.label} className={styles.figure}>
            <span className={styles.figureLabel}>{f.label}</span>
            <span className={styles.figureValue}>{f.value}</span>
            {f.hint && <span className={styles.figureHint}>{f.hint}</span>}
          </div>
        ))}
      </div>
    </section>
  )
}

/**
 * System Administrator console — Overview. GOVERNANCE at a glance: who is provisioned,
 * what is configured, what needs attention, and what changed recently. Deliberately
 * carries NO infrastructure/system-health widgets (backup age, queue depth, snapshot
 * freshness) — those are an ops concern, not administration.
 */
export function AdminOverviewPage() {
  const { data, isLoading } = useAdminSummary()

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} label="Loading console" />
      </div>
    )
  }

  const k = data.kpis

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Governance overview</h1>
        <p className={styles.lead}>
          Provisioning, configuration and integrity across the platform. Operational delivery belongs to MDA accounts.
        </p>
      </header>

      <QuickActions />

      {/* ---------- GOVERNANCE KPIs ---------- */}
      <section className={styles.section} aria-label="Governance indicators">
        <span className={styles.groupLabel}>People &amp; organisations</span>
        <div className={styles.kpiBand}>
          <Kpi headline icon={Users} label="Total users" value={num(k.users_total)} hint={`${num(k.users_active)} active`} />
          <Kpi icon={ShieldCheck} label="Active users" value={num(k.users_active)} hint={`${num(k.users_suspended)} suspended`} />
          <Kpi icon={Building2} label="Registered MDAs" value={num(k.mdas_registered)} hint={`${num(k.mdas_active)} active`} />
          <Kpi icon={HandCoins} label="Development partners" value={num(k.development_partners)} />
        </div>

        {/*
          Catalogue size is a GOVERNANCE figure — how many programme types exist, which
          is the System Administrator's own responsibility (§10). Beneficiary, household
          and activity counts are REPORTING figures and have moved to the summary card
          below, which reads the dashboard aggregation. They were read here from
          `useAdminSummary` — a second source for the same question, free to disagree
          with the dashboard the same admin opens next.
        */}
        <span className={styles.groupLabel}>Catalogue</span>
        <div className={styles.kpiBand}>
          <Kpi icon={LibraryBig} label="Programmes in catalog" value={num(k.programmes_catalog)} />
        </div>
      </section>

      <ReportingSummaryCard />

      <AdoptionTrend trend={data.adoption_trend} />
      <RegistrySnapshot registry={data.registry} />

      {/* ---------- ADMINISTRATIVE ALERTS ---------- */}
      <section className={styles.section} aria-label="Administrative alerts">
        <div className={styles.sectionHead}>
          <Icon icon={AlertTriangle} size={16} />
          <h2 className={styles.sectionTitle}>Administrative alerts</h2>
        </div>
        <div className={styles.panel}>
          {data.alerts.length === 0 ? (
            <p className={styles.allClear}>
              <Icon icon={ShieldCheck} size={16} /> Nothing needs attention. Provisioning and data quality are in order.
            </p>
          ) : (
            <ul className={styles.alerts}>
              {data.alerts.map((a) => (
                <li key={a.id} className={styles.alert} data-severity={a.severity}>
                  <Icon icon={a.severity === 'info' ? Info : AlertTriangle} size={16} className={styles.alertIcon} />
                  <span className={styles.alertBody}>
                    <span className={styles.alertTitle}>{a.title}</span>
                    <span className={styles.alertDetail}>{a.detail}</span>
                  </span>
                </li>
              ))}
            </ul>
          )}
        </div>
      </section>

      {/* ---------- RECENT ADMINISTRATIVE ACTIVITY ---------- */}
      <section className={styles.section} aria-label="Recent administrative activity">
        <div className={styles.sectionHead}>
          <Icon icon={ScrollText} size={16} />
          <h2 className={styles.sectionTitle}>Recent administrative activity</h2>
          <span className={styles.sectionSub}>from the audit log</span>
        </div>
        <div className={styles.panel}>
          {data.recent_activity.length === 0 ? (
            <p className={styles.muted}>No recorded activity yet.</p>
          ) : (
            <ul className={styles.activity}>
              {data.recent_activity.map((a) => (
                <li key={a.id} className={styles.activityRow}>
                  <span className={styles.activityAction}>{humanizeAction(a.action)}</span>
                  <span className={styles.activityMeta}>
                    {a.entity_type ?? '—'} · {a.actor}
                    {a.actor_mda ? ` · ${a.actor_mda}` : ''}
                  </span>
                  <span className={styles.activityAt}>{when(a.at)}</span>
                </li>
              ))}
            </ul>
          )}
        </div>
      </section>

      <p className={styles.footnote}>
        Governance data · de-identified counts · audit payloads are never shown here · updated {when(data.generated_at)}
      </p>
    </div>
  )
}
