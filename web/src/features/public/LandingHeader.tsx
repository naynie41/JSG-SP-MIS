import { useEffect, useState } from 'react'
import { Menu as MenuIcon, X } from 'lucide-react'
import { ButtonLink, Icon } from '@/components'
import { LOGIN_PATH } from './landingConfig'
import { NAV_LINKS } from './landingContent'
import styles from './landing.module.css'

/**
 * The public header: wordmark, section anchors, and the one action that matters.
 *
 * Transparent over the hero so the photograph slot is not boxed in at the top, then
 * solid forest once the page scrolls — without that, the anchors sit on whatever
 * happens to be behind them and contrast becomes a matter of luck.
 */
export function LandingHeader() {
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
    <header className={styles.header} data-solid={scrolled || menuOpen}>
      <div className={styles.headerInner}>
        <a href="#top" className={styles.wordmark}>
          <span className={styles.wordmarkBadge} aria-hidden="true">
            SP
          </span>
          <span className={styles.wordmarkText}>
            <strong>SP-MIS</strong>
            <span className={styles.wordmarkSub}>Jigawa State</span>
          </span>
        </a>

        <nav className={styles.nav} aria-label="Sections">
          <ul className={styles.navList}>
            {NAV_LINKS.map((link) => (
              <li key={link.to}>
                <a href={link.to} className={styles.navLink}>
                  {link.label}
                </a>
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
                <a href={link.to} className={styles.mobileNavLink} onClick={() => setMenuOpen(false)}>
                  {link.label}
                </a>
              </li>
            ))}
          </ul>
        </nav>
      )}
    </header>
  )
}
