<?php

namespace Siberfx\Leafletjs\Support;

final class TileLayer
{
    /**
     * Resolve a tile provider into the `url` + `options` pair L.tileLayer() expects.
     *
     * Falls back to OpenStreetMap when the provider is unknown or Mapbox has no token.
     *
     * @return array{url: string, options: array<string, mixed>}
     */
    public static function resolve(?string $provider = null): array
    {
        $providers = config('backpack.leaflet.providers', []);
        $provider ??= config('backpack.leaflet.provider', 'openstreetmap');

        if ($provider === 'mapbox' && blank($providers['mapbox']['options']['accessToken'] ?? null)) {
            $provider = 'openstreetmap';
        }

        $layer = $providers[$provider] ?? $providers['openstreetmap'];

        return [
            'url' => $layer['url'],
            'options' => array_filter($layer['options'] ?? [], fn ($value) => $value !== null),
        ];
    }
}
