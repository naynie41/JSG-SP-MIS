/** GIS coverage payload (mirrors /gis/coverage). */

export type GisLevel = 'lga' | 'ward'
export type GisMode = 'choropleth' | 'table'

export interface CoverageRow {
  key: string
  name: string
  beneficiary_count: number
  benefit_count: number
  benefit_value: number // kobo
}

export interface CoverageFeatureProperties {
  code: string
  name: string
  level: GisLevel
  beneficiary_count: number
  benefit_count: number
  benefit_value: number
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
  rows: CoverageRow[]
  feature_collection: CoverageFeatureCollection | null
}

/** The metric a viewer chooses to colour the map / rank the table by. */
export type CoverageMetric = 'beneficiary_count' | 'benefit_value'
