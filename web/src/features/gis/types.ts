/** GIS coverage payload (mirrors /gis/coverage). */

export type GisLevel = 'lga' | 'ward'
export type GisMode = 'choropleth' | 'table'
/** Absolute traffic-light band (NOT a population %). Grey = no coverage. */
export type CoverageBand = 'green' | 'yellow' | 'red' | 'grey'

/** Per-area coverage + click-through detail (only the fields we actually hold —
 * no population / poverty / vulnerability / coverage-%). */
export interface CoverageRow {
  key: string
  name: string
  beneficiary_count: number // registered individuals in the area
  benefit_count: number
  benefit_value: number // kobo — budget spent (delivered value)
  households: number
  served: number // net-unique beneficiaries served
  active_programmes: number
  active_activities: number
  mdas: string[] // implementing MDA names
  band: CoverageBand
}

export interface CoverageFeatureProperties extends Omit<CoverageRow, 'key'> {
  code: string
  level: GisLevel
}

export interface CoverageFeature {
  type: 'Feature'
  geometry: GeoJSON.Geometry
  properties: CoverageFeatureProperties
}

export interface CoverageFeatureCollection {
  type: 'FeatureCollection'
  features: CoverageFeature[]
}

export interface GisCoverageResponse {
  level: GisLevel
  scope: { kind: string; label: string }
  mode: GisMode
  bands: { green_min: number; yellow_min: number } // absolute thresholds (for the legend)
  rows: CoverageRow[]
  feature_collection: CoverageFeatureCollection | null
}

/** The metric a viewer chooses to colour the map / rank the table by. */
export type CoverageMetric = 'beneficiary_count' | 'benefit_value'
