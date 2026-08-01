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
import { NAV_CONFIG, navSectionsFor } from './nav'
import styles from './AppLayout.module.css'

/** Authenticated shell: forest rail (permission-filtered) + top bar + content. */
export function AppLayout() {
  const { user, logout, hasPermission } = useAuth()
  const navigate = useNavigate()
  const location = useLocation()
  const [drawerOpen, setDrawerOpen] = useState(false)

  const roleKey = user?.role?.key ?? ''

  // Nav rail: role- + permission-filtered (see navSectionsFor). UX-only — the server
  // is the security boundary.
  const sections: NavSection[] = useMemo(() => navSectionsFor(roleKey, hasPermission), [hasPermission, roleKey])

  const currentLabel = useMemo(() => {
    if (location.pathname === '/') return 'Dashboard'
    // Pick the MOST specific matching rail item (longest `to`) so e.g.
    // `/partner/programmes` resolves to "Programmes & Results", not "Overview".
    let best: { label: string; to: string } | null = null
    for (const section of NAV_CONFIG) {
      for (const item of section.items) {
        if (item.to !== '/' && location.pathname.startsWith(item.to)) {
          if (!best || item.to.length > best.to.length) best = item
        }
      }
    }
    if (best) return best.label
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
      <a href="#main-content" className={styles.skipLink}>
        Skip to content
      </a>
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
        <main id="main-content" tabIndex={-1} className={styles.content}>
          <Outlet />
        </main>
      </div>
    </div>
  )
}
