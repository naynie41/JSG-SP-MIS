import { useMemo, useState } from 'react'
import { Outlet, useLocation, useNavigate } from 'react-router-dom'
import { LogOut } from 'lucide-react'
import { SideNav } from '@/components/SideNav/SideNav'
import type { NavSection } from '@/components/SideNav/SideNav'
import { TopBar } from '@/components/TopBar/TopBar'
import { Breadcrumbs } from '@/components/Breadcrumbs/Breadcrumbs'
import { Icon } from '@/components/Icon/Icon'
import { useAuth } from '@/lib/auth/AuthProvider'
import { NAV_CONFIG } from './nav'
import styles from './AppLayout.module.css'

/** Authenticated shell: forest rail (permission-filtered) + top bar + content. */
export function AppLayout() {
  const { user, logout, hasPermission } = useAuth()
  const navigate = useNavigate()
  const location = useLocation()
  const [drawerOpen, setDrawerOpen] = useState(false)

  // Filter nav by permission (UX only); drop sections left empty.
  const sections: NavSection[] = useMemo(() => {
    return NAV_CONFIG.map((section) => ({
      label: section.label,
      items: section.items
        .filter((item) => !item.permission || hasPermission(item.permission))
        .map(({ label, to, icon }) => ({ label, to, icon })),
    })).filter((section) => section.items.length > 0)
  }, [hasPermission])

  const currentLabel = useMemo(() => {
    for (const section of NAV_CONFIG) {
      const match = section.items.find((item) =>
        item.to === '/' ? location.pathname === '/' : location.pathname.startsWith(item.to),
      )
      if (match) return match.label
    }
    return 'SP-MIS'
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
          onOpenMenu={() => setDrawerOpen(true)}
        />
        <main className={styles.content}>
          <Outlet />
        </main>
      </div>
    </div>
  )
}
