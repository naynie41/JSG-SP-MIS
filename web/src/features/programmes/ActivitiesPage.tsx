import { useMemo, useState } from 'react'
import { Link } from 'react-router-dom'
import { Archive, Pencil, Plus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Menu } from '@/components/Menu/Menu'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { titleCase } from '@/features/registry/constants'
import { ActivityFormModal } from './ActivityFormModal'
import { useAllActivities, useArchiveActivity, useProgrammeCatalog } from './hooks'
import type { Activity } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

/**
 * The MDA's activities across the catalog (PRD §10, FR-PRG-02). Creating an activity
 * begins by picking a catalog programme from a dropdown; the programme shows as a
 * read-only catalog label on each row — MDAs never edit the catalog itself.
 */
export function ActivitiesPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('activity.view')
  const canManage = hasPermission('activity.create')

  const activities = useAllActivities(canView)
  const catalog = useProgrammeCatalog(canView)
  const archive = useArchiveActivity()

  const [form, setForm] = useState<{ open: boolean; activity: Activity | null }>({ open: false, activity: null })

  const programmeName = useMemo(() => {
    const map = new Map((catalog.data?.items ?? []).map((p) => [p.id, p.name]))
    return (id: string) => map.get(id) ?? '—'
  }, [catalog.data])

  if (!canView) {
    return <Card><p className={layout.forbidden}>You do not have permission to view activities.</p></Card>
  }

  const columns: Column<Activity>[] = [
    { key: 'name', header: 'Activity', render: (a) => <Link to={`/activities/${a.id}`}>{a.name}</Link> },
    // The catalog programme is a read-only label — MDAs run it, they don't edit it.
    { key: 'programme', header: 'Programme', render: (a) => <Badge variant="neutral">{programmeName(a.programme_id)}</Badge> },
    { key: 'lga', header: 'LGA', render: (a) => (a.lga ? titleCase(a.lga) : '—') },
    { key: 'budget', header: 'Budget', align: 'right', render: (a) => <span className={styles.mono}>{formatNaira(a.budget_amount)}</span> },
    { key: 'status', header: 'Status', render: (a) => <Badge variant={statusVariant(`programme.${a.status === 'completed' ? 'closed' : a.status}`)} dot>{a.status}</Badge> },
    {
      key: 'actions',
      header: '',
      render: (a) =>
        canManage ? (
          <Menu
            label={`Actions for ${a.name}`}
            actions={[
              { label: 'Edit', icon: Pencil, onSelect: () => setForm({ open: true, activity: a }) },
              { label: 'Archive', icon: Archive, danger: true, onSelect: () => archive.mutate(a.id) },
            ]}
          />
        ) : null,
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">Programmes</span>
          <h1 className="t-h1">Activities</h1>
        </div>
        {canManage && (
          <Button leftIcon={Plus} onClick={() => setForm({ open: true, activity: null })}>
            New activity
          </Button>
        )}
      </div>

      <DataTable
        caption="Activities"
        columns={columns}
        rows={activities.data?.items ?? []}
        getRowId={(a) => a.id}
        loading={activities.isLoading}
        emptyTitle="No activities yet"
        emptyAction={canManage ? <Button size="sm" leftIcon={Plus} onClick={() => setForm({ open: true, activity: null })}>New activity</Button> : undefined}
      />

      {canManage && (
        <ActivityFormModal
          open={form.open}
          onClose={() => setForm({ open: false, activity: null })}
          activity={form.activity}
        />
      )}
    </div>
  )
}
