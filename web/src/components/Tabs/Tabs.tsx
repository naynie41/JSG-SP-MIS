import { useId, useState } from 'react'
import type { ReactNode } from 'react'
import { cn } from '@/lib/utils/cn'
import styles from './Tabs.module.css'

export interface TabItem {
  id: string
  label: string
  content: ReactNode
  disabled?: boolean
}

export interface TabsProps {
  items: TabItem[]
  defaultTabId?: string
}

/** Tabs (DESIGN-SYSTEM.md §5.7) with roving keyboard nav and lime active bar. */
export function Tabs({ items, defaultTabId }: TabsProps) {
  const baseId = useId()
  const [active, setActive] = useState(defaultTabId ?? items[0]?.id)

  function onKeyDown(event: React.KeyboardEvent, index: number) {
    if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') return
    event.preventDefault()
    const dir = event.key === 'ArrowRight' ? 1 : -1
    // Move to the next ENABLED tab, wrapping around.
    let i = index
    for (let step = 0; step < items.length; step++) {
      i = (i + dir + items.length) % items.length
      if (!items[i]?.disabled) break
    }
    const next = items[i]
    if (next && next.id !== active) {
      setActive(next.id)
      // Focus follows activation (roving tabindex): keep the keyboard on the tab.
      requestAnimationFrame(() => document.getElementById(`${baseId}-tab-${next.id}`)?.focus())
    }
  }

  const activeItem = items.find((item) => item.id === active)

  return (
    <div>
      <div role="tablist" className={styles.tablist}>
        {items.map((item, index) => (
          <button
            key={item.id}
            id={`${baseId}-tab-${item.id}`}
            role="tab"
            type="button"
            aria-selected={item.id === active}
            aria-controls={`${baseId}-panel-${item.id}`}
            tabIndex={item.id === active ? 0 : -1}
            disabled={item.disabled}
            className={cn(styles.tab, item.id === active && styles.active)}
            onClick={() => setActive(item.id)}
            onKeyDown={(event) => onKeyDown(event, index)}
          >
            {item.label}
          </button>
        ))}
      </div>
      {activeItem && (
        <div
          id={`${baseId}-panel-${activeItem.id}`}
          role="tabpanel"
          aria-labelledby={`${baseId}-tab-${activeItem.id}`}
          className={styles.panel}
        >
          {activeItem.content}
        </div>
      )}
    </div>
  )
}
