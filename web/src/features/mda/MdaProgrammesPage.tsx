import { Link, useNavigate } from 'react-router-dom'
import { ClipboardList, Eye, LibraryBig } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useProgrammes } from '@/features/programmes/hooks'
import type { Programme } from '@/features/programmes/types'
import { titleCase } from './format'
import styles from './mda.module.css'

/**
 * Programmes — the catalog programmes THIS MDA participates in.
 *
 * "Participation" means the MDA has activities under the programme; it is a view over
 * the global catalog, never ownership. The catalog is centrally owned and read-only
 * here — an MDA can neither create nor edit a programme (CLAUDE.md §10), so this page
 * offers no such control and the server refuses it regardless.
 *
 * The list is filtered SERVER-side (`filter[participating]`): the activity counts
 * beside each row are already MDA-scoped by `MdaScope`, but filtering on them in the
 * client would drop participating programmes that fall beyond the first page.
 */
export function MdaProgrammesPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('programme.view')
  const navigate = useNavigate()

  const { data, isLoading } = useProgrammes({ participating: true, per_page: 100 }, canView)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view programmes.</p>
      </Card>
    )
  }

  const programmes = data?.items ?? []

  const columns: Column<Programme>[] = [
    { key: 'name', header: 'Programme', render: (p) => <Link to={`/mda/programmes/${p.id}`}>{p.name}</Link> },
    { key: 'category', header: 'Category', render: (p) => titleCase(p.benefit_category) },
    { key: 'type', header: 'Type', render: (p) => titleCase(p.type) },
    {
      key: 'activities',
      header: 'Your activities',
      align: 'right',
      render: (p) => (p.activities_count ?? 0).toLocaleString(),
    },
    {
      key: 'status',
      header: 'Status',
      render: (p) => (
        <Badge variant={statusVariant(`programme.${p.status}`)} dot>
          {titleCase(p.status)}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (p) => (
        <Button size="sm" variant="tertiary" leftIcon={Eye} onClick={() => navigate(`/mda/programmes/${p.id}`)}>
          Open
        </Button>
      ),
    },
  ]

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Programmes</h1>
        <p className={styles.lead}>
          The catalogue programmes your MDA delivers, meaning those you run at least one activity under. Open one to see
          your activities, their budgets and targets, and to create another.
        </p>
      </header>

      <Card flush>
        <DataTable
          caption="Programmes your MDA participates in"
          rows={programmes}
          columns={columns}
          getRowId={(p) => p.id}
          getRowLabel={(p) => p.name}
          loading={isLoading}
          
          emptyTitle="No programmes yet"
          
        />
      </Card>

      <section className={styles.section} aria-label="About the catalogue">
        <div className={styles.sectionHead}>
          <Icon icon={LibraryBig} size={16} />
          <h2 className={styles.sectionTitle}>About the catalogue</h2>
        </div>
        <Card>
          <p className={styles.muted}>
            <Icon icon={ClipboardList} size={14} /> Programmes are a shared, state-wide catalogue owned centrally —
            the same programme may be run by several MDAs, each through its own activities. Your MDA selects a
            programme and delivers it; the programme definition itself is maintained by the System Administrator and
            SP Coordination.
          </p>
          <p className={styles.footnote}>
            Budget, funding and targets belong to your activity, never to the shared programme
          </p>
        </Card>
      </section>
    </div>
  )
}
