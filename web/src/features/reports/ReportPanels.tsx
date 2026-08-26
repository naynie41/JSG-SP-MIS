import { useMemo, useState } from 'react'
import { Download, Pause, Play, Save, Table2 } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Checkbox } from '@/components/Field/Checkbox'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { Spinner } from '@/components/Spinner/Spinner'
import { statusVariant } from '@/components/Badge/statusVariant'
import {
  useDownloadRun,
  useExportReport,
  usePreviewReport,
  useReportRuns,
  useReportSchedules,
  useSaveDefinition,
  useUpdateSchedule,
} from './hooks'
import type { AdHocDataset, ReportFormat, ReportRun, ReportSchedule } from './types'
import styles from './reports.module.css'

/*
 * The reusable pieces of a reports screen, over the Phase 6 engine.
 *
 * These originally lived inside AdminReportsPage. They moved here when the MDA console
 * needed the same builder, schedules and run history: copying them would have been a
 * parallel reporting UI over the same endpoints, free to drift in what it permits.
 *
 * Nothing here decides entitlement. Datasets, dimensions, measures and formats all come
 * from the server catalogue, which releases only what the caller's scope allows — so a
 * dataset an MDA may not report on never reaches the UI, and a column that is not
 * selectable server-side cannot be selected here either.
 */

const FORMATS: { value: ReportFormat; label: string }[] = [
  { value: 'csv', label: 'CSV' },
  { value: 'xlsx', label: 'Excel' },
  { value: 'pdf', label: 'PDF' },
]

const sentenceCase = (s: string): string => {
  const words = s.replace(/[_-]/g, ' ')
  return words.charAt(0).toUpperCase() + words.slice(1)
}

function when(iso: string | null): string {
  if (!iso) return '—'
  const d = new Date(iso)
  return Number.isNaN(d.getTime())
    ? '—'
    : d.toLocaleString(undefined, { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}

export function ReportsLoading({ label }: { label: string }) {
  return (
    <div className={styles.loading}>
      <Spinner size={24} label={label} />
    </div>
  )
}

/* -------------------------------------------------------------- report builder */

/**
 * The ad-hoc builder over the engine's whitelisted datasets. Every report it produces is
 * an aggregate — counts and sums over group-by dimensions — so no personal record and no
 * identifier can be selected at all, whatever the caller's permissions.
 */
export function BuilderPanel({
  datasets,
  initialDataset,
  /** Copy for the "aggregate only" reassurance, which differs per console. */
  eyebrow = 'Aggregate only — no personal records',
}: {
  datasets: AdHocDataset[]
  initialDataset?: string
  eyebrow?: string
}) {
  const [datasetKey, setDatasetKey] = useState(initialDataset ?? datasets[0]?.key ?? '')
  const [groupBy, setGroupBy] = useState<string[]>([])
  const [measures, setMeasures] = useState<string[]>([])
  const [format, setFormat] = useState<ReportFormat>('xlsx')
  const [name, setName] = useState('')

  const preview = usePreviewReport()
  const exportReport = useExportReport()
  const saveDefinition = useSaveDefinition()

  const dataset = useMemo(() => datasets.find((d) => d.key === datasetKey), [datasets, datasetKey])
  const definition = { dataset: datasetKey, group_by: groupBy, measures }
  const canRun = datasetKey !== '' && measures.length > 0

  /**
   * A readable label for the run list — "Benefits (ledger) by programme, LGA".
   * Without one the engine falls back to a generic "Ad-hoc report" for every export.
   */
  const derivedName = useMemo(() => {
    if (!dataset) return ''
    const dims = groupBy
      .map((key) => dataset.dimensions.find((d) => d.key === key)?.label.toLowerCase())
      .filter(Boolean)
    return dims.length > 0 ? `${dataset.label} by ${dims.join(', ')}` : dataset.label
  }, [dataset, groupBy])

  function chooseDataset(key: string) {
    setDatasetKey(key)
    setGroupBy([])
    setMeasures([])
    preview.reset()
  }

  const toggle = (list: string[], set: (v: string[]) => void, key: string) =>
    set(list.includes(key) ? list.filter((k) => k !== key) : [...list, key])

  const result = preview.data

  return (
    <div className={styles.page}>
      <Card titleAs="h2" title="Build a report" eyebrow={eyebrow}>
        <div className={styles.filterBar}>
          <SelectField
            label="Dataset"
            value={datasetKey}
            onChange={(e) => chooseDataset(e.target.value)}
            options={datasets.map((d) => ({ value: d.key, label: d.label }))}
          />
          <SelectField
            label="Format"
            value={format}
            onChange={(e) => setFormat(e.target.value as ReportFormat)}
            options={FORMATS.map((f) => ({ value: f.value, label: f.label }))}
          />
        </div>

        {dataset && (
          <>
            <p className={styles.groupLabel}>Group by</p>
            <div className={styles.choiceRow}>
              {dataset.dimensions.map((d) => (
                <Checkbox
                  key={d.key}
                  label={d.label}
                  checked={groupBy.includes(d.key)}
                  onChange={() => toggle(groupBy, setGroupBy, d.key)}
                />
              ))}
            </div>

            <p className={styles.groupLabel}>Measures</p>
            <div className={styles.choiceRow}>
              {dataset.measures.map((m) => (
                <Checkbox
                  key={m.key}
                  label={m.label}
                  checked={measures.includes(m.key)}
                  onChange={() => toggle(measures, setMeasures, m.key)}
                />
              ))}
            </div>
          </>
        )}

        <div className={styles.filterActions}>
          <Button
            leftIcon={Table2}
            variant="secondary"
            disabled={!canRun}
            loading={preview.isPending}
            onClick={() => preview.mutate({ ...definition, name: derivedName })}
          >
            Preview
          </Button>
          <Button
            leftIcon={Download}
            disabled={!canRun}
            loading={exportReport.isPending}
            onClick={() => exportReport.mutate({ definition: { ...definition, name: derivedName }, format })}
          >
            Export
          </Button>
        </div>
        {!canRun && <p className={styles.muted}>Choose at least one measure to run this report.</p>}
      </Card>

      {result && (
        <Card
          titleAs="h2"
          title={result.title}
          eyebrow={`${result.row_count.toLocaleString()} row${result.row_count === 1 ? '' : 's'} · ${result.scope.label}`}
          flush
        >
          <DataTable
            caption="Report preview"
            rows={result.rows.map((cells, i) => ({ id: String(i), cells }))}
            getRowId={(r) => r.id}
            columns={result.columns.map((c, i) => ({
              key: `c${i}`,
              header: c.label,
              align: c.numeric ? ('right' as const) : undefined,
              render: (row: { cells: string[] }) => row.cells[i] ?? '',
            }))}
          />
          {result.truncated && <p className={styles.footnote}>Preview is capped — the export contains every row</p>}
        </Card>
      )}

      {result && (
        <Card titleAs="h2" title="Save for scheduling" eyebrow="Saved reports can be scheduled and delivered">
          <div className={styles.filterBar}>
            <TextField
              label="Report name"
              value={name}
              placeholder={derivedName || 'e.g. Monthly delivery summary'}
              onChange={(e) => setName(e.target.value)}
            />
          </div>
          <div className={styles.filterActions}>
            <Button
              leftIcon={Save}
              variant="secondary"
              disabled={name.trim() === ''}
              loading={saveDefinition.isPending}
              onClick={() => saveDefinition.mutate({ name: name.trim(), definition })}
            >
              Save report
            </Button>
          </div>
        </Card>
      )}
    </div>
  )
}

/* ------------------------------------------------------------------- schedules */

export function SchedulesPanel({
  canManage,
  footnote = 'A schedule delivers only to recipients whose own scope covers the report, so a scheduled report can never carry data outside the recipient’s reach',
}: {
  canManage: boolean
  footnote?: string
}) {
  const { data, isLoading, error } = useReportSchedules()
  const update = useUpdateSchedule()

  if (isLoading) return <ReportsLoading label="Loading scheduled reports" />
  if (error) return <p className={styles.muted}>Could not load scheduled reports. Please try again.</p>

  const schedules = data ?? []

  const columns: Column<ReportSchedule>[] = [
    { key: 'name', header: 'Report', render: (s) => <strong>{s.name}</strong> },
    { key: 'frequency', header: 'Frequency', render: (s) => sentenceCase(s.frequency) },
    { key: 'format', header: 'Format', render: (s) => <Badge variant="neutral" mono>{s.format}</Badge> },
    { key: 'delivery', header: 'Delivery', render: (s) => sentenceCase(s.delivery) },
    { key: 'recipients', header: 'Recipients', align: 'right', render: (s) => (s.recipient_user_ids ?? []).length },
    { key: 'last', header: 'Last run', render: (s) => s.last_run_on ?? 'never' },
    {
      key: 'status',
      header: 'Status',
      render: (s) => (
        <Badge variant={statusVariant(s.status)} dot>
          {s.status}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (s) =>
        canManage ? (
          <Button
            size="sm"
            variant="secondary"
            leftIcon={s.status === 'active' ? Pause : Play}
            disabled={update.isPending}
            onClick={() => update.mutate({ id: s.id, changes: { status: s.status === 'active' ? 'paused' : 'active' } })}
          >
            {s.status === 'active' ? 'Pause' : 'Resume'}
          </Button>
        ) : null,
    },
  ]

  return (
    <div className={styles.page}>
      <Card flush>
        <DataTable
          caption="Scheduled reports"
          rows={schedules}
          columns={columns}
          getRowId={(s) => s.id}
          emptyTitle="No scheduled reports"
        />
      </Card>
      <p className={styles.footnote}>{footnote}</p>
    </div>
  )
}

/* ------------------------------------------------------------------ recent runs */

export function RunsPanel({
  footnote = 'Every export is generated and audited by the shared reporting engine — this console adds datasets, not a second pipeline',
}: {
  footnote?: string
} = {}) {
  const { data, isLoading, error } = useReportRuns()
  const download = useDownloadRun()

  if (isLoading) return <ReportsLoading label="Loading recent exports" />
  if (error) return <p className={styles.muted}>Could not load recent exports. Please try again.</p>

  const runs = data?.items ?? []

  const columns: Column<ReportRun>[] = [
    { key: 'label', header: 'Report', render: (r) => r.report_label ?? r.report_key ?? '—' },
    { key: 'format', header: 'Format', render: (r) => <Badge variant="neutral" mono>{r.format}</Badge> },
    { key: 'rows', header: 'Rows', align: 'right', render: (r) => (r.row_count ?? 0).toLocaleString() },
    { key: 'requested', header: 'Requested', render: (r) => when(r.created_at) },
    {
      key: 'status',
      header: 'Status',
      render: (r) => (
        <div className={styles.stack2}>
          <Badge variant={statusVariant(r.status)} dot>
            {r.status}
          </Badge>
          {r.error ? <span className={styles.muted}>{r.error}</span> : null}
        </div>
      ),
    },
    {
      key: 'actions',
      header: '',
      align: 'right',
      render: (r) =>
        r.download_ready ? (
          <Button
            size="sm"
            variant="secondary"
            leftIcon={Download}
            disabled={download.isPending}
            onClick={() => download.mutate(r)}
          >
            Download
          </Button>
        ) : null,
    },
  ]

  return (
    <div className={styles.page}>
      <Card flush>
        <DataTable
          caption="Recent exports"
          rows={runs}
          columns={columns}
          getRowId={(r) => r.id}
          emptyTitle="No exports yet"
        />
      </Card>
      <p className={styles.footnote}>{footnote}</p>
    </div>
  )
}
