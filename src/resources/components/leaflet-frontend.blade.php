@php
    $current_value = old_empty_or_null($field['name'], '') ?? $field['default'] ?? '';

    $mapProvider = $field['options']['provider'] ?? 'mapbox';
    $zoomLevel = 14;

    $mapId = $field['name'];

    $mapMarker = $field['options']['marker_image'] ?? null;

    $entryInstance = new $field['model'];
    $instance = isset($entry)
    ? $entryInstance::whereId($entry->getKey())->first()
    : null;

    $latMarker = isset($entry)
    ? ($instance->lat ?? 53.8965741)
    : 53.8965741;
    $lngMarker = isset($entry)
    ? ($instance->lng ?? 27.547158)
    : 27.547158;

@endphp

@include('crud::fields.inc.wrapper_start')
@include('crud::fields.inc.translatable_icon')

<div class="mapfield">
    <div id="{{ $mapId }}"></div>
    <div class='pointer'></div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
@include('crud::fields.inc.wrapper_end')

@push('crud_fields_styles')
    @loadOnce('css/leaflet.css')
    @loadOnce('leaflet_custom_styles')
    <link rel="stylesheet" type="text/css" href="//cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">

    <style>
        #{{ $mapId }}
        {
            width: 100%;
            height: 300px;
            z-index: 100;
        }

        #mapSearchContainer {
            position: fixed;
            top: 20px;
            right: 40px;
            height: 30px;
            width: 190px;
            z-index: 110;
            font-size: 12pt;
            color: #5d5d5d;
            border: solid 1px #bbb;
            background-color: #f8f8f8;
        }

        .pointer {
            position: absolute;
            top: 86px;
            left: 60px;
            z-index: 99999;
        }
    </style>
    @endLoadOnce
@endpush

@push('crud_fields_scripts')

    @loadOnce('js/leaflet.js')

    @loadOnce('leaflet_custom_scripts')
    <script src="//cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
    <script src="//cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>

    <script>
        // map code here
        let latField = '#{{ $mapId }}-lat',
            lngField = '#{{ $mapId }}-lng',
            defaultZoom = {{ $zoomLevel }},
            defaultLng = '{{ $lngMarker }}',
            defaultLat = '{{ $latMarker }}';

        let map = L.map('{{ $mapId }}', {
            scrollWheelZoom: false
        }).setView([defaultLng, defaultLat], defaultZoom);
        let url = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}';

        var results = new L.LayerGroup().addTo(map);

        results.addLayer(L.marker([defaultLng, defaultLat]));

        L.tileLayer(url, {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: '{{config('siberfx.leaflet.mapbox.access_token', null)}}'
        }).addTo(map);

        var searchControl = new L.esri.Controls.Geosearch().addTo(map);

        searchControl.on('results', function(data) {
            results.clearLayers();
            for (var i = data.results.length - 1; i >= 0; i--) {
                results.addLayer(L.marker(data.results[i].latlng));

                setHiddenFields(data.results[i].latlng.lat, data.results[i].latlng.lng)
            }
        });

        map.on('click', function(e) {
            var popLocation = e.latlng;

            results.clearLayers();
            console.log(popLocation.lat, popLocation.lng)

            results.addLayer(L.marker(popLocation));

            setHiddenFields(popLocation.lat, popLocation.lng)
        });

        setTimeout(function() {
            $('.pointer').fadeOut('slow');
        }, 3400);

        function setHiddenFields(lat, lng) {

            $(latField).val(lng);
            $(lngField).val(lat);
        }
    </script>
    @endLoadOnce

@endpush
