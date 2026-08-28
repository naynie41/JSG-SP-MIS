import { useState } from 'react'
import type { ReactNode } from 'react'
import { useSearchParams } from 'react-router-dom'
import { HandHeart, Inbox, Plus, Send, ShieldCheck, Split } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Tabs } from '@/components/Tabs/Tabs'
import { TextField } from '@/components/Field/TextField'
import { useAuth } from '@/lib/auth/AuthProvider'
import { RecordBenefitPage } from '@/features/benefits/RecordBenefitPage'
import { AggregateTab, DeliveriesTab, FlagsTab } from '@/features/benefits/BenefitLedgerPage'
import { BenefitsPanel } from '@/features/benefits/BenefitsPanel'
import { ReferralTable } from '@/features/referrals/ReferralsPage'
import { RaiseReferralModal } from '@/features/referrals/RaiseReferralModal'
import { GrievanceDeskPage } from '@/features/grievances/GrievanceDeskPage'
import { ServiceRequestsPage } from '@/features/registry/ServiceRequestsPage'
import { useBeneficiaries } from '@/features/registry/hooks'
import { titleCase } from '@/features/registry/constants'
import type { Beneficiary } from '@/features/registry/types'
import { useMdaActionRequired } from './hooks'
import styles from './mda.module.css'

/** The groups, and the `?tab=` values the Overview's counters deep-link with. */
const TABS = ['benefits', 'referrals', 'grievances', 'service-requests'] as const
type TabId = (typeof TABS)[number]

const isTabId = (value: string | null): value is TabId => TABS.includes((value ?? '') as TabId)

/* ------------------------------------------------------------- queue wrapper */

/**
 * Wraps a composed table that represents work waiting on THIS MDA.
 *
 * The emphasis is the same warning accent the Overview's action cards use, so the two
 * screens speak with one voice: a tinted panel and a count mean "this needs you" in
 * both places. When the queue is empty the panel renders plain — a cleared queue must
 * not keep shouting.
 */
function Queue({
  icon,
  title,
  count,
  note,
  children,
}: {
  icon: LucideIcon
  title: string
  /** `null` while the live count is still in flight — renders no chip rather than a 0. */
  count: number | null
  note: string
  children: ReactNode
}) {
  const outstanding = count !== null && count > 0
  return (
    <section className={`${styles.queue} ${outstanding ? styles.queueActive : ''}`} aria-label={title}>
      <div className={styles.queueHead}>
        <Icon icon={icon} size={16} />
        <h3 className={styles.queueTitle}>{title}</h3>
        {outstanding && <span className={styles.queueCount}>{count} awaiting you</span>}
      </div>
      <p className={styles.queueNote}>{note}</p>
      <div className={styles.queueBody}>{children}</div>
    </section>
  )
}

/* ------------------------------------------------------- intervention history */

/**
 * A beneficiary's complete history ACROSS MDAs (FR-BEN-01/03) — the one benefits view
 * that is per-person rather than per-MDA, so it needs a subject before it can render.
 *
 * The search is glue over the existing `useBeneficiaries` hook; the history itself is
 * the existing {@link BenefitsPanel}, unchanged. That panel is what shows deliveries
 * from other MDAs too, which is the point of the view — it is how an officer sees that
 * someone has already been served elsewhere.
 */
function InterventionHistory() {
  const [query, setQuery] = useState('')
  const [subject, setSubject] = useState<Beneficiary | null>(null)
  const results = useBeneficiaries({ page: 1, search: query }, query.trim().length > 0 && !subject)

  if (subject) {
    return (
      <div className={styles.section}>
        <div className={styles.queueHead}>
          <h3 className={styles.queueTitle}>{subject.full_name}</h3>
          <Badge variant="neutral">{subject.lga ? titleCase(subject.lga) : '—'}</Badge>
          <Button size="sm" variant="tertiary" onClick={() => setSubject(null)}>
            Change beneficiary
          </Button>
        </div>
        <p className={styles.queueNote}>
          Every intervention recorded for this person, by any MDA. This is what makes duplicate support visible.
        </p>
        <BenefitsPanel beneficiaryId={subject.id} />
      </div>
    )
  }

  return (
    <Card>
      <TextField
        label="Find a beneficiary"
        placeholder="Search by name"
        value={query}
        onChange={(event) => setQuery(event.target.value)}
        helper="Your MDA's records, plus any you have been granted access to."
      />
      <div className={styles.activity}>
        {(results.data?.items ?? []).slice(0, 8).map((b) => (
          <div key={b.id} className={styles.activityRow}>
            <span className={styles.activityAction}>{b.full_name}</span>
            <span className={styles.activityMeta}>{b.lga ? titleCase(b.lga) : '—'}</span>
            <Button size="sm" variant="tertiary" onClick={() => setSubject(b)}>
              View history
            </Button>
          </div>
        ))}
      </div>
      {query.trim().length > 0 && (results.data?.items ?? []).length === 0 && !results.isLoading && (
        <p className={styles.muted}>No beneficiary matched that search.</p>
      )}
    </Card>
  )
}

/* -------------------------------------------------------------------- groups */

function BenefitsGroup() {
  const { hasPermission } = useAuth()
  const canRecord = hasPermission('benefit.create')
  const canVerify = hasPermission('benefit.approve')

  return (
    <Tabs
      items={[
        // The §8.3 flow verbatim. The serving-MDA gate it depends on is enforced by the
        // server (DeliveryAuthorization): the owner may always deliver, a non-owner only
        // with an accepted request-to-serve or referral. This page does not re-derive
        // that rule — it surfaces the server's refusal, which is the only authority.
        ...(canRecord
          ? [{ id: 'record', label: 'Record delivery', content: <RecordBenefitPage embedded /> }]
          : []),
        { id: 'delivered', label: 'Benefits delivered', content: <DeliveriesTab /> },
        { id: 'history', label: 'Intervention history', content: <InterventionHistory /> },
        { id: 'ledger', label: 'Benefit ledger', content: <AggregateTab /> },
        ...(canVerify
          ? [{ id: 'verification', label: 'Delivery verification', content: <FlagsTab /> }]
          : []),
      ]}
    />
  )
}

function ReferralsGroup({ pending }: { pending: number | null }) {
  const { hasPermission } = useAuth()
  const canCreate = hasPermission('referral.create')
  const [raising, setRaising] = useState(false)

  if (!hasPermission('referral.view')) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view referrals.</p>
      </Card>
    )
  }

  return (
    <div className={styles.section}>
      {canCreate && (
        <div>
          <Button leftIcon={Plus} onClick={() => setRaising(true)}>
            Raise referral
          </Button>
        </div>
      )}

      {/* Received first, and as a queue: an inbound referral is work, an outbound one
          is a record of work already handed off. */}
      <Queue
        icon={Inbox}
        title="Referrals received"
        count={pending}
        note="Another MDA referred a beneficiary to you. Open one to accept, reject, request more information, or drive it through to completion. It is the same lifecycle both parties see."
      >
        <ReferralTable direction="incoming" />
      </Queue>

      <section className={styles.section} aria-label="Referrals sent">
        <div className={styles.sectionHead}>
          <Icon icon={Send} size={16} />
          <h3 className={styles.queueTitle}>Referrals sent</h3>
        </div>
        <p className={styles.queueNote}>
          Referrals your MDA raised, with the receiving MDA&apos;s progress and any SLA breach. Referring never
          transfers ownership. The beneficiary stays yours throughout.
        </p>
        <ReferralTable direction="outgoing" />
      </section>

      <RaiseReferralModal open={raising} onClose={() => setRaising(false)} onCreated={() => setRaising(false)} />
    </div>
  )
}

/* ---------------------------------------------------------------------- page */

/**
 * Service Delivery — recording what was delivered, and the two-sided coordination
 * around it. Three groups, each composing screens that already exist:
 *
 *  - **Benefits** — {@link RecordBenefitPage} (§8.3), and the three ledger views
 *    ({@link DeliveriesTab}, {@link AggregateTab}, {@link FlagsTab}) split across
 *    "Benefits delivered", "Benefit ledger" and "Delivery verification", plus a
 *    per-person {@link BenefitsPanel} for cross-MDA intervention history.
 *  - **Referrals** — {@link ReferralTable} in both directions (Phase 5 lifecycle).
 *  - **Request-to-serve** — {@link ServiceRequestsPage}, whose approval inbox is the
 *    owner-MDA decision.
 *
 * **Action-required is directional.** Received referrals and INCOMING pending approvals
 * are work waiting on this MDA; sent referrals and our own raised requests are not. The
 * two counts shown here come from the same live `GET /mda/action-required` the Overview
 * uses, so the queue and the counter cannot disagree — and the decision mutations
 * invalidate that query, so clearing an item updates both.
 *
 * **Nothing here is a new endpoint or a new rule.** Ownership never transfers: a
 * referral routes a need and an accepted request-to-serve opens read access, both
 * leaving `owner_mda_id` untouched (FR-OWN-02). Approving is `beneficiary.approve` —
 * MDA Admin, not Officer — and the server enforces it on every decision route
 * regardless of what this page renders.
 */
export function MdaServiceDeliveryPage() {
  const { hasPermission } = useAuth()
  const canViewBenefits = hasPermission('benefit.view')
  const canViewGrievances = hasPermission('grievance.view')
  const [params, setParams] = useSearchParams()

  // Deep-linked from the Overview's action cards (`?tab=referrals`,
  // `?tab=service-requests`). Written back on change so the tab survives a reload and
  // can be shared.
  const requested = params.get('tab')
  const active: TabId = isTabId(requested) ? requested : 'benefits'

  const { data: actions, isLoading: actionsLoading } = useMdaActionRequired()
  const pendingReferrals = actionsLoading ? null : (actions?.pending_referrals ?? 0)
  const pendingApprovals = actionsLoading ? null : (actions?.pending_service_requests ?? 0)

  function selectTab(id: string) {
    const next = new URLSearchParams(params)
    next.set('tab', id)
    setParams(next, { replace: true })
  }

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Service Delivery</h1>
        <p className={styles.lead}>
          What your MDA delivered, and the coordination around it: referrals in both directions and request-to-serve
          decisions on the people you own. Items waiting on your MDA are marked as such.
        </p>
      </header>

      <Tabs
        activeId={active}
        onChange={selectTab}
        items={[
          {
            id: 'benefits',
            label: 'Benefits',
            content: canViewBenefits ? (
              <BenefitsGroup />
            ) : (
              <Card>
                <p className={styles.forbidden}>You do not have permission to view benefits.</p>
              </Card>
            ),
          },
          {
            id: 'referrals',
            label: 'Referrals',
            content: <ReferralsGroup pending={pendingReferrals} />,
          },
          {
            /*
             * Grievances belong to delivery: a complaint is about something this MDA did
             * or failed to do, and `grievances.handling_mda_id` is the MDA answering it.
             *
             * The MDA role holds `grievance.view`/`create`/`edit`, but the "Grievance
             * desk" rail item was removed when the consoles were restructured, and the
             * Coordination hub that inherited it is not on this rail — so an MDA had the
             * permission and no way to reach the feature. Composed here rather than
             * added as a seventh rail item, which would break the six-module design.
             */
            id: 'grievances',
            label: 'Grievances',
            content: canViewGrievances ? (
              <GrievanceDeskPage embedded />
            ) : (
              <Card>
                <p className={styles.forbidden}>You do not have permission to view grievances.</p>
              </Card>
            ),
          },
          {
            id: 'service-requests',
            label: 'Request to serve',
            content: (
              <div className={styles.section}>
                <Queue
                  icon={ShieldCheck}
                  title="Approvals awaiting your MDA"
                  count={pendingApprovals}
                  note="Another MDA has asked to serve a beneficiary you own. Accepting grants them READ access to the record and authorises delivery. Declining blocks it. Either way, ownership stays with you."
                >
                  <ServiceRequestsPage embedded />
                </Queue>
              </div>
            ),
          },
        ]}
      />

      <section className={styles.section} aria-label="About service delivery">
        <div className={styles.sectionHead}>
          <Icon icon={HandHeart} size={16} />
          <h2 className={styles.sectionTitle}>How delivery is authorised</h2>
        </div>
        <Card>
          <p className={styles.muted}>
            <Icon icon={Split} size={14} /> You may always record a delivery for a beneficiary your MDA owns. For
            anyone else, an accepted request-to-serve or an accepted referral is required. The server refuses the
            delivery otherwise, and no page can grant it.
          </p>
          <p className={styles.footnote}>
            A recorded delivery is programme data, not a treasury transaction
          </p>
        </Card>
      </section>
    </div>
  )
}
