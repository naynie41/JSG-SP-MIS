import { useState } from 'react'
import { Link } from 'react-router-dom'
import { Trash2 } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Menu } from '@/components/Menu/Menu'
import type { MenuAction } from '@/components/Menu/Menu'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { useAuth } from '@/lib/auth/AuthProvider'
import { titleCase } from './constants'
import { useDeleteHousehold, useHouseholds } from './hooks'
import type { Household } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

export function HouseholdListPage() {
  const { hasPermission, user } = useAuth()
  const canView = hasPermission('household.view')
  const canEdit = hasPermission('household.edit')

  const [page, setPage] = useState(1)
  const [toDelete, setToDelete] = useState<Household | null>(null)

  const { data, isLoading } = useHouseholds(page, canView)
  const deleteHousehold = useDeleteHousehold()

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view households.</p>
      </Card>
    )
  }

  const rows = data?.items ?? []
  const isOwner = (h: Household) => user?.mda?.id != null && user.mda.id === h.owner_mda_id

  const columns: Column<Household>[] = [
    {
      key: 'id',
      header: 'Household',
      render: (h) => (
        <div className={styles.cellStack}>
          <Link to={`/households/${h.id}`} className={styles.mono}>
            {h.id.slice(0, 8)}…
          </Link>
          <span className={styles.cellSub}>{h.head_beneficiary_id ? 'Head assigned' : 'No head yet'}</span>
        </div>
      ),
    },
    {
      key: 'location',
      header: 'LGA / Ward',
      render: (h) => `${h.lga ? titleCase(h.lga) : '—'} · ${h.ward ?? '—'}`,
    },
    { key: 'members', header: 'Members', align: 'right', render: (h) => h.members.length },
    { key: 'registered', header: 'Registered', render: (h) => <span className={styles.mono}>{h.registration_date}</span> },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (h) => {
        if (!canEdit || !isOwner(h)) return null
        const actions: MenuAction[] = [{ label: 'Delete', icon: Trash2, danger: true, onSelect: () => setToDelete(h) }]
        return <Menu label="Household actions" actions={actions} />
      },
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Households</h1>
        </div>
      </div>

      <DataTable
        caption="Households"
        columns={columns}
        rows={rows}
        getRowId={(h) => h.id}
        loading={isLoading}
        emptyTitle="No households yet — households are formed when a source groups its records"
        pagination={{ page, pageCount: data?.pagination?.total_pages ?? 1, onPageChange: setPage }}
      />

      <ConfirmDialog
        open={toDelete !== null}
        danger
        title="Delete household?"
        confirmLabel="Delete"
        loading={deleteHousehold.isPending}
        onCancel={() => setToDelete(null)}
        onConfirm={async () => {
          if (!toDelete) return
          await deleteHousehold.mutateAsync(toDelete.id)
          setToDelete(null)
        }}
      >
        This will delete the household. Membership history is retained for audit.
      </ConfirmDialog>
    </div>
  )
}
