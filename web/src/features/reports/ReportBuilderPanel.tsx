import { useMemo, useState } from 'react'
import { BarChart3, Table2 } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { Badge } from '@/components/Badge/Badge'
import { SelectField } from '@/components/Field/SelectField'
import { Spinner } from '@/components/Spinner/Spinner'
import { Icon } from '@/components/Icon/Icon'
import { BuilderPanel } from './ReportPanels'
import { SegmentFilters } from './SegmentFilters'
import { useExportSegment, useSegmentDimensions, useSegmentPreview } from './hooks'
import type {
  AdHocDataset,
  ReportFormat,
  SegmentBreakdown,
  SegmentDefinitionInput,
  SegmentFilterInput,
  SegmentPreview,
} from './types'
import styles from './reports.module.css'

const FORMATS: { value: ReportFormat; label: string }[] = [
  { value: 'csv', label: 'CSV' },
  { value: 'xlsx', label: 'Excel' },
  { value: 'pdf', label: 'PDF' },
]

const PEOPLE = '__people__'

interface ReportBuilderPanelProps {
  /** Whitelisted aggregate datasets available to the caller's scope. */
  datasets: AdHocDataset[]
  canExport: boolean
}

/**
 * One place to build a report (PRD FR-RPT-03).
 *
 * Two builders used to sit in separate tabs, and the difference between them was a
 * distinction of implementation, not of intent: one aggregates a whitelisted dataset,
 * the other filters people. An officer does not arrive thinking "I need the ad-hoc
 * aggregator"; they arrive with a question about a subject. So the subject is the first
 * choice, and the right builder follows from it.
 */
export function ReportBuilderPanel({ datasets, canExport }: ReportBuilderPanelProps) {
  const [subject, setSubject] = useState<string>(PEOPLE)

  const subjects = useMemo(
    () => [
      { value: PEOPLE, label: 'People in the registry' },
      ...datasets.map((d) => ({ value: d.key, label: d.label })),
    ],
    [datasets],
  )

  return (
    <div className={styles.builder}>
      <div className={styles.builderSubject}>
        <SelectField
          label="What are you reporting on?"
          value={subject}
          onChange={(event) => setSubject(event.target.value)}
          options={subjects}
          helper={
            subject === PEOPLE
              ? 'Filter the people in your scope, then export the result.'
              : 'Group and total this dataset, then export the result.'
          }
        />
      </div>

      {subject === PEOPLE ? (
        // Filtering the registry only previews until Export is pressed, and the server
        // gates that, so this stays available on `reporting.view` as it always has.
        <SegmentBuilder />
      ) : canExport ? (
        <BuilderPanel datasets={datasets} initialDataset={subject} key={subject} />
      ) : (
        <p className={styles.muted}>
          Generating and exporting reports needs the reporting export permission.
        </p>
      )}
    </div>
  )
}

/* ------------------------------------------------------------ the people builder */

function SegmentBuilder() {
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
    () => ({ filters, breakdown: breakdown === '' ? null : breakdown }),
    [filters, breakdown],
  )

  if (catalogue.isLoading) {
    return (
      <div className={styles.dashLoading}>
        <Spinner size={20} label="Loading filters" />
      </div>
    )
  }

  const tier = catalogue.data?.tier ?? 'aggregate'
  const guardOn = catalogue.data?.cell_size_guard ?? true
  const minimum = catalogue.data?.minimum_cell_size ?? 5

  return (
    <div className={styles.segment}>
      <div className={styles.segmentNotice}>
        <Badge variant={tier === 'rows' ? 'info' : 'neutral'}>
          {tier === 'rows' ? 'You can see beneficiary rows' : 'Counts only — no beneficiary rows'}
        </Badge>
        {catalogue.data?.reveal_pii === false && <Badge variant="neutral">NIN and BVN masked</Badge>}
        {guardOn && <Badge variant="neutral">Groups under {minimum} withheld</Badge>}
      </div>

      <SegmentFilters dimensions={dimensions} value={filters} onChange={setFilters} />

      <div className={styles.segmentRun}>
        <SelectField
          label="Chart breakdown"
          value={breakdown}
          onChange={(event) => setBreakdown(event.target.value)}
          options={[
            { value: '', label: 'None' },
            ...dimensions
              .filter(
                (d) =>
                  d.groupable !== false && (d.kind === 'enum' || d.kind === 'lookup'),
              )
              .map((d) => ({ value: d.key, label: d.label })),
          ]}
        />
        <SelectField
          label="Export as"
          value={format}
          onChange={(event) => setFormat(event.target.value as ReportFormat)}
          options={FORMATS}
        />
        <Button onClick={() => preview.mutate(definition)} loading={preview.isPending}>
          Run report
        </Button>
        <Button
          variant="secondary"
          onClick={() => exportSegment.mutate({ definition, format })}
          loading={exportSegment.isPending}
        >
          Export
        </Button>
      </div>

      {result ? (
        <SegmentResult
          result={result}
          showChart={showChart}
          onToggleChart={() => setShowChart((v) => !v)}
        />
      ) : (
        <p className={styles.segmentIdle}>
          Add the filters you need, then run the report to see who matches.
        </p>
      )}
    </div>
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
              Fewer than {result.minimum_cell_size} people match. The count is withheld so
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
            <Icon icon={showChart ? Table2 : BarChart3} size={16} />
            {showChart ? 'Hide chart' : 'Show chart'}
          </Button>
        )}
      </div>

      {showChart && result.breakdown && <BreakdownChart breakdown={result.breakdown} />}

      {result.tier === 'aggregate' ? (
        <p className={styles.muted}>
          Your role receives aggregate reporting — counts and breakdowns, never the beneficiary
          registry itself.
        </p>
      ) : result.rows.length === 0 ? (
        <p className={styles.muted}>No beneficiaries match these filters.</p>
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
        </div>
      )}
    </div>
  )
}

function BreakdownChart({ breakdown }: { breakdown: SegmentBreakdown }) {
  const max = Math.max(1, ...breakdown.groups.map((g) => g.count ?? 0))

  return (
    <figure className={styles.breakdown}>
      <figcaption className={styles.breakdownTitle}>By {breakdown.label}</figcaption>
      <ul className={styles.breakdownList}>
        {breakdown.groups.map((group) => (
          <li key={group.key} className={styles.breakdownRow}>
            <span className={styles.breakdownLabel}>{group.label}</span>
            <span className={styles.breakdownTrack} aria-hidden>
              <span
                className={group.suppressed ? styles.breakdownBarHeld : styles.breakdownBar}
                style={{ width: group.suppressed ? '100%' : `${((group.count ?? 0) / max) * 100}%` }}
              />
            </span>
            <span className={styles.breakdownValue}>
              {group.suppressed ? `< ${breakdown.minimum}` : group.count?.toLocaleString()}
            </span>
          </li>
        ))}
      </ul>
      {breakdown.suppressed_groups > 0 && (
        <p className={styles.muted}>
          {breakdown.suppressed_groups} group{breakdown.suppressed_groups === 1 ? '' : 's'} withheld
          — fewer than {breakdown.minimum} people each.
        </p>
      )}
    </figure>
  )
}
