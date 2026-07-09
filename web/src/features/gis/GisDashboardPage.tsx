import { useState } from 'react'
import { MapPinned } from 'lucide-react'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { cn } from '@/lib/utils/cn'
import { useAuth } from '@/lib/auth/AuthProvider'
import { formatNaira } from '@/lib/utils/money'
import { CoverageMap } from './CoverageMap'
import { CHOROPLETH_RAMP } from './choropleth'
import { useGisCoverage } from './hooks'
import type { CoverageMetric, CoverageRow, GisLevel } from './types'
import styles from './gis.module.css'

const METRICS: { value: CoverageMetric; label: string }[] = [
  { value: 'beneficiary_count', label: 'Beneficiaries' },
  { value: 'benefit_value', label: 'Benefit value' },
]

const LEVELS: { value: GisLevel; label: string }[] = [
  { value: 'lga', label: 'LGA' },
  { value: 'ward', label: 'Ward' },
]

function metricValue(row: CoverageRow, metric: CoverageMetric): number {
  return metric === 'benefit_value' ? row.benefit_value : row.beneficiary_count
}

function metricDisplay(row: CoverageRow, metric: CoverageMetric): string {
  return metric === 'benefit_value' ? formatNaira(row.benefit_value) : row.beneficiary_count.toLocaleString()
}

/** Ranked coverage bars — shown under the map, and as the no-boundaries fallback. */
function RankedCoverage({ rows, metric }: { rows: CoverageRow[]; metric: CoverageMetric }) {
  const sorted = [...rows].sort((a, b) => metricValue(b, metric) - metricValue(a, metric)).slice(0, 30)
  const max = Math.max(1, ...sorted.map((r) => metricValue(r, metric)))

  if (sorted.length === 0) {
    return <p className={styles.empty}>No coverage data for this scope yet.</p>
  }

  return (
    <div className={styles.bars}>
      {sorted.map((row) => (
        <div key={row.key} className={styles.barRow}>
          <span className={styles.barLabel} title={row.name}>{row.name}</span>
          <span className={styles.barTrack} aria-hidden="true">
            <span className={styles.barFill} style={{ width: `${Math.max(2, Math.round((metricValue(row, metric) / max) * 100))}%` }} />
          </span>
          <span className={styles.barValue}>{metricDisplay(row, metric)}</span>
        </div>
      ))}
    </div>
  )
}

/**
 * GIS coverage dashboard (PRD FR-GIS-01). Scope-aware coverage by LGA/Ward: a
 * choropleth when boundaries are loaded, and a ranked-table fallback when they aren't
 * (the page never breaks). Read-only.
 */
export function GisDashboardPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('dashboard.view')
  const [level, setLevel] = useState<GisLevel>('lga')
  const [metric, setMetric] = useState<CoverageMetric>('beneficiary_count')

  const { data, isLoading } = useGisCoverage(level, canView)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view coverage maps.</p>
      </Card>
    )
  }

  return (
    <div className={styles.page}>
      <div className={styles.head}>
        <div className={styles.title}>
          <span className="eyebrow">06 · Reporting</span>
          <h1 className="t-h1">Coverage map</h1>
          <p className={styles.lead}>
            Where social protection reaches across Jigawa — beneficiaries and benefits by
            {level === 'lga' ? ' LGA' : ' ward'}, within your scope.
          </p>
        </div>
        <div className={styles.toolbar}>
          <div className={styles.toggle} role="group" aria-label="Area level">
            {LEVELS.map((l) => (
              <button key={l.value} type="button" className={cn(styles.toggleBtn, level === l.value && styles.toggleActive)} onClick={() => setLevel(l.value)}>
                {l.label}
              </button>
            ))}
          </div>
          <div className={styles.toggle} role="group" aria-label="Metric">
            {METRICS.map((m) => (
              <button key={m.value} type="button" className={cn(styles.toggleBtn, metric === m.value && styles.toggleActive)} onClick={() => setMetric(m.value)}>
                {m.label}
              </button>
            ))}
          </div>
        </div>
      </div>

      {isLoading || !data ? (
        <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
          <Spinner size={28} label="Loading coverage" />
        </div>
      ) : (
        <>
          {data.mode === 'choropleth' && data.feature_collection ? (
            <Card eyebrow={level === 'lga' ? 'By LGA' : 'By ward'} title="Coverage choropleth">
              <CoverageMap data={data.feature_collection} metric={metric} />
              <div className={styles.legend} style={{ marginTop: 'var(--space-3)' }}>
                <span>Low</span>
                <span className={styles.legendScale}>
                  {CHOROPLETH_RAMP.map((c) => (
                    <span key={c} className={styles.legendSwatch} style={{ background: c }} />
                  ))}
                </span>
                <span>High</span>
                <span>· {metric === 'benefit_value' ? 'Benefit value' : 'Beneficiaries'}</span>
              </div>
            </Card>
          ) : (
            <div className={styles.fallbackNote}>
              <Icon icon={MapPinned} size={18} />
              <span>
                Boundary map data isn’t loaded yet, so coverage is shown as a ranked list. An
                administrator can load LGA/Ward boundaries with <code>php artisan gis:load-boundaries</code>.
              </span>
            </div>
          )}

          <Card eyebrow="Coverage" title={`By ${level === 'lga' ? 'LGA' : 'ward'}`}>
            <RankedCoverage rows={data.rows} metric={metric} />
          </Card>
        </>
      )}
    </div>
  )
}
