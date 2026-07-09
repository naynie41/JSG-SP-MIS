/** Choropleth colour scale for the coverage map (design-system palette). */

/** Sequential mint → forest ramp; lime stays reserved as the accent, not in the scale. */
export const CHOROPLETH_RAMP = ['#DCEDDC', '#AFD6B4', '#7FB588', '#4E7A4B', '#2C3512']

const NO_DATA = '#EFEFEA'

/** Bucket a value against the max onto the ramp; 0/no-data gets the pale swatch. */
export function colorFor(value: number, max: number): string {
  if (value <= 0 || max <= 0) {
    return NO_DATA
  }
  const ratio = value / max
  const index = ratio <= 0.2 ? 0 : ratio <= 0.4 ? 1 : ratio <= 0.6 ? 2 : ratio <= 0.8 ? 3 : 4

  return CHOROPLETH_RAMP[index]!
}
