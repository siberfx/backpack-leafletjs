@once
    <link rel="stylesheet" href="{{ config('backpack.leaflet.assets.leaflet_css') }}" {{ new \Illuminate\View\ComponentAttributeBag(\Siberfx\Leafletjs\Support\AssetAttributes::for(config('backpack.leaflet.assets.leaflet_css'))) }}>
    <script src="{{ config('backpack.leaflet.assets.leaflet_js') }}" {{ new \Illuminate\View\ComponentAttributeBag(\Siberfx\Leafletjs\Support\AssetAttributes::for(config('backpack.leaflet.assets.leaflet_js'))) }}></script>
@endonce

<div {{ $attributes->merge(['id' => $mapId, 'class' => 'leaflet-map', 'style' => "height: {$height}; width: 100%;"]) }}></div>

<script>
    (() => {
        const map = L.map(@json($mapId)).setView(@json($centerPoint), {{ $zoomLevel }});

        L.tileLayer(@json($tileLayer['url']), @json($tileLayer['options'])).addTo(map);

        const markers = @json($markers);

        markers.forEach(({ lat, lng, popup }) => {
            const marker = L.marker([lat, lng]).addTo(map);
            if (popup) marker.bindPopup(popup);
        });

        @if ($fitToMarkers)
            if (markers.length) {
                map.fitBounds(markers.map(({ lat, lng }) => [lat, lng]), { padding: [24, 24], maxZoom: {{ $maxZoomLevel }} });
            }
        @endif
    })();
</script>
