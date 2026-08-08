import { Hammer } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import styles from './mda.module.css'

/**
 * Placeholders for the five modules that follow this step. Each names the EXISTING
 * screens it will compose, so the scaffold documents intent rather than pretending to
 * be finished. No module reimplements a feature — they surface what already exists
 * across Phases 2–6.
 *
 * They exist now so the Overview's deep-links and Quick Actions resolve to a real
 * route instead of a 404.
 */

interface ScaffoldProps {
  title: string
  note: string
  composes: string[]
}

function Scaffold({ title, note, composes }: ScaffoldProps) {
  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>{title}</h1>
      </header>
      <div className={styles.scaffold}>
        <Icon icon={Hammer} size={22} />
        <p className={styles.scaffoldTitle}>Coming in a later step</p>
        <p className={styles.scaffoldNote}>{note}</p>
        <div className={styles.scaffoldList}>
          {composes.map((c) => (
            <span key={c} className={styles.scaffoldChip}>
              {c}
            </span>
          ))}
        </div>
      </div>
    </div>
  )
}



export function MdaServiceDeliveryPage() {
  return (
    <Scaffold
      title="Service Delivery"
      note="Recording what was delivered, and the two-sided coordination around it: benefit ledger, referrals, and request-to-serve approvals. The Overview's action-required counters deep-link here."
      composes={['RecordBenefitPage', 'BulkDeliveryPage', 'BenefitLedgerPage', 'ReferralsPage', 'ServiceRequestsPage']}
    />
  )
}

export function MdaDuplicateResolutionPage() {
  return (
    <Scaffold
      title="Duplicate Resolution"
      note="Adjudicating probable matches from an import, searching the registry before serving, and raising a request-to-serve. Exact matches are definitive and never adjudicated (CLAUDE.md §9)."
      composes={['AdjudicateQueuePage', 'DuplicateSearchPage', 'ServiceRequestsPage']}
    />
  )
}

export function MdaReportsPage() {
  return (
    <Scaffold
      title="Reports"
      note="Your MDA's reporting over the Phase 6 engine — the standard catalogue, the ad-hoc builder and the coverage map, all scoped to your MDA and honouring the export matrix."
      composes={['/reports/catalogue', '/reports/adhoc', 'GisDashboardPage']}
    />
  )
}

export function MdaSettingsPage() {
  return (
    <Scaffold
      title="Settings"
      note="Your account and workspace preferences — profile, password, MFA enrolment and notification preferences. Opened from the gear affordance, not the navigation rail. Content lands in MDA.7."
      composes={['/auth/me', '/auth/password', '/auth/mfa', '/notifications/preferences']}
    />
  )
}
