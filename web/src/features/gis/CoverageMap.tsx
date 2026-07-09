import { useEffect } from 'react'
import { GeoJSON, MapContainer, TileLayer, useMap } from 'react-leaflet'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { colorFor } from './choropleth'
import type { CoverageFeatureCollection, CoverageFeatureProperties, CoverageMetric } from './types'
import styles from './gis.module.css'

const TILE_URL = (import.meta.env.VITE_MAP_TILE_URL as string | undefined) ?? 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'

/** Fits the map to the loaded boundaries whenever they change. */
function FitBounds({ data }: { data: CoverageFeatureCollection }) {
  const map = useMap()
  useEffect(() => {
    if (data.features.length === 0) {
      return
    }
    const bounds = L.geoJSON(data as unknown as GeoJSON.GeoJsonObject).getBounds()
    if (bounds.isValid()) {
      map.fitBounds(bounds, { padding: [16, 16] })
    }
  }, [data, map])

  return null
}

/**
 * LGA/Ward coverage choropleth (PRD FR-GIS-01). Colours each boundary by the chosen
 * metric on the design-system ramp; tiles are configurable via `VITE_MAP_TILE_URL`.
 * Heat maps (FR-GIS-02) can hang off the PostGIS `geom` served alongside this — a
 * clean extension point; not built here.
 */
export function CoverageMap({ data, metric }: { data: CoverageFeatureCollection; metric: CoverageMetric }) {
  const max = Math.max(1, ...data.features.map((f) => f.properties[metric]))

  return (
    <MapContainer center={[12.2, 9.55]} zoom={8} scrollWheelZoom={false} className={styles.map}>
      <TileLayer url={TILE_URL} attribution="&copy; OpenStreetMap contributors" />
      <GeoJSON
        key={`${metric}:${data.features.length}`}
        data={data as unknown as GeoJSON.GeoJsonObject}
        style={(feature) => {
          const props = feature?.properties as CoverageFeatureProperties | undefined
          return { fillColor: colorFor(props?.[metric] ?? 0, max), weight: 1, color: '#2C3512', fillOpacity: 0.75 }
        }}
        onEachFeature={(feature, layer) => {
          const props = feature.properties as CoverageFeatureProperties
          const value = metric === 'benefit_value'
            ? `₦${(props.benefit_value / 100).toLocaleString()}`
            : `${props.beneficiary_count.toLocaleString()} beneficiaries`
          layer.bindTooltip(`${props.name}: ${value}`)
        }}
      />
      <FitBounds data={data} />
    </MapContainer>
  )
}
