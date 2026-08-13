import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { MatchingConfigPage } from '@/features/registry/MatchingConfigPage'
import { useMatchingVersions } from '@/features/registry/hooks'
import type { MatchingConfig } from '@/features/registry/types'
import { useDashboard } from '@/features/dashboard/hooks'
import { useAdminSummary, useRegistryRules } from './hooks'
import type { RegistryRuleField } from './types'
import styles from './admin.module.css'

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const pct = (rate: number | null | undefined): string =>
  rate === null || rate === undefined ? '—' : `${Math.round(rate * 100)}%`
const titleCase = (s: string): string => s.replace(/[_-]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())

function Figure({ label, value, hint }: { label: string; value: string; hint?: string }) {
  return (
    <div className={styles.figure}>
      <span className={styles.figureLabel}>{label}</span>
      <span className={styles.figureValue}>{value}</span>
      {hint && <span className={styles.figureHint}>{hint}</span>}
    </div>
  )
}

/**
 * Version history for the matching configuration — every publish is a new immutable
 * version (never an in-place edit), so this is the audit trail of the cascade.
 */
function VersionsPanel() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('matching.view')
  const { data, isLoading } = useMatchingVersions(canView)

  if (!canView) return <p className={styles.muted}>You do not have permission to view matching configuration.</p>
  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading versions" />
      </div>
    )
  }

  const columns: Column<MatchingConfig>[] = [
    {
      key: 'version',
      header: 'Version',
      render: (v) => (
        <>
          <strong>v{v.version}</strong>
          {v.is_active && (
            <>
              {' '}
              <Badge variant="success" dot>
                active
              </Badge>
            </>
          )}
        </>
      ),
    },
    {
      key: 'cascade',
      header: 'Cascade order',
      render: (v) => v.deterministic_rules.map((rule) => rule.join(' + ')).join(' → ') || '—',
    },
    { key: 'review', header: 'Review', align: 'right', render: (v) => v.review_threshold.toFixed(2) },
    {
      key: 'auto',
      header: 'Auto-accept',
      align: 'right',
      render: (v) => (v.auto_accept_threshold === null ? '—' : v.auto_accept_threshold.toFixed(2)),
    },
    { key: 'description', header: 'Note', render: (v) => v.description ?? '—' },
  ]

  return (
    <div className={styles.page}>
      <Card flush>
        <DataTable rows={data} columns={columns} getRowId={(v) => v.id} caption="Matching configuration versions" />
      </Card>
      <p className={styles.footnote}>
        Every publish creates a new immutable version and is audited · the active version drives the engine
      </p>
    </div>
  )
}

/** Duplicate-detection statistics produced by the configured engine. */
function DuplicateStatsPanel() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const { data: dashboard, isLoading: a } = useDashboard(undefined, canView)
  const { data: summary, isLoading: b } = useAdminSummary(canView)

  if (!canView) return <p className={styles.muted}>You do not have permission to view duplicate statistics.</p>
  if (a || b || !dashboard || !summary) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading duplicate statistics" />
      </div>
    )
  }

  const dup = dashboard.metrics.duplicates
  const reg = summary.registry

  return (
    <div className={styles.page}>
      <div className={styles.figureGrid}>
        <Figure label="Matches surfaced" value={num(reg.duplicates_surfaced)} hint="exact + probable" />
        <Figure label="Resolved" value={num(reg.duplicates_resolved)} />
        <Figure label="Pending adjudication" value={num(reg.duplicates_pending)} />
        <Figure label="Duplicate rate" value={pct(reg.duplicate_rate)} hint="matches ÷ rows processed" />
        <Figure label="Rows processed" value={num(reg.rows_total)} />
        <Figure label="Validation rate" value={pct(reg.validation_rate)} hint="valid rows" />
      </div>

      <Card titleAs="h2" eyebrow="Adjudication" title="How matches were resolved">
        {dup === null || dup === undefined ? (
          <p className={styles.muted}>No matching activity recorded yet.</p>
        ) : (
          <div className={styles.figureGrid}>
            <Figure label="Resolved as new" value={num(dup.resolved_new)} hint="distinct person" />
            <Figure label="Resolved as served" value={num(dup.resolved_served)} hint="linked to existing" />
            <Figure label="Skipped" value={num(dup.resolved_skipped)} />
          </div>
        )}
      </Card>

      <p className={styles.footnote}>
        Produced by the active matching configuration · adjudication happens at the probable band only (§9)
      </p>
    </div>
  )
}

/**
 * The enforced registry validation rules — READ ONLY. Identity-field handling is a
 * locked decision (CLAUDE.md §9): a present-but-malformed identity field rejects the
 * whole row and is never partial-saved, so these are policy, not configuration. The
 * payload comes from the canonical rule class, so this view cannot drift from what
 * ingestion actually enforces.
 */
function ValidationRulesPanel() {
  const { data, isLoading } = useRegistryRules()

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading validation rules" />
      </div>
    )
  }

  const columns: Column<RegistryRuleField>[] = [
    { key: 'field', header: 'Field', render: (f) => <strong>{titleCase(f.field)}</strong> },
    {
      key: 'class',
      header: 'Class',
      render: (f) =>
        f.identity ? (
          <Badge variant="warning" dot>
            identity
          </Badge>
        ) : (
          <Badge variant="neutral">standard</Badge>
        ),
    },
    { key: 'required', header: 'Required', render: (f) => (f.required ? 'Yes' : 'Optional') },
    {
      key: 'constraints',
      header: 'Constraints',
      render: (f) => <span className={styles.activityMeta}>{f.constraints.join(' · ')}</span>,
    },
  ]

  return (
    <div className={styles.page}>
      <div className={styles.inertGridWide}>
        <div className={styles.policyNote}>
          <span className={styles.policyLabel}>Identity fields</span>
          <span className={styles.policyText}>{data.policy.identity}</span>
        </div>
        <div className={styles.policyNote}>
          <span className={styles.policyLabel}>Standard fields</span>
          <span className={styles.policyText}>{data.policy.non_identity}</span>
        </div>
      </div>

      <Card flush>
        <DataTable
          rows={data.fields}
          columns={columns}
          getRowId={(f) => f.field}
          caption="Registry validation rules"
        />
      </Card>

      <p className={styles.footnote}>
        Read-only — these rules are a locked decision, not configuration · shown from the same source both ingestion
        paths enforce
      </p>
    </div>
  )
}

/**
 * Matching Rules &amp; Registry Config (console section 6) — COMPOSES the Phase 3 engine:
 *
 *  - **Matching rules** renders the existing {@link MatchingConfigPage}: the cascade
 *    order, fuzzy fields and weights (confidence scoring), and the review/auto-accept
 *    thresholds. Publishing goes through `/matching/config`, creating a new immutable,
 *    audited version that the engine picks up — there is no second config store.
 *  - **Version history** and **Duplicate statistics** read existing endpoints.
 *  - **Validation rules** are read-only policy (§9), served from the canonical source.
 */
export function AdminMatchingPage() {
  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Matching Rules &amp; Registry Config</h1>
        <p className={styles.lead}>
          The duplicate-verification cascade, confidence weights and thresholds — versioned and audited — plus the
          statistics they produce and the registry validation rules they run alongside.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'rules', label: 'Matching rules', content: <MatchingConfigPage embedded /> },
          { id: 'versions', label: 'Version history', content: <VersionsPanel /> },
          { id: 'stats', label: 'Duplicate statistics', content: <DuplicateStatsPanel /> },
          { id: 'validation', label: 'Validation rules', content: <ValidationRulesPanel /> },
        ]}
      />
    </div>
  )
}
