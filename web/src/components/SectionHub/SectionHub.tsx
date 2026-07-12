import { Link } from 'react-router-dom'
import { ArrowUpRight } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { cn } from '@/lib/utils/cn'
import type { StatTone } from '@/components/StatCard/StatCard'
import styles from './SectionHub.module.css'

export interface SectionHubItem {
  label: string
  description: string
  to: string
  icon: LucideIcon
  /** Soft icon-chip colour (calm, varied); defaults to forest. */
  tone?: StatTone
}

export interface SectionHubProps {
  eyebrow: string
  title: string
  lead?: string
  /** Already permission-filtered by the caller. */
  items: SectionHubItem[]
  /** Optional actions rendered in the header (e.g. a primary create button). */
  actions?: React.ReactNode
  /** Optional context metric band rendered between the header and the launcher grid. */
  metrics?: React.ReactNode
}

/**
 * Section "launcher" screen: a functional area presents its sub-tasks as a grid of
 * permission-aware cards instead of exploding them into the side rail. Keeps the
 * navbar to one link per area while surfacing everything on the area's own screen.
 */
export function SectionHub({ eyebrow, title, lead, items, actions, metrics }: SectionHubProps) {
  return (
    <div className={styles.page}>
      <header className={styles.head}>
        <div>
          <span className="eyebrow">{eyebrow}</span>
          <h1 className="t-h1">{title}</h1>
          {lead && <p className={styles.lead}>{lead}</p>}
        </div>
        {actions && <div className={styles.headActions}>{actions}</div>}
      </header>

      {metrics}

      {items.length === 0 ? (
        <p className={styles.empty}>You don’t have access to any tools in this area yet.</p>
      ) : (
        <div className={styles.grid}>
          {items.map((item, i) => (
            <Link
              key={item.to}
              to={item.to}
              className={styles.card}
              style={{ animationDelay: `${i * 45}ms` }}
            >
              <span className={cn(styles.chip, styles[item.tone ?? 'forest'])} aria-hidden="true">
                <Icon icon={item.icon} size={22} />
              </span>
              <span className={styles.body}>
                <span className={styles.cardTitle}>{item.label}</span>
                <span className={styles.cardDesc}>{item.description}</span>
              </span>
              <span className={styles.arrow} aria-hidden="true">
                <Icon icon={ArrowUpRight} size={16} />
              </span>
            </Link>
          ))}
        </div>
      )}
    </div>
  )
}
