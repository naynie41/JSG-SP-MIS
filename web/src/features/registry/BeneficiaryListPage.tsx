import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { FileUp, Pencil, Search, Trash2 } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { Menu } from '@/components/Menu/Menu'
import type { MenuAction } from '@/components/Menu/Menu'
import { DataTableExport } from '@/components/DataTable/DataTableExport'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { useAuth } from '@/lib/auth/AuthProvider'
import { EditBeneficiaryModal } from './EditBeneficiaryModal'
import { LookupModal } from './LookupModal'
import { LGA_OPTIONS, REGISTRATION_SOURCE_LABELS, STATUS_OPTIONS, titleCase } from './constants'
import { useBeneficiaries, useDeleteBeneficiary } from './hooks'
import type { Beneficiary } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

export function BeneficiaryListPage() {
  const { hasPermission, user } = useAuth()
  const navigate = useNavigate()
  const canView = hasPermission('beneficiary.view')
  const canImport = hasPermission('beneficiary.create')
  const canEdit = hasPermission('beneficiary.edit')
  const canLookup = hasPermission('beneficiary-lookup.view')

  const [page, setPage] = useState(1)
  const [search, setSearch] = useState('')
  const [lga, setLga] = useState('')
  const [status, setStatus] = useState('')
  const [formOpen, setFormOpen] = useState<{ open: boolean; beneficiary: Beneficiary | null }>({ open: false, beneficiary: null })
  const [lookupOpen, setLookupOpen] = useState(false)
  const [toDelete, setToDelete] = useState<Beneficiary | null>(null)

  const { data, isLoading } = useBeneficiaries({ page, search, lga, status }, canView)
  const deleteBeneficiary = useDeleteBeneficiary()

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view beneficiaries.</p>
      </Card>
    )
  }

  const rows = data?.items ?? []
  const isOwner = (b: Beneficiary) => user?.mda?.id != null && user.mda.id === b.owner_mda_id

  const columns: Column<Beneficiary>[] = [
    {
      key: 'name',
      header: 'Name',
      render: (b) => (
        <div className={styles.cellStack}>
          <Link to={`/beneficiaries/${b.id}`}>{b.full_name}</Link>
          <span className={styles.cellSub}>{REGISTRATION_SOURCE_LABELS[b.registration_source] ?? b.registration_source}</span>
        </div>
      ),
    },
    {
      key: 'location',
      header: 'LGA / Ward',
      render: (b) => (
        <div className={styles.cellStack}>
          <span>{b.lga ? titleCase(b.lga) : '—'}</span>
          <span className={styles.cellSub}>{b.ward ?? '—'}</span>
        </div>
      ),
    },
    {
      key: 'status',
      header: 'Status',
      render: (b) => (
        <Badge variant={statusVariant(b.status)} dot>
          {b.status}
        </Badge>
      ),
    },
    { key: 'registered', header: 'Registered', render: (b) => <span className={styles.mono}>{b.registration_date}</span> },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (b) => {
        if (!canEdit || !isOwner(b)) return null
        const actions: MenuAction[] = [
          { label: 'Edit', icon: Pencil, onSelect: () => setFormOpen({ open: true, beneficiary: b }) },
          { label: 'Delete', icon: Trash2, danger: true, onSelect: () => setToDelete(b) },
        ]
        return <Menu label={`Actions for ${b.full_name}`} actions={actions} />
      },
    },
  ]

  const pageCount = data?.pagination?.total_pages ?? 1

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Beneficiaries</h1>
        </div>
        <div className={styles.rowActions}>
          <DataTableExport
            endpoint="/beneficiaries/export"
            permission="beneficiary.export"
            params={{ search: search || undefined, 'filter[lga]': lga || undefined, 'filter[status]': status || undefined }}
          />
          {canLookup && (
            <Button variant="tertiary" leftIcon={Search} onClick={() => setLookupOpen(true)}>
              Cross-MDA lookup
            </Button>
          )}
          {canImport && (
            <Button leftIcon={FileUp} onClick={() => navigate('/imports')}>
              Import beneficiaries
            </Button>
          )}
        </div>
      </div>

      <div className={styles.filters}>
        <div className={styles.searchField}>
          <TextField
            label="Search"
            hideLabel
            placeholder="Search name or NIN/BVN…"
            value={search}
            onChange={(e) => {
              setPage(1)
              setSearch(e.target.value)
            }}
          />
        </div>
        <div className={styles.filterField}>
          <SelectField
            label="LGA"
            hideLabel
            options={[{ value: '', label: 'All LGAs' }, ...LGA_OPTIONS]}
            value={lga}
            onChange={(e) => {
              setPage(1)
              setLga(e.target.value)
            }}
          />
        </div>
        <div className={styles.filterField}>
          <SelectField
            label="Status"
            hideLabel
            options={[{ value: '', label: 'All statuses' }, ...STATUS_OPTIONS]}
            value={status}
            onChange={(e) => {
              setPage(1)
              setStatus(e.target.value)
            }}
          />
        </div>
      </div>

      <DataTable
        caption="Beneficiaries"
        columns={columns}
        rows={rows}
        getRowId={(b) => b.id}
        loading={isLoading}
        emptyTitle="No beneficiaries yet — records are added by importing a source"
        emptyAction={
          canImport ? (
            <Button size="sm" leftIcon={FileUp} onClick={() => navigate('/imports')}>
              Go to Import Center
            </Button>
          ) : undefined
        }
        pagination={{ page, pageCount, onPageChange: setPage }}
      />

      {formOpen.beneficiary && (
        <EditBeneficiaryModal
          open={formOpen.open}
          beneficiary={formOpen.beneficiary}
          onClose={() => setFormOpen({ open: false, beneficiary: null })}
        />
      )}

      {canLookup && <LookupModal open={lookupOpen} onClose={() => setLookupOpen(false)} />}

      <ConfirmDialog
        open={toDelete !== null}
        danger
        title="Delete beneficiary?"
        confirmLabel="Delete"
        loading={deleteBeneficiary.isPending}
        onCancel={() => setToDelete(null)}
        onConfirm={async () => {
          if (!toDelete) return
          await deleteBeneficiary.mutateAsync(toDelete.id)
          setToDelete(null)
        }}
      >
        This will remove <strong>{toDelete?.full_name}</strong> from the registry. Their record is retained
        for audit and can be restored by an administrator.
      </ConfirmDialog>
    </div>
  )
}
