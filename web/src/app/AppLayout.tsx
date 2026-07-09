import { useMemo, useState } from 'react'
import { Outlet, useLocation, useNavigate } from 'react-router-dom'
import { LogOut } from 'lucide-react'
import { SideNav } from '@/components/SideNav/SideNav'
import type { NavSection } from '@/components/SideNav/SideNav'
import { TopBar } from '@/components/TopBar/TopBar'
import { Breadcrumbs } from '@/components/Breadcrumbs/Breadcrumbs'
import { Icon } from '@/components/Icon/Icon'
import { NotificationBell } from '@/features/notifications/NotificationBell'
import { useAuth } from '@/lib/auth/AuthProvider'
import { NAV_CONFIG } from './nav'
import styles from './AppLayout.module.css'

/** Authenticated shell: forest rail (permission-filtered) + top bar + content. */
export function AppLayout() {
  const { user, logout, hasPermission } = useAuth()
  const navigate = useNavigate()
  const location = useLocation()
  const [drawerOpen, setDrawerOpen] = useState(false)

  const roleKey = user?.role?.key ?? ''

  // Filter nav by role (whole section) then permission (item); drop empty sections.
  // Visibility is UX-only — the server is the security boundary.
  const sections: NavSection[] = useMemo(() => {
    return NAV_CONFIG.filter((section) => !section.roles || section.roles.includes(roleKey))
      .map((section) => ({
        label: section.label,
        items: section.items
          .filter((item) => !item.permission || hasPermission(item.permission))
          .map(({ label, to, icon }) => ({ label, to, icon })),
      }))
      .filter((section) => section.items.length > 0)
  }, [hasPermission, roleKey])

  const currentLabel = useMemo(() => {
    if (location.pathname === '/') return 'Dashboard'
    for (const section of NAV_CONFIG) {
      const match = section.items.find(
        (item) => item.to !== '/' && location.pathname.startsWith(item.to),
      )
      if (match) return match.label
    }
    // Functional pages reached from a hub aren't in the rail — title-case the first segment.
    const segment = location.pathname.split('/').filter(Boolean)[0]
    if (!segment) return 'SP-MIS'
    return segment.replace(/-/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())
  }, [location.pathname])

  async function handleLogout() {
    await logout()
    navigate('/login', { replace: true })
  }

  return (
    <div className={styles.shell}>
      <SideNav
        sections={sections}
        open={drawerOpen}
        onClose={() => setDrawerOpen(false)}
        footer={
          <button type="button" className={styles.logout} onClick={handleLogout}>
            <Icon icon={LogOut} size={18} />
            Sign out
          </button>
        }
      />

      <div className={styles.main}>
        <TopBar
          left={<Breadcrumbs items={[{ label: 'SP-MIS', to: '/' }, { label: currentLabel }]} />}
          userName={user?.name ?? 'User'}
          userRole={user?.role?.name ?? '—'}
          notifications={<NotificationBell />}
          onOpenMenu={() => setDrawerOpen(true)}
        />
        <main className={styles.content}>
          <Outlet />
        </main>
      </div>
    </div>
  )
}
