import { useState } from 'react'
import { UserPlus } from 'lucide-react'
import { Modal } from '@/components/Modal/Modal'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { TextField } from '@/components/Field/TextField'
import { SelectField } from '@/components/Field/SelectField'
import { ApiError } from '@/types/api'
import { useBeneficiaries, useHouseholds } from '@/features/registry/hooks'
import { titleCase } from '@/features/registry/constants'
import type { Beneficiary, Household } from '@/features/registry/types'
import { useActivities, useBulkEnroll, useEnroll } from './hooks'
import type { BulkEnrollResult, Enrollment, Programme } from './types'
import formStyles from '@/features/shared/formLayout.module.css'
import styles from './programmes.module.css'

interface EnrollModalProps {
  open: boolean
  onClose: () => void
  programme: Programme
}

type Target = { id: string; label: string; sub: string }

/**
 * Enroll beneficiaries (individual programme) or households (household programme)
 * into a programme — type-aware, single + bulk, with eligibility flagging (FR-PRG-03).
 */
export function EnrollModal({ open, onClose, programme }: EnrollModalProps) {
  const isIndividual = programme.type === 'individual'
  const [query, setQuery] = useState('')
  const [selected, setSelected] = useState<Set<string>>(new Set())
  const [activityId, setActivityId] = useState('')
  const [error, setError] = useState<string | null>(null)
  const [single, setSingle] = useState<Enrollment | null>(null)
  const [bulk, setBulk] = useState<BulkEnrollResult | null>(null)

  const beneficiaries = useBeneficiaries({ page: 1, search: query }, open && isIndividual)
  const households = useHouseholds(1, open && !isIndividual)
  const activities = useActivities(programme.id, open)
  const enroll = useEnroll(programme.id)
  const bulkEnroll = useBulkEnroll(programme.id)

  const targets: Target[] = isIndividual
    ? (beneficiaries.data?.items ?? []).map((b: Beneficiary) => ({ id: b.id, label: b.full_name, sub: `${b.lga ? titleCase(b.lga) : '—'} · ${b.status}` }))
    : (households.data?.items ?? []).map((h: Household) => ({ id: h.id, label: `Household ${h.id.slice(0, 8)}`, sub: `${h.lga ? titleCase(h.lga) : '—'} · ${h.members.length} members` }))

  function toggle(id: string) {
    setSelected((s) => {
      const next = new Set(s)
      if (next.has(id)) next.delete(id)
      else next.add(id)
      return next
    })
  }

  async function submit() {
    setError(null)
    setSingle(null)
    setBulk(null)
    const ids = [...selected]
    const idKey = isIndividual ? 'beneficiary_id' : 'household_id'
    const idsKey = isIndividual ? 'beneficiary_ids' : 'household_ids'
    try {
      if (ids.length === 1) {
        setSingle(await enroll.mutateAsync({ [idKey]: ids[0], activity_id: activityId || undefined }))
      } else {
        setBulk(await bulkEnroll.mutateAsync({ [idsKey]: ids, activity_id: activityId || undefined }))
      }
      setSelected(new Set())
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Enrollment failed. Please try again.')
    }
  }

  const columns: Column<Target>[] = [
    { key: 'label', header: isIndividual ? 'Beneficiary' : 'Household', render: (t) => t.label },
    { key: 'sub', header: 'LGA · detail', render: (t) => <span className={styles.note}>{t.sub}</span> },
  ]

  const flaggedCount = bulk?.results.filter((r) => r.eligibility_flagged).length ?? 0

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={`Enroll ${isIndividual ? 'beneficiaries' : 'households'} · ${programme.name}`}
      footer={
        <>
          <Button variant="tertiary" onClick={onClose}>
            Close
          </Button>
          <Button leftIcon={UserPlus} onClick={submit} loading={enroll.isPending || bulkEnroll.isPending} disabled={selected.size === 0}>
            Enroll {selected.size > 0 ? selected.size : ''}
          </Button>
        </>
      }
    >
      <div className={styles.stack}>
        {error && (
          <p className={formStyles.alert} role="alert">
            {error}
          </p>
        )}

        {single && (
          <p className={styles.note}>
            Enrolled.{' '}
            {single.eligibility_flagged ? (
              <Badge variant="warning" dot>
                Eligibility flagged: {single.eligibility_notes?.join(', ')}
              </Badge>
            ) : (
              <Badge variant="success" dot>
                Eligible
              </Badge>
            )}
          </p>
        )}

        {bulk && (
          <div className={styles.bulkResult}>
            <Badge variant="success">{bulk.enrolled} enrolled</Badge>
            <Badge variant="neutral">{bulk.skipped} skipped</Badge>
            <Badge variant="danger">{bulk.rejected} rejected</Badge>
            {flaggedCount > 0 && <Badge variant="warning" dot>{flaggedCount} flagged</Badge>}
          </div>
        )}

        <div className={formStyles.grid2}>
          {isIndividual && (
            <TextField label="Search beneficiaries" placeholder="Name" value={query} onChange={(e) => setQuery(e.target.value)} />
          )}
          <SelectField
            label="Activity (optional)"
            placeholder="Programme-level"
            options={(activities.data?.items ?? []).map((a) => ({ value: a.id, label: a.name }))}
            value={activityId}
            onChange={(e) => setActivityId(e.target.value)}
          />
        </div>

        <DataTable
          caption="Enrollment targets"
          columns={columns}
          rows={targets}
          getRowId={(t) => t.id}
          loading={beneficiaries.isLoading || households.isLoading}
          selectedIds={selected}
          onToggleRow={toggle}
          emptyTitle={isIndividual ? 'Search for beneficiaries to enroll' : 'No households found'}
        />
      </div>
    </Modal>
  )
}
