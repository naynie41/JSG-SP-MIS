import { useState } from 'react'
import { Eye, ShieldCheck } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useAccessRoles, groupByModule } from './hooks'
import type { AccessRole } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './access.module.css'

/**
 * Roles administration (FR-UAM-01/05). The seven predefined roles are system-owned
 * (seeder-defined), so this is a read view: each role's MFA requirement and its full
 * permission bundle, grouped by module. Creating/editing roles is not exposed.
 */
export function RolesPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('role.view')
  const { data: roles = [], isLoading } = useAccessRoles(canView)
  const [detail, setDetail] = useState<AccessRole | null>(null)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view roles.</p>
      </Card>
    )
  }

  const columns: Column<AccessRole>[] = [
    { key: 'name', header: 'Role', render: (r) => <strong>{r.name}</strong> },
    { key: 'key', header: 'Key', render: (r) => <Badge variant="neutral" mono>{r.key}</Badge> },
    {
      key: 'mfa',
      header: 'MFA',
      render: (r) => (r.requires_mfa ? <Badge variant="info" dot>Required</Badge> : <span className={styles.note}>Optional</span>),
    },
    { key: 'count', header: 'Permissions', align: 'right', render: (r) => r.permissions.length },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (r) => (
        <Button size="sm" variant="tertiary" leftIcon={Eye} onClick={() => setDetail(r)}>
          View permissions
        </Button>
      ),
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">02 · Administration</span>
          <h1 className="t-h1">Roles</h1>
        </div>
      </div>

      <DataTable
        caption="Roles"
        columns={columns}
        rows={roles}
        getRowId={(r) => r.id}
        loading={isLoading}
        emptyTitle="No roles"
      />

      <Modal open={detail !== null} onClose={() => setDetail(null)} title={detail ? `${detail.name} · permissions` : ''}>
        {detail && (
          <div className={styles.stack}>
            <p className={styles.note}>
              <ShieldCheck size={13} style={{ verticalAlign: '-2px', marginRight: 4 }} />
              {detail.description ?? 'System-defined role.'} · {detail.permissions.length} permissions
              {detail.requires_mfa ? ' · MFA required' : ''}
            </p>
            {detail.permissions.length === 0 ? (
              <p className={styles.note}>This role grants no permissions.</p>
            ) : (
              Object.entries(groupByModule(detail.permissions)).map(([module, keys]) => (
                <div key={module} className={styles.group}>
                  <div className={styles.groupHead}>
                    <span className={styles.groupName}>{module.replace(/-/g, ' ')}</span>
                    <span className={styles.note}>{keys.length}</span>
                  </div>
                  <div className={styles.chips}>
                    {keys.map((key) => (
                      <Badge key={key} variant="outline" mono>{key}</Badge>
                    ))}
                  </div>
                </div>
              ))
            )}
          </div>
        )}
      </Modal>
    </div>
  )
}
