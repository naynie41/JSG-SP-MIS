import { Link } from 'react-router-dom'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
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
          caption="Catalog usage across MDAs"
        />
      </Card>

      <p className={styles.footnote}>
        One global programme, many MDAs — each running it through its own activity · budget and delivery live on the
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
 *    through `/programmes` and the existing `ProgrammePolicy`. Writes stay restricted to
 *    catalog administrators — an MDA can never create a programme (CLAUDE.md §10).
 *  - **Usage across MDAs** reports uptake from the same endpoint.
 *
 * Programmes remain GLOBAL and unowned; this section adds no second catalog and no
 * parallel lifecycle.
 */
export function AdminCatalogPage() {
  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Programme Catalog</h1>
        <p className={styles.lead}>
          The global catalog of social-protection programme types — categories, standard eligibility and status — plus
          how widely each is run across MDAs. Programmes are unowned; MDAs deliver them through their own activities.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'catalog', label: 'Catalog', content: <ProgrammeListPage embedded /> },
          { id: 'usage', label: 'Usage across MDAs', content: <UsagePanel /> },
        ]}
      />
    </div>
  )
}
