import { useState } from 'react'
import type { KeyboardEvent, PointerEvent } from 'react'
import type { TrendPoint } from '@/features/dashboard/types'
import { monthLong, monthShort, niceTicks } from './geometry'
import { useChartWidth } from './hooks'
import styles from './charts.module.css'

interface AreaTrendChartProps {
  /** Names the series, e.g. "New registrations". */
  label: string
  points: TrendPoint[]
  /** Full value, for the tooltip and the end label. */
  format: (value: number) => string
  /** Short value, for axis ticks. */
  axisFormat: (value: number) => string
}

const HEIGHT = 240
const PAD = { top: 24, right: 20, bottom: 30, left: 52 }

/**
 * One series over time: a 2px line over a 10% wash, hairline grid, the latest value
 * labelled at its end. A crosshair snaps to the nearest month under the pointer, and the
 * arrow keys walk it month by month for keyboard readers.
 *
 * One series at a time by design: registrations, beneficiaries and naira are different
 * scales, and plotting them together would need a second axis.
 */
export function AreaTrendChart({ label, points, format, axisFormat }: AreaTrendChartProps) {
  const { ref, width } = useChartWidth()
  const [active, setActive] = useState<number | null>(null)
  const series = points.slice(-12)

  if (series.length === 0 || series.every((point) => point.value === 0)) {
    return <p className={styles.empty}>Nothing recorded in this period yet.</p>
  }

  const innerWidth = Math.max(40, width - PAD.left - PAD.right)
  const innerHeight = HEIGHT - PAD.top - PAD.bottom
  const ticks = niceTicks(Math.max(...series.map((point) => point.value)))
  const top = ticks[ticks.length - 1] || 1
  const last = series.length - 1

  const x = (index: number) => PAD.left + (series.length <= 1 ? innerWidth / 2 : (index / last) * innerWidth)
  const y = (value: number) => PAD.top + innerHeight - (value / top) * innerHeight

  const line = series.map((point, index) => `${index === 0 ? 'M' : 'L'}${x(index).toFixed(1)},${y(point.value).toFixed(1)}`).join(' ')
  const area = `${line} L${x(last).toFixed(1)},${PAD.top + innerHeight} L${x(0).toFixed(1)},${PAD.top + innerHeight} Z`

  // Labels thin out when months would collide, rather than overlapping.
  const labelEvery = innerWidth / series.length < 36 ? 2 : 1

  const nearest = (event: PointerEvent<SVGSVGElement>) => {
    const box = event.currentTarget.getBoundingClientRect()
    const ratio = (event.clientX - box.left - PAD.left) / innerWidth
    setActive(Math.min(last, Math.max(0, Math.round(ratio * last))))
  }

  const onKeyDown = (event: KeyboardEvent<SVGSVGElement>) => {
    const current = active ?? last
    const next =
      event.key === 'ArrowLeft' ? current - 1 : event.key === 'ArrowRight' ? current + 1 : event.key === 'Home' ? 0 : event.key === 'End' ? last : null
    if (next === null) return
    event.preventDefault()
    setActive(Math.min(last, Math.max(0, next)))
  }

  const point = active !== null ? series[active] : null

  return (
    <div ref={ref} className={styles.plot}>
      <svg
        width={width}
        height={HEIGHT}
        className={styles.svg}
        role="img"
        tabIndex={0}
        aria-label={`${label} over the last ${series.length} months. Latest, ${monthLong(series[last].month)}: ${format(series[last].value)}. Use the arrow keys to read each month.`}
        onPointerMove={nearest}
        onPointerLeave={() => setActive(null)}
        onFocus={() => setActive(last)}
        onBlur={() => setActive(null)}
        onKeyDown={onKeyDown}
      >
        {ticks.map((tick) => (
          <g key={tick}>
            <line x1={PAD.left} x2={PAD.left + innerWidth} y1={y(tick)} y2={y(tick)} className={tick === 0 ? styles.axis : styles.grid} />
            <text x={PAD.left - 10} y={y(tick)} textAnchor="end" dominantBaseline="central" className={styles.axisText}>
              {axisFormat(tick)}
            </text>
          </g>
        ))}

        {series.map((entry, index) =>
          index % labelEvery === 0 || index === last ? (
            <text key={entry.month} x={x(index)} y={HEIGHT - 8} textAnchor="middle" className={styles.axisText}>
              {monthShort(entry.month)}
            </text>
          ) : null,
        )}

        <path d={area} className={styles.area} />
        <path d={line} className={styles.line} />

        <text x={x(last)} y={y(series[last].value) - 12} textAnchor="end" className={styles.valueText}>
          {format(series[last].value)}
        </text>
        <circle cx={x(last)} cy={y(series[last].value)} r={4} className={styles.dot} />

        {point && active !== null && (
          <g>
            <line x1={x(active)} x2={x(active)} y1={PAD.top} y2={PAD.top + innerHeight} className={styles.crosshair} />
            <circle cx={x(active)} cy={y(point.value)} r={5} className={styles.dot} />
          </g>
        )}
      </svg>

      {point && active !== null && (
        <div className={styles.tip} style={{ left: x(active), top: y(point.value) }} aria-hidden="true">
          <p className={styles.tipTitle}>{monthLong(point.month)}</p>
          <p className={styles.tipRow}>
            <span className={styles.tipKey} style={{ background: 'var(--chart-1)' }} />
            <strong className={styles.tipValue}>{format(point.value)}</strong>
            <span className={styles.tipLabel}>{label.toLowerCase()}</span>
          </p>
        </div>
      )}
    </div>
  )
}
