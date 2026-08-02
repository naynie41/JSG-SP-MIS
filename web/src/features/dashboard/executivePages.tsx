import { CoordinationTab } from './CoordinationTab'
import { CoverageMapTab } from './CoverageMapTab'
import { ExecutiveOverviewTab } from './ExecutiveOverviewTab'
import { ProgrammesTab } from './ProgrammesTab'
import { RegistryTab } from './RegistryTab'
import { useExecutiveDashboard } from './executiveContext'

/**
 * Routed pages for the Executive briefing suite (Phase 6E). Each is a thin wrapper that
 * reads the shared dashboard/filter/drill from {@link useExecutiveDashboard} (provided by
 * ExecutiveLayout) and renders the corresponding section body. The section bodies are the
 * same components the suite has always used — only the navigation changed (side-rail
 * pages instead of in-page tabs).
 */

export function ExecutiveOverviewPage() {
  const { data, drill } = useExecutiveDashboard()
  return <ExecutiveOverviewTab data={data} onDrill={drill} />
}

export function ExecutiveProgrammesPage() {
  const { data, drill } = useExecutiveDashboard()
  return <ProgrammesTab data={data} onDrill={drill} />
}

export function ExecutiveRegistryPage() {
  const { data } = useExecutiveDashboard()
  return <RegistryTab data={data} />
}

export function ExecutiveCoordinationPage() {
  const { data } = useExecutiveDashboard()
  return <CoordinationTab data={data} />
}

export function ExecutiveCoveragePage() {
  const { filter } = useExecutiveDashboard()
  return <CoverageMapTab filter={filter} />
}
