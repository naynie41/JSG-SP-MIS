import { useState } from 'react'
import { Button } from '@/components/Button/Button'
import { SelectField } from '@/components/Field/SelectField'
import { Spinner } from '@/components/Spinner/Spinner'
import { dashboardApi } from '@/features/dashboard/api'
import type { DashboardExportFormat } from '@/features/dashboard/api'
import { useDashboard } from '@/features/dashboard/hooks'
import { summariseReporting } from '@/features/dashboard/reportingSummary'
import { EMPTY_FILTER } from '@/features/dashboard/types'
import type { DashboardFilterValue, DashboardResponse, TrendPoint } from '@/features/dashboard/types'
import { REGISTRATION_SOURCE_LABELS, titleCase } from '@/features/registry/constants'
import { Bars, Figure, Panel } from './MdaReportWidgets'
import type { CountRow } from './MdaReportWidgets'
import { formatCount, isHeld } from './mdaReportFormat'
import styles from './mdaReports.module.css'

const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

/** Age bands come from server configuration; these only name the known keys. */
const AGE_LABELS: Record<string, string> = {
  children: 'Children',
  youth: 'Youth',
  adults: 'Adults',
  elderly: 'Elderly',
  unknown: 'Not recorded',
}

const STATUS_LABELS: Record<string, string> = {
  active: 'Active',
  flagged: 'Flagged for review',
  suspended: 'Suspended',
}

/** How many LGAs the "where they live" breakdown lists before summarising the rest. */
const LGA_LIMIT = 8

function isEmptyFilter(filter: DashboardFilterValue): boolean {
  return Object.values(filter).every((value) => value === null)
}

function percent(ratio: number | null | undefined): string {
  return ratio === null || ratio === undefined ? '—' : `${Math.round(ratio * 100)}%`
}

function monthName(month: string): string {
  const index = Number(month.slice(5, 7)) - 1
  return MONTHS[index] ?? month
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

/**
 * The Dashboard tab of the MDA's Reports (PRD FR-DSH-01, FR-RPT-02).
 *
 * One reading of the MDA's own population and delivery, arranged the way the MDA asked
 * for it: headline figures beside the quality of the records behind them, who is
 * registered, then where, when and how.
 *
 * Every figure comes from the one scoped dashboard query. The headline tiles run through
 * `summariseReporting()` — the derivation the Overview card uses — so the two pages show
 * the same numbers by construction. Nothing here introduces a measure the server did not
 * compute, and the net-unique beneficiary count leads (CLAUDE.md §11).
 */
export function MdaReportsDashboard({ canExport }: { canExport: boolean }) {
  const [filter, setFilter] = useState<DashboardFilterValue>(EMPTY_FILTER)
  const [format, setFormat] = useState<DashboardExportFormat>('pdf')
  const [exporting, setExporting] = useState(false)
  const [exportFailed, setExportFailed] = useState(false)
  const active = !isEmptyFilter(filter)
  const { data, isLoading, isFetching } = useDashboard(active ? filter : undefined)

  // The shared executive export menu is drawn for a dark header; on this page the
  // control matches the report builders instead. Same endpoint, same scoped file.
  const runExport = async () => {
    setExporting(true)
    setExportFailed(false)
    try {
      await dashboardApi.export(format, active ? filter : undefined)
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
  const total = data.metrics.registry.beneficiaries.total
  const minimum = data.min_cell_size ?? null

  return (
    <div className={styles.dash}>
      <div className={styles.head}>
        <div className={styles.headCopy}>
          <h2 className={styles.title}>{data.scope.label}</h2>
          <p className={styles.lead}>
            Overall picture of the people your MDA has registered and the benefits it has delivered.{' '}
            {formatCount(total, minimum)} beneficiaries in view. Computed {computedAt(data.computed_at)}.
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
              options={[
                { value: '', label: 'All quarters' },
                ...[1, 2, 3, 4].map((q) => ({ value: String(q), label: `Q${q}` })),
              ]}
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
              options={[
                { value: '', label: 'All LGAs' },
                ...(options?.lgas ?? []).map((lga) => ({ value: lga, label: titleCase(lga) })),
              ]}
            />
          </div>
          {canExport && (
            <>
              <div className={styles.control}>
                <SelectField
                  label="Export as"
                  value={format}
                  onChange={(event) => setFormat(event.target.value as DashboardExportFormat)}
                  options={[
                    { value: 'pdf', label: 'PDF' },
                    { value: 'xlsx', label: 'Excel' },
                    { value: 'csv', label: 'CSV' },
                  ]}
                />
              </div>
              <Button variant="secondary" loading={exporting} onClick={() => void runExport()}>
                Export
              </Button>
            </>
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

      <HeadlineBand data={data} />
      <WhoIsRegistered data={data} />
      <Breakdowns data={data} />
    </div>
  )
}

/* ------------------------------------------------------------------ the band */

function HeadlineBand({ data }: { data: DashboardResponse }) {
  const summary = summariseReporting(data)
  const quality = data.metrics.registry_quality

  const meters = quality
    ? [
        {
          key: 'verified',
          name: 'Verified',
          hint: 'Records active, not flagged or suspended',
          ratio: quality.total > 0 ? quality.verified / quality.total : null,
        },
        {
          key: 'nin',
          name: 'NIN recorded',
          hint: 'Records carrying a National Identification Number',
          ratio: quality.nin_completeness,
        },
        {
          key: 'phone',
          name: 'Phone recorded',
          hint: 'Records with a number to reach the person on',
          ratio: quality.phone_completeness,
        },
        {
          key: 'overall',
          name: 'Overall completeness',
          hint: 'Identifier, phone, birth date, gender and LGA together',
          ratio: quality.data_completeness,
        },
      ]
    : []

  // The weakest single detail, not "overall", which is an average of the others.
  const measured = meters.filter((m) => m.key !== 'overall' && m.ratio !== null)
  const weakest = measured.length
    ? measured.reduce((low, m) => ((m.ratio ?? 1) < (low.ratio ?? 1) ? m : low))
    : null
  const flagWeakest = weakest !== null && (weakest.ratio ?? 1) < 1

  return (
    <section className={styles.band} aria-label="Headline figures and record quality">
      <dl className={styles.figures}>
        {summary.tiles.map((tile) => (
          <Figure
            key={tile.key}
            label={tile.label}
            held={tile.suppressed}
            value={tile.suppressed ? `< ${summary.minCellSize}` : (tile.value ?? 0).toLocaleString()}
          />
        ))}
      </dl>

      <div className={styles.panelBody}>
        <div className={styles.panelHead}>
          <h3 className={styles.panelTitle}>Quality of your records</h3>
          <p className={styles.panelSub}>
            The share of your {quality ? quality.total.toLocaleString() : ''} records that carry each detail
          </p>
        </div>

        {!quality || quality.total === 0 ? (
          <p className={styles.empty}>No records in this view yet, so there is nothing to measure.</p>
        ) : (
          <>
            <ul className={styles.meters}>
              {meters.map((meter) => (
                <li key={meter.key} className={styles.meter}>
                  <span className={styles.meterName}>
                    {meter.name}
                    <span className={styles.meterHint}>{meter.hint}</span>
                  </span>
                  <span className={styles.meterTrack} aria-hidden="true">
                    <span
                      className={flagWeakest && meter.key === weakest?.key ? styles.meterFillWeak : styles.meterFill}
                      style={{ width: `${Math.round((meter.ratio ?? 0) * 100)}%` }}
                    />
                  </span>
                  <span className={styles.meterValue}>{percent(meter.ratio)}</span>
                </li>
              ))}
            </ul>
            <p className={styles.insight}>
              {flagWeakest && weakest
                ? `${weakest.name} is the weakest detail at ${percent(weakest.ratio)}.`
                : 'Every record carries each of these details.'}
            </p>
          </>
        )}
      </div>
    </section>
  )
}

/* ------------------------------------------------------------ who is registered */

function WhoIsRegistered({ data }: { data: DashboardResponse }) {
  const m = data.metrics
  const minimum = data.min_cell_size ?? null
  const demographics = m.demographics
  const households = m.household_size

  if (!demographics) return null

  const women = demographics.by_gender.female ?? 0
  const men = demographics.by_gender.male ?? 0
  const other = demographics.by_gender.other ?? 0
  const unrecorded = demographics.by_gender.unspecified ?? 0
  // A share computed from a withheld count would give the count back.
  const shareHidden = isHeld(women, minimum) || isHeld(men, minimum)

  return (
    <section className={styles.section} aria-labelledby="mda-reports-who">
      <h3 id="mda-reports-who" className={styles.sectionTitle}>
        Who is registered
      </h3>

      <div className={styles.pair}>
        <article className={styles.pairItem}>
          <div className={styles.pairHead}>
            <h4 className={styles.pairName}>Women and men</h4>
            <span className={styles.pairMeta}>
              {formatCount(demographics.gender_known, minimum)} with a recorded gender
            </span>
          </div>
          <div className={styles.big}>
            <span className={styles.bigValue}>{shareHidden ? '—' : percent(demographics.female_pct)}</span>
            <span className={styles.bigLabel}>are women</span>
          </div>
          <dl className={styles.stats}>
            <Stat label="Women" value={formatCount(women, minimum)} />
            <Stat label="Men" value={formatCount(men, minimum)} />
            {other > 0 && <Stat label="Other" value={formatCount(other, minimum)} />}
            <Stat label="Not recorded" value={formatCount(unrecorded, minimum)} />
          </dl>
        </article>

        <article className={styles.pairItem}>
          <div className={styles.pairHead}>
            <h4 className={styles.pairName}>Households</h4>
            <span className={styles.pairMeta}>
              {formatCount(households?.total_households ?? 0, minimum)} households
            </span>
          </div>
          <div className={styles.big}>
            <span className={styles.bigValue}>
              {households?.average_size !== null && households?.average_size !== undefined
                ? households.average_size.toFixed(1)
                : '—'}
            </span>
            <span className={styles.bigLabel}>people per household, on average</span>
          </div>
          <dl className={styles.stats}>
            <Stat
              label="In a household"
              value={formatCount(demographics.household_vs_individual.in_household, minimum)}
            />
            <Stat
              label="Registered as individuals"
              value={formatCount(demographics.household_vs_individual.individual, minimum)}
            />
            <Stat label="Households of 7 or more" value={formatCount(households?.bands['7+'] ?? 0, minimum)} />
          </dl>
        </article>
      </div>
    </section>
  )
}

function Stat({ label, value }: { label: string; value: string }) {
  return (
    <div className={styles.stat}>
      <dt className={styles.statLabel}>{label}</dt>
      <dd className={styles.statValue}>{value}</dd>
    </div>
  )
}

/* ------------------------------------------------------------------ breakdowns */

function Breakdowns({ data }: { data: DashboardResponse }) {
  const m = data.metrics
  const minimum = data.min_cell_size ?? null

  const lgas = rowsFrom(m.registry.beneficiaries.by_lga, (key) => (key === 'unspecified' ? 'Not recorded' : titleCase(key)))
  const ages = Object.entries(m.demographics?.age_bands ?? {}).map(([key, count]) => ({
    key,
    label: AGE_LABELS[key] ?? titleCase(key),
    count: Number(count) || 0,
  }))

  return (
    <div className={styles.grid}>
      <Panel title="Registrations by month" sub="People newly registered in each month">
        <MonthColumns points={m.trends?.registrations ?? []} />
      </Panel>

      <Panel title="Where they live" sub="Beneficiaries by local government area, largest first">
        <Bars rows={lgas.slice(0, LGA_LIMIT)} minimum={minimum} emptyText="No beneficiaries recorded in this view yet." />
        {lgas.length > LGA_LIMIT && (
          <p className={styles.note}>
            And {lgas.length - LGA_LIMIT} more LGA{lgas.length - LGA_LIMIT === 1 ? '' : 's'}. Filter by LGA to see one on its
            own.
          </p>
        )}
      </Panel>

      <Panel title="Age groups" sub="From date of birth, in the bands the state reports on">
        <Bars rows={ages} minimum={minimum} emptyText="No dates of birth recorded in this view yet." />
      </Panel>

      <Panel title="How people were registered" sub="The source each record came from">
        <Bars
          rows={rowsFrom(m.registry.beneficiaries.by_source, (key) => REGISTRATION_SOURCE_LABELS[key] ?? titleCase(key))}
          minimum={minimum}
          emptyText="No registrations recorded in this view yet."
        />
      </Panel>

      <Panel title="Benefits delivered" sub="Deliveries recorded, by type of benefit">
        <Bars
          rows={(m.benefits.by_type ?? [])
            .map((group) => ({
              key: group.key ?? 'unspecified',
              label: group.key ? titleCase(group.key) : 'Unspecified',
              count: group.benefit_count,
            }))
            .sort((a, b) => b.count - a.count)}
          minimum={minimum}
          emptyText="No benefits delivered in this view yet."
        />
      </Panel>

      <Panel title="Status of records" sub="Whether each record is active, flagged or suspended">
        <Bars
          rows={rowsFrom(m.registry.beneficiaries.by_status, (key) => STATUS_LABELS[key] ?? titleCase(key))}
          minimum={minimum}
          emptyText="No records in this view yet."
        />
      </Panel>
    </div>
  )
}

/**
 * Registrations as monthly columns. Only the peak and the latest month carry a printed
 * value — a number on every column is a table drawn badly — and each column names its
 * month and count on hover. A visually hidden table carries every value for screen
 * readers.
 */
function MonthColumns({ points }: { points: TrendPoint[] }) {
  const recent = points.slice(-12)

  if (recent.length === 0 || recent.every((point) => point.value === 0)) {
    return <p className={styles.empty}>No registrations recorded in this period.</p>
  }

  const max = Math.max(...recent.map((point) => point.value))
  const peak = recent.findIndex((point) => point.value === max)
  const last = recent.length - 1

  return (
    <>
      <div className={styles.columns} aria-hidden="true">
        {recent.map((point, index) => {
          const height = (point.value / max) * 100

          return (
            <div
              key={point.month}
              className={styles.column}
              title={`${monthName(point.month)} ${point.month.slice(0, 4)}: ${point.value.toLocaleString()}`}
            >
              {(index === peak || index === last) && point.value > 0 && (
                <span className={styles.columnValue} style={{ bottom: `calc(${height}% + 4px)` }}>
                  {point.value.toLocaleString()}
                </span>
              )}
              <span className={styles.columnFill} style={{ height: `${height}%` }} />
            </div>
          )
        })}
      </div>
      <div className={styles.columnAxis} aria-hidden="true">
        {recent.map((point) => (
          <span key={point.month}>{monthName(point.month)}</span>
        ))}
      </div>
      <table className={styles.srOnly}>
        <caption>Registrations by month</caption>
        <thead>
          <tr>
            <th scope="col">Month</th>
            <th scope="col">Registrations</th>
          </tr>
        </thead>
        <tbody>
          {recent.map((point) => (
            <tr key={point.month}>
              <td>
                {monthName(point.month)} {point.month.slice(0, 4)}
              </td>
              <td>{point.value.toLocaleString()}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  )
}
