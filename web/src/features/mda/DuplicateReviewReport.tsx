import { useMemo, useState } from 'react'
import { Link } from 'react-router-dom'
import { ArrowRight } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { SelectField } from '@/components/Field/SelectField'
import { TextField } from '@/components/Field/TextField'
import { Icon } from '@/components/Icon/Icon'
import { Spinner } from '@/components/Spinner/Spinner'
import { REGISTRATION_SOURCE_LABELS, titleCase } from '@/features/registry/constants'
import { useDuplicateReview, useExportDuplicateReview } from '@/features/reports/hooks'
import type {
  DuplicateBand,
  DuplicateDecision,
  DuplicateReviewFilterInput,
  DuplicateReviewReport as Report,
  ReportFormat,
} from '@/features/reports/types'
import { Bars, Figure, Panel } from '@/features/reports/BoardWidgets'
import styles from '@/features/reports/reportBoard.module.css'

/** The words the resolution screen and the exported file use for each decision. */
const DECISIONS: { key: DuplicateDecision; label: string }[] = [
  { key: 'new', label: 'Created as a new person' },
  { key: 'link', label: 'Linked to another MDA’s record' },
  { key: 'own', label: 'Already your beneficiary' },
  { key: 'skip', label: 'Skipped' },
]

const FORMATS: { value: ReportFormat; label: string }[] = [
  { value: 'pdf', label: 'PDF' },
  { value: 'xlsx', label: 'Excel' },
  { value: 'csv', label: 'CSV' },
]

const RESOLUTION_PATH = '/mda/duplicate-resolution'

function duration(hours: number | null): string {
  if (hours === null) return 'No decisions yet'
  if (hours < 1) return 'Under an hour'
  if (hours < 48) return `${Number(hours.toFixed(1))} hours`
  return `${Number((hours / 24).toFixed(1))} days`
}

function uploadedOn(iso: string | null): string {
  if (!iso) return '—'
  const date = new Date(iso)
  return Number.isNaN(date.getTime())
    ? '—'
    : date.toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' })
}

/**
 * The duplicate review report (FR-DUP), in place of the generic group-and-total builder.
 *
 * "Group by match band, measure rows" could only count rows. What an MDA brings to this
 * subject is where its match queue stands: what is waiting, how long it has waited, what
 * was decided, and which upload the backlog came from. So those are the report, the only
 * narrowing is when matches were found and which band, and there is nothing to configure.
 *
 * Counts only. No matched person's name or identifier is requested or shown.
 */
export function DuplicateReviewReport({ canExport }: { canExport: boolean }) {
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo] = useState('')
  const [band, setBand] = useState<'' | DuplicateBand>('')
  const [format, setFormat] = useState<ReportFormat>('pdf')

  const rangeError = dateFrom !== '' && dateTo !== '' && dateTo < dateFrom
  const filter = useMemo<DuplicateReviewFilterInput>(() => {
    const out: DuplicateReviewFilterInput = {}
    if (dateFrom) out.date_from = dateFrom
    if (dateTo) out.date_to = dateTo
    if (band) out.band = band
    return out
  }, [dateFrom, dateTo, band])

  const { data, isLoading, isError, isFetching } = useDuplicateReview(filter, !rangeError)
  const exportReport = useExportDuplicateReview()

  return (
    <div className={styles.dash}>
      <div className={styles.head}>
        <div className={styles.headCopy}>
          <h2 className={styles.title}>Duplicate review</h2>
          <p className={styles.lead}>
            Where your match queue stands: what is waiting, for how long, what was decided, and which upload each match
            came from. Counts only, never the people matched.
          </p>
        </div>

        <div className={styles.controls} role="group" aria-label="Narrow the duplicate review report">
          <div className={styles.control}>
            <TextField label="Found from" type="date" value={dateFrom} max={dateTo || undefined} onChange={(e) => setDateFrom(e.target.value)} />
          </div>
          <div className={styles.control}>
            <TextField
              label="Found to"
              type="date"
              value={dateTo}
              min={dateFrom || undefined}
              error={rangeError ? 'Must be on or after the start date' : undefined}
              onChange={(e) => setDateTo(e.target.value)}
            />
          </div>
          <div className={styles.control}>
            <SelectField
              label="Match type"
              value={band}
              onChange={(e) => setBand(e.target.value as '' | DuplicateBand)}
              options={[
                { value: '', label: 'All matches' },
                { value: 'exact', label: 'Exact match' },
                { value: 'probable', label: 'Possible match' },
              ]}
            />
          </div>
          {canExport && (
            <>
              <div className={styles.control}>
                <SelectField
                  label="Export as"
                  value={format}
                  onChange={(e) => setFormat(e.target.value as ReportFormat)}
                  options={FORMATS}
                />
              </div>
              <Button
                variant="secondary"
                disabled={rangeError}
                loading={exportReport.isPending}
                onClick={() => exportReport.mutate({ filter, format })}
              >
                Export
              </Button>
            </>
          )}
          <span className={styles.updating} aria-live="polite">
            {isFetching && !isLoading ? 'Updating…' : ''}
          </span>
        </div>
      </div>

      {isLoading ? (
        <div className={styles.loading}>
          <Spinner size={22} label="Loading the duplicate review" />
        </div>
      ) : isError || !data ? (
        <p className={styles.empty}>The duplicate review could not be loaded. Please try again.</p>
      ) : data.totals.surfaced === 0 ? (
        <p className={styles.empty}>
          {Object.keys(filter).length > 0
            ? 'No matches were found for this narrowing.'
            : 'No matches found yet. Every upload so far registered without a possible duplicate.'}
        </p>
      ) : (
        <ReportBody report={data} />
      )}
    </div>
  )
}

function ReportBody({ report }: { report: Report }) {
  const { totals } = report
  const parts = [
    { key: 'decided', label: 'Decided', count: totals.decided, className: styles.partDecided },
    { key: 'awaiting', label: 'Awaiting a decision', count: totals.awaiting, className: styles.partAwaiting },
    {
      key: 'closed',
      label: 'Upload finished before a decision',
      count: totals.closed_undecided,
      className: styles.partClosed,
    },
  ].filter((part) => part.key !== 'closed' || part.count > 0)

  const truncated = report.batches.length < report.batches_total

  return (
    <>
      <section className={styles.band} aria-label="The queue at a glance">
        <dl className={styles.figures}>
          <Figure label="Matches found" value={totals.surfaced.toLocaleString()} />
          <Figure label="Awaiting a decision" value={totals.awaiting.toLocaleString()} />
          <Figure label="Decided" value={totals.decided.toLocaleString()} />
          <Figure label="Median time to decide" value={duration(report.median_hours_to_decide)} worded />
        </dl>

        <div className={styles.panelBody}>
          <div className={styles.panelHead}>
            <h3 className={styles.panelTitle}>Review progress</h3>
            <p className={styles.panelSub}>
              {totals.exact.toLocaleString()} exact and {totals.probable.toLocaleString()} probable matches
            </p>
          </div>

          <div className={styles.progress} aria-hidden="true">
            {parts.map((part) =>
              part.count > 0 ? (
                <span
                  key={part.key}
                  className={`${styles.progressPart} ${part.className}`}
                  style={{ flexGrow: part.count }}
                />
              ) : null,
            )}
          </div>
          <ul className={styles.legend}>
            {parts.map((part) => (
              <li key={part.key} className={styles.legendItem}>
                <span className={`${styles.swatch} ${part.className}`} aria-hidden="true" />
                {part.label}
                <span className={styles.legendValue}>{part.count.toLocaleString()}</span>
              </li>
            ))}
          </ul>

          <p className={styles.insight}>
            {totals.awaiting > 0 ? (
              <>
                {totals.awaiting.toLocaleString()} match{totals.awaiting === 1 ? ' is' : 'es are'} waiting for a decision.{' '}
                <Link to={RESOLUTION_PATH} className={styles.link}>
                  Review matches
                  <Icon icon={ArrowRight} size={14} />
                </Link>
              </>
            ) : (
              'Nothing is waiting for a decision.'
            )}
          </p>
          {totals.closed_undecided > 0 && (
            <p className={styles.note}>
              {totals.closed_undecided.toLocaleString()} match{totals.closed_undecided === 1 ? ' was' : 'es were'} in
              uploads that finished or failed before a decision was taken, so no decision can be recorded for them now.
            </p>
          )}
        </div>
      </section>

      <div className={styles.grid}>
        <Panel title="How long matches have waited" sub="Matches still awaiting a decision, by when they were found">
          <Bars
            rows={report.waiting.map((band) => ({ key: band.key, label: band.label, count: band.count }))}
            emptyText="Nothing is waiting for a decision."
          />
        </Panel>

        <Panel title="Decisions taken" sub="What was decided for each match">
          <Bars
            rows={DECISIONS.map((decision) => ({
              key: decision.key,
              label: decision.label,
              count: report.decisions[decision.key] ?? 0,
            }))}
            emptyText="No decisions have been taken yet."
          />
        </Panel>
      </div>

      <section className={styles.section} aria-labelledby="duplicate-review-uploads">
        <h3 id="duplicate-review-uploads" className={styles.sectionTitle}>
          Uploads with matches
        </h3>
        <div className={styles.tableWrap}>
          <table className={styles.table}>
            <thead>
              <tr>
                <th scope="col">Upload</th>
                <th scope="col">Activity</th>
                <th scope="col">Uploaded</th>
                <th scope="col">Source</th>
                <th scope="col" className={styles.num}>
                  Matches
                </th>
                <th scope="col" className={styles.num}>
                  Exact
                </th>
                <th scope="col" className={styles.num}>
                  Probable
                </th>
                <th scope="col" className={styles.num}>
                  Decided
                </th>
                <th scope="col" className={styles.num}>
                  Awaiting
                </th>
              </tr>
            </thead>
            <tbody>
              {report.batches.map((batch) => (
                <tr key={batch.id}>
                  <td className={styles.fileCell} title={batch.file}>
                    {batch.file}
                  </td>
                  <td className={styles.wrapCell}>{batch.activity ?? '—'}</td>
                  <td>{uploadedOn(batch.uploaded_at)}</td>
                  <td>{REGISTRATION_SOURCE_LABELS[batch.source] ?? titleCase(batch.source)}</td>
                  <td className={styles.num}>{batch.matches.toLocaleString()}</td>
                  <td className={styles.num}>{batch.exact.toLocaleString()}</td>
                  <td className={styles.num}>{batch.probable.toLocaleString()}</td>
                  <td className={styles.num}>{batch.decided.toLocaleString()}</td>
                  <td className={styles.num}>{batch.awaiting.toLocaleString()}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        {truncated && (
          <p className={styles.note}>
            Showing the {report.batches.length} uploads with the most waiting, of {report.batches_total}. The exported file
            lists the same uploads.
          </p>
        )}
      </section>
    </>
  )
}
