/** A Jigawa Local Government Area (GEO.1 reference data). */
export interface Lga {
  id: string
  code: string
  name: string
  state: string
  /** How many wards are loaded for this LGA — 0 means ward data has not been supplied. */
  ward_count: number
}

/** A ward, always within one LGA. Ward codes are unique only within their LGA. */
export interface Ward {
  id: string
  lga_id: string
  code: string
  name: string
}

/**
 * One LGA in an activity's declared location set, as the form holds it and the API
 * accepts it. `whole_lga` and a non-empty `ward_ids` are mutually exclusive.
 */
export interface LocationSetEntry {
  lga_id: string
  ward_ids: string[]
  whole_lga: boolean
}
