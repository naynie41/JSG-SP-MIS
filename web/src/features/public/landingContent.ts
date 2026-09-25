import {
  BarChart3,
  Building2,
  ClipboardList,
  HandHeart,
  Handshake,
  HeartHandshake,
  LineChart,
  MessageSquareWarning,
  Network,
  Users,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'

/**
 * Every word and figure on the public landing page.
 *
 * Kept as data so the page markup stays readable and so a reviewer can check the COPY —
 * which is the part with rules — in one place. Nothing here is a measurement: there are
 * no counts, no coverage figures, no budgets. The only numerals are the step numbers of
 * a sequence.
 */

export interface Pillar {
  icon: LucideIcon
  title: string
  body: string
}

/** Section 3 — what the system is, in three verbs. */
export const PILLARS: Pillar[] = [
  {
    icon: Network,
    title: 'Coordinate',
    body:
      'Ministries, departments and agencies work from one shared record instead of separate lists, ' +
      'so they can see where their programmes overlap before they deliver, not after.',
  },
  {
    icon: HandHeart,
    title: 'Deliver',
    body:
      'Each agency runs its own activities and records what it delivers, keeping ownership of the ' +
      'people it registered while still being able to refer them onward.',
  },
  {
    icon: LineChart,
    title: 'Monitor',
    body:
      'Officers and oversight bodies draw on the same evidence, so reporting is a query rather than ' +
      'an assembly job, and every figure traces back to a delivery record.',
  },
]

export interface Capability {
  icon: LucideIcon
  title: string
  body: string
}

/** Section 4 — the modules, described by what they let someone do. */
export const CAPABILITIES: Capability[] = [
  {
    icon: ClipboardList,
    title: 'Programmes',
    body:
      'A single state catalogue of social protection programmes. Agencies deliver against it, and no ' +
      'agency can create its own version of the same scheme.',
  },
  {
    icon: Users,
    title: 'Beneficiary registry',
    body:
      'A shared register with clear ownership and traceable provenance, where duplicate registration ' +
      'is caught as records arrive rather than cleaned up later.',
  },
  {
    icon: HeartHandshake,
    title: 'Service delivery',
    body:
      'Every benefit delivered is recorded against the activity that delivered it, so a person’s ' +
      'history is visible to the agencies entitled to see it.',
  },
  {
    icon: MessageSquareWarning,
    title: 'Grievance redress',
    body:
      'Complaints and appeals raised through agency channels are tracked to a resolution, with the ' +
      'time taken visible to whoever is accountable for it.',
  },
  {
    icon: BarChart3,
    title: 'Reporting and analytics',
    body:
      'Dashboards and exports for the people entitled to them, scoped to what each role may see, ' +
      'and drawn from delivery records rather than manual returns.',
  },
]

export interface Step {
  number: string
  title: string
  body: string
}

/**
 * Section 5 — the actual order of operations. The numbering is information here: a
 * service cannot be recorded before the person is registered, and insight cannot precede
 * the delivery it describes.
 */
export const STEPS: Step[] = [
  {
    number: '01',
    title: 'A programme is defined',
    body: 'The state catalogue defines the programme centrally, so every agency delivering it means the same thing by it.',
  },
  {
    number: '02',
    title: 'Beneficiaries are registered',
    body: 'An agency brings people into the register through its own activity, from data collected in the field or held in an existing system.',
  },
  {
    number: '03',
    title: 'A service is delivered',
    body: 'The benefit delivered is recorded against that activity, against that person, on the day it happened.',
  },
  {
    number: '04',
    title: 'Feedback is heard',
    body: 'Questions, complaints and appeals raised through agency channels are logged and followed to an outcome.',
  },
  {
    number: '05',
    title: 'Insight comes back',
    body: 'What was delivered becomes the evidence base for the next decision about who is covered and where the gaps are.',
  },
]

export interface Stakeholder {
  icon: LucideIcon
  title: string
  body: string
}

/** Section 8 — who the system connects, and what each one gets from it. */
export const STAKEHOLDERS: Stakeholder[] = [
  {
    icon: Building2,
    title: 'Government MDAs',
    body:
      'Run your own programmes and keep ownership of the records you originated, while seeing enough ' +
      'of the wider picture to avoid delivering twice.',
  },
  {
    icon: Network,
    title: 'SP Coordination Unit',
    body:
      'Maintain the state programme catalogue and the rules everyone works to, and monitor delivery ' +
      'across agencies from one place.',
  },
  {
    icon: Handshake,
    title: 'Development partners',
    body:
      'Follow the programmes you fund, and see what has been delivered against them and by whom, without ' +
      'access to the underlying personal records.',
  },
  {
    icon: Users,
    title: 'Beneficiaries and communities',
    body:
      'Register once and be known across programmes, with a route to raise a concern and have it ' +
      'answered by the agency responsible.',
  },
]

/** Section 7 — how a grievance actually travels. Descriptive, not a submission form. */
export const GRIEVANCE_FLOW = [
  'Grievance officer',
  'MDA intake',
  'SP-MIS',
  'MDA administrator',
  'Resolution',
]

export interface Faq {
  question: string
  answer: string
  /** An optional onward route. Only for answers where the next step is a real page. */
  link?: { label: string; to: string }
}

/**
 * Section 9 — the questions a member of the public actually arrives with.
 *
 * Most of these are answered by saying what the system does NOT do, and that is the
 * point: the commonest expectations of a government portal — sign up, look yourself up,
 * apply here — are all wrong for this one, and leaving a visitor to discover that by
 * hunting for a button is worse than telling them plainly.
 *
 * Two rules bind this copy, both inherited from the page itself:
 *
 *  - **No figures.** Not a count, not a coverage percentage, and not a timeframe. A
 *    "resolved within N days" answer would be an SLA commitment invented on a landing
 *    page; the real ones live with the agencies. `LandingPage.test.tsx` enforces the
 *    absence of digits, so a number added here fails the suite rather than shipping.
 *  - **No contact details.** No phone number, address or mailbox appears anywhere on
 *    this page, so no answer may imply one. Every route out of a question ends at the
 *    agency that holds the record, which is the body actually accountable for it.
 */
export const FAQS: Faq[] = [
  {
    question: 'Who is SP-MIS for?',
    answer:
      'It is a working system for the ministries, departments and agencies that deliver social ' +
      'protection in Jigawa State, and for the bodies that oversee them. This page is the public ' +
      'explanation of what it does; the system behind it is not open to the public.',
  },
  {
    question: 'Can I create an account?',
    answer:
      'No. SP-MIS has no public sign-up. Accounts are issued by the ministry, department or agency ' +
      'you work for, and each one carries a role that decides what its holder may see and do.',
  },
  {
    question: 'How do I register for a social protection programme?',
    answer:
      'Not here. Registration happens through the agency running the programme, as part of its own ' +
      'activity — in the field, or from records it already holds. SP-MIS records that registration; ' +
      'it is not a place to apply.',
  },
  {
    question: 'I am already registered. Can I look up my own record on this site?',
    answer:
      'No. Nothing about a registered person is published here, and there is no public lookup. Ask ' +
      'the agency that registered you: it holds your record and is the body accountable for it.',
  },
  {
    question: 'How do I raise a complaint or ask about a programme?',
    answer:
      'Through the agency delivering it — its grievance officer or intake desk. From there the ' +
      'matter is logged in SP-MIS and followed until it reaches an outcome, so nothing depends on ' +
      'who happened to take the call.',
    link: { label: 'How a grievance travels', to: '#grievance-redress' },
  },
  {
    question: 'Why does this page show no beneficiary numbers or coverage figures?',
    answer:
      'Because those are operational information about real people who never consented to a public ' +
      'page. They belong to the officers and oversight bodies accountable for them, and they are ' +
      'read inside the system, by the roles entitled to read them.',
  },
  {
    question: 'Where can I find policies, guidelines, tools and reports?',
    answer:
      'On the Resources page. Everything published there can be read and downloaded by anyone, ' +
      'without an account and without signing in.',
    link: { label: 'Open Resources', to: '/resources' },
  },
  {
    question: 'Is personal information kept safe?',
    answer:
      'Access is role-based, so a user reaches only the records their role allows, and every action ' +
      'taken on a record is written to an audit trail. Identifying details stay masked unless ' +
      'someone has been specifically permitted to see them.',
  },
  {
    question: 'Who runs SP-MIS?',
    answer:
      'The Jigawa State Government. The programme catalogue and the rules every agency works to are ' +
      'maintained centrally, while each agency owns and runs its own delivery.',
  },
]

/** Section 11 — footer link groups. Anchors stay on this page; the rest are real routes. */
export const FOOTER_LINKS: { heading: string; links: { label: string; to: string }[] }[] = [
  {
    heading: 'Quick links',
    links: [
      { label: 'About', to: '#about' },
      { label: 'Programmes', to: '#programmes' },
      { label: 'Grievance redress', to: '#grievance-redress' },
      { label: 'Contact', to: '#contact' },
    ],
  },
  {
    heading: 'Support',
    links: [
      { label: 'FAQs', to: '#faq' },
      { label: 'Help', to: '#contact' },
      { label: 'Privacy', to: '#privacy' },
      { label: 'Security', to: '#privacy' },
    ],
  },
]

/** Header navigation — anchors to sections on this page, never to authenticated routes. */
/**
 * Header navigation.
 *
 * Mostly in-page anchors, because the landing page is one long document. `/resources`
 * is the exception — a real route — and LandingHeader routes anything that does not
 * start with `#` through the router rather than reloading the application.
 */
export const NAV_LINKS = [
  { label: 'About', to: '#about' },
  { label: 'Programmes', to: '#programmes' },
  { label: 'Resources', to: '/resources' },
  { label: 'Grievance redress', to: '#grievance-redress' },
  { label: 'FAQs', to: '#faq' },
  { label: 'Contact', to: '#contact' },
]
