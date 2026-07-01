import { useState } from 'react'
import { Ban, CheckCircle2, KeyRound, PauseCircle, Pencil, Plus, ShieldOff } from 'lucide-react'
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
import { UserFormModal } from './UserFormModal'
import { useForcePasswordReset, useResetMfa, useUserStatus, useUsers } from './hooks'
import type { ManagedUser } from './types'
import layout from '@/features/shared/formLayout.module.css'

interface ConfirmState {
  title: string
  body: React.ReactNode
  confirmLabel: string
  danger: boolean
  run: () => Promise<unknown>
}

export function UserListPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('user.view')
  const canCreate = hasPermission('user.create')
  const canEdit = hasPermission('user.edit')

  const { data: users = [], isLoading, error } = useUsers(canView)
  const statusMutation = useUserStatus()
  const passwordReset = useForcePasswordReset()
  const resetMfa = useResetMfa()

  const [formState, setFormState] = useState<{ open: boolean; user: ManagedUser | null }>({ open: false, user: null })
  const [confirm, setConfirm] = useState<ConfirmState | null>(null)
  const busy = statusMutation.isPending || passwordReset.isPending || resetMfa.isPending

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view users.</p>
      </Card>
    )
  }

  function buildActions(user: ManagedUser): MenuAction[] {
    const actions: MenuAction[] = [
      { label: 'Edit', icon: Pencil, onSelect: () => setFormState({ open: true, user }) },
    ]

    if (user.status !== 'active') {
      actions.push({
        label: 'Activate',
        icon: CheckCircle2,
        onSelect: () => statusMutation.mutate({ id: user.id, action: 'activate' }),
      })
    }
    if (user.status === 'active') {
      actions.push({
        label: 'Suspend',
        icon: PauseCircle,
        danger: true,
        onSelect: () =>
          setConfirm({
            title: 'Suspend user?',
            confirmLabel: 'Suspend',
            danger: true,
            body: (
              <>
                <strong>{user.name}</strong> will be signed out and unable to sign in until reactivated.
              </>
            ),
            run: () => statusMutation.mutateAsync({ id: user.id, action: 'suspend' }),
          }),
      })
    }
    if (user.status !== 'deactivated') {
      actions.push({
        label: 'Deactivate',
        icon: Ban,
        danger: true,
        onSelect: () =>
          setConfirm({
            title: 'Deactivate user?',
            confirmLabel: 'Deactivate',
            danger: true,
            body: (
              <>
                <strong>{user.name}</strong> will be signed out and deactivated. This can be reversed.
              </>
            ),
            run: () => statusMutation.mutateAsync({ id: user.id, action: 'deactivate' }),
          }),
      })
    }

    actions.push({
      label: 'Force password reset',
      icon: KeyRound,
      onSelect: () =>
        setConfirm({
          title: 'Force password reset?',
          confirmLabel: 'Reset password',
          danger: false,
          body: (
            <>
              <strong>{user.name}</strong> will be signed out and must set a new password.
            </>
          ),
          run: () => passwordReset.mutateAsync(user.id),
        }),
    })

    if (user.mfa_enabled) {
      actions.push({
        label: 'Reset MFA',
        icon: ShieldOff,
        onSelect: () =>
          setConfirm({
            title: 'Reset MFA?',
            confirmLabel: 'Reset MFA',
            danger: false,
            body: (
              <>
                Clears MFA for <strong>{user.name}</strong>. If their role requires MFA they will
                re-enrol at next sign-in.
              </>
            ),
            run: () => resetMfa.mutateAsync(user.id),
          }),
      })
    }

    return actions
  }

  const columns: Column<ManagedUser>[] = [
    {
      key: 'name',
      header: 'Name',
      render: (u) => (
        <span className={layout.cellStack}>
          <span>{u.name}</span>
          <span className={layout.cellSub}>{u.email}</span>
        </span>
      ),
    },
    { key: 'mda', header: 'MDA', render: (u) => u.mda?.name ?? <span className={layout.cellSub}>—</span> },
    { key: 'role', header: 'Role', render: (u) => (u.role ? <Badge variant="dark">{u.role.name}</Badge> : '—') },
    {
      key: 'status',
      header: 'Status',
      render: (u) => (
        <Badge variant={statusVariant(u.status)} dot>
          {u.status}
        </Badge>
      ),
    },
    {
      key: 'mfa',
      header: 'MFA',
      render: (u) =>
        u.mfa_enabled ? <Badge variant="success">On</Badge> : <Badge variant="neutral">Off</Badge>,
    },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (u) => (canEdit ? <Menu label={`Actions for ${u.name}`} actions={buildActions(u)} /> : null),
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">02 · Administration</span>
          <h1 className="t-h1">Users</h1>
        </div>
        {canCreate && (
          <Button leftIcon={Plus} onClick={() => setFormState({ open: true, user: null })}>
            Create user
          </Button>
        )}
      </div>

      {error instanceof ApiError && error.status === 403 ? (
        <Card>
          <p className={layout.forbidden}>You do not have permission to view users.</p>
        </Card>
      ) : (
        <DataTable
          caption="Users"
          columns={columns}
          rows={users}
          getRowId={(u) => u.id}
          loading={isLoading}
          emptyTitle="No users yet"
          emptyAction={
            canCreate ? (
              <Button size="sm" leftIcon={Plus} onClick={() => setFormState({ open: true, user: null })}>
                Create user
              </Button>
            ) : undefined
          }
        />
      )}

      <UserFormModal
        open={formState.open}
        user={formState.user}
        onClose={() => setFormState({ open: false, user: null })}
      />

      <ConfirmDialog
        open={confirm !== null}
        danger={confirm?.danger}
        title={confirm?.title ?? ''}
        confirmLabel={confirm?.confirmLabel}
        loading={busy}
        onCancel={() => setConfirm(null)}
        onConfirm={async () => {
          if (!confirm) return
          await confirm.run()
          setConfirm(null)
        }}
      >
        {confirm?.body}
      </ConfirmDialog>
    </div>
  )
}
