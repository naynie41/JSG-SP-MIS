---
# Machine-readable token block. Mirrors web/src/styles/theme.css, which remains
# the runtime source of truth; this exists so the Impeccable detector can check
# source files for token drift (design-system-color / -font / -radius / -font-size).
# Keep in sync with §2, §3 and §4 below. `npm run check:design` (in web/) verifies
# the generated web/DESIGN.md mirror still matches this block.
typography:
  scale:
    caption: 12px
    label: 13px
    ui: 14px
    body: 16px
    h3: 18px
    h2: 22px
    h1: 32px
    kpi: 44px
    display: 60px
  display:
    fontFamily: "Space Grotesk, system-ui, sans-serif"
    fontSize: 60px
  heading:
    fontFamily: "Space Grotesk, system-ui, sans-serif"
    fontSize: 32px
  body:
    fontFamily: "Hanken Grotesk, system-ui, sans-serif"
    fontSize: 16px
  mono:
    fontFamily: "Space Mono, ui-monospace, monospace"
    fontSize: 12px
  # Fluid display ramp (§3a). Dashboard heroes and figure values interpolate with
  # the viewport rather than stepping, so they are declared as clamp() roles: the
  # two endpoints are the documented sizes and the middle term is viewport-relative.
  # The fixed `scale` above still governs everything that is not a display figure.
  fluidFigureSm:
    fontSize: clamp(0.95rem, 1.8vw, 1.15rem)
  fluidFigure:
    fontSize: clamp(1.1rem, 2.2vw, 1.5rem)
  fluidFigureLg:
    fontSize: clamp(1.2rem, 2.4vw, 1.7rem)
  fluidFigureXl:
    fontSize: clamp(1.2rem, 2.6vw, 1.8rem)
  fluidStat:
    fontSize: clamp(1.4rem, 3vw, 2rem)
  fluidStatLg:
    fontSize: clamp(1.5rem, 3.2vw, 2.1rem)
  fluidPanel:
    fontSize: clamp(1.6rem, 4vw, 2.4rem)
  fluidPanelLg:
    fontSize: clamp(1.7rem, 3.6vw, 2.5rem)
  fluidKpi:
    fontSize: clamp(1.8rem, 4.5vw, 2.8rem)
  fluidKpiAlt:
    fontSize: clamp(1.8rem, 4vw, 2.6rem)
  fluidHeadline:
    fontSize: clamp(2.2rem, 5vw, 3rem)
  fluidHero:
    fontSize: clamp(2.4rem, 7vw, 4rem)
  fluidHeroLg:
    fontSize: clamp(2.8rem, 8vw, 5rem)
  fluidHeroAlt:
    fontSize: clamp(2rem, 4.4vw, 3.4rem)
colors:
  accent: "#C6F135"
  accentHover: "#B7E21F"
  accentSoft: "#E7F98A"
  surfaceMint: "#DCEDDC"
  surfaceMint2: "#C6E3CC"
  forest: "#2C3512"
  forest2: "#46551F"
  bg: "#F5F5F1"
  ink: "#181818"
  textMuted: "#6C7064"
  surface: "#FFFFFF"
  border: "#E2E3DD"
  borderStrong: "#C9CBC1"
  textMutedStrong: "#52564A"
  success: "#2F7D3B"
  successSoft: "#DFF0E1"
  successInk: "#1E5127"
  warning: "#B4791E"
  warningSoft: "#F5E7C8"
  warningInk: "#6E4A0F"
  danger: "#B23A31"
  dangerSoft: "#F6DED9"
  dangerInk: "#6E211B"
  dangerHover: "#9C322A"
  info: "#356E7A"
  infoSoft: "#D9EAEC"
  infoInk: "#1D454C"
  chart1: "#008300"
  chart2: "#2A78D6"
  chart3: "#EDA100"
  chart4: "#E87BA4"
  chart5: "#1BAF7A"
  chart6: "#EB6834"
  chartOther: "#9A9C93"
  forestTint: "#384318"
  forestMid: "#333D16"
  forestDeep: "#232B0E"
rounded:
  swatch: 3px
  sm: 4px
  md: 12px
  lg: 20px
  full: 999px
---

# DESIGN.md (generated mirror)

> **Do not edit.** Generated from the repo-root [`DESIGN.md`](../DESIGN.md) by
> `scripts/sync-design-tokens.mjs`. Only the token frontmatter is mirrored here,
> so the Impeccable detector can resolve a design system from inside `web/`
> (its walk-up stops at `web/package.json`). The prose design system — components,
> states, rules — lives in the root file and is the one to read and edit.

Regenerate with `npm run sync:design`; verify with `npm run check:design`.
