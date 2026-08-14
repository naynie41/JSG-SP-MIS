import { useState } from 'react'
import { ShieldOff, Users } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Modal } from '@/components/Modal/Modal'
import { TextareaField } from '@/components/Field/TextareaField'
import { ApiError } from '@/types/api'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useRevokeGrant, useServiceGrants } from './hooks'
import type { Beneficiary, ServiceGrant } from './types'
import layout from '@/features/shared/formLayout.module.css'
import styles from './registry.module.css'

const when = (iso: string | null): string => {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

/**
 * Who outside the owner MDA can read this record, and the control to end it
 * (FR-OWN-07).
 *
 * An accepted Service Request opens a read grant that previously had no expiry and no
 * off switch — access, once given, stayed given. This is the off switch, and it is
 * placed on the owner's own record because that is where the person accountable for the
 * data is already looking.
 *
 * **Owner MDA only** (plus all-MDA oversight). The panel is not rendered for anyone
 * else: a serving MDA seeing the list would learn which *other* MDAs hold access to a
 * record it does not own. The server enforces the same boundary
 * (`BeneficiaryPolicy::viewGrants`), so this is presentation, not protection.
 *
 * Revoked grants stay listed. "Who could read this last month" is as much a part of the
 * owner's accountability as "who can read it today", and hiding withdrawn access would
 * quietly erase the history of an access episode.
 */
export function AccessGrantsPanel({ beneficiary }: { beneficiary: Beneficiary }) {
  const { user, hasPermission } = useAuth()

  const isOwner = user?.mda?.id != null && user.mda.id === beneficiary.owner_mda_id
  // An oversight role (cross-mda.view) legitimately sees this without owning the record.
  const canView = hasPermission('beneficiary.view') && (isOwner || hasPermission('cross-mda.view'))
  // Revoking is the owner's call — the approval permission that opened the grant.
  const canRevoke = (isOwner && hasPermission('beneficiary.approve')) || hasPermission('mda-access.edit')

  const { data: grants, isLoading } = useServiceGrants(beneficiary.id, canView)
  const revoke = useRevokeGrant(beneficiary.id)

  const [target, setTarget] = useState<ServiceGrant | null>(null)
  const [reason, setReason] = useState('')
  const [error, setError] = useState<string | null>(null)

  if (!canView) return null

  function open(grant: ServiceGrant) {
    setError(null)
    setReason('')
    setTarget(grant)
  }

  async function confirm() {
    if (!target) return
    setError(null)
    try {
      await revoke.mutateAsync({ grantId: target.id, reason: reason.trim() || undefined })
      setTarget(null)
    } catch (err) {
      setError(err instanceof ApiError ? err.message : 'Could not withdraw the access.')
    }
  }

  const rows = grants ?? []
  const active = rows.filter((g) => g.active)

  return (
    <Card title="Cross-MDA access" eyebrow="Sharing">
      <p className={styles.note}>
        MDAs that were granted read access to this record by an accepted request to serve. Access is
        read-only — it never allowed editing this profile, and it never moved ownership.
      </p>

      {isLoading && <p className={styles.cellSub}>Loading access…</p>}

      {!isLoading && rows.length === 0 && (
        <p className={styles.cellSub}>
          <Icon icon={Users} size={14} /> No other MDA has been granted access to this record.
        </p>
      )}

      {rows.length > 0 && (
        <ul className={styles.grantList}>
          {rows.map((grant) => (
            <li key={grant.id} className={styles.grantRow}>
              <div className={styles.cellStack}>
                <span>
                  <strong>{grant.mda?.name ?? 'Unknown MDA'}</strong>{' '}
                  {grant.active ? (
                    <Badge variant="success" dot>
                      Active
                    </Badge>
                  ) : (
                    <Badge variant="neutral">Withdrawn</Badge>
                  )}
                </span>
                <span className={styles.cellSub}>Granted {when(grant.granted_at)}</span>
                {/* The authority for the access, not decoration: it is what an auditor
                    follows back to the decision that opened it. */}
                <span className={styles.cellSub}>
                  Request <span className={styles.mono}>{grant.service_request_id}</span>
                </span>
                {!grant.active && (
                  <span className={styles.cellSub}>
                    Withdrawn {when(grant.revoked_at)}
                    {grant.revoked_by ? ` by ${grant.revoked_by}` : ''}
                    {grant.revocation_reason ? ` — ${grant.revocation_reason}` : ''}
                  </span>
                )}
              </div>

              {grant.active && canRevoke && (
                <Button size="sm" variant="danger" leftIcon={ShieldOff} onClick={() => open(grant)}>
                  Revoke access
                </Button>
              )}
            </li>
          ))}
        </ul>
      )}

      {active.length === 0 && rows.length > 0 && (
        <p className={styles.cellSub}>No MDA currently holds access to this record.</p>
      )}

      {/* Keyed per grant so the reason field never carries over between rows. */}
      {target !== null && (
        <Modal
          key={target.id}
          open
          onClose={() => setTarget(null)}
          title={`Withdraw ${target.mda?.name ?? 'this MDA'}’s access?`}
          footer={
            <>
              <Button variant="tertiary" onClick={() => setTarget(null)}>
                Cancel
              </Button>
              <Button variant="danger" onClick={confirm} loading={revoke.isPending}>
                Withdraw access
              </Button>
            </>
          }
        >
          {error && (
            <p className={layout.alert} role="alert">
              {error}
            </p>
          )}
          <p>
            {target.mda?.name ?? 'That MDA'} will no longer be able to read this record, enrol this
            person, or record new deliveries for them.
          </p>
          {/* States the boundary plainly, because "revoke" reads like an undo and is not one. */}
          <p className={styles.note}>
            Deliveries already recorded stay on the ledger, and ownership does not change. If they
            need access again, they must raise a new request to serve.
          </p>
          <TextareaField
            label="Reason"
            rows={3}
            value={reason}
            onChange={(e) => setReason(e.target.value)}
            helper="Optional. Recorded on the grant and in the audit trail."
          />
        </Modal>
      )}
    </Card>
  )
}
