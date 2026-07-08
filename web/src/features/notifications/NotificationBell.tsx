import { useEffect, useRef, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { Bell, CheckCheck } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { Toggle } from '@/components/Field/Toggle'
import {
  useMarkAllRead,
  useMarkNotificationRead,
  useNotificationPreferences,
  useNotifications,
  useUnreadCount,
  useUpdateNotificationPreferences,
} from './hooks'
import type { AppNotification } from './types'
import { linkFor } from './links'
import styles from './notifications.module.css'

function timeAgo(iso: string | null): string {
  if (!iso) return ''
  const diff = Date.now() - new Date(iso).getTime()
  const mins = Math.round(diff / 60000)
  if (mins < 1) return 'just now'
  if (mins < 60) return `${mins}m ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  return new Date(iso).toLocaleDateString(undefined, { month: 'short', day: 'numeric' })
}

/**
 * Notification bell (DESIGN-SYSTEM.md §5.6): unread count + a panel listing the
 * caller's notifications, with mark-read / mark-all-read, deep links to the related
 * referral / grievance / approval, and an email-preference toggle (FR-NOT-01/02).
 */
export function NotificationBell() {
  const [open, setOpen] = useState(false)
  const wrapRef = useRef<HTMLDivElement>(null)
  const navigate = useNavigate()

  const unread = useUnreadCount()
  const list = useNotifications(open)
  const prefs = useNotificationPreferences(open)
  const markRead = useMarkNotificationRead()
  const markAll = useMarkAllRead()
  const updatePrefs = useUpdateNotificationPreferences()

  const unreadCount = unread.data?.unread ?? 0
  const items = list.data?.items ?? []

  useEffect(() => {
    if (!open) return
    function onDocClick(event: MouseEvent) {
      if (!wrapRef.current?.contains(event.target as Node)) setOpen(false)
    }
    function onKey(event: KeyboardEvent) {
      if (event.key === 'Escape') setOpen(false)
    }
    document.addEventListener('mousedown', onDocClick)
    document.addEventListener('keydown', onKey)
    return () => {
      document.removeEventListener('mousedown', onDocClick)
      document.removeEventListener('keydown', onKey)
    }
  }, [open])

  function onSelect(notification: AppNotification) {
    if (notification.read_at === null) {
      markRead.mutate(notification.id)
    }
    const link = linkFor(notification)
    setOpen(false)
    if (link) navigate(link)
  }

  return (
    <div className={styles.wrap} ref={wrapRef}>
      <button
        type="button"
        className={styles.trigger}
        aria-haspopup="dialog"
        aria-expanded={open}
        aria-label={unreadCount > 0 ? `Notifications, ${unreadCount} unread` : 'Notifications'}
        onClick={() => setOpen((v) => !v)}
      >
        <Icon icon={Bell} size={20} />
        {unreadCount > 0 && <span className={styles.count}>{unreadCount > 99 ? '99+' : unreadCount}</span>}
      </button>

      {open && (
        <div className={styles.panel} role="dialog" aria-label="Notifications">
          <div className={styles.header}>
            <span className={styles.title}>Notifications</span>
            <button
              type="button"
              className={styles.markAll}
              disabled={unreadCount === 0 || markAll.isPending}
              onClick={() => markAll.mutate()}
            >
              <Icon icon={CheckCheck} size={14} /> Mark all read
            </button>
          </div>

          <ul className={styles.list}>
            {list.isLoading && <li className={styles.empty}>Loading…</li>}
            {!list.isLoading && items.length === 0 && <li className={styles.empty}>You’re all caught up.</li>}
            {items.map((n) => (
              <li key={n.id}>
                <button type="button" className={styles.item} onClick={() => onSelect(n)}>
                  <span className={n.read_at === null ? styles.unreadDot : styles.readDot} aria-hidden="true" />
                  <span>
                    <span className={styles.itemSubject}>{n.subject}</span>
                    <span className={styles.itemBody}>{n.body}</span>
                    <span className={styles.itemTime}>{timeAgo(n.created_at)}</span>
                  </span>
                </button>
              </li>
            ))}
          </ul>

          <div className={styles.footer}>
            <span>Email notifications</span>
            <Toggle
              label="Email notifications"
              hideLabel
              checked={prefs.data?.email_enabled ?? true}
              disabled={prefs.isLoading || updatePrefs.isPending}
              onChange={(e) => updatePrefs.mutate(e.target.checked)}
            />
          </div>
        </div>
      )}
    </div>
  )
}
