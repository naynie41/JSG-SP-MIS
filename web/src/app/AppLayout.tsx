import { Suspense, useEffect, useMemo, useRef, useState } from 'react'
import { Outlet, useLocation, useNavigate } from 'react-router-dom'
import { LogOut } from 'lucide-react'
import { Spinner } from '@/components/Spinner/Spinner'
import { SideNav } from '@/components/SideNav/SideNav'
import type { NavSection } from '@/components/SideNav/SideNav'
import { TopBar } from '@/components/TopBar/TopBar'
import { Breadcrumbs } from '@/components/Breadcrumbs/Breadcrumbs'
import { Icon } from '@/components/Icon/Icon'
import { NotificationBell } from '@/features/notifications/NotificationBell'
import { useAuth } from '@/lib/auth/AuthProvider'
import { NAV_CONFIG, navSectionsFor } from './nav'
import styles from './AppLayout.module.css'

/** Breadcrumb labels for pages opened from an affordance rather than the nav rail. */
const OFF_RAIL_LABELS: Record<string, string> = {
  '/admin/settings': 'Settings',
  '/mda/settings': 'Settings',
}

/** Consoles whose Settings page opens from the gear, keyed by role. */
const SETTINGS_ROUTE_BY_ROLE: Record<string, string> = {
  system_administrator: '/admin/settings',
  mda_officer: '/mda/settings',
  mda_admin: '/mda/settings',
}

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
    // Pages reached from an affordance rather than the rail. Without this, the
    // longest-prefix scan below would resolve /admin/settings to its parent rail
    // item ("Overview") because Settings is deliberately not a rail link.
    const offRail = OFF_RAIL_LABELS[location.pathname]
    if (offRail) return offRail
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

  // A client-side route change replaces the whole page silently: no document
  // load, so assistive tech announces nothing and focus stays on the nav link
  // that was activated. Announce the new page and move focus to <main>, but not
  // on first render — that would fight the browser's own initial focus.
  const isFirstRender = useRef(true)
  const mainRef = useRef<HTMLElement>(null)
  const [routeAnnouncement, setRouteAnnouncement] = useState('')

  useEffect(() => {
    if (isFirstRender.current) {
      isFirstRender.current = false
      return
    }
    setRouteAnnouncement(`${currentLabel} page loaded`)
    mainRef.current?.focus()
  }, [location.pathname, currentLabel])

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
          userMda={user?.mda?.name}
          notifications={<NotificationBell />}
          onOpenMenu={() => setDrawerOpen(true)}
          // Consoles reach Settings from the gear, never the rail. Roles without a
          // Settings page get no gear rather than a dead one.
          onOpenSettings={
            SETTINGS_ROUTE_BY_ROLE[roleKey]
              ? () => navigate(SETTINGS_ROUTE_BY_ROLE[roleKey] as string)
              : undefined
          }
        />
        <main id="main-content" ref={mainRef} tabIndex={-1} className={styles.content}>
          {/* Route chunks load on demand (see App.tsx). The boundary sits here rather
              than around the router so the rail and top bar stay put — the reader
              keeps their place instead of watching the whole shell blink. */}
          <Suspense
            fallback={
              <div className={styles.routeLoading}>
                <Spinner size={26} label="Loading page" />
              </div>
            }
          >
            <Outlet />
          </Suspense>
        </main>
        {/* Route-change announcer. Separate from the toast region so a
            navigation never competes with an action confirmation. */}
        <p className="sr-only" role="status" aria-live="polite">
          {routeAnnouncement}
        </p>
      </div>
    </div>
  )
}
