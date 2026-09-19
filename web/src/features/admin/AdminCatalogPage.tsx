import { Link } from 'react-router-dom'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ProgrammeApprovalsPanel } from '@/features/programmes/ProgrammeApprovalsPanel'
import { ProgrammeListPage } from '@/features/programmes/ProgrammeListPage'
import { useProgrammes } from '@/features/programmes/hooks'
import type { Programme } from '@/features/programmes/types'
import styles from './admin.module.css'

const num = (n: number | undefined): string => (n ?? 0).toLocaleString()

/**
 * CATALOG USAGE across MDAs — how widely each global programme has been taken up.
 * One catalog programme may be run by many MDAs, each through its own activity (§10),
 * so uptake is the pair (distinct MDAs, activities). Read from the SAME `/programmes`
 * endpoint the catalog list uses; the counts inherit the caller's existing MDA scope.
 */
function UsagePanel() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('programme.view')
  const { data, isLoading } = useProgrammes({ page: 1, per_page: 100 }, canView)

  if (!canView) {
    return <p className={styles.muted}>You do not have permission to view the programme catalog.</p>
  }

  if (isLoading || !data) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading catalog usage" />
      </div>
    )
  }

  const programmes = data.items
  const adopted = programmes.filter((p) => (p.mdas_count ?? 0) > 0)
  const unused = programmes.length - adopted.length
  const totalActivities = programmes.reduce((sum, p) => sum + (p.activities_count ?? 0), 0)

  const columns: Column<Programme>[] = [
    {
      key: 'name',
      header: 'Programme',
      render: (p) => <Link to={`/programmes/${p.id}`}>{p.name}</Link>,
    },
    { key: 'type', header: 'Category', render: (p) => <Badge variant={statusVariant(`type.${p.type}`)}>{p.type}</Badge> },
    { key: 'benefit', header: 'Benefit', render: (p) => p.benefit_category ?? '—' },
    {
      key: 'status',
      header: 'Status',
      render: (p) => (
        <Badge variant={statusVariant(`programme.${p.status}`)} dot>
          {p.status}
        </Badge>
      ),
    },
    {
      key: 'mdas',
      header: 'MDAs running it',
      align: 'right',
      render: (p) =>
        (p.mdas_count ?? 0) === 0 ? <span className={styles.muted}>not adopted</span> : num(p.mdas_count),
    },
    { key: 'activities', header: 'Activities', align: 'right', render: (p) => num(p.activities_count) },
  ]

  const figures = [
    { label: 'Catalog programmes', value: num(programmes.length) },
    { label: 'Adopted by an MDA', value: num(adopted.length), hint: `${num(unused)} not yet adopted` },
    { label: 'Activities referencing the catalog', value: num(totalActivities) },
  ]

  return (
    <div className={styles.page}>
      <div className={styles.figureGrid}>
        {figures.map((f) => (
          <div key={f.label} className={styles.figure}>
            <span className={styles.figureLabel}>{f.label}</span>
            <span className={styles.figureValue}>{f.value}</span>
            {f.hint && <span className={styles.figureHint}>{f.hint}</span>}
          </div>
        ))}
      </div>

      <Card flush>
        <DataTable
          rows={programmes}
          columns={columns}
          getRowId={(p) => p.id}
          caption="Catalog usage across agencies"
        />
      </Card>

      <p className={styles.footnote}>
        One global programme, many MDAs, each running it through its own activity. Budget and delivery live on the
        activity, never on the catalog entry
      </p>
    </div>
  )
}

/**
 * Programme Catalog (console section 4) — COMPOSES the existing catalog module:
 *
 *  - **Catalog** renders the existing {@link ProgrammeListPage}: create/edit with the
 *    programme category (type), benefit category, standard eligibility and status, all
 *    through `/programmes` and the existing `ProgrammePolicy`. Writes to the CENTRAL
 *    catalog stay restricted to catalog administrators (CLAUDE.md §10).
 *  - **Approvals** decides the programmes MDAs have created for themselves. Until one
 *    is decided the MDA can do nothing with it, so the count rides on the tab.
 *  - **Usage across agencies** reports uptake from the same endpoint.
 *
 * The central catalog is still one shared, unowned list; an MDA's own programme is a
 * separate row on the same table, not a second catalog with its own lifecycle.
 */
export function AdminCatalogPage() {
  const { hasPermission } = useAuth()
  // The count is on the tab because a queue nobody opens is a queue that stalls an
  // MDA's whole programme — they cannot deliver anything until it is decided.
  const { data: pending } = useProgrammes({ approval: 'pending', per_page: 100 }, hasPermission('programme.approve'))
  const waiting = pending?.items.length ?? 0
  const pendingLabel = waiting > 0 ? `Approvals (${waiting})` : 'Approvals'

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Programme Catalog</h1>
        <p className={styles.lead}>
          The state catalog of social-protection programme types, with their categories, standard eligibility and
          status, plus how widely each is run across MDAs. Catalog entries are unowned and every MDA delivers them
          through its own activities. An MDA may also create a programme for itself — those wait here for your
          approval, and no other MDA ever sees them.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'catalog', label: 'Catalog', content: <ProgrammeListPage embedded /> },
          { id: 'approvals', label: pendingLabel, content: <ProgrammeApprovalsPanel /> },
          { id: 'usage', label: 'Usage across agencies', content: <UsagePanel /> },
        ]}
      />
    </div>
  )
}
