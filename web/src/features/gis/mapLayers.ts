import type { Layer, LatLng, PathOptions } from 'leaflet'

/**
 * Pluggable overlay-layer framework for the coverage map.
 *
 * The coverage choropleth is the base layer. Additional *contextual* layers — schools,
 * health facilities, IDP camps, flood-risk areas, etc. — are supplied later as external
 * GeoJSON and registered here; the map renders every registered layer as a toggleable
 * overlay. No such layers ship today (by design); this is only the extension point.
 *
 * ── Registering a layer (documented example) ─────────────────────────────────────────
 * Call `registerMapLayer` once at app start (e.g. in a bootstrap module). `load` is
 * invoked lazily the first time a viewer enables the layer, so the GeoJSON is only
 * fetched on demand:
 *
 * ```ts
 * import { registerMapLayer } from '@/features/gis/mapLayers'
 *
 * registerMapLayer({
 *   id: 'schools',
 *   label: 'Schools',
 *   // Any GeoJSON source: a bundled asset, a signed URL, or an API endpoint.
 *   load: async () => (await fetch('/geo/schools.geojson')).json(),
 *   // Points → small circle markers; polygons/lines use `style` instead.
 *   pointToLayer: (_feature, latlng) => L.circleMarker(latlng, { radius: 4, color: '#356e7a' }),
 *   tooltip: (props) => String(props.name ?? 'School'),
 * })
 * ```
 *
 * A polygon layer (e.g. flood-risk areas) looks the same but supplies `style` instead of
 * `pointToLayer`:
 *
 * ```ts
 * registerMapLayer({
 *   id: 'flood-risk',
 *   label: 'Flood risk',
 *   load: async () => (await fetch('/geo/flood-risk.geojson')).json(),
 *   style: { color: '#356e7a', weight: 1, fillOpacity: 0.25 },
 * })
 * ```
 */
export interface MapOverlayLayer {
  /** Stable unique id (also the registry key). */
  id: string
  /** Human label for the toggle control. */
  label: string
  /** Load the layer's GeoJSON on demand (bundled asset, URL, or API). */
  load: () => Promise<GeoJSON.GeoJsonObject> | GeoJSON.GeoJsonObject
  /** Leaflet path style for polygon/line features. */
  style?: PathOptions
  /** Turn a GeoJSON point into a Leaflet marker/circle (point layers). */
  pointToLayer?: (feature: GeoJSON.Feature, latlng: LatLng) => Layer
  /** Tooltip text for a feature. */
  tooltip?: (properties: Record<string, unknown>) => string
}

const registry = new Map<string, MapOverlayLayer>()

/** Register (or replace) an overlay layer. */
export function registerMapLayer(layer: MapOverlayLayer): void {
  registry.set(layer.id, layer)
}

/** Remove a previously-registered layer. */
export function unregisterMapLayer(id: string): void {
  registry.delete(id)
}

/** All registered overlay layers, in registration order. */
export function getMapLayers(): MapOverlayLayer[] {
  return [...registry.values()]
}

/** Clear the registry (primarily for tests/hot-reload). */
export function clearMapLayers(): void {
  registry.clear()
}
