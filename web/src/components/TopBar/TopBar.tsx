import type { ReactNode } from 'react'
import { Bell, Menu, Settings } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { Avatar } from '@/components/Avatar/Avatar'
import styles from './TopBar.module.css'

export interface TopBarProps {
  /** Usually breadcrumbs. */
  left?: ReactNode
  userName: string
  userRole: string
  hasNotifications?: boolean
  /** Live notification control (bell + panel). Replaces the static bell when given. */
  notifications?: ReactNode
  onOpenMenu?: () => void
  onOpenUser?: () => void
  /** Settings (gear) affordance — an account/preferences page, never a nav section. */
  onOpenSettings?: () => void
  settingsLabel?: string
}

/** Top bar (DESIGN-SYSTEM.md §5.6): breadcrumbs, notifications, user menu. */
export function TopBar({
  left,
  userName,
  userRole,
  hasNotifications,
  notifications,
  onOpenMenu,
  onOpenUser,
  onOpenSettings,
  settingsLabel = 'Settings',
}: TopBarProps) {
  return (
    <header className={styles.bar}>
      <div className={styles.left}>
        <button type="button" className={styles.menuButton} onClick={onOpenMenu} aria-label="Open navigation">
          <Icon icon={Menu} size={20} />
        </button>
        {left}
      </div>

      <div className={styles.right}>
        {notifications ?? (
          <button type="button" className={styles.iconButton} aria-label="Notifications">
            <Icon icon={Bell} size={20} />
            {hasNotifications && <span className={styles.dot} aria-hidden="true" />}
          </button>
        )}
        {onOpenSettings && (
          <button type="button" className={styles.iconButton} onClick={onOpenSettings} aria-label={settingsLabel}>
            <Icon icon={Settings} size={20} />
          </button>
        )}
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
