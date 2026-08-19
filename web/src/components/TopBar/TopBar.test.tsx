import { describe, expect, it } from 'vitest'
import { render, screen } from '@testing-library/react'
import { TopBar } from './TopBar'

/**
 * The shell's identity block — who you are and which MDA you are acting for.
 *
 * The failure this guards against is not visual polish: the role line is mono, uppercase
 * and tracked, so "MDA ADMIN · MINISTRY OF WOMEN AFFAIRS & SOCIAL DEVELOPMENT" measures
 * over half a laptop's bar. Unbounded, it pushed the breadcrumb into the corner. Bounded,
 * it can be cut short — so what is cut has to stay resolvable.
 *
 * jsdom computes no layout, so the width itself is not assertable here; what IS assertable
 * is the structure that makes truncation safe, plus the CSS invariants that make it work.
 */

const CSS = import.meta.glob('./TopBar.module.css', { query: '?raw', import: 'default', eager: true }) as Record<
  string,
  string
>
const CRUMB_CSS = import.meta.glob('../Breadcrumbs/Breadcrumbs.module.css', {
  query: '?raw',
  import: 'default',
  eager: true,
}) as Record<string, string>

const topBarCss = Object.values(CSS)[0]!
const breadcrumbCss = Object.values(CRUMB_CSS)[0]!

function renderBar(props: Partial<React.ComponentProps<typeof TopBar>> = {}) {
  return render(
    <TopBar
      userName="Amina Bello"
      userRole="MDA Admin"
      userMda="Ministry of Women Affairs & Social Development"
      {...props}
    />,
  )
}

describe('TopBar identity block', () => {
  it('shows the acting MDA beside the role', () => {
    // Which MDA you act for governs every record you can see, so it belongs in the shell
    // rather than being inferred from what a page is missing.
    renderBar()

    expect(screen.getByText('Amina Bello')).toBeInTheDocument()
    expect(
      screen.getByText('MDA Admin · Ministry of Women Affairs & Social Development'),
    ).toBeInTheDocument()
  })

  it('keeps the full context available when the visible text is cut short', () => {
    // The block is capped, so a long agency name renders as "…MINISTRY OF WOMEN AFF…".
    // Without the title the user cannot tell which agency they are acting for — which is
    // exactly the thing the line exists to say.
    renderBar()

    expect(screen.getByTitle('MDA Admin · Ministry of Women Affairs & Social Development')).toBeInTheDocument()
  })

  it('shows the role alone when the user belongs to no MDA', () => {
    // A System Administrator or Executive is not acting for one agency; a dangling
    // separator would imply a missing value rather than an absent concept.
    renderBar({ userMda: null })

    expect(screen.getByText('MDA Admin')).toBeInTheDocument()
    expect(screen.queryByText(/·/)).not.toBeInTheDocument()
  })

  it('stacks the two lines with layout, not a content line break', () => {
    // A `<br>` cannot be truncated or hidden per line; a flex column can.
    const { container } = renderBar()

    expect(container.querySelector('br')).toBeNull()
  })

  /* --------------------------------------------------- the layout invariants */

  it('lets the identity block shrink before the breadcrumb does', () => {
    // Without `min-width: 0` on the right side, the identity block is unshrinkable and
    // the breadcrumb is squeezed instead — backwards, since the breadcrumb says where
    // you are and the identity is repeated on every screen.
    expect(topBarCss).toMatch(/\.right\s*\{[^}]*min-width:\s*0/)

    // Anchored on the BASE rule — the one that also sets `flex-direction: column`.
    // A looser match is satisfied by the narrow-viewport override in the media query,
    // which would let the desktop cap be deleted without failing anything.
    expect(topBarCss).toMatch(/\.userMeta\s*\{[^}]*flex-direction:\s*column[^}]*max-width/)
  })

  it('truncates each identity line rather than wrapping the bar', () => {
    expect(topBarCss).toMatch(/\.userName,\s*\.userRole\s*\{[^}]*text-overflow:\s*ellipsis/)
    expect(topBarCss).toMatch(/\.userName,\s*\.userRole\s*\{[^}]*white-space:\s*nowrap/)
  })

  it('never wraps the breadcrumb trail', () => {
    // The bar has a fixed height, so a wrapped second line does not make it taller —
    // it overflows it.
    expect(breadcrumbCss).toMatch(/\.crumbs\s*\{[^}]*flex-wrap:\s*nowrap/)
    expect(breadcrumbCss).not.toMatch(/\.crumbs\s*\{[^}]*flex-wrap:\s*wrap/)
  })

  it('keeps the avatar and the icon buttons at full size', () => {
    // Compressing a tap target is a WCAG 2.5.8 problem, not a cosmetic one.
    expect(topBarCss).toMatch(/\.iconButton\s*\{[^}]*flex:\s*none/)
    expect(topBarCss).toMatch(/\.user\s*>\s*:last-child\s*\{[^}]*flex:\s*none/)
  })
})
