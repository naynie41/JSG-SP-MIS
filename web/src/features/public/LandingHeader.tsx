import { useEffect, useState } from 'react'
import type { ComponentPropsWithoutRef, ReactNode } from 'react'
import { Link } from 'react-router-dom'
import { Menu as MenuIcon, X } from 'lucide-react'
import { BrandMark, ButtonLink, Icon } from '@/components'
import { LOGIN_PATH } from './landingConfig'
import { NAV_LINKS } from './landingContent'
import styles from './landing.module.css'

/**
 * A nav entry is either an in-page anchor or a real route. An anchor stays a plain
 * <a>; a route goes through the router, because an <a href="/resources"> would
 * reload the whole application to reach a page the SPA already has.
 */
function NavItem({
  to,
  label,
  className,
  onClick,
  children,
  ...rest
}: {
  to: string
  label?: string
  className: string
  onClick?: () => void
  children?: ReactNode
} & Omit<ComponentPropsWithoutRef<'a'>, 'href' | 'className' | 'onClick' | 'children'>) {
  const content = children ?? label

  if (to.startsWith('#')) {
    return (
      <a href={to} className={className} onClick={onClick} {...rest}>
        {content}
      </a>
    )
  }

  return (
    <Link to={to} className={className} onClick={onClick} {...rest}>
      {content}
    </Link>
  )
}

/**
 * The public header: wordmark, section anchors, and the one action that matters.
 *
 * Transparent over the hero so the photograph slot is not boxed in at the top, then
 * solid forest once the page scrolls — without that, the anchors sit on whatever
 * happens to be behind them and contrast becomes a matter of luck.
 */
export interface LandingHeaderProps {
  /**
   * Render solid from the start, for a page with no hero behind the header.
   *
   * The default transparent-until-scrolled behaviour only makes sense over the
   * landing hero. On an ordinary page it puts near-white header text on a near-white
   * background — the nav and the wordmark are simply invisible until you scroll.
   */
  solid?: boolean
}

export function LandingHeader({ solid = false }: LandingHeaderProps = {}) {
  const [scrolled, setScrolled] = useState(false)
  const [menuOpen, setMenuOpen] = useState(false)

  useEffect(() => {
    // Threshold rather than `> 0`, so a one-pixel trackpad nudge does not flicker it.
    const onScroll = () => setScrolled(window.scrollY > 24)
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  return (
    <header className={styles.header} data-solid={solid || scrolled || menuOpen}>
      <div className={styles.headerInner}>
        {/* On the landing page the wordmark scrolls to the top; anywhere else that
            anchor points at nothing, so it goes home instead. */}
        <NavItem
          to={solid ? '/' : '#top'}
          label=""
          className={styles.wordmark}
          aria-label="SP-MIS Jigawa State — home"
        >
          <BrandMark size={44} className={styles.wordmarkMark} />
          <span className={styles.wordmarkText}>
            <strong>SP-MIS</strong>
            <span className={styles.wordmarkSub}>Jigawa State</span>
          </span>
        </NavItem>

        <nav className={styles.nav} aria-label="Sections">
          <ul className={styles.navList}>
            {NAV_LINKS.map((link) => (
              <li key={link.to}>
                <NavItem to={link.to} label={link.label} className={styles.navLink} />
              </li>
            ))}
          </ul>
        </nav>

        <div className={styles.headerActions}>
          <ButtonLink to={LOGIN_PATH} size="sm" className={styles.headerLogin}>
            Login →
          </ButtonLink>
          <button
            type="button"
            className={styles.menuButton}
            aria-expanded={menuOpen}
            aria-controls="landing-mobile-nav"
            onClick={() => setMenuOpen((open) => !open)}
          >
            <Icon icon={menuOpen ? X : MenuIcon} size={20} aria-hidden="true" />
            <span className="sr-only">{menuOpen ? 'Close menu' : 'Open menu'}</span>
          </button>
        </div>
      </div>

      {menuOpen && (
        <nav id="landing-mobile-nav" className={styles.mobileNav} aria-label="Sections">
          <ul>
            {NAV_LINKS.map((link) => (
              <li key={link.to}>
                <NavItem to={link.to} label={link.label} className={styles.mobileNavLink} onClick={() => setMenuOpen(false)} />
              </li>
            ))}
          </ul>
        </nav>
      )}
    </header>
  )
}
