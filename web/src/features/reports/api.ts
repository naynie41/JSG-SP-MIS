import { apiRequest, apiRequestList } from '@/lib/api/client'
import type { Paginated } from '@/lib/api/client'
import { downloadFile } from '@/lib/api/exportList'
import type {
  AdHocDataset,
  AdHocDefinitionInput,
  AdHocPreview,
  SegmentDefinitionInput,
  SegmentDimensionCatalogue,
  SegmentPreview,
  CatalogueReport,
  ReportDefinition,
  ReportFormat,
  ReportRun,
  ReportSchedule,
} from './types'

/**
 * The Phase 6 reporting engine's HTTP surface. Every call here targets an endpoint
 * that already existed — the administration console composes this engine rather than
 * running a second one.
 */
export const reportsApi = {
  /** Standard report catalogue + the formats the engine can emit. */
  async catalogue(): Promise<{ reports: CatalogueReport[]; formats: { value: ReportFormat; label: string }[] }> {
    return apiRequest({ method: 'GET', url: '/reports/catalogue' })
  },

  /** Whitelisted ad-hoc datasets available to the caller's scope. */
  async datasets(): Promise<AdHocDataset[]> {
    const { datasets } = await apiRequest<{ datasets: AdHocDataset[] }>({
      method: 'GET',
      url: '/reports/adhoc/datasets',
    })
    return datasets
  },

  /** Capped, de-identified preview of an ad-hoc definition. */
  preview(definition: AdHocDefinitionInput): Promise<AdHocPreview> {
    return apiRequest<AdHocPreview>({ method: 'POST', url: '/reports/adhoc/preview', data: definition })
  },

  /** Queue an ad-hoc export. Returns the run; the file is fetched once it is ready. */
  exportAdHoc(definition: AdHocDefinitionInput, format: ReportFormat): Promise<ReportRun> {
    return apiRequest<ReportRun>({ method: 'POST', url: '/reports/adhoc', data: { ...definition, format } })
  },

  /** The segment builder's filter catalogue, derived server-side from the schema. */
  segmentDimensions(): Promise<SegmentDimensionCatalogue> {
    return apiRequest<SegmentDimensionCatalogue>({ method: 'GET', url: '/reports/segments/dimensions' })
  },

  /** Run a composed segment and return one page of it. */
  segmentPreview(definition: SegmentDefinitionInput): Promise<SegmentPreview> {
    return apiRequest<SegmentPreview>({ method: 'POST', url: '/reports/segments/preview', data: definition })
  },

  /** Queue a segment export; the file is fetched once the run is ready. */
  exportSegment(definition: SegmentDefinitionInput, format: ReportFormat): Promise<ReportRun> {
    return apiRequest<ReportRun>({ method: 'POST', url: '/reports/segments/export', data: { ...definition, format } })
  },

  /** Queue a standard catalogue report. */
  generate(reportKey: string, format: ReportFormat): Promise<ReportRun> {
    return apiRequest<ReportRun>({ method: 'POST', url: '/reports', data: { report_key: reportKey, format } })
  },

  /** My report runs, most recent first. */
  runs(perPage = 20): Promise<Paginated<ReportRun>> {
    return apiRequestList<ReportRun>({ method: 'GET', url: '/reports', params: { per_page: perPage } })
  },

  run(id: string): Promise<ReportRun> {
    return apiRequest<ReportRun>({ method: 'GET', url: `/reports/${id}` })
  },

  /** Authenticated download of a ready run. */
  download(run: ReportRun): Promise<void> {
    return downloadFile(`/reports/${run.id}/download`, {}, run.file_name ?? `report.${run.format}`)
  },

  async definitions(): Promise<ReportDefinition[]> {
    const { definitions } = await apiRequest<{ definitions: ReportDefinition[] }>({
      method: 'GET',
      url: '/report-definitions',
    })
    return definitions
  },

  saveDefinition(name: string, definition: AdHocDefinitionInput): Promise<ReportDefinition> {
    return apiRequest<ReportDefinition>({ method: 'POST', url: '/report-definitions', data: { name, ...definition } })
  },

  async schedules(): Promise<ReportSchedule[]> {
    const { schedules } = await apiRequest<{ schedules: ReportSchedule[] }>({
      method: 'GET',
      url: '/report-schedules',
    })
    return schedules
  },

  createSchedule(payload: {
    name: string
    report_key?: string
    report_definition_id?: string
    format: ReportFormat
    frequency: string
    delivery: string
    recipient_user_ids: string[]
  }): Promise<ReportSchedule> {
    return apiRequest<ReportSchedule>({ method: 'POST', url: '/report-schedules', data: payload })
  },

  updateSchedule(id: string, changes: Partial<{ status: string; format: string; frequency: string }>): Promise<ReportSchedule> {
    return apiRequest<ReportSchedule>({ method: 'PATCH', url: `/report-schedules/${id}`, data: changes })
  },

  deleteSchedule(id: string): Promise<void> {
    return apiRequest<void>({ method: 'DELETE', url: `/report-schedules/${id}` })
  },
}
