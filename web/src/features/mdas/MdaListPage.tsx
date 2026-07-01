import { useState } from 'react'
import { Ban, CheckCircle2, Pencil, Plus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Menu } from '@/components/Menu/Menu'
import type { MenuAction } from '@/components/Menu/Menu'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ApiError } from '@/types/api'
import { MdaFormModal } from './MdaFormModal'
import { useMdaStatus, useMdas } from './hooks'
import type { Mda } from './types'
import layout from '@/features/shared/formLayout.module.css'

export function MdaListPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('mda.view')
  const canCreate = hasPermission('mda.create')
  const canEdit = hasPermission('mda.edit')

  const { data: mdas = [], isLoading, error } = useMdas(canView)
  const statusMutation = useMdaStatus()

  const [formState, setFormState] = useState<{ open: boolean; mda: Mda | null }>({ open: false, mda: null })
  const [confirm, setConfirm] = useState<{ mda: Mda; action: 'activate' | 'deactivate' } | null>(null)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view MDAs.</p>
      </Card>
    )
  }

  const columns: Column<Mda>[] = [
    { key: 'name', header: 'Name', sortable: false, render: (m) => m.name },
    { key: 'type', header: 'Type', render: (m) => <span style={{ textTransform: 'capitalize' }}>{m.type}</span> },
    {
      key: 'status',
      header: 'Status',
      render: (m) => (
        <Badge variant={statusVariant(m.status)} dot>
          {m.status}
        </Badge>
      ),
    },
    { key: 'contact', header: 'Contact', render: (m) => m.contact_email ?? <span className={layout.cellSub}>—</span> },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (m) => {
        if (!canEdit) return null
        const actions: MenuAction[] = [
          { label: 'Edit', icon: Pencil, onSelect: () => setFormState({ open: true, mda: m }) },
          m.status === 'active'
            ? { label: 'Deactivate', icon: Ban, danger: true, onSelect: () => setConfirm({ mda: m, action: 'deactivate' }) }
            : { label: 'Activate', icon: CheckCircle2, onSelect: () => setConfirm({ mda: m, action: 'activate' }) },
        ]
        return <Menu label={`Actions for ${m.name}`} actions={actions} />
      },
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">02 · Administration</span>
          <h1 className="t-h1">MDAs</h1>
        </div>
        {canCreate && (
          <Button leftIcon={Plus} onClick={() => setFormState({ open: true, mda: null })}>
            Create MDA
          </Button>
        )}
      </div>

      {error instanceof ApiError && error.status === 403 ? (
        <Card>
          <p className={layout.forbidden}>You do not have permission to view MDAs.</p>
        </Card>
      ) : (
        <DataTable
          caption="MDAs"
          columns={columns}
          rows={mdas}
          getRowId={(m) => m.id}
          loading={isLoading}
          emptyTitle="No MDAs yet"
          emptyAction={
            canCreate ? (
              <Button size="sm" leftIcon={Plus} onClick={() => setFormState({ open: true, mda: null })}>
                Create MDA
              </Button>
            ) : undefined
          }
        />
      )}

      <MdaFormModal
        open={formState.open}
        mda={formState.mda}
        onClose={() => setFormState({ open: false, mda: null })}
      />

      <ConfirmDialog
        open={confirm !== null}
        danger={confirm?.action === 'deactivate'}
        title={confirm?.action === 'deactivate' ? 'Deactivate MDA?' : 'Activate MDA?'}
        confirmLabel={confirm?.action === 'deactivate' ? 'Deactivate' : 'Activate'}
        loading={statusMutation.isPending}
        onCancel={() => setConfirm(null)}
        onConfirm={async () => {
          if (!confirm) return
          await statusMutation.mutateAsync({ id: confirm.mda.id, action: confirm.action })
          setConfirm(null)
        }}
      >
        {confirm?.action === 'deactivate' ? (
          <>
            This will deactivate <strong>{confirm?.mda.name}</strong> and hide it from new assignments.
            You can reactivate it later.
          </>
        ) : (
          <>
            This will reactivate <strong>{confirm?.mda.name}</strong>.
          </>
        )}
      </ConfirmDialog>
    </div>
  )
}
