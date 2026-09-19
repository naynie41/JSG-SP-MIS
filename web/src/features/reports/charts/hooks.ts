import { useEffect, useRef, useState } from 'react'
import type { FocusEvent, PointerEvent, RefObject } from 'react'

/**
 * The rendered width of a chart's container, so SVG is drawn at real pixels. Scaling a
 * viewBox instead would stretch the text and the 2px lines along with the plot.
 */
export function useChartWidth(fallback = 600) {
  const ref = useRef<HTMLDivElement>(null)
  const [width, setWidth] = useState(fallback)

  useEffect(() => {
    const element = ref.current
    if (!element) return
    const measure = () => {
      const next = Math.round(element.getBoundingClientRect().width)
      if (next > 0) setWidth(next)
    }
    measure()
    if (typeof ResizeObserver === 'undefined') return
    const observer = new ResizeObserver(measure)
    observer.observe(element)
    return () => observer.disconnect()
  }, [])

  return { ref, width }
}

export interface TipRow {
  label: string
  value: string
  color?: string
}

export interface TipState {
  x: number
  y: number
  title: string
  rows: TipRow[]
}

/**
 * One tooltip per chart. Every mark binds to it; pointer and keyboard focus show the same
 * content, and the mark's accessible name carries it too, so the tooltip only ever adds
 * convenience — the value is also printed beside the mark and in the table view.
 */
export function useTooltip(container: RefObject<HTMLElement | null>) {
  const [tip, setTip] = useState<TipState | null>(null)

  const place = (clientX: number, clientY: number, content: Omit<TipState, 'x' | 'y'>) => {
    const box = container.current?.getBoundingClientRect()
    setTip({ x: clientX - (box?.left ?? 0), y: clientY - (box?.top ?? 0), ...content })
  }

  const bind = (content: Omit<TipState, 'x' | 'y'>) => ({
    tabIndex: 0,
    'aria-label': `${content.title}: ${content.rows.map((row) => `${row.value} ${row.label}`.trim()).join(', ')}`,
    onPointerMove: (event: PointerEvent<Element>) => place(event.clientX, event.clientY, content),
    onPointerLeave: () => setTip(null),
    onFocus: (event: FocusEvent<Element>) => {
      const rect = event.currentTarget.getBoundingClientRect()
      place(rect.left + rect.width / 2, rect.top, content)
    },
    onBlur: () => setTip(null),
  })

  return { tip, bind }
}
