import type { CoverageBand, CoverageFeatureProperties } from '@/features/gis/types'
import styles from './coverageHoverCard.module.css'

/**
 * What a viewer learns by pointing at an LGA on the coverage map.
 *
 * Pinned inside the map rather than floating on the pointer. A Leaflet tooltip is
 * clipped by the map's own `overflow: hidden`, so a card this tall lost its heading and
 * half its figures on every boundary near the top edge — and the boundaries near the top
 * edge are Jigawa's northern LGAs, not an edge case. Pinned, it never clips, it sits in
 * the same place every time so the eye does not hunt, and the pointer stays free to
 * trace borders.
 *
 * Every figure comes straight from `/gis/coverage` for that area. Nothing is derived:
 * there is no coverage percentage, no population denominator, no utilisation rate,
 * because the system does not hold those and a card under the cursor is exactly where an
 * invented ratio would look authoritative.
 */

const BAND_LABEL: Record<CoverageBand, string> = {
  green: 'Well covered',
  yellow: 'Partial',
  red: 'Low',
  grey: 'No coverage recorded',
}

const count = (n: number) => n.toLocaleString()

/**
 * Kobo → naira. This is the recorded VALUE OF BENEFITS DELIVERED — SP-MIS records
 * delivery and does not move money, so it is never "spent" or "disbursed".
 */
const naira = (kobo: number) => `₦${Math.round(kobo / 100).toLocaleString()}`

/**
 * "an LGA", "a Ward". Keyed on the initial SOUND, not the letter — "LGA" is read
 * "el-gee-ay". Matches the article the area-detail panel already uses.
 */
const article = (word: string) => (/^[aeiouAEFHILMNORSX]/.test(word) ? 'an' : 'a')

function Stat({ label, value }: { label: string; value: string }) {
  return (
    <div className={styles.stat}>
      <dt>{label}</dt>
      <dd>{value}</dd>
    </div>
  )
}

export interface CoverageHoverCardProps {
  area: CoverageFeatureProperties | null
  /** "LGA" or "Ward" — the prompt names what the viewer should point at. */
  areaWord: string
  /**
   * The figure the map is currently coloured BY, led so the card explains the shade the
   * viewer is looking at before the standing figures below it.
   */
  lead?: { label: string; value: string } | null
}

export function CoverageHoverCard({ area, areaWord, lead = null }: CoverageHoverCardProps) {
  if (area === null) {
    return (
      <div className={styles.card} data-empty="true" aria-hidden="true">
        <p className={styles.prompt}>Point at {article(areaWord)} {areaWord} to see its coverage.</p>
      </div>
    )
  }

  // Announced politely: the map is a supporting view, and a live region that fires on
  // every pointer move would talk over the page.
  const nothingRecorded = area.beneficiary_count === 0 && area.served === 0 && area.benefit_count === 0

  return (
    <div className={styles.card} role="status" aria-live="polite">
      <header className={styles.head}>
        <span className={styles.dot} data-band={area.band} />
        <span className={styles.name}>{area.name}</span>
        <span className={styles.band}>{BAND_LABEL[area.band] ?? BAND_LABEL.grey}</span>
      </header>

      {nothingRecorded ? (
        // "Nothing recorded" and "we measured zero" are different claims, and only the
        // first one is true here.
        <p className={styles.prompt}>No beneficiaries or deliveries recorded here in the current scope.</p>
      ) : (
        <>
          {lead && (
            <dl className={styles.lead}>
              <dt>{lead.label}</dt>
              <dd>{lead.value}</dd>
            </dl>
          )}
          <dl className={styles.stats}>
            <Stat label="Beneficiaries" value={count(area.beneficiary_count)} />
            <Stat label="Households" value={count(area.households)} />
            {/* Net-unique is the headline figure everywhere else in the product. */}
            <Stat label="Net-unique served" value={count(area.served)} />
            <Stat label="Deliveries" value={count(area.benefit_count)} />
            <Stat label="Delivered value" value={naira(area.benefit_value)} />
            <Stat
              label="Programmes"
              value={`${count(area.active_programmes)} · ${count(area.active_activities)} activities`}
            />
          </dl>
          {area.mdas.length > 0 && (
            <footer className={styles.mdas}>
              <span className={styles.mdaLabel}>Delivered by</span>
              {/* Two names, then a count — the full list is a click away in the detail panel. */}
              {area.mdas.length <= 2
                ? area.mdas.join(', ')
                : `${area.mdas.slice(0, 2).join(', ')} +${area.mdas.length - 2}`}
            </footer>
          )}
        </>
      )}
    </div>
  )
}
