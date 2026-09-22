import { useState } from 'react'
import { Spinner } from '@/components/Spinner/Spinner'
import { filterParams } from '@/features/dashboard/api'
import { BandChoroplethMap } from '@/features/dashboard/BandChoroplethMap'
import { summariseReporting } from '@/features/dashboard/reportingSummary'
import type { DashboardFilterValue, DashboardResponse, TrafficLight, TrendPoint } from '@/features/dashboard/types'
import { BAND_COLORS, BAND_LABELS } from '@/features/gis/choropleth'
import { useGisCoverage } from '@/features/gis/hooks'
import type { CoverageBand } from '@/features/gis/types'
import { REGISTRATION_SOURCE_LABELS, titleCase } from '@/features/registry/constants'
import { formatNaira } from '@/lib/utils/money'
import { Bars } from './BoardWidgets'
import { formatCount, isHeld } from './counts'
import { rowsFrom } from './counts'
import { AreaTrendChart } from './charts/AreaTrendChart'
import { ChartCard } from './charts/ChartCard'
import type { ChartTable } from './charts/ChartCard'
import { ColumnChart } from './charts/ColumnChart'
import { PyramidChart } from './charts/PyramidChart'
import { RingMeter } from './charts/RingMeter'
import { Sparkline } from './charts/Sparkline'
import { SplitBar } from './charts/SplitBar'
import { compactNaira, compactNumber, monthLong } from './charts/geometry'
import chartStyles from './charts/charts.module.css'
import styles from './reportBoard.module.css'

/*
 * The cards a reporting board is made of (PRD FR-DSH-01).
 *
 * Every card is a pure function of the SCOPED dashboard payload, which is why the
 * same components serve an MDA console and the state-wide administration console:
 * the server has already decided what the caller may count, so a card never asks
 * whose numbers these are. What differs between the two boards is the filter bar
 * above them, the order they are laid out in, and the cross-agency card only a
 * state-wide scope receives — not the cards themselves.
 *
 * Each chart prints its values, answers hover and keyboard focus, and offers a
 * table view (DESIGN.md §5.12).
 */

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

function percent(ratio: number | null | undefined): string {
  return ratio === null || ratio === undefined ? '—' : `${Math.round(ratio * 100)}%`
}

function share(value: number, total: number): string {
  return total > 0 ? `${Math.round((value / total) * 100)}%` : '—'
}

/* -------------------------------------------------------------- headline tiles */

export function HeadlineTiles({ data }: { data: DashboardResponse }) {
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

export function TrendCard({ data }: { data: DashboardResponse }) {
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

export function QualityCard({ data }: { data: DashboardResponse }) {
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

/**
 * Gender against age, as a population pyramid.
 *
 * This was a donut of the gender split. A donut of two categories can say exactly one
 * thing — the share of women — and says it in the shape least suited to reading a
 * proportion. The pyramid carries that same number in its wings AND the age structure
 * underneath it, which is what anyone planning a programme actually came for.
 *
 * The honesty problem it inherits: only people with BOTH a gender and a date of birth
 * can be placed on the chart at all. Where date-of-birth coverage is poor the pyramid
 * describes a minority of the register, so the card says how many it left out rather
 * than letting the shape stand unqualified. The table view still carries every gender,
 * including Other and Not recorded, which a two-winged chart has no position for.
 */
export function GenderCard({ data }: { data: DashboardResponse }) {
  const minimum = data.min_cell_size ?? null
  const demographics = data.metrics.demographics
  if (!demographics) return null

  const count = (key: string) => demographics.by_gender[key] ?? 0
  const bands = demographics.gender_by_age ?? []

  const charted = bands.reduce((sum, b) => sum + b.female + b.male, 0)
  const total = demographics.total ?? 0
  const missing = Math.max(0, total - charted)

  const genders = [
    { key: 'female', label: 'Women', value: count('female') },
    { key: 'male', label: 'Men', value: count('male') },
    ...(count('other') > 0 ? [{ key: 'other', label: 'Other', value: count('other') }] : []),
    { key: 'unspecified', label: 'Not recorded', value: count('unspecified') },
  ]
  const genderTotal = genders.reduce((sum, g) => sum + g.value, 0)

  return (
    <ChartCard
      title="Women and men by age"
      sub={
        missing > 0
          ? `${formatCount(charted, minimum)} of ${formatCount(total, minimum)} — the rest have no date of birth recorded`
          : `${formatCount(charted, minimum)} with a gender and date of birth recorded`
      }
      headingLevel="h4"
      table={{
        caption: 'People by gender and age',
        columns: ['Age', 'Women', 'Men'],
        rows: [
          ...bands.map((b) => [b.band, formatCount(b.female, minimum), formatCount(b.male, minimum)]),
          // Every gender, including the two the pyramid cannot place — the table is
          // where the full picture lives.
          ...genders.map((g) => [
            g.label,
            formatCount(g.value, minimum),
            isHeld(g.value, minimum) ? '—' : share(g.value, genderTotal),
          ]),
        ],
      }}
    >
      <PyramidChart
        label="People by gender and age"
        bands={bands}
        femaleColor="var(--chart-1)"
        maleColor="var(--chart-2)"
        minimum={minimum}
        emptyText="No dates of birth recorded yet, so people cannot be placed on an age band."
      />
    </ChartCard>
  )
}

export function AgeCard({ data }: { data: DashboardResponse }) {
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

export function HouseholdCard({ data }: { data: DashboardResponse }) {
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

export function CoverageCard({ filter }: { filter?: DashboardFilterValue }) {
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

export function LgaCard({ data }: { data: DashboardResponse }) {
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

export function BenefitsCard({ data }: { data: DashboardResponse }) {
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

export function RecordsCard({ data }: { data: DashboardResponse }) {
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

export function ProgrammesCard({ data }: { data: DashboardResponse }) {
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
export function ProgressLine({ label, ratio, text }: { label: string; ratio: number | null; text: string }) {
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
