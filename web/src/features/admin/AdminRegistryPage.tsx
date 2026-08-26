import { Card } from '@/components/Card/Card'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ImportListPage } from '@/features/registry/ImportListPage'
import { useDashboard } from '@/features/dashboard/hooks'
import type { DashboardMetrics } from '@/features/dashboard/types'
import { useAdminSummary } from './hooks'
import type { AdminRegistrySnapshot } from './types'
import styles from './admin.module.css'

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const pct = (rate: number | null | undefined): string =>
  rate === null || rate === undefined ? '—' : `${Math.round(rate * 100)}%`

const titleCase = (s: string): string => s.replace(/[_-]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())

/** Labelled magnitude bars for a small categorical breakdown (counts only). */
function BarList({ map, empty }: { map: Record<string, number>; empty: string }) {
  const rows = Object.entries(map).sort((a, b) => b[1] - a[1])
  const max = Math.max(1, ...rows.map(([, v]) => v))

  if (rows.length === 0) return <p className={styles.muted}>{empty}</p>

  return (
    <div className={styles.bars}>
      {rows.map(([key, value]) => (
        <div key={key} className={styles.barRow}>
          <span className={styles.barLabel}>{titleCase(key)}</span>
          <span className={styles.barTrack}>
            <span className={styles.barFill} style={{ width: `${Math.max(2, Math.round((value / max) * 100))}%` }} />
          </span>
          <span className={styles.barVal}>{num(value)}</span>
        </div>
      ))}
    </div>
  )
}

function Figure({ label, value, hint }: { label: string; value: string; hint?: string }) {
  return (
    <div className={styles.figure}>
      <span className={styles.figureLabel}>{label}</span>
      <span className={styles.figureValue}>{value}</span>
      {hint && <span className={styles.figureHint}>{hint}</span>}
    </div>
  )
}

/** A 0..1 quality meter. */
function Meter({ label, rate, hint }: { label: string; rate: number | null | undefined; hint?: string }) {
  const width = rate === null || rate === undefined ? 0 : Math.max(2, Math.round(rate * 100))
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

/**
 * Registry statistics + DATA-SOURCE monitoring, from the scoped reporting aggregates.
 * Counts by status, provenance and area — never a beneficiary record.
 */
function RegistryPanel({ metrics }: { metrics: DashboardMetrics }) {
  const b = metrics.registry.beneficiaries
  const households = metrics.registry.households
  const pop = metrics.population
  const hh = metrics.household_size

  return (
    <div className={styles.page}>
      <div className={styles.figureGrid}>
        <Figure label="Registered individuals" value={num(b.total)} hint="deduplicated registry" />
        <Figure label="Registered households" value={num(households?.total ?? pop?.total_households)} />
        <Figure label="Average household size" value={hh?.average_size ? hh.average_size.toFixed(2) : '—'} />
        <Figure label="LGAs covered" value={num(pop?.lgas_covered)} />
        <Figure label="Wards covered" value={num(pop?.wards_covered)} />
        <Figure
          label="New registrations"
          value={num(pop?.new_registrations_period)}
          hint={`last ${num(pop?.period_days)} days`}
        />
      </div>

      <div className={styles.demoGrid}>
        <Card titleAs="h2" eyebrow="Registry" title="By status">
          <BarList map={b.by_status} empty="No records yet." />
        </Card>
        <Card titleAs="h2" eyebrow="Data sources" title="By provenance">
          <BarList map={b.by_source} empty="No provenance recorded." />
        </Card>
      </div>

      <Card titleAs="h2" eyebrow="Coverage" title="By LGA">
        <BarList map={b.by_lga} empty="No areas recorded." />
      </Card>

      <p className={styles.footnote}>
        Aggregate counts only · every figure is de-identified — no beneficiary record is read here
      </p>
    </div>
  )
}

/**
 * DATA-QUALITY indicators (Phase 2 completeness) and DUPLICATE-DETECTION statistics
 * (Phase 3 matching). Completeness is field PRESENCE only — NIN coverage is measured
 * from the keyed hash, never the value itself (SECURITY.md §4).
 */
function QualityPanel({ metrics, snapshot }: { metrics: DashboardMetrics; snapshot: AdminRegistrySnapshot }) {
  const q = metrics.registry_quality
  const dup = metrics.duplicates

  return (
    <div className={styles.page}>
      <Card titleAs="h2" eyebrow="Data quality" title="Completeness &amp; coverage">
        <div className={styles.meters}>
          <Meter label="Data completeness" rate={q?.data_completeness} hint="across captured fields" />
          <Meter label="NIN coverage" rate={q?.nin_completeness} hint="identity linkage (hash presence)" />
          <Meter label="Phone coverage" rate={q?.phone_completeness} />
          <Meter label="Row validation rate" rate={snapshot.validation_rate} hint="valid import rows" />
        </div>
      </Card>

      <div className={styles.figureGrid}>
        <Figure label="Verified" value={num(q?.verified)} hint="status: active" />
        <Figure label="Pending review" value={num(q?.pending)} hint="flagged" />
        <Figure label="Suspended" value={num(q?.suspended)} />
        <Figure label="Rows rejected" value={num(snapshot.rows_invalid)} hint={`of ${num(snapshot.rows_total)} processed`} />
        <Figure label="Duplicate rate" value={pct(snapshot.duplicate_rate)} hint="matches ÷ records" />
        <Figure label="Duplicates pending" value={num(snapshot.duplicates_pending)} hint="awaiting adjudication" />
      </div>

      <Card titleAs="h2" eyebrow="Duplicate detection" title="Matching outcomes">
        {dup === null || dup === undefined ? (
          <p className={styles.muted}>No matching activity recorded.</p>
        ) : (
          <div className={styles.figureGrid}>
            <Figure label="Matches surfaced" value={num(dup.matches_surfaced)} hint="exact + probable" />
            <Figure label="Resolved as new" value={num(dup.resolved_new)} hint="distinct person" />
            <Figure label="Resolved as served" value={num(dup.resolved_served)} hint="linked to existing" />
            <Figure label="Skipped" value={num(dup.resolved_skipped)} />
          </div>
        )}
      </Card>

      <p className={styles.footnote}>
        Completeness measures field PRESENCE, never values · NIN coverage is read from the keyed hash · no identifiers
        are shown in this console
      </p>
    </div>
  )
}

/**
 * Registry &amp; Data Quality (console section 5) — READ-ONLY oversight that COMPOSES
 * existing data:
 *
 *  - **Registry** and **Data quality** read the scoped reporting aggregates
 *    (`/dashboard`, which resolves state-wide for an administrator) plus the console's
 *    governance snapshot — aggregate counts only, so there is no PII to mask.
 *  - **Import history** renders the existing {@link ImportListPage} in read-only mode:
 *    the same batches, statuses and validation counts an MDA sees, minus the upload
 *    panel (bulk ingestion belongs to an acting MDA — CLAUDE.md §10).
 *
 * Nothing here re-implements registry, import or matching logic.
 */
export function AdminRegistryPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const { data: dashboard, isLoading: dashboardLoading } = useDashboard(undefined, canView)
  const { data: summary, isLoading: summaryLoading } = useAdminSummary(canView)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view registry oversight.</p>
      </Card>
    )
  }

  if (dashboardLoading || summaryLoading || !dashboard || !summary) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={28} label="Loading registry oversight" />
      </div>
    )
  }

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Registry &amp; Data Quality</h1>
        <p className={styles.lead}>
          Read-only oversight of the shared registry: size and provenance, import throughput, validation results and
          duplicate detection. Aggregates only — beneficiary records stay with their owning MDA.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'registry', label: 'Registry', content: <RegistryPanel metrics={dashboard.metrics} /> },
          {
            id: 'quality',
            label: 'Data quality',
            content: <QualityPanel metrics={dashboard.metrics} snapshot={summary.registry} />,
          },
          { id: 'imports', label: 'Import history', content: <ImportListPage readOnly embedded /> },
        ]}
      />
    </div>
  )
}
