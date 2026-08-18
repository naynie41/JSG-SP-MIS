import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { formatNaira } from '@/lib/utils/money'
import { REGISTRATION_SOURCE_LABELS, titleCase } from './constants'
import type { MatchReveal } from './types'
import styles from './registry.module.css'

interface MatchRevealPanelProps {
  reveal: MatchReveal
  /** Optional heading eyebrow — defaults to "Existing record". */
  eyebrow?: string
}

/**
 * One readable line about what this person already received.
 *
 * Deliberately says "delivered", never "spent" or "disbursed": this is the recorded
 * VALUE OF BENEFITS DELIVERED under a programme, not treasury expenditure.
 *
 * `total_value` null means the viewing MDA may not see monetary value — which is not
 * zero — so the value is simply omitted rather than shown as ₦0.
 */
function benefitsSummaryLine(summary: NonNullable<MatchReveal['benefits']['summary']>): string {
  const parts = [`${summary.count} ${summary.count === 1 ? 'delivery' : 'deliveries'}`]

  if (summary.total_value !== null) {
    parts.push(`${formatNaira(summary.total_value)} delivered`)
  }
  if (summary.types.length > 0) {
    parts.push(summary.types.map((t) => titleCase(t)).join(', '))
  }
  if (summary.last_delivery_date !== null) {
    parts.push(`last ${summary.last_delivery_date}`)
  }

  return parts.join(' · ')
}

/**
 * The cross-MDA match REVEAL (PRD FR-DUP-04): the minimal projection of an
 * existing record — name, owner MDA, source, registration date, LGA/Ward, status —
 * never the full profile. Programme + benefit sections are present-but-empty until
 * Phase 4. Shared by the import review and the standalone duplicate search so the
 * disclosed fields never diverge.
 */
export function MatchRevealPanel({ reveal, eyebrow = 'Existing record' }: MatchRevealPanelProps) {
  const source = REGISTRATION_SOURCE_LABELS[reveal.registration_source] ?? reveal.registration_source

  return (
    <div className={styles.reveal}>
      <div className={styles.revealHead}>
        <div className={styles.cellStack}>
          <span className="eyebrow">{eyebrow}</span>
          <span className={styles.revealName}>{reveal.full_name}</span>
        </div>
        <Badge variant={statusVariant(reveal.status)} dot>
          {reveal.status}
        </Badge>
      </div>

      <dl className={styles.dl}>
        <dt>Owner MDA</dt>
        <dd>{reveal.owner_mda?.name ?? '—'}</dd>
        <dt>Data source</dt>
        <dd>{source}</dd>
        <dt>Registered</dt>
        <dd>{reveal.registration_date ?? '—'}</dd>
        <dt>LGA / Ward</dt>
        <dd>
          {reveal.lga ? titleCase(reveal.lga) : '—'} · {reveal.ward ?? '—'}
        </dd>
      </dl>

      {/* Programme + benefit disclosure renders only once there is something to
          disclose. Empty "populates in Phase 4" placeholders occupied half this
          panel at the moment of maximum concentration and leaked internal
          roadmap vocabulary to officers; an absent section says more than an
          empty promise. */}
      {reveal.programmes.length > 0 && (
        <section className={styles.revealSection}>
          <span className="eyebrow">Programmes</span>
          <p>{reveal.programmes.length} recorded</p>
        </section>
      )}
      {/* Only when something was actually received. A count of zero is the same as no
          history for this decision, and an empty "Benefits received" section would add a
          line to read at the moment of maximum concentration. */}
      {reveal.benefits.summary && reveal.benefits.summary.count > 0 && (
        <section className={styles.revealSection}>
          <span className="eyebrow">Benefits received</span>
          <p>{benefitsSummaryLine(reveal.benefits.summary)}</p>
        </section>
      )}
    </div>
  )
}
