import type { ReactNode } from 'react'
import { Bell, Menu } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { Avatar } from '@/components/Avatar/Avatar'
import styles from './TopBar.module.css'

export interface TopBarProps {
  /** Usually breadcrumbs. */
  left?: ReactNode
  userName: string
  userRole: string
  hasNotifications?: boolean
  onOpenMenu?: () => void
  onOpenUser?: () => void
}

/** Top bar (DESIGN-SYSTEM.md §5.6): breadcrumbs, notifications, user menu. */
export function TopBar({ left, userName, userRole, hasNotifications, onOpenMenu, onOpenUser }: TopBarProps) {
  return (
    <header className={styles.bar}>
      <div className={styles.left}>
        <button type="button" className={styles.menuButton} onClick={onOpenMenu} aria-label="Open navigation">
          <Icon icon={Menu} size={20} />
        </button>
        {left}
      </div>

      <div className={styles.right}>
        <button type="button" className={styles.iconButton} aria-label="Notifications">
          <Icon icon={Bell} size={20} />
          {hasNotifications && <span className={styles.dot} aria-hidden="true" />}
        </button>
        <button type="button" className={styles.user} onClick={onOpenUser}>
          <span className={styles.userMeta}>
            <span className={styles.userName}>{userName}</span>
            <br />
            <span className={styles.userRole}>{userRole}</span>
          </span>
          <Avatar name={userName} size="sm" />
        </button>
      </div>
    </header>
  )
}
