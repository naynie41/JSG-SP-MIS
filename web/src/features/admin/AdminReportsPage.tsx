import { useMemo, useState } from 'react'
import { Download, FileBarChart, Pause, Play, Save, Table2 } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Button } from '@/components/Button/Button'
import { Card } from '@/components/Card/Card'
import { DataTable } from '@/components/DataTable/DataTable'
import type { Column } from '@/components/DataTable/DataTable'
import { Checkbox } from '@/components/Field/Checkbox'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { Spinner } from '@/components/Spinner/Spinner'
import { Tabs } from '@/components/Tabs/Tabs'
import { statusVariant } from '@/components/Badge/statusVariant'
import { useAuth } from '@/lib/auth/AuthProvider'
import {
  useDownloadRun,
  useExportReport,
  usePreviewReport,
  useReportDatasets,
  useReportRuns,
  useReportSchedules,
  useSaveDefinition,
  useUpdateSchedule,
} from '@/features/reports/hooks'
import type { AdHocDataset, ReportFormat, ReportRun, ReportSchedule } from '@/features/reports/types'
import styles from './admin.module.css'

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

function Loading({ label }: { label: string }) {
  return (
    <div style={{ display: 'grid', placeItems: 'center', padding: 'var(--space-8)' }}>
      <Spinner size={24} label={label} />
    </div>
  )
}

/* -------------------------------------------------------------- report builder */

/**
 * The ad-hoc builder over the engine's whitelisted datasets. Dimensions, measures and
 * formats all come FROM the server catalogue — nothing is hard-coded here, so a
 * dataset the caller may not use simply never appears, and a column that is not
 * selectable server-side cannot be selected in the UI either.
 */
function BuilderPanel({ datasets, initialDataset }: { datasets: AdHocDataset[]; initialDataset?: string }) {
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
   * A readable label for the run list — "Users & access by role, account status".
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
      <Card title="Build a report" eyebrow="Aggregate only — no personal records">
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
          {result.truncated && (
            <p className={styles.footnote}>Preview is capped — the export contains every row</p>
          )}
        </Card>
      )}

      {result && (
        <Card title="Save for scheduling" eyebrow="Saved reports can be scheduled and delivered">
          <div className={styles.filterBar}>
            <TextField
              label="Report name"
              value={name}
              placeholder={derivedName || 'e.g. Monthly access review'}
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

/* ------------------------------------------------------------------- catalogue */

/** The administrative report families, taken from the server's dataset catalogue. */
function CataloguePanel({ datasets, onBuild }: { datasets: AdHocDataset[]; onBuild: (key: string) => void }) {
  return (
    <div className={styles.page}>
      <div className={styles.demoGrid}>
        {datasets.map((d) => (
          <Card key={d.key} title={d.label} eyebrow={d.admin ? 'Administrative' : 'Delivery'}>
            <p className={styles.muted}>Group by {d.dimensions.map((dim) => dim.label).join(' · ')}</p>
            <div className={styles.filterActions}>
              <Button size="sm" variant="secondary" leftIcon={FileBarChart} onClick={() => onBuild(d.key)}>
                Build report
              </Button>
            </div>
          </Card>
        ))}
      </div>
      <p className={styles.footnote}>
        Every report is an aggregate — counts and sums over administrative attributes, never a personal record
      </p>
    </div>
  )
}

/* ------------------------------------------------------------------- schedules */

function SchedulesPanel({ canManage }: { canManage: boolean }) {
  const { data, isLoading, error } = useReportSchedules()
  const update = useUpdateSchedule()

  if (isLoading) return <Loading label="Loading scheduled reports" />
  if (error) return <p className={styles.muted}>Could not load scheduled reports. Please try again.</p>

  const schedules = data ?? []

  const columns: Column<ReportSchedule>[] = [
    { key: 'name', header: 'Report', render: (s) => <strong>{s.name}</strong> },
    { key: 'frequency', header: 'Frequency', render: (s) => sentenceCase(s.frequency) },
    { key: 'format', header: 'Format', render: (s) => <Badge variant="neutral" mono>{s.format}</Badge> },
    { key: 'delivery', header: 'Delivery', render: (s) => sentenceCase(s.delivery) },
    {
      key: 'recipients',
      header: 'Recipients',
      align: 'right',
      render: (s) => (s.recipient_user_ids ?? []).length,
    },
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
            onClick={() =>
              update.mutate({ id: s.id, changes: { status: s.status === 'active' ? 'paused' : 'active' } })
            }
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
      <p className={styles.footnote}>
        A schedule delivers only to recipients whose own scope covers the report — an administrative report can
        never be delivered outside the administration console
      </p>
    </div>
  )
}

/* ------------------------------------------------------------------ recent runs */

function RunsPanel() {
  const { data, isLoading, error } = useReportRuns()
  const download = useDownloadRun()

  if (isLoading) return <Loading label="Loading recent exports" />
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
      <p className={styles.footnote}>
        Every export is generated and audited by the shared reporting engine — the console adds datasets, not a
        second pipeline
      </p>
    </div>
  )
}

/* ------------------------------------------------------------------------ page */

/**
 * Reports (console section 9) — the administrative report catalogue over the Phase 6
 * reporting engine.
 *
 * There is no reporting logic here: the datasets, dimensions, measures and formats are
 * served by `/reports/adhoc/datasets`, previews and exports run through
 * `/reports/adhoc`, schedules through `/report-schedules`, and every file is produced
 * by the same exporter registry the rest of the platform uses. The administrative
 * datasets (users, organizations, catalogue, duplicates, audit, imports) are released
 * by the server only to a governance scope, so this page renders whatever it is
 * entitled to and never decides entitlement itself.
 */
export function AdminReportsPage() {
  const { hasPermission } = useAuth()
  const canExport = hasPermission('reporting.export')
  const { data, isLoading, error } = useReportDatasets()
  const [tab, setTab] = useState('catalogue')
  const [seedDataset, setSeedDataset] = useState<string | undefined>(undefined)

  const datasets = data ?? []
  const adminDatasets = datasets.filter((d) => d.admin)

  function buildFrom(key: string) {
    setSeedDataset(key)
    setTab('builder')
  }

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>Administration console</span>
        <h1 className={styles.pageTitle}>Reports</h1>
        <p className={styles.lead}>
          Administrative reporting over the platform's own records — users and access, organizations, the programme
          catalogue, duplicate review, the audit log and imports. Reports are generated, scheduled and exported by
          the shared reporting engine.
        </p>
      </header>

      {isLoading && <Loading label="Loading report datasets" />}
      {error && <p className={styles.muted}>Could not load the report catalogue. Please try again.</p>}

      {!isLoading && !error && (
        <Tabs
          activeId={tab}
          onChange={setTab}
          items={[
            {
              id: 'catalogue',
              label: 'Report catalogue',
              content: <CataloguePanel datasets={adminDatasets} onBuild={buildFrom} />,
            },
            {
              id: 'builder',
              label: 'Build & export',
              content: canExport ? (
                <BuilderPanel datasets={adminDatasets} initialDataset={seedDataset} />
              ) : (
                <p className={styles.muted}>
                  Generating and exporting reports needs the reporting export permission.
                </p>
              ),
            },
            {
              id: 'schedules',
              label: 'Scheduled reports',
              content: <SchedulesPanel canManage={canExport} />,
            },
            { id: 'runs', label: 'Recent exports', content: <RunsPanel /> },
          ]}
        />
      )}
    </div>
  )
}
