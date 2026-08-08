import { useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { ArrowLeft, CalendarRange, Eye, Plus, Target, Wallet } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import { ActivityFormModal } from '@/features/programmes/ActivityFormModal'
import { useActivities, useProgramme } from '@/features/programmes/hooks'
import type { Activity } from '@/features/programmes/types'
import styles from './mda.module.css'

const titleCase = (s: string | null | undefined): string =>
  !s ? '—' : s.replace(/[_-]/g, ' ').replace(/^./, (c) => c.toUpperCase())

/** Budget is stored in minor units (kobo). */
const naira = (kobo: number | null | undefined): string =>
  kobo === null || kobo === undefined ? '—' : `₦${(kobo / 100).toLocaleString(undefined, { minimumFractionDigits: 2 })}`

/**
 * A start/end date is a CALENDAR date, not an instant. `new Date('2026-01-01')` parses
 * as UTC midnight and then renders in local time, so anyone behind UTC sees
 * "31 Dec 2025" for an activity that starts on 1 January. Appending a local time
 * pins it to the day the API actually sent.
 */
const day = (iso: string | null): string => {
  if (!iso) return '—'
  const d = new Date(`${iso}T00:00:00`)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' })
}

/** Start → end as one readable span; the activity's timeline in a single cell. */
function timeline(a: Activity): string {
  if (!a.starts_on && !a.ends_on) return 'Not scheduled'
  return `${day(a.starts_on)} → ${day(a.ends_on)}`
}

/**
 * A catalog programme seen from inside ONE MDA: the programme's own (read-only)
 * definition, and beneath it the activities THIS MDA runs under it.
 *
 * Everything MDA-specific — timeline, budget, funding source, target beneficiaries,
 * status — lives on the activity, never on the shared programme (CLAUDE.md §10). The
 * activity list is `MdaScope`d server-side, so another MDA's activities under the same
 * programme are not merely hidden here, they are never returned.
 *
 * Create Activity opens the EXISTING {@link ActivityFormModal} with the programme
 * pinned — the same wizard the Overview's Quick Action launches, including the
 * involves-beneficiaries branch and its mandatory upload. One flow, two entry points.
 */
export function MdaProgrammeDetailPage() {
  const { id } = useParams<{ id: string }>()
  const { hasPermission } = useAuth()
  const navigate = useNavigate()

  const canView = hasPermission('programme.view')
  const canViewActivities = hasPermission('activity.view')
  const canCreate = hasPermission('activity.create')

  const { data: programme, isLoading } = useProgramme(id, canView)
  const { data: activities, isLoading: activitiesLoading } = useActivities(id, canViewActivities)
  const [wizardOpen, setWizardOpen] = useState(false)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view programmes.</p>
      </Card>
    )
  }

  if (isLoading || !programme) {
    return (
      <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
        <Spinner size={26} label="Loading programme" />
      </div>
    )
  }

  const rows: Activity[] = activities?.items ?? []
  const withTarget = rows.filter((a) => a.involves_beneficiaries)
  const totalTarget = withTarget.reduce((sum, a) => sum + (a.target_beneficiaries ?? 0), 0)
  const totalBudget = rows.reduce((sum, a) => sum + (a.budget_amount ?? 0), 0)
  const activeCount = rows.filter((a) => a.status === 'active').length

  const columns: Column<Activity>[] = [
    { key: 'name', header: 'Activity', render: (a) => <Link to={`/activities/${a.id}`}>{a.name}</Link> },
    { key: 'timeline', header: 'Timeline', render: (a) => timeline(a) },
    { key: 'location', header: 'Location', render: (a) => titleCase(a.lga) },
    {
      key: 'target',
      header: 'Target',
      align: 'right',
      // An activity that does not involve beneficiaries has no target by design
      // (§10) — an em dash, never a misleading zero.
      render: (a) => (a.involves_beneficiaries ? (a.target_beneficiaries ?? 0).toLocaleString() : '—'),
    },
    { key: 'budget', header: 'Budget', align: 'right', render: (a) => naira(a.budget_amount) },
    { key: 'funding', header: 'Funding', render: (a) => titleCase(a.funding_source) },
    {
      key: 'status',
      header: 'Status',
      render: (a) => (
        <Badge variant={statusVariant(a.status)} dot>
          {a.status}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (a) => (
        <Button size="sm" variant="tertiary" leftIcon={Eye} onClick={() => navigate(`/activities/${a.id}`)}>
          View
        </Button>
      ),
    },
  ]

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace · programme</span>
        <h1 className={styles.pageTitle}>{programme.name}</h1>
        <p className={styles.lead}>{programme.objective ?? 'No objective recorded for this catalogue programme.'}</p>
        <div className={styles.choiceRow}>
          <Badge variant="neutral">{titleCase(programme.type)}</Badge>
          {programme.benefit_category && <Badge variant="neutral">{titleCase(programme.benefit_category)}</Badge>}
          <Badge variant={statusVariant(programme.status)} dot>
            {programme.status}
          </Badge>
        </div>
      </header>

      <div className={styles.actionBar}>
        <button type="button" className={styles.action} onClick={() => navigate('/mda/programmes')}>
          <Icon icon={ArrowLeft} size={15} />
          All programmes
        </button>
        {canCreate && (
          <button type="button" className={styles.action} onClick={() => setWizardOpen(true)}>
            <Icon icon={Plus} size={15} />
            Create activity
          </button>
        )}
      </div>

      {/* Roll-up of the MDA's own activities under this programme. Derived from the
          rows below, so the figures and the table can never disagree. Named so it is
          addressable as a region rather than three loose numbers. */}
      <section className={styles.kpiBand} aria-label="Your delivery under this programme">
        <div className={styles.kpi}>
          <span className={styles.kpiLabel}>
            <Icon icon={CalendarRange} size={13} />
            Your activities
          </span>
          <span className={styles.kpiValue}>{rows.length.toLocaleString()}</span>
          <span className={styles.kpiHint}>{activeCount} active</span>
        </div>
        <div className={styles.kpi}>
          <span className={styles.kpiLabel}>
            <Icon icon={Target} size={13} />
            Target beneficiaries
          </span>
          <span className={styles.kpiValue}>{totalTarget.toLocaleString()}</span>
          <span className={styles.kpiHint}>
            across {withTarget.length} {withTarget.length === 1 ? 'activity' : 'activities'} with beneficiaries
          </span>
        </div>
        <div className={styles.kpi}>
          <span className={styles.kpiLabel}>
            <Icon icon={Wallet} size={13} />
            Allocated budget
          </span>
          <span className={styles.kpiValue}>{naira(totalBudget)}</span>
          <span className={styles.kpiHint}>your activities only</span>
        </div>
      </section>

      <Card flush>
        <DataTable
          caption={`Your activities under ${programme.name}`}
          rows={rows}
          columns={columns}
          getRowId={(a) => a.id}
          getRowLabel={(a) => a.name}
          loading={activitiesLoading}
          emptyTitle="No activities under this programme yet"
          emptyAction={
            canCreate ? (
              <Button size="sm" leftIcon={Plus} onClick={() => setWizardOpen(true)}>
                Create activity
              </Button>
            ) : undefined
          }
        />
      </Card>

      <p className={styles.footnote}>
        Open an activity to see target versus actual, the beneficiaries and interventions recorded under it, its
        import summary, and any request-to-serve awaiting a decision
      </p>

      {/* The SAME wizard as the Overview's Quick Action, with the programme fixed —
          including the involves-beneficiaries branch and its mandatory, deduplicated
          upload. There is no second creation path. */}
      <ActivityFormModal open={wizardOpen} onClose={() => setWizardOpen(false)} programmeId={programme.id} />
    </div>
  )
}
