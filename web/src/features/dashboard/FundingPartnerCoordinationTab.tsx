import {
  Building2,
  CalendarX,
  CheckCircle2,
  ClipboardX,
  HandCoins,
  MapPin,
  Network,
  Radar,
  RefreshCw,
  Waypoints,
} from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { formatNaira } from '@/lib/utils/money'
import type { DashboardResponse, DrillFn, PartnerFunding } from './types'
import shell from './fundingPartner.module.css'
import styles from './partnerCoordination.module.css'

/* ------------------------------------------------------------------ helpers */

const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const cap = (s: string): string => s.charAt(0).toUpperCase() + s.slice(1)

function asOf(iso: string | null): string {
  if (!iso) return 'never'
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000)
  if (mins < 1) return 'moments ago'
  if (mins < 60) return `${mins} min ago`
  const hrs = Math.round(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  return new Date(iso).toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' })
}

/* --------------------------------------------------------------- components */

function LandscapeCard({ icon, label, value, hint }: { icon: LucideIcon; label: string; value: string; hint: string }) {
  return (
    <div className={styles.landCard}>
      <span className={styles.landIcon}>
        <Icon icon={icon} size={18} />
      </span>
      <span className={styles.landValue}>{value}</span>
      <span className={styles.landLabel}>{label}</span>
      <span className={styles.landHint}>{hint}</span>
    </div>
  )
}

function OverlapSection({ pf, onDrill }: { pf: PartnerFunding; onDrill?: DrillFn }) {
  const overlap = pf.programme_overlap
  const byLga = new Map<string, number>()
  for (const cell of overlap.cells) byLga.set(cell.lga, (byLga.get(cell.lga) ?? 0) + 1)

  return (
    <section className={`${shell.section} ${shell.reveal}`} aria-label="Programme overlap">
      <div className={shell.sectionHead}>
        <Icon icon={Radar} size={16} />
        <h2 className={shell.sectionTitle}>Programme overlap</h2>
        <span className={shell.sectionSub}>duplication &amp; reallocation</span>
      </div>
      <div className={shell.panel}>
        {overlap.count === 0 ? (
          <p className={styles.allClear}>
            <Icon icon={CheckCircle2} size={16} /> No overlap detected. No funded programme is run in the same LGA by
            another funder or MDA.
          </p>
        ) : (
          <>
            <p className={styles.overlapLede}>
              {num(overlap.count)} {overlap.count === 1 ? 'cell where a' : 'cells where a'} funded programme is also run
              in the same LGA by a different funder or MDA. That is a signal to coordinate or reallocate.
            </p>

            {/* Map indicator — overlapped LGAs at a glance (the full map lives in the GIS view). */}
            <div className={styles.mapIndicator} role="group" aria-label="Overlap by LGA">
              {[...byLga.entries()]
                .sort((a, b) => b[1] - a[1])
                .map(([lga, count]) =>
                  onDrill ? (
                    <button key={lga} type="button" className={styles.lgaChip} onClick={() => onDrill('investment', { lga })} title="See this LGA on the investment map">
                      <Icon icon={MapPin} size={12} /> {cap(lga)} <strong>{count}</strong>
                    </button>
                  ) : (
                    <span key={lga} className={styles.lgaChip}>
                      <Icon icon={MapPin} size={12} /> {cap(lga)} <strong>{count}</strong>
                    </span>
                  ),
                )}
            </div>

            <div className={styles.tableWrap}>
              <table className={styles.table}>
                <thead>
                  <tr>
                    <th scope="col">Programme</th>
                    <th scope="col">LGA</th>
                    <th scope="col" className={styles.numHead}>Other funders</th>
                    <th scope="col" className={styles.numHead}>Other MDAs</th>
                  </tr>
                </thead>
                <tbody>
                  {overlap.cells.map((cell) => (
                    <tr key={`${cell.programme_id}-${cell.lga}`}>
                      <td>{cell.programme ?? '—'}</td>
                      <td>{cap(cell.lga)}</td>
                      <td className={styles.numCell}>{num(cell.other_funders)}</td>
                      <td className={styles.numCell}>{num(cell.other_mdas)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </>
        )}
      </div>
    </section>
  )
}

export interface FundingPartnerCoordinationTabProps {
  data: DashboardResponse
  /** Drill from a programme-overlap LGA to the investment map (scoped to that LGA). */
  onDrill?: DrillFn
}

/**
 * Coordination (Phase 6P, tab 4) — the actor landscape around the partner's funded
 * programmes: funding organisations, government agencies (MDAs) and implementing agencies
 * active in them; a funding-by-partner table (amounts for YOUR OWN funding only — a
 * partner never sees another funder's money); PROGRAMME OVERLAP (the headline: same
 * catalog programme run in the same LGA by different funders/MDAs — table + map indicator,
 * to expose duplication / reallocation); and data sharing / sync health. Coordination
 * meetings and reporting-compliance are omitted (no such module) — inert slots only.
 */
export function FundingPartnerCoordinationTab({ data, onDrill }: FundingPartnerCoordinationTabProps) {
  const pf = data.metrics.partner_funding

  if (!pf) {
    return <p className={shell.empty}>No funded activities are attributed to you yet.</p>
  }

  const c = pf.coordination
  const ds = c.data_sharing

  return (
    <div className={shell.tabBody}>
      {/* ---------- PARTNER LANDSCAPE ---------- */}
      <section className={shell.reveal} aria-label="Partner landscape">
        <span className={shell.groupLabel}>Partner landscape · your funded programmes</span>
        <div className={styles.landscape}>
          <LandscapeCard icon={HandCoins} label="Funding organisations" value={num(c.landscape.funders)} hint="funders active here (incl. you)" />
          <LandscapeCard icon={Building2} label="Government agencies (MDAs)" value={num(c.landscape.government_agencies)} hint="implementing activities" />
          <LandscapeCard icon={Waypoints} label="Implementing agencies" value={num(c.landscape.implementing_agencies)} hint="delivering on your activities" />
        </div>
      </section>

      {/* ---------- PROGRAMME OVERLAP (headline) ---------- */}
      <OverlapSection pf={pf} onDrill={onDrill} />

      {/* ---------- FUNDING BY PARTNER ---------- */}
      <section className={`${shell.section} ${shell.reveal}`} aria-label="Funding by partner">
        <div className={shell.sectionHead}>
          <Icon icon={HandCoins} size={16} />
          <h2 className={shell.sectionTitle}>Funding by partner</h2>
          <span className={shell.sectionSub}>your funding only</span>
        </div>
        <div className={shell.panel}>
          <div className={styles.tableWrap}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th scope="col">Partner</th>
                  <th scope="col" className={styles.numHead}>Programmes</th>
                  <th scope="col" className={styles.numHead}>Allocated</th>
                  <th scope="col" className={styles.numHead}>Delivered</th>
                  <th scope="col" className={styles.numHead}>Beneficiaries</th>
                </tr>
              </thead>
              <tbody>
                {c.funding_by_partner.map((f) => (
                  <tr key={f.partner_id} data-self={f.is_self}>
                    <td>
                      {f.name}
                      {f.is_self && <span className={styles.youBadge}>You</span>}
                    </td>
                    <td className={styles.numCell}>{num(f.is_self ? f.funded_programmes : f.shared_programmes)}{!f.is_self && <span className={styles.sharedNote}> shared</span>}</td>
                    <td className={styles.numCell}>{f.is_self ? formatNaira(f.allocated ?? 0) : <span className={styles.hidden} title="Visible for your own funding only">—</span>}</td>
                    <td className={styles.numCell}>{f.is_self ? formatNaira(f.delivered_value ?? 0) : <span className={styles.hidden}>—</span>}</td>
                    <td className={styles.numCell}>{f.is_self ? num(f.net_unique_reached) : <span className={styles.hidden}>—</span>}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <p className={styles.tableFoot}>
            Amounts and reach are shown for <strong>your own funding only</strong> — a partner never sees another
            funder’s money. Co-funders appear so you can coordinate.
          </p>
        </div>
      </section>

      {/* ---------- GOVERNMENT AGENCIES (MDAs) ---------- */}
      {c.agencies.length > 0 && (
        <section className={`${shell.section} ${shell.reveal}`} aria-label="Implementing agencies">
          <div className={shell.sectionHead}>
            <Icon icon={Building2} size={16} />
            <h2 className={shell.sectionTitle}>Government agencies</h2>
            <span className={shell.sectionSub}>{num(c.agencies.length)} implementing</span>
          </div>
          <div className={styles.agencyGrid}>
            {c.agencies.map((a) => (
              <div key={a.id} className={styles.agency}>
                <span className={styles.agencyName}>{a.name ?? 'Agency'}</span>
                <span className={styles.agencyMeta}>
                  {num(a.activities)} {a.activities === 1 ? 'activity' : 'activities'} · {num(a.programmes)}{' '}
                  {a.programmes === 1 ? 'programme' : 'programmes'}
                </span>
              </div>
            ))}
          </div>
        </section>
      )}

      {/* ---------- DATA SHARING ---------- */}
      <section className={`${shell.section} ${shell.reveal}`} aria-label="Data sharing">
        <div className={shell.sectionHead}>
          <Icon icon={Network} size={16} />
          <h2 className={shell.sectionTitle}>Data sharing</h2>
          <span className={shell.sectionSub}>integrations &amp; sync health</span>
        </div>
        <div className={shell.panel}>
          <div className={styles.dsGrid}>
            <div className={styles.dsFig}>
              <span className={styles.dsVal}>{num(ds.agencies_integrated)}</span>
              <span className={styles.dsLabel}>Agencies integrated</span>
            </div>
            <div className={styles.dsFig}>
              <span className={styles.dsVal}>{num(ds.connectors)}</span>
              <span className={styles.dsLabel}>Connectors</span>
            </div>
            <div className={styles.dsFig}>
              <span className={styles.dsVal}>{num(ds.succeeded)}</span>
              <span className={styles.dsLabel}>Runs succeeded</span>
            </div>
            <div className={styles.dsFig} data-tone={ds.failed > 0 ? 'warn' : undefined}>
              <span className={styles.dsVal}>{num(ds.failed)}</span>
              <span className={styles.dsLabel}>Runs failed</span>
            </div>
            <div className={styles.dsFig}>
              <span className={styles.dsVal}>{num(ds.api_registrations)}</span>
              <span className={styles.dsLabel}>API registrations</span>
            </div>
            <div className={styles.dsFig}>
              <span className={styles.dsValSm}>
                <Icon icon={RefreshCw} size={13} /> {asOf(ds.last_run_at)}
              </span>
              <span className={styles.dsLabel}>Last sync</span>
            </div>
          </div>
          {ds.sources.length > 0 && (
            <div className={styles.sources}>
              <span className={styles.sourcesLabel}>Sources</span>
              {ds.sources.map((s) => (
                <span key={s} className={styles.sourceChip}>{cap(s)}</span>
              ))}
            </div>
          )}
          {ds.connectors === 0 && ds.total_runs === 0 && (
            <p className={styles.muted}>No data-sharing integrations for the implementing agencies yet.</p>
          )}
        </div>
      </section>

      {/* ---------- OMITTED (inert) ---------- */}
      <section className={`${shell.section} ${shell.reveal}`} aria-label="Not tracked here">
        <div className={styles.inertGrid}>
          <div className={styles.inert}>
            <span className={styles.inertLabel}>
              <Icon icon={CalendarX} size={13} /> Coordination meetings &amp; action items
            </span>
            <span className={styles.inertNote}>No meetings module. Not tracked in SP-MIS.</span>
          </div>
          <div className={styles.inert}>
            <span className={styles.inertLabel}>
              <Icon icon={ClipboardX} size={13} /> Reporting compliance
            </span>
            <span className={styles.inertNote}>No reporting workflow. Not tracked in SP-MIS.</span>
          </div>
        </div>
      </section>
    </div>
  )
}
