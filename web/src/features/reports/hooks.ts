import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useToast } from '@/components/Toast/ToastProvider'
import { ApiError } from '@/types/api'
import { reportsApi } from './api'
import type { AdHocDefinitionInput, ReportFormat, ReportRun } from './types'

const DATASETS_KEY = ['report-datasets']
const CATALOGUE_KEY = ['report-catalogue']
const RUNS_KEY = ['report-runs']
const SCHEDULES_KEY = ['report-schedules']

const message = (error: unknown, fallback: string): string =>
  error instanceof ApiError ? error.message : fallback

export function useReportDatasets() {
  return useQuery({ queryKey: DATASETS_KEY, queryFn: () => reportsApi.datasets() })
}

export function useReportCatalogue() {
  return useQuery({ queryKey: CATALOGUE_KEY, queryFn: () => reportsApi.catalogue() })
}

export function useReportRuns(perPage = 20) {
  return useQuery({ queryKey: [...RUNS_KEY, perPage], queryFn: () => reportsApi.runs(perPage) })
}

export function useReportSchedules() {
  return useQuery({ queryKey: SCHEDULES_KEY, queryFn: () => reportsApi.schedules() })
}

/** Preview an ad-hoc definition. A rejected definition is a 422 with the reason. */
export function usePreviewReport() {
  const toast = useToast()

  return useMutation({
    mutationFn: (definition: AdHocDefinitionInput) => reportsApi.preview(definition),
    onError: (error) => toast.error('Could not build that report', message(error, 'Please adjust the selection.')),
  })
}

/** Queue an export through the engine, then refresh the run list. */
export function useExportReport() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: ({ definition, format }: { definition: AdHocDefinitionInput; format: ReportFormat }) =>
      reportsApi.exportAdHoc(definition, format),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: RUNS_KEY })
      toast.success('Export queued', 'It appears under Recent exports as soon as it is ready.')
    },
    onError: (error) => toast.error('Could not start the export', message(error, 'Please try again.')),
  })
}

/** Queue a standard catalogue report. */
export function useGenerateReport() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: ({ reportKey, format }: { reportKey: string; format: ReportFormat }) =>
      reportsApi.generate(reportKey, format),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: RUNS_KEY })
      toast.success('Report queued', 'It appears under Recent exports as soon as it is ready.')
    },
    onError: (error) => toast.error('Could not generate the report', message(error, 'Please try again.')),
  })
}

export function useDownloadRun() {
  const toast = useToast()

  return useMutation({
    mutationFn: (run: ReportRun) => reportsApi.download(run),
    onError: (error) => toast.error('Could not download the file', message(error, 'Please try again.')),
  })
}

export function useSaveDefinition() {
  const toast = useToast()

  return useMutation({
    mutationFn: ({ name, definition }: { name: string; definition: AdHocDefinitionInput }) =>
      reportsApi.saveDefinition(name, definition),
    onSuccess: () => toast.success('Report saved', 'You can schedule it from Scheduled reports.'),
    onError: (error) => toast.error('Could not save the report', message(error, 'Please try again.')),
  })
}

export function useUpdateSchedule() {
  const qc = useQueryClient()
  const toast = useToast()

  return useMutation({
    mutationFn: ({ id, changes }: { id: string; changes: { status?: string } }) =>
      reportsApi.updateSchedule(id, changes),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: SCHEDULES_KEY })
      toast.success('Schedule updated')
    },
    onError: (error) => toast.error('Could not update the schedule', message(error, 'Please try again.')),
  })
}
