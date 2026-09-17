import { useState } from 'react'
import { FileDown } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { SelectField } from '@/components/Field/SelectField'
import { Spinner } from '@/components/Spinner/Spinner'
import { dashboardApi, filterParams } from '@/features/dashboard/api'
import { BandChoroplethMap } from '@/features/dashboard/BandChoroplethMap'
import { useDashboard } from '@/features/dashboard/hooks'
import { summariseReporting } from '@/features/dashboard/reportingSummary'
import { EMPTY_FILTER } from '@/features/dashboard/types'
import type { DashboardFilterValue, DashboardResponse, TrafficLight, TrendPoint } from '@/features/dashboard/types'
import { BAND_COLORS, BAND_LABELS } from '@/features/gis/choropleth'
import { useGisCoverage } from '@/features/gis/hooks'
import type { CoverageBand } from '@/features/gis/types'
import { REGISTRATION_SOURCE_LABELS, titleCase } from '@/features/registry/constants'
import { formatNaira } from '@/lib/utils/money'
import { Bars } from './MdaReportWidgets'
import type { CountRow } from './MdaReportWidgets'
import { formatCount, isHeld } from './mdaReportFormat'
import { AreaTrendChart } from './reportCharts/AreaTrendChart'
import { ChartCard } from './reportCharts/ChartCard'
import type { ChartTable } from './reportCharts/ChartCard'
import { ColumnChart } from './reportCharts/ColumnChart'
import { DonutChart } from './reportCharts/DonutChart'
import { RingMeter } from './reportCharts/RingMeter'
import { Sparkline } from './reportCharts/Sparkline'
import { SplitBar } from './reportCharts/SplitBar'
import { compactNaira, compactNumber, monthLong } from './reportCharts/geometry'
import chartStyles from './reportCharts/charts.module.css'
import styles from './mdaReports.module.css'

/* ----------------------------------------------------------------- vocabulary */

/** Age bands come from server configuration; these only name the known keys. */
const AGE_LABELS: Record<string, string> = {
  children: 'Children',
  youth: 'Youth',
  adults: 'Adults',
  elderly: 'Elderly',
  unknown: 'Not recorded',
}

/** Record status is status, so it wears the status tokens (DESIGN.md §5.8) — with labels. */
const STATUS_SEGMENTS = [
  { key: 'active', label: 'Active', color: 'var(--success)' },
  { key: 'flagged', label: 'Flagged for review', color: 'var(--danger)' },
  { key: 'suspended', label: 'Suspended', color: 'var(--warning)' },
]

/** Categorical slots in fixed order; a fifth category folds into "Other". */
const CATEGORY_COLORS = ['var(--chart-1)', 'var(--chart-2)', 'var(--chart-3)', 'var(--chart-4)']
const OTHER_COLOR = 'var(--chart-other)'

const HOUSEHOLD_BANDS: Array<[string, string]> = [
  ['1', '1 person'],
  ['2-3', '2 to 3'],
  ['4-6', '4 to 6'],
  ['7+', '7 or more'],
]

const LIGHTS: Record<TrafficLight, { label: string; color: string }> = {
  green: { label: 'On target', color: 'var(--success)' },
  yellow: { label: 'Behind', color: 'var(--warning)' },
  red: { label: 'Off target', color: 'var(--danger)' },
  unrated: { label: 'No target set', color: 'var(--chart-other)' },
}

const LGA_LIMIT = 10

/* -------------------------------------------------------------------- helpers */

function isEmptyFilter(filter: DashboardFilterValue): boolean {
  return Object.values(filter).every((value) => value === null)
}

function percent(ratio: number | null | undefined): string {
  return ratio === null || ratio === undefined ? '—' : `${Math.round(ratio * 100)}%`
}

function rowsFrom(map: Record<string, number> | undefined, label: (key: string) => string): CountRow[] {
  return Object.entries(map ?? {})
    .map(([key, count]) => ({ key, label: label(key), count: Number(count) || 0 }))
    .sort((a, b) => b.count - a.count)
}

function computedAt(iso: string): string {
  const date = new Date(iso)
  return Number.isNaN(date.getTime())
    ? 'at an unknown time'
    : date.toLocaleString(undefined, { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}

function share(value: number, total: number): string {
  return total > 0 ? `${Math.round((value / total) * 100)}%` : '—'
}

/* ---------------------------------------------------------------------- page */

/**
 * The Dashboard tab of the MDA's Reports (PRD FR-DSH-01, FR-RPT-02).
 *
 * Headline tiles, then activity over time beside the quality of the records behind it,
 * then who is registered, where they are, and how delivery and programmes are going.
 *
 * Every figure comes from the scoped dashboard query (plus the scoped LGA coverage the
 * map needs), and the tiles run through `summariseReporting()` — the derivation the
 * Overview card uses — so both pages show the same numbers by construction. Each chart
 * prints its values, answers hover and keyboard focus, and has a table view.
 */
export function MdaReportsDashboard({ canExport }: { canExport: boolean }) {
  const [filter, setFilter] = useState<DashboardFilterValue>(EMPTY_FILTER)
  const [exporting, setExporting] = useState(false)
  const [exportFailed, setExportFailed] = useState(false)
  const active = !isEmptyFilter(filter)
  const { data, isLoading, isFetching } = useDashboard(active ? filter : undefined)

  const runExport = async () => {
    setExporting(true)
    setExportFailed(false)
    try {
      // PDF only: the file is this dashboard laid out on paper, with the same figures.
      await dashboardApi.export('pdf', active ? filter : undefined, 'mda-dashboard.pdf')
    } catch {
      setExportFailed(true)
    } finally {
      setExporting(false)
    }
  }

  if (isLoading) {
    return (
      <div className={styles.loading}>
        <Spinner size={22} label="Loading reporting figures" />
      </div>
    )
  }

  if (!data) {
    return <p className={styles.empty}>The dashboard could not be loaded. Please try again.</p>
  }

  const set = (key: keyof DashboardFilterValue, raw: string) => {
    const parsed = raw === '' ? null : key === 'year' || key === 'quarter' ? Number(raw) : raw
    const next = { ...filter, [key]: parsed }
    if (key === 'quarter' && parsed !== null) next.month = null
    setFilter(next as DashboardFilterValue)
  }

  const options = data.filter_options
  const minimum = data.min_cell_size ?? null

  return (
    <div className={styles.dash}>
      <div className={styles.head}>
        <div className={styles.headCopy}>
          <h2 className={styles.title}>{data.scope.label}</h2>
          <p className={styles.lead}>
            Overall picture of the people your MDA has registered and the benefits it has delivered.{' '}
            {formatCount(data.metrics.registry.beneficiaries.total, minimum)} beneficiaries in view, each person
            counted once however many programmes they are in. Figures as at{' '}
            {computedAt(data.computed_at)}.
          </p>
        </div>

        <div className={styles.controls} role="group" aria-label="Dashboard filters">
          <div className={styles.control}>
            <SelectField
              label="Year"
              value={filter.year?.toString() ?? ''}
              onChange={(event) => set('year', event.target.value)}
              options={[
                { value: '', label: 'All years' },
                ...(options?.years ?? []).map((year) => ({ value: String(year), label: String(year) })),
              ]}
            />
          </div>
          <div className={styles.control}>
            <SelectField
              label="Quarter"
              value={filter.quarter?.toString() ?? ''}
              onChange={(event) => set('quarter', event.target.value)}
              options={[{ value: '', label: 'All quarters' }, ...[1, 2, 3, 4].map((q) => ({ value: String(q), label: `Q${q}` }))]}
            />
          </div>
          {(options?.programmes.length ?? 0) > 0 && (
            <div className={styles.controlWide}>
              <SelectField
                label="Programme"
                value={filter.programme_id ?? ''}
                onChange={(event) => set('programme_id', event.target.value)}
                options={[
                  { value: '', label: 'All programmes' },
                  ...(options?.programmes ?? []).map((p) => ({ value: p.id, label: titleCase(p.name) })),
                ]}
              />
            </div>
          )}
          <div className={styles.control}>
            <SelectField
              label="LGA"
              value={filter.lga ?? ''}
              onChange={(event) => set('lga', event.target.value)}
              options={[{ value: '', label: 'All LGAs' }, ...(options?.lgas ?? []).map((lga) => ({ value: lga, label: titleCase(lga) }))]}
            />
          </div>
          {canExport && (
            <Button variant="secondary" leftIcon={FileDown} loading={exporting} onClick={() => void runExport()}>
              Export PDF
            </Button>
          )}
          <span className={styles.updating} aria-live="polite">
            {isFetching ? 'Updating…' : ''}
          </span>
        </div>
      </div>

      {exportFailed && (
        <p className={styles.note} role="alert">
          The export could not be prepared. Please try again.
        </p>
      )}

      {/* A filter change keeps the last picture on screen, dimmed, until the new one lands. */}
      <div className={isFetching ? `${styles.board} ${styles.boardUpdating}` : styles.board}>
        <HeadlineTiles data={data} />

        <div className={styles.split21}>
          <TrendCard data={data} />
          <QualityCard data={data} />
        </div>

        <section className={styles.section} aria-labelledby="mda-reports-who">
          <h3 id="mda-reports-who" className={styles.sectionTitle}>
            Who is registered
          </h3>
          <div className={styles.grid3}>
            <GenderCard data={data} />
            <AgeCard data={data} />
            <HouseholdCard data={data} />
          </div>
        </section>

        <section className={styles.section} aria-labelledby="mda-reports-where">
          <h3 id="mda-reports-where" className={styles.sectionTitle}>
            Where they are
          </h3>
          <div className={styles.split21}>
            <CoverageCard filter={active ? filter : undefined} />
            <LgaCard data={data} />
          </div>
        </section>

        <section className={styles.section} aria-labelledby="mda-reports-delivery">
          <h3 id="mda-reports-delivery" className={styles.sectionTitle}>
            Delivery and records
          </h3>
          <div className={styles.grid}>
            <BenefitsCard data={data} />
            <RecordsCard data={data} />
          </div>
          <ProgrammesCard data={data} />
        </section>
      </div>
    </div>
  )
}

/* -------------------------------------------------------------- headline tiles */

function HeadlineTiles({ data }: { data: DashboardResponse }) {
  const summary = summariseReporting(data)
  const m = data.metrics

  const extras: Record<string, { note?: string; spark?: TrendPoint[] }> = {
    beneficiaries: {
      spark: m.trends?.beneficiaries_cumulative,
      note: m.population
        ? `${formatCount(m.population.new_registrations_period)} new in the last ${m.population.period_days} days`
        : undefined,
    },
    households: {
      note:
        m.household_size?.average_size !== null && m.household_size?.average_size !== undefined
          ? `${m.household_size.average_size.toFixed(1)} people per household on average`
          : undefined,
    },
    programmes: { note: `of ${m.programmes.total.toLocaleString()} in view` },
    activities: {
      note: m.programmes.activities_total !== undefined ? `of ${m.programmes.activities_total.toLocaleString()} in view` : undefined,
    },
    deliveries: {
      spark: m.trends?.disbursement,
      note: `${compactNaira(m.benefits.disbursed.total_value)} delivered in total`,
    },
    duplicates: { note: 'people who may already be registered' },
  }

  return (
    <section aria-label="Headline figures">
      <dl className={styles.tiles}>
        {summary.tiles.map((tile, index) => {
          const extra = extras[tile.key] ?? {}
          const lead = index === 0
          return (
            <div key={tile.key} className={lead ? `${styles.tile} ${styles.tileLead}` : styles.tile}>
              <dt className={styles.tileLabel}>{tile.label}</dt>
              <dd className={tile.suppressed ? styles.tileHeld : styles.tileValue}>
                {tile.suppressed ? `< ${summary.minCellSize}` : (tile.value ?? 0).toLocaleString()}
              </dd>
              {extra.note && <dd className={styles.tileNote}>{extra.note}</dd>}
              {extra.spark && (
                <dd className={styles.tileSpark}>
                  <Sparkline points={extra.spark} onDark={lead} />
                </dd>
              )}
            </div>
          )
        })}
      </dl>
    </section>
  )
}

/* ------------------------------------------------------------ activity over time */

type SeriesKey = 'registrations' | 'beneficiaries' | 'value'

function TrendCard({ data }: { data: DashboardResponse }) {
  const [series, setSeries] = useState<SeriesKey>('registrations')
  const trends = data.metrics.trends

  const choices: Record<SeriesKey, { label: string; sub: string; points: TrendPoint[]; format: (n: number) => string; axis: (n: number) => string }> = {
    registrations: {
      label: 'New registrations',
      sub: 'People newly registered in each month',
      points: trends?.registrations ?? [],
      format: (n) => n.toLocaleString(),
      axis: compactNumber,
    },
    beneficiaries: {
      label: 'Total beneficiaries',
      sub: 'Everyone registered so far, month by month',
      points: trends?.beneficiaries_cumulative ?? [],
      format: (n) => n.toLocaleString(),
      axis: compactNumber,
    },
    value: {
      label: 'Value delivered',
      sub: 'Recorded value of benefits delivered each month',
      points: trends?.disbursement ?? [],
      format: (kobo) => formatNaira(kobo),
      axis: compactNaira,
    },
  }
  const current = choices[series]

  const table: ChartTable = {
    caption: `${current.label} by month`,
    columns: ['Month', current.label],
    rows: current.points.slice(-12).map((point) => [monthLong(point.month), current.format(point.value)]),
  }

  return (
    <ChartCard
      title="Activity over time"
      sub={current.sub}
      table={table}
      actions={
        <div className={chartStyles.segmented} role="group" aria-label="Choose what the chart shows">
          {(Object.keys(choices) as SeriesKey[]).map((key) => (
            <button
              key={key}
              type="button"
              className={chartStyles.segment}
              aria-pressed={series === key}
              onClick={() => setSeries(key)}
            >
              {choices[key].label}
            </button>
          ))}
        </div>
      }
    >
      <AreaTrendChart key={series} label={current.label} points={current.points} format={current.format} axisFormat={current.axis} />
    </ChartCard>
  )
}

/* -------------------------------------------------------------- record quality */

function QualityCard({ data }: { data: DashboardResponse }) {
  const quality = data.metrics.registry_quality

  const meters = quality
    ? [
        { key: 'verified', name: 'Verified', hint: 'Active, not flagged or suspended', ratio: quality.total > 0 ? quality.verified / quality.total : null },
        { key: 'nin', name: 'NIN recorded', hint: 'Carry a National Identification Number', ratio: quality.nin_completeness },
        { key: 'phone', name: 'Phone recorded', hint: 'Have a number to reach the person on', ratio: quality.phone_completeness },
        { key: 'overall', name: 'All details recorded', hint: 'Identifier, phone, birth date, gender and LGA', ratio: quality.data_completeness },
      ]
    : []

  // The weakest single detail, not "overall", which is an average of the others.
  const measured = meters.filter((meter) => meter.key !== 'overall' && meter.ratio !== null)
  const weakest = measured.length ? measured.reduce((low, meter) => ((meter.ratio ?? 1) < (low.ratio ?? 1) ? meter : low)) : null
  const flagWeakest = weakest !== null && (weakest.ratio ?? 1) < 1
  const hasRecords = quality !== undefined && quality.total > 0

  return (
    <ChartCard
      title="Quality of your records"
      sub={hasRecords ? `Share of your ${quality.total.toLocaleString()} records carrying each detail` : undefined}
      table={
        hasRecords
          ? { caption: 'Share of records carrying each detail', columns: ['Detail', 'Share'], rows: meters.map((meter) => [meter.name, percent(meter.ratio)]) }
          : undefined
      }
    >
      {!hasRecords ? (
        <p className={styles.empty}>No records in this view yet, so there is nothing to measure.</p>
      ) : (
        <>
          <div className={chartStyles.rings}>
            {meters.map((meter) => (
              <RingMeter
                key={meter.key}
                label={meter.name}
                hint={meter.hint}
                ratio={meter.ratio}
                weakest={flagWeakest && meter.key === weakest?.key}
              />
            ))}
          </div>
          <p className={styles.insight}>
            {flagWeakest && weakest
              ? `${weakest.name} is the weakest detail at ${percent(weakest.ratio)}.`
              : 'Every record carries each of these details.'}
          </p>
        </>
      )}
    </ChartCard>
  )
}

/* ------------------------------------------------------------ who is registered */

function GenderCard({ data }: { data: DashboardResponse }) {
  const minimum = data.min_cell_size ?? null
  const demographics = data.metrics.demographics
  if (!demographics) return null

  const count = (key: string) => demographics.by_gender[key] ?? 0
  const slices = [
    { key: 'female', label: 'Women', value: count('female'), color: 'var(--chart-1)' },
    { key: 'male', label: 'Men', value: count('male'), color: 'var(--chart-2)' },
    ...(count('other') > 0 ? [{ key: 'other', label: 'Other', value: count('other'), color: 'var(--chart-3)' }] : []),
    { key: 'unspecified', label: 'Not recorded', value: count('unspecified'), color: OTHER_COLOR },
  ]
  const total = slices.reduce((sum, slice) => sum + slice.value, 0)
  // A share computed from a withheld count would give the count back.
  const shareHidden = isHeld(count('female'), minimum) || isHeld(count('male'), minimum)

  return (
    <ChartCard
      title="Women and men"
      sub={`${formatCount(demographics.gender_known, minimum)} with a recorded gender`}
      headingLevel="h4"
      table={{
        caption: 'People by gender',
        columns: ['Gender', 'People', 'Share'],
        rows: slices.map((slice) => [slice.label, formatCount(slice.value, minimum), isHeld(slice.value, minimum) ? '—' : share(slice.value, total)]),
      }}
    >
      <DonutChart
        label="People by gender"
        slices={slices}
        centerValue={shareHidden ? '—' : percent(demographics.female_pct)}
        centerLabel="are women"
        unit="people"
        minimum={minimum}
        emptyText="No genders recorded in this view yet."
      />
    </ChartCard>
  )
}

function AgeCard({ data }: { data: DashboardResponse }) {
  const minimum = data.min_cell_size ?? null
  const rows = Object.entries(data.metrics.demographics?.age_bands ?? {}).map(([key, value]) => ({
    key,
    label: AGE_LABELS[key] ?? titleCase(key),
    count: Number(value) || 0,
  }))

  return (
    <ChartCard
      title="Age groups"
      sub="From date of birth, in the bands the state reports on"
      headingLevel="h4"
      table={{ caption: 'People by age group', columns: ['Age group', 'People'], rows: rows.map((row) => [row.label, formatCount(row.count, minimum)]) }}
    >
      <ColumnChart label="People by age group" rows={rows} unit="people" minimum={minimum} emptyText="No dates of birth recorded in this view yet." />
    </ChartCard>
  )
}

function HouseholdCard({ data }: { data: DashboardResponse }) {
  const minimum = data.min_cell_size ?? null
  const sizes = data.metrics.household_size
  const split = data.metrics.demographics?.household_vs_individual
  const rows = HOUSEHOLD_BANDS.map(([key, label]) => ({ key, label, count: sizes?.bands[key] ?? 0 }))
  const segments = [
    { key: 'household', label: 'In a household', value: split?.in_household ?? 0, color: 'var(--chart-1)' },
    { key: 'individual', label: 'Registered as individuals', value: split?.individual ?? 0, color: 'var(--chart-2)' },
  ]

  return (
    <ChartCard
      title="Household size"
      sub={
        sizes
          ? `${formatCount(sizes.total_households, minimum)} households${sizes.average_size !== null ? ` · ${sizes.average_size.toFixed(1)} people on average` : ''}`
          : undefined
      }
      headingLevel="h4"
      table={{
        caption: 'Households by size, and people in or outside a household',
        columns: ['Group', 'Count'],
        rows: [
          ...rows.map((row) => [`Households of ${row.label}`, formatCount(row.count, minimum)]),
          ...segments.map((segment) => [segment.label, formatCount(segment.value, minimum)]),
        ],
      }}
    >
      <div className={styles.stack}>
        <ColumnChart label="Households by number of members" rows={rows} unit="households" minimum={minimum} emptyText="No households in this view yet." height={170} />
        <SplitBar label="People in a household or registered alone" segments={segments} unit="people" minimum={minimum} emptyText="No people in this view yet." />
      </div>
    </ChartCard>
  )
}

/* --------------------------------------------------------------- where they are */

function CoverageCard({ filter }: { filter?: DashboardFilterValue }) {
  const [selected, setSelected] = useState<string | null>(null)
  const coverage = useGisCoverage('lga', filterParams(filter))
  const rows = [...(coverage.data?.rows ?? [])].sort((a, b) => b.beneficiary_count - a.beneficiary_count)
  const chosen = rows.find((row) => row.key === selected) ?? null
  const bands = coverage.data?.bands

  return (
    <ChartCard
      title="Coverage across your LGAs"
      sub="Each LGA shaded by how many of your beneficiaries live there"
      headingLevel="h4"
      table={
        rows.length > 0
          ? {
              caption: 'Coverage by LGA',
              columns: ['LGA', 'Beneficiaries', 'Households', 'Value delivered', 'Coverage'],
              rows: rows.map((row) => [row.name, row.beneficiary_count.toLocaleString(), row.households.toLocaleString(), formatNaira(row.benefit_value), BAND_LABELS[row.band]]),
            }
          : undefined
      }
    >
      {coverage.isLoading ? (
        <div className={styles.loading}>
          <Spinner size={20} label="Loading the coverage map" />
        </div>
      ) : coverage.data?.mode === 'choropleth' && coverage.data.feature_collection ? (
        <>
          <BandChoroplethMap data={coverage.data.feature_collection} selectedCode={selected} onSelect={setSelected} overlays={[]} />
          {bands && <BandKey greenMin={bands.green_min} yellowMin={bands.yellow_min} />}
          {chosen && (
            <p className={styles.insight}>
              <strong>{chosen.name}</strong>: {chosen.beneficiary_count.toLocaleString()} beneficiaries,{' '}
              {chosen.households.toLocaleString()} households, {formatNaira(chosen.benefit_value)} delivered.
            </p>
          )}
        </>
      ) : (
        <p className={styles.empty}>
          The LGA boundary map is not available here yet, so coverage is listed by LGA beside this card and in the table
          view.
        </p>
      )}
    </ChartCard>
  )
}

/** The map key: which shade means how many people, from the configured thresholds. */
function BandKey({ greenMin, yellowMin }: { greenMin: number; yellowMin: number }) {
  const ranges: Record<CoverageBand, string> = {
    green: `${greenMin.toLocaleString()} or more`,
    yellow: `${yellowMin.toLocaleString()} to ${(greenMin - 1).toLocaleString()}`,
    red: `1 to ${(yellowMin - 1).toLocaleString()}`,
    grey: 'None',
  }

  return (
    <ul className={styles.bandKey} aria-label="Map key">
      {(['green', 'yellow', 'red', 'grey'] as CoverageBand[]).map((band) => (
        <li key={band}>
          <span className={styles.bandSwatch} style={{ background: BAND_COLORS[band] }} aria-hidden="true" />
          {BAND_LABELS[band]}
          <span className={styles.bandRange}>{ranges[band]}</span>
        </li>
      ))}
    </ul>
  )
}

function LgaCard({ data }: { data: DashboardResponse }) {
  const minimum = data.min_cell_size ?? null
  const lgas = rowsFrom(data.metrics.registry.beneficiaries.by_lga, (key) => (key === 'unspecified' ? 'Not recorded' : titleCase(key)))

  return (
    <ChartCard
      title="Largest LGAs"
      sub="Beneficiaries by local government area"
      headingLevel="h4"
      table={{ caption: 'Beneficiaries by LGA', columns: ['LGA', 'Beneficiaries'], rows: lgas.map((row) => [row.label, formatCount(row.count, minimum)]) }}
    >
      <Bars rows={lgas.slice(0, LGA_LIMIT)} minimum={minimum} emptyText="No beneficiaries recorded in this view yet." />
      {lgas.length > LGA_LIMIT && (
        <p className={styles.note}>
          And {lgas.length - LGA_LIMIT} more. The table view lists every LGA.
        </p>
      )}
    </ChartCard>
  )
}

/* -------------------------------------------------------- delivery and records */

function BenefitsCard({ data }: { data: DashboardResponse }) {
  const minimum = data.min_cell_size ?? null
  const groups = [...(data.metrics.benefits.by_type ?? [])].sort((a, b) => b.benefit_count - a.benefit_count)
  const rows = groups.map((group) => ({ key: group.key ?? 'unspecified', label: group.key ? titleCase(group.key) : 'Unspecified', count: group.benefit_count }))

  return (
    <ChartCard
      title="Benefits delivered"
      sub="Deliveries recorded, by type of benefit"
      headingLevel="h4"
      table={{
        caption: 'Benefits delivered by type',
        columns: ['Type', 'Deliveries', 'Value delivered'],
        rows: groups.map((group) => [group.key ? titleCase(group.key) : 'Unspecified', formatCount(group.benefit_count, minimum), formatNaira(group.total_value)]),
      }}
    >
      <Bars rows={rows} minimum={minimum} emptyText="No benefits delivered in this view yet." />
    </ChartCard>
  )
}

function RecordsCard({ data }: { data: DashboardResponse }) {
  const minimum = data.min_cell_size ?? null
  const beneficiaries = data.metrics.registry.beneficiaries

  const sources = rowsFrom(beneficiaries.by_source, (key) => REGISTRATION_SOURCE_LABELS[key] ?? titleCase(key))
  const sourceSegments = [
    ...sources.slice(0, CATEGORY_COLORS.length).map((row, index) => ({ key: row.key, label: row.label, value: row.count, color: CATEGORY_COLORS[index] })),
    ...(sources.length > CATEGORY_COLORS.length
      ? [{ key: 'other', label: 'Other sources', value: sources.slice(CATEGORY_COLORS.length).reduce((sum, row) => sum + row.count, 0), color: OTHER_COLOR }]
      : []),
  ]
  const statusSegments = STATUS_SEGMENTS.map((segment) => ({ ...segment, value: beneficiaries.by_status[segment.key] ?? 0 }))

  return (
    <ChartCard
      title="Records"
      sub="How they came in, and where they stand"
      headingLevel="h4"
      table={{
        caption: 'Records by registration source and by status',
        columns: ['Group', 'Records'],
        rows: [
          ...sourceSegments.map((segment) => [`Source: ${segment.label}`, formatCount(segment.value, minimum)]),
          ...statusSegments.map((segment) => [`Status: ${segment.label}`, formatCount(segment.value, minimum)]),
        ],
      }}
    >
      <div className={styles.stack}>
        <div>
          <p className={styles.subhead}>How they were registered</p>
          <SplitBar label="Records by registration source" segments={sourceSegments} unit="records" minimum={minimum} emptyText="No registrations recorded in this view yet." />
        </div>
        <div>
          <p className={styles.subhead}>Status of records</p>
          <SplitBar label="Records by status" segments={statusSegments} unit="records" minimum={minimum} emptyText="No records in this view yet." />
        </div>
      </div>
    </ChartCard>
  )
}

function ProgrammesCard({ data }: { data: DashboardResponse }) {
  const programmes = [...(data.metrics.programme_performance ?? [])].sort((a, b) => b.reached - a.reached).slice(0, 6)

  return (
    <ChartCard
      title="How your programmes are doing"
      sub="People reached against target, and value delivered against budget"
      headingLevel="h4"
      table={
        programmes.length > 0
          ? {
              caption: 'Programme performance',
              columns: ['Programme', 'Reached', 'Target', 'Delivered', 'Budget', 'Progress'],
              rows: programmes.map((programme) => [
                programme.name ? titleCase(programme.name) : 'Unnamed programme',
                programme.reached.toLocaleString(),
                programme.target > 0 ? programme.target.toLocaleString() : '—',
                formatNaira(programme.budget.spent),
                programme.budget.allocated > 0 ? formatNaira(programme.budget.allocated) : '—',
                LIGHTS[programme.traffic_light].label,
              ]),
            }
          : undefined
      }
    >
      {programmes.length === 0 ? (
        <p className={styles.empty}>No programme activity in this view yet.</p>
      ) : (
        <ul className={styles.programmes}>
          {programmes.map((programme) => {
            const light = LIGHTS[programme.traffic_light]
            return (
              <li key={programme.programme_id} className={styles.programme}>
                <div className={styles.programmeHead}>
                  <span className={styles.programmeName}>{programme.name ? titleCase(programme.name) : 'Unnamed programme'}</span>
                  <span className={styles.light}>
                    <span className={styles.lightDot} style={{ background: light.color }} aria-hidden="true" />
                    {light.label}
                  </span>
                </div>
                <ProgressLine
                  label="Reached"
                  ratio={programme.target > 0 ? programme.completion_rate : null}
                  text={
                    programme.target > 0
                      ? `${programme.reached.toLocaleString()} of ${programme.target.toLocaleString()}`
                      : `${programme.reached.toLocaleString()} reached, no target set`
                  }
                />
                <ProgressLine
                  label="Delivered"
                  ratio={programme.budget.allocated > 0 ? programme.budget.utilization_rate : null}
                  text={
                    programme.budget.allocated > 0
                      ? `${compactNaira(programme.budget.spent)} of ${compactNaira(programme.budget.allocated)}`
                      : `${compactNaira(programme.budget.spent)}, no budget recorded`
                  }
                />
              </li>
            )
          })}
        </ul>
      )}
    </ChartCard>
  )
}

/** One progress measure: the track only fills when there is something to measure against. */
function ProgressLine({ label, ratio, text }: { label: string; ratio: number | null; text: string }) {
  return (
    <div className={styles.programmeMeter}>
      <span className={styles.programmeMeterLabel}>{label}</span>
      <span className={styles.programmeMeterTrack} aria-hidden="true">
        {ratio !== null && <span className={styles.programmeMeterFill} style={{ width: `${Math.min(100, Math.round(ratio * 100))}%` }} />}
      </span>
      <span className={styles.programmeMeterText}>
        {text}
        {ratio !== null ? ` · ${percent(ratio)}` : ''}
      </span>
    </div>
  )
}
