import { Info } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Tabs } from '@/components/Tabs/Tabs'
import { useAuth } from '@/lib/auth/AuthProvider'
import { BeneficiaryListPage } from '@/features/registry/BeneficiaryListPage'
import { HouseholdListPage } from '@/features/registry/HouseholdListPage'
import { ImportListPage } from '@/features/registry/ImportListPage'
import styles from './mda.module.css'

/**
 * Beneficiaries — browse, import, correct. There is deliberately NO create path.
 *
 * Every record enters through an activity-bound upload (CLAUDE.md §9, FR-REG-10), so
 * this module offers three surfaces and no form:
 *
 *  - **Registry** — {@link BeneficiaryListPage}: the people this MDA owns, searchable
 *    and filterable, NIN/BVN masked by the server in every payload. A profile carries
 *    its provenance (source, owner MDA, batch, original record id) and, for the OWNER
 *    only, the correction affordance required by FR-OWN-02.
 *  - **Households** — {@link HouseholdListPage}: household groupings, formed by the
 *    source that supplied them rather than assembled here.
 *  - **Import Center** — {@link ImportListPage}: the SECONDARY upload path. Same
 *    endpoint, same validation, same duplicate cascade and same preview screen as the
 *    activity wizard's inline upload; the difference is only that the wizard creates
 *    the activity in the same step while this binds to one that already exists. The
 *    wizard remains the primary path.
 *
 * All three are `MdaScope`d server-side: another MDA's records are never returned, so
 * scoping does not depend on anything rendered here.
 */
export function MdaBeneficiariesPage() {
  const { hasPermission } = useAuth()
  const canViewRegistry = hasPermission('beneficiary.view')
  const canViewHouseholds = hasPermission('household.view')

  if (!canViewRegistry) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view the beneficiary registry.</p>
      </Card>
    )
  }

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Beneficiaries</h1>
        <p className={styles.lead}>
          The people and households your MDA owns, and the imports that brought them in. Records are never keyed in
          one at a time — every beneficiary arrives through a file uploaded against one of your activities.
        </p>
      </header>

      <Tabs
        items={[
          { id: 'registry', label: 'Registry', content: <BeneficiaryListPage embedded /> },
          ...(canViewHouseholds
            ? [{ id: 'households', label: 'Households', content: <HouseholdListPage embedded /> }]
            : []),
          { id: 'imports', label: 'Import Center', content: <ImportListPage /> },
        ]}
      />

      <Card>
        <p className={styles.muted}>
          <Icon icon={Info} size={14} /> There is no “add beneficiary” form. Bulk upload is the only way in, which is
          what lets every record carry its provenance and be checked for duplicates before it is saved. Corrections to
          an existing record are made by the MDA that owns it.
        </p>
        <p className={styles.footnote}>
          NIN and BVN are masked in every response; revealing them is a separate, audited permission
        </p>
      </Card>
    </div>
  )
}
