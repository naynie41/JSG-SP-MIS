import { useState } from 'react'
import { ExternalLink, Eye, EyeOff, FileText, Pencil, Plus, Trash2 } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { Menu } from '@/components/Menu/Menu'
import type { MenuAction } from '@/components/Menu/Menu'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { useAuth } from '@/lib/auth/AuthProvider'
import { LibraryFormModal } from './LibraryFormModal'
import { fileKindLabel, formatBytes, formatPublished } from './format'
import { useDeleteResource, useLibraryAdmin, useResourceStatus } from './hooks'
import type { LibraryCategory, LibraryResource } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './library.module.css'

/**
 * Resource library administration — the tenth section of the System Administrator
 * console (FR-RES-02/03).
 *
 * What makes this screen different from the rest of the console: everything on it
 * is one click from being public. So the status column is not decoration, the
 * publish and withdraw actions are separated from Edit, and the empty state says
 * where the result will appear.
 */
export function LibraryAdminPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('library.view')
  const canCreate = hasPermission('library.create')
  const canEdit = hasPermission('library.edit')

  const { data, isLoading } = useLibraryAdmin(canView)
  const statusMutation = useResourceStatus()
  const deleteMutation = useDeleteResource()

  const [form, setForm] = useState<{ open: boolean; resource: LibraryResource | null }>({ open: false, resource: null })
  const [confirmDelete, setConfirmDelete] = useState<LibraryResource | null>(null)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to manage the resource library.</p>
      </Card>
    )
  }

  const rows = data?.items ?? []
  const categories: LibraryCategory[] = Object.entries(data?.categories ?? {}).map(([key, label]) => ({ key, label }))

  const columns: Column<LibraryResource>[] = [
    {
      key: 'title',
      header: 'Resource',
      render: (r) => (
        <div className={styles.cellTitle}>
          <span className={styles.cellName}>
            {r.title}
            {r.featured && <Badge variant="accent">Key</Badge>}
          </span>
          <span className={styles.cellMeta}>
            <Icon icon={r.kind === 'file' ? FileText : ExternalLink} size={12} />
            {r.kind === 'file'
              ? `${fileKindLabel(r.original_filename)} · ${formatBytes(r.size_bytes)}`
              : 'External link'}
          </span>
        </div>
      ),
    },
    { key: 'category', header: 'Category', render: (r) => r.category_label },
    {
      key: 'status',
      header: 'Status',
      // Published is the one that carries consequence, so it is the only one that
      // gets a strong colour — a page of green badges would say nothing.
      render: (r) => (
        <Badge variant={r.status === 'published' ? 'success' : r.status === 'archived' ? 'neutral' : 'warning'} dot>
          {r.status_label}
        </Badge>
      ),
    },
    {
      key: 'published',
      header: 'Published',
      render: (r) => formatPublished(r.published_at) || <span className={layout.cellSub}>—</span>,
    },
    {
      key: 'downloads',
      header: 'Downloads',
      align: 'right',
      render: (r) => (r.kind === 'file' ? r.download_count.toLocaleString() : <span className={layout.cellSub}>—</span>),
    },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (r) => {
        if (!canEdit) return null

        const actions: MenuAction[] = [
          { label: 'Edit', icon: Pencil, onSelect: () => setForm({ open: true, resource: r }) },
          r.status === 'published'
            ? {
                label: 'Withdraw from public page',
                icon: EyeOff,
                onSelect: () => statusMutation.mutate({ id: r.id, status: 'archived' }),
              }
            : {
                label: 'Publish',
                icon: Eye,
                onSelect: () => statusMutation.mutate({ id: r.id, status: 'published' }),
              },
          { label: 'Remove', icon: Trash2, danger: true, onSelect: () => setConfirmDelete(r) },
        ]

        return <Menu label={`Actions for ${r.title}`} actions={actions} />
      },
    },
  ]

  return (
    <div>
      <div className={`${layout.pageHead} ${layout.pageHeadEmbedded}`}>
        <p className={styles.adminLede}>
          Policies, guidelines, tools and reports. Anything published here appears on the public{' '}
          <strong>Resources</strong> page and can be downloaded by anyone, without signing in.
        </p>
        {canCreate && (
          <Button leftIcon={Plus} onClick={() => setForm({ open: true, resource: null })}>
            Add resource
          </Button>
        )}
      </div>

      <DataTable
        caption="Library resources"
        columns={columns}
        rows={rows}
        getRowId={(r) => r.id}
        loading={isLoading}
        emptyTitle="No resources yet"
        emptyAction={
          canCreate ? (
            <Button size="sm" leftIcon={Plus} onClick={() => setForm({ open: true, resource: null })}>
              Add the first resource
            </Button>
          ) : undefined
        }
      />

      <LibraryFormModal
        open={form.open}
        resource={form.resource}
        categories={categories}
        onClose={() => setForm({ open: false, resource: null })}
      />

      <ConfirmDialog
        open={confirmDelete !== null}
        danger
        title="Remove this resource?"
        confirmLabel="Remove"
        loading={deleteMutation.isPending}
        onCancel={() => setConfirmDelete(null)}
        onConfirm={async () => {
          if (!confirmDelete) return
          await deleteMutation.mutateAsync(confirmDelete.id)
          setConfirmDelete(null)
        }}
      >
        <>
          <strong>{confirmDelete?.title}</strong> will be removed from the library and from the public page.
          {confirmDelete?.status === 'published' && (
            <>
              {' '}
              It is currently public. If you only want to take it down, <strong>withdraw</strong> it instead — that keeps
              its download history.
            </>
          )}
        </>
      </ConfirmDialog>
    </div>
  )
}
