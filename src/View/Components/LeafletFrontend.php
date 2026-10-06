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

    /**
     * @param  array<int|string, float>  $centerPoint  [lat, lng] or ['lat' => ..., 'lng' => ...]
     * @param  array<int, array<int|string, mixed>>  $markers  Each one [lat, lng] or ['lat' => ..., 'lng' => ..., 'popup' => ...]
     */
    public function __construct(
        array $centerPoint = [],
        array $markers = [],
        public int $zoomLevel = 13,
        public int $maxZoomLevel = 18,
        ?string $tileHost = null,
        ?string $id = null,
        public string $height = '400px',
    ) {
        $this->mapId = $id ?? 'leaflet-'.Str::lower(Str::random(8));
        $this->centerPoint = $centerPoint
            ? $this->toLatLng($centerPoint)
            : array_values(config('backpack.leaflet.default_center'));
        $this->markers = array_map(fn (array $marker) => [
            ...array_combine(['lat', 'lng'], $this->toLatLng($marker)),
            'popup' => isset($marker['popup']) ? e($marker['popup']) : null,
        ], array_values($markers));
        $this->tileLayer = TileLayer::resolve($tileHost);
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
