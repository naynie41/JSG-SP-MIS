import { useState } from 'react'
import { Download, FileBarChart, Lock, ShieldCheck } from 'lucide-react'
import { Badge } from '@/components/Badge/Badge'
import { Card } from '@/components/Card/Card'
import { Icon } from '@/components/Icon/Icon'
import { Tabs } from '@/components/Tabs/Tabs'
import { DataTableExport } from '@/components/DataTable/DataTableExport'
import { useAuth } from '@/lib/auth/AuthProvider'
import { useReportDatasets } from '@/features/reports/hooks'
import {
  BuilderPanel,
  CataloguePanel,
  ReportsLoading,
  RunsPanel,
  SchedulesPanel,
} from '@/features/reports/ReportPanels'
import styles from './mda.module.css'

/**
 * The six report types this module offers, each mapped to the dataset that answers it.
 *
 * There is no "programmes" dataset for an MDA: a programme is a shared, unowned
 * catalogue entry (§10), so counting catalogue rows would tell an MDA nothing about
 * itself. A programme report is therefore the MDA's own delivery GROUPED BY programme —
 * which is what `activities` and `benefits` already express.
 */
const REPORT_TYPES: { label: string; dataset: string; hint: string }[] = [
  { label: 'Programme', dataset: 'benefits', hint: 'Your delivery grouped by programme — the catalogue is shared, your delivery is yours.' },
  { label: 'Activity', dataset: 'activities', hint: 'Activities you run, their targets and budgets, by programme, status or locality.' },
  { label: 'Beneficiary', dataset: 'beneficiaries', hint: 'Who you have registered, by LGA, ward, status or source.' },
  { label: 'Benefit', dataset: 'benefits', hint: 'Deliveries recorded and their value, by programme, type or locality.' },
  { label: 'Referral', dataset: 'referrals', hint: 'Referrals your MDA is party to, by status and counterparty.' },
  { label: 'Duplicate', dataset: 'duplicates', hint: 'Match bands and resolutions across your own imports.' },
]

/** "a benefit report" / "an activity report" — the labels are fixed, so this is enough. */
const article = (word: string): string => (/^[aeiou]/i.test(word) ? 'an' : 'a')

/* ---------------------------------------------------------- report type overview */

function ReportTypes({ available, onBuild }: { available: Set<string>; onBuild: (dataset: string) => void }) {
  return (
    <div className={styles.section}>
      <div className={styles.cardGrid}>
        {REPORT_TYPES.map((type) => {
          const reachable = available.has(type.dataset)
          return (
            <Card key={type.label} titleAs="h3" title={`${type.label} reports`} eyebrow="Aggregate · your MDA">
              <p className={styles.queueNote}>{type.hint}</p>
              {reachable ? (
                <button type="button" className={styles.action} onClick={() => onBuild(type.dataset)}>
                  <Icon icon={FileBarChart} size={15} />
                  Build {article(type.label)} {type.label.toLowerCase()} report
                </button>
              ) : (
                <p className={styles.muted}>
                  <Icon icon={Lock} size={13} /> Not available to your account.
                </p>
              )}
            </Card>
          )
        })}
      </div>
      <p className={styles.footnote}>
        Every figure here is scoped to your MDA — a filter can narrow it further but never reach another MDA&apos;s data
      </p>
    </div>
  )
}

/* --------------------------------------------------------- beneficiary list export */

/**
 * The one PII-bearing export, and therefore the one governed by the export permission
 * matrix (docs/SECURITY.md §3) rather than by `reporting.export`:
 *
 *  - **MDA Admin** — the one MDA role since FR-UAM-01 — may export, own MDA only.
 *  - **NIN/BVN are masked** unless the caller also holds `export.reveal_pii`, which is a
 *    System Administrator permission and is never bundled into an MDA role.
 *  - Every export is audited with actor, scope, filters, format and row count.
 *
 * The affordance below mirrors that; the server enforces it. `DataTableExport` posts to
 * the existing `/beneficiaries/export`, which streams a small file and queues a large one
 * through the same report-run pipeline.
 */
function BeneficiaryExportPanel() {
  const { hasPermission } = useAuth()
  const canExport = hasPermission('beneficiary.export')
  const canReveal = hasPermission('export.reveal_pii')

  return (
    <div className={styles.section}>
      <Card
        titleAs="h3"
        title="Beneficiary list export"
        eyebrow="Personal records · permission-controlled"
      >
        <p className={styles.queueNote}>
          A row-level export of the beneficiaries your MDA owns — the only export here that contains personal records.
          It carries the same scope and filters as the registry list it comes from.
        </p>

        <div className={styles.rowActions} style={{ justifyContent: 'flex-start' }}>
          <Badge variant={canExport ? 'success' : 'warning'} dot>
            {canExport ? 'You may export' : 'Not permitted'}
          </Badge>
          <Badge variant="neutral" dot>
            {canReveal ? 'NIN/BVN revealed' : 'NIN/BVN masked'}
          </Badge>
        </div>

        {canExport ? (
          <div className={styles.section}>
            {/* Unfiltered: this is the whole registry the MDA owns. The control gates
                itself on the same permission and the server enforces scope, masking
                and the audit entry. */}
            <DataTableExport endpoint="/beneficiaries/export" params={{}} permission="beneficiary.export" />
            <p className={styles.queueNote}>
              {canReveal
                ? 'Your account holds the reveal permission, so identifiers are exported in full. This is audited distinctly.'
                : 'Identifiers are masked in the file. Revealing them is a separate, rarer permission held by the System Administrator.'}
            </p>
          </div>
        ) : (
          <p className={styles.muted}>
            <Icon icon={Lock} size={13} /> Bulk export of personal records is an MDA Administrator permission. An
            administrator can grant it to your account if your work requires it — it stays limited to your own MDA.
          </p>
        )}

        <p className={styles.footnote}>
          Every export is recorded in the audit log with who ran it, the scope and filters applied, the format and the
          row count
        </p>
      </Card>
    </div>
  )
}

/* ---------------------------------------------------------------------- page */

/**
 * Reports — the MDA's reporting over the Phase 6 engine, scoped to its own data.
 *
 * **No reporting engine here, and no reporting UI either.** The builder, schedules and
 * run history are the shared panels in `@/features/reports/ReportPanels` — the same
 * components the administration console uses — over the same endpoints
 * (`/reports/adhoc/*`, `/report-schedules`, `/reports`) and the same exporter registry
 * for CSV, Excel and PDF.
 *
 * **Scope is the server's decision.** `/reports/adhoc/datasets` returns only what the
 * caller's resolved `DashboardScope` permits, so this page renders what it is given
 * rather than deciding entitlement. An MDA scope constrains every dataset to its own
 * rows, and a filter can only narrow within that.
 *
 * **Two different export gates, deliberately.** Aggregate reports carry no personal
 * record — no identifier column is even selectable — and ride `reporting.export`. The
 * beneficiary list export carries PII and rides the SECURITY.md §3 matrix:
 * `beneficiary.export` (Admin yes, Officer only if granted), masked unless
 * `export.reveal_pii`. Conflating the two would either block legitimate aggregate
 * reporting or leak a PII path to everyone who can run a report.
 */
export function MdaReportsPage() {
  const { hasPermission } = useAuth()
  const canView = hasPermission('reporting.view')
  const canExport = hasPermission('reporting.export')

  const { data, isLoading, error } = useReportDatasets(canView)
  const [tab, setTab] = useState('types')
  const [seedDataset, setSeedDataset] = useState<string | undefined>(undefined)

  if (!canView) {
    return (
      <Card>
        <p className={styles.forbidden}>You do not have permission to view reports.</p>
      </Card>
    )
  }

  const datasets = data ?? []
  const available = new Set(datasets.map((d) => d.key))

  function buildFrom(dataset: string) {
    setSeedDataset(dataset)
    setTab('builder')
  }

  return (
    <div className={styles.page}>
      <header className={styles.pageHead}>
        <span className={styles.eyebrow}>MDA workspace</span>
        <h1 className={styles.pageTitle}>Reports</h1>
        <p className={styles.lead}>
          Reporting over your MDA&apos;s own data — programmes you deliver, activities you run, people you have
          registered, benefits delivered, referrals and duplicate review. Generated, scheduled and exported by the
          shared reporting engine.
        </p>
      </header>

      {isLoading && <ReportsLoading label="Loading report datasets" />}
      {error && <p className={styles.muted}>Could not load the report catalogue. Please try again.</p>}

      {!isLoading && !error && (
        <Tabs
          activeId={tab}
          onChange={setTab}
          items={[
            {
              id: 'types',
              label: 'Report types',
              content: <ReportTypes available={available} onBuild={buildFrom} />,
            },
            {
              id: 'catalogue',
              label: 'Datasets',
              content: (
                <CataloguePanel
                  datasets={datasets}
                  onBuild={buildFrom}
                  footnote="Each dataset is constrained to your MDA by the server — counts and sums only, never a personal record"
                />
              ),
            },
            {
              id: 'builder',
              label: 'Build & export',
              content: canExport ? (
                <BuilderPanel
                  datasets={datasets}
                  initialDataset={seedDataset}
                  eyebrow="Aggregate only — scoped to your MDA"
                />
              ) : (
                <p className={styles.muted}>
                  Generating and exporting reports needs the reporting export permission.
                </p>
              ),
            },
            {
              id: 'schedules',
              label: 'Scheduled reports',
              content: (
                <SchedulesPanel
                  canManage={canExport}
                  footnote="A scheduled report is delivered only to recipients whose own scope covers it, so a schedule can never carry your MDA’s data to someone who could not have run the report themselves"
                />
              ),
            },
            { id: 'runs', label: 'Recent exports', content: <RunsPanel /> },
            {
              id: 'registry-export',
              label: 'Beneficiary export',
              content: <BeneficiaryExportPanel />,
            },
          ]}
        />
      )}

      <section className={styles.section} aria-label="How exports are controlled">
        <div className={styles.sectionHead}>
          <Icon icon={ShieldCheck} size={16} />
          <h2 className={styles.sectionTitle}>What you can export</h2>
        </div>
        <Card>
          <p className={styles.muted}>
            <Icon icon={Download} size={14} /> Aggregate reports contain no personal records, so anyone in your MDA
            who can run a report can export one. A row-level beneficiary export is different: it is an MDA
            Administrator permission, limited to your own MDA, with NIN and BVN masked unless a separate reveal
            permission has been granted.
          </p>
          <p className={styles.footnote}>
            You can only ever export what you could already see — an export inherits the scope and filters of the
            report or list it came from
          </p>
        </Card>
      </section>
    </div>
  )
}
