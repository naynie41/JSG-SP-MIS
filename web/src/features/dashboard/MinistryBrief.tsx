import { Building2 } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import { num } from './executiveFormat'
import type { MinistryBriefRow } from './types'
import styles from './executiveOverview.module.css'

/**
 * The ministries doing the most, and what they are running.
 *
 * Three agencies, three programmes each, ranked by people reached. Ranked rather than
 * named: a fixed list would go stale the moment priorities move, and would be a
 * stakeholder decision frozen into code.
 *
 * Rows, not a figure grid. This page's whole argument is that an executive who has to
 * STUDY a dashboard asks for the PDF instead, so a brief that arrives as nine more
 * co-equal numbers defeats the page it was added to. Each line reads as a sentence:
 * this programme, this many people, this much money.
 *
 * On the numbers, two rules that the API guarantees and this component must not
 * undo by "helpfully" totalling anything:
 *
 *  - A ministry's reach is NOT the sum of its programmes' reach — one person on two
 *    programmes is one person to that ministry.
 *  - Reach is NOT addable across ministries either. The same person counts for Health
 *    and for Education, and once for the state. Only the state-wide headline, computed
 *    separately, may be read as a total.
 *
 * So nothing here is summed. Every figure is printed exactly as the server computed it.
 */
export function MinistryBrief({ ministries }: { ministries: MinistryBriefRow[] }) {
  if (ministries.length === 0) {
    return null
  }

  return (
    <section className={styles.reveal} style={{ animationDelay: '200ms' }} aria-labelledby="ministry-brief-heading">
      <div className={styles.sectionHead}>
        <Icon icon={Building2} size={18} aria-hidden="true" />
        <h2 className={styles.sectionTitle} id="ministry-brief-heading">
          Leading ministries
        </h2>
      </div>

      <div className={styles.ministryGrid}>
        {ministries.map((ministry) => (
          <article key={ministry.mda_id} className={styles.panel}>
            <h3 className={styles.ministryName}>{ministry.mda}</h3>
            <p className={styles.ministryReach}>
              <strong>{num(ministry.reached)}</strong> people reached
            </p>

            {ministry.programmes.length === 0 ? (
              <p className={styles.ministryEmpty}>No active programme has reached anyone yet.</p>
            ) : (
              <ol className={styles.ministryProgrammes}>
                {ministry.programmes.map((programme) => (
                  <li key={programme.programme_id} className={styles.ministryProgramme}>
                    <span className={styles.ministryProgrammeName}>{programme.name}</span>
                    <span className={styles.ministryProgrammeFigures}>
                      {num(programme.reached)} reached · {formatNaira(programme.allocated)} allocated
                    </span>
                  </li>
                ))}
              </ol>
            )}
          </article>
        ))}
      </div>
    </section>
  )
}
