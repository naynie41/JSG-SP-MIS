# DESIGN-SYSTEM.md — SP-MIS UI Reference

> The single source of truth for how SP-MIS looks and behaves. Every UI prompt and every screen
> Claude Code builds must derive its colors, type, spacing, and components from this file. Combine
> it with the **`frontend-design`** skill (the skill guides layout, hierarchy, and polish; the
> tokens below are non-negotiable). **Never hand-roll a component that already exists here** —
> extend the shared component instead.
>
> **Source:** Jigawa Social-Protection MIS Design System V1.0. Foundations (color, type, spacing,
> radius, elevation) and primitives (buttons, inputs, badges, toggle) come directly from the brand
> guide. Items marked **[inferred]** were not specified in the guide and were derived to be
> accessible and on-palette — flag them to the design owner for confirmation.
>
> **Context:** SP-MIS is a dense, form- and table-heavy government application, not a marketing
> site. The brand's marketing components (hero banners, testimonials, demo CTAs) are **not** used;
> their tokens are. Spend boldness in one place — the **lime accent** — and keep everything else
> quiet and disciplined.

---

## 1. Signature

The one memorable thing: **lime as the single active/primary accent**, used sparingly against a
calm paper/mint field, with **mono uppercase eyebrow labels** (e.g. `01 · REGISTRY`) as the
structural device and a **forest-dark navigation rail**. If a screen has more than a couple of
lime elements competing for attention, remove one.

---

## 2. Color tokens

```css
:root {
  /* Brand — from the guide */
  --accent:          #C6F135; /* lime — primary actions, active state, key highlights */
  --accent-soft:     #E7F98A; /* lime soft — selected rows, subtle accent fills */
  --surface-mint:    #DCEDDC; /* mint — secondary panels, hover tints */
  --surface-mint-2:  #C6E3CC; /* mint deep — pressed/again-secondary surfaces */
  --forest:          #2C3512; /* forest — nav rail, dark panels, primary text on light */
  --forest-2:        #46551F; /* forest soft — dark hover, borders on dark */
  --bg:              #F5F5F1; /* paper — app background */
  --ink:             #181818; /* ink — primary text, dark button */
  --text-muted:      #6C7064; /* muted — secondary text (large/label only, see a11y note) */

  /* Neutrals [inferred] — needed for tables, borders, surfaces */
  --surface:         #FFFFFF; /* card/table surface on paper bg */
  --border:          #E2E3DD; /* hairline dividers, input borders */
  --border-strong:   #C9CBC1; /* emphasised borders */
  --text-muted-strong:#52564A;/* [inferred] darker muted for small secondary text (AA) */

  /* Semantic [inferred] — distinct hues from lime, each with a soft tint */
  --success:         #2F7D3B; --success-soft: #DFF0E1; --success-ink: #1E5127;
  --warning:         #B4791E; --warning-soft: #F5E7C8; --warning-ink: #6E4A0F;
  --danger:          #B23A31; --danger-soft:  #F6DED9; --danger-ink:  #6E211B;
  --info:            #356E7A; --info-soft:    #D9EAEC; --info-ink:    #1D454C;
}
```

### Contrast & usage rules (accessibility floor — WCAG AA)

- **Lime (`--accent`, `--accent-soft`) is a dark-text surface only.** Text/icons on lime are
  `--ink` or `--forest`. **Never white text on lime** (fails contrast).
- **Forest surfaces** (`--forest`, `--forest-2`) use `--bg`/`white` text and may use `--accent`
  for highlights.
- **Mint surfaces** use `--ink` text.
- `--text-muted` on paper is borderline for small text — use it for labels and ≥18px secondary
  text; for small (≤14px) secondary text use `--text-muted-strong`.
- Every semantic color pairs its `-soft` background with its `-ink` text for badges/alerts.

---

## 3. Typography

**Load:** Space Grotesk (display/headings), Hanken Grotesk (body/UI), Space Mono **[inferred
choice]** (captions/eyebrows/data). All available on Google Fonts.

```css
:root {
  --font-display: "Space Grotesk", system-ui, sans-serif;
  --font-body:    "Hanken Grotesk", system-ui, sans-serif;
  --font-mono:    "Space Mono", ui-monospace, monospace;
}
```

### Type scale (from the guide; app sizes marked [inferred])

| Role          | Font          | Weight | Size / line-height | Use |
|---------------|---------------|--------|--------------------|-----|
| Display XL    | Space Grotesk | 700    | 60 / 1.03          | Marketing/login splash only |
| Heading (h1)  | Space Grotesk | 600    | 32 / 1.1           | Page titles |
| Subheading(h2)| Space Grotesk | 600    | 22 / 1.2           | Section titles |
| Title (h3)    | Space Grotesk | 600    | 18 / 1.3 **[inferred]** | Card/table titles |
| Body Large    | Hanken Grotesk| 400    | 18 / 1.55          | Lead paragraphs |
| Body          | Hanken Grotesk| 400    | 16 / 1.55          | Default body |
| UI / Table    | Hanken Grotesk| 400/500| 14 / 1.5 **[inferred]** | Dense tables, form controls |
| Label         | Hanken Grotesk| 500    | 13 / 1.4 **[inferred]** | Field labels |
| Caption/Eyebrow| Space Mono   | 400    | 12 / 0.14em tracking, UPPERCASE | Eyebrows, metadata, codes |

Use the **mono caption** for structural eyebrows and machine-ish data (IDs, NIN masks, timestamps,
audit codes) — it encodes "this is a precise value," which fits the subject.

---

## 4. Spacing, radius, elevation

```css
:root {
  /* Spacing scale (from guide) */
  --space-1: 4px;  --space-2: 8px;  --space-3: 12px; --space-4: 16px;
  --space-5: 24px; --space-6: 32px; --space-7: 48px; --space-8: 64px;

  /* Radius (from guide) */
  --radius-sm: 4px; --radius-md: 12px; --radius-lg: 20px; --radius-full: 999px;

  /* Elevation [inferred] — soft, low, greenish-neutral */
  --elev-sm: 0 1px 2px rgba(24,24,24,.06);
  --elev-md: 0 4px 12px rgba(24,24,24,.08);
  --elev-lg: 0 12px 32px rgba(44,53,18,.12);
}
```

- **Buttons & chips:** `--radius-full` (pill) — matches the brand.
- **Inputs:** `--radius-md`. **Cards/modals/panels:** `--radius-lg`. **Table container:** `--radius-lg`.
- Use elevation sparingly: cards `--elev-sm`, dropdowns/popovers `--elev-md`, modals `--elev-lg`.

---

## 5. Component specifications

For each component, all interactive states are **required**: default, hover, active/pressed,
**focus-visible** (keyboard), disabled, and where relevant loading and error.

**Focus ring (global):** `outline: 2px solid var(--forest); outline-offset: 2px;` — on forest/dark
surfaces use `var(--accent)` instead. Focus must always be visible.

### 5.1 Buttons (pill)
- **Primary** — bg `--accent`, text `--ink`; hover darken lime ~6%; disabled 40% opacity.
- **Secondary (dark)** — bg `--forest`, text `--bg`; hover `--forest-2`.
- **Tertiary / ghost** — transparent, text `--ink`, 1px `--border`; hover bg `--surface-mint`.
- **Danger** — bg `--danger`, white text (danger is a valid white-text surface).
- **Link** — text `--forest`, underline on hover, trailing arrow "→" allowed.
- Sizes: sm (h32, 13px), md (h40, 14px), lg (h48, 16px). Icon+label spacing `--space-2`.
- Loading: replace label with spinner, keep width, disable pointer.

### 5.2 Inputs & form fields
- Text input / textarea / select / date / number: bg `--surface`, 1px `--border`, `--radius-md`,
  14px text, `--space-3` padding. Label above (`--label`), optional helper below.
- **Focus:** border `--forest` + focus ring. **Error:** border `--danger`, helper becomes
  `--danger-ink`, show the message from the API error envelope. **Disabled:** bg `--bg`, muted text.
- **Checkbox / radio:** `--accent` when checked, `--ink` check glyph, visible focus.
- **Toggle/switch:** off = `--border-strong` track; on = `--accent` track with dark knob (matches
  the brand's "Human review enabled" toggle).
- **File upload:** dashed `--border-strong` dropzone, `--radius-md`, shows validation errors
  inline (used for supporting documents — enforce SECURITY.md file rules in UI copy).
- Required-field marker, inline validation mirroring backend rules (see CONVENTIONS.md §6).

### 5.3 Badges & chips (pill, uppercase mono optional)
Variants: **accent** (lime, dark text), **neutral** (mint, ink), **dark** (forest, paper),
**outline** (border + ink), and **semantic** success/warning/danger/info (`-soft` bg + `-ink`).
Optional leading dot. Use for statuses, match types, tags. Keep to one badge per status cell.

### 5.4 Data table (workhorse)
- Container: `--surface`, `--radius-lg`, `--elev-sm`, hairline `--border` row dividers.
- Header row: mono caption style, uppercase, `--text-muted-strong`, sortable affordance on click.
- Body: 14px, `--space-3` cell padding. **Row hover:** `--surface-mint`. **Row selected:**
  `--accent-soft`. Optional leading checkbox column.
- Right-aligned numeric columns; masked sensitive values (e.g. NIN) with a reveal control gated by
  permission. Row actions in an overflow menu.
- **Export action (optional, per grid):** a toolbar "Export" control (CSV / Excel) that exports the
  **current filtered/searched view** within the user's scope, with sensitive fields masked unless the
  user has reveal permission. Large exports run on the queue with a ready notification. Reuses the
  shared export service; permission-gated and audited. Build it as a reusable action so any grid can
  opt in.
- **Pagination** below (page or cursor, per CONVENTIONS.md). **Empty state** = one line of
  direction + a primary action, never a blank box. **Loading** = skeleton rows.

### 5.5 Cards / panels / KPI
- Card: `--surface`, `--radius-lg`, `--elev-sm`, `--space-5` padding. Secondary = `--surface-mint`.
- **Emphasis / exec KPI:** forest panel (`--forest`, paper text) with a big Space Grotesk number
  and a mono label — reserved for dashboards; use sparingly.

### 5.6 Navigation
- **Side rail:** `--forest` background, paper text, section groups with mono eyebrows. **Active
  item:** lime left-bar or `--accent` text + `--forest-2` fill. Collapsible on mobile.
- **Top bar:** paper, breadcrumbs (mono separators), user menu (avatar), notifications bell.

### 5.7 Overlays
- **Modal/dialog:** centered, `--surface`, `--radius-lg`, `--elev-lg`; scrim = `--forest` at ~40%.
  Header (Space Grotesk 22), body, footer with primary + ghost cancel. Destructive confirm uses the
  danger button and names the exact consequence. Trap focus; Esc + overlay-click close.
- **Toast:** top-right, semantic variant (`-soft` bg, `-ink` text, matching icon), auto-dismiss
  ~5s, screen-reader announced. Toast verb matches the action ("Beneficiary registered").
- **Tooltip / popover:** `--ink` bg, paper text, `--elev-md`, small.
- **Tabs, breadcrumbs, avatar, pagination:** consistent with the above tokens.

### 5.8 Domain status → variant map (use everywhere for consistency)

| Domain status                     | Badge variant |
|-----------------------------------|---------------|
| Beneficiary: active               | success       |
| Beneficiary: suspended            | warning       |
| Beneficiary: flagged / duplicate  | danger        |
| Match: exact/deterministic        | dark (dot)    |
| Match: probable (needs review)    | warning       |
| Match: AI-scored                  | accent        |
| Referral: Created                 | neutral       |
| Referral: Accepted                | info          |
| Referral: In Progress             | accent        |
| Referral: Completed/Closed        | success       |
| Referral: Rejected                | danger        |
| Service Request: pending          | warning       |
| Service Request: accepted         | success       |
| Service Request: declined         | muted-danger  |
| Import row: valid / error         | success / danger |

Extend this table as new statuses appear — don't invent one-off colors per screen.

### 5.9 New states / components (PRD v1.2)

- **Activity selector** as a required first step in the import wizard, before file/source selection.
  The wizard cannot proceed without a selected registered activity (FR-REG-10).
- **Match resolution controls** (the match badge exact/probable/none already exists): show
  **adjudicate ("same person / not")** ONLY for probable (fuzzy) matches, then **discard /
  provide-service**. Hide the adjudication control on exact matches (definitive), which still show
  the discard/serve choice (FR-DUP-09).
- **Request-to-serve status chip:** pending / accepted / declined → map to warning / success /
  muted-danger (confirm the muted-danger tone with the design owner alongside the other
  `[inferred]` semantic colors).
- **Owner-MDA approval inbox:** a list of incoming Service Requests with accept / decline actions
  and a reason field on decline (FR-OWN-06).
- **Import error report:** two visually distinct groups — **"rows rejected (identity)"** vs
  **"fields dropped (non-identity)"** (FR-REG-05/09).

### 5.10 Programme catalog & activity creation (revises FR-PRG-01/02)

- **No programme create/edit control in any MDA view** (officer or MDA admin). Programme management
  lives in a **System-Admin "Programme Catalog"** screen only.
- **Activity creation modal/flow** (MDA): first field is a **programme dropdown** sourced from the
  global catalog; then the MDA-specific fields (location, schedule, budget, funding source, period,
  targets). The programme is required before the rest of the form proceeds.
- **Optional final step — "Upload beneficiary data (optional)":** the wizard's last step lets the
  officer attach a beneficiary file (or skip it). If attached, on submit the system runs validation +
  the duplicate cascade in a **preview panel BEFORE saving**: it shows valid new rows, rejected
  (identity) rows, and duplicates (exact badge = definitive; probable badge = adjudicate inline). On
  confirm: the activity saves, new beneficiaries save with interventions under the activity, and each
  duplicate chosen to serve becomes a **pending Service Request attached to the activity** (intervention
  deferred until Owner-MDA approval). If skipped, the activity saves alone.
- **Activity detail** shows its beneficiaries/interventions and a **"Pending service requests"** section
  listing request-to-serve items awaiting approval under that activity.
- Programme appears as a read-only **catalog chip/label** on activity and intervention views for MDAs.

---

## 6. Iconography, motion, responsiveness

- **Icons:** one line-icon set (recommend **Lucide** — pairs with the clean grotesque type),
  consistent 1.5px stroke, 20px default. No mixed icon styles.
- **Motion [restrained]:** 150–200ms ease transitions for hover/enter; subtle row/hover
  micro-interactions only. No marketing-style animation. **Respect `prefers-reduced-motion`.**
- **Responsive:** desktop-first but fully usable on mobile browsers (NFR-USE-01). Breakpoints
  `sm 640 / md 768 / lg 1024 / xl 1280`. Tables collapse to stacked cards or horizontal scroll on
  small screens. Min tap target 44px. Side rail collapses to a drawer.
- **Accessibility floor (non-negotiable):** WCAG AA contrast, visible keyboard focus everywhere,
  labelled inputs, semantic HTML, error messages tied to fields, reduced motion respected.

---

## 7. Implementation notes

- Define all tokens once as CSS custom properties (`:root`) in a single `theme.css`; if a utility
  framework (e.g. Tailwind) is in use, map these tokens into its theme config — **do not** hard-code
  hex values in components. Everything references `var(--token)`.
- Load fonts via Google Fonts with `font-display: swap`; set `--font-body` as the app default.
- Build the primitives (button, input/field set, badge, table, card, modal, toast, nav, tabs,
  pagination, empty-state, skeleton) once as shared components; feature screens compose these only.
- Keep a living Storybook/preview page of the components as they're built.

---

## 8. Rules for Claude Code

1. Read this file (and load the `frontend-design` skill) before building any UI.
2. Derive every color/type/spacing value from the tokens above; never introduce off-palette values.
3. Never hand-roll a component that exists in §5 — extend the shared one.
4. Every interactive element ships all required states, incl. visible focus, and meets the a11y floor.
5. Use the §5.8 status map; don't invent per-screen status colors.
6. Keep the lime accent scarce. When in doubt, make it quieter.
7. `[inferred]` values are provisional — if the design owner later supplies real ones, update here first.