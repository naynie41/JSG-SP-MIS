import { ArrowRight } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import type { DrillFn } from './types'
import styles from './executiveOverview.module.css'

export interface SectionFigure {
  label: string
  value: string
  /** A short qualifier under the value — the sentence the number belongs to. */
  hint?: string
  tone?: 'default' | 'warn' | 'bad'
}

export interface ExecutiveSectionCardProps {
  icon: LucideIcon
  title: string
  /** The question this card answers, in the executive's own words. */
  question: string
  figures: SectionFigure[]
  /** The tab that explains these figures. */
  tab: string
  linkLabel: string
  onDrill?: DrillFn
}

/**
 * One band of the executive overview: a question, at most four figures, and a way
 * through to the page that explains them.
 *
 * THE CAP IS THE DESIGN. Four figures, never five. This page's older form carried
 * three figures and a trend precisely because a screen of co-equal numbers is a
 * screen that must be studied, and an executive who has to study a dashboard asks
 * for the PDF instead — which is the manual-report problem the system replaced. The
 * cards are denser than that, on the owner's instruction, but density is bought with
 * hierarchy rather than spent on volume: a card answers ONE question, and anything
 * that does not help answer it belongs on the tab.
 *
 * The whole card is the link, not just the label. An executive reading "17.8% of
 * budget delivered" and wanting to know why should not have to find a small blue
 * word; the visible "View …" stays so the affordance is not a guess.
 */
export function ExecutiveSectionCard({
  icon,
  title,
  question,
  figures,
  tab,
  linkLabel,
  onDrill,
}: ExecutiveSectionCardProps) {
  const go = () => onDrill?.(tab, {})

  return (
    <article className={styles.sectionCard}>
      {/*
       * A button wrapping the heading rather than the <article>: making the whole
       * card one control would swallow the figures into its accessible name, which
       * reads as one long unpunctuated string to a screen reader. The click target
       * still covers the card through the ::after overlay in CSS.
       */}
      {/*
       * An explicit label, because the computed one runs the two spans together
       * ("DeliveryView programmes"). Spelling it out also makes the destination
       * unambiguous when a screen-reader user tabs through four of these cards and
       * hears only the link text.
       */}
      <button type="button" className={styles.sectionCardLink} onClick={go} aria-label={`${title} — ${linkLabel}`}>
        <span className={styles.sectionCardHead}>
          <Icon icon={icon} size={16} aria-hidden="true" />
          <span className={styles.sectionCardTitle}>{title}</span>
        </span>
        <span className={styles.sectionCardGo}>
          {linkLabel}
          <Icon icon={ArrowRight} size={14} aria-hidden="true" />
        </span>
      </button>

      <p className={styles.sectionCardQuestion}>{question}</p>

      <dl className={styles.sectionFigures} data-count={figures.length}>
        {figures.map((f) => (
          <div key={f.label} className={styles.sectionFigure} data-tone={f.tone ?? 'default'}>
            <dt className={styles.sectionFigureLabel}>{f.label}</dt>
            <dd className={styles.sectionFigureValue}>{f.value}</dd>
            {f.hint && <dd className={styles.sectionFigureHint}>{f.hint}</dd>}
          </div>
        ))}
      </dl>
    </article>
  )
}
