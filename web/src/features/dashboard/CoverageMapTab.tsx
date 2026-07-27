import { useEffect, useState } from 'react'
import { Building2, ClipboardList, Coins, House, Layers, MapPin, MapPinned, Users } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { cn } from '@/lib/utils/cn'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { BAND_COLORS, BAND_LABELS } from '@/features/gis/choropleth'
import { getMapLayers } from '@/features/gis/mapLayers'
import { useGisCoverage } from '@/features/gis/hooks'
import type { CoverageBand, CoverageRow, GisLevel } from '@/features/gis/types'
import { BandChoroplethMap } from './BandChoroplethMap'
import { filterParams } from './api'
import type { DashboardFilterValue } from './types'
import styles from './coverageMap.module.css'

const LEVELS: { value: GisLevel; label: string }[] = [
  { value: 'lga', label: 'LGA' },
  { value: 'ward', label: 'Ward' },
]
const BAND_ORDER: CoverageBand[] = ['green', 'yellow', 'red', 'grey']
const num = (n: number | null | undefined): string => (n ?? 0).toLocaleString()

/** Legend explaining the ABSOLUTE-count bands (with the configured thresholds). */
function BandLegend({ thresholds }: { thresholds: { green_min: number; yellow_min: number } }) {
  const note: Record<CoverageBand, string> = {
    green: `≥ ${num(thresholds.green_min)} beneficiaries`,
    yellow: `≥ ${num(thresholds.yellow_min)}`,
    red: `< ${num(thresholds.yellow_min)}`,
    grey: 'No coverage',
  }
  return (
    <div className={styles.legend} role="note" aria-label="Coverage bands">
      <span className={styles.legendTitle}>Beneficiaries / area</span>
      {BAND_ORDER.map((b) => (
        <span key={b} className={styles.legendItem}>
          <span className={styles.legendSwatch} style={{ background: BAND_COLORS[b] }} aria-hidden="true" />
          {BAND_LABELS[b]} <span className={styles.legendNote}>· {note[b]}</span>
        </span>
      ))}
    </div>
  )
}

/** Detail for a clicked area — ONLY the fields we hold (no population / poverty /
 * vulnerability / coverage-%: those have no denominator and are left as inert slots). */
function AreaDetail({ row, areaWord }: { row: CoverageRow | null; areaWord: string }) {
  if (!row) {
    return (
      <div className={styles.detail}>
        <p className={styles.detailEmpty}>
          <Icon icon={MapPin} size={16} /> Select {areaWord === 'LGA' ? 'an' : 'a'} {areaWord} to see its detail.
        </p>
      </div>
    )
  }
  const stats: { icon: typeof Users; label: string; value: string }[] = [
    { icon: House, label: 'Registered households', value: num(row.households) },
    { icon: Users, label: 'Registered individuals', value: num(row.beneficiary_count) },
    { icon: Users, label: 'Beneficiaries served', value: num(row.served) },
    { icon: ClipboardList, label: 'Active programmes', value: num(row.active_programmes) },
    { icon: Layers, label: 'Active activities', value: num(row.active_activities) },
    { icon: Coins, label: 'Budget spent', value: formatNaira(row.benefit_value) },
  ]
  return (
    <div className={styles.detail}>
      <div className={styles.detailHead}>
        <span className={styles.detailDot} data-band={row.band} aria-hidden="true" />
        <h3 className={styles.detailTitle}>{row.name}</h3>
        <span className={styles.detailBand}>{BAND_LABELS[row.band]}</span>
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
    </div>
  )
}

export interface CoverageMapTabProps {
  /** The active cross-cutting filter — the map refetches so its bands + detail reflect it. */
  filter?: DashboardFilterValue
}

/**
 * Coverage Map tab (Phase 6E, tab 5) on the Phase 6 GIS/PostGIS map. An ABSOLUTE-count
 * choropleth (green/yellow/red/grey — never a population %) with click-through area
 * detail limited to the fields we hold. Degrades to a ranked table when boundaries
 * aren't loaded. Contextual overlay layers plug in via {@link getMapLayers}.
 */
export function CoverageMapTab({ filter }: CoverageMapTabProps) {
  const { hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const [level, setLevel] = useState<GisLevel>('lga')
  const [selectedCode, setSelectedCode] = useState<string | null>(null)
  const [enabled, setEnabled] = useState<Set<string>>(new Set())

  const { data, isLoading } = useGisCoverage(level, filterParams(filter), canView)

  // Clear the selection when the area level changes.
  useEffect(() => setSelectedCode(null), [level])

  const areaWord = level === 'lga' ? 'LGA' : 'Ward'
  const rows = data?.rows ?? []
  const thresholds = data?.bands ?? { green_min: 0, yellow_min: 0 }
  const selected = rows.find((r) => r.key === selectedCode) ?? null

  const overlays = getMapLayers()
  const activeOverlays = overlays.filter((o) => enabled.has(o.id))
  const toggleLayer = (id: string) =>
    setEnabled((prev) => {
      const next = new Set(prev)
      if (next.has(id)) next.delete(id)
      else next.add(id)
      return next
    })

  const ranked = [...rows].sort((a, b) => b.beneficiary_count - a.beneficiary_count)
  const areasCovered = rows.filter((r) => r.band !== 'grey' && r.beneficiary_count > 0).length

  return (
    <div className={styles.page}>
      <div className={styles.toolbar}>
        <div className={styles.toggle} role="group" aria-label="Area level">
          {LEVELS.map((l) => (
            <button key={l.value} type="button" className={cn(styles.toggleBtn, level === l.value && styles.toggleActive)} onClick={() => setLevel(l.value)}>
              {l.label}
            </button>
          ))}
        </div>
        <BandLegend thresholds={thresholds} />
      </div>

      {isLoading || !data ? (
        <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
          <Spinner size={28} label="Loading coverage" />
        </div>
      ) : (
        <>
          {data.mode === 'choropleth' && data.feature_collection ? (
            <div className={styles.mapCard}>
              <BandChoroplethMap data={data.feature_collection} selectedCode={selectedCode} onSelect={setSelectedCode} overlays={activeOverlays} />
              {overlays.length > 0 && (
                <div className={styles.layers} role="group" aria-label="Overlay layers">
                  <span className={styles.layersLabel}>Overlays</span>
                  {overlays.map((o) => (
                    <label key={o.id} className={styles.layerToggle}>
                      <input type="checkbox" checked={enabled.has(o.id)} onChange={() => toggleLayer(o.id)} />
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
                Boundary map data isn’t loaded yet, so coverage is shown as a ranked table. An administrator can load
                LGA/Ward boundaries with <code>php artisan gis:load-boundaries</code>.
              </span>
            </div>
          )}

          <div className={styles.grid}>
            <div className={styles.panel}>
              <div className={styles.panelHead}>
                <Icon icon={MapPinned} size={16} />
                <h2 className={styles.panelTitle}>Coverage by {areaWord}</h2>
                <span className={styles.panelSub}>
                  {num(areasCovered)} of {num(rows.length)} covered
                </span>
              </div>
              {ranked.length === 0 ? (
                <p className={styles.empty}>No coverage data for this scope yet.</p>
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
                        <span className={styles.areaDot} data-band={row.band} aria-hidden="true" />
                        <span className={styles.areaName}>{row.name}</span>
                        <span className={styles.areaValue}>{num(row.beneficiary_count)}</span>
                      </button>
                    </li>
                  ))}
                </ul>
              )}
            </div>

            <AreaDetail row={selected} areaWord={areaWord} />
          </div>
        </>
      )}
    </div>
  )
}
