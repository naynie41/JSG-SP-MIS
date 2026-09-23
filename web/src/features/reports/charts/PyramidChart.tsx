import { useRef } from 'react'
import { formatCount, isHeld } from '../counts'
import { ChartTooltip } from './ChartCard'
import { useTooltip } from './hooks'
import styles from './charts.module.css'

export interface PyramidBand {
  key: string
  /** The age range as it is read, e.g. "18–34" or "60+". */
  band: string
  female: number
  male: number
}

interface PyramidChartProps {
  label: string
  bands: PyramidBand[]
  femaleColor: string
  maleColor: string
  minimum?: number | null
  emptyText: string
}

/**
 * A population pyramid: age bands down the middle, women left, men right.
 *
 * It replaced a donut of the gender split, which could only ever say one number — the
 * share of women — and said it in the least precise shape available. The pyramid says
 * that and the age structure at once, and the outline itself carries the finding: a
 * register skewed young, or skewed female, is visible before a single figure is read.
 *
 * Two decisions worth keeping:
 *
 *  - **Bars are scaled to the largest single bar**, not to each side's own total.
 *    Scaling per side would draw both wings the same width whatever the split, hiding
 *    the one thing a reader takes from this chart at a glance.
 *  - **Percentages are of the charted population** — everyone who appears here — so the
 *    two sides together make 100%. The caller states how many people that excludes;
 *    anyone without a recorded date of birth cannot be placed on an age axis at all.
 *
 * Only two series, because a pyramid has two wings. Other and unrecorded genders are
 * real and belong in the table view, not at an invented position on the axis.
 */
export function PyramidChart({
  label,
  bands,
  femaleColor,
  maleColor,
  minimum = null,
  emptyText,
}: PyramidChartProps) {
  const container = useRef<HTMLDivElement>(null)
  const { tip, bind } = useTooltip(container)

  const drawn = bands.filter((b) => b.female > 0 || b.male > 0)
  const charted = drawn.reduce((sum, b) => sum + b.female + b.male, 0)
  const peak = drawn.reduce((max, b) => Math.max(max, b.female, b.male), 0)

  if (drawn.length === 0 || charted === 0 || peak === 0) {
    return <p className={styles.empty}>{emptyText}</p>
  }

  const share = (n: number) => `${Math.round((n / charted) * 1000) / 10}%`

  const side = (b: PyramidBand, which: 'female' | 'male') => {
    const value = which === 'female' ? b.female : b.male
    const color = which === 'female' ? femaleColor : maleColor
    const held = isHeld(value, minimum)

    return (
      <span
        className={which === 'female' ? styles.pyramidWingLeft : styles.pyramidWingRight}
        {...bind({
          title: `${which === 'female' ? 'Women' : 'Men'} · ${b.band}`,
          rows: [
            {
              value: formatCount(value, minimum),
              label: held ? 'group too small to publish' : `people · ${share(value)} of those shown`,
              color,
            },
          ],
        })}
      >
        <span className={styles.pyramidValue}>{held ? '—' : share(value)}</span>
        <span
          className={`${styles.pyramidBar} ${styles.mark}`}
          // Width is the only thing computed inline: it is data, and there is no
          // class that could carry it.
          style={{ width: `${Math.max(value > 0 ? 2 : 0, (value / peak) * 100)}%`, background: color }}
        />
      </span>
    )
  }

  const femaleTotal = drawn.reduce((sum, b) => sum + b.female, 0)
  const maleTotal = drawn.reduce((sum, b) => sum + b.male, 0)

  return (
    <div ref={container} className={styles.plot}>
      {/* The legend is not decoration. Two wings distinguished only by colour would
          carry identity in colour alone, which fails for a colourblind reader, in
          greyscale and on a printout. Each side is named, and each bar is labelled. */}
      <div className={styles.pyramidLegend}>
        <span className={styles.pyramidLegendItem}>
          <span className={styles.pyramidSwatch} style={{ background: femaleColor }} aria-hidden="true" />
          Female
          <strong>
            {formatCount(femaleTotal, minimum)} · {share(femaleTotal)}
          </strong>
        </span>
        <span className={styles.pyramidLegendItem}>
          <strong>
            {formatCount(maleTotal, minimum)} · {share(maleTotal)}
          </strong>
          Male
          <span className={styles.pyramidSwatch} style={{ background: maleColor }} aria-hidden="true" />
        </span>
      </div>

      <div className={styles.pyramid} role="group" aria-label={label}>
        {drawn.map((b) => (
          <div key={b.key} className={styles.pyramidRow}>
            {side(b, 'female')}
            <span className={styles.pyramidBand}>{b.band}</span>
            {side(b, 'male')}
          </div>
        ))}
      </div>
      <ChartTooltip tip={tip} />
    </div>
  )
}
