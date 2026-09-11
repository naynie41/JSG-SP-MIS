import styles from './brandMark.module.css'

export interface BrandMarkProps {
  /** Rendered size in px. The crest is square-padded, so this is both edges. */
  size?: number
  /**
   * Only set this where the crest stands alone. Beside the SP-MIS wordmark it is
   * decorative — announcing "Jigawa State coat of arms" there makes a screen reader
   * read the same identity twice.
   */
  alt?: string
  className?: string
}

/**
 * The Jigawa State coat of arms (DESIGN.md — brand).
 *
 * One component for every placement: the sidebar, the login panel, the public
 * header and the landing hero previously each rendered their own "SP" chip, so the
 * identity lived in three stylesheets and drifted independently.
 *
 * Deliberately NOT set on a lime chip like the badge it replaces. A crest carries
 * its own silhouette and its own colours — green map, red eagle, white horses — and
 * boxing that in an accent square makes two marks fight. It sits on whatever surface
 * hosts it; the white keyline in the artwork is what lets it read on forest.
 */
export function BrandMark({ size = 32, alt = '', className }: BrandMarkProps) {
  return (
    <img
      src="/brand/jigawa-logo.png"
      alt={alt}
      aria-hidden={alt === '' || undefined}
      width={size}
      height={size}
      className={className ? `${styles.mark} ${className}` : styles.mark}
      // Sizes below ~40px lose the crest detail and read as the state silhouette,
      // which is the intended behaviour at sidebar scale.
      decoding="async"
    />
  )
}
