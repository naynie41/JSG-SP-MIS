import { Link } from 'react-router-dom'
import { ArrowUpRight, TriangleAlert } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { Spinner } from '@/components/Spinner/Spinner'
import { Icon } from '@/components/Icon/Icon'
import { isMdaRole } from '@/features/mda/roles'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useDashboard } from './hooks'
import { reportsPathFor, summariseReporting } from './reportingSummary'
import styles from './reportingSummary.module.css'

/**
 * Beyond this, the figures stop being "now" and start being history.
 *
 * Snapshots recompute every fifteen minutes on the scheduler, so a few hours of slack
 * absorbs a restart or a slow run. A day means the scheduler is not running, and the
 * reader needs to know that before quoting the number.
 */
const STALE_AFTER_HOURS = 24

function formatMoment(iso: string): string {
  const date = new Date(iso)
  if (Number.isNaN(date.getTime())) return 'unknown'
  return date.toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function hoursSince(iso: string): number | null {
  const date = new Date(iso)
  if (Number.isNaN(date.getTime())) return null
  return (Date.now() - date.getTime()) / 36e5
}

/**
 * The reporting dashboard, condensed to one card on the Overview (DESIGN.md §5.5).
 *
 * It reads `useDashboard()` — the SAME query the full dashboard renders, so React Query
 * serves both from one cache entry and the two cannot disagree. The figures are derived
 * by `summariseReporting()`, also shared, so "which number is right" is never a question
 * about which page you opened.
 *
 * The card owes the reader three things: the headline figure given more weight than the
 * ones supporting it, a visible way through to the detail, and — when the snapshot has
 * gone stale — a plain warning instead of a quiet timestamp. A figure presented as
 * current when it is weeks old is the one failure this card can cause on its own.
 */
export function ReportingSummaryCard() {
  const { user, hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const { data, isLoading } = useDashboard(undefined, canView)

  if (!canView) return null

  if (isLoading || !data) {
    return (
      <Card title="Reporting" titleAs="h2">
        <div className={styles.loading}>
          <Spinner size={20} />
        </div>
      </Card>
    )
  }

  const summary = summariseReporting(data)
  const reportsPath = reportsPathFor(user?.role?.key, isMdaRole(user?.role?.key))
  const age = hoursSince(summary.computedAt)
  const stale = age !== null && age > STALE_AFTER_HOURS

  // Net-unique beneficiaries is THE headline (CLAUDE.md §11) and carries the weight;
  // the rest support it. Six figures at one size is a list, not a summary.
  const [headline, ...supporting] = summary.tiles

  return (
    <Card
      title="Reporting"
      titleAs="h2"
      actions={
        <div className={styles.actions}>
          <Badge variant="neutral">{summary.scopeLabel}</Badge>
          {reportsPath && (
            // The expand affordance. A link, not a button: it is a navigation, so it
            // opens in a new tab on middle-click and shows its destination on hover.
            <Link to={reportsPath} className={styles.expand}>
              Open full dashboard
              <Icon icon={ArrowUpRight} size={16} />
            </Link>
          )}
        </div>
      }
    >
      <dl className={styles.figures}>
        {headline && (
          // dt before dd: a description list requires that order, and it is also how a
          // screen reader should hear it ("Net-unique beneficiaries, 62"). CSS lifts the
          // value above the label visually.
          <div className={`${styles.figure} ${styles.headline}`}>
            <dt className={styles.headlineLabel}>{headline.label}</dt>
            <dd className={headline.suppressed ? styles.headlineHeld : styles.headlineValue}>
              {headline.suppressed ? `< ${summary.minCellSize}` : headline.value?.toLocaleString()}
            </dd>
          </div>
        )}

        {supporting.map((tile) => (
          <div key={tile.key} className={styles.figure}>
            <dt className={styles.label}>{tile.label}</dt>
            <dd
              className={[
                tile.suppressed ? styles.valueHeld : styles.value,
                tile.value === 0 ? styles.valueZero : '',
              ]
                .filter(Boolean)
                .join(' ')}
            >
              {tile.suppressed ? `< ${summary.minCellSize}` : tile.value?.toLocaleString()}
            </dd>
          </div>
        ))}
      </dl>

      <div className={styles.footer}>
        {stale ? (
          <span className={styles.stale}>
            <Icon icon={TriangleAlert} size={15} />
            These figures were computed {formatMoment(summary.computedAt)} and have not
            refreshed since. Treat them as out of date.
          </span>
        ) : (
          <span>Last updated {formatMoment(summary.computedAt)}</span>
        )}

        {summary.suppressedCount > 0 && (
          <span>
            {summary.suppressedCount} figure{summary.suppressedCount === 1 ? '' : 's'} withheld —
            groups smaller than {summary.minCellSize} are not published.
          </span>
        )}
      </div>
    </Card>
  )
}
