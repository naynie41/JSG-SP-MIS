import { useState } from 'react'
import { Columns3, PlugZap, Power, PowerOff, RefreshCw } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ImportListPage } from '@/features/registry/ImportListPage'
import { ConnectorMappingModal } from '@/features/sync/ConnectorMappingModal'
import {
  isSyncUnavailable,
  useSetConnectorEnabled,
  useSyncConnectors,
  useSyncRuns,
  useTriggerSync,
} from '@/features/sync/hooks'
import type { SyncConnector, SyncRun } from '@/features/sync/types'
import syncStyles from '@/features/sync/sync.module.css'
import styles from './admin.module.css'

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const sentenceCase = (s: string): string => {
  const words = s.replace(/[_-]/g, ' ')
  return words.charAt(0).toUpperCase() + words.slice(1)
}

function when(iso: string | null): string {
  if (!iso) return 'never'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}

/**
 * The honest empty state when the Phase 7 synchronization engine is not enabled in this
 * deployment. It fabricates nothing — no zeroed counters, no placeholder connectors.
 */
function SyncPending({ what }: { what: string }) {
  return (
    <div className={styles.scaffold}>
      <Icon icon={PlugZap} size={22} />
      <p className={styles.scaffoldTitle}>Available when synchronization (Phase 7) is enabled</p>
      <p className={styles.scaffoldNote}>
        {what} will appear here once the synchronization engine is switched on for this deployment. Nothing is shown in
        the meantime. This console never displays placeholder sync data.
      </p>
    </div>
  )
}

/**
 * A connector's standing mapping state (CLAUDE.md §11).
 *
 * `stale` is shown distinctly from `never configured`, because they demand different
 * actions: one connector has never been set up, the other WAS working and has stopped.
 * Collapsing them into "not ready" would hide a broken feed among unfinished setup.
 */
function MappingStatusCell({ connector }: { connector: SyncConnector }) {
  const { status, confirmed_at, confirmed_by, stale_reason } = connector.mapping

  if (status === 'never_configured') {
    return (
      <Badge variant="neutral" dot>
        Not configured
      </Badge>
    )
  }

  if (status === 'stale') {
    return (
      <div className={syncStyles.statusCell}>
        <Badge variant="warning" dot>
          Needs review
        </Badge>
        <span className={styles.muted}>{stale_reason ?? 'The source’s fields changed.'}</span>
      </div>
    )
  }

  return (
    <div className={syncStyles.statusCell}>
      <Badge variant="success" dot>
        Confirmed
      </Badge>
      <span className={styles.muted}>
        {confirmed_by ? `by ${confirmed_by}` : ''} {when(confirmed_at)}
      </span>
    </div>
  )
}

/** Connected systems — the configured connectors and their status. */
function ConnectorsPanel({ canRun }: { canRun: boolean }) {
  const { data, isLoading, error } = useSyncConnectors()
  const trigger = useTriggerSync()
  const setEnabled = useSetConnectorEnabled()
  const [mapping, setMapping] = useState<SyncConnector | null>(null)

  if (isSyncUnavailable(error)) return <SyncPending what="Connected systems" />
  if (isLoading) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading connected systems" />
      </div>
    )
  }
  if (error) return <p className={styles.muted}>Could not load connected systems. Please try again.</p>

  const connectors = data ?? []

  const columns: Column<SyncConnector>[] = [
    { key: 'name', header: 'System', render: (c) => <strong>{c.name}</strong> },
    { key: 'source', header: 'Source', render: (c) => <Badge variant="neutral" mono>{c.source}</Badge> },
    { key: 'mda', header: 'Owner MDA', render: (c) => c.owner_mda?.name ?? '—' },
    {
      key: 'enabled',
      header: 'Status',
      render: (c) => (
        <Badge variant={c.enabled ? 'success' : 'neutral'} dot>
          {c.enabled ? 'enabled' : 'disabled'}
        </Badge>
      ),
    },
    {
      key: 'mapping',
      header: 'Column mapping',
      render: (c) => <MappingStatusCell connector={c} />,
    },
    { key: 'policy', header: 'Conflict policy', render: (c) => sentenceCase(c.conflict_policy) },
    { key: 'schedule', header: 'Schedule', render: (c) => c.schedule ?? 'manual only' },
    { key: 'last', header: 'Last run', render: (c) => when(c.last_run_at) },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (c) =>
        canRun ? (
          <div className={styles.rowActions}>
            <Button size="sm" variant="tertiary" leftIcon={Columns3} onClick={() => setMapping(c)}>
              {c.mapping.status === 'never_configured' ? 'Map columns' : 'Review mapping'}
            </Button>
            <Button
              size="sm"
              variant="secondary"
              leftIcon={c.enabled ? PowerOff : Power}
              loading={setEnabled.isPending}
              // Enabling is refused server-side while the mapping is unconfirmed or
              // stale; disabling is always allowed, so an administrator is never
              // trapped with a feed they cannot stop.
              disabled={!c.enabled && !c.mapping.can_enable}
              onClick={() => setEnabled.mutate({ id: c.id, enabled: !c.enabled })}
            >
              {c.enabled ? 'Disable' : 'Enable'}
            </Button>
            <Button
              size="sm"
              variant="secondary"
              leftIcon={RefreshCw}
              disabled={!c.enabled || c.mapping.status !== 'confirmed' || trigger.isPending}
              onClick={() => trigger.mutate(c.id)}
            >
              Sync now
            </Button>
          </div>
        ) : null,
    },
  ]

  return (
    <div className={styles.page}>
      <Card flush>
        <DataTable rows={connectors} columns={columns} getRowId={(c) => c.id} caption="Connected systems" />
      </Card>
      <p className={styles.footnote}>
        Manual synchronization is queued and idempotent. Re-running a connector never double-inserts a record.
        A connector syncs only once its NIN, BVN, name and phone columns have been confirmed, and holds again
        if the source’s fields change.
      </p>

      {/* Keyed per connector so one connector's answers never carry into another's. */}
      {mapping !== null && (
        <ConnectorMappingModal key={mapping.id} connector={mapping} onClose={() => setMapping(null)} />
      )}
    </div>
  )
}

/** Synchronization status + integration history — the run log. */
function RunsPanel() {
  const { data, isLoading, error } = useSyncRuns()

  if (isSyncUnavailable(error)) return <SyncPending what="Synchronization history" />
  if (isLoading) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading synchronization history" />
      </div>
    )
  }
  if (error) return <p className={styles.muted}>Could not load synchronization history. Please try again.</p>

  const runs = data?.items ?? []
  const succeeded = runs.filter((r) => r.status === 'completed').length
  const failed = runs.filter((r) => r.status === 'failed').length

  const columns: Column<SyncRun>[] = [
    { key: 'started', header: 'Started', render: (r) => when(r.started_at ?? r.created_at) },
    {
      key: 'source',
      header: 'Source',
      render: (r) =>
        r.source ? (
          <Badge variant="neutral" mono>
            {r.source}
          </Badge>
        ) : (
          '—'
        ),
    },
    { key: 'trigger', header: 'Trigger', render: (r) => sentenceCase(r.trigger) },
    {
      key: 'status',
      header: 'Status',
      render: (r) => (
        <div className={styles.stack2}>
          <Badge variant={statusVariant(r.status)} dot>
            {r.status}
          </Badge>
          {r.error ? <span className={styles.muted}>{r.error}</span> : null}
        </div>
      ),
    },
    { key: 'fetched', header: 'Fetched', align: 'right', render: (r) => num(r.summary.fetched) },
    { key: 'created', header: 'Created', align: 'right', render: (r) => num(r.summary.created) },
    { key: 'updated', header: 'Updated', align: 'right', render: (r) => num(r.summary.updated) },
    { key: 'flagged', header: 'Flagged', align: 'right', render: (r) => num(r.summary.flagged) },
    { key: 'errors', header: 'Errors', align: 'right', render: (r) => num(r.summary.errors) },
  ]

  return (
    <div className={styles.page}>
      <div className={styles.figureGrid}>
        <div className={styles.figure}>
          <span className={styles.figureLabel}>Runs shown</span>
          <span className={styles.figureValue}>{num(runs.length)}</span>
        </div>
        <div className={styles.figure}>
          <span className={styles.figureLabel}>Succeeded</span>
          <span className={styles.figureValue}>{num(succeeded)}</span>
        </div>
        <div className={styles.figure}>
          <span className={styles.figureLabel}>Failed</span>
          <span className={styles.figureValue}>{num(failed)}</span>
        </div>
      </div>

      <Card flush>
        <DataTable rows={runs} columns={columns} getRowId={(r) => r.id} caption="Synchronization history" />
      </Card>

      <p className={styles.footnote}>
        Every synced record runs the SAME validation, duplicate and ownership pipeline as a bulk import
      </p>
    </div>
  )
}

/**
 * Integrations (console section 8) — connected systems, synchronization status and
 * history, manual synchronization, and import logs.
 *
 * Phase 7 presence is decided at RUNTIME: the sync panels compose `/sync/*` when the
 * engine answers, and fall back to an explicit "available when synchronization is
 * enabled" state when it does not. No sync data is ever fabricated and no parallel sync
 * engine exists here. Import logs reuse the Phase 2 import history immediately.
 */
export function AdminIntegrationsPage() {
  const { hasPermission } = useAuth()
  const canViewSync = hasPermission('sync.view')
  const canRunSync = hasPermission('sync.run')

  const syncTab = (content: React.ReactNode, what: string) =>
    canViewSync ? content : <SyncPending what={what} />

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Integrations</h1>
        <p className={styles.lead}>
          Systems feeding the registry: connectors and their synchronization runs, plus the bulk-import history. Every
          inbound record passes the same validation, duplicate and ownership checks as a manual import.
        </p>
      </header>

      <Tabs
        items={[
          {
            id: 'systems',
            label: 'Connected systems',
            content: syncTab(<ConnectorsPanel canRun={canRunSync} />, 'Connected systems'),
          },
          {
            id: 'history',
            label: 'Sync status & history',
            content: syncTab(<RunsPanel />, 'Synchronization history'),
          },
          { id: 'imports', label: 'Import logs', content: <ImportListPage readOnly embedded /> },
        ]}
      />
    </div>
  )
}
