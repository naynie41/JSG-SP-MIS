import { useMemo, useState } from 'react'
import { ArrowDownWideNarrow, ArrowUpNarrowWide, Clock, Target, UserCheck, Users } from 'lucide-react'
import { Link, useParams } from 'react-router-dom'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { MetricBand } from '@/components/MetricBand/MetricBand'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import type { ServiceRequest } from '@/features/registry/types'
import { useActivity } from './hooks'
import type { ActivityBeneficiary } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

type BeneficiarySortKey = 'name' | 'location' | 'status' | 'enrolled'

/**
 * The orderings offered for the beneficiary list.
 *
 * NIN is deliberately absent: it is masked (`•••••••5052`), so sorting by it would
 * order the list by the mask rather than by anything real.
 */
const BENEFICIARY_SORTS: { key: BeneficiarySortKey; label: string }[] = [
  { key: 'name', label: 'Name' },
  { key: 'location', label: 'LGA / Ward' },
  { key: 'status', label: 'Status' },
  { key: 'enrolled', label: 'Date enrolled' },
]

/** The value a given ordering compares on. */
function sortValue(b: ActivityBeneficiary, key: BeneficiarySortKey): string {
  switch (key) {
    case 'name':
      return b.full_name ?? ''
    case 'location':
      // LGA first, then ward, so one LGA's people stay together.
      return `${b.lga ?? ''} ${b.ward ?? ''}`
    case 'status':
      return b.beneficiary_status ?? ''
    case 'enrolled':
      return b.enrolled_on ?? '' // ISO dates sort correctly as strings
  }
}

/**
 * Activity detail (§10 / DESIGN.md §5.10) — powered by GET /activities/{id}: the
 * catalog programme, target vs actual beneficiary counts, the beneficiaries/interventions
 * recorded under the activity, its import/validation summary, and the request-to-serve
 * items attached to it. Owner-MDA scoped; NIN/BVN masked.
 */
export function ActivityDetailPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission } = useAuth()
  const canView = hasPermission('activity.view')

  const { data: activity, isLoading } = useActivity(id, canView)

  // Name A–Z by default: with hundreds of rows the question is almost always
  // "is this person on the list", and enrolment order cannot answer it.
  const [sort, setSort] = useState<{ key: BeneficiarySortKey; direction: 'asc' | 'desc' }>({
    key: 'name',
    direction: 'asc',
  })

  const sortedBeneficiaries = useMemo(() => {
    const rows = [...(activity?.beneficiaries ?? [])]
    const factor = sort.direction === 'asc' ? 1 : -1

    return rows.sort((a, b) =>
      // localeCompare so accented and mixed-case names order the way a reader expects
      // rather than by code point; numeric so "Ward 2" precedes "Ward 10".
      sortValue(a, sort.key).localeCompare(sortValue(b, sort.key), undefined, {
        sensitivity: 'base',
        numeric: true,
      }) * factor,
    )
  }, [activity?.beneficiaries, sort])

  if (!canView) {
    return <Card><p className={layout.forbidden}>You do not have permission to view activities.</p></Card>
  }
  if (isLoading || !activity) {
    return <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}><Spinner size={24} label="Loading activity" /></div>
  }

  const involves = activity.involves_beneficiaries
  const requests = activity.service_requests
  const pendingCount = requests.filter((r) => r.status === 'pending').length
  const summary = activity.import_summary

  const beneficiaryColumns: Column<ActivityBeneficiary>[] = [
    { key: 'name', header: 'Name', sortable: true, render: (b) => b.full_name ?? '—' },
    { key: 'location', header: 'LGA / Ward', sortable: true, render: (b) => `${b.lga ? titleCase(b.lga) : '—'}${b.ward ? ` · ${b.ward}` : ''}` },
    // NIN is masked, so ordering by it would sort on the mask — not offered.
    { key: 'nin', header: 'NIN', render: (b) => <span className={styles.mono}>{b.nin ?? '—'}</span> },
    { key: 'status', header: 'Status', sortable: true, render: (b) => <Badge variant={statusVariant(`beneficiary.${b.beneficiary_status}`)} dot>{b.beneficiary_status ?? '—'}</Badge> },
    { key: 'enrolled', header: 'Enrolled', align: 'right', sortable: true, render: (b) => <span className={styles.mono}>{b.enrolled_on}</span> },
  ]

  const srColumns: Column<ServiceRequest>[] = [
    { key: 'beneficiary', header: 'Beneficiary', render: (r) => r.beneficiary_name ?? <span className={styles.mono}>#{r.beneficiary_id.slice(0, 8)}</span> },
    { key: 'owner', header: 'Owner MDA', render: (r) => r.owner_mda?.name ?? '—' },
    { key: 'status', header: 'Status', render: (r) => <Badge variant={statusVariant(`service_request.${r.status}`)} dot>{r.status}</Badge> },
    { key: 'raised', header: 'Raised', align: 'right', render: (r) => <span className={styles.mono}>{r.created_at?.slice(0, 10) ?? '—'}</span> },
  ]

  return (
    <div className={styles.stack}>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">Activities</span>
          <h1 className="t-h1">{activity.name}</h1>
          <Link to="/activities" className={styles.note}>← All activities</Link>
        </div>
        <div className={styles.rowActions}>
          <Badge variant="neutral">{activity.programme?.name ?? '—'}</Badge>
          <Badge variant={statusVariant(`programme.${activity.status === 'completed' ? 'closed' : activity.status}`)} dot>{activity.status}</Badge>
        </div>
      </div>

      {involves && (
        <MetricBand
          items={[
            { icon: Target, label: 'Target beneficiaries', value: (activity.counts.target ?? 0).toLocaleString(), tone: 'forest' },
            { icon: Users, label: 'Actual (enrolled)', value: activity.counts.actual.toLocaleString(), tone: 'info' },
            { icon: Clock, label: 'Pending requests', value: activity.counts.pending_service_requests.toLocaleString(), tone: 'warning' },
          ]}
        />
      )}

      <Card title="Details" eyebrow="Activity">
        <dl className={styles.dl}>
          <dt>Programme</dt>
          <dd>{activity.programme?.name ?? '—'}</dd>
          <dt>Areas covered</dt>
          <dd>
            {activity.locations.length === 0 ? (
              '—'
            ) : (
              <ul className={styles.areaList}>
                {activity.locations.map((group) => (
                  <li key={group.lga_id}>
                    <strong>{group.lga_name ?? '—'}</strong>
                    {' · '}
                    {group.whole_lga
                      ? <span className={styles.areaWhole}>whole LGA</span>
                      : group.wards.map((ward) => ward.ward_name).join(', ')}
                  </li>
                ))}
              </ul>
            )}
          </dd>
          {activity.location_description && (
            <>
              <dt>Location detail</dt>
              <dd>{activity.location_description}</dd>
            </>
          )}
          <dt>Budget</dt>
          <dd className={styles.mono}>{formatNaira(activity.budget_amount)}</dd>
          <dt>Funding</dt>
          <dd>{activity.funding_source ?? '—'}</dd>
          <dt>Period</dt>
          <dd>{activity.starts_on ?? '—'} → {activity.ends_on ?? '—'}</dd>
          <dt>Beneficiaries</dt>
          <dd>{involves ? `Yes · target ${activity.counts.target ?? '—'} · actual ${activity.counts.actual}` : 'No'}</dd>
        </dl>
      </Card>

      {involves && (
        <Card
          title="Beneficiaries"
          eyebrow="Interventions"
          // Hundreds of rows otherwise push the import summary and service requests
          // off the page; the count stays in the header when it is folded away.
          collapsible
          actions={<Badge variant="info" dot>{activity.counts.actual} enrolled</Badge>}
        >
          <div className={styles.sortRow}>
            <label className={styles.sortLabel} htmlFor="beneficiary-sort">Sort by</label>
            <select
              id="beneficiary-sort"
              className={styles.sortSelect}
              value={sort.key}
              onChange={(e) => setSort({ key: e.target.value as BeneficiarySortKey, direction: 'asc' })}
            >
              {BENEFICIARY_SORTS.map((option) => (
                <option key={option.key} value={option.key}>{option.label}</option>
              ))}
            </select>
            <Button
              size="sm"
              variant="tertiary"
              leftIcon={sort.direction === 'asc' ? ArrowUpNarrowWide : ArrowDownWideNarrow}
              onClick={() => setSort((s) => ({ ...s, direction: s.direction === 'asc' ? 'desc' : 'asc' }))}
              aria-label={`Sorted ${sort.direction === 'asc' ? 'ascending' : 'descending'} — reverse the order`}
            >
              {sort.direction === 'asc' ? 'Ascending' : 'Descending'}
            </Button>
          </div>
          <DataTable
            caption="Beneficiaries recorded under this activity"
            columns={beneficiaryColumns}
            rows={sortedBeneficiaries}
            getRowId={(b) => b.enrollment_id}
            // Column headers drive the SAME state as the row above, so the two
            // affordances can never disagree about how the table is ordered.
            sort={sort}
            onSortChange={(key) =>
              setSort((s) =>
                s.key === key
                  ? { ...s, direction: s.direction === 'asc' ? 'desc' : 'asc' }
                  : { key: key as BeneficiarySortKey, direction: 'asc' },
              )
            }
            emptyTitle="No beneficiaries recorded under this activity yet"
          />
        </Card>
      )}

      {summary && (
        <Card title="Import summary" eyebrow="Validation">
          <MetricBand
            items={[
              { icon: UserCheck, label: 'Valid rows', value: summary.valid_rows.toLocaleString(), tone: 'success' },
              { icon: Users, label: 'New saved', value: summary.committed_rows.toLocaleString(), tone: 'info' },
              { icon: Clock, label: 'Duplicates served', value: summary.served_rows.toLocaleString(), tone: 'mint' },
              { icon: Target, label: 'Rejected (identity)', value: summary.rejected_rows.toLocaleString(), tone: 'danger' },
            ]}
          />
        </Card>
      )}

      {involves && (
        <Card
          title="Pending service requests"
          eyebrow="Request-to-serve"
          actions={pendingCount > 0 ? <Badge variant="warning" dot>{pendingCount} awaiting approval</Badge> : undefined}
        >
          <p className={styles.note} style={{ marginBottom: 'var(--space-3)' }}>
            Duplicates chosen to serve during this activity's upload raise a request to the beneficiary's owner
            MDA. The intervention is deferred until the owner approves.
          </p>
          <DataTable
            caption="Service requests raised under this activity"
            columns={srColumns}
            rows={requests}
            getRowId={(r) => r.id}
            emptyTitle="No service requests raised under this activity"
          />
        </Card>
      )}
    </div>
  )
}
