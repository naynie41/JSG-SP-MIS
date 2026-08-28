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

/** Section 10 — footer link groups. Anchors stay on this page; the rest are real routes. */
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
      { label: 'Help', to: '#contact' },
      { label: 'Privacy', to: '#privacy' },
      { label: 'Security', to: '#privacy' },
    ],
  },
]

/** Header navigation — anchors to sections on this page, never to authenticated routes. */
export const NAV_LINKS = [
  { label: 'About', to: '#about' },
  { label: 'Programmes', to: '#programmes' },
  { label: 'Grievance redress', to: '#grievance-redress' },
  { label: 'Contact', to: '#contact' },
]
