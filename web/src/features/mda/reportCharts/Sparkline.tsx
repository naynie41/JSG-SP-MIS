import type { TrendPoint } from '@/features/dashboard/types'
import { useChartWidth } from './hooks'
import styles from './charts.module.css'

const HEIGHT = 40
const PAD = 5

/**
 * The last twelve months behind a headline figure: a 2px line, a faint wash and an
 * emphasised end. Decorative to assistive tech — the tile beside it states the figure and
 * what changed, and the full series is in the trend chart's table.
 */
export function Sparkline({ points, onDark = false }: { points: TrendPoint[]; onDark?: boolean }) {
  const { ref, width } = useChartWidth(180)
  const series = points.slice(-12)

  if (series.length < 2 || series.every((point) => point.value === 0)) return null

  const max = Math.max(...series.map((point) => point.value)) || 1
  const last = series.length - 1
  const x = (index: number) => PAD + (index / last) * (width - PAD * 2)
  const y = (value: number) => HEIGHT - PAD - (value / max) * (HEIGHT - PAD * 2)

  const line = series.map((point, index) => `${index === 0 ? 'M' : 'L'}${x(index).toFixed(1)},${y(point.value).toFixed(1)}`).join(' ')
  const area = `${line} L${x(last).toFixed(1)},${HEIGHT - PAD} L${x(0).toFixed(1)},${HEIGHT - PAD} Z`

  return (
    <div ref={ref} className={onDark ? `${styles.spark} ${styles.sparkOnDark}` : styles.spark} aria-hidden="true">
      <svg width={width} height={HEIGHT} className={styles.svg}>
        <path d={area} className={styles.sparkArea} />
        <path d={line} className={styles.sparkLine} />
        <circle cx={x(last)} cy={y(series[last].value)} r={3.5} className={styles.sparkDot} />
      </svg>
    </div>
  )
}
