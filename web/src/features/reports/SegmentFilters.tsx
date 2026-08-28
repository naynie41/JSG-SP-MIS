import { useCallback, useEffect, useMemo, useRef, useState } from 'react'
import { ChevronDown, Plus, X } from 'lucide-react'
import { Icon } from '@/components/Icon/Icon'
import { TextField } from '@/components/Field/TextField'
import type { SegmentDimension, SegmentFilterInput } from './types'
import styles from './reports.module.css'

interface SegmentFiltersProps {
  dimensions: SegmentDimension[]
  value: Record<string, SegmentFilterInput>
  onChange: (next: Record<string, SegmentFilterInput>) => void
}

/** Long option lists get a search box; short ones would only gain a chore. */
const SEARCHABLE_FROM = 8

/** Stable identity so the option memo is not defeated by a fresh `[]` each render. */
const EMPTY_OPTIONS: { value: string; label: string }[] = []

const ADD_MENU = '__add__'

/**
 * Compose a segment from the schema's own fields (FR-RPT-03).
 *
 * Every filter is a PILL — one line reading `Gender: Female, Male ▾` — whose options
 * live in a menu that overlays the page rather than growing it. Four filters cost one
 * wrapped row, not four tall cards, and the row reads left to right as the query itself.
 *
 * The panel also starts EMPTY. It used to render every dimension at once, twenty-seven
 * LGA checkboxes among them, which asked the officer to read the whole schema before
 * asking their question.
 *
 * Checkboxes still live INSIDE the open menu: within one dimension the values are OR'd,
 * so more than one can be picked, and a control that hides that would misrepresent the
 * query. What is gone is the wall of them on the page.
 */
export function SegmentFilters({ dimensions, value, onChange }: SegmentFiltersProps) {
  const [openKey, setOpenKey] = useState<string | null>(null)
  const rootRef = useRef<HTMLDivElement>(null)

  const active = dimensions.filter((d) => value[d.key])
  const available = dimensions.filter((d) => !value[d.key])

  // One menu at a time, dismissed the way every menu is: click away, or Escape.
  useEffect(() => {
    if (openKey === null) return

    function onPointerDown(event: MouseEvent) {
      if (!rootRef.current?.contains(event.target as Node)) setOpenKey(null)
    }
    function onKey(event: KeyboardEvent) {
      if (event.key === 'Escape') setOpenKey(null)
    }

    document.addEventListener('mousedown', onPointerDown)
    document.addEventListener('keydown', onKey)
    return () => {
      document.removeEventListener('mousedown', onPointerDown)
      document.removeEventListener('keydown', onKey)
    }
  }, [openKey])

  const add = useCallback(
    (key: string) => {
      const dimension = dimensions.find((d) => d.key === key)
      if (!dimension) return
      const op = dimension.kind === 'age' || dimension.kind === 'date' ? 'between' : 'in'
      onChange({ ...value, [key]: { op, values: op === 'between' ? ['', ''] : [] } })
      setOpenKey(key) // opens straight onto its options
    },
    [dimensions, onChange, value],
  )

  function remove(key: string) {
    const next = { ...value }
    delete next[key]
    onChange(next)
    setOpenKey((current) => (current === key ? null : current))
  }

  function set(key: string, values: string[], op: 'in' | 'between') {
    onChange({ ...value, [key]: { op, values } })
  }

  return (
    <div className={styles.filters} ref={rootRef}>
      <div className={styles.filtersHead}>
        <h3 className={styles.filtersTitle}>Filters</h3>
        <p className={styles.filtersHint}>
          {active.length === 0
            ? 'No filters. Every beneficiary in your scope.'
            : 'All conditions must match. Within one filter, any selected value matches.'}
        </p>
      </div>

      <div className={styles.pillRow}>
        {active.map((dimension) => (
          <FilterPill
            key={dimension.key}
            dimension={dimension}
            filter={value[dimension.key]}
            open={openKey === dimension.key}
            onToggle={() => setOpenKey((k) => (k === dimension.key ? null : dimension.key))}
            onSet={(values, op) => set(dimension.key, values, op)}
            onRemove={() => remove(dimension.key)}
          />
        ))}

        {available.length > 0 && (
          <div className={styles.pillWrap}>
            <button
              type="button"
              className={styles.addButton}
              aria-expanded={openKey === ADD_MENU}
              aria-haspopup="menu"
              onClick={() => setOpenKey((k) => (k === ADD_MENU ? null : ADD_MENU))}
            >
              <Icon icon={Plus} size={15} />
              Add filter
            </button>

            {openKey === ADD_MENU && (
              <div className={styles.menu} role="menu" aria-label="Add a filter">
                <p className={styles.menuHead}>Fields you can filter on</p>
                <div className={styles.menuList}>
                  {available.map((dimension) => (
                    <button
                      key={dimension.key}
                      type="button"
                      role="menuitem"
                      className={styles.menuItem}
                      onClick={() => add(dimension.key)}
                    >
                      <span>{dimension.label}</span>
                      <span className={styles.menuOrigin}>
                        {dimension.canonical ? 'schema' : 'record'}
                      </span>
                    </button>
                  ))}
                </div>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  )
}

/**
 * One filter, closed to a pill.
 *
 * The pill carries its own selection, which is what lets it stay closed: the row of
 * pills is a readable statement of the query without opening anything.
 */
function FilterPill({
  dimension,
  filter,
  open,
  onToggle,
  onSet,
  onRemove,
}: {
  dimension: SegmentDimension
  filter: SegmentFilterInput
  open: boolean
  onToggle: () => void
  onSet: (values: string[], op: 'in' | 'between') => void
  onRemove: () => void
}) {
  const [search, setSearch] = useState('')
  const values = filter.values ?? []
  const options = dimension.options ?? EMPTY_OPTIONS
  const isRange = dimension.kind === 'age' || dimension.kind === 'date'

  const shown = useMemo(() => {
    const term = search.trim().toLowerCase()
    if (term === '') return options
    return options.filter((o) => o.label.toLowerCase().includes(term))
  }, [options, search])

  const selection = summarise(dimension, values)

  return (
    <div className={styles.pillWrap}>
      <span className={open ? `${styles.pill} ${styles.pillOpen}` : styles.pill}>
        <button
          type="button"
          className={styles.pillTrigger}
          aria-expanded={open}
          aria-haspopup="dialog"
          onClick={onToggle}
        >
          <span className={styles.pillName}>{dimension.label}</span>
          <span className={styles.pillValue}>{selection}</span>
          <Icon icon={ChevronDown} size={14} className={styles.pillChevron} />
        </button>
        <button
          type="button"
          className={styles.pillRemove}
          onClick={onRemove}
          aria-label={`Remove the ${dimension.label} filter`}
        >
          <Icon icon={X} size={13} />
        </button>
      </span>

      {open && (
        <div className={styles.menu} role="dialog" aria-label={dimension.label}>
          {isRange ? (
            <div className={styles.menuRange}>
              <TextField
                label={dimension.kind === 'age' ? 'From age' : 'From'}
                type={dimension.kind === 'age' ? 'number' : 'date'}
                value={values[0] ?? ''}
                onChange={(e) => onSet([e.target.value, values[1] ?? ''], 'between')}
              />
              <TextField
                label={dimension.kind === 'age' ? 'To age' : 'To'}
                type={dimension.kind === 'age' ? 'number' : 'date'}
                value={values[1] ?? ''}
                onChange={(e) => onSet([values[0] ?? '', e.target.value], 'between')}
              />
            </div>
          ) : options.length > 0 ? (
            <>
              {options.length >= SEARCHABLE_FROM && (
                <TextField
                  label={`Search ${dimension.label.toLowerCase()}`}
                  hideLabel
                  placeholder={`Search ${dimension.label.toLowerCase()}…`}
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                />
              )}
              <div className={styles.menuList}>
                {shown.map((option) => {
                  const checked = values.includes(option.value)
                  return (
                    <label key={option.value} className={styles.menuOption}>
                      <input
                        type="checkbox"
                        checked={checked}
                        onChange={() =>
                          onSet(
                            checked
                              ? values.filter((v) => v !== option.value)
                              : [...values, option.value],
                            'in',
                          )
                        }
                      />
                      <span>{option.label}</span>
                    </label>
                  )
                })}
                {shown.length === 0 && (
                  <p className={styles.menuEmpty}>No match for “{search}”.</p>
                )}
              </div>
              {values.length > 0 && (
                <button type="button" className={styles.menuClear} onClick={() => onSet([], 'in')}>
                  Clear selection
                </button>
              )}
            </>
          ) : (
            <TextField
              label={dimension.label}
              hideLabel
              placeholder="Type a value"
              helper="Separate several with commas"
              value={values.join(', ')}
              onChange={(e) =>
                onSet(
                  e.target.value
                    .split(',')
                    .map((v) => v.trim())
                    .filter((v) => v !== ''),
                  'in',
                )
              }
            />
          )}
        </div>
      )}
    </div>
  )
}

/** The pill's plain-language reading of its own selection. */
function summarise(dimension: SegmentDimension, values: string[]): string {
  const chosen = values.filter((v) => v !== '')
  if (chosen.length === 0) return 'Any'

  if (dimension.kind === 'age') {
    return chosen.length < 2 ? `${chosen[0]}+` : `${values[0]}–${values[1]}`
  }
  if (dimension.kind === 'date') {
    return chosen.length < 2 ? chosen[0] : `${values[0]} → ${values[1]}`
  }

  const labels = chosen.map((v) => dimension.options?.find((o) => o.value === v)?.label ?? v)
  return labels.length <= 2 ? labels.join(', ') : `${labels[0]} +${labels.length - 1}`
}
