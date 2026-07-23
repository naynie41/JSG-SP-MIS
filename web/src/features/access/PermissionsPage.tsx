import { Check, Minus } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { Tabs } from '@/components/Tabs/Tabs'
import { Spinner } from '@/components/Spinner/Spinner'
import { Icon } from '@/components/Icon/Icon'
import { useAuth } from '@/lib/auth/AuthProvider'
import { usePermissionMatrix, usePermissionModules } from './hooks'
import layout from '@/features/shared/formLayout.module.css'
import styles from './access.module.css'

/** Permission catalogue grouped by module. */
function Catalogue() {
  const { data: modules, isLoading } = usePermissionModules()
  if (isLoading || !modules) return <Spinner size={22} label="Loading permissions" />

  const entries = Object.entries(modules)
  if (entries.length === 0) return <p className={styles.note}>No permissions registered.</p>

  return (
    <div className={styles.stack}>
      {entries.map(([module, perms]) => (
        <Card key={module} eyebrow="Module" title={module.replace(/-/g, ' ')}>
          {perms.map((p) => (
            <div key={p.key} className={styles.permRow}>
              <Badge variant="neutral" mono>{p.key}</Badge>
              <span className={styles.note}>{p.description ?? '—'}</span>
            </div>
          ))}
        </Card>
      ))}
    </div>
  )
}

/** Role × permission matrix: who can do what. */
function Matrix() {
  const { data, isLoading } = usePermissionMatrix()
  if (isLoading || !data) return <Spinner size={22} label="Loading matrix" />

  const held = data.roles.map((r) => new Set(r.permissions))

  return (
    <Card flush>
      <div className={styles.matrixWrap}>
        <table className={styles.matrix}>
          <thead>
            <tr>
              <th className={styles.matrixKey}>Permission</th>
              {data.roles.map((r) => (
                <th key={r.key} title={r.name}>{r.name}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {data.permissions.map((permission) => (
              <tr key={permission}>
                <td className={styles.matrixKey}>{permission}</td>
                {data.roles.map((r, i) => (
                  <td key={r.key}>
                    {held[i].has(permission) ? (
                      <Icon icon={Check} size={15} className={styles.has} label={`${r.name} has ${permission}`} />
                    ) : (
                      <Icon icon={Minus} size={13} className={styles.hasnt} />
                    )}
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </Card>
  )
}

/**
 * Permissions administration (FR-UAM-05): the module + action catalogue and the
 * role × permission matrix. Read-only — permissions are declared by the modules and
 * bundled onto the predefined roles; there is nothing to create/edit here.
 */
export function PermissionsPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('permission.view')

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view permissions.</p>
      </Card>
    )
  }

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">02 · Administration</span>
          <h1 className="t-h1">Permissions</h1>
        </div>
      </div>

      <Tabs
        items={[
          { id: 'catalogue', label: 'Catalogue', content: <Catalogue /> },
          { id: 'matrix', label: 'Role matrix', content: <Matrix /> },
        ]}
      />
    </div>
  )
}
