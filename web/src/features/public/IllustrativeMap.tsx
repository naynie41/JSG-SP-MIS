import styles from './landing.module.css'

/**
 * A decorative diagram of "many places, one system" — NOT a map of Jigawa.
 *
 * Deliberately abstract. The authenticated coverage map draws real traced LGA boundaries
 * shaded by real figures; this is a public page, and a recognisable outline shaded by
 * anything would read as data whether or not it was. So the shapes are an even lattice
 * that resembles no administrative geography, the shading is a fixed decorative pattern
 * repeated on a cycle, and there is nothing to click.
 *
 * If this ever needs to become a real map, it needs an authenticated route and a
 * cell-size guard, not a change of artwork.
 */

/** A fixed lattice — a rhythm, not a survey. Nothing here is derived from a figure. */
const NODES = Array.from({ length: 24 }, (_, i) => ({
  x: 40 + (i % 6) * 68,
  y: 34 + Math.floor(i / 6) * 62,
  // A repeating 3-step cycle, so the shading is visibly periodic rather than plausible —
  // and so the legend below accounts for every tone the diagram uses.
  tone: i % 3,
}))

export function IllustrativeMap() {
  return (
    <figure className={styles.mapFigure}>
      <svg
        className={styles.mapSvg}
        viewBox="0 0 440 260"
        role="img"
        aria-label="A decorative diagram of connected places across the state. It is illustrative and shows no data."
      >
        {/* Connecting lines first, so the nodes sit on top of them. */}
        <g stroke="var(--forest-2)" strokeWidth="1" opacity="0.35">
          {NODES.map((node, i) =>
            (i % 6) < 5 ? (
              <line key={`h${i}`} x1={node.x + 22} y1={node.y + 16} x2={node.x + 68} y2={node.y + 16} />
            ) : null,
          )}
          {NODES.map((node, i) =>
            i < 18 ? (
              <line key={`v${i}`} x1={node.x + 22} y1={node.y + 32} x2={node.x + 22} y2={node.y + 62} />
            ) : null,
          )}
        </g>

        {NODES.map((node, i) => (
          <rect
            key={i}
            x={node.x}
            y={node.y}
            width="44"
            height="32"
            rx="8"
            className={styles.mapNode}
            data-tone={node.tone}
          />
        ))}
      </svg>

      <ul className={styles.mapLegend}>
        <li>
          <span className={styles.mapSwatch} data-tone="0" /> Programme areas
        </li>
        <li>
          <span className={styles.mapSwatch} data-tone="2" /> Delivery activity
        </li>
        <li>
          <span className={styles.mapSwatch} data-tone="1" /> Coordination links
        </li>
      </ul>

      <figcaption className={styles.mapCaption}>
        Illustrative only. This diagram is decorative and shows no beneficiary, coverage or delivery data.
        Real coverage maps are available to authorised users after signing in.
      </figcaption>
    </figure>
  )
}
