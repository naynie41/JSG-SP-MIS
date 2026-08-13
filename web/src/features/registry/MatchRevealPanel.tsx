import { Badge } from '@/components/Badge/Badge'
import { statusVariant } from '@/components/Badge/statusVariant'
import { REGISTRATION_SOURCE_LABELS, titleCase } from './constants'
import type { MatchReveal } from './types'
import styles from './registry.module.css'

interface MatchRevealPanelProps {
  reveal: MatchReveal
  /** Optional heading eyebrow — defaults to "Existing record". */
  eyebrow?: string
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
      {reveal.benefits.summary && (
        <section className={styles.revealSection}>
          <span className="eyebrow">Benefits received</span>
          <p>{reveal.benefits.summary}</p>
        </section>
      )}
    </div>
  )
}
