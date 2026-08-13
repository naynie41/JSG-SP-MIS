import { describe, expect, it } from 'vitest'
import { linkFor } from './links'
import type { AppNotification } from './types'

/**
 * Where the bell sends the reader, per event type and per role.
 *
 * MDA Admins and Admins work in the six-module MDA workspace and no longer have the
 * generic operator rail, so a deep-link that resolved to `/referrals` would drop them
 * outside their console. Everyone else keeps the generic routes. These are the exact
 * event types the Phase 5 subscriber emits.
 */
const notification = (
  type: string,
  related: { type: string; id: string } | null = null,
): AppNotification => ({
  id: 'n1',
  type,
  subject: 'Subject',
  body: 'Body',
  payload: {},
  related,
  read_at: null,
  created_at: '2026-08-07T09:00:00+01:00',
})

describe('notification deep-linking', () => {
  /* ------------------------------------------------------------- referrals */

  it('sends a referral to its detail page for both consoles', () => {
    const n = notification('referral.accepted', { type: 'referral', id: 'r1' })
    // The lifecycle is driven on the same screen either way.
    expect(linkFor(n, 'mda_admin')).toBe('/referrals/r1')
    expect(linkFor(n, 'sp_coordination')).toBe('/referrals/r1')
  })

  it('routes an SLA breach to the same referral', () => {
    const n = notification('referral.sla_breached', { type: 'referral', id: 'r9' })
    expect(linkFor(n, 'mda_admin')).toBe('/referrals/r9')
  })

  /* ------------------------------------------------------------- approvals */

  it('sends a request-to-serve into the MDA’s Service Delivery module', () => {
    const n = notification('service_request.created', { type: 'service_request', id: 'sr1' })

    // Straight to the approvals queue, the tab the Overview counter also deep-links to.
    expect(linkFor(n, 'mda_admin')).toBe('/mda/service-delivery?tab=service-requests')
    expect(linkFor(n, 'mda_admin')).toBe('/mda/service-delivery?tab=service-requests')
    // A non-MDA role keeps the generic route.
    expect(linkFor(n, 'system_administrator')).toBe('/service-requests')
  })

  it('routes accepted and declined decisions to the same place', () => {
    for (const type of ['service_request.accepted', 'service_request.declined']) {
      const n = notification(type, { type: 'service_request', id: 'sr2' })
      expect(linkFor(n, 'mda_admin')).toBe('/mda/service-delivery?tab=service-requests')
    }
  })

  it('routes an ownership transfer request alongside the approvals', () => {
    const n = notification('ownership_transfer.requested', { type: 'ownership_transfer_request', id: 'ot1' })
    expect(linkFor(n, 'mda_admin')).toBe('/mda/service-delivery?tab=service-requests')
  })

  /* -------------------------------------------------------- duplicate alerts */

  it('sends a duplicate alert to Duplicate Resolution, not the import screen', () => {
    const n = notification('import.duplicates_surfaced', { type: 'import_batch', id: 'ib1' })

    // The news is "there is adjudication waiting", so the module that adjudicates is the
    // right landing place for an MDA user.
    expect(linkFor(n, 'mda_admin')).toBe('/mda/duplicate-resolution')
    // Outside the MDA console, the adjudication queue for that batch is the equivalent.
    expect(linkFor(n, 'system_administrator')).toBe('/imports/ib1/adjudicate')
  })

  /* ---------------------------------------------------------- import results */

  it('sends an import result to that batch', () => {
    const n = notification('import.completed', { type: 'import_batch', id: 'ib7' })
    expect(linkFor(n, 'mda_admin')).toBe('/imports/ib7')
    expect(linkFor(n, 'executive')).toBe('/imports/ib7')
  })

  it('distinguishes a duplicate alert from an import result on the same batch', () => {
    const batch = { type: 'import_batch', id: 'ib3' }
    // Same related record, different work — so different destinations.
    expect(linkFor(notification('import.duplicates_surfaced', batch), 'mda_admin'))
      .not.toBe(linkFor(notification('import.completed', batch), 'mda_admin'))
  })

  /* ------------------------------------------------------ system announcements */

  it('leaves a system announcement in the panel', () => {
    // A broadcast has no related record; the message IS the content, so there is nowhere
    // better to go and the bell must not navigate somewhere arbitrary.
    expect(linkFor(notification('system.announcement'), 'mda_admin')).toBeNull()
    expect(linkFor(notification('system.announcement'), 'executive')).toBeNull()
  })

  /* -------------------------------------------------------------- other events */

  it('routes a ready report to the reader’s own reports module', () => {
    const n = notification('report.ready', { type: 'report_run', id: 'run1' })
    expect(linkFor(n, 'mda_admin')).toBe('/mda/reports')
    expect(linkFor(n, 'executive')).toBe('/reports')
  })

  it('routes a grievance to its detail page', () => {
    const n = notification('grievance.assigned', { type: 'grievance', id: 'g1' })
    expect(linkFor(n, 'mda_admin')).toBe('/grievances/g1')
  })

  it('routes a graduation to the beneficiary', () => {
    const n = notification('beneficiary.graduated', { type: 'beneficiary', id: 'b1' })
    expect(linkFor(n, 'mda_admin')).toBe('/beneficiaries/b1')
  })

  /* -------------------------------------------------------------- robustness */

  it('never navigates on a related record with no id', () => {
    // A malformed or redacted reference must not produce `/referrals/undefined`.
    expect(linkFor(notification('referral.accepted', { type: 'referral', id: '' }), 'mda_admin')).toBeNull()
  })

  it('falls back sensibly for an import event with no batch id', () => {
    const n = notification('import.completed', { type: 'import_batch', id: '' })
    expect(linkFor(n, 'mda_admin')).toBe('/mda/beneficiaries')
    expect(linkFor(n, 'system_administrator')).toBe('/imports')
  })

  it('treats an unknown related type as unlinkable rather than guessing', () => {
    expect(linkFor(notification('something.new', { type: 'widget', id: 'w1' }), 'mda_admin')).toBeNull()
  })

  it('defaults to the generic routes when the role is unknown', () => {
    const n = notification('service_request.created', { type: 'service_request', id: 'sr1' })
    expect(linkFor(n)).toBe('/service-requests')
    expect(linkFor(n, null)).toBe('/service-requests')
  })
})
