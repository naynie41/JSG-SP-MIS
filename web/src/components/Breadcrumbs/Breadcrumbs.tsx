import { Link } from 'react-router-dom'
import { Fragment } from 'react'
import styles from './Breadcrumbs.module.css'

export interface Crumb {
  label: string
  to?: string
}

export interface BreadcrumbsProps {
  items: Crumb[]
}

/** Breadcrumbs with mono separators (DESIGN-SYSTEM.md §5.6). */
export function Breadcrumbs({ items }: BreadcrumbsProps) {
  return (
    <nav aria-label="Breadcrumb">
      <ol className={styles.crumbs}>
        {items.map((item, index) => {
          const isLast = index === items.length - 1
          return (
            <Fragment key={`${item.label}-${index}`}>
              <li className={styles.crumb}>
                {item.to && !isLast ? (
                  <Link to={item.to} className={styles.link}>
                    {item.label}
                  </Link>
                ) : (
                  <span className={styles.current} aria-current={isLast ? 'page' : undefined}>
                    {item.label}
                  </span>
                )}
              </li>
              {!isLast && (
                <li className={styles.sep} aria-hidden="true">
                  /
                </li>
              )}
            </Fragment>
          )
        })}
      </ol>
    </nav>
  )
}
