import { useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { Pencil } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { Tabs } from '@/components/Tabs/Tabs'
import { Spinner } from '@/components/Spinner/Spinner'
import { useAuth } from '@/lib/auth/AuthProvider'
import { EditBeneficiaryModal } from './EditBeneficiaryModal'
import { DocumentsPanel } from './DocumentsPanel'
import { REGISTRATION_SOURCE_LABELS, titleCase } from './constants'
import { useBeneficiary } from './hooks'
import type { Beneficiary } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

function ProfileTab({ beneficiary }: { beneficiary: Beneficiary }) {
  return (
    <div className={styles.detailGrid}>
      <Card title="Core profile" eyebrow="Identity">
        <dl className={styles.dl}>
          <dt>Full name</dt>
          <dd>{beneficiary.full_name}</dd>
          <dt>Date of birth</dt>
          <dd>{beneficiary.date_of_birth ?? '—'}</dd>
          <dt>Gender</dt>
          <dd style={{ textTransform: 'capitalize' }}>{beneficiary.gender ?? '—'}</dd>
          <dt>NIN</dt>
          <dd className={styles.mono}>{beneficiary.nin ?? '—'}</dd>
          <dt>BVN</dt>
          <dd className={styles.mono}>{beneficiary.bvn ?? '—'}</dd>
          <dt>Phone</dt>
          <dd>{beneficiary.phone ?? '—'}</dd>
          <dt>LGA / Ward</dt>
          <dd>{`${beneficiary.lga ? titleCase(beneficiary.lga) : '—'} · ${beneficiary.ward ?? '—'}`}</dd>
          <dt>Address</dt>
          <dd>{beneficiary.address ?? '—'}</dd>
        </dl>
      </Card>

      <Card title="Provenance" eyebrow="Origin" variant="mint">
        <dl className={styles.dl}>
          <dt>Source</dt>
          <dd>{REGISTRATION_SOURCE_LABELS[beneficiary.registration_source] ?? beneficiary.registration_source}</dd>
          <dt>Registered</dt>
          <dd className={styles.mono}>{beneficiary.registration_date}</dd>
          <dt>Record ID</dt>
          <dd className={styles.mono}>{beneficiary.id}</dd>
          {beneficiary.original_record_id && (
            <>
              <dt>Original ID</dt>
              <dd className={styles.mono}>{beneficiary.original_record_id}</dd>
            </>
          )}
          {beneficiary.import_batch_id && (
            <>
              <dt>Import batch</dt>
              <dd className={styles.mono}>{beneficiary.import_batch_id}</dd>
            </>
          )}
        </dl>
      </Card>
    </div>
  )
}

function HouseholdTab({ beneficiary }: { beneficiary: Beneficiary }) {
  const household = beneficiary.current_household
  if (!household) {
    return (
      <Card>
        <p className={styles.note}>
          This beneficiary is not currently in a household. Households are managed on the{' '}
          <Link to="/households">Households</Link> screen.
        </p>
      </Card>
    )
  }
  return (
    <Card title="Current household" eyebrow="Membership">
      <dl className={styles.dl}>
        <dt>Household</dt>
        <dd>
          <Link to={`/households/${household.household_id}`}>{household.household_id}</Link>
        </dd>
        <dt>Role</dt>
        <dd style={{ textTransform: 'capitalize' }}>{household.role_in_household}</dd>
        <dt>Joined</dt>
        <dd className={styles.mono}>{household.joined_at.slice(0, 10)}</dd>
      </dl>
    </Card>
  )
}

export function BeneficiaryDetailPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission, user } = useAuth()
  const canView = hasPermission('beneficiary.view')
  const { data: beneficiary, isLoading, error } = useBeneficiary(id, canView)
  const [editOpen, setEditOpen] = useState(false)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view beneficiaries.</p>
      </Card>
    )
  }

  if (isLoading) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={24} label="Loading beneficiary" />
      </div>
    )
  }

  if (error || !beneficiary) {
    return (
      <Card>
        <p className={layout.forbidden}>This beneficiary could not be found or is outside your MDA.</p>
      </Card>
    )
  }

  const isOwner = user?.mda?.id != null && user.mda.id === beneficiary.owner_mda_id
  const canEdit = hasPermission('beneficiary.edit') && isOwner

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">03 · Registry</span>
          <h1 className="t-h1">{beneficiary.full_name}</h1>
        </div>
        <div className={styles.rowActions}>
          <Badge variant={statusVariant(beneficiary.status)} dot>
            {beneficiary.status}
          </Badge>
          {canEdit && (
            <Button leftIcon={Pencil} onClick={() => setEditOpen(true)}>
              Edit
            </Button>
          )}
        </div>
      </div>

      <Tabs
        items={[
          { id: 'profile', label: 'Profile', content: <ProfileTab beneficiary={beneficiary} /> },
          {
            id: 'documents',
            label: 'Documents',
            content: <DocumentsPanel beneficiaryId={beneficiary.id} canManage={canEdit} />,
          },
          { id: 'household', label: 'Household', content: <HouseholdTab beneficiary={beneficiary} /> },
        ]}
      />

      {canEdit && <EditBeneficiaryModal open={editOpen} beneficiary={beneficiary} onClose={() => setEditOpen(false)} />}
    </div>
  )
}
