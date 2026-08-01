import { FundingPartnerCoordinationTab } from './FundingPartnerCoordinationTab'
import { FundingPartnerInvestmentTab } from './FundingPartnerInvestmentTab'
import { FundingPartnerOverviewTab } from './FundingPartnerOverviewTab'
import { FundingPartnerProgrammesTab } from './FundingPartnerProgrammesTab'
import { FundingPartnerRegistryTab } from './FundingPartnerRegistryTab'
import { usePartnerDashboard } from './partnerContext'

/**
 * Routed pages for the Development-Partner suite (Phase 6P). Each is a thin wrapper that
 * reads the shared dashboard/filter/drill from {@link usePartnerDashboard} (provided by
 * PartnerLayout) and renders the corresponding section body. The section bodies are the
 * same components the suite has always used — only the navigation changed (side-rail
 * pages instead of in-page tabs).
 */

export function PartnerOverviewPage() {
  const { data, drill } = usePartnerDashboard()
  return <FundingPartnerOverviewTab data={data} onDrill={drill} />
}

export function PartnerProgrammesPage() {
  const { data, canDrill } = usePartnerDashboard()
  return <FundingPartnerProgrammesTab data={data} canDrill={canDrill} />
}

export function PartnerRegistryPage() {
  const { data } = usePartnerDashboard()
  return <FundingPartnerRegistryTab data={data} />
}

export function PartnerCoordinationPage() {
  const { data, drill } = usePartnerDashboard()
  return <FundingPartnerCoordinationTab data={data} onDrill={drill} />
}

export function PartnerInvestmentPage() {
  const { filter, drill } = usePartnerDashboard()
  return <FundingPartnerInvestmentTab filter={filter} onDrill={drill} />
}
