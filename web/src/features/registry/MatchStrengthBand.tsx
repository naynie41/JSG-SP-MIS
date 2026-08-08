import styles from './registry.module.css'

interface MatchStrengthBandProps {
  score: number
  thresholds?: { review: number; auto_accept: number | null } | null
  /** Deterministic identifier hits are definitive — no band applies. */
  deterministic?: boolean
}

const pct = (n: number) => `${Math.min(100, Math.max(0, n * 100))}%`

/**
 * Match strength as a position between the configured thresholds, not a bare
 * decimal.
 *
 * A raw "0.87" is a model artefact: it invites deference to the number and
 * discourages reading the fields, which is the actual evidence. Showing where
 * the score sits relative to the admin-configured review and auto-accept
 * thresholds keeps the configuration meaningful while leaving the judgement to
 * the officer (FR-DUP-02/03/09). The exact score still reaches the audit log.
 */
export function MatchStrengthBand({ score, thresholds, deterministic }: MatchStrengthBandProps) {
  if (deterministic) {
    return (
      <p className={styles.bandDefinitive}>
        Matched on a unique identifier — definitively the same person.
      </p>
    )
  }

  if (!thresholds) {
    // No active configuration: say so rather than implying a calibrated verdict.
    return <p className={styles.note}>Match strength cannot be placed — no active matching configuration.</p>
  }

  const { review, auto_accept: autoAccept } = thresholds
  const aboveReview = score >= review
  const aboveAuto = autoAccept != null && score >= autoAccept

  const verdict = aboveAuto
    ? 'Above the auto-accept threshold'
    : aboveReview
      ? 'Above the review threshold — needs your judgement'
      : 'Below the review threshold'

  return (
    <div className={styles.band}>
      <div
        className={styles.bandTrack}
        role="img"
        aria-label={`Match strength ${verdict.toLowerCase()}. Review threshold ${review.toFixed(2)}${
          autoAccept != null ? `, auto-accept ${autoAccept.toFixed(2)}` : ''
        }.`}
      >
        <span className={styles.bandMark} style={{ left: pct(review) }} data-label="review" />
        {autoAccept != null && (
          <span className={styles.bandMark} style={{ left: pct(autoAccept) }} data-label="auto" />
        )}
        <span className={styles.bandPin} style={{ left: pct(score) }} />
      </div>
      <div className={styles.bandLegend}>
        <span>review {review.toFixed(2)}</span>
        {autoAccept != null && <span>auto-accept {autoAccept.toFixed(2)}</span>}
      </div>
      <p className={styles.bandVerdict}>{verdict}</p>
    </div>
  )
}
