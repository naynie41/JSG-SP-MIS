import { useEffect, useMemo, useState } from 'react'
import { Building2, Coins, HandCoins, House, Layers, Lock, MapPin, MapPinned, PackageCheck, Users } from 'lucide-react'
import type { LucideIcon } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { cn } from '@/lib/utils/cn'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { BAND_COLORS } from '@/features/gis/choropleth'
import { getMapLayers } from '@/features/gis/mapLayers'
import { useGisCoverage } from '@/features/gis/hooks'
import { INVESTMENT_METRICS, OMITTED_LAYERS, QUADRANTS, QUADRANT_ORDER, classifyQuadrant, densityBand, median } from '@/features/gis/investment'
import type { InvestmentMetric } from '@/features/gis/investment'
import type { CoverageBand, CoverageFeatureCollection, CoverageRow, GisLevel } from '@/features/gis/types'
import { BandChoroplethMap } from './BandChoroplethMap'
import { filterParams } from './api'
import type { DashboardFilterValue, DrillFn } from './types'
import styles from './partnerInvestment.module.css'

const LEVELS: { value: GisLevel; label: string }[] = [
  { value: 'lga', label: 'LGA' },
  { value: 'ward', label: 'Ward' },
]
const DENSITY_ORDER: CoverageBand[] = ['green', 'yellow', 'red', 'grey']
const DENSITY_LABEL: Record<CoverageBand, string> = { green: 'High', yellow: 'Moderate', red: 'Low', grey: 'None' }
const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()
const fmtMetric = (metric: InvestmentMetric, v: number): string => (metric.kind === 'money' ? formatNaira(v) : num(v))

/** Density-band legend (relative high/moderate/low — never a population %). */
function DensityLegend({ metric }: { metric: InvestmentMetric }) {
  return (
    <div className={styles.legend} role="note" aria-label="Density bands">
      <span className={styles.legendTitle}>{metric.label}</span>
      {DENSITY_ORDER.map((b) => (
        <span key={b} className={styles.legendItem}>
          <span className={styles.legendSwatch} style={{ background: BAND_COLORS[b] }} aria-hidden="true" />
          {DENSITY_LABEL[b]}
        </span>
      ))}
    </div>
  )
}

/** LGA/Ward drill-down — only the fields we hold (no population / poverty / vulnerability). */
function AreaDetail({ row, areaWord, onDrillTab }: { row: CoverageRow | null; areaWord: string; onDrillTab?: (tab: string) => void }) {
  if (!row) {
    return (
      <section className={styles.detail} aria-label="Investment detail">
        <p className={styles.detailEmpty}>
          <Icon icon={MapPin} size={16} /> Select {areaWord === 'LGA' ? 'an' : 'a'} {areaWord} to see its investment detail.
        </p>
      </section>
    )
  }
  const stats: { icon: LucideIcon; label: string; value: string }[] = [
    { icon: House, label: 'Registered households', value: num(row.households) },
    { icon: Users, label: 'Beneficiaries', value: num(row.beneficiary_count) },
    { icon: Layers, label: 'Funded programmes', value: num(row.active_programmes) },
    { icon: HandCoins, label: 'Funding received', value: formatNaira(row.funding_allocated) },
    { icon: PackageCheck, label: 'Funds delivered', value: formatNaira(row.benefit_value) },
    { icon: Users, label: 'Coverage (served)', value: num(row.served) },
  ]
  return (
    <section className={styles.detail} aria-label="Investment detail">
      <div className={styles.detailHead}>
        <Icon icon={MapPinned} size={16} />
        <h3 className={styles.detailTitle}>{row.name}</h3>
      </div>
      <dl className={styles.detailStats}>
        {stats.map((s) => (
          <div key={s.label}>
            <dt>
              <Icon icon={s.icon} size={13} /> {s.label}
            </dt>
            <dd>{s.value}</dd>
          </div>
        ))}
      </dl>
      <div className={styles.detailMdas}>
        <span className={styles.detailMdaLabel}>
          <Icon icon={Building2} size={13} /> Implementing MDAs
        </span>
        {row.mdas.length === 0 ? <span className={styles.detailMuted}>—</span> : <span>{row.mdas.join(', ')}</span>}
      </div>
      <p className={styles.detailNote}>
        Funding received is committed <strong>budget</strong>; funds delivered is <strong>delivery value</strong>, not
        treasury expenditure.
      </p>
      {onDrillTab && (
        <div className={styles.detailDrill}>
          <button type="button" className={styles.drillBtn} onClick={() => onDrillTab('programmes')}>
            View programmes here
          </button>
          <button type="button" className={styles.drillBtn} onClick={() => onDrillTab('registry')}>
            View registry here
          </button>
        </div>
      )}
    </section>
  )
}

/**
 * Investment / Coverage Map (Phase 6P, tab 5) on the Phase 6 GIS map + 6E layer framework.
 * Colours LGAs/Wards by attributed FUNDING (activity budget) with green/yellow/red/grey
 * density bands, and toggles the layers we HAVE (funding distribution, beneficiary
 * concentration, programme coverage, funded programmes) — poverty/vulnerability are inert
 * slots (no data). A coverage-vs-funding QUADRANT analysis flags high-funding/low-coverage
 * (possible implementation problem), and click-through gives per-LGA investment detail.
 * Degrades to a ranked table when boundaries aren't loaded. Absolute coverage, never a %.
 */
export function FundingPartnerInvestmentTab({ filter, onDrill }: { filter?: DashboardFilterValue; onDrill?: DrillFn } = {}) {
  const { hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const [level, setLevel] = useState<GisLevel>('lga')
  const [metricId, setMetricId] = useState('funding')
  const [selectedCode, setSelectedCode] = useState<string | null>(null)
  const [enabled, setEnabled] = useState<Set<string>>(new Set())

  const { data, isLoading } = useGisCoverage(level, filterParams(filter), canView)

  useEffect(() => setSelectedCode(null), [level])

  const metric = INVESTMENT_METRICS.find((m) => m.id === metricId) ?? INVESTMENT_METRICS[0]!
  const areaWord = level === 'lga' ? 'LGA' : 'Ward'
  const rows = useMemo(() => data?.rows ?? [], [data])
  const coverageThreshold = data?.bands.yellow_min ?? 0
  const selected = rows.find((r) => r.key === selectedCode) ?? null

  const max = rows.reduce((m, r) => Math.max(m, metric.value(r)), 0)
  const fundingMidpoint = useMemo(() => median(rows.map((r) => r.funding_allocated)), [rows])
  const ranked = useMemo(() => [...rows].sort((a, b) => metric.value(b) - metric.value(a)), [rows, metric])

  // Choropleth coloured by the SELECTED metric's density band.
  const featureCollection: CoverageFeatureCollection | null = useMemo(() => {
    if (!data?.feature_collection) return null
    return {
      type: 'FeatureCollection',
      features: data.feature_collection.features.map((f) => ({
        ...f,
        properties: { ...f.properties, band: densityBand(metric.value(f.properties), max) },
      })),
    }
  }, [data, metric, max])

  // Coverage-vs-funding quadrants (funding = budget; coverage = ABSOLUTE served).
  const quadrants = useMemo(() => {
    const buckets = { strong: [] as CoverageRow[], review: [] as CoverageRow[], efficient: [] as CoverageRow[], emerging: [] as CoverageRow[] }
    for (const r of rows) {
      if (r.funding_allocated <= 0 && r.served <= 0) continue
      buckets[classifyQuadrant(r.funding_allocated, r.served, fundingMidpoint, coverageThreshold)].push(r)
    }
    return buckets
  }, [rows, fundingMidpoint, coverageThreshold])

  const overlays = getMapLayers()
  const activeOverlays = overlays.filter((o) => enabled.has(o.id))
  const toggleOverlay = (id: string) =>
    setEnabled((prev) => {
      const next = new Set(prev)
      if (next.has(id)) next.delete(id)
      else next.add(id)
      return next
    })

  return (
    <div className={styles.page}>
      {/* ---------- TOOLBAR: level + layers + legend ---------- */}
      <div className={styles.toolbar}>
        <div className={styles.toggle} role="group" aria-label="Area level">
          {LEVELS.map((l) => (
            <button key={l.value} type="button" className={cn(styles.toggleBtn, level === l.value && styles.toggleActive)} onClick={() => setLevel(l.value)}>
              {l.label}
            </button>
          ))}
        </div>
        <DensityLegend metric={metric} />
      </div>

      <div className={styles.layers} role="group" aria-label="Map layers">
        <span className={styles.layersLabel}>Layer</span>
        {INVESTMENT_METRICS.map((m) => (
          <button key={m.id} type="button" className={cn(styles.layerBtn, metricId === m.id && styles.layerActive)} onClick={() => setMetricId(m.id)} title={m.hint} aria-pressed={metricId === m.id}>
            {m.label}
          </button>
        ))}
        {OMITTED_LAYERS.map((l) => (
          <span key={l.id} className={styles.layerSlot} title={`Not available: ${l.reason}`}>
            <Icon icon={Lock} size={11} /> {l.label}
          </span>
        ))}
      </div>

      {isLoading || !data ? (
        <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
          <Spinner size={28} label="Loading investment map" />
        </div>
      ) : (
        <>
          {data.mode === 'choropleth' && featureCollection ? (
            <div className={styles.mapCard}>
              <BandChoroplethMap
                data={featureCollection}
                selectedCode={selectedCode}
                onSelect={setSelectedCode}
                overlays={activeOverlays}
                areaWord={areaWord}
                leadOf={(p) => ({ label: metric.label, value: fmtMetric(metric, metric.value(p)) })}
              />
              {overlays.length > 0 && (
                <div className={styles.overlays} role="group" aria-label="Overlay layers">
                  <span className={styles.layersLabel}>Overlays</span>
                  {overlays.map((o) => (
                    <label key={o.id} className={styles.overlayToggle}>
                      <input type="checkbox" checked={enabled.has(o.id)} onChange={() => toggleOverlay(o.id)} />
                      {o.label}
                    </label>
                  ))}
                </div>
              )}
            </div>
          ) : (
            <div className={styles.fallbackNote}>
              <Icon icon={MapPinned} size={18} />
              <span>
                Boundary map data isn’t loaded yet, so investment is shown as a ranked table. An administrator can load
                LGA/Ward boundaries with <code>php artisan gis:load-boundaries</code>.
              </span>
            </div>
          )}

          {/* ---------- COVERAGE-vs-FUNDING QUADRANTS ---------- */}
          <section className={styles.quadWrap} aria-label="Coverage versus funding">
            <div className={styles.quadHead}>
              <Icon icon={Coins} size={16} />
              <h2 className={styles.quadTitle}>Coverage vs funding</h2>
              <span className={styles.quadSub}>funding (budget) × coverage (absolute)</span>
            </div>
            <div className={styles.quadGrid}>
              {QUADRANT_ORDER.map((q) => {
                const meta = QUADRANTS[q]
                const areas = quadrants[q]
                return (
                  <div key={q} className={styles.quad} data-tone={meta.tone}>
                    <div className={styles.quadTop}>
                      <span className={styles.quadLabel}>{meta.label}</span>
                      <span className={styles.quadCount}>{num(areas.length)}</span>
                    </div>
                    <p className={styles.quadDetail}>{meta.detail}</p>
                    <div className={styles.quadAreas}>
                      {areas.length === 0 ? (
                        <span className={styles.detailMuted}>—</span>
                      ) : (
                        areas
                          .slice(0, 8)
                          .map((r) => (
                            <button key={r.key} type="button" className={styles.quadChip} onClick={() => setSelectedCode(r.key)}>
                              {r.name}
                            </button>
                          ))
                      )}
                    </div>
                  </div>
                )
              })}
            </div>
          </section>

          {/* ---------- RANKED LIST + DRILL-DOWN ---------- */}
          <div className={styles.grid}>
            <div className={styles.panel}>
              <div className={styles.panelHead}>
                <Icon icon={MapPinned} size={16} />
                <h2 className={styles.panelTitle}>
                  {metric.label} by {areaWord}
                </h2>
              </div>
              {ranked.length === 0 ? (
                <p className={styles.empty}>No investment data for this scope yet.</p>
              ) : (
                <ul className={styles.areaList}>
                  {ranked.map((row) => (
                    <li key={row.key}>
                      <button
                        type="button"
                        className={cn(styles.areaRow, selectedCode === row.key && styles.areaActive)}
                        onClick={() => setSelectedCode(row.key)}
                        aria-pressed={selectedCode === row.key}
                      >
                        <span className={styles.areaDot} style={{ background: BAND_COLORS[densityBand(metric.value(row), max)] }} aria-hidden="true" />
                        <span className={styles.areaName}>{row.name}</span>
                        <span className={styles.areaValue}>{fmtMetric(metric, metric.value(row))}</span>
                      </button>
                    </li>
                  ))}
                </ul>
              )}
            </div>

            <AreaDetail
              row={selected}
              areaWord={areaWord}
              onDrillTab={onDrill && level === 'lga' && selected ? (t) => onDrill(t, { lga: selected.key }) : undefined}
            />
          </div>
        </>
      )}
    </div>
  )
}
