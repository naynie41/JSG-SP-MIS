import { useEffect, useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import { Plus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Card } from '@/components/Card/Card'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { ProgrammeFormModal } from './ProgrammeFormModal'
import { PROGRAMME_STATUS_OPTIONS, PROGRAMME_TYPE_OPTIONS } from './constants'
import { useProgrammes } from './hooks'
import type { Programme } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

export function ProgrammeListPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('programme.view')
  const canCreate = hasPermission('programme.create')

  const [page, setPage] = useState(1)
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState('')
  const [type, setType] = useState('')
  const [createOpen, setCreateOpen] = useState(false)

  // Deep-link from the dashboard / hub "New programme" shortcut opens the create modal.
  const [searchParams, setSearchParams] = useSearchParams()
  useEffect(() => {
    if (searchParams.get('new') === '1' && canCreate) {
      setCreateOpen(true)
      searchParams.delete('new')
      setSearchParams(searchParams, { replace: true })
    }
  }, [searchParams, setSearchParams, canCreate])

  const { data, isLoading } = useProgrammes({ page, search, status, type }, canView)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view programmes.</p>
      </Card>
    )
  }

  const columns: Column<Programme>[] = [
    { key: 'name', header: 'Programme', render: (p) => <Link to={`/programmes/${p.id}`}>{p.name}</Link> },
    { key: 'type', header: 'Type', render: (p) => <Badge variant={statusVariant(`type.${p.type}`)}>{p.type}</Badge> },
    { key: 'status', header: 'Status', render: (p) => <Badge variant={statusVariant(`programme.${p.status}`)} dot>{p.status}</Badge> },
    { key: 'budget', header: 'Budget', align: 'right', render: (p) => <span className={styles.mono}>{formatNaira(p.budget_amount)}</span> },
    { key: 'activities', header: 'Activities', align: 'right', render: (p) => p.activities_count ?? 0 },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">Programmes</span>
          <h1 className="t-h1">Programmes</h1>
        </div>
        {canCreate && (
          <Button leftIcon={Plus} onClick={() => setCreateOpen(true)}>
            Create programme
          </Button>
        )}
      </div>

      <div className={styles.filters}>
        <TextField className={styles.searchField} label="Search" placeholder="Programme name" value={search} onChange={(e) => { setSearch(e.target.value); setPage(1) }} />
        <SelectField className={styles.filterField} label="Type" placeholder="All types" options={PROGRAMME_TYPE_OPTIONS} value={type} onChange={(e) => { setType(e.target.value); setPage(1) }} />
        <SelectField className={styles.filterField} label="Status" placeholder="All statuses" options={PROGRAMME_STATUS_OPTIONS} value={status} onChange={(e) => { setStatus(e.target.value); setPage(1) }} />
      </div>

      <DataTable
        caption="Programmes"
        columns={columns}
        rows={data?.items ?? []}
        getRowId={(p) => p.id}
        loading={isLoading}
        emptyTitle="No programmes yet"
        emptyAction={canCreate ? <Button size="sm" leftIcon={Plus} onClick={() => setCreateOpen(true)}>Create programme</Button> : undefined}
        pagination={
          data?.pagination
            ? { page: data.pagination.page, pageCount: data.pagination.total_pages, onPageChange: setPage }
            : undefined
        }
      />

      <ProgrammeFormModal open={createOpen} onClose={() => setCreateOpen(false)} />
    </div>
  )
}
