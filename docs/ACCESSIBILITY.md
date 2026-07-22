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

## 1. Findings fixed in this pass

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
