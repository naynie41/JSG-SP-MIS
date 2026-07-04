import { AlertTriangle } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Button } from '@/components/Button/Button'
import { Icon } from '@/components/Icon/Icon'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { BENEFIT_STATUS_LABELS, BENEFIT_TYPE_LABELS } from './constants'
import { useBeneficiaryLedger, useBenefitFlags, useReviewFlag } from './hooks'
import type { Benefit } from './types'
import styles from '@/features/programmes/programmes.module.css'

/**
 * A beneficiary's complete benefit history across MDAs (FR-BEN-01/03), with the
 * status badge map, and any double-dipping flags surfaced for review (FR-BEN-05).
 */
export function BenefitsPanel({ beneficiaryId }: { beneficiaryId: string }) {
  const { hasPermission } = useAuth()
  const canReview = hasPermission('benefit.approve')

  const ledger = useBeneficiaryLedger(beneficiaryId)
  const flags = useBenefitFlags({ beneficiary_id: beneficiaryId, status: 'open' })
  const review = useReviewFlag()

  const openFlags = flags.data ?? []

  const columns: Column<Benefit>[] = [
    { key: 'type', header: 'Type', render: (b) => BENEFIT_TYPE_LABELS[b.benefit_type] ?? b.benefit_type },
    { key: 'date', header: 'Delivered', render: (b) => <span className={styles.mono}>{b.delivery_date}</span> },
    { key: 'mda', header: 'Delivering MDA', render: (b) => <span className={styles.mono}>#{b.mda_id.slice(0, 8)}</span> },
    { key: 'value', header: 'Value', align: 'right', render: (b) => <span className={styles.mono}>{formatNaira(b.monetary_value)}</span> },
    { key: 'status', header: 'Status', render: (b) => <Badge variant={statusVariant(`benefit.${b.status}`)} dot>{BENEFIT_STATUS_LABELS[b.status] ?? b.status}</Badge> },
  ]

  return (
    <div className={styles.stack}>
      {openFlags.length > 0 && (
        <Card title="Potential double-dipping" eyebrow="Review" variant="mint">
          <p className={styles.note} style={{ display: 'flex', alignItems: 'center', gap: 'var(--space-2)', marginBottom: 'var(--space-3)' }}>
            <Icon icon={AlertTriangle} size={16} />
            {openFlags.length} open {openFlags.length === 1 ? 'flag' : 'flags'}. Delivery was not blocked — review below.
          </p>
          {openFlags.map((flag) => (
            <div key={flag.id} className={styles.rowActions} style={{ justifyContent: 'space-between', paddingBottom: 'var(--space-2)' }}>
              <span>
                <Badge variant="warning" dot>{flag.benefit_type}</Badge> {flag.reason}
              </span>
              {canReview && (
                <span className={styles.rowActions}>
                  <Button size="sm" variant="tertiary" onClick={() => review.mutate({ id: flag.id, status: 'dismissed' })}>Dismiss</Button>
                  <Button size="sm" variant="danger" onClick={() => review.mutate({ id: flag.id, status: 'confirmed' })}>Confirm</Button>
                </span>
              )}
            </div>
          ))}
        </Card>
      )}

      <DataTable
        caption="Benefit ledger"
        columns={columns}
        rows={ledger.data?.items ?? []}
        getRowId={(b) => b.id}
        loading={ledger.isLoading}
        emptyTitle="No benefits recorded yet"
      />
    </div>
  )
}
