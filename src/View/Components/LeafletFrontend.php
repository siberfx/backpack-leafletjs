<?php

namespace Siberfx\Leafletjs\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Siberfx\Leafletjs\Support\TileLayer;

class LeafletFrontend extends Component
{
    public string $mapId;

    /** @var array{0: float, 1: float} */
    public array $centerPoint;

    /** @var list<array{lat: float, lng: float, popup: ?string}> */
    public array $markers;

    /** @var array{url: string, options: array<string, mixed>} */
    public array $tileLayer;

    public int $zoomLevel;

    /** True when no center point was given, so the map fits all markers instead. */
    public bool $fitToMarkers;

    /**
     * @param  array<int|string, float>  $centerPoint  [lat, lng] or ['lat' => ..., 'lng' => ...]
     * @param  array<int, array<int|string, mixed>>  $markers  Each one [lat, lng] or ['lat' => ..., 'lng' => ..., 'popup' => ...]
     * @param  ?int  $zoomLevel  Defaults to `backpack.leaflet.default_zoom`.
     * @param  ?string  $tileHost  Deprecated alias of $provider, kept for existing views.
     * @param  ?string  $provider  Tile provider key from `backpack.leaflet.providers`.
     */
    public function __construct(
        array $centerPoint = [],
        array $markers = [],
        ?int $zoomLevel = null,
        public int $maxZoomLevel = 18,
        ?string $tileHost = null,
        ?string $id = null,
        public string $height = '400px',
        ?string $provider = null,
    ) {
        $this->zoomLevel = $zoomLevel ?? (int) config('backpack.leaflet.default_zoom', 14);
        $this->mapId = $id ?? 'leaflet-'.Str::lower(Str::random(8));
        $this->centerPoint = $centerPoint
            ? $this->toLatLng($centerPoint)
            : array_values(config('backpack.leaflet.default_center'));
        $this->markers = array_map(fn (array $marker) => [
            ...array_combine(['lat', 'lng'], $this->toLatLng($marker)),
            'popup' => isset($marker['popup']) ? e($marker['popup']) : null,
        ], array_values($markers));
        $this->fitToMarkers = $centerPoint === [] && $this->markers !== [];
        $this->tileLayer = TileLayer::resolve($provider ?? $tileHost);
        $this->tileLayer['options']['maxZoom'] = $maxZoomLevel;
    }

    public function render(): View
    {
        return view('leafletjs::components.leaflet-frontend');
    }

    /**
     * @param  array<int|string, mixed>  $point
     * @return array{0: float, 1: float}
     */
    private function toLatLng(array $point): array
    {
        return [
            (float) ($point['lat'] ?? $point[0]),
            (float) ($point['lng'] ?? $point[1]),
        ];
    }
}
