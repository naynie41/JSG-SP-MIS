import { useState } from 'react'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card, KpiPanel } from '@/components/Card/Card'
import { Tabs } from '@/components/Tabs/Tabs'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { Button } from '@/components/Button/Button'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { AGGREGATE_DIMENSION_OPTIONS, BENEFIT_STATUS_LABELS, BENEFIT_TYPE_LABELS } from './constants'
import { useBenefitAggregate, useBenefitFlags, useBenefits, useReviewFlag } from './hooks'
import type { AggregateGroup, Benefit, BenefitFlag } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from '@/features/programmes/programmes.module.css'

function AggregateTab() {
  const [groupBy, setGroupBy] = useState('programme')
  const [from, setFrom] = useState('')
  const [to, setTo] = useState('')
  const { data, isLoading } = useBenefitAggregate({ group_by: groupBy, date_from: from || undefined, date_to: to || undefined })

  const columns: Column<AggregateGroup>[] = [
    { key: 'key', header: 'Group', render: (g) => <span className={styles.mono}>{g.key ?? '—'}</span> },
    { key: 'count', header: 'Deliveries', align: 'right', render: (g) => g.benefit_count },
    { key: 'value', header: 'Total value', align: 'right', render: (g) => <span className={styles.mono}>{formatNaira(g.total_value)}</span> },
  ]

  return (
    <div className={styles.stack}>
      <div className={styles.filters}>
        <SelectField className={styles.filterField} label="Group by" options={AGGREGATE_DIMENSION_OPTIONS} value={groupBy} onChange={(e) => setGroupBy(e.target.value)} />
        <TextField className={styles.filterField} label="From" type="date" value={from} onChange={(e) => setFrom(e.target.value)} />
        <TextField className={styles.filterField} label="To" type="date" value={to} onChange={(e) => setTo(e.target.value)} />
      </div>

      {data && (
        <div className={styles.kpiRow}>
          <KpiPanel label="Total delivered" value={formatNaira(data.totals.total_value)} hint={`${data.totals.benefit_count} deliveries`} />
          <KpiPanel label="Groups" value={data.groups.length} hint={`by ${data.group_by}`} />
        </div>
      )}

      <DataTable caption="Aggregated benefits" columns={columns} rows={data?.groups ?? []} getRowId={(g) => g.key ?? 'none'} loading={isLoading} emptyTitle="No benefits in range" />
    </div>
  )
}

function DeliveriesTab() {
  const [status, setStatus] = useState('')
  const [page, setPage] = useState(1)
  const { data, isLoading } = useBenefits({ page, status: status || undefined })

  const columns: Column<Benefit>[] = [
    { key: 'type', header: 'Type', render: (b) => BENEFIT_TYPE_LABELS[b.benefit_type] ?? b.benefit_type },
    { key: 'date', header: 'Delivered', render: (b) => <span className={styles.mono}>{b.delivery_date}</span> },
    { key: 'ben', header: 'Beneficiary', render: (b) => <span className={styles.mono}>#{b.beneficiary_id.slice(0, 8)}</span> },
    { key: 'value', header: 'Value', align: 'right', render: (b) => <span className={styles.mono}>{formatNaira(b.monetary_value)}</span> },
    { key: 'status', header: 'Status', render: (b) => <Badge variant={statusVariant(`benefit.${b.status}`)} dot>{BENEFIT_STATUS_LABELS[b.status] ?? b.status}</Badge> },
  ]

  return (
    <div className={styles.stack}>
      <div className={styles.filters}>
        <SelectField className={styles.filterField} label="Status" placeholder="All" options={Object.entries(BENEFIT_STATUS_LABELS).map(([value, label]) => ({ value, label }))} value={status} onChange={(e) => { setStatus(e.target.value); setPage(1) }} />
      </div>
      <DataTable
        caption="Deliveries"
        columns={columns}
        rows={data?.items ?? []}
        getRowId={(b) => b.id}
        loading={isLoading}
        emptyTitle="No deliveries recorded"
        pagination={data?.pagination ? { page: data.pagination.page, pageCount: data.pagination.total_pages, onPageChange: setPage } : undefined}
      />
    </div>
  )
}

function FlagsTab() {
  const { hasPermission } = useAuth()
  const canReview = hasPermission('benefit.approve')
  const [status, setStatus] = useState('open')
  const { data, isLoading } = useBenefitFlags({ status: status || undefined })
  const review = useReviewFlag()

  const columns: Column<BenefitFlag>[] = [
    { key: 'ben', header: 'Beneficiary', render: (f) => <span className={styles.mono}>#{f.beneficiary_id.slice(0, 8)}</span> },
    { key: 'type', header: 'Type', render: (f) => f.benefit_type },
    { key: 'reason', header: 'Reason', render: (f) => <span className={styles.note}>{f.reason}</span> },
    { key: 'status', header: 'Status', render: (f) => <Badge variant={statusVariant(`flag.${f.status}`)} dot>{f.status}</Badge> },
    {
      key: 'actions',
      header: '',
      render: (f) =>
        canReview && f.status === 'open' ? (
          <span className={styles.rowActions}>
            <Button size="sm" variant="tertiary" onClick={() => review.mutate({ id: f.id, status: 'dismissed' })}>Dismiss</Button>
            <Button size="sm" variant="danger" onClick={() => review.mutate({ id: f.id, status: 'confirmed' })}>Confirm</Button>
          </span>
        ) : null,
    },
  ]

  return (
    <div className={styles.stack}>
      <div className={styles.filters}>
        <SelectField
          className={styles.filterField}
          label="Status"
          placeholder="All"
          options={[{ value: 'open', label: 'Open' }, { value: 'confirmed', label: 'Confirmed' }, { value: 'dismissed', label: 'Dismissed' }]}
          value={status}
          onChange={(e) => setStatus(e.target.value)}
        />
      </div>
      <DataTable caption="Double-dipping flags" columns={columns} rows={data ?? []} getRowId={(f) => f.id} loading={isLoading} emptyTitle="No flags" />
    </div>
  )
}

export function BenefitLedgerPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('benefit.view')

  if (!canView) {
    return <Card><p className={layout.forbidden}>You do not have permission to view the benefit ledger.</p></Card>
  }

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">04 · Benefits</span>
          <h1 className="t-h1">Benefit ledger</h1>
        </div>
      </div>

      <Tabs
        items={[
          { id: 'aggregate', label: 'Aggregate', content: <AggregateTab /> },
          { id: 'deliveries', label: 'Deliveries', content: <DeliveriesTab /> },
          { id: 'flags', label: 'Double-dipping', content: <FlagsTab /> },
        ]}
      />
    </div>
  )
}
