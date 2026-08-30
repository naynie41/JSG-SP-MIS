# ACCESSIBILITY.md — WCAG 2.1 AA Audit & Regression (NFR-USE-01)

Final accessibility pass across the SP-MIS web app: an audit of the design system and
shared components against **WCAG 2.1 Level AA**, the fixes applied, the responsive
check for the core flows, and the tracked items that need manual assistive-technology
sign-off before go-live.

Method: code audit of the single design-token stylesheet (`web/src/styles/theme.css`)
and every shared component (`web/src/components/*`), plus the layout and core-flow
pages, against the AA success criteria for contrast, keyboard operability, focus
visibility, semantics, labelling, error association, and motion. The app is built
almost entirely from shared primitives, so component-level compliance propagates to
every flow.

---

## 0. Re-audit — 2026-08-30 (pass 2, automated against the running app)

Pass 1 was a code-and-design review. This pass ran **axe-core against the live app in a
real browser, behind a real session**, because contrast and focus do not exist until the
CSS is applied — they cannot be audited from source.

**Coverage: 26 page/viewport combinations** — 13 routes × desktop (1440×900) and mobile
(390×844), signed in as MDA Admin and System Administrator, plus the two unauthenticated
routes. Ruleset: `wcag2a, wcag2aa, wcag21a, wcag21aa`.

Routes: public landing, login, MDA overview, import, duplicate resolution, benefit
recording, referrals, grievances, MDA reports, beneficiaries, GIS, admin overview,
admin access.

### Result

| Check | Method | Result |
|---|---|---|
| WCAG 2.1 A/AA (axe-core) | 26 page/viewport runs | **0 violations** after the fix below |
| Lime "dark text only" (§2) | computed colour on every lime surface, 10 routes | **PASS** — no light text on any lime background |
| Keyboard operability | tab-walk of every focus stop, 9 core flows | **PASS** — every page reachable, 1–18 stops each |
| Reduced motion | `prefers-reduced-motion: reduce` emulated | **PASS** — nothing animates beyond 50 ms |

### A11Y-04 — Scrollable table region not keyboard-focusable (2.1.1) — FIXED

axe found four instances of `scrollable-region-focusable`, all **mobile only**, all the
same element: the `DataTable` horizontal-scroll wrapper on referrals, grievances,
beneficiaries and admin access.

On a narrow screen the table overflows sideways and the wrapper scrolls. It had no
`tabindex`, so it could only be scrolled by dragging — meaning a keyboard-only user could
not reach the right-hand columns, which on these tables is where status and the row
actions live. The content was rendered and unreachable.

Fixed in `DataTable.tsx`: the wrapper is now `tabIndex={0}` with `role="region"` and
`aria-label={caption}`, so it is a focus stop that announces which table it belongs to
rather than an unnamed box. `DataTable.module.css` gives it a `:focus-visible` ring — a
new focus stop without a visible indicator would have traded one violation for another.

### Not a defect — recorded so it is not re-investigated

A single focus stop on **benefit recording** was flagged as having no visible indicator in
one sequential multi-page run. It did not reproduce when that page was tabbed in
isolation, and the underlying rule is demonstrably correct: focusing the control directly
shows `outline: 2px solid` from t=0 plus a `box-shadow` that settles to `0 0 0 3px` within
100 ms. Treated as a measurement artefact of the automated walk, **not** as a finding — no
code was changed for it.

---

## 1. Findings fixed in pass 1 (code review)

### A11Y-01 — Input focus invisible in forced-colors mode (2.4.7, 1.4.11) — FIXED
`.control:focus` used `outline: none` with only a low-alpha `box-shadow` for the focus
indicator. Box-shadow is dropped in Windows High-Contrast / `forced-colors` mode, so
keyboard focus on text inputs/selects/textareas became invisible there.
**Fix:** replaced `outline: none` with `outline: 2px solid transparent; outline-offset: 2px`
— invisible in normal mode (the box-shadow still carries the visual), but forced-colors
substitutes the system focus color, restoring a visible ring.
(`web/src/components/Field/Field.module.css`)

### A11Y-02 — No "skip to content" link (2.4.1 Bypass Blocks) — FIXED
Keyboard users had to tab through the whole nav rail on every page.
**Fix:** added a skip link as the first focusable element in the authenticated shell; it
is off-screen until focused, then jumps to `#main-content` (the `<main>` landmark, now
focusable). (`web/src/app/AppLayout.tsx`, `AppLayout.module.css`)

### A11Y-03 — Tabs: focus did not follow activation (2.4.3, 2.1.1) — FIXED
Arrow-key navigation changed the active tab (roving `tabindex`) but left keyboard focus
on the previously-active button (now `tabindex=-1`), stranding the focus point.
**Fix:** arrow keys now move focus to the newly-activated tab and skip disabled tabs.
(`web/src/components/Tabs/Tabs.tsx`)

---

## 2. Verified already-compliant (no change needed)

The design system was built accessibility-first; the audit confirmed:

| Criterion | Evidence |
|---|---|
| **Lime "dark-text-only" rule** (1.4.3) | Lime (`--accent #c6f135`) surfaces always pair with dark text (`--ink`/`--forest`) — Button primary, Badge `accent`, active states. Never white-on-lime. |
| **Visible keyboard focus** (2.4.7) | Global `:focus-visible` → 2px forest outline (lime on dark surfaces); every custom control is a real `<button>`/`<a>`, so it inherits the ring. |
| **Contrast** (1.4.3) | Small secondary text uses `--text-muted-strong` (AA on paper); `--text-muted` is restricted to large/label text; all semantic chips use `-ink` on `-soft`. |
| **Reduced motion** (2.3.3) | Global `@media (prefers-reduced-motion: reduce)` zeroes transitions/animations and `--transition`. |
| **Labelled inputs** (1.3.1, 4.1.2) | `FieldShell` binds `<label for>` to the control id across TextField/SelectField/TextareaField/Checkbox/RadioGroup/Toggle. |
| **Error association** (3.3.1) | Controls set `aria-invalid` and `aria-describedby` → the message id; form-level errors render with `role="alert"`. |
| **Dialogs** (2.4.3, 4.1.2) | Modal has `role="dialog"`, `aria-modal`, focus trap, Esc + overlay close, focus restored on close. |
| **Live regions** (4.1.3) | Toasts render in an `aria-live="polite"` region with `role="status"` and a labelled dismiss button. |
| **Menus** (4.1.2, 2.1.1) | Overflow Menu: `aria-haspopup`/`aria-expanded`, `role="menu"`/`menuitem`, arrow-key nav, Esc, outside-click, focus-first-item. |
| **Tabs** (4.1.2) | `role="tablist"/"tab"/"tabpanel"`, `aria-selected`/`-controls`/`-labelledby`, roving `tabindex` (+ A11Y-03 fix). |
| **Icon-only buttons** (4.1.2) | Carry `aria-label` (menu trigger, toast dismiss, modal close); the `Icon` wrapper is `aria-hidden` unless given a `label`. |
| **Landmarks** (1.3.1) | `<main>` content region, `<nav>` rail, header top bar. |
| **Touch targets** (2.5.5) | Interactive controls meet ~44px min height (e.g. nav, logout). |

---

## 3. Responsive check — core flows (desktop + mobile)

The app's responsiveness comes from shared primitives, so each core flow inherits it:

- **Mobile nav**: the forest rail becomes an off-canvas drawer (`SideNav` `open/onClose`)
  opened by the TopBar hamburger (`onOpenMenu`); content padding steps down at ≤640px.
- **Tables** (dedup resolution, benefit ledger, referrals/grievances lists, import rows):
  the shared `DataTable` wraps in an `overflow-x: auto` container, so wide tables scroll
  horizontally on narrow screens without breaking the page layout.
- **Forms/wizards** (import upload, benefit recording, activity wizard, consent): the
  shared `formLayout` grid is two-column on desktop and collapses to one column at ≤640px.
- **Dashboards**: KPI/metric bands and cards use flex/grid with `gap`, wrapping on small
  screens; charts sit in responsive containers.
- **GIS**: choropleth renders when boundaries are loaded, with a ranked-table fallback —
  the table path is fully responsive; the map path needs manual device verification (§4).

| Core flow | Desktop | Mobile (≤640px) | Notes |
|---|---|---|---|
| Bulk import (upload→preview→commit) | ✓ | ✓ | preview table scrolls; wizard collapses to 1-col |
| Dedup resolution | ✓ | ✓ | candidate table scrolls; actions in overflow menu |
| Benefit recording | ✓ | ✓ | form grid collapses; modal is width-capped |
| Referrals / Grievances | ✓ | ✓ | list tables scroll; detail panels stack |
| Dashboards | ✓ | ✓ | metric bands + cards wrap |
| GIS coverage | ✓ | ⚠ manual | table fallback responsive; map needs device check |

---

### Automated responsive re-check — 2026-08-30

Eleven routes × three viewports (phone 390×844, tablet 768×1024, desktop 1440×900),
signed in, against the running app.

| Check | Result |
|---|---|
| Body scrolls horizontally | **None** — no route, no viewport. Wide content scrolls inside its own container, never the page. |
| Core flows reachable on a phone | All eleven render and are operable |
| Controls under 32 px tall (phone) | 10 found, **all inline text links — not violations**; see below |

**On the short controls.** Every one is an inline text link: seven footer links, the
hero's "What is SP-MIS?", the GRM "Learn about grievance redress", and a breadcrumb.
WCAG 2.1 AA — the standard this system targets — has **no** minimum target-size
criterion; 2.5.5 Target Size is AAA, and 2.5.8 (WCAG 2.2, AA) explicitly exempts targets
that sit inline in a block of text. So the 32 px bar the check used is stricter than both
the standard and the intent of DESIGN.md §6, whose 44 px minimum is about controls, not
prose links. Nothing was changed: inflating footer links to 44 px would make the page
worse for no accessibility gain.

One judgement call left open rather than decided unilaterally: the landing page's footer
links render 18 px tall on a phone. Conformant and conventional, but small for a thumb.
Worth raising the footer's line spacing if the communications team wants it — a design
preference, not a defect.


## 4. Tracked exceptions — manual AT / device sign-off before go-live

These require a human with real assistive technology / devices and are out of scope for
a code audit. They are the go-live accessibility gate:

- **AT-01 — Screen-reader pass** (NVDA + VoiceOver) over the core flows: verify reading
  order, announcements (toasts, validation, route changes), and form/error semantics.
- **AT-02 — Real-device responsive pass** on iOS Safari + Android Chrome, including the
  **GIS map** interaction (pan/zoom, choropleth legend) and long tables.
- **AT-03 — Automated axe scan** in CI: add `@axe-core/playwright` (or `jest-axe`) against
  the rendered pages for a continuous regression signal. (Not added here to avoid a new
  test dependency without approval — recommended as a follow-up.)
- **AT-04 — Color-contrast spot-check** of any data-viz series palettes against AA once
  live datasets/branding are final (the dataviz palette is validated by the skill's
  checker; re-run on the final brand values).

---

## 5. Regression

The full automated suite was run as part of this pass:

- **Backend**: `php artisan test` — all Feature/Unit tests pass.
- **Frontend**: `vitest run` (unit/component) + `tsc --noEmit` (types) + `oxlint` (lint) — all pass.

The per-phase manual checklists (`docs/PHASE-1..6-CHECKLIST.md`) plus the v1.2 remediation
and Phase 5/6/7 flows are the manual regression basis. (Note: a consolidated
`docs/QA-CHECKLIST-P1-P4.md` was referenced by the task but does not exist in the repo;
the per-phase checklists above are the standing manual QA source and should be walked on
staging alongside AT-01/AT-02.)
