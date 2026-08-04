import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { statusVariant } from '@/components/Badge/statusVariant'
import { MdaListPage } from '@/features/mdas/MdaListPage'
import { useAdminOrganizations } from './hooks'
import type { OrganizationRow, PartnerOrganization } from './types'
import styles from './admin.module.css'

const num = (n: number): string => n.toLocaleString()

/**
 * Allocation &amp; delivery per organization — a READ-ONLY roll-up over existing data
 * (Phase 1 users, Phase 4 activities). Organizations themselves are created, edited and
 * activated on the Organizations tab, through the existing `/mdas` endpoints.
 */
function AllocationPanel() {
  const { data, isLoading } = useAdminOrganizations()

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading organizations" />
      </div>
    )
  }

  const columns: Column<OrganizationRow>[] = [
    { key: 'name', header: 'Organization', render: (o) => <strong>{o.name}</strong> },
    { key: 'type', header: 'Type', render: (o) => <span style={{ textTransform: 'capitalize' }}>{o.type}</span> },
    {
      key: 'status',
      header: 'Status',
      render: (o) => (
        <Badge variant={statusVariant(o.status)} dot>
          {o.status}
        </Badge>
      ),
    },
    { key: 'users', header: 'Users', align: 'right', render: (o) => num(o.users_total) },
    { key: 'admins', header: 'MDA admins', align: 'right', render: (o) => num(o.mda_admins) },
    {
      key: 'activities',
      header: 'Activities',
      align: 'right',
      render: (o) => (
        <>
          {num(o.activities_active)}
          <span className={styles.activityMeta}> of {num(o.activities_total)}</span>
        </>
      ),
    },
  ]

  const t = data.totals
  const figures = [
    { label: 'Organizations', value: num(t.mdas), hint: `${num(t.mdas_active)} active` },
    { label: 'Users allocated', value: num(t.users_allocated), hint: 'assigned to an MDA' },
    { label: 'Platform accounts', value: num(t.users_unallocated), hint: 'no MDA (exec, partner, admin)' },
  ]

  return (
    <div className={styles.page}>
      <div className={styles.figureGrid}>
        {figures.map((f) => (
          <div key={f.label} className={styles.figure}>
            <span className={styles.figureLabel}>{f.label}</span>
            <span className={styles.figureValue}>{f.value}</span>
            <span className={styles.figureHint}>{f.hint}</span>
          </div>
        ))}
      </div>

      <Card flush>
        <DataTable
          rows={data.mdas}
          columns={columns}
          getRowId={(o) => o.id}
          caption="User allocation and activities by organization"
        />
      </Card>

      <p className={styles.footnote}>
        Active activities shown against the total · counts only, from the existing user and activity records
      </p>
    </div>
  )
}

/**
 * Development Partners and the delivery they FUND (`activities.funding_partner_id`).
 * Partner accounts are provisioned as users under User &amp; Access — this view reports
 * their footprint rather than duplicating account management.
 */
function PartnersPanel() {
  const { data, isLoading } = useAdminOrganizations()

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading partners" />
      </div>
    )
  }

  const columns: Column<PartnerOrganization>[] = [
    {
      key: 'name',
      header: 'Development partner',
      render: (p) => (
        <>
          <strong>{p.name}</strong>
          <span className={styles.activityMeta}> · {p.email}</span>
        </>
      ),
    },
    {
      key: 'status',
      header: 'Status',
      render: (p) => (
        <Badge variant={statusVariant(p.status)} dot>
          {p.status}
        </Badge>
      ),
    },
    { key: 'programmes', header: 'Funded programmes', align: 'right', render: (p) => num(p.funded_programmes) },
    { key: 'activities', header: 'Funded activities', align: 'right', render: (p) => num(p.funded_activities) },
    { key: 'mdas', header: 'Implementing MDAs', align: 'right', render: (p) => num(p.implementing_mdas) },
  ]

  return (
    <div className={styles.page}>
      <Card flush>
        <DataTable
          rows={data.partners}
          columns={columns}
          getRowId={(p) => p.id}
          caption="Development partners and the delivery they fund"
        />
      </Card>
      <p className={styles.footnote}>
        Partner accounts are provisioned under User &amp; Access · funding attribution comes from the activities they fund
      </p>
    </div>
  )
}

/**
 * Organization (console section 3) — COMPOSES the existing modules:
 *
 *  - **Organizations** renders the existing {@link MdaListPage} (create, edit, activate,
 *    deactivate) — the same component, endpoints and policies, so there is no second
 *    organization store or lifecycle.
 *  - **Allocation &amp; activity** and **Development partners** are read-only roll-ups
 *    over data that already exists (Phase 1 users, Phase 4 activities, Phase 6P funding
 *    attribution), answering "who works here and what does it run".
 */
export function AdminOrganizationPage() {
  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Organization</h1>
        <p className={styles.lead}>
          MDAs and development partners, their people and the delivery they run — backed by the existing organization,
          user and activity records.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'mdas', label: 'Organizations', content: <MdaListPage /> },
          { id: 'allocation', label: 'Allocation & activity', content: <AllocationPanel /> },
          { id: 'partners', label: 'Development partners', content: <PartnersPanel /> },
        ]}
      />
    </div>
  )
}
