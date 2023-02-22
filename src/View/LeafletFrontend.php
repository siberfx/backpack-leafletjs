<?php

namespace Siberfx\Leafletjs\View;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LeafletFrontend extends Component
{
    const DEFAULTMAPID = "defaultMapId";

    public int $zoomLevel;

    public int $maxZoomLevel;

    public array $centerPoint;

    public array $markers;

    public $tileHost;

    public $mapId;

    public function __construct($centerPoint = [0,0], $markers = [], $zoomLevel = 13, $maxZoomLevel = 18, $tileHost = 'mapbox', $id = self::DEFAULTMAPID )
    {
        $this->centerPoint = $centerPoint;
        $this->zoomLevel = $zoomLevel;
        $this->maxZoomLevel = $maxZoomLevel;
        $this->markers = $markers;
        $this->tileHost = $tileHost;
        $this->mapId = $id;
    }

    public function render() : View
    {
        $markerArray = [];

        foreach($this->markers as $marker) {
            $markerArray[] = [implode(",", $marker)];
        }

        return view('components.leaflet-frontend', [
            'centerPoint' => $this->centerPoint,
            'zoomLevel' => $this->zoomLevel,
            'maxZoomLevel' => $this->maxZoomLevel,
            'markers' => $this->markers,
            'markerArray' => $markerArray,
            'tileHost' => $this->tileHost,
            'mapId' => $this->mapId === self::DEFAULTMAPID ? Str::random() : $this->mapId
        ]);
    }
}
