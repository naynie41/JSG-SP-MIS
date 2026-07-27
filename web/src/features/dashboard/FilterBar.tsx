import { SlidersHorizontal, X } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { titleCase } from '@/features/registry/constants'
import { EMPTY_FILTER } from './types'
import type { DashboardFilterValue, FilterOptions } from './types'
import styles from './filterBar.module.css'

const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

function activeCount(value: DashboardFilterValue): number {
  return Object.values(value).filter((v) => v !== null && v !== undefined && v !== '').length
}

/** A labelled native <select>; empty string maps back to null. */
function Field({
  label,
  value,
  onChange,
  children,
}: {
  label: string
  value: string
  onChange: (v: string) => void
  children: React.ReactNode
}) {
  return (
    <label className={styles.field}>
      <span className={styles.label}>{label}</span>
      <select className={styles.select} value={value} onChange={(e) => onChange(e.target.value)}>
        {children}
      </select>
    </label>
  )
}

export interface FilterBarProps {
  value: DashboardFilterValue
  options: FilterOptions
  onChange: (next: DashboardFilterValue) => void
  /** True while a filtered (live-recomputed) view is loading/showing. */
  live?: boolean
}

/**
 * Cross-cutting filter bar (Phase 6E) — period (year/quarter/month), programme, area
 * (LGA/ward) and MDA. Applies consistently across every tab: changing a filter refetches
 * the whole dashboard so all metrics/charts/map reflect it. Options are scoped by the
 * server, so a caller is only ever offered what they may see.
 */
export function FilterBar({ value, options, onChange, live }: FilterBarProps) {
  const n = activeCount(value)
  const set = <K extends keyof DashboardFilterValue>(key: K, raw: string) => {
    const parsed = raw === '' ? null : key === 'year' || key === 'quarter' || key === 'month' ? Number(raw) : raw
    onChange({ ...value, [key]: parsed as DashboardFilterValue[K] })
  }

  return (
    <div className={styles.bar} role="group" aria-label="Dashboard filters">
      <span className={styles.title}>
        <Icon icon={SlidersHorizontal} size={15} />
        Filters
        {live && <span className={styles.liveDot} title="Showing a filtered view" aria-label="Filtered view active" />}
      </span>

      <Field label="Year" value={value.year?.toString() ?? ''} onChange={(v) => set('year', v)}>
        <option value="">All years</option>
        {options.years.map((y) => (
          <option key={y} value={y}>
            {y}
          </option>
        ))}
      </Field>

      <Field label="Quarter" value={value.quarter?.toString() ?? ''} onChange={(v) => set('quarter', v)}>
        <option value="">All</option>
        {[1, 2, 3, 4].map((q) => (
          <option key={q} value={q}>
            Q{q}
          </option>
        ))}
      </Field>

      <Field label="Month" value={value.month?.toString() ?? ''} onChange={(v) => set('month', v)}>
        <option value="">All</option>
        {MONTHS.map((m, i) => (
          <option key={m} value={i + 1}>
            {m}
          </option>
        ))}
      </Field>

      <Field label="Programme" value={value.programme_id ?? ''} onChange={(v) => set('programme_id', v)}>
        <option value="">All programmes</option>
        {options.programmes.map((p) => (
          <option key={p.id} value={p.id}>
            {titleCase(p.name)}
          </option>
        ))}
      </Field>

      <Field label="MDA" value={value.mda_id ?? ''} onChange={(v) => set('mda_id', v)}>
        <option value="">All MDAs</option>
        {options.mdas.map((m) => (
          <option key={m.id} value={m.id}>
            {m.name}
          </option>
        ))}
      </Field>

      <Field label="LGA" value={value.lga ?? ''} onChange={(v) => set('lga', v)}>
        <option value="">All LGAs</option>
        {options.lgas.map((l) => (
          <option key={l} value={l}>
            {titleCase(l)}
          </option>
        ))}
      </Field>

      <Field label="Ward" value={value.ward ?? ''} onChange={(v) => set('ward', v)}>
        <option value="">All wards</option>
        {options.wards.map((w) => (
          <option key={w} value={w}>
            {titleCase(w)}
          </option>
        ))}
      </Field>

      {n > 0 && (
        <button type="button" className={styles.clear} onClick={() => onChange({ ...EMPTY_FILTER })}>
          <Icon icon={X} size={13} />
          Clear{n > 1 ? ` (${n})` : ''}
        </button>
      )}
    </div>
  )
}
