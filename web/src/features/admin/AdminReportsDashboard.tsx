import { useState } from 'react'
import { FileDown } from 'lucide-react'
import { Button } from '@/components/Button/Button'
import { SelectField } from '@/components/Field/SelectField'
import { Spinner } from '@/components/Spinner/Spinner'
import { dashboardApi } from '@/features/dashboard/api'
import { useDashboard } from '@/features/dashboard/hooks'
import { EMPTY_FILTER } from '@/features/dashboard/types'
import type { DashboardFilterValue } from '@/features/dashboard/types'
import { titleCase } from '@/features/registry/constants'
import { formatCount } from '@/features/reports/counts'
import {
  AgeCard,
  BenefitsCard,
  CoverageCard,
  GenderCard,
  HeadlineTiles,
  HouseholdCard,
  LgaCard,
  ProgrammesCard,
  QualityCard,
  RecordsCard,
  TrendCard,
} from '@/features/reports/BoardCards'
import { MdaDeliveryCard } from '@/features/reports/MdaDeliveryCard'
import styles from '@/features/reports/reportBoard.module.css'

/* -------------------------------------------------------------------- helpers */

function isEmptyFilter(filter: DashboardFilterValue): boolean {
  return Object.values(filter).every((value) => value === null)
}

function computedAt(iso: string): string {
  const date = new Date(iso)
  return Number.isNaN(date.getTime())
    ? 'at an unknown time'
    : date.toLocaleString(undefined, { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}

/* ---------------------------------------------------------------------- page */

/**
 * The Dashboard tab of the administration console's Reports (PRD FR-DSH-01).
 *
 * The same board an MDA reads, at STATE-WIDE scope — the cards are shared
 * (`features/reports/BoardCards`), so the two consoles cannot drift into showing
 * the same figure two ways.
 *
 * What this console adds is the question an MDA console structurally cannot ask:
 * how agencies compare. It sees across MDAs, so delivery by MDA leads the second
 * half of the board, and the filter bar carries an MDA filter the MDA board has no
 * use for. The demographic and quality cards follow, describing the state's whole
 * registry rather than one agency's.
 */
export function AdminReportsDashboard({ canExport }: { canExport: boolean }) {
  const [filter, setFilter] = useState<DashboardFilterValue>(EMPTY_FILTER)
  const [exporting, setExporting] = useState(false)
  const [exportFailed, setExportFailed] = useState(false)
  const active = !isEmptyFilter(filter)
  const { data, isLoading, isFetching } = useDashboard(active ? filter : undefined)

  const runExport = async () => {
    setExporting(true)
    setExportFailed(false)
    try {
      // 'board' asks for the page itself — tiles, charts and the map — not the
      // executive suite's sectioned export off the same endpoint.
      await dashboardApi.export('pdf', active ? filter : undefined, 'state-dashboard.pdf', 'board')
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
  const byMda = data.metrics.mda_delivery ?? []

  return (
    <div className={styles.dash}>
      <div className={styles.head}>
        <div className={styles.headCopy}>
          <h2 className={styles.title}>Across the whole state</h2>
          <p className={styles.lead}>
            Everyone registered in Jigawa and the benefits delivered to them, across every MDA.{' '}
            {formatCount(data.metrics.registry.beneficiaries.total, minimum)} beneficiaries in view, each person
            counted once however many MDAs or programmes they are in. Figures as at {computedAt(data.computed_at)}.
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
          {/* The MDA filter is this console's own: only a state-wide scope has more
              than one agency to choose between. */}
          {(options?.mdas.length ?? 0) > 0 && (
            <div className={styles.controlWide}>
              <SelectField
                label="MDA"
                value={filter.mda_id ?? ''}
                onChange={(event) => set('mda_id', event.target.value)}
                options={[
                  { value: '', label: 'All MDAs' },
                  ...(options?.mdas ?? []).map((mda) => ({ value: mda.id, label: mda.name })),
                ]}
              />
            </div>
          )}
          {(options?.programmes.length ?? 0) > 0 && (
            <div className={styles.controlWide}>
              <SelectField
                label="Programme"
                value={filter.programme_id ?? ''}
                onChange={(event) => set('programme_id', event.target.value)}
                options={[
                  { value: '', label: 'All programmes' },
                  ...(options?.programmes ?? []).map((programme) => ({ value: programme.id, label: programme.name })),
                ]}
              />
            </div>
          )}
          <div className={styles.controlWide}>
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

        {/* The governance question first: who is delivering, and how much. */}
        {byMda.length > 0 && (
          <section className={styles.section} aria-labelledby="admin-reports-agencies">
            <h3 id="admin-reports-agencies" className={styles.sectionTitle}>
              How the agencies compare
            </h3>
            <MdaDeliveryCard rows={byMda} minimum={minimum} />
          </section>
        )}

        <section className={styles.section} aria-labelledby="admin-reports-who">
          <h3 id="admin-reports-who" className={styles.sectionTitle}>
            Who is registered
          </h3>
          <div className={styles.grid3}>
            <GenderCard data={data} />
            <AgeCard data={data} />
            <HouseholdCard data={data} />
          </div>
        </section>

        <section className={styles.section} aria-labelledby="admin-reports-where">
          <h3 id="admin-reports-where" className={styles.sectionTitle}>
            Where they are
          </h3>
          <div className={styles.split21}>
            <CoverageCard filter={active ? filter : undefined} />
            <LgaCard data={data} />
          </div>
        </section>

        <section className={styles.section} aria-labelledby="admin-reports-delivery">
          <h3 id="admin-reports-delivery" className={styles.sectionTitle}>
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
