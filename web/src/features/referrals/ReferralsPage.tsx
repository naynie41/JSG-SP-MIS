import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { AlertTriangle, ArrowRight, Plus } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { Tabs } from '@/components/Tabs/Tabs'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { SelectField } from '@/components/Field/SelectField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { REFERRAL_STATUS_FILTER, REFERRAL_STATUS_LABELS } from './constants'
import { useReferrals } from './hooks'
import { RaiseReferralModal } from './RaiseReferralModal'
import type { Referral, ReferralDirection } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './referrals.module.css'

const shortId = (id: string) => id.slice(0, 8)

export function StatusBadge({ status }: { status: Referral['status'] }) {
  return (
    <Badge variant={statusVariant(`referral.${status}`)} dot>
      {REFERRAL_STATUS_LABELS[status]}
    </Badge>
  )
}

/** Overdue/SLA signal (FR-REF-04): breached referrals carry an escalation level. */
export function SlaIndicator({ referral }: { referral: Pick<Referral, 'sla_breached_at' | 'escalation_level' | 'status'> }) {
  const terminal = referral.status === 'completed' || referral.status === 'closed' || referral.status === 'rejected'
  if (referral.sla_breached_at === null && referral.escalation_level === 0) {
    return <span className={styles.mono}>{terminal ? '—' : 'On track'}</span>
  }
  return (
    <span className={styles.sla}>
      <Badge variant="danger" dot>
        <Icon icon={AlertTriangle} size={12} /> {`Overdue · L${referral.escalation_level}`}
      </Badge>
    </span>
  )
}

function ReferralTable({ direction }: { direction: ReferralDirection }) {
  const navigate = useNavigate()
  const [status, setStatus] = useState('')
  const { data, isLoading } = useReferrals({ direction, status: status || undefined })

  const counterpartyHeader = direction === 'incoming' ? 'Referring MDA' : 'Receiving MDA'
  const counterpartyId = (r: Referral) => (direction === 'incoming' ? r.from_mda_id : r.to_mda_id)

  const columns: Column<Referral>[] = [
    { key: 'ben', header: 'Beneficiary', render: (r) => <span className={styles.mono}>#{shortId(r.beneficiary_id)}</span> },
    { key: 'mda', header: counterpartyHeader, render: (r) => <span className={styles.mono}>#{shortId(counterpartyId(r))}</span> },
    { key: 'need', header: 'Need', render: (r) => r.need },
    { key: 'status', header: 'Status', render: (r) => <StatusBadge status={r.status} /> },
    { key: 'sla', header: 'SLA', render: (r) => <SlaIndicator referral={r} /> },
    {
      key: 'view',
      header: '',
      align: 'right',
      render: (r) => (
        <Button size="sm" variant="tertiary" rightIcon={ArrowRight} onClick={() => navigate(`/referrals/${r.id}`)}>
          View
        </Button>
      ),
    },
  ]

  return (
    <div>
      <div className={styles.tableTools}>
        <div className={styles.filter}>
          <SelectField label="Status" options={REFERRAL_STATUS_FILTER} value={status} onChange={(e) => setStatus(e.target.value)} />
        </div>
      </div>
      <DataTable
        caption={direction === 'incoming' ? 'Referrals to my MDA' : 'Referrals my MDA raised'}
        columns={columns}
        rows={data?.items ?? []}
        getRowId={(r) => r.id}
        loading={isLoading}
        emptyTitle={direction === 'incoming' ? 'No incoming referrals' : 'You have not raised any referrals'}
      />
    </div>
  )
}

/**
 * Referrals (FR-REF, §8.2). Two-party: an **outbox** of referrals my MDA raised and
 * an **inbox** of referrals routed to my MDA. Each row shows the lifecycle status and
 * an SLA/overdue signal; open one to act on its lifecycle.
 */
export function ReferralsPage() {
  const { hasPermission } = useAuth()
  const navigate = useNavigate()
  const canView = hasPermission('referral.view')
  const canCreate = hasPermission('referral.create')
  const [raising, setRaising] = useState(false)

  if (!canView) {
    return (
      <Card>
        <p className={layout.forbidden}>You do not have permission to view referrals.</p>
      </Card>
    )
  }

  return (
    <div>
      <div className={layout.pageHead}>
        <div className={layout.pageTitle}>
          <span className="eyebrow">05 · Coordination</span>
          <h1 className="t-h1">Referrals</h1>
          <p className={styles.note}>
            Refer a beneficiary to another MDA to serve a need. Referrals never change ownership; both MDAs track the
            lifecycle and are notified at each step and on SLA breach.
          </p>
        </div>
        {canCreate && (
          <Button leftIcon={Plus} onClick={() => setRaising(true)}>
            Raise referral
          </Button>
        )}
      </div>

      <Tabs
        items={[
          { id: 'outbox', label: 'Outbox — raised by us', content: <ReferralTable direction="outgoing" /> },
          { id: 'inbox', label: 'Inbox — referred to us', content: <ReferralTable direction="incoming" /> },
        ]}
      />

      <RaiseReferralModal open={raising} onClose={() => setRaising(false)} onCreated={(r) => { setRaising(false); navigate(`/referrals/${r.id}`) }} />
    </div>
  )
}
