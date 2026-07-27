import { afterEach, describe, expect, it, vi } from 'vitest'
import { clearMapLayers, getMapLayers, registerMapLayer, unregisterMapLayer } from './mapLayers'
import type { MapOverlayLayer } from './mapLayers'

const schools: MapOverlayLayer = {
  id: 'schools',
  label: 'Schools',
  load: async () => ({ type: 'FeatureCollection', features: [] }),
}

describe('map layer registry', () => {
  afterEach(() => clearMapLayers())

  it('registers a layer and returns it', () => {
    expect(getMapLayers()).toHaveLength(0)
    registerMapLayer(schools)
    expect(getMapLayers().map((l) => l.id)).toEqual(['schools'])
    expect(getMapLayers()[0].label).toBe('Schools')
  })

  it('registering the same id replaces the layer (no duplicates)', () => {
    registerMapLayer(schools)
    registerMapLayer({ ...schools, label: 'Schools (2026)' })
    expect(getMapLayers()).toHaveLength(1)
    expect(getMapLayers()[0].label).toBe('Schools (2026)')
  })

  it('preserves registration order across multiple layers', () => {
    registerMapLayer(schools)
    registerMapLayer({ ...schools, id: 'flood-risk', label: 'Flood risk' })
    expect(getMapLayers().map((l) => l.id)).toEqual(['schools', 'flood-risk'])
  })

  it('unregisters and clears layers', () => {
    registerMapLayer(schools)
    registerMapLayer({ ...schools, id: 'health', label: 'Health facilities' })
    unregisterMapLayer('schools')
    expect(getMapLayers().map((l) => l.id)).toEqual(['health'])
    clearMapLayers()
    expect(getMapLayers()).toHaveLength(0)
  })

  it('exposes a lazily-invoked GeoJSON loader', async () => {
    const load = vi.fn(async () => ({ type: 'FeatureCollection', features: [] }) as GeoJSON.GeoJsonObject)
    registerMapLayer({ id: 'idp-camps', label: 'IDP camps', load })
    expect(load).not.toHaveBeenCalled() // registration must not fetch
    const [layer] = getMapLayers()
    await layer.load()
    expect(load).toHaveBeenCalledOnce()
  })
})
