import { ArrowRight, ShieldCheck } from 'lucide-react'
import { ButtonLink, Icon } from '@/components'
import { IllustrativeMap } from './IllustrativeMap'
import { LandingHeader } from './LandingHeader'
import { HERO_IMAGE_ALT, HERO_IMAGE_URL, LOGIN_PATH } from './landingConfig'
import { CAPABILITIES, FOOTER_LINKS, GRIEVANCE_FLOW, PILLARS, STAKEHOLDERS, STEPS } from './landingContent'
import styles from './landing.module.css'

/**
 * The public face of SP-MIS (unauthenticated).
 *
 * This page reads NOTHING from the API. No query hooks, no counts, no coverage, no
 * programme names — an anonymous visitor is entitled to know what the system is for, not
 * what is in it, and everything in it is personal data about people who did not consent
 * to a public page (NDPA/NDPR). The map here is decorative by construction; see
 * {@link IllustrativeMap}.
 *
 * It also renders none of the authenticated shell — no nav rail, no top bar, no layout
 * that would try to resolve a session. It is a document with a door in it, and the door
 * is {@link LOGIN_PATH}.
 */
export function LandingPage() {
  const hasHeroImage = HERO_IMAGE_URL !== ''

  return (
    <div className={styles.page} id="top">
      <a href="#main" className={styles.skipLink}>
        Skip to content
      </a>

      <LandingHeader />

      <main id="main">
        {/* ─────────────────────────────────────────────────────────── 2. Hero ── */}
        <section className={styles.hero} data-has-image={hasHeroImage}>
          {/*
           * LICENSED PHOTOGRAPH SLOT.
           *
           * Set VITE_LANDING_HERO_IMAGE (see landingConfig.ts) and the photograph
           * renders here beneath the legibility overlay. No stock or generated image
           * ships in the repo: a photograph on a government page shows real people, and
           * clearing rights and consent for that is the communications team's call.
           * Until one is set, the gradient below is the finished treatment.
           */}
          {hasHeroImage && (
            <img
              className={styles.heroImage}
              src={HERO_IMAGE_URL}
              alt={HERO_IMAGE_ALT}
              aria-hidden={HERO_IMAGE_ALT === '' || undefined}
            />
          )}
          <div className={styles.heroOverlay} aria-hidden="true" />

          <div className={styles.heroInner}>
            <p className={styles.eyebrow}>Jigawa State Government</p>
            <h1 className={styles.heroTitle}>Social Protection Management Information System</h1>
            <p className={styles.heroLead}>
              Connecting people, programmes and services across every ministry, department and agency
              delivering social protection in Jigawa State.
            </p>
            <div className={styles.heroActions}>
              <ButtonLink to={LOGIN_PATH} size="lg" rightIcon={ArrowRight}>
                Login
              </ButtonLink>
              <a href="#about" className={styles.heroSecondary}>
                What is SP-MIS?
              </a>
            </div>
          </div>
        </section>

        {/* ──────────────────────────────────────────────── 3. What is SP-MIS ── */}
        <section className={styles.section} id="about" aria-labelledby="about-heading">
          <div className={styles.sectionInner}>
            <p className={styles.eyebrowDark}>About</p>
            <h2 className={styles.sectionTitle} id="about-heading">
              One record of social protection, shared by the agencies that deliver it
            </h2>
            <p className={styles.sectionLead}>
              Each agency used to keep its own list, which meant the same household could be enrolled
              several times over while no one could see it happening. SP-MIS replaces those separate
              lists with shared data under clear ownership.
            </p>

            <ul className={styles.pillars}>
              {PILLARS.map((pillar) => (
                <li key={pillar.title} className={styles.pillar}>
                  <span className={styles.pillarIcon} aria-hidden="true">
                    <Icon icon={pillar.icon} size={22} />
                  </span>
                  <h3 className={styles.pillarTitle}>{pillar.title}</h3>
                  <p className={styles.pillarBody}>{pillar.body}</p>
                </li>
              ))}
            </ul>
          </div>
        </section>

        {/* ──────────────────────────────────────── 4. What SP-MIS provides ── */}
        <section className={styles.sectionMint} id="programmes" aria-labelledby="provides-heading">
          <div className={styles.sectionInner}>
            <p className={styles.eyebrowDark}>Capabilities</p>
            <h2 className={styles.sectionTitle} id="provides-heading">
              What SP-MIS provides
            </h2>

            <ul className={styles.capabilities}>
              {CAPABILITIES.map((capability) => (
                <li key={capability.title} className={styles.capability}>
                  <span className={styles.capabilityIcon} aria-hidden="true">
                    <Icon icon={capability.icon} size={20} />
                  </span>
                  <h3 className={styles.capabilityTitle}>{capability.title}</h3>
                  <p className={styles.capabilityBody}>{capability.body}</p>
                </li>
              ))}
            </ul>
          </div>
        </section>

        {/* ───────────────────────────────────────────────── 5. How it works ── */}
        <section className={styles.section} aria-labelledby="how-heading">
          <div className={styles.sectionInner}>
            <p className={styles.eyebrowDark}>How it works</p>
            <h2 className={styles.sectionTitle} id="how-heading">
              From a programme to the evidence it produces
            </h2>
            <p className={styles.sectionLead}>
              The steps run in this order because each one depends on the last: nothing can be
              delivered to someone who is not registered, and no insight precedes the delivery it
              describes.
            </p>

            <ol className={styles.steps}>
              {STEPS.map((step) => (
                <li key={step.number} className={styles.step}>
                  <span className={styles.stepNumber} aria-hidden="true">
                    {step.number}
                  </span>
                  <h3 className={styles.stepTitle}>{step.title}</h3>
                  <p className={styles.stepBody}>{step.body}</p>
                </li>
              ))}
            </ol>
          </div>
        </section>

        {/* ────────────────────────────────────────────── 6. Across the state ── */}
        <section className={styles.sectionMint} aria-labelledby="state-heading">
          <div className={styles.sectionInner}>
            <p className={styles.eyebrowDark}>Across the state</p>
            <h2 className={styles.sectionTitle} id="state-heading">
              Built for every local government area
            </h2>
            <p className={styles.sectionLead}>
              Programmes reach communities in all 27 local government areas of Jigawa State. Where
              delivery has actually reached, and how far, is operational information. It belongs to
              the people accountable for it, not to a public page.
            </p>

            <IllustrativeMap />
          </div>
        </section>

        {/* ────────────────────────────────────────── 7. Your voice matters ── */}
        <section className={styles.section} id="grievance-redress" aria-labelledby="grm-heading">
          <div className={styles.sectionInner}>
            <p className={styles.eyebrowDark}>Grievance redress</p>
            <h2 className={styles.sectionTitle} id="grm-heading">
              Your voice matters
            </h2>
            <p className={styles.sectionLead}>
              If you have a question or a complaint about a social protection programme, raise it with
              the agency delivering it, through its grievance officer or intake desk. From there it is
              logged in SP-MIS and followed until it reaches an outcome, so nothing depends on who
              happened to take the call.
            </p>

            <ol className={styles.flow} aria-label="How a grievance travels">
              {GRIEVANCE_FLOW.map((stage, i) => (
                <li key={stage} className={styles.flowStage}>
                  <span className={styles.flowLabel}>{stage}</span>
                  {i < GRIEVANCE_FLOW.length - 1 && (
                    <Icon icon={ArrowRight} size={16} className={styles.flowArrow} aria-hidden="true" />
                  )}
                </li>
              ))}
            </ol>

            <p className={styles.sectionNote}>
              {/*
               * No public "submit a grievance" control, deliberately. Intake runs through
               * MDA channels with an officer who can identify the person and the
               * programme; a form here would collect personal data from an anonymous
               * visitor into a queue with no owner.
               */}
              <a href="#contact" className={styles.textLink}>
                Learn about grievance redress
                <Icon icon={ArrowRight} size={15} aria-hidden="true" />
              </a>
            </p>
          </div>
        </section>

        {/* ────────────────────────────────── 8. Connecting the ecosystem ── */}
        <section className={styles.sectionMint} id="contact" aria-labelledby="ecosystem-heading">
          <div className={styles.sectionInner}>
            <p className={styles.eyebrowDark}>Who it serves</p>
            <h2 className={styles.sectionTitle} id="ecosystem-heading">
              Connecting the ecosystem
            </h2>

            <ul className={styles.stakeholders}>
              {STAKEHOLDERS.map((stakeholder) => (
                <li key={stakeholder.title} className={styles.stakeholder}>
                  <span className={styles.stakeholderIcon} aria-hidden="true">
                    <Icon icon={stakeholder.icon} size={20} />
                  </span>
                  <div>
                    <h3 className={styles.stakeholderTitle}>{stakeholder.title}</h3>
                    <p className={styles.stakeholderBody}>{stakeholder.body}</p>
                  </div>
                </li>
              ))}
            </ul>
          </div>
        </section>

        {/* ───────────────────────────────────────────────── 9. Access CTA ── */}
        <section className={styles.cta} aria-labelledby="access-heading">
          <div className={styles.ctaInner}>
            <p className={styles.eyebrow}>Access</p>
            <h2 className={styles.ctaTitle} id="access-heading">
              Access SP-MIS
            </h2>
            <p className={styles.ctaLead}>
              Authorised users can access the platform using their assigned credentials. Accounts are
              issued by your ministry, department or agency. SP-MIS has no public sign-up.
            </p>
            <ButtonLink to={LOGIN_PATH} size="lg" rightIcon={ArrowRight}>
              Login
            </ButtonLink>
            <p className={styles.ctaNote}>
              <Icon icon={ShieldCheck} size={16} aria-hidden="true" />
              Access is role-based and every action is audited.
            </p>
          </div>
        </section>
      </main>

      {/* ───────────────────────────────────────────────────── 10. Footer ── */}
      <footer className={styles.footer} id="privacy">
        <div className={styles.footerInner}>
          <div className={styles.footerBrand}>
            <span className={styles.wordmarkBadge} aria-hidden="true">
              SP
            </span>
            <p className={styles.footerAbout}>
              The Jigawa State Social Protection Management Information System. It is the shared record
              through which the state’s ministries, departments and agencies coordinate, deliver and
              report on social protection.
            </p>
          </div>

          {FOOTER_LINKS.map((group) => (
            <nav key={group.heading} className={styles.footerGroup} aria-label={group.heading}>
              <h2 className={styles.footerHeading}>{group.heading}</h2>
              <ul>
                {group.links.map((link) => (
                  <li key={link.label}>
                    <a href={link.to} className={styles.footerLink}>
                      {link.label}
                    </a>
                  </li>
                ))}
              </ul>
            </nav>
          ))}
        </div>

        <div className={styles.footerBase}>
          <p>© 2026 Jigawa State Government · Implementing institution</p>
        </div>
      </footer>
    </div>
  )
}
