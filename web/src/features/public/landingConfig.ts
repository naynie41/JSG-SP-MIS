/**
 * Configuration points for the public landing page.
 *
 * Everything here is STATIC. The landing page is served to anonymous visitors and reads
 * nothing from the API: no beneficiary counts, no coverage figures, no programme totals.
 * Under NDPA/NDPR an unauthenticated visitor is entitled to know what the system is for,
 * not what is in it.
 */

/**
 * ── HERO PHOTOGRAPH SLOT ─────────────────────────────────────────────────────────────
 *
 * Set this to the URL of a LICENSED photograph and the hero uses it, behind the dark
 * legibility overlay the copy needs. Leave it empty and the hero ships its gradient,
 * which is a finished treatment rather than a placeholder — nothing looks broken while
 * the photo is being cleared.
 *
 * Deliberately empty in the repo. No stock or AI-generated image is embedded here:
 * a photograph on a state government's public page depicts real people, and the rights
 * and consent for that are a decision for the communications team, not a build step.
 *
 * When supplying one:
 *   • confirm the licence permits government/public-sector use,
 *   • confirm subjects consented to be photographed and published,
 *   • prefer a wide landscape crop — the overlay darkens it, so mid-tones read best,
 *   • serve it from the app's own origin or an approved CDN,
 *   • set HERO_IMAGE_ALT to a real description, or leave it empty if the image is
 *     purely decorative (the headline already carries the meaning).
 *
 * A build-time environment variable is wired up so the image can differ per
 * environment without a code change: set `VITE_LANDING_HERO_IMAGE`.
 */
export const HERO_IMAGE_URL: string = (import.meta.env.VITE_LANDING_HERO_IMAGE as string | undefined) ?? ''

/** Describe the photograph if it carries meaning; empty means decorative. */
export const HERO_IMAGE_ALT = ''

/** Where the "Login" actions go. The auth page owns everything past this point. */
export const LOGIN_PATH = '/login'
