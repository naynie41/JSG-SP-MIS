import {
  AlertTriangle,
  ArrowRight,
  Building2,
  CalendarPlus,
  CheckCircle2,
  ClipboardList,
  Coins,
  Info,
  Lightbulb,
  MapPin,
  Network,
  TrendingUp,
  Users,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import { buildAlerts, buildInsights } from './executiveInsights'
import type { InsightTone } from './executiveInsights'
import { Figure, TrendCard } from './executiveWidgets'
import { ExecutiveSectionCard } from './ExecutiveSectionCard'
import { MinistryBrief } from './MinistryBrief'
import { num, pct } from './executiveFormat'
import type { DashboardResponse, DrillFn } from './types'
import styles from './executiveOverview.module.css'

const INSIGHT_ICON: Record<InsightTone, LucideIcon> = {
  positive: CheckCircle2,
  neutral: Lightbulb,
  attention: AlertTriangle,
}

export interface ExecutiveOverviewTabProps {
  data: DashboardResponse
  /** Drill-down: jump to a detailed page with a scoped filter. */
  onDrill?: DrillFn
}

/**
 * Executive Overview — the Governor's first screen.
 *
 * It answers one question: *is social protection working across the state, and
 * where is it not?* PRODUCT.md describes this audience as highest-stakes and
 * lowest-frequency — they arrive infrequently and must comprehend without
 * training. Fifty co-equal figures is a page that has to be studied, and an
 * executive who must study a dashboard asks for the PDF instead, which is the
 * manual-report problem this system exists to replace.
 *
 * So this page carries the headline (net-unique reached — the one figure that
 * exists only because of deduplication, and therefore the product's own proof of
 * value; rendered in the shell hero), three figures that qualify it, the
 * interpretive prose, and a single trend. Delivery, programme distribution,
 * projections and demographics moved to the sections that own those questions —
 * nothing was deleted.
 */
export function ExecutiveOverviewTab({ data, onDrill }: ExecutiveOverviewTabProps) {
  const m = data.metrics
  const pop = m.population
  const budget = m.benefits.budget
  const trends = m.trends
  const ministries = m.ministry_brief ?? null

  // Condensed to the first three: the interpretation earns its place by saying what
  // the figures MEAN, which is the thing an executive is short of — but it no longer
  // gets a full panel, because the sections below need the room more.
  const insights = buildInsights(m).slice(0, 3)
  const alerts = buildAlerts(m)

  return (
    <div className={styles.page}>
      {/*
        NEEDS ATTENTION COMES FIRST, above the figures.

        An executive with a minute on this page can act on exactly one part of it:
        the things that are wrong. The figures are context for those, not the other
        way round — so putting the numbers first, as a dashboard conventionally
        does, buries the only actionable content under a band that is usually
        reassuring. Every row names the section that explains it and goes there.
      */}
      <section className={styles.reveal} aria-labelledby="attention-heading">
        <div className={styles.sectionHead}>
          <Icon icon={AlertTriangle} size={18} />
          <h2 className={styles.sectionTitle} id="attention-heading">
            Needs attention
          </h2>
        </div>
        {alerts.length === 0 ? (
          <p className={styles.allClear}>
            <Icon icon={CheckCircle2} size={16} /> Nothing is outside its threshold. All indicators are within range.
          </p>
        ) : (
          <ul className={styles.alerts}>
            {alerts.map((a) => (
              <li key={a.id} className={styles.alert} data-severity={a.severity}>
                <Icon icon={a.severity === 'info' ? Info : AlertTriangle} size={16} className={styles.alertIcon} />
                <span className={styles.alertBody}>
                  <span className={styles.alertTitle}>{a.title}</span>
                  <span className={styles.alertDetail}>{a.detail}</span>
                </span>
                {a.tab && onDrill && (
                  <button type="button" className={styles.alertGo} onClick={() => onDrill(a.tab!, {})}>
                    Open<Icon icon={ArrowRight} size={13} aria-hidden="true" />
                  </button>
                )}
                <span className={styles.alertBadge} data-severity={a.severity}>
                  {a.severity}
                </span>
              </li>
            ))}
          </ul>
        )}
      </section>

      {/*
        Four figures, and none of them repeats the hero.

        The hero already carries "People reached" and "Benefits disbursed", so a
        band restating either is spending the reader's attention on something they
        read a second ago. Budget appears here as the SHARE used, which is the thing
        the hero's naira figure does not say.
      */}
      <section className={styles.reveal} style={{ animationDelay: '80ms' }} aria-label="Key indicators">
        <div className={styles.figureGrid} data-cols="4">
          <Figure
            icon={Coins}
            label="Budget used"
            value={`${pct(budget.utilization_rate)}%`}
            hint={`${formatNaira(budget.utilized_value)} of ${formatNaira(budget.allocated)}`}
          />
          <Figure
            icon={MapPin}
            label="LGAs covered"
            value={num(pop?.lgas_covered)}
            hint="local government areas reached"
          />
          <Figure
            icon={CalendarPlus}
            label="New this period"
            value={num(pop?.new_registrations_period)}
            hint={`last ${num(pop?.period_days)} days`}
          />
          <Figure
            icon={Building2}
            label="Agencies delivering"
            value={num(m.coordination?.active_mdas)}
            hint={`${num(m.programmes?.activities_active)} activities running`}
          />
        </div>
      </section>

      {/* What it adds up to. Three lines, not a panel — the only part of the page
          that says what the numbers MEAN rather than what they are. */}
      {insights.length > 0 && (
        <section className={styles.reveal} style={{ animationDelay: '140ms' }} aria-label="Interpretation">
          <ul className={styles.insightStrip}>
            {insights.map((i) => (
              <li key={i.id} className={styles.insight} data-tone={i.tone}>
                <Icon icon={INSIGHT_ICON[i.tone]} size={15} className={styles.insightIcon} />
                <span>{i.text}</span>
              </li>
            ))}
          </ul>
        </section>
      )}

      {/*
        One card per section, each answering that section's question and leading to
        the page that explains it. This is the "packed" part — and the cap of four
        figures a card is what keeps packed from becoming a wall: a reader who wants
        the fifth figure wants the tab, and the card says so.
      */}
      <section className={styles.reveal} style={{ animationDelay: '200ms' }} aria-label="Across the state">
        <div className={styles.sectionCards}>
          <ExecutiveSectionCard
            icon={ClipboardList}
            title="Delivery"
            question="Are programmes running, and is the money moving?"
            tab="programmes"
            linkLabel="View programmes"
            onDrill={onDrill}
            figures={[
              { label: 'Active programmes', value: num(m.programmes?.active) },
              { label: 'Activities running', value: num(m.programmes?.activities_active), hint: `of ${num(m.programmes?.activities_total)}` },
              {
                label: 'Budget delivered',
                value: `${pct(budget.utilization_rate)}%`,
                hint: `${formatNaira(budget.utilized_value)} of ${formatNaira(budget.allocated)}`,
                tone: (budget.utilization_rate ?? 0) < 0.25 ? 'warn' : 'default',
              },
              { label: 'Benefits recorded', value: num(budget.benefit_count) },
            ]}
          />

          <ExecutiveSectionCard
            icon={Users}
            title="The register"
            question="Is the record sound, and is deduplication working?"
            tab="registry"
            linkLabel="View register"
            onDrill={onDrill}
            figures={[
              { label: 'People registered', value: num(m.registry?.beneficiaries?.total) },
              {
                label: 'Verified',
                value: `${pct((m.registry_quality?.verified ?? 0) / Math.max(1, m.registry_quality?.total ?? 1))}%`,
                hint: `${num(m.registry_quality?.pending)} pending`,
                tone: (m.registry_quality?.pending ?? 0) > 0 ? 'warn' : 'default',
              },
              { label: 'Duplicates caught', value: num(m.registry_quality?.duplicates_detected), hint: 'before anyone was served twice' },
              {
                label: 'NIN recorded',
                value: `${pct(m.registry_quality?.nin_completeness)}%`,
                tone: (m.registry_quality?.nin_completeness ?? 0) < 0.8 ? 'warn' : 'default',
              },
            ]}
          />

          <ExecutiveSectionCard
            icon={MapPin}
            title="Reach"
            question="Does delivery reach the whole state?"
            tab="coverage"
            linkLabel="View coverage"
            onDrill={onDrill}
            figures={[
              { label: 'LGAs reached', value: `${num(pop?.lgas_covered)} of 27` },
              { label: 'Wards reached', value: num(pop?.wards_covered) },
              {
                label: 'Below threshold',
                value: num(m.coverage_bands?.summary?.red),
                hint: 'areas in the lowest coverage band',
                tone: (m.coverage_bands?.summary?.red ?? 0) > 0 ? 'bad' : 'default',
              },
              { label: 'Households', value: num(pop?.total_households) },
            ]}
          />

          <ExecutiveSectionCard
            icon={Network}
            title="Coordination"
            question="Are agencies working together, and are handovers honoured?"
            tab="coordination"
            linkLabel="View coordination"
            onDrill={onDrill}
            figures={[
              { label: 'Agencies active', value: num(m.coordination?.active_mdas), hint: `${num(m.coordination?.joint_programmes)} joint programmes` },
              {
                label: 'Referrals overdue',
                value: num(m.referrals?.overdue),
                hint: `of ${num(m.referrals?.total)} raised`,
                tone: (m.referrals?.overdue ?? 0) > 0 ? 'bad' : 'default',
              },
              {
                label: 'Grievances past SLA',
                value: num(m.grievances?.sla_breaches),
                hint: `of ${num(m.grievances?.total)} raised`,
                tone: (m.grievances?.sla_breaches ?? 0) > 0 ? 'warn' : 'default',
              },
              { label: 'Serve requests approved', value: `${pct(m.coordination?.request_to_serve?.approval_rate)}%`, hint: `${num(m.coordination?.request_to_serve?.raised)} raised` },
            ]}
          />
        </div>
      </section>

      {/* Who is actually delivering, and what. Sits after the interpretation and
          before the trend: an executive asks "is it working" first, then "who is
          doing it", then "which way is it going". */}
      {ministries !== null && ministries.length > 0 && <MinistryBrief ministries={ministries} />}

      {/* One trend: the headline's trajectory. The disbursement, registration and
          programme-growth series live with their own sections. */}
      {trends && (
        <section className={styles.reveal} style={{ animationDelay: '240ms' }} aria-label="Trend">
          <div className={styles.sectionHead}>
            <Icon icon={TrendingUp} size={16} />
            <h2 className={styles.sectionTitle}>
              Beneficiaries reached over the last {trends.months.length} months
            </h2>
          </div>
          <div className={styles.trendGrid} data-cols="1">
            <TrendCard title="Beneficiaries reached (cumulative)" points={trends.beneficiaries_cumulative} format={num} />
          </div>
        </section>
      )}
    </div>
  )
}
