import { useState } from 'react'
import { Plus, Trash2 } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { useAuth } from '@/lib/auth/AuthProvider'
import { GrantFormModal } from './GrantFormModal'
import { useGrants, useRevokeGrant } from './hooks'
import type { AccessGrant } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './access.module.css'

function expiryLabel(grant: AccessGrant): string {
  if (grant.expires_at === null) return 'No expiry'
  return new Date(grant.expires_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
}

/**
 * Cross-MDA access grants (FR-UAM-03, FR-DSH-01): who can read another MDA's scoped
 * data, why, and until when. Admins grant and revoke; every change is audited
 * server-side. Distinct from a beneficiary Service Request (which is per-beneficiary).
 */
export function GrantsPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('mda-access.view')
  const canGrant = hasPermission('mda-access.create')
  const canRevoke = hasPermission('mda-access.edit')

  const { data: grants = [], isLoading } = useGrants(canView)
  const revoke = useRevokeGrant()

  const [formOpen, setFormOpen] = useState(false)
  const [confirm, setConfirm] = useState<AccessGrant | null>(null)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view cross-MDA access.</p>
      </Card>
    )
  }

  const columns: Column<AccessGrant>[] = [
    {
      key: 'user',
      header: 'User',
      render: (g) => (
        <div>
          <div>{g.user?.name ?? '—'}</div>
          {g.user && <div className={styles.subEmail}>{g.user.email}</div>}
        </div>
      ),
    },
    { key: 'mda', header: 'Can access', render: (g) => g.mda?.name ?? '—' },
    { key: 'reason', header: 'Reason', render: (g) => g.reason ?? <span className={styles.note}>—</span> },
    { key: 'expires', header: 'Expires', render: (g) => <span className={styles.mono}>{expiryLabel(g)}</span> },
    {
      key: 'status',
      header: 'Status',
      render: (g) => (g.active ? <Badge variant="success" dot>Active</Badge> : <Badge variant="neutral" dot>Expired</Badge>),
    },
    { key: 'granted_by', header: 'Granted by', render: (g) => g.granted_by ?? <span className={styles.note}>—</span> },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (g) =>
        canRevoke ? (
          <Button size="sm" variant="tertiary" leftIcon={Trash2} onClick={() => setConfirm(g)}>
            Revoke
          </Button>
        ) : null,
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">02 · Administration</span>
          <h1 className="t-h1">Cross-MDA access</h1>
        </div>
        {canGrant && (
          <Button leftIcon={Plus} onClick={() => setFormOpen(true)}>
            Grant access
          </Button>
        )}
      </div>

      <DataTable
        caption="Cross-MDA access grants"
        columns={columns}
        rows={grants}
        getRowId={(g) => g.id}
        loading={isLoading}
        emptyTitle="No cross-MDA grants"
        emptyAction={
          canGrant ? (
            <Button size="sm" leftIcon={Plus} onClick={() => setFormOpen(true)}>
              Grant access
            </Button>
          ) : undefined
        }
      />

      {canGrant && <GrantFormModal open={formOpen} onClose={() => setFormOpen(false)} />}

      <ConfirmDialog
        open={confirm !== null}
        danger
        title="Revoke cross-MDA access?"
        confirmLabel="Revoke"
        loading={revoke.isPending}
        onCancel={() => setConfirm(null)}
        onConfirm={async () => {
          if (!confirm) return
          await revoke.mutateAsync(confirm.id)
          setConfirm(null)
        }}
      >
        {confirm?.user && confirm?.mda ? (
          <>
            This removes <strong>{confirm.user.name}</strong>'s access to <strong>{confirm.mda.name}</strong>. They will
            immediately stop seeing that MDA's scoped data.
          </>
        ) : (
          <>This removes the grant.</>
        )}
      </ConfirmDialog>
    </div>
  )
}
