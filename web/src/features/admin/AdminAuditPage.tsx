import { useState } from 'react'
import { Download, Search, ShieldCheck } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { adminApi } from './api'
import { useAuditLogs } from './hooks'
import type { AuditEntry, AuditFilters } from './types'
import styles from './admin.module.css'

const CATEGORY_OPTIONS = [
  { value: 'security', label: 'Security & sign-in' },
  { value: 'permission', label: 'Permission changes' },
  { value: 'service_request', label: 'Request-to-serve' },
  { value: 'data_access', label: 'Data access & export' },
  { value: 'activity', label: 'User activity' },
]

const CATEGORY_VARIANT: Record<string, 'danger' | 'warning' | 'info' | 'success' | 'neutral'> = {
  security: 'danger',
  permission: 'warning',
  service_request: 'info',
  data_access: 'success',
  activity: 'neutral',
}

const humanize = (s: string): string => {
  const t = s.replace(/[._]/g, ' ')
  return t.charAt(0).toUpperCase() + t.slice(1)
}

function when(iso: string | null): string {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

/**
 * Audit &amp; Security (console section 7) — READ-ONLY review of the immutable,
 * tamper-evident audit log (FR-AUD-01). It filters and exports the EXISTING log; there
 * is no second logging path and nothing here writes an entry except the export itself,
 * which is audited through the same path as every other export.
 *
 * PII SAFETY: the table shows the audit ENVELOPE (who, what, when, from where) plus the
 * NAMES of the fields an action touched. Recorded before/after values never reach the
 * client — they stay in the tamper-evident log for forensic access.
 */
export function AdminAuditPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const canExport = hasPermission('reporting.export')

  const [filters, setFilters] = useState<AuditFilters>({ page: 1 })
  const [search, setSearch] = useState('')
  const [exporting, setExporting] = useState(false)

  const { data, isLoading } = useAuditLogs(filters, canView)

  const set = <K extends keyof AuditFilters>(key: K, value: AuditFilters[K]) =>
    setFilters((f) => ({ ...f, [key]: value === '' ? undefined : value, page: 1 }))

  async function runExport() {
    setExporting(true)
    try {
      await adminApi.exportAuditLogs('csv', filters)
    } finally {
      setExporting(false)
    }
  }

  const columns: Column<AuditEntry>[] = [
    { key: 'at', header: 'When', render: (e) => when(e.at) },
    { key: 'action', header: 'Action', render: (e) => <strong>{humanize(e.action)}</strong> },
    {
      key: 'category',
      header: 'Category',
      render: (e) => <Badge variant={CATEGORY_VARIANT[e.category] ?? 'neutral'}>{humanize(e.category)}</Badge>,
    },
    {
      key: 'actor',
      header: 'Actor',
      render: (e) => (
        <>
          {e.actor}
          {e.actor_mda && <span className={styles.activityMeta}> · {e.actor_mda}</span>}
        </>
      ),
    },
    { key: 'entity', header: 'Entity', render: (e) => e.entity_type ?? '—' },
    {
      key: 'fields',
      header: 'Changed fields',
      render: (e) =>
        e.changed_fields.length === 0 ? (
          <span className={styles.muted}>—</span>
        ) : (
          <span className={styles.activityMeta}>{e.changed_fields.join(', ')}</span>
        ),
    },
    { key: 'ip', header: 'IP', render: (e) => e.ip_address ?? '—' },
    { key: 'chain', header: 'Chain #', align: 'right', render: (e) => e.chain_position ?? '—' },
  ]

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to review the audit log.</p>
      </Card>
    )
  }

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Audit &amp; Security</h1>
        <p className={styles.lead}>
          The immutable, hash-chained record of who did what, when and from where — sign-in and security events,
          permission changes, request-to-serve decisions and data access.
        </p>
      </header>

      <Card>
        <div className={styles.filterBar}>
          <SelectField
            label="Category"
            placeholder="All categories"
            options={CATEGORY_OPTIONS}
            value={filters.category ?? ''}
            onChange={(e) => set('category', e.target.value)}
          />
          <TextField
            label="From"
            type="date"
            value={filters.from ?? ''}
            onChange={(e) => set('from', e.target.value)}
          />
          <TextField label="To" type="date" value={filters.to ?? ''} onChange={(e) => set('to', e.target.value)} />
          <TextField
            label="Search action or entity"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            onKeyDown={(e) => e.key === 'Enter' && set('q', search)}
          />
          <div className={styles.filterActions}>
            <Button variant="secondary" leftIcon={Search} onClick={() => set('q', search)}>
              Search
            </Button>
            {canExport && (
              <Button variant="secondary" leftIcon={Download} loading={exporting} onClick={runExport}>
                Export CSV
              </Button>
            )}
          </div>
        </div>
      </Card>

      <Card flush>
        <DataTable
          rows={data?.items ?? []}
          columns={columns}
          getRowId={(e) => e.id}
          getRowLabel={(e) => `${humanize(e.action)} at ${when(e.at)}`}
          loading={isLoading}
          caption="Audit and security events"
          pagination={
            data?.pagination
              ? {
                  page: data.pagination.page,
                  pageCount: data.pagination.total_pages,
                  onPageChange: (page) => setFilters((f) => ({ ...f, page })),
                }
              : undefined
          }
        />
      </Card>

      <p className={styles.footnote}>
        <Icon icon={ShieldCheck} size={13} /> Append-only and hash-chained · the envelope and changed field NAMES are
        shown; recorded values never leave the server
      </p>
    </div>
  )
}
