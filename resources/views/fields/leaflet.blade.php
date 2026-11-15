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
        // Legacy: the host app owns the inputs, so there is nothing to render or prefill here.
        $latSelector = '#'.$names[0].'-lat';
        $lngSelector = '#'.$names[0].'-lng';
    }

    $center = config('backpack.leaflet.default_center');
    $tileLayer = \Siberfx\Leafletjs\Support\TileLayer::resolve($field['options']['provider'] ?? null);
    $cssUrl = config('backpack.leaflet.assets.leaflet_css');
    $geocoderCssUrl = config('backpack.leaflet.assets.geocoder_css');
    $jsUrl = config('backpack.leaflet.assets.leaflet_js');
    $geocoderJsUrl = config('backpack.leaflet.assets.geocoder_js');
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
        data-clearable="{{ ($field['options']['clearable'] ?? true) ? 'true' : 'false' }}"
        data-clear-label="{{ __('Clear location') }}"
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
    @basset($cssUrl, true, \Siberfx\Leafletjs\Support\AssetAttributes::for($cssUrl))
    @basset($geocoderCssUrl, true, \Siberfx\Leafletjs\Support\AssetAttributes::for($geocoderCssUrl))
    @bassetBlock('siberfx/leafletjs/leaflet-field.css')
    <style>
        .leaflet-field-map {
            width: 100%;
            z-index: 0;
            border-radius: var(--tblr-border-radius, 4px);
        }
        .leaflet-field-clear {
            margin-top: .5rem;
        }
    </style>
    @endBassetBlock
@endpush

{{-- FIELD JS - loaded once per page --}}
@push('crud_fields_scripts')
    @basset($jsUrl, true, \Siberfx\Leafletjs\Support\AssetAttributes::for($jsUrl))
    @basset($geocoderJsUrl, true, \Siberfx\Leafletjs\Support\AssetAttributes::for($geocoderJsUrl))
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
            const sameSpot = (a, b) => a.equals(b, 1e-6);

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

            let marker = null;

            const placeMarker = (latlng) => {
                if (marker) {
                    marker.setLatLng(latlng);
                    return;
                }
                marker = L.marker(latlng, markerOptions).addTo(map);
                marker.on('dragend', () => setPosition(marker.getLatLng()));
            };

            const removeMarker = () => {
                if (!marker) return;
                map.removeLayer(marker);
                marker = null;
            };

            // Writes the inputs and tells listeners (forms, other scripts) about the change.
            const writeInputs = (lat, lng) => {
                [[latInput, lat], [lngInput, lng]].forEach(([input, value]) => {
                    if (!input) return;
                    input.value = value === null ? '' : value.toFixed(7);
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            };

            const setPosition = (latlng) => {
                placeMarker(latlng);
                writeInputs(latlng.lat, latlng.lng);
            };

            const clearPosition = () => {
                removeMarker();
                writeInputs(null, null);
            };

            // Typing into external inputs (legacy mode) moves the marker. Our own writes are
            // ignored because the marker already sits on those coordinates.
            const syncFromInputs = () => {
                const latText = latInput?.value ?? '';
                const lngText = lngInput?.value ?? '';

                if (latText === '' && lngText === '') {
                    removeMarker();
                    return;
                }

                const lat = parseFloat(latText);
                const lng = parseFloat(lngText);
                if (Number.isNaN(lat) || Number.isNaN(lng)) return;

                const latlng = L.latLng(lat, lng);
                if (marker && sameSpot(marker.getLatLng(), latlng)) return;

                placeMarker(latlng);
                map.panTo(latlng);
            };

            [latInput, lngInput].forEach((input) => {
                input?.addEventListener('input', syncFromInputs);
                input?.addEventListener('change', syncFromInputs);
            });

            // Only show a marker for a saved location; an empty field waits for a click.
            if (hasValue) {
                placeMarker(L.latLng(start));
            }

            map.on('click', (event) => setPosition(event.latlng));

            if (element.data('search') && L.Control.geocoder) {
                L.Control.geocoder({ defaultMarkGeocode: false })
                    .on('markgeocode', (event) => {
                        setPosition(event.geocode.center);
                        map.fitBounds(event.geocode.bbox);
                    })
                    .addTo(map);
            }

            if (element.data('clearable')) {
                const clearButton = document.createElement('button');
                clearButton.type = 'button';
                clearButton.className = 'btn btn-sm btn-outline-secondary leaflet-field-clear';
                clearButton.textContent = element.data('clear-label');
                clearButton.addEventListener('click', clearPosition);
                container.appendChild(clearButton);
            }

            // Maps rendered inside hidden tabs/modals need a resize once they become visible.
            new ResizeObserver(() => map.invalidateSize()).observe(container);
        }
    </script>
    @endBassetBlock
@endpush
