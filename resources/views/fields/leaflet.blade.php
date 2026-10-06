{{--
    Leaflet map field.

    Recommended: 'name' => 'lat,lng' — the field renders and saves both inputs itself.
    Legacy:      'name' => 'leafletMapId' — the field writes into existing inputs with the
                 ids "leafletMapId-lat" and "leafletMapId-lng" (e.g. hidden fields).
--}}
@php
    $names = explode(',', $field['name']);
    $ownsInputs = count($names) === 2;
    $mapId = 'leaflet-'.\Illuminate\Support\Str::slug(implode('-', $names));

    if ($ownsInputs) {
        [$latName, $lngName] = $names;
        $latValue = old(square_brackets_to_dots($latName)) ?? ($field['value'][0] ?? null);
        $lngValue = old(square_brackets_to_dots($lngName)) ?? ($field['value'][1] ?? null);
        $latSelector = '#'.$mapId.'-lat';
        $lngSelector = '#'.$mapId.'-lng';
    } else {
        $latValue = isset($entry) ? $entry->{config('backpack.leaflet.lat_field', 'lat')} : null;
        $lngValue = isset($entry) ? $entry->{config('backpack.leaflet.lng_field', 'lng')} : null;
        $latSelector = '#'.$names[0].'-lat';
        $lngSelector = '#'.$names[0].'-lng';
    }

    $center = config('backpack.leaflet.default_center');
    $tileLayer = \Siberfx\Leafletjs\Support\TileLayer::resolve($field['options']['provider'] ?? null);
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <div
        class="leaflet-field"
        data-init-function="bpFieldInitLeafletElement"
        data-lat-input="{{ $latSelector }}"
        data-lng-input="{{ $lngSelector }}"
        data-default-lat="{{ $center['lat'] }}"
        data-default-lng="{{ $center['lng'] }}"
        data-zoom="{{ $field['options']['zoom'] ?? config('backpack.leaflet.default_zoom', 14) }}"
        data-tile-layer="{{ json_encode($tileLayer) }}"
        data-marker-image="{{ $field['options']['marker_image'] ?? '' }}"
        data-search="{{ ($field['options']['search'] ?? true) ? 'true' : 'false' }}"
    >
        <div class="leaflet-field-map" style="height: {{ $field['options']['height'] ?? '350px' }}"></div>
    </div>

    @if ($ownsInputs)
        <input type="hidden" id="{{ $mapId }}-lat" name="{{ $latName }}" value="{{ $latValue }}">
        <input type="hidden" id="{{ $mapId }}-lng" name="{{ $lngName }}" value="{{ $lngValue }}">
    @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- FIELD CSS - loaded once per page --}}
@push('crud_fields_styles')
    @basset(config('backpack.leaflet.assets.leaflet_css'))
    @basset(config('backpack.leaflet.assets.geocoder_css'))
    @bassetBlock('siberfx/leafletjs/leaflet-field.css')
    <style>
        .leaflet-field-map {
            width: 100%;
            z-index: 0;
            border-radius: var(--tblr-border-radius, 4px);
        }
    </style>
    @endBassetBlock
@endpush

{{-- FIELD JS - loaded once per page --}}
@push('crud_fields_scripts')
    @basset(config('backpack.leaflet.assets.leaflet_js'))
    @basset(config('backpack.leaflet.assets.geocoder_js'))
    @once
    <script>
        // Basset serves leaflet.css from a local cache, so point the default marker at the CDN images.
        L.Icon.Default.imagePath = @json(config('backpack.leaflet.assets.leaflet_images'));
    </script>
    @endonce
    @bassetBlock('siberfx/leafletjs/leaflet-field.js')
    <script>
        function bpFieldInitLeafletElement(element) {
            const container = element[0];
            const latInput = document.querySelector(element.data('lat-input'));
            const lngInput = document.querySelector(element.data('lng-input'));
            const tileLayer = element.data('tile-layer');
            const markerImage = element.data('marker-image');

            const hasValue = Boolean(latInput?.value && lngInput?.value);
            const start = hasValue
                ? [parseFloat(latInput.value), parseFloat(lngInput.value)]
                : [parseFloat(element.data('default-lat')), parseFloat(element.data('default-lng'))];

            const map = L.map(container.querySelector('.leaflet-field-map'), { scrollWheelZoom: false })
                .setView(start, element.data('zoom'));

            L.tileLayer(tileLayer.url, tileLayer.options).addTo(map);

            const markerOptions = { draggable: true };
            if (markerImage) {
                markerOptions.icon = L.icon({ iconUrl: markerImage, iconSize: [25, 41], iconAnchor: [12, 41] });
            }
            const marker = L.marker(start, markerOptions).addTo(map);

            const setPosition = (latlng) => {
                marker.setLatLng(latlng);
                [[latInput, latlng.lat], [lngInput, latlng.lng]].forEach(([input, value]) => {
                    if (!input) return;
                    input.value = value.toFixed(7);
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            };

            map.on('click', (event) => setPosition(event.latlng));
            marker.on('dragend', () => setPosition(marker.getLatLng()));

            if (element.data('search') && L.Control.geocoder) {
                L.Control.geocoder({ defaultMarkGeocode: false })
                    .on('markgeocode', (event) => {
                        setPosition(event.geocode.center);
                        map.fitBounds(event.geocode.bbox);
                    })
                    .addTo(map);
            }

            // Maps rendered inside hidden tabs/modals need a resize once they become visible.
            new ResizeObserver(() => map.invalidateSize()).observe(container);
        }
    </script>
    @endBassetBlock
@endpush
