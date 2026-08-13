import { Link, useNavigate } from 'react-router-dom'
import {
  AlertTriangle,
  Bell,
  CalendarPlus,
  ChevronRight,
  ClipboardList,
  FileBarChart,
  HandHeart,
  Inbox,
  Layers,
  LibraryBig,
  ShieldCheck,
  Split,
  UploadCloud,
  UserSquare2,
  Users,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useDashboard } from '@/features/dashboard/hooks'
import { useNotifications } from '@/features/notifications/hooks'
import { useMdaActionRequired } from './hooks'
import styles from './mda.module.css'

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()

function when(iso: string | null): string {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}

/* --------------------------------------------------------------- quick actions */

interface QuickAction {
  id: string
  label: string
  icon: LucideIcon
  to: string
  /** Permission required to launch it; omit for always-available. */
  permission?: string
}

/**
 * Launchers into EXISTING flows — this console never introduces a parallel form.
 * Each is filtered by the signed-in user's permissions, so an Officer and an Admin
 * see the same bar minus what they cannot do.
 */
const QUICK_ACTIONS: QuickAction[] = [
  { id: 'create-activity', label: 'Create Activity', icon: CalendarPlus, to: '/activities', permission: 'activity.create' },
  { id: 'upload-beneficiaries', label: 'Upload Beneficiaries', icon: UploadCloud, to: '/imports', permission: 'beneficiary.create' },
  { id: 'record-benefit', label: 'Record Benefit Delivery', icon: HandHeart, to: '/benefits/record', permission: 'benefit.create' },
  { id: 'create-referral', label: 'Create Referral', icon: Split, to: '/referrals', permission: 'referral.create' },
  { id: 'request-to-serve', label: 'Request to Serve', icon: UserSquare2, to: '/duplicate-search', permission: 'beneficiary-lookup.view' },
  { id: 'generate-report', label: 'Generate Report', icon: FileBarChart, to: '/mda/reports', permission: 'reporting.view' },
]

function QuickActions() {
  const { hasPermission } = useAuth()
  const navigate = useNavigate()
  const actions = QUICK_ACTIONS.filter((a) => !a.permission || hasPermission(a.permission))

  if (actions.length === 0) return null

  return (
    <section className={styles.section} aria-label="Quick actions">
      <div className={styles.sectionHead}>
        <Icon icon={Layers} size={16} />
        <h2 className={styles.sectionTitle}>Quick actions</h2>
        <span className={styles.sectionSub}>launch an existing flow</span>
      </div>
      <div className={styles.actionBar}>
        {actions.map((a) => (
          <button key={a.id} type="button" className={styles.action} onClick={() => navigate(a.to)}>
            <Icon icon={a.icon} size={15} />
            {a.label}
          </button>
        ))}
      </div>
    </section>
  )
}

/* ------------------------------------------------------------------ KPI band */

function Kpi({ icon, label, value, hint }: { icon: LucideIcon; label: string; value: string; hint?: string }) {
  return (
    <div className={styles.kpi}>
      <span className={styles.kpiLabel}>
        <Icon icon={icon} size={13} />
        {label}
      </span>
      <span className={styles.kpiValue}>{value}</span>
      {hint && <span className={styles.kpiHint}>{hint}</span>}
    </div>
  )
}

/* ------------------------------------------------------------ action required */

/**
 * A queue tile. It is a LINK, not a metric — it carries affordance and deep-links into
 * the module where the work is done. A cleared queue renders quiet rather than
 * shouting a zero.
 */
function ActionCard({
  icon,
  count,
  label,
  hint,
  to,
  loading,
}: {
  icon: LucideIcon
  count: number
  label: string
  hint: string
  to: string
  /** Counts arrive on their own request; the card keeps its shape while they do. */
  loading: boolean
}) {
  const outstanding = !loading && count > 0
  return (
    <Link
      to={to}
      className={`${styles.actionCard} ${outstanding ? styles.actionCardActive : ''}`}
      aria-label={loading ? `${label}: loading.` : `${label}: ${count}. Open in Service Delivery.`}
    >
      <span className={styles.actionIcon} aria-hidden="true">
        <Icon icon={icon} size={18} />
      </span>
      <span className={styles.actionBody}>
        {/* An em dash, not a spinner or a zero: the card must not claim a cleared
            queue before it knows, and it must not change height when it finds out. */}
        <span className={styles.actionCount}>{loading ? '—' : num(count)}</span>
        <span className={styles.actionLabel}>{label}</span>
        <span className={styles.actionHint}>
          {loading ? 'Checking…' : outstanding ? hint : 'Nothing waiting on your MDA'}
        </span>
      </span>
      <Icon icon={ChevronRight} size={18} className={styles.actionChevron} />
    </Link>
  )
}

/* ------------------------------------------------------------------------ page */

/**
 * MDA Overview — the first screen of the delivery workspace.
 *
 * Two data sources, deliberately:
 *  - **KPIs** reuse the Phase 6 aggregation (`GET /dashboard`), MDA-scoped by
 *    `DashboardScopeResolver`. Unfiltered, that is a snapshot on a 15-minute refresh,
 *    which is right for metrics.
 *  - **Action-required counters** come from `GET /mda/action-required`, which is LIVE
 *    and DIRECTIONAL. A work queue must not be stale, and "awaiting me" is the inbound
 *    side — something the dashboard's two-party referral block cannot express.
 *
 * Alerts are derived from figures already in the snapshot; nothing is invented. Recent
 * activity is the caller's Phase 5 notification feed — the same source as the header
 * bell — labelled as such rather than presented as an MDA-wide audit stream.
 */
export function MdaOverviewPage() {
  const { user, hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')

  const { data, isLoading } = useDashboard(undefined, canView)
  const { data: actions, isLoading: actionsLoading } = useMdaActionRequired(canView)
  const { data: notifications } = useNotifications(true)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view the MDA dashboard.</p>
      </Card>
    )
  }

  const m = data?.metrics
  const mdaName = user?.mda?.name ?? 'your MDA'

  // Operational alerts, all derived from real scoped figures in the snapshot.
  const alerts: { id: string; severity: 'warning' | 'info'; title: string; detail: string }[] = []
  if ((m?.referrals?.overdue ?? 0) > 0) {
    alerts.push({
      id: 'overdue-referrals',
      severity: 'warning',
      title: `${num(m?.referrals?.overdue)} referrals past their SLA`,
      detail: 'Referrals your MDA has not completed within the agreed response time.',
    })
  }
  if ((m?.grievances?.sla_breaches ?? 0) > 0) {
    alerts.push({
      id: 'grievance-sla',
      severity: 'warning',
      title: `${num(m?.grievances?.sla_breaches)} grievances past their SLA`,
      detail: 'Cases in your MDA that have breached the resolution window.',
    })
  }
  const unresolved =
    (m?.duplicates?.matches_surfaced ?? 0) -
    ((m?.duplicates?.resolved_new ?? 0) + (m?.duplicates?.resolved_served ?? 0) + (m?.duplicates?.resolved_skipped ?? 0))
  if (unresolved > 0) {
    alerts.push({
      id: 'unresolved-duplicates',
      severity: 'info',
      title: `${num(unresolved)} possible duplicates awaiting review`,
      detail: 'Imported rows matched an existing record and need an adjudication decision.',
    })
  }

  const recent = (notifications?.items ?? []).slice(0, 6)

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Overview</h1>
        <p className={styles.lead}>
          {mdaName} — the programmes you deliver, the people you have registered, and the work waiting on your team.
          Everything here is scoped to your MDA.
        </p>
      </header>

      {isLoading ? (
        <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
          <Spinner size={26} label="Loading your MDA overview" />
        </div>
      ) : (
        <>
          <section className={styles.section} aria-label="Delivery at a glance">
            <div className={styles.kpiBand}>
              <Kpi
                icon={LibraryBig}
                label="Programmes"
                value={num(m?.programmes?.total)}
                hint="catalogue programmes you deliver"
              />
              <Kpi
                icon={ClipboardList}
                label="Activities"
                value={num(m?.programmes?.activities_total)}
                hint={`${num(m?.programmes?.activities_active)} active`}
              />
              <Kpi
                icon={Users}
                label="Registered beneficiaries"
                value={num(m?.registry?.beneficiaries?.total)}
                hint="owned by your MDA"
              />
              <Kpi
                icon={HandHeart}
                label="Benefits delivered"
                value={num(m?.benefits?.disbursed?.benefit_count)}
                hint="interventions recorded"
              />
            </div>
          </section>

          <section className={styles.section} aria-label="Action required">
            <div className={styles.sectionHead}>
              <Icon icon={Inbox} size={16} />
              <h2 className={styles.sectionTitle}>Action required</h2>
              <span className={styles.sectionSub}>live · waiting on your MDA</span>
            </div>
            {/* Rendered unconditionally: the counts load on a separate request, and
                swapping a spinner for the grid shifted everything below it down the
                page the moment they arrived. */}
            <div className={styles.actionGrid}>
              <ActionCard
                icon={Split}
                loading={actionsLoading}
                count={actions?.pending_referrals ?? 0}
                label="Referrals awaiting your response"
                hint="Another MDA referred a beneficiary to you."
                to="/mda/service-delivery?tab=referrals"
              />
              <ActionCard
                icon={ShieldCheck}
                loading={actionsLoading}
                count={actions?.pending_service_requests ?? 0}
                label="Request-to-serve approvals"
                hint="Another MDA wants to serve a beneficiary you own."
                to="/mda/service-delivery?tab=service-requests"
              />
            </div>
          </section>

          <QuickActions />

          <section className={styles.section} aria-label="Operational alerts">
            <div className={styles.sectionHead}>
              <Icon icon={AlertTriangle} size={16} />
              <h2 className={styles.sectionTitle}>Alerts</h2>
            </div>
            {alerts.length === 0 ? (
              <p className={styles.allClear}>
                <Icon icon={ShieldCheck} size={15} />
                Nothing overdue or unresolved in your MDA.
              </p>
            ) : (
              <div className={styles.alerts}>
                {alerts.map((a) => (
                  <div key={a.id} className={styles.alert} data-severity={a.severity}>
                    <Icon icon={AlertTriangle} size={16} className={styles.alertIcon} />
                    <div className={styles.alertBody}>
                      <span className={styles.alertTitle}>{a.title}</span>
                      <span className={styles.alertDetail}>{a.detail}</span>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </section>

          <section className={styles.section} aria-label="Recent activity">
            <div className={styles.sectionHead}>
              <Icon icon={Bell} size={16} />
              <h2 className={styles.sectionTitle}>Recent activity</h2>
              <span className={styles.sectionSub}>your notifications</span>
            </div>
            <Card>
              {recent.length === 0 ? (
                <p className={styles.muted}>Nothing yet. Referrals, approvals and import results appear here.</p>
              ) : (
                <div className={styles.activity}>
                  {recent.map((n) => (
                    <div key={n.id} className={styles.activityRow}>
                      <span className={styles.activityAction}>{n.subject}</span>
                      <span className={styles.activityMeta}>{n.body}</span>
                      <span className={styles.activityAt}>{when(n.created_at)}</span>
                    </div>
                  ))}
                </div>
              )}
              <p className={styles.footnote}>
                Drawn from your notification inbox — the same feed as the bell in the header, not an MDA-wide audit
                trail
              </p>
            </Card>
          </section>
        </>
      )}
    </div>
  )
}
