import { useEffect, useState } from 'react'
import { GeoJSON, MapContainer, TileLayer, useMap } from 'react-leaflet'
import L from 'leaflet'
import type { Path, PathOptions } from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { BAND_COLORS } from '@/features/gis/choropleth'
import type { CoverageFeatureCollection, CoverageFeatureProperties } from '@/features/gis/types'
import type { MapOverlayLayer } from '@/features/gis/mapLayers'
import { CoverageHoverCard } from './CoverageHoverCard'
import styles from './coverageMap.module.css'

const TILE_URL = (import.meta.env.VITE_MAP_TILE_URL as string | undefined) ?? 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'

/** Fits the map to the loaded boundaries whenever they change. */
function FitBounds({ data }: { data: CoverageFeatureCollection }) {
  const map = useMap()
  useEffect(() => {
    if (data.features.length === 0) return
    const bounds = L.geoJSON(data as unknown as GeoJSON.GeoJsonObject).getBounds()
    if (bounds.isValid()) map.fitBounds(bounds, { padding: [16, 16] })
  }, [data, map])
  return null
}

/** A registered contextual overlay — loads its GeoJSON lazily when mounted (enabled). */
function OverlayLayer({ layer }: { layer: MapOverlayLayer }) {
  const [data, setData] = useState<GeoJSON.GeoJsonObject | null>(null)
  useEffect(() => {
    let live = true
    Promise.resolve(layer.load())
      .then((d) => live && setData(d))
      .catch(() => undefined)
    return () => {
      live = false
    }
  }, [layer])

  if (!data) return null
  return (
    <GeoJSON
      data={data}
      style={layer.style}
      pointToLayer={layer.pointToLayer}
      onEachFeature={(feature, lyr) => {
        if (layer.tooltip) lyr.bindTooltip(layer.tooltip((feature.properties ?? {}) as Record<string, unknown>))
      }}
    />
  )
}

/**
 * A boundary's resting appearance: band fill, heavier ink when it is the selected area.
 *
 * Named rather than inline because hovering has to put it back — `setStyle` mutates the
 * layer, and Leaflet will not re-run the `style` prop to undo it.
 */
function styleFor(p: CoverageFeatureProperties | undefined, selectedCode: string | null): PathOptions {
  const selected = p?.code === selectedCode
  return {
    fillColor: BAND_COLORS[p?.band ?? 'grey'],
    weight: selected ? 3 : 1,
    color: selected ? '#181818' : '#2C3512',
    fillOpacity: 0.72,
  }
}

/** Under the pointer: a heavier outline and a slightly denser fill, no colour change. */
const HOVER_STYLE: PathOptions = { weight: 3, color: '#181818', fillOpacity: 0.86 }

export interface BandChoroplethMapProps {
  data: CoverageFeatureCollection
  selectedCode: string | null
  onSelect: (code: string) => void
  overlays: MapOverlayLayer[]
  /** "LGA" or "Ward" — names what the hover card asks the viewer to point at. */
  areaWord?: string
  /** Lead the hover card with the figure this map is coloured by. */
  leadOf?: (properties: CoverageFeatureProperties) => { label: string; value: string }
}

/**
 * LGA/Ward coverage choropleth coloured by ABSOLUTE-count band (green/yellow/red/grey).
 * Clicking a boundary selects it (detail is shown by the parent). Registered contextual
 * overlays (schools, health facilities, …) render on top — see {@link MapOverlayLayer}.
 */
export function BandChoroplethMap({ data, selectedCode, onSelect, overlays, areaWord = 'LGA', leadOf }: BandChoroplethMapProps) {
  const [hovered, setHovered] = useState<CoverageFeatureProperties | null>(null)

  return (
    <div className={styles.mapFrame}>
      <MapContainer center={[12.2, 9.55]} zoom={8} scrollWheelZoom={false} className={styles.map}>
        <TileLayer url={TILE_URL} attribution="&copy; OpenStreetMap contributors" />
        <GeoJSON
          key={`${data.features.length}:${selectedCode ?? ''}`}
          data={data as unknown as GeoJSON.GeoJsonObject}
          style={(feature) => styleFor(feature?.properties as CoverageFeatureProperties | undefined, selectedCode)}
          onEachFeature={(feature, layer) => {
            const p = feature.properties as CoverageFeatureProperties
            layer.on({
              click: () => onSelect(p.code),
              // The boundary answers the pointer too: without it the card can read as
              // belonging to the neighbour where two LGAs meet under the cursor.
              mouseover: (event) => {
                ;(event.target as Path).setStyle(HOVER_STYLE)
                setHovered(p)
              },
              mouseout: (event) => {
                ;(event.target as Path).setStyle(styleFor(p, selectedCode))
                // Cleared rather than left showing the last area: a card that outlives
                // the pointer attributes figures to nothing in particular.
                setHovered((current) => (current?.code === p.code ? null : current))
              },
            })
          }}
        />
        {overlays.map((o) => (
          <OverlayLayer key={o.id} layer={o} />
        ))}
        <FitBounds data={data} />
      </MapContainer>
      <CoverageHoverCard area={hovered} areaWord={areaWord} lead={hovered && leadOf ? leadOf(hovered) : null} />
    </div>
  )
}
