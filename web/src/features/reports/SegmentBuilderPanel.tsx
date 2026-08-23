import { useMemo, useState } from 'react'
import { BarChart3, Table2 } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { useExportSegment, useSegmentDimensions, useSegmentPreview } from './hooks'
import type {
  ReportFormat,
  SegmentBreakdown,
  SegmentDefinitionInput,
  SegmentDimension,
  SegmentFilterInput,
  SegmentPreview,
} from './types'
import styles from './reports.module.css'

const FORMATS: { value: ReportFormat; label: string }[] = [
  { value: 'csv', label: 'CSV' },
  { value: 'xlsx', label: 'Excel' },
  { value: 'pdf', label: 'PDF' },
]

/**
 * The filtered report builder (PRD FR-RPT-03).
 *
 * Compose a population from segmentable dimensions, run it, and export it. The filter
 * list is NOT hard-coded here — it is fetched from the API, which derives it from the
 * canonical schema, so a newly segmentable field appears in this panel without a
 * frontend change.
 *
 * The panel shows the caller their own limits rather than hiding them: which tier they
 * are on, whether identifiers are masked, and whether small groups are being withheld.
 * A user who cannot see why a number is missing assumes the report is broken.
 */
export function SegmentBuilderPanel() {
  const catalogue = useSegmentDimensions()
  const preview = useSegmentPreview()
  const exportSegment = useExportSegment()

  const [filters, setFilters] = useState<Record<string, SegmentFilterInput>>({})
  const [breakdown, setBreakdown] = useState('')
  const [format, setFormat] = useState<ReportFormat>('csv')
  const [showChart, setShowChart] = useState(false)

  const dimensions = catalogue.data?.dimensions ?? []
  const result = preview.data as SegmentPreview | undefined

  const definition: SegmentDefinitionInput = useMemo(
    () => ({
      filters,
      breakdown: breakdown === '' ? null : breakdown,
    }),
    [filters, breakdown],
  )

  function setValues(key: string, values: string[], op?: 'in' | 'between') {
    setFilters((current) => {
      const next = { ...current }
      if (values.filter((v) => v !== '').length === 0) {
        delete next[key]
      } else {
        next[key] = { op, values }
      }
      return next
    })
  }

  if (catalogue.isLoading) {
    return <Card title="Segment builder">Loading filters…</Card>
  }

  const tier = catalogue.data?.tier ?? 'aggregate'
  const guardOn = catalogue.data?.cell_size_guard ?? true
  const minimum = catalogue.data?.minimum_cell_size ?? 5

  return (
    <Card
      title="Segment builder"
      titleAs="h2"
      actions={
        <div className={styles.tierBadges}>
          <Badge variant={tier === 'rows' ? 'info' : 'neutral'}>
            {tier === 'rows' ? 'Beneficiary rows' : 'Aggregates only'}
          </Badge>
          {catalogue.data?.reveal_pii === false && <Badge variant="neutral">Identifiers masked</Badge>}
        </div>
      }
    >
      <p className={styles.note}>
        Filters combine with AND across dimensions and OR within one. Identity fields (NIN, BVN,
        phone, name) describe individuals rather than groups, so they are never offered here and are
        masked in any output.
        {guardOn && ` Groups smaller than ${minimum} are withheld to prevent re-identification.`}
      </p>

      <div className={styles.filterGrid}>
        {dimensions.map((dimension) => (
          <DimensionField
            key={dimension.key}
            dimension={dimension}
            value={filters[dimension.key]}
            onChange={(values, op) => setValues(dimension.key, values, op)}
          />
        ))}
      </div>

      <div className={styles.builderActions}>
        <SelectField
          label="Break down by (chart)"
          value={breakdown}
          onChange={(event) => setBreakdown(event.target.value)}
          options={[
            { value: '', label: 'No breakdown' },
            ...dimensions
              .filter((d) => d.kind === 'enum' || d.kind === 'lookup')
              .map((d) => ({ value: d.key, label: d.label })),
          ]}
        />

        <SelectField
          label="Export format"
          value={format}
          onChange={(event) => setFormat(event.target.value as ReportFormat)}
          options={FORMATS}
        />

        <Button onClick={() => preview.mutate(definition)} loading={preview.isPending}>
          Run segment
        </Button>
        <Button
          variant="secondary"
          onClick={() => exportSegment.mutate({ definition, format })}
          loading={exportSegment.isPending}
        >
          Export
        </Button>
      </div>

      {result && <SegmentResult result={result} showChart={showChart} onToggleChart={() => setShowChart((v) => !v)} />}
    </Card>
  )
}

/** One filter control, shaped by the dimension's kind. */
function DimensionField({
  dimension,
  value,
  onChange,
}: {
  dimension: SegmentDimension
  value?: SegmentFilterInput
  onChange: (values: string[], op?: 'in' | 'between') => void
}) {
  const values = value?.values ?? []

  if (dimension.kind === 'age' || dimension.kind === 'date') {
    const isAge = dimension.kind === 'age'
    return (
      <fieldset className={styles.rangeField}>
        <legend className={styles.rangeLegend}>
          {dimension.label}
          {dimension.unit ? ` (${dimension.unit})` : ''}
        </legend>
        <TextField
          label="From"
          type={isAge ? 'number' : 'date'}
          value={values[0] ?? ''}
          onChange={(event) => onChange([event.target.value, values[1] ?? ''], 'between')}
        />
        <TextField
          label="To"
          type={isAge ? 'number' : 'date'}
          value={values[1] ?? ''}
          onChange={(event) => onChange([values[0] ?? '', event.target.value], 'between')}
        />
      </fieldset>
    )
  }

  if (dimension.options && dimension.options.length > 0) {
    return (
      <fieldset className={styles.multiField}>
        <legend className={styles.rangeLegend}>{dimension.label}</legend>
        {dimension.options.map((option) => {
          const checked = values.includes(option.value)
          return (
            <label key={option.value} className={styles.checkRow}>
              <input
                type="checkbox"
                checked={checked}
                onChange={() =>
                  onChange(
                    checked ? values.filter((v) => v !== option.value) : [...values, option.value],
                    'in',
                  )
                }
              />
              <span>{option.label}</span>
            </label>
          )
        })}
      </fieldset>
    )
  }

  // A lookup with no enumerable options (ward, programme id): free entry, comma-separated.
  return (
    <TextField
      label={dimension.label}
      value={values.join(', ')}
      placeholder="Any"
      helper="Separate several with commas"
      onChange={(event) =>
        onChange(
          event.target.value
            .split(',')
            .map((v) => v.trim())
            .filter((v) => v !== ''),
          'in',
        )
      }
    />
  )
}

function SegmentResult({
  result,
  showChart,
  onToggleChart,
}: {
  result: SegmentPreview
  showChart: boolean
  onToggleChart: () => void
}) {
  return (
    <div className={styles.segmentResult}>
      <div className={styles.resultHead}>
        <p className={styles.resultTotal}>
          {result.total_suppressed ? (
            <>
              Fewer than {result.minimum_cell_size} people match — the count is withheld so
              individuals cannot be identified.
            </>
          ) : (
            <>
              <strong>{result.total?.toLocaleString()}</strong> matching beneficiaries
            </>
          )}
        </p>
        {result.breakdown && !result.breakdown.unsupported && (
          <Button size="sm" variant="tertiary" onClick={onToggleChart}>
            {showChart ? <Table2 size={16} aria-hidden /> : <BarChart3 size={16} aria-hidden />}
            {showChart ? 'Hide chart' : 'Show chart'}
          </Button>
        )}
      </div>

      {showChart && result.breakdown && <BreakdownChart breakdown={result.breakdown} />}

      {result.tier === 'aggregate' ? (
        <p className={styles.note}>
          Your role receives aggregate reporting only — counts and breakdowns, never the beneficiary
          registry itself.
        </p>
      ) : (
        <div className={styles.tableScroll}>
          <table className={styles.resultTable}>
            <thead>
              <tr>
                {result.columns.map((column) => (
                  <th key={column.key} scope="col">
                    {column.label}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {result.rows.map((row, index) => (
                <tr key={index}>
                  {result.columns.map((column) => (
                    <td key={column.key}>{row[column.key] ?? '—'}</td>
                  ))}
                </tr>
              ))}
            </tbody>
          </table>
          {result.rows.length === 0 && <p className={styles.note}>No beneficiaries match these filters.</p>}
        </div>
      )}
    </div>
  )
}

/**
 * The breakdown as horizontal bars.
 *
 * A suppressed group keeps its row and says so. Dropping it would leak more than the
 * number does: a reader comparing two runs would learn the group exists and is small.
 */
function BreakdownChart({ breakdown }: { breakdown: SegmentBreakdown }) {
  const max = Math.max(1, ...breakdown.groups.map((g) => g.count ?? 0))

  return (
    <figure className={styles.chart}>
      <figcaption className={styles.chartCaption}>By {breakdown.label}</figcaption>
      <ul className={styles.chartList}>
        {breakdown.groups.map((group) => (
          <li key={group.key} className={styles.chartRow}>
            <span className={styles.chartLabel}>{group.label}</span>
            <span className={styles.chartTrack} aria-hidden>
              <span
                className={group.suppressed ? styles.chartBarSuppressed : styles.chartBar}
                style={{ width: group.suppressed ? '100%' : `${((group.count ?? 0) / max) * 100}%` }}
              />
            </span>
            <span className={styles.chartValue}>
              {group.suppressed ? `< ${breakdown.minimum}` : group.count?.toLocaleString()}
            </span>
          </li>
        ))}
      </ul>
      {breakdown.suppressed_groups > 0 && (
        <p className={styles.note}>
          {breakdown.suppressed_groups} group{breakdown.suppressed_groups === 1 ? '' : 's'} withheld
          (fewer than {breakdown.minimum} people each).
        </p>
      )}
    </figure>
  )
}
