import { useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { ArrowRightLeft, Crown, Plus, Trash2 } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Modal } from '@/components/Modal/Modal'
import { SelectField } from '@/components/Field/SelectField'
import { Menu } from '@/components/Menu/Menu'
import type { MenuAction } from '@/components/Menu/Menu'
import { ConfirmDialog } from '@/components/Modal/ConfirmDialog'
import { Spinner } from '@/components/Spinner/Spinner'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { HOUSEHOLD_ROLE_OPTIONS, titleCase } from './constants'
import {
  useAddMember,
  useBeneficiaries,
  useDesignateHead,
  useHousehold,
  useMoveMember,
  useRemoveMember,
} from './hooks'
import type { HouseholdMembership, HouseholdRole } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

type MemberMode = 'add' | 'move'

export function HouseholdDetailPage() {
  const { id = '' } = useParams<{ id: string }>()
  const { hasPermission, user } = useAuth()
  const canView = hasPermission('household.view')

  const { data: household, isLoading, error } = useHousehold(id, canView)
  const { data: beneficiaryPage } = useBeneficiaries({ page: 1, perPage: 100 }, canView)
  const addMember = useAddMember(id)
  const moveMember = useMoveMember(id)
  const removeMember = useRemoveMember(id)
  const designateHead = useDesignateHead(id)

  const [memberModal, setMemberModal] = useState<MemberMode | null>(null)
  const [pickBeneficiary, setPickBeneficiary] = useState('')
  const [pickRole, setPickRole] = useState<HouseholdRole>('other')
  const [memberError, setMemberError] = useState<string | null>(null)
  const [toRemove, setToRemove] = useState<HouseholdMembership | null>(null)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view households.</p>
      </Card>
    )
  }

  if (isLoading) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading household" />
      </div>
    )
  }

  if (error || !household) {
    return (
      <Card>
        <p className={layout.forbidden}>This household could not be found or is outside your MDA.</p>
      </Card>
    )
  }

  const isOwner = user?.mda?.id != null && user.mda.id === household.owner_mda_id
  const canEdit = hasPermission('household.edit') && isOwner
  const members = household.members
  const history = household.history ?? []
  const beneficiaries = beneficiaryPage?.items ?? []
  const available = beneficiaries.filter((b) => !members.some((m) => m.beneficiary_id === b.id))

  async function submitMember() {
    if (!pickBeneficiary) {
      setMemberError('Choose a beneficiary.')
      return
    }
    setMemberError(null)
    try {
      if (memberModal === 'move') {
        await moveMember.mutateAsync({ beneficiary_id: pickBeneficiary, role_in_household: pickRole })
      } else {
        await addMember.mutateAsync({ beneficiary_id: pickBeneficiary, role_in_household: pickRole })
      }
      setMemberModal(null)
      setPickBeneficiary('')
    } catch (err) {
      setMemberError(err instanceof ApiError ? err.message : 'Action failed. Please try again.')
    }
  }

  const memberColumns: Column<HouseholdMembership>[] = [
    {
      key: 'name',
      header: 'Member',
      render: (m) => (
        <div className={styles.cellStack}>
          <Link to={`/beneficiaries/${m.beneficiary_id}`}>{m.beneficiary_name ?? m.beneficiary_id.slice(0, 8)}</Link>
          {household.head_beneficiary_id === m.beneficiary_id && (
            <span className={styles.cellSub}>Head of household</span>
          )}
        </div>
      ),
    },
    { key: 'role', header: 'Role', render: (m) => <span style={{ textTransform: 'capitalize' }}>{m.role_in_household}</span> },
    { key: 'joined', header: 'Joined', render: (m) => <span className={styles.mono}>{m.joined_at.slice(0, 10)}</span> },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (m) => {
        if (!canEdit) return null
        const actions: MenuAction[] = [
          { label: 'Make head', icon: Crown, onSelect: () => designateHead.mutate(m.beneficiary_id) },
          { label: 'Remove', icon: Trash2, danger: true, onSelect: () => setToRemove(m) },
        ]
        return <Menu label="Member actions" actions={actions} />
      },
    },
  ]

  const historyColumns: Column<HouseholdMembership>[] = [
    { key: 'name', header: 'Member', render: (m) => m.beneficiary_name ?? m.beneficiary_id.slice(0, 8) },
    { key: 'role', header: 'Role', render: (m) => <span style={{ textTransform: 'capitalize' }}>{m.role_in_household}</span> },
    { key: 'joined', header: 'Joined', render: (m) => <span className={styles.mono}>{m.joined_at.slice(0, 10)}</span> },
    { key: 'left', header: 'Left', render: (m) => <span className={styles.mono}>{m.left_at?.slice(0, 10) ?? '—'}</span> },
    {
      key: 'state',
      header: 'State',
      render: (m) => <Badge variant={m.is_open ? 'success' : 'neutral'}>{m.is_open ? 'Open' : 'Closed'}</Badge>,
    },
  ]

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">Household</h1>
          <span className={styles.mono}>{household.id}</span>
        </div>
        {canEdit && (
          <div className={styles.rowActions}>
            <Button variant="tertiary" leftIcon={ArrowRightLeft} onClick={() => { setMemberModal('move'); setMemberError(null) }}>
              Move member in
            </Button>
            <Button leftIcon={Plus} onClick={() => { setMemberModal('add'); setMemberError(null) }}>
              Add member
            </Button>
          </div>
        )}
      </div>

      <div className={styles.stack}>
        <Card title="Details" eyebrow="Household">
          <dl className={styles.dl}>
            <dt>LGA / Ward</dt>
            <dd>{`${household.lga ? titleCase(household.lga) : '—'} · ${household.ward ?? '—'}`}</dd>
            <dt>Address</dt>
            <dd>{household.address ?? '—'}</dd>
            <dt>Registered</dt>
            <dd className={styles.mono}>{household.registration_date}</dd>
          </dl>
        </Card>

        <Card title="Current members" eyebrow="Composition" flush>
          <DataTable
            caption="Current members"
            columns={memberColumns}
            rows={members}
            getRowId={(m) => m.id}
            emptyTitle="No current members"
          />
        </Card>

        <Card title="Membership history" eyebrow="Audit trail" flush>
          <DataTable
            caption="Membership history"
            columns={historyColumns}
            rows={history}
            getRowId={(m) => m.id}
            emptyTitle="No membership history"
          />
        </Card>
      </div>

      <Modal
        open={memberModal !== null}
        onClose={() => setMemberModal(null)}
        title={memberModal === 'move' ? 'Move member into household' : 'Add member'}
        footer={
          <>
            <Button variant="tertiary" onClick={() => setMemberModal(null)} disabled={addMember.isPending || moveMember.isPending}>
              Cancel
            </Button>
            <Button onClick={submitMember} loading={addMember.isPending || moveMember.isPending}>
              {memberModal === 'move' ? 'Move member' : 'Add member'}
            </Button>
          </>
        }
      >
        <div className={layout.form}>
          {memberError && (
            <p className={layout.alert} role="alert">
              {memberError}
            </p>
          )}
          {memberModal === 'move' && (
            <p className={styles.note}>
              Moving a beneficiary closes their current open membership (history is kept) and opens a new one here.
            </p>
          )}
          <SelectField
            label="Beneficiary"
            placeholder={available.length ? 'Select beneficiary' : 'No beneficiaries available'}
            options={available.map((b) => ({ value: b.id, label: b.full_name }))}
            value={pickBeneficiary}
            onChange={(e) => setPickBeneficiary(e.target.value)}
          />
          <SelectField
            label="Role"
            options={HOUSEHOLD_ROLE_OPTIONS}
            value={pickRole}
            onChange={(e) => setPickRole(e.target.value as HouseholdRole)}
          />
        </div>
      </Modal>

      <ConfirmDialog
        open={toRemove !== null}
        danger
        title="Remove member?"
        confirmLabel="Remove"
        loading={removeMember.isPending}
        onCancel={() => setToRemove(null)}
        onConfirm={async () => {
          if (!toRemove) return
          await removeMember.mutateAsync(toRemove.beneficiary_id)
          setToRemove(null)
        }}
      >
        This closes the membership for <strong>{toRemove?.beneficiary_name ?? 'this member'}</strong>. The
        history entry is retained.
      </ConfirmDialog>
    </div>
  )
}
